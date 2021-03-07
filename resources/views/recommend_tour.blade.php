<!-- <?php
?>  -->
<!-- .fa-utensils -->
<!DOCTYPE html>
<html>
  <head>
    <title>Waypoints in Directions</title>
    <meta charset="utf-8">   
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap&libraries=places"   defer></script>
     <!-- AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s -->
     <!-- AIzaSyAxKKPlkGTldh2wdUBvILN6kdFO1lHYSg4 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/map.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
var locationsdata = [],// All data of location 
    timeline = [],
    locatsList = [],//place id of locations 
    locats = [], //LatLng of location
    // durOrDis = 1,// 1 duration 0 distance
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
    dello = 0,
    disresponse,
    choosendur = 0,
    staMarker;// Start location marker
const colorlist = ['#418bca','#00bc8c','#f89a14','#ef6f6c','#5bc0de','#811411']
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
  
  //Click on map to get start location\

  $('#your-start-close').click(()=>{
    staMarker.setMap(null);
    staMarker = undefined;
    $('.map-marker-label').remove();
    $('#your-start').hide();
    starlocat = undefined;
    updateRoute();
  })
  
  map.addListener('click',function(evt){
    if(isclick == 1){
      $('#your-start').show();
      if(staMarker != undefined) staMarker.setMap(null);
        $('.map-marker-label').remove();
        staMarker = new google.maps.Marker({
            label: 'Your start location',
        });

      staMarker.setMap(map);
      staMarker.setPosition(evt.latLng);
      starlocat = evt.latLng.toJSON();
      console.log(starlocat);
      customLabel(staMarker);
    } else {
      $('#clickWarning').modal('show')
      $('#change-click').click(()=>{ 
        $('#clickWarning').modal('hide')
        isclick = 1;
        updateRoute();
      });
      
    }
  });

  if(!$('#is-opt').is(':checked')){
    $('#is-opt-sub').hide();
    isopt = 0;
  } 

  $('#is-opt').click(()=>{
    if(!$('#is-opt').is(':checked')){
      $('#is-opt-sub').hide();
      isopt = 0;
    } else {
      $('#is-opt-sub').show();
      isopt = parseInt($('.dur-dis').filter(":checked").val());
    }
  });

  //Reset all html onclick
  document.querySelectorAll(".reset-all").forEach(ele=>{
    ele.addEventListener('click',function(){
    $("#get-route").text("Let's go");
    $("#saveTour").css("display","none");
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
    $('#your-start').hide();
    tablinks = document.getElementsByClassName('tablinks');
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById('tab-map').className += " active";
    $('.options-list').show();

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
    // durOrDis = 1;
    markersArray=[];
    isopt = 1;
    isclick = 1;
    starlocat = undefined;
    allRoutePosible = [];
    routeOptimized = {
      route: [],
      value: 0
    }; 
    resmarkers = [];
    polylines = [];
    dello = 0;
    disresponse = undefined;
    choosendur = 0;
    staMarker = undefined;
  
  });
  })
  

  //add location button
  $("#add-button").click(()=>{ 
    updateRoute();
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
      if(!isopt){
      // if(locatsList.length == 2){
        loader();// turn on loader
        idToData(null,'LatLngArr');
        drawRoutes();
      }else{
        // timealert();
        (starlocat!= undefined)?processanddrawrouteclient():processanddrawrouteserver();
        // isopt = 0;
        
      }
      $("#get-route").hide();
      if(locatsList.length==5) $("#add-button").hide();
      $("#tab-timeline").attr('style','display: block');
    } else{
      alert("Please choose at least 2 locations");
    } 
    $("#saveTour").css("display","block");
  });
  
  $('#is-back').click(updateRoute);
  $('#is-opt').click(updateRoute);
  $('.dur-dis').click(updateRoute);
  $('#time').change(updateRoute);
  $('#time-end').change(updateRoute);
  function updateRoute(){
    if(markersArray.length){
      $("#get-route").show();
      $("#get-route").text('Update');
    }
  }


  function setOptions(){
    isclick = 0;
    if($('#is-opt').is(':checked')){
      isopt = parseInt($('.dur-dis').filter(":checked").val());
    } else {
      isopt = 0;
    }
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
      // if(disresponse != undefined && closebtn.length == 2){
      //   alert("You can't delete any more locations.");
      // } else {
        this.parentElement.parentElement.remove();
        var num = parseInt(this.parentElement.lastElementChild.innerText);
        locatsList.splice(num-1,1);
        updateRoute(); 
        sortlocats();
        for(var i=0,a = $(".order"); i<a.length;i++){
          a[i].innerText = i+1;
        }
        value = this.parentElement.parentElement.getAttribute('value');  
   
        $("#waypoints").append('<option value="'+
        value+'">'+idToData(value,'text') + '</option>');

        $('#get-route').show();
        $('#add-button').show();
      // }
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
    var data =  { 
      locatsList: locatsList, 
      durordis: isopt,
      choosendur: converttime($('#time-end').val()) - 
      converttime($('#time').val()),
      isback: ($('#is-back').is(':checked') === true),
      dello: dello
    };
    $.ajax({
      // url:"{{ route('processroute') }}?index="+ durOrDis,
      url:"{{ route('processroute') }}",
      type: 'get', 
      // data: {data: locatsList}, 
      data: data, 
      error: (err)=>{
        alert("An error occured: " + err.status + " " + err.statusText);
      },
      success: (result)=>{    
        if(typeof(result) =='string'){
          timealert(parseInt(result));
        } else {
          // loader();
          locatsList = result;
          idToData(null,'LatLngArr');
          drawRoutes();
        } 
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
        marker.setIcon("{{asset('imgs/icon.jpg')}}");
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
    for(var i=0; i<locats.length; i++)
    {
      locats[i].lat = parseFloat(locats[i].lat);
      locats[i].lng = parseFloat(locats[i].lng);
    }
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
        heading = Array.from(document.querySelectorAll(".nearby-find"));
        li = $('.timeline').children();

    //clear timeline
    $('.nearby-find').empty();
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
      title[0].innerText = '{{ trans("messages.YourLo") }}';
      $(title[0]).css('color','#ea4335');
      $(body[0]).append(
        '<p> {{ trans("messages.Startthetourat") }} {{ trans("messages.yourLo") }} {{ trans("messages.At") }} '+timeline[0] +'</p>'
      );
      if($('#is-back').is(':checked')){
        title[timeline.length-1 ].innerText = '{{ trans("messages.YourLo") }}';
        $(body[timeline.length-1]).append(
        '<p>{{ trans("messages.CmBck") }} {{ trans("messages.yourLo") }}.</p>');
      } else {
        $(body[timeline.length-1]).append(
        '<p> {{ trans("messages.Finish") }} '+ idToData(locatsList[locatsList.length-1],'text') +' {{ trans("messages.At") }} '+timeline[timeline.length-1]+'</p>'
        );
        
      }
    } else{
      $(body[0]).append(
        '<p> {{ trans("messages.Startthetourat") }} '+idToData(locatsList[0],'text')+' {{ trans("messages.At") }} '+timeline[0] +' {{ trans("messages.VisIn") }} '+converttime(idToData(locatsList[0],'duration'))+
        '</p><br><p>'+idToData(locatsList[0],'description')+'</p><p><a href="'+idToData(locatsList[0],'link')+'"target="_blank">View the location</a></p><div class="show-more">{{ trans("messages.Showmore") }} <i class="fa fa-chevron-down" aria-hidden="true"></i></div>'
      ); 
      $(heading[0]).append('<div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-utensils" value="'+0+'"></i></div><span class="nearby-find-text">Restaurant</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-store" value="'+0+'"></i></div><span class="nearby-find-text">Store</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-coffee" value="'+0+'"></i></div><span class="nearby-find-text">Coffe Store</span></div>');


      if($('#is-back').is(':checked')){
        title[timeline.length-1].innerText = idToData(locatsList[0],'text');
        $(body[timeline.length-1]).append(
            '<p>{{ trans("messages.CmBck") }} '+idToData(locatsList[0],'text')+' {{ trans("messages.At") }} '+timeline[timeline.length-1]+'</p>'
        );
      } else {
        $(body[timeline.length-1]).append(
          '<p> {{ trans("messages.Finish") }} '+ idToData(locatsList[locatsList.length-1],'text') +' {{ trans("messages.At") }} '+timeline[timeline.length-1]+'</p>'
        );
      }
    }

    j=0;
    for(; i<timeline.length;i+=2){
      if(i+1<timeline.length){
        title[i].innerText = idToData(locatsList[j],'text');
      // title[i+1].innerText = idToData(locatsList[j],'text');
        title[i+1].innerText = '{{ trans("messages.TralTo") }}';
        $(title[i+1]).css('color','red');
      }
      
      if(i>0&&i<timeline.length-1){
        $(heading[i]).append('<div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-utensils" value="'+j+'"></i></div><span class="nearby-find-text">Restaurant</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-store" value="'+j+'"></i></div><span class="nearby-find-text">Store</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-coffee" value="'+j+'"></i></div><span class="nearby-find-text">Coffe Store</span></div>');
        $(body[i]).append('<p>{{ trans("messages.Arr") }} '+ idToData(locatsList[j],'text') +' {{ trans("messages.At") }} '+timeline[i]+' {{ trans("messages.VisIn") }} '+ converttime(idToData(locatsList[j],'duration'))+'</p><br><p>'+idToData(locatsList[j],'description')+'</p><p><a href="'+idToData(locatsList[j],'link')+'" target="_blank">View the location</a></p><div class="show-more">{{ trans("messages.Showmore") }} <i class="fa fa-chevron-down" aria-hidden="true"></i></div>');
      }
      if(i<timeline.length-2){
        $(body[i+1]).append('<p>{{ trans("messages.CplVis") }} '+idToData(locatsList[j],'text')+' {{ trans("messages.At") }} '+timeline[i+1]+' {{ trans("messages.GoNxt") }}</p>');
      }
      j++;
    }

    var showmore = document.getElementsByClassName("show-more");
    for(i= 0 ; i<showmore.length;i++){
      showmore[i].addEventListener('click',function(){
        if(this.innerText=="{{ trans("messages.Showmore") }} "){
          this.parentElement.parentElement.setAttribute('style','max-height: none');
          this.innerHTML = '{{ trans("messages.Hide") }} <i class="fa fa-chevron-up" aria-hidden="true"></i>';
        } else {
          this.parentElement.parentElement.setAttribute('style','max-height: 174px'); 
          this.innerHTML = '{{ trans("messages.Showmore") }} <i class="fa fa-chevron-down" aria-hidden="true"></i>';
        }
      });
    }
    
    for(i= timeline.length; i<li.length;i++)
      li[i].style.display = "none"; 
    nearByFind();
  }

  function nearByFind(){
    fa = document.querySelectorAll('.nearby-find-icon');
    console.log(fa);
    for( var i =0;i<fa.length;i++)
      fa[i].addEventListener('click',function(){
        var type = this.parentElement.children[1].innerText;
        if(type == 'Restaurant'){
          type = 'restaurant'
        } else if(type = 'Store'){
          type = 'store'
        } else{
          type = 'cafe'
        }
        // var length = $(this.nextSibling.lastChild).find(":selected").val();
        var val = parseInt(this.children[0].getAttribute('value'));
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
        place.nearbySearch({
          location: idToData(locatsList[val],'LatLng'),
          radius: '500',
          type: type,
        }, (response, status) => {
          // if(response.length < length) 
          //   length = response.length;

          for (let i = 0; i <15; i++) {
            resMarker(response[i]);
          }
          map.setCenter(idToData(locatsList[val],'LatLng'));
          map.setZoom(18);
        });
      });  
  }
  function resMarker(place){
    var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25),
      };

    var resMarker = new google.maps.Marker({
      map,
      position: place.geometry.location,
      label: place.name,
      icon: icon
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
   function updpath(data){
    var dataobj = [];
    for(var i = 0; i < data.length; i++)
      for(var j = 0; j < data.length; j++)
        if(j!=i){
          dataobj.push({
            pa_de_start: locatsList[i],
            pa_de_end: locatsList[j],
            pa_distance: data[i].elements[j].distance.value,
            pa_duration: data[i].elements[j].duration.value
          });
        }

    $.ajax({
      url: '{{ route("updpath") }}',
      type: 'get',
      data: {data: dataobj},
       error: (err)=>{
        alert("An error occured: " + err.status + " " + err.statusText);
      },
      success: (result)=>{
      }
    });
   }

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
    // if(disresponse == undefined){
      idToData(null,'LatLngArr');
      allRoutePosible = [];
      var Arr = [];
      //init array [1,2,3,4,...n] with n is number of locations
      for(var i = 0;i < locatsList.length;i++)
        Arr[i] = i+1;    
      /*swaps the positions of the elements 
        in the array to create all possible paths*/
      arrPermutations(Arr.length,Arr); 
      // add 0 to first element [0,1,2,...];
      // for(var i =0; i< allRoutePosible.length;i++){
      //   allRoutePosible[i].unshift(0);
      //   if($('#is-back').is(':checked')) allRoutePosible[i].push(0);
      // }
      distanceRequest(distanceResponse);
    // } else {
    //   timealert();
    // }
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

  function distanceResponse(response,status){
    disresponse = response;
    timealert();
  }

  function timealert(serverres){
    choosendur = converttime($('#time-end').val()) - converttime($('#time').val());
    if(isNaN(choosendur)){
      bestWay();
    } else {
      var c;
      var time = 0;
      if(serverres !== undefined){
        time  = serverres;
      } else {
        for(var i = 0;i<locatsList.length; i++)
        time += idToData(locatsList[i],'duration');
      }
      

      if(time > choosendur){
        $("#timeAlert").modal("show");
        $("#del-time-click").click(()=>{
          $("#timeAlert").modal("hide");
          dello = 1;
          if(serverres !== undefined){
            processanddrawrouteserver();
          } else {
            bestWay();
          }
        });
        $("#timeAlert-close").click(()=>{
          $("#timeAlert").modal("hide");
          $('.options-list').hide();
          $(document.getElementById('time-end').parentElement).show();        
          $('#add-button').hide();
          $('#get-route').show();
        });
        // var c =confirm('The travel time has exceeded the selected time. Click cancel to delete some locations or choose the another end time.  Click OK we will delete some locations and Optimized the routes automaticaly.');
        // if(c == true){
        //   dello = 1;
        //   if(serverres !== undefined){
        //     processanddrawrouteserver();
        //   } else {
        //     bestWay();
        //   }
        // }  else {
        //   loader();
        //   $('.options-list').hide();
        //   $(document.getElementById('time-end').parentElement).show();        
        //   $('#add-button').hide();
        //   $('#get-route').show();
        // }
      }else {
        bestWay();
      }
    }  
  }


  function bestWay(){
    routeOptimized = {
      route: [],
      value: 0
    }
    var total = 0;
    // Loop all route posible to calculate the best way 
    if(!dello){
      for(var i = 0 ;i<allRoutePosible.length; i++){
        var A = allRoutePosible[i];
        if(isopt == 1){
          // total += disresponse.rows[A[0]].elements[A[1]].duration.value;
          for(var j = 0 ;j<A.length; j++){
            if(j==0){
              total += disresponse.rows[0].elements[A[0]].duration.value;
            } else {
               total += disresponse.rows[A[j-1]].elements[A[j]].duration.value;
            }
              total+= idToData(locatsList[A[j]-1],'duration');  
          }
          if($('#is-back').is(':checked')) 
            total += disresponse.rows[A[A.length-1]].elements[0].duration.value;

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
          for(var j = 0 ;j<A.length-1; j++){
            if(j==0){
              total += disresponse.rows[0].elements[A[0]].distance.value;
            } else {
               total += disresponse.rows[A[j-1]].elements[A[j]].distance.value;
            }
          }

          if($('#is-back').is(':checked')) 
            total += disresponse.rows[A[A.length-1]].elements[0].distance.value;

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
    } else {
      for(var i = 0 ;i<allRoutePosible.length; i++){
        var A = allRoutePosible[i];
        // if(durOrDis){
          var j = 0;
          var tmparr=[];
          while(j!=-1&&j<A.length){
            tmparr.push(A[j]);
            if(j==0){
              total += disresponse.rows[0].elements[A[0]].duration.value;
            } else {
               total += disresponse.rows[A[j-1]].elements[A[j]].duration.value;
            }
            total+= idToData(locatsList[A[j]-1],'duration');
            var tmptotal = total;
            if($('#is-back').is(':checked')){
              total+= disresponse.rows[A[j]].elements[0].duration.value;
            }
            j++;
            if(total<=choosendur){
              if (routeOptimized.value == 0){ 
                routeOptimized.route = tmparr;
                routeOptimized.value = tmptotal;
              }else if(tmparr.length >= routeOptimized.route.length){
                if(tmparr.length > routeOptimized.route.length){
                  routeOptimized.route = tmparr;
                  routeOptimized.value = tmptotal;
                }else if(tmptotal <= routeOptimized.value){
                  routeOptimized.route = tmparr;
                }
              }
            } else {
              j = -1;
            }
          }
          total = 0;
        // } else {
        //   var j = 1;
        //   var tmparr=[0];
        //   while(j!=0&&j<A.length){  
        //     tmparr.push(A[j]);
        //     total += response.rows[A[j-1]].elements[A[j]].distance.value;
        //     var tmptotal = total;
        //     if($('#is-back').is(':checked')){
        //       total+= response.rows[A[j]].elements[A[0]].distance.value;
        //     }
        //     j++;
        //     if(total<=choosendur){
        //       if (routeOptimized.value == 0){ 
        //         routeOptimized.route = tmparr;
        //         routeOptimized.value = tmptotal;
        //       } else if(tmparr.length >= routeOptimized.route.length){
        //         if(tmparr.length > routeOptimized.route.length){
        //           routeOptimized.route = tmparr;
        //           routeOptimized.value = tmptotal;
        //         }else if(tmptotal <= routeOptimized.value){
        //           routeOptimized.route = tmparr;
        //           routeOptimized.value = tmptotal;
        //         }
        //       }
        //         // console.log(routeOptimized);
        //     } else {
        //       j = 0;
        //       if($('#is-back').is(':checked')) tmparr.push(0);
        //     }
        //   }
        //   total = 0;
        // }
      }
    }
    var tmp = [];

    // routeOptimized.route.splice(0,1);

    // if($('#is-back').is(':checked')) 
    //   routeOptimized.route.splice(locats.length-1,1)
    // convert intenger array to waypoinits location
    for(var i=0;i<routeOptimized.route.length;i++)
      tmp[i] = locatsList[routeOptimized.route[i]-1];
    locatsList = tmp;
    // timeline = routeOptimized.timeline;
    idToData(null,'LatLngArr');
    drawRoutes();
  }


// function bestway2(response,status){
//   //Using A*
//   var openList = []; 
//   var closeList = [];
//   var curnode = 0;
//   var newnode = {
//     node: 0,
//     childnode: 
//     value: 0
//     path: [0],
//     backvalue: 0
//   };
//   var run = 1;
//   var min = 0;
//   var total = 0;

//   function checkednode(index,A){
//     for (var i = 0; i <A.length; i++) {
//       if(index == A[i]) return 1;
//     }
//     return 0;
//   }

  // optimize by time with start location
  // if()...
  // closeList.push(0);
  // do{
    // Choose node to calculate
    // if(openList.length>0){
    //   newnode = openList[0];
    //   curnode = newnode.childnode;
    //   closeList.push(curnode);
    //   openList.shift();
    // }

    // Find next path
//     for(var i = 0;i<locats.length;i++){
//       if(!checkednode(i,closeList)){
//         var fn = response.rows[curnode].elements[i].duration.value;
//         fn +=idToData(locatsList[i-1],'duration');

       
//         var tmppath = newnode.path
//             tmpvalue = newnode.value;
//         var tmpnode = {
//           node: curnode,
//           childnode: i,
//           value: tmpvalue + fn,
//           path: tmppath.push(i),
//           backvalue: fn + response.rows[i].elements[0].duration.value
//         }

//         if(openList.length == 0){
//          openList.push(tmpnode);
//         } else {
//           var j =0,
//             tmprun = 1;
//           while(j<openList.length&&tmprun){
//             if(tmpnode.value<openList[j].value){
//               openList.splice(j,0,tmpnode);
//             }
//             j++;
//           }
//         }

        

//       }
//     }
//   }while(run&&openList.length>0);
// }

function sortlocats(){// Sortable location list text
  var rowSize = 45; // => container height / number of items
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
    // $("#get-route").show();
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
        x: 27,
        y: 43
      };
      var text = String(this.get('text'));
      // var color = String(this.get('text'));
      var position = this.getProjection().fromLatLngToDivPixel(this.get('position'));
      this.span.innerHTML = text;
      // this.span.setAttribute('color',color);
      // this.span.style.left = (position.x - (markerSize.x / 2)) - (text.length * 3) + 10 + 'px';
      // this.span.style.top = (position.y - markerSize.y + 40) + 'px';
      this.span.style.left = (position.x) + 10 +'px';
      this.span.style.top = (position.y)  -15+ 'px';
    }
  });

};
</script> 
  <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
  <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('css/retour.css')}}">
  <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <div class="menu_siteBar">
      <div class="logoDashboard">
        <a href="{{route('user.dashboard')}}">TOUR ADVICE</a>
      </div>
      <div class="Language">
          <div class="lan_title">
              <span class="text-uppercase">{{ trans('messages.lang') }}</span><i class="fas fa-caret-down"></i>
          </div>
          <div class="lan_more">
              <p class="lan_vn" id="Lan_VN">VN</p>
              <p class="lan_en" id="Lan_EN">EN</p>
          </div>
      </div>
      <div class="menuDashboard">
        <ul>
          <li class="li_menu_start">
            <p class="menu_title_start text-uppercase">{{ trans('messages.StartTour') }}</p>
          </li>
          <li class="li_menu_acc">
            <p class="menu_title_acc text-uppercase" id="your_account">{{ trans('messages.Youraccount') }} <i class="fas fa-sort-down"></i></p>
            <div class="menu_content">
              <?php 
                  use Illuminate\Support\Facades\Auth;
                  $user = Auth::user();
              ?>
              @if($user->us_type == "1")
                  <p id="comback_admin">{{ trans('messages.adminPage') }}</p>
              @endif
              <p id="personalInfo">{{ trans('messages.Aboutyou') }}</p>
              <p id="p_logout">{{ trans('messages.Logout') }}</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <!-- Masthead-->
    <div id="overlay">
      <div class="loader"></div>
    </div> 
    <div id="map" class="tabcontent" ></div>
    <!--timeline-->
    <div id="timeline" class="tabcontent"  style="display: none;">
      <div class="row">
        <ul class="timeline">
          <li>
            <div class="timeline-badge" >
            </div>
            <div class="timeline-panel" >
              <div class="timeline-heading">
                <h4 class="timeline-title"></h4>
              </div>
              <div class="nearby-find"> 
                <!-- <div class="nearby-find-content">
                  <div class="nearby-find-icon">
                    <i class="fas fa-utensils"></i>
                  </div>
                  <span class="nearby-find-text">Restaurant</span>
                </div>
                <div class="nearby-find-content">
                  <div class="nearby-find-icon">
                    <i class="fas fa-store"></i>
                  </div>
                  <span class="nearby-find-text">Store</span>
                </div>
                <div class="nearby-find-content">
                  <div class="nearby-find-icon">
                    <i class="fas fa-coffee"></i>
                  </div>
                  <span class="nearby-find-text">Coffe Store</span>
                </div> -->

                
                <!-- <a href="#" class="fa fa-cutlery" value="'+j+'">
                </a> -->
                <!-- <div class="restaurant-select">
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
                  <!-- <p> Start the tour at '+idToData(locatsList[0],'text')+' at '+timeline[0] +' and visit in '+converttime(idToData(locatsList[0],'duration'))+
                  '</p>
                  <p>Chùa Một Cột có tên ban đầu là Liên Hoa Đài có tức là Đài Hoa Sen với lối kiến trúc độc đáo: một điện thờ đặt trên một cột trụ duy nhất. Liên Hoa Đài là công trình nổi tiếng nhất nằm trong quần thể kiến trúc Chùa Diên Hựu (延祐寺), có nghĩa là ngôi chùa "Phúc lành dài lâu". Công trình Chùa Diên Hựu nguyên bản được xây vào thời vua Lý Thái Tông mùa đông năm 1049 và hoàn thiện vào năm 1105 thời vua Lý Thánh Tông nay đã không còn. Công trình Liên Hoa Đài hiện tại nằm ở Hà Nội là một phiên bản được chỉnh sửa nhiều lần qua các thời kỳ, bị Pháp phá huỷ khi rút khỏi Hà Nội ngày 11/9/1954 và được dựng lại năm 1955 bởi kiến trúc sư Nguyễn Bá Lăng theo kiến trúc để lại từ thời Nguyễn. Đây là ngôi chùa có kiến trúc độc đáo ở Việt Nam. Chùa Một Cột đã được chọn làm một trong những biểu tượng của thủ đô Hà Nội, ngoài ra biểu tượng chùa Một Cột còn được thấy ở mặt sau đồng tiền kim loại 5000 đồng của Việt Nam. Tại quận Thủ Đức, Thành phố Hồ Chí Minh cũng có một phiên bản chùa Một Cột. Ngoài ra, tại thủ đô Moskva của Nga cũng có một phiên bản chùa Một Cột được xây lắp tại Tổ hợp Trung tâm Văn hóa - Thương mại và Khách sạn "Hà Nội - Matxcova".Chùa còn là biểu tượng cao quý thoát tục của con người Việt Nam. </p> -->
              </div>
              <!-- <div class="show-more">
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
                <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
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
                  <div class="nearby-find"></div>
                  <div class="timeline-body">
                  </div>
              </div>
            </li>
        </ul>
      </div>
    </div>
    <!--timeline ends-->
    <!-- control panel -->
    <div id="switch-tab" class="control-panel">
      <button class="tablinks active" onclick="openTab(event, 'map')"  id="tab-map">{{ trans('messages.Map') }}</button>
      <button class="tablinks" id="tab-timeline" style="display: none;" onclick="openTab(event, 'timeline')">{{ trans('messages.Timeline') }}</button>
    </div>
    <div id="search-panel" class="control-panel">
        <div id="search-panel-control">
          <div id="select-box">
            <i id="drop-down-arow" class="fa fa-angle-down " aria-hidden="true"></i> 
            <select id="waypoints">
            </select>
            
            <div id="select-box-reset" class="reset-all">
              <a href="#" class="tooltip-test" title="Click to reset"><i class="fa fa-undo fa-lg" aria-hidden="true"></i></a>
            </div>
          </div>
          <button id="add-button">{{ trans('messages.Addlocation') }}</button>
          <button id="get-route">{{ trans('messages.findWay') }}</button>
          <!-- <button id="get-route" style="display: none;">Update</button> -->
        </div>
        <div id="your-start" style="display: none;"> 
          {{ trans('messages.Yourstartlocation') }}<span id="your-start-close">×</span>
        </div>
        <div class="container">  
        </div>
      </div>
      <div id="switch-tab" class="control-panel">
        <button class="tablinks" onclick=""  id="saveTour">{{ trans('messages.SaveTour') }}</button>
        <button class="tablinks reset-all"  onclick="">{{ trans('messages.Reset') }}</button>
      </div>
      <div id="options-control" class="control-panel">
        <div id="options-control-title"><b>{{ trans('messages.Selecttouroptions') }}</b></div>
        <div class="options-list options-list1">
          <div><b>{{ trans('messages.Selectthestarttime') }}:</b></div>
          <input type="time" id="time" value="07:00" style="width: 100%;">
        </div>
        <div class="options-list">
          <div><b>{{ trans('messages.Selecttheendtime') }}:</b></div>
          <input type="time" id="time-end" value="" style="width: 100%;">
        </div>
        <div class="options-list">
          <input type="checkbox" id='is-back'> <b>{{ trans('messages.Comebackthestart') }}</b>
        </div>
        <div class="options-list">
          <div><input type="checkbox" id='is-opt' checked><b> {{ trans('messages.Optimized') }}</b></div>
          <div id="is-opt-sub">
            <input type="radio" class="dur-dis" name="durdis" value="1" checked> {{ trans('messages.Duration') }}   
            <input type="radio" class="dur-dis" name="durdis" value="2"> {{ trans('messages.Cost') }}
          </div>
            
        </div>
        <!-- <button class="slider-button slider-button-left" onclick="plusDivs(-1)">
          &#10094;
        </button>
        <button class="slider-button slider-button-right" onclick="plusDivs(1)">
          &#10095;
        </button> -->
      </div>
    <!-- Footer-->
    <footer class="footer text-center">
        <div class="footer_1 footer_div">
          <h4 class="title_footer_1 text-uppercase">{{ trans('messages.Location') }}</h4>
          <div class="content_footer_1">
            <p>HA NOI</p>
            <p>VIET NAM</p>
          </div>
        </div>
        <div class="footer_2 footer_div">
          <h4 class="title_footer_2 text-uppercase">{{ trans('messages.AroundtheWeb') }}</h4>
          <div class="content_footer_2">
            <div class="icon_content">
              <i class="fab fa-fw fa-facebook-f"></i>
            </div>
            <div class="icon_content">
              <i class="fab fa-fw fa-twitter"></i>
            </div>
            <div class="icon_content">
              <i class="fab fa-fw fa-linkedin-in"></i>
            </div>
            <div class="icon_content">
              <i class="fab fa-fw fa-dribbble"></i>
            </div>
          </div>
        </div>
        <div class="footer_3 footer_div">
          <h4 class="title_footer_3 text-uppercase">{{ trans('messages.Abouttouradvice') }}</h4>
          <div class="content_footer_3">
            <p>{{ trans('messages.experience') }}</p>
          </div>
        </div>
    </footer>
    <!-- Copyright Section-->
    <div class="Copyright">
        <div class="Copyright_content"><small>Copyright © Tour Advice 2021</small></div>
    </div>

