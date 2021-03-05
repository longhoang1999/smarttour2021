<!-- <?php
?>  -->
<!DOCTYPE html>
<html>
  <head>
    <title>Waypoints in Directions</title>
    <meta charset="utf-8">   
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s &callback=initMap&libraries=places"   defer></script>
     <!-- AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s -->
     <!-- AIzaSyAxKKPlkGTldh2wdUBvILN6kdFO1lHYSg4 -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/map.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript">
var locationsdata = [],// All data of location 
    timeline = [],
    locatsList = [],//place id of locations 
    locats = [], //LatLng of location
    durOrDis = 1,// 1 duration 0 distance
    markersArray=[],
    isopt = 1, //optimize
    isclick = 1,
    starlocat,// click on map to choose star loaction
    allRoutePosible = [],
    routeOptimized = {
      route: [],
      value: 0
    },
    resmarkers= [],
    polylines = [],
    colorlist = ['#418bca','#00bc8c','#f89a14','#ef6f6c','#5bc0de','#811411'],
    staMarker;// Start location marker

// $(document).ready()
function initMap(){
  $.ajax({
    url:"{{ route('showmap') }}",
    type: 'get',  
    error: (err)=>{
      alert("An error occured: " + err.status + " " + err.statusText);
    },
    success: (result)=>{
      locationsdata =result;
      //add select options to select box
      for(var i = 0; i < locationsdata.length ; i++)
        $("#waypoints").append('<option value="'+
          locationsdata[i].place_id+'">'+locationsdata[i].de_name + '</option>');

      showMap();
    }
  }); 
};

