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
		allRoutePosible = [],
		// routeOptimized = {
		//   route: [],
		//   value: 0
		// },
		resmarkers= [],
		polylines = [],
		dello = 0,
		disresponse,
		choosendur = 0,
		newPlaceId,
		newPlaceIdArr =[],
		clickMarker,
		newClickMarker =[],
		startlocat,// click on map to choose star loaction
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
			locationsdata = result[0];
			let data = result[1];
			console.log(data);
			$('#waypoints').select2({
				data: data
			});
			showMap();
		}
	}); 
};

function showMap(){
	
	// init map and Direction service     
	const map = new google.maps.Map(document.getElementById("map"), {
					zoom: 12.5,
					center: { lat: 21.0226586, lng: 105.8179091 },
				}),
			directionsService = new google.maps.DirectionsService();
	
	map.addListener('click',function(evt){
		if(isclick == 1){
			if(clickMarker != undefined){
				clickMarker.setMap(null);
				$('.map-marker-label[value='+newPlaceId+']').remove();
			} 
			GeocoderService(evt.latLng,geocoderCallBack);
		} else {
			$('#clickWarning').modal('show')
			$('#change-click').click(()=>{ 
				$('#clickWarning').modal('hide')
				isclick = 1;
				updateRoute();
			});
			
		}
	});


	function GeocoderService(LatLng,callback){
		let geocoder = new google.maps.Geocoder();
		geocoder.geocode({location: LatLng},callback);
	}

	function geocoderCallBack(response, status){
		clickMarker= new google.maps.Marker({
					label: response[0].formatted_address,
		});
		clickMarker.setMap(map);
		clickMarker.setPosition(response[0].geometry.location);
		newPlaceId = response[0].place_id;

		// if($('#your-start').is(':visible') || $('.list-item').length){
		$('#set-dur-panel').show();
		if(startlocat == undefined){
			$('#set-dur-input').data('durationPicker').setValue(0);
			$('#set-dur-panel').val(0);
		} else {
			$('#set-dur-input').data('durationPicker').setValue(3600);
			$('#set-dur-panel').val(3600);
		}
		
			// $('#set-dur-panel').val(response[0].place_id);
		// }
		locationsdata.push({
			de_name: response[0].formatted_address,
			location: response[0].geometry.location.toJSON(),
			place_id: response[0].place_id,
			de_duration: 3600,
			de_default: 1
		})
		customLabel(clickMarker,response[0].place_id);
	
		//add new location option in select 
		$("#waypoints").append('<option value="'+
					response[0].place_id+'">'+response[0].formatted_address+ '</option>');
		$('#waypoints').val(response[0].place_id);


	}

	
	
	if(!$('#is-opt').is(':checked')){
		$('#is-opt-sub').hide();
		isopt = 0;
	} 

	//Reset all html onclick
	document.querySelectorAll(".reset-all").forEach(ele=>{
		ele.addEventListener('click',function(){
		$("#get-route").text("Let's go");
		$("#saveTour").css("display","none");
		//Reset options
		$('.container').empty();
		document.querySelector("#time")._flatpickr.setDate(new Date().setHours(7,0,0));
		document.querySelector("#time-end")._flatpickr.clear();
		$('#is-back').prop('checked', false);
		$('.dur-dis[value="1"]').prop('checked', true);

		if(newClickMarker.length){
			newClickMarker.forEach(ele=>{
				ele.setMap(null);
			})

			newClickMarker = [];
		}

		newPlaceId = undefined;
		newPlaceIdArr = [];
		if(clickMarker!=undefined) clickMarker.setMap(null);
		clickMarker = undefined;
		locationContainerHeight();
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
		$('#your-start')[0].setAttribute('data-strtlocat',0);
		$('#your-start')[0].setAttribute('data-clsclk',0);
		$('#your-start')[0].innerText = 'Click on map to start location';
		$('#set-dur-panel').hide();
		tablinks = document.getElementsByClassName('tablinks');
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById('tab-map').className += " active";
		$('.options-list').show();

		// Reset locations select list
		//Disable selected option
		$("#waypoints").find(":selected").prop('disabled',true);
		let options = $('#waypoints  option');
		for(let i =0 ;i<options.length;i++){
				options[i].disabled = false;
		}
		options[0].selected = true;
		$("#waypoints").select2();

		// reset all varibles
		timeline = [];
		locatsList = [];
		locats = [];
		// durOrDis = 1;
		markersArray=[];
		isopt = 1;
		isclick = 1;
		allRoutePosible = [];
		// routeOptimized = {
		//   route: [],
		//   value: 0
		// }; 
		resmarkers = [];
		polylines = [];
		dello = 0;
		disresponse = undefined;
		choosendur = 0;
		newPlaceId = undefined;
		newPlaceIdArr =[];
		clickMarker = undefined
		newClickMarker =[]
		startlocat = undefined;
		staMarker = undefined;
	
	});
	})
	
	// function containerHeight(){

	// }
	//add location button
	$("#add-button").click(()=>{
		let datastrtlocat = parseInt(document.getElementById('your-start').getAttribute('data-strtlocat'));
		if(datastrtlocat == 0){
			$('#your-start')[0].innerHTML = 'Click here to add start location'
		}

		idToData($("#waypoints").find(":selected").val(),"setDur",$('#set-dur-panel').val());
		//Add the start location
		if(startlocat == undefined && !$('.list-item').length &&clickMarker !=undefined || datastrtlocat == 1){
			document.getElementById('your-start').setAttribute('data-strtlocat',2);
			startlocat = $("#waypoints").find(":selected").val();
			staMarker = clickMarker;
			clickMarker = undefined;
			$('#your-start').show();
			$('#set-dur-panel').hide(); 
			document.getElementById('your-start').innerHTML= $("#waypoints").find(":selected")[0].innerText+
			'<span id="your-start-close">×</span>';

			//Disable selected option
			$("#waypoints").find(":selected").prop('disabled',true);
			let options = $('#waypoints  option');
			for(let i =0 ;i<options.length;i++){
				if(!options[i].disabled){
					options[i].selected = true;
					console.log(options[i]);
					$('#waypoints').select2();
					break;
				}
			}

			$('#your-start-close').click(()=>{
				$('#your-start')[0].setAttribute('data-clsclk',1);
				$('#your-start')[0].setAttribute('data-strtlocat',0);
				staMarker.setMap(null);
				staMarker = undefined;
				$('.map-marker-label[value='+startlocat+']').remove();
				$('#your-start')[0].innerHTML = 'Click here to add start location';
				startlocat = undefined;
				updateRoute();
			})
			if(locatsList.length==5) $("#add-button").hide();
			return;
		}

		if($("#waypoints").find(":selected").val() == newPlaceId){
			newClickMarker.push(clickMarker);
			newPlaceIdArr.push(newPlaceId)
			clickMarker = undefined;
			newPlaceId = undefined;
		}
		locationContainerHeight('add'); 
		updateRoute();
		$('#get-route').show();
		$('#set-dur-panel').hide(); 
		locatsList.push($("#waypoints").find(":selected").val());
		addLoText();
		sortlocats();

		//Disable selected option
		$("#waypoints").find(":selected").prop('disabled',true);
		let options = $('#waypoints  option');
		for(let i =0 ;i<options.length;i++){
			if(!options[i].disabled){
				options[i].selected = true;
				console.log(options[i]);
				$('#waypoints').select2();
				break;
			}
		}
		


		// $("#waypoints").select2().on("select2:select", function(e) {
		//   var selected_element = $(e.currentTarget);
		//   var select_val = selected_element.val();
		//   $.each(this.options, function(i, item) {
		//     if (item.selected) {
		//       $(item).prop("disabled", true);
		//     }
		//     console.log(item);
		//   });
		//   $('select').select2();
		// });
		if(locatsList.length==5) $("#add-button").hide();//hide add button
	});

	//get route button
	$("#get-route").click(()=>{
		//Reset resmarker 
		if(resmarkers.length){
			resmarkers.forEach(ele=>ele.setMap(null));
			resmarkers = [];
		}
		
		if(locatsList.length>1){
			setOptions();
			if(!isopt){
			// turn on loader
				$('#overlay').show()
				idToData(null,'LatLngArr');
				drawRoutes();
			}else{
				// (startlocat== undefined&&!newPlaceIdArr.length)? processanddrawrouteserver():processanddrawrouteclient();
				processanddrawrouteclient();
				
			}
			$("#get-route").hide();
			if(locatsList.length==5) $("#add-button").hide();
			$("#tab-timeline").attr('style','display: block');
		}
		if(startlocat != undefined && locatsList.length == 1){
			$('#overlay').show()// turn on loader
			idToData(null,'LatLngArr');
			drawRoutes();
			$("#get-route").hide();
			if(locatsList.length==5) $("#add-button").hide();
			$("#tab-timeline").attr('style','display: block');
		}
		if((startlocat != undefined && locatsList.length == 0) || (startlocat == undefined && locatsList.length<=1)){
			alert("Please choose at least 2 locations");
		}
		else
		{
			$("#saveTour").css("display","block");
		}
	});

	function locationContainerHeight(type){
		var height;
		switch(type){
			case 'add':
				height = ($('.list-item').length+2) * 45 +5;
				$('#container-height').css('height',height+'px');
				break;
			case 'del':
				height = $('.list-item').length * 45;
				$('#container-height').css('height',height+'px');
				break;
			default: 
				$('#container-height').css('height','auto');
				break;
		}
	}
	
	$('#your-start').click(()=>{
		let dataclsclk = parseInt(document.getElementById('your-start').getAttribute('data-clsclk'));
		if(dataclsclk == 1){
			$('#your-start')[0].setAttribute('data-clsclk',0);
			return;
		}
		if(startlocat == undefined ){
			$('#your-start')[0].innerHTML = 'Please click on map to choose start location';
			if(locatsList.length){
				$('#your-start')[0].setAttribute('data-strtlocat',1);
				updateRoute();
			}
		}
		$('#add-button').show();
	})
	$('#clear-service').click(()=>{
		$('#clear-service').hide();
		for(var i = 0; i<resmarkers.length;i++)
			resmarkers[i].setMap(null);
		$('.map-marker-label[value="nearByPlace"]').remove();
	})
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
		// isclick = 0;
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
				locationContainerHeight('del');
				let id = this.parentElement.parentElement.getAttribute('value');
				//Delete markers
				for(let i = 0; i < newClickMarker.length; i++){
					if(Object.entries(newClickMarker[i].position.toJSON()).toString() === Object.entries(idToData(id,'LatLng')).toString()){
						newClickMarker[i].setMap(null);
						$('.map-marker-label[value='+id+']').remove();
						newClickMarker.splice(i,1);
						break;
					}
				}
				//Delete markers
				for(let i = 0; i < markersArray.length; i++){
					if(Object.entries(markersArray[i].position.toJSON()).toString() === Object.entries(idToData(id,'LatLng')).toString()){
						markersArray[i].setMap(null)
						markersArray.splice(i,1);
						break;
					}
				}
				this.parentElement.parentElement.remove();
				var num = parseInt(this.parentElement.lastElementChild.innerText);
				locatsList.splice(num-1,1);
				updateRoute(); 
				sortlocats();
				for(var i=0,a = $(".order"); i<a.length;i++){
					a[i].innerText = i+1;
				}
				value = this.parentElement.parentElement.getAttribute('value');  
				//Undisable selected option
				$("#waypoints").find(":selected").prop('disabled',true);
				let options = $('#waypoints  option');
				for(let i =0 ;i<options.length;i++){
					let j = 0;
					if(options[i].getAttribute('value') == value ||options[i].selected == true ){
						options[i].disabled = false;
						j++;
						if(j == 2){
							$('#waypoints').select2();
							break;
						}
					}
				}

				$('#get-route').show();
				$('#add-button').show();
			// }
		});
	}

	function idToData(id,type,value){
		if(type == 'LatLngArr'){
			locats = [];
			for( var j= 0; j<locatsList.length; j++)
				for(var i=0; i<locationsdata.length; i++){
					if (locatsList[j] == locationsdata[i].place_id)
					locats.push(locationsdata[i].location);
				}
			if(startlocat != undefined)  locats.unshift(idToData(startlocat,'LatLng'));
			if($('#is-back').is(':checked')) locats.push(locats[0]);
		}

		for(var i=0; i<locationsdata.length; i++){
			switch(type){
				case 'text':
					if (id == locationsdata[i].place_id)
						return locationsdata[i].de_name;
					break;
				case 'default':
					if (id == locationsdata[i].place_id)
						return locationsdata[i].de_default;
					break;
				case 'LatLng':
					if (id == locationsdata[i].place_id)
						return locationsdata[i].location;
					break;
				case 'duration':
					if (id == locationsdata[i].place_id)
						return locationsdata[i].de_duration;
					break;
				case 'link':
					if (id == locationsdata[i].place_id)
						return locationsdata[i].de_link;
					break;
				case 'description':
					if (id == locationsdata[i].place_id)
						return locationsdata[i].de_description;
					break;
				case 'setDur':
					if (id == locationsdata[i].place_id && locationsdata[i].de_default != undefined){
						locationsdata[i].de_duration = parseInt(value); 
					}
					break;
				default: break;
			}
		}
		
	}

	function processanddrawrouteserver(){
		$('#overlay').show()
		var data =  { 
			locatsList: locatsList, 
			durordis: isopt,
			choosendur: (document.querySelector("#time-end")._flatpickr.selectedDates[0] - document.querySelector("#time")._flatpickr.selectedDates[0])/1000,
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
		if(newClickMarker.length){
			newClickMarker.forEach(ele=>{
				ele.setMap(null);
			})

			newClickMarker = [];
		}
		if(newPlaceIdArr.length){
			newPlaceIdArr.forEach(ele=>{
				$('.map-marker-label[value='+ele+']').remove();
			})
		}
			
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
// {placeId: 'ChIJoRyG2ZurNTERqRfKcnt_iOc'}
		directionsService.route({
				origin: locats[0],
				destination: locats[locats.length-1],
				waypoints: waypts,
				travelMode: 'DRIVING',
		},customDirectionsRenderer);
	}

	function customDirectionsRenderer(response, status) {
		$('#overlay').hide();// turn off loader
		var bounds = new google.maps.LatLngBounds();
		var legs = response.routes[0].legs;
		for(var i=0;i<polylines.length;i++){
			polylines[i].setMap(null);
		}

		for (i = 0; i < legs.length; i++) {
			(i>=5&&i%5 == 0)?index = 4:((startlocat != undefined)?index = (i%5)-1:index = (i%5));
			if(startlocat != undefined && i==0) index = 5;
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
		timeline = [];
		let tmp = document.querySelector("#time")._flatpickr.selectedDates[0];
		console.log(document.querySelector("#time")._flatpickr.selectedDates[0]);
		timeline.push(tmp);
		if(startlocat != undefined && idToData(startlocat,"duration")!=0){
			tmp = new Date(tmp.getTime() + idToData(startlocat,"duration")*1000);
		console.log('here');
			timeline.push(tmp);
		}
		for(var i = 0; i < response.length-1 ; i++){
			if(startlocat == undefined){
				tmp = new Date(tmp.getTime() + idToData(locatsList[i],"duration")*1000);
				timeline.push(tmp);
			} else if(i >=1){
				 tmp = new Date(tmp.getTime() + idToData(locatsList[i-1],"duration")*1000);
				timeline.push(tmp);
			}
			
			tmp = new Date(tmp.getTime() + response[i].duration.value*1000);
			timeline.push(tmp);
		}

		if(!$('#is-back').is(':checked')){
			tmp = new Date(tmp.getTime() + idToData(locatsList[locatsList.length-1],"duration")*1000);
			timeline.push(tmp);
		}
		let totaldistance = 0;
		for(let i = 0;i<response.length;i++){
			totaldistance+=response[i].distance.value;
		}
		totaldistance = totaldistance/1000 +'km';
		for(let i= 0;i<response.length;i++){
			let infoWindow = new google.maps.InfoWindow();
			let content = 
			'<p>- This route duration: '+response[i].duration.text+'</p>'+
			'<p>- This route distance: '+response[i].distance.text+'</p>'+
			'<p>- Total time: '+converttime('duration')+'</p>'+
			'<p>- Total distance: '+totaldistance+'</p>';

			polylines[i].addListener('mouseover', (e)=>{
				infoWindow.setPosition(e.latLng);
				infoWindow.setContent(content);
				infoWindow.open(map);
			});

		// Close the InfoWindow on mouseout:
			polylines[i].addListener('mouseout', () => {
				 infoWindow.close();
			});
		}
			
		

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
		 icon[i].innerText = converttime(timeline[i]); 
		i=0;
		if(startlocat!= undefined && idToData(startlocat,'duration') == 0){
			i=1;
			title[0].innerText = '{{ trans("messages.YourLo") }} '+timeline[0].toLocaleDateString();
			$(title[0]).css('color','#ea4335');
			$(body[0]).append(
				'<p> {{ trans("messages.Startthetourat") }} ' +idToData(startlocat,'text')+ ' {{ trans("messages.At") }} '+converttime(timeline[0]) +'</p>'
			);
			if($('#is-back').is(':checked')){
				title[timeline.length-1 ].innerText = '{{ trans("messages.YourLo") }} '+timeline[timeline.length-1].toLocaleDateString();
				$(body[timeline.length-1]).append(
				'<p>{{ trans("messages.CmBck") }} '+idToData(startlocat,'text')+'</p>');
			} else {
				$(body[timeline.length-1]).append(
				'<p> {{ trans("messages.Finish") }} '+ idToData(locatsList[locatsList.length-1],'text') +' {{ trans("messages.At") }} '+converttime(timeline[timeline.length-1])+'</p>'
				);
				
			}
		} 

		if(startlocat!= undefined && idToData(startlocat,'duration') != 0){
			i=2;
			title[0].innerText = '{{ trans("messages.YourLo") }} '+timeline[0].toLocaleDateString();
			$(title[0]).css('color','#ea4335');
			$(body[0]).append(
				'<p> {{ trans("messages.Startthetourat") }} ' +idToData(startlocat,'text')+ ' {{ trans("messages.At") }} '+converttime(timeline[0]) +' {{ trans("messages.VisIn") }} '+converttime(idToData(startlocat,'duration')) +'</p>'
			);
			title[1].innerText = idToData(startlocat,'text')+' '+timeline[1].toLocaleDateString();
			$(title[1]).css('color','red');
			$(body[1]).append('<p>{{ trans("messages.CplVis") }} '+idToData(startlocat,'text')+' {{ trans("messages.At") }} '+converttime(timeline[1])+' {{ trans("messages.GoNxt") }}</p>');
			if($('#is-back').is(':checked')){
				title[timeline.length-1 ].innerText = '{{ trans("messages.YourLo") }} '+timeline[timeline.length-1].toLocaleDateString();
				$(body[timeline.length-1]).append(
				'<p>{{ trans("messages.CmBck") }} '+idToData(startlocat,'text')+'</p>');
			} else {
				$(body[timeline.length-1]).append(
				'<p> {{ trans("messages.Finish") }} '+ idToData(locatsList[locatsList.length-1],'text') +' {{ trans("messages.At") }} '+converttime(timeline[timeline.length-1])+'</p>'
				);
				
			}
		}

		if(startlocat == undefined){
			$(body[0]).append(
				'<p> {{ trans("messages.Startthetourat") }} '+idToData(locatsList[0],'text')+' {{ trans("messages.At") }} '+converttime(timeline[0]) +' {{ trans("messages.VisIn") }} '+converttime(idToData(locatsList[0],'duration'))+
				'</p><br><p>'+idToData(locatsList[0],'description')+'</p><p><a href="'+idToData(locatsList[0],'link')+'"target="_blank">View the location</a></p><div class="show-more">{{ trans("messages.Showmore") }} <i class="fa fa-chevron-down" aria-hidden="true"></i></div>'
			); 
			$(heading[0]).append('<div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-utensils" value="'+0+'"></i></div><span class="nearby-find-text">Restaurant</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-store" value="'+0+'"></i></div><span class="nearby-find-text">Store</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-coffee" value="'+0+'"></i></div><span class="nearby-find-text">Coffe Store</span></div>');


			if($('#is-back').is(':checked')){
				title[timeline.length-1].innerText = idToData(locatsList[0],'text') +' '+timeline[timeline.length-1].toLocaleDateString() ;
				$(body[timeline.length-1]).append(
						'<p>{{ trans("messages.CmBck") }} '+idToData(locatsList[0],'text')+' {{ trans("messages.At") }} '+converttime(timeline[timeline.length-1])+'</p>'
				);
			} else {
				$(body[timeline.length-1]).append(
					'<p> {{ trans("messages.Finish") }} '+ idToData(locatsList[locatsList.length-1],'text') +' {{ trans("messages.At") }} '+converttime(timeline[timeline.length-1])+'</p>'
				);
			}
		}

		j=0;
		for(; i<timeline.length;i+=2){
			if(i+1<timeline.length){
				title[i].innerText = idToData(locatsList[j],'text') + ' '+timeline[i].toLocaleDateString();
				title[i+1].innerText = '{{ trans("messages.TralTo") }} '+timeline[i+1].toLocaleDateString();
				$(title[i+1]).css('color','red');
			}
			
			if(i>0&&i<timeline.length-1){
				$(heading[i]).append('<div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-utensils" value="'+j+'"></i></div><span class="nearby-find-text">Restaurant</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-store" value="'+j+'"></i></div><span class="nearby-find-text">Store</span></div><div class="nearby-find-content"><div class="nearby-find-icon"><i class="fas fa-coffee" value="'+j+'"></i></div><span class="nearby-find-text">Coffe Store</span></div>');
				$(body[i]).append('<p>{{ trans("messages.Arr") }} '+ idToData(locatsList[j],'text') +' {{ trans("messages.At") }} '+converttime(timeline[i])+' {{ trans("messages.VisIn") }} '+ converttime(idToData(locatsList[j],'duration'))+'</p><br><p>'+idToData(locatsList[j],'description')+'</p><p><a href="'+idToData(locatsList[j],'link')+'" target="_blank">View the location</a></p><div class="show-more">{{ trans("messages.Showmore") }} <i class="fa fa-chevron-down" aria-hidden="true"></i></div>');
			}
			if(i<timeline.length-2){
				$(body[i+1]).append('<p>{{ trans("messages.CplVis") }} '+idToData(locatsList[j],'text')+' {{ trans("messages.At") }} '+converttime(timeline[i+1])+' {{ trans("messages.GoNxt") }}</p>');
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
		document.getElementById('total-time-value').innerText = converttime('duration');
		
		for(i= timeline.length; i<li.length;i++)
			li[i].style.display = "none"; 
		nearByFind();
	}

	function nearByFind(){
		fa = document.querySelectorAll('.nearby-find-icon');
		for( var i =0;i<fa.length;i++)
			fa[i].addEventListener('click',function(){
				$('#clear-service').show();
				var type = this.parentElement.children[1].innerText;
				if(type == 'Restaurant'){
					type = 'restaurant'
				} else if(type == 'Store'){
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
					for(var i = 0; i<resmarkers.length;i++)
						resmarkers[i].setMap(null);
					$('.map-marker-label[value="nearByPlace"]').remove();
					
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
		customLabel(resMarker,'nearByPlace');

	}
	// convert time in seconds and HH:MM format
	function converttime(time){
		if(time === 'duration'){
			let seconds = (timeline[timeline.length-1].getTime() - timeline[0])/1000;
			var d = Math.floor(seconds / (3600*24));
			seconds  -= d*3600*24;
			let h   = Math.floor(seconds / 3600);
			seconds  -= h*3600;
			let m = Math.floor(seconds / 60);
			seconds  -= m*60;
			if(seconds>=30) m+=1;

			let tmp = [];
			if(d){(d==1)?tmp.push(d + ' Day'):tmp.push(d + ' Days')}
			if(d || h){(h==1)?tmp.push(h + ' Hour'):tmp.push(h + ' Hours')};
			if(d || h || m){(m==1)?tmp.push(h + ' Minute'):tmp.push(h + ' Minutes')};
			return tmp.join(' ');
		}
		if(time === 'duration_second'){
			let seconds = (timeline[timeline.length-1].getTime() - timeline[0])/1000;
			return seconds;
		}
		if(time instanceof Date ){
			let tmpDate = time.toTimeString();
			let tmp = tmpDate.split(":");
			time = tmp[0]+":"+tmp[1];
			return time;
		}
		if(typeof(time) == "number"){
			let hours = Math.floor(time / 3600);
			time %= 3600;
			let minutes = Math.floor(time / 60);
			minutes = String(minutes).padStart(2, "0");
			hours = String(hours).padStart(2, "0");
			time = hours + ":" + minutes;
		} else{
			let a = time.split(':'); 
			time = (+a[0]) * 60 * 60 + (+a[1]) * 60; 
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
	console.log(1); 
		$('#overlay').show()
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
			console.log(2);
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
		console.log("123")
		timealert();
	}

	function timealert(serverres){

				console.log('here');
		choosendur = (document.querySelector("#time-end")._flatpickr.selectedDates[0] - document.querySelector("#time")._flatpickr.selectedDates[0])/1000;
		if(isNaN(choosendur)){

				console.log('here');
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
				console.log('here');
						processanddrawrouteclient();
					} else {
				console.log('here');
						bestWay();
					}
				});
				$("#timeAlert-close").click(()=>{
					$('#saveTour').hide()
					$('#overlay').hide();
					$("#timeAlert").modal("hide");
					// $('.options-list').hide();
					// $(document.getElementById('time-end').parentElement).show();        
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
		let routeOptimized = {
			route: [],
			value: 0
		}
		let total = 0;

		// Optimize by duration with the start location
		if(!dello && isopt == 1 && startlocat != undefined){
		// Loop all route posible to calculate the best way
			for(var i = 0 ;i<allRoutePosible.length; i++){
				var A = allRoutePosible[i];
				
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
				}
		}

		// Optimize by cost with the start location
		if(!dello && isopt == 2 && startlocat != undefined){
			for(var i = 0 ;i<allRoutePosible.length; i++){// Loop all route posible to calculate the best way
				var A = allRoutePosible[i];
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

		// if(!dello && isopt == 1 && startlocat == undefined && newPlaceIdArr.length){
		if(!dello && isopt == 1 && startlocat == undefined){
			for(var i = 0 ;i<allRoutePosible.length; i++){
				var A = allRoutePosible[i];
				for(var j = 0 ;j<A.length-1; j++){
					total+= idToData(locatsList[A[j]],'duration');
					total+= disresponse.rows[A[j]-1].elements[A[j+1]-1].duration.value;
				}
				total+= idToData(locatsList[A[A.length-1]-1],'duration');

				if($('#is-back').is(':checked')) 
						total += disresponse.rows[A[A.length-1]-1].elements[0].duration.value;

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

		// if(!dello && isopt == 2 && startlocat == undefined && newPlaceIdArr.length){
			if(!dello && isopt == 2 && startlocat == undefined){
			for(var i = 0 ;i<allRoutePosible.length; i++){
				var A = allRoutePosible[i];
				for(var j = 0 ;j<A.length-1; j++)
					total+= disresponse.rows[A[j]-1].elements[A[j+1]-1].distance.value;

				if($('#is-back').is(':checked')) 
						total += disresponse.rows[A[A.length-1]-1].elements[0].distance.value;

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

		if(dello && startlocat != undefined){
			let allRouteOptimize =[];
			for(var i = 0 ;i<allRoutePosible.length; i++){
					var A = allRoutePosible[i];
					let tmpRouteOpt = {
						route: [],
						value: 0
					}
					for(var j =0;j<A.length;j++){
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
						if(total<=choosendur){
							if (tmpRouteOpt.value == 0){ 
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}else {
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}
						} else {
							allRouteOptimize.push(tmpRouteOpt);
							break;
						}
					} 
					total = 0;
			}
			let min = allRouteOptimize[0];
			for(let k = 1; k < allRouteOptimize.length; k++){
				if(allRouteOptimize[k].route.length >= min.route.length){
					if(allRouteOptimize[k].route.length > min.route.length){
						min = allRouteOptimize[k];
					}else if(allRouteOptimize[k].value <= min.value){
						min = allRouteOptimize[k]
					}
				}
			}

			routeOptimized = min;
		}

		if(dello && startlocat == undefined){
			let allRouteOptimize =[];
			for(var i = 0 ;i<allRoutePosible.length; i++){
					var A = allRoutePosible[i];
					let tmpRouteOpt = {
						route: [],
						value: 0
					}
					for(var j =0;j<A.length;j++){
						if(j!=0){
							 total += disresponse.rows[A[j-1]-1].elements[A[j]-1].duration.value;
						}
						total+= idToData(locatsList[A[j]-1],'duration');
						var tmptotal = total;
						if($('#is-back').is(':checked')){
							total+= disresponse.rows[A[j]].elements[0].duration.value;
						}
						if(total<=choosendur){
							if (tmpRouteOpt.value == 0){ 
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}else {
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}
						} else {
							allRouteOptimize.push(tmpRouteOpt);
							break;
						}
					} 
					total = 0;
			}
			let min = allRouteOptimize[0];
			for(let k = 1; k < allRouteOptimize.length; k++){
				if(allRouteOptimize[k].route.length >= min.route.length){
					if(allRouteOptimize[k].route.length > min.route.length){
						min = allRouteOptimize[k];
					}else if(allRouteOptimize[k].value <= min.value){
						min = allRouteOptimize[k]
					}
				}
			}

			routeOptimized = min;
		}


		var tmp = [];

	
		for(var i=0;i<routeOptimized.route.length;i++)
			tmp[i] = locatsList[routeOptimized.route[i]-1];
		locatsList = tmp;

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

	// Sortable location list text
	function sortlocats(){
		var rowSize = 45; // => container height / number of items
		var container = document.querySelector(".container");
		var listItems = Array.from(document.querySelectorAll(".list-item")); // Array of elements
		// if(listItems.ondragexit == true){
			
		// }
		
		var sortables = listItems.map(Sortable); // Array of sortables
		var total = sortables.length;
		TweenLite.to(container, 0.5, { autoAlpha: 1 });
		function changeIndex(item, to) {
			// console.log('AAAAAAAAAAAAA')
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
			updateRoute()
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
	function customLabel(marker,place_id) {
		var label = marker.label;
		marker.label = new MarkerLabel({
			map: marker.map,
			marker: marker,
			text: label
		},place_id);
		marker.label.bindTo('position', marker, 'position');
		marker.setLabel('');


	};

	var MarkerLabel = function(options,place_id) {
		this.setValues(options);
		this.span = document.createElement('span');
		this.span.className = 'map-marker-label';
		if(place_id!=undefined);
			this.span.setAttribute('value',place_id);
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
	<?php use Illuminate\Support\Arr;
				use App\Models\Destination; 
	?>
	@if(!isset($to_des))
		$("#btnSaveNameTour").click(function(){
			let nameTour = $('input[name="nameTour"]').val();
			if(nameTour == "")
			{
				alert("Please enter the tour name first");
			}
			else
			{
				let $url_path = '{!! url('/') !!}';
				let _token = $('meta[name="csrf-token"]').attr('content');
				let routeDetail=$url_path+"/saveTour";
				let timeStart = $('#time input').val();
				let timeEnd = converttime('duration_second');
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
				let tmparr = [];
				let val = {};
				if(startlocat != undefined){
					val.de_id = startlocat;
					let coor = idToData(startlocat,'LatLng');
					val.location = coor.lat+"|"+coor.lng;
					val.de_name = idToData(startlocat,'text');
					val.de_duration = idToData(startlocat,'duration');
					val.de_default = idToData(startlocat,'default');
				}

				locatsList.forEach(ele=>{
					let coor = idToData(ele,'LatLng');
					let tmp = ele+'';
					tmparr.push({
						de_id: tmp,
						location: coor.lat+"|"+coor.lng,
						de_name: idToData(ele,'text'),
						de_duration: idToData(ele,'duration'),
						de_default: idToData(ele,'default')
					})
				})

				$.ajax({
							url:routeDetail,
							method:"get",
							data:{_token:_token,tmparr:tmparr,timeStart:timeStart,timeEnd:timeEnd,to_comback:to_comback,to_optimized:to_optimized,nameTour:nameTour,val:val},
							success:function(data){ 
								// alert("Your tour has been saved");
								// $("#saveTour").css("display","none");
								// $("#enterNameTour").modal("hide");
								location.replace(data[1]);
							}
				});
			}
		});
		$("#btnSaveShareTour").click(function(){
			let nameTour = $('input[name="nameTour"]').val();
			if(nameTour == "")
			{
				alert("Please enter the tour name first");
			}
			else
			{
				let $url_path = '{!! url('/') !!}';
				let _token = $('meta[name="csrf-token"]').attr('content');
				let routeDetail=$url_path+"/saveTour";
				let timeStart = $('#time input').val();
				let timeEnd = converttime('duration_second');
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
				let tmparr = [];
				let val = {};
				if(startlocat != undefined){
					val.de_id = startlocat;
					let coor = idToData(startlocat,'LatLng');
					val.location = coor.lat+"|"+coor.lng;
					val.de_name = idToData(startlocat,'text');
					val.de_duration = idToData(startlocat,'duration');
					val.de_default = idToData(startlocat,'default');
				}

				locatsList.forEach(ele=>{
					let coor = idToData(ele,'LatLng');
					let tmp = ele+'';
					tmparr.push({
						de_id: tmp,
						location: coor.lat+"|"+coor.lng,
						de_name: idToData(ele,'text'),
						de_duration: idToData(ele,'duration'),
						de_default: idToData(ele,'default')
					})
				})
				$.ajax({
						url:routeDetail,
						method:"get",
						data:{_token:_token,tmparr:tmparr,timeStart:timeStart,timeEnd:timeEnd,to_comback:to_comback,to_optimized:to_optimized,nameTour:nameTour,val:val},
						success:function(data){ 
							$("#rankModal").modal("show");
							$("#route_ID").val(data[0]);
						}
				});
			}
		});
	@else
		$("#btnSaveNameTour").click(function(){
			let nameTour = $('input[name="nameTour"]').val();
			if(nameTour == "")
			{
				alert("Please enter the tour name first");
			}
			else
			{
				let $url_path = '{!! url('/') !!}';
				let _token = $('meta[name="csrf-token"]').attr('content');
				let routeId = {{$id}};
				let routeDetail=$url_path+"/editRoute/"+routeId;
				let timeStart = $('#time input').val();
				let timeEnd = converttime('duration_second');
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
					// to_optimized = $('input[name="durdis"]').val();
					to_optimized = "1";
				}
				let tmparr = [];
				let val = {};
				if(startlocat != undefined){
					val.de_id = startlocat;
					let coor = idToData(startlocat,'LatLng');
					val.location = coor.lat+"|"+coor.lng;
					val.de_name = idToData(startlocat,'text');
					val.de_duration = idToData(startlocat,'duration');
					val.de_default = idToData(startlocat,'default');
				}

				locatsList.forEach(ele=>{
					let coor = idToData(ele,'LatLng');
					let tmp = ele+'';
					tmparr.push({
						de_id: tmp,
						location: coor.lat+"|"+coor.lng,
						de_name: idToData(ele,'text'),
						de_duration: idToData(ele,'duration'),
						de_default: idToData(ele,'default')
					})
				})
				$.ajax({
							url:routeDetail,
							method:"get",
							data:{_token:_token,tmparr:tmparr,timeStart:timeStart,timeEnd:timeEnd,to_comback:to_comback,to_optimized:to_optimized,nameTour:nameTour,val:val},
							success:function(data){ 
								location.reload();
							}
				});
			}
		});
		$("#saveTour").show();
		//vẽ đường
		<?php 
				$pieces_2 = explode("|", $to_des);
				$array = array();
				for ($i=0; $i < count($pieces_2)-1; $i++) {
						$array = Arr::add($array, $i ,$pieces_2[$i]);
				}
		 ?>
		@foreach($array as $value)
			locatsList.push('{{$value}}');
		@endforeach
			$("#time input").val('{{date("d-m-Y h:i", strtotime($to_starttime))}}');
		@if($to_endtime != "")
			$("#time-end input").val('{{date("d-m-Y h:i", strtotime($to_endtime))}}');
		@endif
		@if($to_comback == '1')
			$('#is-back').prop('checked',true);
		@endif
		@if($to_optimized == '1')
			//$('.dur-dis[value="1"]').prop('checked', true);
		@elseif($to_optimized == '2')
			//$('.dur-dis[value="2"]').prop('checked', true);
		@else
			$('#is-opt').prop('checked',false);
		@endif
		<?php $dem = 0 ?>
		@if(count($latlng_new) > 0)
				@foreach($latlng_new as $value)
						locationsdata.push({
								de_name: "{{$dename_new[$dem]}}",
								location: <?php echo json_encode($latlng_new[$dem]); ?>,
								place_id: "{{$placeId_new[$dem]}}",
								de_duration: {{$duration_new[$dem]}},
								de_default: 1
						})
						newPlaceIdArr.push("{{$placeId_new[$dem]}}")
						<?php $dem++; ?>
				@endforeach
		@endif
		@if( $latlng_start != "")
			locationsdata.push({
					de_name: "{{$dename_start}}",
					location: <?php echo json_encode($latlng_start); ?>,
					place_id: "{{$placeId_start}}",
					de_duration: {{$duration_start}},
					de_default: 1
			})
			newPlaceIdArr.push("{{$placeId_start}}")
		@endif
		// startlocat
		@if( $latlng_start != "")
			startlocat = "{{$placeId_start}}";
			staMarker = new google.maps.Marker({
						label: idToData(startlocat,'text'),
			});
			staMarker.setMap(map);
			staMarker.setPosition(idToData(startlocat,'LatLng'));
			$('#your-start').show();
			document.getElementById('your-start').innerHTML= idToData(startlocat,'text')+'<span id="your-start-close">×</span>';
			$('#your-start-close').click(()=>{
					//staMarker.setMap(null);
					staMarker = undefined;
					$('.map-marker-label').remove();
					$('#your-start').hide();
					startlocat = undefined;
					updateRoute();
			})
			customLabel(staMarker);
		@endif
		// drawRoutes
		@if($to_des != "")
			idToData(null,'LatLngArr');
			drawRoutes();
			$("#tab-timeline").attr('style','display: block');
			$("#get-route").hide();
			// setTimeout(function(){ 
			//   $("#get-route").click();
			// }, 200);
			let height = ($('.list-item').length+1) * 45 +5;
			$('#container-height').css('height',height+'px');
		@endif
	@endif
};
</script> 
	<link href="{{asset('css/styles.css')}}" rel="stylesheet" />
	<link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" href="{{asset('css/retour.css')}}">
	<link rel="stylesheet" href="{{asset('css/notlogin.css')}}">
	<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="menu_siteBar">
			<div class="logoDashboard">
				<a href="{{route('login')}}">TOUR ADVICE</a>
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
					<?php 
							use Illuminate\Support\Facades\Auth;
							$user = Auth::user();
					?>
					@if(Auth::check())
					<li class="li_menu_acc">
						<p class="menu_title_acc text-uppercase" id="your_account">{{ trans('messages.Youraccount') }} <i class="fas fa-sort-down"></i></p>
						<div class="menu_content">
							@if($user->us_type == "1")
									<p id="comback_admin">{{ trans('messages.adminPage') }}</p>
							@endif
							<p id="personalInfo">{{ trans('messages.Aboutyou') }}</p>
							<p id="p_logout">{{ trans('messages.Logout') }}</p>
						</div>
					</li>
					@else
					<li class="li_menu_acc">
						<p class="menu_title_acc text-uppercase" id="your_account" data-toggle="modal" data-target="#modalLogin">Login</p>
					</li>
					@endif
				</ul>
			</div>
		</div>
		<!-- Login modal 1-->
		<div class="portfolio-modal modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="portfolioModal1Label" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fas fa-times"></i></span>
					</button>
					<div class="modal-body text-center">
						<div class="container-fuild">
							<div class="row justify-content-center">
								<div class="col-lg-8">
								<!-- Portfolio Modal - Title-->
									<h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal1Label">{{ trans('messages.Login') }}</h2>
																		<!-- Icon Divider-->
									<div class="divider-custom">
										<div class="divider-custom-line"></div>
										<div class="divider-custom-icon"><i class="fas fa-star"></i></div>
										<div class="divider-custom-line"></div>
									</div>
									<p class="mb-5">{{ trans('messages.pleaseLogin') }}</p>
									<!-- Form login -->
									<form class="loginForm mb-4 pt-3 pb-3" method="post" action="{{route('postLogin')}}">
										<input type="hidden" name="_token" value="{{ csrf_token() }}" />
										@if ($message = Session::get('error'))
										<div class="alert alert-danger alert-block">
											<button type="button" class="close" data-dismiss="alert">x</button>
											<strong>{{$message}}</strong>
										</div>
										@endif
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
										<div class="txt_field">
											<input type="email" name="us_email" required="">
											<span></span>
											<label>Email</label>
										</div>
										<div class="txt_field">
											<input type="password" name="us_password" required="" maxlength="32">
											<span></span>
											<label>Password</label>
										</div>
										<div class="div_submit">
											<input type="submit" value="{{ trans('messages.Login') }}"> 
											<input type="button" id="btn_register" data-toggle="modal" data-target="#modalRegis" value="{{ trans('messages.Registration') }}">
										</div>

										<div class="pass">
											{{ trans('messages.forgotPassword') }}
										</div>
									</form>
									<!-- Form login -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Model -->
		<!-- Masthead-->
		<div id="overlay">
			<div class="loader"></div>
		</div> 
		<div id="wrap"> 

			<div id="map" class="tabcontent" ></div>
		
			<!--timeline-->
			<div id="timeline" class="tabcontent"  style="display: none;">
				<div class="row">
					<ul class="timeline" style="width: 100%;">
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
				<div id="total-time">
					<span>Total time: </span>
					<span id="total-time-value"></span>
				</div>
			</div>
			<!--timeline ends-->

			<!-- control panel -->
			<div id='control-panel'>
				<div id="switch-tab" class="control-panel">
					<button class="tablinks active" onclick="openTab(event, 'map')"  id="tab-map">{{ trans('messages.Map') }}</button>
					<button class="tablinks" id="tab-timeline" style="display: none;" onclick="openTab(event, 'timeline')">{{ trans('messages.Timeline') }}</button>
				</div>
				<div id="search-panel" class="control-panel">
						<div id="search-panel-control">
							<div id="select-box">
								<!-- <i id="drop-down-arow" class="fa fa-angle-down " aria-hidden="true"></i>  -->
								<select id="waypoints">
								</select>
								
								<div id="select-box-reset" class="reset-all">
									<a href="#" class="tooltip-test" title="Click to reset"><i class="fa fa-undo fa-lg" aria-hidden="true"></i></a>
								</div>
							</div>
							<button id="add-button">{{ trans('messages.Addlocation') }}</button>
							<!-- <button id="get-route" style="display: none;">Update</button> -->
						</div>
						<div id="your-start" data-strtlocat='0' data-clsclk="0" style="display: block;">Click on map to choose start location
						<!-- <span id="your-start-close">×</span> -->
						</div>
						<div class="container" id='container'>  
						</div>
						<div id='container-height'></div>
				</div>
				<div id='set-dur-panel' class="control-panel" value="" style="height: 130px; display: none;">
					<b>How long would you like to stay in this place?</b>
					<div>
						<input id='set-dur-input' class="form-control" type="text"></input>
						<!-- <button id='set-dur-button' style="width: 20px; height: 20px;" ></button> -->
					</div>
						
				</div>
				<div id="options-control" class="control-panel">
					<div id="options-control-title"><b>{{ trans('messages.Selecttouroptions') }}</b></div>
					<div class="options-list options-list1">
						<div><b>{{ trans('messages.Selectthestarttime') }}:</b></div>
						<!-- <input type="date" style="width: 100%; margin-bottom: 0.5rem" >
						<div>
							<input type="time" id="time" value="07:00" style="width: 80%">
						</div> -->
						<!-- <div id="date-start" value="" style="margin-bottom: 0.5rem;display: flex;">
							<input type="text" placeholder="Select Date.." data-input style="width: 100%">  
							<a class="input-button" title="clear" data-clear style="border: 1px solid #bbb;display: inline-block;padding: 1px 10px;cursor: pointer;margin-left: 0.2rem">
									<i class="fas fa-times"></i>
							</a>
						</div> -->
						<div id="time" value="" style="margin-bottom: 0.5rem;display: flex;">
							<input type="text" placeholder="Select Time.." data-input style="width: 100%">  
							<a class="input-button" title="clear" data-clear style="border: 1px solid #bbb;display: inline-block;padding: 1px 10px;cursor: pointer;margin-left: 0.2rem">
									<i class="fas fa-times"></i>
							</a>
						</div>
					</div>
					<div class="options-list">
						<div><b>{{ trans('messages.Selecttheendtime') }}:</b></div>
						<!-- <div id="date-end" value="" style="margin-bottom: 0.5rem;display: flex;">
							<input type="text" placeholder="Select Date.." data-input style="width: 100%">  
							<a class="input-button" title="clear" data-clear style="border: 1px solid #bbb;display: inline-block;padding: 1px 10px;cursor: pointer;margin-left: 0.2rem">
									<i class="fas fa-times"></i>
							</a>
						</div> -->
						<div id="time-end" value="" style="margin-bottom: 0.5rem;display: flex;">
							<input type="text" placeholder="Select Time.." data-input style="width: 100%">  
							<a class="input-button" title="clear" data-clear style="border: 1px solid #bbb;display: inline-block;padding: 1px 10px;cursor: pointer;margin-left: 0.2rem">
									<i class="fas fa-times"></i>
							</a>
						</div>
						<!-- <div>
							<input type="time" id="time-end" value="" style="width: auto;">
							<i class="fas fa-times" style="width: auto;"></i>
						</div> -->
						
					</div>
					<div class="options-list">
						<input type="checkbox" id='is-back'> <b>{{ trans('messages.Comebackthestart') }}</b>
					</div>
					<div class="options-list" style="display: none;">
						<div><input type="checkbox" id='is-opt' style="display: none;" checked>
							<!-- <b> {{ trans('messages.Optimized') }}</b> -->
						</div>
						<div id="is-opt-sub" style="display: none;">
							<input type="radio" class="dur-dis" name="durdis" value="1" checked> {{ trans('messages.Duration') }}   
							<input type="radio" class="dur-dis" name="durdis" value="2"> {{ trans('messages.Cost') }}
						</div>
					</div>
					<button id="clear-service" style="display: none;">Clear service</button>
				</div>
				<!-- <div id="switch-tab" class="control-panel">
					<button class="tablinks" onclick=""  id="saveTour">{{ trans('messages.SaveTour') }}</button>
					<button class="tablinks reset-all"  onclick="">{{ trans('messages.Reset') }}</button>
				</div> -->
				<div id="switch-tab" class="control-panel">
					<button id="get-route">{{ trans('messages.findWay') }}</button>
					<style type="text/css">
						#btn-rating{display: none;}
					</style>
					<button class="tablinks" onclick=""  id="btn-rating">Rating</button>
					<button class="tablinks" onclick=""  id="saveTour">
					@if(!isset($to_des))
						{{ trans('messages.SaveTour') }}
					@else
					{{ trans('messages.EditTour') }}
					@endif
					</button>
					<!-- <button class="tablinks reset-all"  onclick="">{{ trans('messages.Reset') }}</button> -->
				</div>
			</div>
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
				<button type="button" class="btn btn-primary" id="btn_editInfo">{{ trans('messages.Editinformation') }}</button>
				<button type="button" class="btn btn-primary" id="btn_submitInfo">{{ trans('messages.SubmitEdit') }}</button>
			</div>
		</div>
	</div>
</div>
<!-- /modal -->
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
				<button type="button" class="btn btn-primary" id="btnSaveNameTour">
					@if(!isset($to_des))
						{{ trans('messages.SaveTour') }}
					@else
						{{ trans('messages.EditTour') }}
					@endif
				</button>
				@if(!isset($to_des))
					<button type="button" class="btn btn-success" id="btnSaveShareTour">Save and share the tour</button>
				@endif
			</div>
		</div>
	</div>
</div>
<!-- modal đánh giá -->
<!-- Modal -->
<div class="modal fade" id="rankModal" tabindex="-1" role="dialog" aria-labelledby="rankModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="rankModalLabel">Share your tour with everyone</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="{{route('user.shareTour')}}" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" id="route_ID" name="ro_id">
				<div class="modal-body">
					<div class="container-fuild">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-12">
								<p class="font-weight-bold font-italic">Rating for your tour</p>
							</div>
							<div class="col-md-12 col-sm-12 col-12 mb-3" id="div_Starrank_tour">
								<i class="fas fa-star star_1 fa-2x"  data-value="1" style="cursor: pointer;"></i>
								<i class="fas fa-star star_2 fa-2x" data-value="2" style="cursor: pointer;"></i>
								<i class="fas fa-star star_3 fa-2x" data-value="3" style="cursor: pointer;"></i>
								<i class="fas fa-star star_4 fa-2x"  data-value="4" style="cursor: pointer;"></i>
								<i class="fas fa-star star_5 fa-2x" data-value="5" style="cursor: pointer;"></i>
								<i class="fas fa-star star_6 fa-2x" data-value="6" style="cursor: pointer;"></i> 
								<i class="fas fa-star star_7 fa-2x" data-value="7" style="cursor: pointer;"></i>
								<i class="fas fa-star star_8 fa-2x" data-value="8" style="cursor: pointer;"></i>
								<i class="fas fa-star star_9 fa-2x" data-value="9" style="cursor: pointer;"></i>
								<i class="fas fa-star star_10 fa-2x" data-value="10" style="cursor: pointer;"></i>
							</div>
							<input type="hidden" id="star_Share" name="star">
							<div class="col-md-12 col-sm-12 col-12">
								<p class="font-weight-bold font-italic">Introduce about your tour</p>
							</div>
							<div class="col-md-12 col-sm-12 col-12 mb-3">
									<textarea id="textarea_share" class="form-control" name="content" placeholder="Introduce about your tour"></textarea>
							</div>
							<div class="col-md-12 col-sm-12 col-12">
								<p class="font-weight-bold font-italic">Photo to represent your tour</p>
							</div>
							<div class="col-md-12 col-sm-12 col-12 mb-3">
									<div class="Update_img_tour">Upload Image</div>
									<p class="name_file_tour font-weight-bold font-italic"></p>
									<input accept="image/*" type="file" name="image_tour" class="form-control" id="img_input_Rank">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success" id="btn_submit_sharetour">Share tour</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- modal  -->
<!-- Modal notification-->
<div class="modal fade" id="notification" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="font-italic font-weight-bold" id="contentNotification"></h5>
      </div>
    </div>
  </div>
</div>
<!-- /modal notification -->
<!-- Change pass -->
<div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="changePassLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="changePassLabel">{{trans('messages.Notification')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="text-success">{{trans('messages.defaultPass')}}</h4>
      </div>
    </div>
  </div>
</div>  
<!-- /Modal Change pass -->
<!-- End Modal -->
		<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/utils/Draggable.min.js'></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/TweenMax.min.js'></script>
		<script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="{{ asset('js/bootstrap-duration-picker.js')}}"></script>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-duration-picker.css')}}">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



<script type="text/javascript">
	$(document).ready(function(){
		$("#saveTour").click(function(){
			//loginForm
			$(".loginForm").submit(function(e){
				e.preventDefault();
				let form = $(this);
			    var url = "{{route('postLoginAjax')}}";
			    $.ajax({
		           type: "POST",
		           url: url,
		           data: form.serialize(),
		           success: function(data)
		           {
		               if(data == "lock")
	               		{
	               			$("#modalLogin").modal("hide");
	               			$("#notification").modal("show");
		               		$("#contentNotification").removeClass("text-success");
		               		$("#contentNotification").addClass("text-danger");
		               		$("#contentNotification").html("Your account is locked!");
	               		}
		               else if(data == "fail login")
		               {
		               		$("#modalLogin").modal("hide");
		               		$("#notification").modal("show");
		               		$("#contentNotification").removeClass("text-success");
		               		$("#contentNotification").addClass("text-danger");
		               		$("#contentNotification").html("Login unsuccessful, you entered the wrong account or password!");
		               	}
		               else
		               {
		               		$("#modalLogin").modal("hide");
		               		$(".li_menu_acc").empty();
		               		if(data[0] == "admin")
		               			$(".li_menu_acc").append('<p class="menu_title_acc text-uppercase" id="your_account">{{ trans("messages.Youraccount") }} <i class="fas fa-sort-down"></i></p><div class="menu_content"><p id="comback_admin">{{ trans("messages.adminPage") }}</p><p id="personalInfo">{{ trans("messages.Aboutyou") }}</p><p id="p_logout">{{ trans("messages.Logout") }}</p></div>');
		               		else if(data[0] == "user")
		               			$(".li_menu_acc").append('<p class="menu_title_acc text-uppercase" id="your_account">{{ trans("messages.Youraccount") }} <i class="fas fa-sort-down"></i></p><div class="menu_content"><p id="personalInfo">{{ trans("messages.Aboutyou") }}</p><p id="p_logout">{{ trans("messages.Logout") }}</p></div>');
		               		$("#enterNameTour").modal("show");
		               		$( "#saveTour").unbind( "click" );
		               		$("#saveTour").click(function(){
								$("#enterNameTour").modal("show");
								$("#modalLogin").modal("hide");
							});
		               }
		           }
		        });
			})
		});
		// input time
		let todayDate = new Date();
			todayDate.setHours(7,0,0);
			$("#time").flatpickr({
				wrap: true,
				onChange: function(selectedDates, dateStr, instance) {
					$("#time").val(selectedDates);
				},
				enableTime: true,
				minDate: "today",
				defaultDate: todayDate,
				dateFormat: "d-m-Y H:i"
			});
			$('#time').val(todayDate);

			$("#time-end").flatpickr({
						wrap: true,
						onChange: function(selectedDates, dateStr) {
							$("#time-end").val(dateStr);
						},
						enableTime: true,
				dateFormat: "d-m-Y H:i"
				});

		@for($i = 1; $i<= 10; $i++)
			$("#div_Starrank_tour .star_{{$i}}").click(function(){
					@for($j = 1 ; $j <= 10; $j++)
							$("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
					@endfor
					@for($j = 1 ; $j <= $i; $j++)
							$("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
					@endfor
					console.log($(this).attr("data-value"));
					$("#star_Share").val($(this).attr("data-value"));
			});
		@endfor
		$(".Update_img_tour").click(function(){
			$("#img_input_Rank").click();
		});
		$("#img_input_Rank").change(function(){
			$(".Update_img_tour").css("background","#ff9700");
			$(".name_file_tour").html("File name: &#60;"+$("#img_input_Rank").val().split('\\').pop()+"&#62;");
			$(".name_file_tour").show();
		});
		$(".li_menu_acc").on('click','#comback_admin',function(){
			location.replace("{{route('admin.generalInfor')}}");
		})
		$('button').click(function(){
			$('button').css('border','none');
			$('button').css('outline','none');
		});
		@if(Auth::check())
			$("#saveTour").click(function(){
					$("#enterNameTour").modal("show");
			});
		@else
			$("#saveTour").click(function(){
					$("#modalLogin").modal("show");
			});
		@endif
		
		$('#set-dur-input').durationPicker({
			onChanged: function (value, isInitializing) {
				$('#set-dur-panel').val(value);
		}
		}); 
		
		$(".menu_title_start").click(function(){
			location.replace("{{route('user.maps')}}");
		});
		$(".li_menu_acc").on('click','#p_logout',function(){
			location.replace("{{route('logout')}}");
		});
		$(".li_menu_acc").on('click','#personalInfo',function(){
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
		$(".li_menu_acc").on('click','.menu_title_acc',function(){
			if ($('.menu_content').is(':visible'))
			{
					$(".menu_content").slideUp("fast");
			}
			else
			{
					$(".menu_content").slideDown("fast");
			}
		})
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
@if(!Auth::check())
	<!-- modal dashboard -->
	<!-- Modal reggis -->
	<div class="modal fade" id="modalRegis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.userRegistration') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{{route('register')}}" method="post">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<div class="container-fluid">
									<div class="row">
											<div class="col-md-3 col-sm-6 col-6 mb-3">
													<p class="text-left font-weight-bold">Email</p>
											</div>
											<div class="col-md-9 col-sm-6 col-6 mb-3">
													<input type="email" class="form-control" placeholder="Enter Email" required="" name="email">
											</div>
											<div class="col-md-3 col-sm-6 col-6 mb-3">
													<p class="text-left font-weight-bold">Password</p>
											</div>
											<div class="col-md-9 col-sm-6 col-6 mb-3">
													<input type="password" class="form-control" placeholder="Enter password" name="password" required="">
											</div>
											<div class="col-md-3 col-sm-6 col-6 mb-3">
													<p class="text-left font-weight-bold">{{ trans('messages.confirmPassword') }}</p>
											</div>
											<div class="col-md-9 col-sm-6 col-6 mb-3">
													<input type="password" class="form-control" placeholder="{{ trans('messages.confirmPassword') }}" name="confirm" required="">
											</div>
											<div class="col-md-3 col-sm-6 col-6 mb-3">
													<p class="text-left font-weight-bold">{{ trans('messages.FullName') }}</p>
											</div>
											<div class="col-md-9 col-sm-6 col-6 mb-3">
													<input type="text" class="form-control" placeholder="{{ trans('messages.FullName') }}" name="fullname" required="">
											</div>
											<div class="col-md-3 col-sm-6 col-6 mb-3">
													<p class="text-left font-weight-bold">{{ trans('messages.Gender') }}</p>
											</div>
											<div class="col-md-9 col-sm-6 col-6 mb-3">
													<select class="form-control" name="gender">
															<option value="Male">Male</option>
															<option value="Female">Female</option>
													</select>
											</div>
											<div class="col-md-3 col-sm-6 col-6 mb-3">
													<p class="text-left font-weight-bold">{{ trans('messages.Age') }}</p>
											</div>
											<div class="col-md-9 col-sm-6 col-6 mb-3">
													<input type="number" class="form-control" placeholder="{{ trans('messages.Age') }}" name="age" required="">
											</div>
									</div>
							</div>
							<hr>
							<div class="div_btn_register">
									<input type="submit" class="btn btn-primary" value="{{ trans('messages.Registration') }}">
									<p id="p_backLogin">{{ trans('messages.youHaveAcc') }} <span class="backFormLogin">{{ trans('messages.Login') }}</span></p>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal forgotpass -->
	<div class="modal fade" id="modalForgotPass" tabindex="-1" role="dialog" aria-labelledby="modalForgotPassLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalForgotPassLabel">{{trans('messages.forgotPassword')}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
							<div class="row">
									<div class="col-md-6 col-sm-12 col-12 mb-2">
											<p class="pt-2 font-weight-bold">{{trans('messages.enterEmail')}} </p>
									</div>
									<div class="col-md-6 col-sm-12 col-12 mb-2">
											<p id="icon_correct" class="text-success"><i class="fas fa-check"></i> {{trans('messages.correctEmail')}}</p>
											<p id="icon_incorrect" class="text-danger"><i class="fas fa-check"></i> {{trans('messages.incorrectEmail')}}</p>
											<input type="text" class="form-control" placeholder="Enter your email" id="inputEmail">
									</div>
									<div class="col-md-6 col-sm-12 col-12 mb-2">
									</div>
									<div class="col-md-6 col-sm-12 col-12 mb-2">
											<button type="button" class="btn btn-info" id="btn_senKey">{{trans('messages.sendKey')}}</button>
									</div>
							</div>
							<div class="row" id="formCheckKey">
									<div class="col-md-6 col-sm-12 col-12 mb-2">
											<p class="pt-2 font-weight-bold">{{trans('messages.enterKey')}} </p>
									</div>
									<div class="col-md-6 col-sm-12 col-12 mb-2">
											<p id="key_incorrect" class="text-danger"><i class="fas fa-check"></i> {{trans('messages.incorrectKey')}} </p>
											<input type="text" class="form-control text-uppercase" placeholder="{{trans('messages.enterKey')}}" id="inputKey">
									</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- hết modal dashboard -->
	<script type="text/javascript">
		$(document).ready(function(){
			//trang dashboard
			$('#modalRegis').on('shown.bs.modal', function () {
				$('#modalLogin').modal("hide");
			});
			$('#modalLogin').on('shown.bs.modal', function () {
				$('#modalRegis').modal("hide");
			});
			$(".pass").click(function(){
					$("#modalForgotPass").modal("show");
			});
			$('#modalForgotPass').on('shown.bs.modal', function () {
				$('#modalLogin').modal("hide");
			});
			$(".backFormLogin").click(function(){
					$("#modalLogin").modal("show");
			});
			$("#inputEmail").keyup(function(){
					let _token = $('meta[name="csrf-token"]').attr('content');
					let $url_path = '{!! url('/') !!}';
					let routeCheckForgot=$url_path+"/checkForgot";
					let input = $("#inputEmail").val();
					$.ajax({
								url:routeCheckForgot,
								method:"POST",
								data:{_token:_token,input:input},
								success:function(data){ 
									if(input == "")
									{
											$("#icon_correct").css("display","none");
											$("#icon_incorrect").css("display","none");
											$("#btn_senKey").css("display","none");
									}
									else
									{
											if(data=="true")
											{
													$("#icon_correct").css("display","block");
													$("#btn_senKey").css("display","block");
													$("#icon_incorrect").css("display","none");
											}
											else if(data="false")
											{
													$("#icon_correct").css("display","none");
													$("#btn_senKey").css("display","none");
													$("#icon_incorrect").css("display","block");
											}
									}
							 }
					});
			});
			$("#btn_senKey").click(function(){
					let _token = $('meta[name="csrf-token"]').attr('content');
					let $url_path = '{!! url('/') !!}';
					let routeSendKey=$url_path+"/senkey";
					let input = $("#inputEmail").val();
					$.ajax({
								url:routeSendKey,
								method:"POST",
								data:{_token:_token,input:input},
								success:function(data){ 
									if(data=="true")
									{
											$("#inputEmail").attr("readonly","");
											$("#formCheckKey").css("display","flex");
									}
									if(data == "false")
									{
											alert("Cannot send email");
									}
							}
					});
			});
			$("#inputKey").keyup(function(){
					let _token = $('meta[name="csrf-token"]').attr('content');
					let $url_path = '{!! url('/') !!}';
					let routeCheckKey=$url_path+"/checkkey";
					let email = $("#inputEmail").val();
					let input = $("#inputKey").val();
					$.ajax({
								url:routeCheckKey,
								method:"POST",
								data:{_token:_token,input:input,email:email},
								success:function(data){
									if(input == "")
									{
											$("#key_incorrect").css("display","none");
									}
									else
									{
											if(data=="true")
											{
													$("#changePass").modal("show");
													$("#modalForgotPass").modal("hide");
											}
											if(data == "false")
											{
													$("#key_incorrect").css("display","block");
											}
									}
							 }
					});
			});
			//hết trang dashboard
		})
	</script>
@endif
@if(isset($justview) && Auth::check())
<!-- Modal -->
<div class="modal fade" id="modalRating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Rating</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fuild">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-12">
								<p class="font-weight-bold font-italic">Rating for your tour</p>
							</div>
							<div class="col-md-12 col-sm-12 col-12 mb-3" id="div_Starrank_tour">
								<i class="fas fa-star star_1 fa-2x"  data-value="1" style="cursor: pointer;"></i>
								<i class="fas fa-star star_2 fa-2x" data-value="2" style="cursor: pointer;"></i>
								<i class="fas fa-star star_3 fa-2x" data-value="3" style="cursor: pointer;"></i>
								<i class="fas fa-star star_4 fa-2x"  data-value="4" style="cursor: pointer;"></i>
								<i class="fas fa-star star_5 fa-2x" data-value="5" style="cursor: pointer;"></i>
								<i class="fas fa-star star_6 fa-2x" data-value="6" style="cursor: pointer;"></i> 
								<i class="fas fa-star star_7 fa-2x" data-value="7" style="cursor: pointer;"></i>
								<i class="fas fa-star star_8 fa-2x" data-value="8" style="cursor: pointer;"></i>
								<i class="fas fa-star star_9 fa-2x" data-value="9" style="cursor: pointer;"></i>
								<i class="fas fa-star star_10 fa-2x" data-value="10" style="cursor: pointer;"></i>
							</div>
							<input type="hidden" id="star_Share" name="numberStar">
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btn_Rating">Rating</button>
			</div>
		</div>
	</div>
</div>
@endif
@if(isset($justview))
	<style>
		#saveTour{display: none !important;}
		#btn-rating{display: block;}
	</style>
	<script type="text/javascript">
		@if(!Auth::check())
			$("#btn-rating").click(function(){
				$("#modalLogin").modal("show");
			});
		@else
			$("#btn-rating").click(function(){
				$("#modalRating").modal("show");
			});
			// votess star
			@for($i = 1; $i<= 10; $i++)
				$("#div_Starrank_tour .star_{{$i}}").click(function(){
						@for($j = 1 ; $j <= 10; $j++)
								$("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
						@endfor
						@for($j = 1 ; $j <= $i; $j++)
								$("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
						@endfor
						//console.log($(this).attr("data-value"));
						$("#star_Share").val($(this).attr("data-value"));
				});
			@endfor
			$("#btn_Rating").click(function(){
					let _token = $('meta[name="csrf-token"]').attr('content');
					let $url_path = '{!! url('/') !!}';
					let routeRating=$url_path+"/rating";
					let numberStar = $("#star_Share").val();
					$.ajax({
								url:routeRating,
								method:"POST",
								data:{_token:_token,numberStar:numberStar,shareId:{{$shareId}}},
								success:function(data){ 
									alert("You have successfully evaluated");
									location.reload();
							 }
					});
			});
		@endif
	</script>
@endif
	</body>
</html>