<!-- Modal personal-->
<div class="modal fade" id="personal" tabindex="-1" role="dialog" aria-labelledby="personalModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="personalModal">{{ trans('messages.Yourpersonalinformation') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{$message}}</strong>
            </div>
        @endif
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 text-center">
                    <div id="text_img" class="mb-5" ></div>
                    <img class="mb-5" src="{{asset('assets/img/avataaars.svg')}}" alt="" id="default_img" />
                </div>
            </div>
        </div>
        <form action="{{route('user.editInfo')}}" method="post" id="formFixInfor" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12 text-center mb-2">
                        <p class="text_content">{{ trans('messages.Avatar') }}</p>
                        <div class="btn_upload">{{ trans('messages.Upload') }}</div>
                        <p class="text_content" id="file_name"></p>
                        <input type="file" class="form-control" id="input_File" name="file">
                    </div>
                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">Email</p></div>
                    <div class="col-md-8 col-sm-6 col-6" id="text_email"></div>
                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.FullName') }}</p></div>
                    <div class="col-md-8 col-sm-6 col-6" id="text_fullName"></div>
                    <div class="col-md-8 col-sm-6 col-6" id="input_fullName">
                        <input type="text" placeholder="Enter your fullname" class="form-control" name="fullName">
                    </div>
                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.Gender') }}</p></div>
                    <div class="col-md-8 col-sm-6 col-6" id="text_gender"></div>
                    <div class="col-md-8 col-sm-6 col-6" id="input_gender">
                        <select name="gender" class="form-control">
                            <option value="Male">{{ trans('messages.Male') }}</option>
                            <option value="Female">{{ trans('messages.Female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.Age') }}</p></div>
                    <div class="col-md-8 col-sm-6 col-6" id="text_age"></div>
                    <div class="col-md-8 col-sm-6 col-6" id="input_age">
                        <input type="number" placeholder="Enter your age" class="form-control" name="age">
                    </div>
                    <!-- pass -->

                    <p class="col-md-12 col-sm-12 col-12 openChangePass text-info">{{ trans('messages.ifYouchange') }}. <span class="openClickHere">{{ trans('messages.clickHere') }}</span></p>
                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content openItems">{{ trans('messages.oldPassword') }}</p></div>
                    <div class="col-md-8 col-sm-6 col-6 openItems" id="input_Oldpassword">
                        <input type="password" placeholder="{{ trans('messages.oldPassword') }}" class="form-control" name="oldpass">
                    </div>
                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content openItems">{{ trans('messages.newPassword') }}</p></div>
                    <div class="col-md-8 col-sm-6 col-6 openItems" id="input_password">
                        <input type="password" placeholder="{{ trans('messages.newPassword') }}" class="form-control" name="newpass">
                    </div>
                    <div class="col-md-4 col-sm-6 col-6 openItems"><p class="text_content">{{ trans('messages.confirmPassword') }}</p></div>
                    <div class="col-md-8 col-sm-6 col-6 openItems" id="input_Confirmpassword">
                        <input type="password" placeholder="{{ trans('messages.confirmPassword') }}" class="form-control" name="confirmpass">
                    </div>
                </div>   
            </div>
        </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.CloseWindow') }}</button>
        <button type="button" class="btn btn-primary" id="btn_editInfo">{{ trans('messages.Editinformation') }}</button>
        <button type="button" class="btn btn-primary" id="btn_submitInfo">{{ trans('messages.SubmitEdit') }}</button>
      </div>
    </div>
  </div>