function showMap(){
  // init map and Direction service     
  var map = new google.maps.Map(document.getElementById("map"), {
          zoom: 12.5,
          center: { lat: 21.0226586, lng: 105.8179091 },
        }),
      directionsService = new google.maps.DirectionsService();

    //     directionsRenderer = new google.maps.DirectionsRenderer({
    //         suppressMarkers: true
    //     }); 
    // directionsRenderer.setMap(map);
  
  //Click on map to get start location
  map.addListener('click',function(evt){
    if(isclick == 1){
      if(staMarker == undefined)
        staMarker = new google.maps.Marker({
          label: 'Your start location',
        });
      
      staMarker.setMap(map);
      staMarker.setPosition(evt.latLng);
      starlocat = evt.latLng;
      customLabel(staMarker);
    }
  }); 


  //Reset all html onclick
  document.getElementById("select-box-reset").addEventListener('click',function(){
    //Reset options
    $('.container').empty();
    $('#time').val('07:00');
    $('#is-back').prop('checked', false);
    $('.dur-dis[value="1"]').prop('checked', true);

    // clear and reset map
    for(var i = 0 ; i<markersArray.length;i++)
      markersArray[i].setMap(null);
    
    $('.map-marker-label').remove();
    for(var i=0;i<polylines.length;i++)
      polylines[i].setMap(null);

    for(var i = 0 ; i<resmarkers.length;i++)
      resmarkers[i].setMap(null);
    
    if(staMarker != undefined){
      staMarker.setMap(null); 
      $('.labels').remove();
      staMarker = undefined;
    } 
    map.setZoom(12.5);
    map.setCenter({lat: 21.0226586, lng: 105.8179091});

    //Reset page layout, buttons, tabs
    $('#tab-map').show();
    $('#tab-timeline').hide();
    $('#map').show();
    $('#timeline').hide();
    $('#add-button').show();
    $('#get-route').show();
    tablinks = document.getElementsByClassName('tablinks');
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById('tab-map').className += " active";

    // Reset locations select list
    $("#waypoints").empty();
    for(var i = 0; i < locationsdata.length ; i++)
      $("#waypoints").append('<option value="'+
      locationsdata[i].place_id+'">'
      +locationsdata[i].de_name + '</option>');


    // reset all varibles
    timeline = [];
    locatsList = [];
    locats = [];
    durOrDis = 1;
    markersArray=[];
    isopt = 1;
    isclick = 1;
    starlocat = undefined;
    allRoutePosible = [];
    routeOptimized = {
      route: [],
      value: 0
    };
    polylines = [];
    staMarker = undefined;
    resmarkers = [];
  });


  //add location button
  $("#add-button").click(()=>{ 
    $('#get-route').show();  
    locatsList.push($("#waypoints").find(":selected").val());
    addLoText();
    sortlocats();
    removeOptions();
    if(locatsList.length==5) $("#add-button").hide();//hide add button
  });

  //get route button
  $("#get-route").click(()=>{
      setOptions();
    if(locatsList.length>1){
      if(locatsList.length == 2 || !isopt){
      // if(locatsList.length == 2){
        loader();// turn on loader
        idToData(null,'LatLngArr');
        drawRoutes();
      }else{ 
        (starlocat!= undefined)?processanddrawrouteclient():processanddrawrouteserver();
        // isopt = 0;
      }
      $("#get-route").hide();
      if(locatsList.length==5) $("#add-button").hide();
      $("#tab-timeline").attr('style','display: block');
    } else{
      alert("Please choose at least 2 locations");
    } 
  });

  function setOptions(){
    isclick = 0;
    durOrDis = parseInt($('.dur-dis').filter(":checked").val());
  }

  // add location text list to control panel
  function addLoText(){
    var lotext = $("#waypoints").find(":selected").text();
    var value = $("#waypoints").find(":selected").val();
    $('.container').append(
      '<div class="list-item" value="'+value+'">'+
        '<div class="item-content">'+
         ' <span class="closebtn">&times</span>' +
          '<span class="order">' +( $('.container')[0].childElementCount+1)+'</span>' +' '+lotext+
        '</div>'+
      ' </div>');
    deltext();
  };

  function removeOptions(){// remove location selected
    $("#waypoints").find(":selected").remove();
  };

  function reorderlist(){
    $('.container').empty();
    for(var i= 0; i<locatsList.length;i++){
       $('.container').append(
        '<div class="list-item" value="'+locatsList[i]+'">'+
          '<div class="item-content" style="color: white; background-color: '+colorlist[i%5]+';">'+
           ' <span class="closebtn">&times</span>' +
            '<span class="order">' +(i+1)+'</span>' +' '+idToData(locatsList[i],'text')+
          '</div>'+
        ' </div>');
       deltext();
    }
    sortlocats();
  }

  // Click to delete text list
  function deltext(){
    var closebtn = document.getElementsByClassName("closebtn");
    closebtn[closebtn.length-1].addEventListener("click", function() {
      this.parentElement.parentElement.remove();
      var num = parseInt(this.parentElement.lastElementChild.innerText);
      locatsList.splice(num-1,1);
      sortlocats();
      for(var i=0,a = $(".order"); i<a.length;i++){
        a[i].innerText = i+1;
      }
      value = this.parentElement.parentElement.getAttribute('value');  
 
      $("#waypoints").append('<option value="'+
      value+'">'+idToData(value,'text') + '</option>');

      $('#get-route').show();
      $('#add-button').show();
      // instruction();
    });
  }

  function idToData(id,type){
    if(type == 'text'){
      for(var i=0; i<locationsdata.length; i++)
        if (id == locationsdata[i].place_id)
          return locationsdata[i].de_name;
    }
    if(type == 'LatLng'){
      for(var i=0; i<locationsdata.length; i++)
        if (id == locationsdata[i].place_id)
          return locationsdata[i].location;
    }
    if(type == 'LatLngArr'){
      locats = [];
      for( var j= 0; j<locatsList.length; j++)
        for(var i=0; i<locationsdata.length; i++){
          if (locatsList[j] == locationsdata[i].place_id)
          locats.push(locationsdata[i].location);
        }
      if(starlocat != undefined)  locats.unshift(starlocat);
      if($('#is-back').is(':checked')) locats.push(locats[0]);
    }
    if(type == 'duration'){
      for(var i=0; i<locationsdata.length; i++)
        if (id == locationsdata[i].place_id)
          return locationsdata[i].de_duration;
    }
    if(type == 'link'){
      for(var i=0; i<locationsdata.length; i++)
        if (id == locationsdata[i].place_id)
          return locationsdata[i].de_link;
    }
    if(type == 'description'){
      for(var i=0; i<locationsdata.length; i++)
        if (id == locationsdata[i].place_id)
          return locationsdata[i].de_description;
    }
  }

  function processanddrawrouteserver(){
    loader();
    $.ajax({
      url:"{{ route('processroute') }}?index="+ durOrDis,
      type: 'get', 
      data: {data: locatsList}, 
      error: (err)=>{
        alert("An error occured: " + err.status + " " + err.statusText);
      },
      success: (result)=>{      
        locatsList = result;
        idToData(null,'LatLngArr');
        drawRoutes();
      }
    });
  }

  function loader(){
    if($('#overlay').css("display") == 'none'){
      $('#overlay').css('display', 'block');
    } else{
       $('#overlay').css('display', 'none');
    }
  }

  //Create marker and event
  function addMarkers(id,index){
      var icon = {
            path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
            fillColor: colorlist[index%5],
            fillOpacity: 1,
            strokeColor: 'white',
            strokeWeight: 3,
            scale: 1.4,
          },
          label = {
            text: idToData(id,'text'),
            color: colorlist[index%5],
            fontWeight: 'bold'  
          };

      var marker = new google.maps.Marker({
          map: map,
          position: idToData(id,'LatLng'),
          label: label,
          icon: icon
      });

      var content = '<p><h4>'+idToData(id,'text')+'</h4></p>'+ 
          '<p><a href="'+idToData(id,'link')+'"target="_blank">Click to view tour</a></p>',
          infowindow = new google.maps.InfoWindow({
            content: content,
          });

      marker.addListener('click',()=>{
        marker.setIcon("{{asset('uploads/imgs/icon.jpg')}}");
        marker.setLabel('');
        infowindow.open(map, marker);
      });

      infowindow.addListener('closeclick',()=>{
        marker.setIcon(icon);
        marker.setMap(map);
        marker.setLabel(label);
      });

      markersArray.push(marker);
  }

  //Draw marker on map
  function markersOnMap(){  
    //clear marker 
    for(var i =0 ; i<markersArray.length;i++){
      markersArray[i].setMap(null);
    }
    markersArray = []; 

    //create new marker
    for(var i = 0; i<locatsList.length;i++){
      addMarkers(locatsList[i],i);
    }
  }

  function drawRoutes(){
    reorderlist();
    markersOnMap();
    var waypts = [];
    for(var i=1; i<locats.length; i++)
      waypts.push({
        location: locats[i],
        stopover: true
      });
    directionsService.route({
        origin: locats[0],
        destination: locats[locats.length-1],
        waypoints: waypts,
        travelMode: google.maps.TravelMode.DRIVING,
    },customDirectionsRenderer);
  }

  function customDirectionsRenderer(response, status) {
    loader();// turn off loader
    var bounds = new google.maps.LatLngBounds();
    var legs = response.routes[0].legs;
    for(var i=0;i<polylines.length;i++){
      polylines[i].setMap(null);
    }

    for (i = 0; i < legs.length; i++) {
      (i>=5&&i%5 == 0)?index = 4:((starlocat != undefined)?index = (i%5)-1:index = (i%5));
      if(starlocat != undefined && i==0) index = 5;
       var polyline = new google.maps.Polyline({
        map:map, 
        path:[], 
        strokeColor: colorlist[index],
        strokeOpacity: 0.7,
        strokeWeight: 5});
      var steps = legs[i].steps;
      for (j = 0; j < steps.length; j++) {
        var nextSegment = steps[j].path;
        for (k = 0; k < nextSegment.length; k++) {
          polyline.getPath().push(nextSegment[k]);
          bounds.extend(nextSegment[k]);
        }
      }
      polylines.push(polyline);
    }
    map.fitBounds(bounds);
    getandsettimeline(response.routes[0].legs);
  };

  function getandsettimeline(response){
    // $.ajax({
    //   url: '{{ route("gettimeline") }}?time='+$('#time').val(),
    //   type: 'get',
    //   data:{ data:locatsList},
    //   error: (err)=>{
    //     alert("An error occured: " + err.status + " " + err.statusText);
    //   },
    //   success: (result)=>{
    //     timeline = [];
    //     for(var i =0 ;i<result.length;i++){
    //       timeline.push(converttime(result[i]));
    //     } 
    //     settimeline();
    //   }
    // });
    timeline = [];
    timeline.push($('#time').val());
    var tmp = converttime($('#time').val());

    for(var i = 0; i < response.length-1 ; i++){
      if(starlocat == undefined){
        tmp += idToData(locatsList[i],'duration');
        timeline.push(converttime(tmp));
      } else if(i >=1){
         tmp += idToData(locatsList[i-1],'duration');
        timeline.push(converttime(tmp));
      }
      
      tmp += response[i].duration.value;
      timeline.push(converttime(tmp));
    }

    if(!$('#is-back').is(':checked')){
      tmp += idToData(locatsList[locatsList.length-1],'duration');
      timeline.push(converttime(tmp));
    }

    // if($('#time-end').val() != null)

    settimeline();
  }

  function settimeline(){
    var icon =  Array.from(document.querySelectorAll(".timeline-badge")),
        title = Array.from(document.querySelectorAll(".timeline-title"));
        body = Array.from(document.querySelectorAll(".timeline-body")); 
        heading = Array.from(document.querySelectorAll(".restaurant-find"));
        li = $('.timeline').children();

    //clear timeline
    $('.fa-cutlery').remove();
    for (var j = 0; j < icon.length; j++) {
      li[j].style.display='block';
      $(body[j]).empty();
      $(heading[j]).empty();
      icon[j].innerText = '';
      title[j].innerText = '';
    }

    for(var i=0; i<timeline.length;i++)
     icon[i].innerText = timeline[i]; 
    i=0;
    if(starlocat!= undefined){
      i=1;
      title[0].innerText = "Your location";
      $(title[0]).css('color','#ea4335');
      $(body[0]).append(
        '<p> Start the tour at your location at '+timeline[0] +'</p>'
      );
      if($('#is-back').is(':checked')){
        title[timeline.length-1 ].innerText = "Your location";
        $(body[timeline.length-1]).append(
        '<p>Come back to your location.</p>');
      } else {
        $(body[timeline.length-1]).append(
        '<p> Finish the tour at '+ idToData(locatsList[locatsList.length-1],'text') +' at '+timeline[timeline.length-1]+'</p>'
        );
        
      }
    } else{
      $(body[0]).append(
        '<p> Start the tour at '+idToData(locatsList[0],'text')+' at '+timeline[0] +' and visit in '+converttime(idToData(locatsList[0],'duration'))+
        '</p><br><p>'+idToData(locatsList[0],'description')+'</p><p><a href="'+idToData(locatsList[0],'link')+'"target="_blank">View the location</a></p><div class="show-more">Show more <i class="fa fa-chevron-down" aria-hidden="true"></i></div>'
      ); 
      $(heading[0]).append('<a href="#" class="fa fa-cutlery" value="'+0+'"></a><div class="restaurant-select"><div>Select number of restaurants</div><select><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select></div>');

      if($('#is-back').is(':checked')){
        title[timeline.length-1].innerText = idToData(locatsList[0],'text');
        $(body[timeline.length-1]).append(
            '<p>Come back to '+idToData(locatsList[0],'text')+' at '+timeline[timeline.length-1]+'</p>'
        );
      } else {
        $(body[timeline.length-1]).append(
          '<p> Finish the tour at '+ idToData(locatsList[locatsList.length-1],'text') +' at '+timeline[timeline.length-1]+'</p>'
        );
      }
    }

    j=0;
    for(; i<timeline.length;i+=2){
      if(i+1<timeline.length){
        title[i].innerText = idToData(locatsList[j],'text');
      // title[i+1].innerText = idToData(locatsList[j],'text');
        title[i+1].innerText = "Travel to the next location";
        $(title[i+1]).css('color','red');
      }
      
      if(i>0&&i<timeline.length-1){
        $(heading[i]).append('<a href="#" class="fa fa-cutlery" value="'+j+'"></a><div class="restaurant-select"><div>Select number of restaurants</div><select><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select></div>');
        $(body[i]).append('<p>Arrive '+ idToData(locatsList[j],'text') +' at '+timeline[i]+' and visit in '+ converttime(idToData(locatsList[j],'duration'))+'</p><br><p>'+idToData(locatsList[j],'description')+'</p><p><a href="'+idToData(locatsList[j],'link')+'" target="_blank">View the location</a></p><div class="show-more">Show more <i class="fa fa-chevron-down" aria-hidden="true"></i></div>');
      }
      if(i<timeline.length-2){
        $(body[i+1]).append('<p>Complete the visit to '+idToData(locatsList[j],'text')+' at '+timeline[i+1]+' and go to the next destination</p>');
      }
      j++;
    }

    var showmore = document.getElementsByClassName("show-more");
    for(i= 0 ; i<showmore.length;i++){
      showmore[i].addEventListener('click',function(){
        if(this.innerText=="Show more "){
          this.parentElement.parentElement.setAttribute('style','max-height: none');
          this.innerHTML = 'Hide <i class="fa fa-chevron-up" aria-hidden="true"></i>';
        } else {
          this.parentElement.parentElement.setAttribute('style','max-height: 174px'); 
          this.innerHTML = 'Show more <i class="fa fa-chevron-down" aria-hidden="true"></i>';
        }
      });
    }
    
    for(i= timeline.length; i<li.length;i++)
      li[i].style.display = "none"; 
    findres();
  }

  function findres(){
    fa = document.querySelectorAll('.fa-cutlery');
    for( var i =0;i<fa.length;i++)
      fa[i].addEventListener('click',function(){
        var length = $(this.nextSibling.lastChild).find(":selected").val();
        var val = parseInt(this.getAttribute('value'));
        $('#map').css('display','block');
        $('#timeline').css('display','none');
        // $('#tab-map').css('color','#1a73e8');
        
        // Change color of map and timeline button
        tablinks = document.getElementsByClassName('tablinks');
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById('tab-map').className += " active";

        
        var place = new google.maps.places.PlacesService(map);
        // console.log(idToData(locatsList[val],'LatLng'));
        place.nearbySearch({
          location: idToData(locatsList[val],'LatLng'),
          radius: '500',
          type: 'restaurant'
        }, (response, status) => {
          // console.log(response);
          if(response.length < length) 
            length = response.length;

          for (let i = 0; i <length; i++) {
            resMarker(response[i]);
          }
          map.setCenter(idToData(locatsList[val],'LatLng'));
          map.setZoom(18);
        });
      });  
  }
  function resMarker(place){
    var resMarker = new google.maps.Marker({
      map,
      position: place.geometry.location,
      label: place.name
    });
    resmarkers.push(resMarker);
    customLabel(resMarker);

  }
  // convert time in seconds and HH:MM format
  function converttime(time){
    if(typeof(time) == "number"){
      var hours = Math.floor(time / 3600);
      time %= 3600;
      var minutes = Math.floor(time / 60);
      minutes = String(minutes).padStart(2, "0");
      hours = String(hours).padStart(2, "0");
      time = hours + ":" + minutes;
    } else{
      var a = time.split(':'); 
      var time = (+a[0]) * 60 * 60 + (+a[1]) * 60; 
    }
    return time;
  }

// update the path data to database
   // function updpath(data){
   //  var dataobj = [];
   //  for(var i = 0; i < data.length; i++)
   //    for(var j = 0; j < data.length; j++)
   //      if(j!=i){
   //        dataobj.push({
   //          pa_de_start: locatsList[i],
   //          pa_de_end: locatsList[j],
   //          pa_distance: data[i].elements[j].distance.value,
   //          pa_duration: data[i].elements[j].duration.value
   //        });
   //      }

   //  $.ajax({
   //    url: '{{ route("updpath") }}',
   //    type: 'get',
   //    data: {data: dataobj},
   //     error: (err)=>{
   //      alert("An error occured: " + err.status + " " + err.statusText);
   //    },
   //    success: (result)=>{
   //    }
   //  });
   // }

  function distanceRequest(theFunction){
    var geocoder = new google.maps.Geocoder();
    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
      origins: locats,
      destinations: locats,
      travelMode: google.maps.TravelMode.DRIVING,
    },theFunction);
    // },(response, status) => { 
      // updpath(response.rows);
      // console.log(response.rows);
      // directobject = response;
      // const originList = response.originAddresses;
      // const destinationList = response.destinationAddresses;
      // Add text of addreses ,time travel, distance between
      //  each location to panel
      // $("#directions-panel").append('__________________________<br>');
      //  $("#directions-panel").append('We calculated the best way for you<br>');
      // for (let i = 0; i < locatsList.length-1; i++) {
      //   const results = response.rows[i].elements;
      //     $("#directions-panel").append("- "+originList[i] +
      //       " to " + destinationList[i+1] + ": " +
      //       results[i+1].distance.text +
      //       " in " + results[i+1].duration.text +"<br>");
      //       travelTimes(i,results[i+1].duration.text);
      // }
      // });
  }

  function processanddrawrouteclient(){
    loader();
    idToData(null,'LatLngArr');
    var Arr = [];
    //init array [1,2,3,4,...n] with n is number of locations
    for(var i = 0;i < locatsList.length;i++)
      Arr[i] = i+1;    
    /*swaps the positions of the elements 
      in the array to create all possible paths*/
    arrPermutations(Arr.length,Arr); 
    // add 0 to first element [0,1,2,...];
    for(var i =0; i< allRoutePosible.length;i++){
      allRoutePosible[i].unshift(0);
      if($('#is-back').is(':checked')) allRoutePosible[i].push(0);
    }
    distanceRequest(bestWay);
  }

  function arrPermutations(n,Arr){ 
    if (n == 1){
     allRoutePosible.push(Arr.slice());
    } else {
      for(var i = 0; i <= n-1; i++) {
        arrPermutations(n-1, Arr);
        swapArrEle(n % 2 == 0 ? i : 0 ,n-1,Arr);
      }
    }
  }

  // Swap elements of array
  function swapArrEle(a,b,Arr){
    var tmp = Arr[a];
    Arr[a] = Arr[b];
    Arr[b] = tmp;
  }

  function bestWay(response,status){  
    var totaldistance = 0,
        totaltime = 0;
    var total = 0;
  
    // Loop all route posible to calculate the best way 
    for(var i = 0 ;i<allRoutePosible.length; i++){
      var A = allRoutePosible[i];
      if(durOrDis){
        total += response.rows[A[0]].elements[A[1]].duration.value;
        for(var j = 1 ;j<A.length-1; j++){
            total+= idToData(locatsList[A[j]-1],'duration');
            total += response.rows[A[j]].elements[A[j+1]].duration.value;
        }
        if(!$('#is-back').is(':checked')) 
          total+= idToData(locatsList[locatsList.length-1],'duration');

        if (routeOptimized.value == 0){ 
          routeOptimized.route = A;
          routeOptimized.value = total;
        }
        if(total < routeOptimized.value){
          routeOptimized.route = A;
          routeOptimized.value = total;
        }
        total = 0;
      } else {
        for(var j = 0 ;j<A.length-1; j++)
          total += response.rows[A[j]].elements[A[j+1]].distance.value;

        if (routeOptimized.value == 0){ 
          routeOptimized.route = A;
          routeOptimized.value = total;
        }
        if(total < routeOptimized.value){
          routeOptimized.route = A;
          routeOptimized.value = total;

        }
        total = 0;
      }
    }
    var tmp = [];
    routeOptimized.route.splice(0,1);

    if($('#is-back').is(':checked')) 
      routeOptimized.route.splice(locats.length-1,1)

    // convert intenger array to waypoinits location
    for(var i=0;i<locatsList.length;i++)
      tmp[i] = locatsList[routeOptimized.route[i]-1];
    locatsList = tmp;
    // timeline = routeOptimized.timeline;
    idToData(null,'LatLngArr');
    drawRoutes();
  }


function sortlocats(){// Sortable location list text
  var rowSize = 50; // => container height / number of items
  var container = document.querySelector(".container");
  var listItems = Array.from(document.querySelectorAll(".list-item")); // Array of elements
  // if(listItems.ondragexit == true){
    
  // }
  
  var sortables = listItems.map(Sortable); // Array of sortables
  var total = sortables.length;
  TweenLite.to(container, 0.5, { autoAlpha: 1 });
  function changeIndex(item, to) {
    // Change position in array
    // if($("#get-route").is(":hidden")){
    //   $('#opt').prop('checked', false);
    // }
    arrayMove(sortables, item.index, to);

    // Set index for each sortable
    sortables.forEach((sortable, index) => sortable.setIndex(index));
    var tmp = [];
     for(var i = 0; i < sortables.length;i++){
      tmp.push(sortables[i].element.getAttribute('value'));
    }
    locatsList = tmp;
    idToData(null,'LatLngArr');
    $("#get-route").show();
  }

  function Sortable(element, index){
    var content = element.querySelector(".item-content");
    var order = element.querySelector(".order");
    var animation = TweenLite.to(content, 0.3, {
      boxShadow: "rgba(0,0,0,0.2) 0px 16px 32px 0px",
      force3D: true,
      scale: 1.1,
      paused: true });
    var dragger = new Draggable(element, {
      onDragStart: downAction,
      onRelease: upAction,
      onDrag: dragAction,
      cursor: "inherit",
      type: "y" });
    // Public properties and methods
    var sortable = {
      dragger: dragger,
      element: element,
      index: index,
      setIndex: setIndex };
    // TweenLite.set(element, { y: index * rowSize });
    TweenLite.set(element, { y: index * rowSize });
    function setIndex(index) {
      sortable.index = index;
      order.textContent = index + 1;
      // Don't layout if you're dragging
      if (!dragger.isDragging) layout();
    }
    function downAction() {
      animation.play();
      this.update();
    }
    function dragAction() {
      // Calculate the current inheritdex based on element's position
      var index = clamp(Math.round(this.y / rowSize), 0, total - 1);
      if (index !== sortable.index) {
        changeIndex(sortable, index);
      }
    }
    function upAction() {
      animation.reverse();
      layout();
    }
    function layout() {
      TweenLite.to(element, 0.3, { y: sortable.index * rowSize });
    }
    return sortable;
  }

  // Changes an elements's position in array
  function arrayMove(array, from, to) {
    array.splice(to, 0, array.splice(from, 1)[0]);
  }

  // Clamps a value to a min/max
  function clamp(value, a, b) {
    return value < a ? a : value > b ? b : value;
  }
}