</div>
<!-- /modal -->
<!-- Modal -->
<div class="modal fade" id="enterNameTour" tabindex="-1" role="dialog" aria-labelledby="enterNameTourLabel" aria-hidden="true">
  <div class="modal-dialog modal-dm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="enterNameTourLabel">{{ trans('messages.Enternametour') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-4 col-sm-6 col-6">{{ trans('messages.NameTour') }}</div>
            <div class="col-md-8 col-sm-6 col-6">
              <input type="text" class="form-control" placeholder="{{ trans('messages.NameTour') }}" name="nameTour">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.CloseWindow') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveNameTour">{{ trans('messages.SaveTour') }}</button>
      </div>
    </div>
  </div>
</div>
<!-- /Modal -->
<!-- Modal alert click -->
<div class="modal fade" id="clickWarning" tabindex="-1" role="dialog" aria-labelledby="clickWarningLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clickWarningLabel">{{ trans('messages.Warning') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-danger">{{ trans('messages.WantChange') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="change-click">{{ trans('messages.Change') }}</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal -->
<!-- Modal alert click -->
<div class="modal fade" id="timeAlert" tabindex="-1" role="dialog" aria-labelledby="clickWarningLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="timeAlertLabel">{{ trans('messages.Warning') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" id="timeAlert-close">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text">{{ trans('messages.OverTime') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="del-time-click">{{ trans('messages.Autodeletelocations') }}</button>
      </div>
    </div>
  </div>
</div>
<!-- End modal -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/utils/Draggable.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/TweenMax.min.js'></script>
    <script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript">
  $(document).ready(function(){
      $("#comback_admin").click(function(){
          location.replace("{{route('admin.generalInfor')}}");
      });
      $('button').click(function(){
        $('button').css('border','none');
        $('button').css('outline','none');
      });
      $("#saveTour").click(function(){
          $("#enterNameTour").modal("show");
      });
      $("#btnSaveNameTour").click(function(){
          let nameTour = $('input[name="nameTour"]').val();
          if(nameTour == "")
          {
            alert("Please enter the tour name first");
          }
          else
          {
            let coordinates = "";
            if(starlocat != undefined)
            {
              coordinates = starlocat.lat+"-"+starlocat.lng;
            }
            let $url_path = '{!! url('/') !!}';
            let _token = $('meta[name="csrf-token"]').attr('content');
            let routeDetail=$url_path+"/saveTour";
            let timeStart = $('#time').val();
            let timeEnd = $('#time-end').val();
            let to_comback;
            if ($('#is-back').is(':checked'))
            {
                to_comback = "1";
            }
            else to_comback = "0";
            let to_optimized;
            if ($('#is-opt').is(':checked') == false)
            {
              to_optimized="0";
            }
            else{
              to_optimized = $('input[name="durdis"]').val();
            }
            $.ajax({
                  url:routeDetail,
                  method:"get",
                  data:{_token:_token,locatsList:locatsList,timeStart:timeStart,timeEnd:timeEnd,to_comback:to_comback,to_optimized:to_optimized,nameTour:nameTour,coordinates:coordinates},
                  success:function(data){ 
                    alert("Your tour has been saved");
                    $("#saveTour").css("display","none");
                    $("#enterNameTour").modal("hide");
                  }
            });
          }
      });
      $(".menu_title_start").click(function(){
        location.reload();
      });
      $("#p_logout").click(function(){
        location.replace("{{route('logout')}}");
      });
      $("#personalInfo").click(function(){
        $("#personal").modal("show");
      });
      $('#personal').on('show.bs.modal', function (event) {
          let _token = $('meta[name="csrf-token"]').attr('content');
          let $url_path = '{!! url('/') !!}';
          let routeCheckUser=$url_path+"/checkUser";
          $.ajax({
                url:routeCheckUser,
                method:"POST",
                data:{_token:_token},
                success:function(data){ 
                  $("#text_email").empty();
                  $("#text_fullName").empty();
                  $("#text_gender").empty();
                  $("#text_age").empty();
                  if(data[5] == false)
                  {
                      $("#default_img").css("display","block");
                      $("#text_img").css("display","none");
                  }
                  else
                  {
                      $("#default_img").css("display","none");
                      $("#text_img").css("display","block");
                      $("#text_img").css("background","url('"+data[0]+"')");
                      $("#text_img").css("background-size","cover");
                      $("#text_img").css("background-repeat","no-repeat");
                  }
                  if(data[6] != "")
                  {
                      $("#text_email").append(data[1]+"<span class='text-danger' style='font-style: italic;'> (Chưa xác minh)</span>");
                  }
                  if(data[6] == "")
                  {
                      $("#text_email").append(data[1]+"<span class='text-success' style='font-style: italic;'> (Đã xác minh)</span>");
                  }
                  $("#text_fullName").append(data[2]);
                  $("#text_gender").append(data[3]);
                  $("#text_age").append(data[4]);
                  //append input
                  $("#input_age input").val(data[4]);
                  $("#input_gender select").val(data[3]);
                  $("#input_fullName input").val(data[2]);
               }
          });
      });
      $("#btn_editInfo").click(function(){
          //ẩn
          $("#text_fullName").slideUp("fast");
          $("#text_age").slideUp("fast");
          $("#text_gender").slideUp("fast");
          //hiện
          $(".openChangePass").css('display','block');
          $("#btn_submitInfo").css("display","block");
          $(".btn_upload").slideDown("fast");
          $("#input_age").slideDown("fast");
          $("#input_gender").slideDown("fast");
          $("#input_fullName").slideDown("fast");
          $("#btn_editInfo").css("display","none");
      });
      $(".btn_upload").click(function(){
          $("#input_File").click();
      });
      $("#btn_submitInfo").click(function(){
          $("#formFixInfor").submit();
      });
      $(".openClickHere").click(function(){
          $(".openItems").css("display","flex");
          $(".openChangePass").css("display","none");
      });
      $("#input_File").change(function(){
          $(".btn_upload").css("background","#ff8304");
          $("#file_name").css("display","block");
          $("#file_name").html($("#input_File").val().split('\\').pop());
      });
      $("#Lan_VN").click(function(){
          let $url_path = '{!! url('/') !!}';
          let _token = $('meta[name="csrf-token"]').attr('content');
          let routeLangVN=$url_path+"/langVN";
          $.ajax({
                url:routeLangVN,
                method:"POST",
                data:{_token:_token},
                success:function(data){ 
                  location.reload();
               }
          });
      });
      $("#Lan_EN").click(function(){
          let $url_path = '{!! url('/') !!}';
          let _token = $('meta[name="csrf-token"]').attr('content');
          let routeLangVN=$url_path+"/langEN";
          $.ajax({
                url:routeLangVN,
                method:"POST",
                data:{_token:_token},
                success:function(data){ 
                  location.reload();
               }
          });
      });
      $(".lan_title").click(function(){
          if ($('.lan_more').is(':visible'))
          {
              $(".lan_more").slideUp("fast");
          }
          else
          {
              $(".lan_more").slideDown("fast");
          }
      });
      $(document).click(function (e)
      {
          var container = $(".Language");
          //click ra ngoài đối tượng
          if (!container.is(e.target) && container.has(e.target).length === 0)
          {
              $(".lan_more").slideUp("fast");
          }
      });
      $(".menu_title_acc").click(function(){
          if ($('.menu_content').is(':visible'))
          {
              $(".menu_content").slideUp("fast");
          }
          else
          {
              $(".menu_content").slideDown("fast");
          }
      });
      $(document).click(function (e)
      {
          var container = $(".li_menu_acc");
          //click ra ngoài đối tượng
          if (!container.is(e.target) && container.has(e.target).length === 0)
          {
              $(".menu_content").slideUp("fast");
          }
      });
  });
</script>
<script type="text/javascript">
  $('[data-toggle="tooltip"]').tooltip(); 
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