// google.maps.Marker.prototype.setLabel = 
function customLabel(marker) {
  // console.log(this);
  var label = marker.label;
  marker.label = new MarkerLabel({
    map: marker.map,
    marker: marker,
    text: label
  });
  marker.label.bindTo('position', marker, 'position');
  marker.setLabel('');
};

  var MarkerLabel = function(options) {
    this.setValues(options);
    this.span = document.createElement('span');
    this.span.className = 'map-marker-label';
  };

  MarkerLabel.prototype = $.extend(new google.maps.OverlayView(), {
    onAdd: function() {
      this.getPanes().overlayImage.appendChild(this.span);
      var self = this;
      this.listeners = [
        google.maps.event.addListener(this, 'position_changed', function() {
          self.draw();
        })
      ];
    },
    draw: function() {
      var markerSize = {
        x: 22,
        y: 40
      };
      var text = String(this.get('text'));
      // var color = String(this.get('text'));
      var position = this.getProjection().fromLatLngToDivPixel(this.get('position'));
      this.span.innerHTML = text;
      // this.span.setAttribute('color',color);
      this.span.style.left = (position.x - (markerSize.x / 2)) - (text.length * 3) + 10 + 'px';
      this.span.style.top = (position.y - markerSize.y + 40) + 'px';
    }
  });

};
</script> 
  </head>
  <body>
    <div id="overlay">
      <div class="loader"></div>
    </div> 
    <div id="map" class="tabcontent" ></div>
    <!--timeline-->
    <div id="timeline" class="tabcontent" style="display: none;" >
      <div class="row">
        <ul class="timeline">
          <li>
            <div class="timeline-badge" >
            </div>
            <div class="timeline-panel" >
              <div class="timeline-heading">
                <h4 class="timeline-title">Your location</h4>
              </div>
              <div class="restaurant-find"> 
                <!-- <a href="#" class="fa fa-cutlery" value="'+j+'">
                </a>
                <div class="restaurant-select">
                  <div>Select number of restaurants</div>
                <select>
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="20">20</option>
                  <option value="25">25</option>
                  <option value="30">30</option>
                </select>
                </div> -->
              </div>
              <div class="timeline-body">
                 <!--  <p> Start the tour at '+idToData(locatsList[0],'text')+' at '+timeline[0] +' and visit in '+converttime(idToData(locatsList[0],'duration'))+
                  '</p>
                  <p>Chùa Một Cột có tên ban đầu là Liên Hoa Đài có tức là Đài Hoa Sen với lối kiến trúc độc đáo: một điện thờ đặt trên một cột trụ duy nhất. Liên Hoa Đài là công trình nổi tiếng nhất nằm trong quần thể kiến trúc Chùa Diên Hựu (延祐寺), có nghĩa là ngôi chùa "Phúc lành dài lâu". Công trình Chùa Diên Hựu nguyên bản được xây vào thời vua Lý Thái Tông mùa đông năm 1049 và hoàn thiện vào năm 1105 thời vua Lý Thánh Tông nay đã không còn. Công trình Liên Hoa Đài hiện tại nằm ở Hà Nội là một phiên bản được chỉnh sửa nhiều lần qua các thời kỳ, bị Pháp phá huỷ khi rút khỏi Hà Nội ngày 11/9/1954 và được dựng lại năm 1955 bởi kiến trúc sư Nguyễn Bá Lăng theo kiến trúc để lại từ thời Nguyễn. Đây là ngôi chùa có kiến trúc độc đáo ở Việt Nam. Chùa Một Cột đã được chọn làm một trong những biểu tượng của thủ đô Hà Nội, ngoài ra biểu tượng chùa Một Cột còn được thấy ở mặt sau đồng tiền kim loại 5000 đồng của Việt Nam. Tại quận Thủ Đức, Thành phố Hồ Chí Minh cũng có một phiên bản chùa Một Cột. Ngoài ra, tại thủ đô Moskva của Nga cũng có một phiên bản chùa Một Cột được xây lắp tại Tổ hợp Trung tâm Văn hóa - Thương mại và Khách sạn "Hà Nội - Matxcova".Chùa còn là biểu tượng cao quý thoát tục của con người Việt Nam. </p>
              </div>
              <div class="show-more">
                Show more <i class="fa fa-chevron-down" aria-hidden="true"></i>
              </div> -->
            </div>
          </li>
          <li class="timeline-inverted">
            <div class="timeline-badge danger">
            </div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                    <h4 class="timeline-title"></h4>
                </div>
                <div class="restaurant-find"></div>
                <div class="timeline-body">
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge info">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge  ">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge primary">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge danger">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge info">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge  ">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
                </div>
            </li>
            <li>
              <div class="timeline-badge primary">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge danger">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge info">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge  ">
              </div>
              <div class="timeline-panel">
                  <div class="timeline-heading">
                      <h4 class="timeline-title"></h4>
                  </div>
                  <div class="restaurant-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
        </ul>
      </div>
    </div>
    <!--timeline ends-->
    <!-- control panel -->
    <div id="search-panel" class="control-panel">
        <div id="search-panel-control">
          <div id="select-box">
            <i id="drop-down-arow" class="fa fa-angle-down " aria-hidden="true"></i> 
            <select id="waypoints">
            </select>
            
            <div id="select-box-reset">
              <i class="fa fa-undo fa-lg" aria-hidden="true"></i>
            </div>
          </div>
          <button id="add-button">Add location</button>
          <button id="get-route">Get routes</button>
        </div>
        <div class="container">  
        </div>
      </div>

      <div id="options-control" class="control-panel">
        <div id="options-control-title"><b>Select tour options</b></div>
        <div class="options-list options-list1">
          <div><b>Select the start time:</b></div>
          <input type="time" id="time" value="07:00" style="width: 100%;">
        </div>
        <div class="options-list">
          <div><b>Select the end time:</b></div>
          <input type="time" id="time-end" value="" style="width: 100%;">
        </div>
        <div class="options-list">
          <input type="checkbox" id='is-back'> Come back the start?
        </div>
        <div class="options-list">
          <div><b>Optimized by</b></div>
          <input type="radio" class="dur-dis" name="durdis" value="1" checked> Duration   
          <input type="radio" class="dur-dis" name="durdis" value="0"> Cost
        </div>
        <!-- <button class="slider-button slider-button-left" onclick="plusDivs(-1)">
          &#10094;
        </button>
        <button class="slider-button slider-button-right" onclick="plusDivs(1)">
          &#10095;
        </button> -->
      </div>
      <div id="switch-tab" class="control-panel">
        <button class="tablinks active" onclick="openTab(event, 'map')"  id="tab-map">Map</button>
        <button class="tablinks" id="tab-timeline" style="display: none;" onclick="openTab(event, 'timeline')">Timeline</button>
      </div>
        
    <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/utils/Draggable.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/TweenMax.min.js'></script>

<script type="text/javascript">
// Route options slider
// var slideIndex = 1;
// showDivs(slideIndex);
// function plusDivs(n) {
//   showDivs(slideIndex += n);
// }
// function showDivs(n) {
//   var i;
//   var x = document.getElementsByClassName("options-list");
//   if (n > x.length) {slideIndex = 1}
//   if (n < 1) {slideIndex = x.length}
//   for (i = 0; i < x.length; i++) {
//      x[i].style.display = "none";  
//   }
//   x[slideIndex-1].style.display = "block";  
// }
//end slider

// switch map and timeline tab    
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
//End switch tab

</script>

  </body>
</html>


