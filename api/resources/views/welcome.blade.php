<!DOCTYPE html>
<html>
  <head>
	<link href="/css/style.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script> 
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Satellite Radar</title>
  </head>
  <body> 
  
  <style>
        
       
         /*style the box*/  
         .gm-style .gm-style-iw {
            display: block !important;
         }    
     
         /*style the p tag*/
         .gm-style .gm-style-iw #google-popup p{
         }
         
     
        /*style the arrow*/
        .gm-style div div div div div div div div {
            color: #212121;
            font-size: 11px;
			font-weight: bold;
		
        }
        
        /*style the link*/
        .gm-style div div div div div div div div a {
			opacity: 0.7;
			width: 20px;
			height: 20px;
        }
		.gm-style div div div div div div img {
			display: none;
			width: 0px;
   	 		height: 0px;
        }
              
      </style>
<div id="app">

	<div class="topMenu" id="topMenu">
		<table border="0">
			<tbody>
				<tr>
					<td>
						<img src="https://image.ibb.co/jq7P4o/Maps_Satellites_icon.png" style="width: 41px; height: 41px;">
					</td>
					<td>
						<div style="">Satellite Radar | Sky-Space.cloud</div>
						<div style="font-size:  10px;">Check position satellite in Real-Time</div>
					</td>
					<td style="padding-left: 25px;">
						<input type="text" name="search" placeholder="Search setallite..." style="border-radius: 4px;padding: 7px;font-size: 10px;width: 200px;">
					</td>
				</tr>
			</tbody>
		</table>



		<div class="menu_header_links">
		<div class="menu_button">
				<table border="0">
					<tr>
						<td>
							<i class="material-icons" style="font-size: 15px;">fiber_new</i> 	
						</td>
						<td>
							New Satellites
						</td>
					</tr>
				</table>
			</div>

			<div class="menu_button">
				<table border="0">
					<tr>
						<td>
							<i class="material-icons" style="font-size: 15px;">list_alt</i> 	
						</td>
						<td>
							Categories
						</td>
					</tr>
				</table>
			</div>

			<div class="menu_button">
				<table border="0">
					<tr>
						<td>
							<i class="material-icons" style="font-size: 15px;">person_add</i> 	
						</td>
						<td>
							Sign Up
						</td>
					</tr>
				</table>
			</div>
			<div class="menu_button">
				<table border="0">
					<tr>
						<td>
							<i class="material-icons" style="font-size: 15px;">lock</i> 	
						</td>
						<td>
							Login
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<!--<div style="position: fixed; z-index: 1; right: 0; margin-top: -48px; color: #eaebed; margin-right: 17px; font-size: 21px;">
			<div style="display: inline-block;">
				<div style="text-align: right;">
					<span style="font-size: 9px;">UTC
					</span>
					<?php
					echo date("H:i", strtotime ("+307 minute"));
					?>
				</div>
				<div style="font-size: 12px; text-align: right;">
					Satellite: <span style="font-weight: bold;">@{{satelliteConuter}}</span>
				</div>
			</div>

		</div>	-->
	</div>		




		<div class="leftMenu_static" id="leftMenu2">
			<table border="0" style="margin-left: -10px; width: 106%;">
				<tr>
					<td  colspan="2" class="table_main_header_static">
					<div v-if="loader == false" class="loader"> 
				<div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
				<div>Checking your position...</div>
			</div>

					</td>
				</tr>
			</table>






		</div>

				
				   
				 
	<div class="leftMenu" id="leftMenu">

		<div class="close_button" @click="close()">
			<i class="material-icons" style="font-size: 14px;color: #212121;"> close </i>
		</div>


		<div style="    transition: box-shadow 1s, -webkit-box-shadow 1s; width: 241px;    margin-top: -21px;    background-image: url(https://www.thetimes.co.uk/imageserver/image/methode%2Ftimes%2Fprod%2Fweb%2Fbin%2Ffe7fba9c-0298-11e8-a2b0-4e5c7848ab02.jpg?crop=5905%2C3321%2C148%2C576&amp;resize=685);height: 132px;    background-size: contain;    padding-top:  10px;    padding-left:  10px;    font-family: 'Roboto', sans-serif;font-size: 9px;">
			@thetimes.co.uk 
		</div>
		<div class="info_SatelliteName">
			@{{satelliteInformations.name}}
			<div class="info_SatelliteCategory"> 
				<span v-if="satelliteInformations.category">@{{satelliteInformations.category}}</span><span v-else> n/o </span>
			</div>
		</div>
		<div style="margin-top: 8px; width: 100%; text-align: center;">




			<table border="0" class="table_main_info">
				<tr>
					<td  colspan="2" class="table_main_header">
						<table border="0"><tr><td><i class="material-icons" style="font-size: 21px;"> info </i></td><td>Global Informations</td></tr></table>
					</td>
				</tr>

				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
						Launch Date
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.launch_date">@{{satelliteInformations.launch_date}}</span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
							Day on orbit
						</div>
						<div class="info_Value">
						<span v-if="satelliteInformations.launch_date_day">@{{satelliteInformations.launch_date_day}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>


			</table>




			<table border="0" class="table_main_info">
				<tr>
					<td  colspan="2" class="table_main_header">
						<table border="0"><tr><td><i class="material-icons" style="font-size: 21px;"> gps_fixed </i></td><td>Satellite Position</td></tr></table>
					</td>
				</tr>
				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
							Latitude
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.latitude">@{{satelliteInformations.latitude}}</span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
						Longitude
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.longitude">@{{satelliteInformations.longitude}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
						Altitude
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.altitude">@{{satelliteInformations.altitude}}</span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
						Satellite Speed
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.speed">@{{satelliteInformations.speed}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>
			</table>



			<table border="0" class="table_main_info">
				<tr>
					<td  colspan="2" class="table_main_header">
						<table border="0"><tr><td><i class="material-icons" style="font-size: 21px;"> not_listed_location </i></td><td>Satellite Informations</td></tr></table>
					</td>
				</tr>


				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
							Satellite area
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.RCS">@{{satelliteInformations.RCS}} <span v-if="satelliteInformations.RCS != 'Unknown'">m<sup>2</sup></span></span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
							Inclination
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.Inclination">@{{satelliteInformations.Inclination}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
							Apogee
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.Apogee">@{{satelliteInformations.Apogee}}</span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
						Perigee
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.Perigee">@{{satelliteInformations.Perigee}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
							Semi major axis
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.Semi_major_axis">@{{satelliteInformations.Semi_major_axis}}</span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
						Azimuth
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.azimuth">@{{satelliteInformations.azimuth}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>

				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
						Elevation
						</div>
						<div class="info_Value">
							<span v-if="satelliteInformations.elevation">@{{satelliteInformations.elevation}}</span><span v-else> n/o </span>
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
							NORAD ID
						</div>
						<div class="info_Value">
						<span v-if="satelliteInformations.satellite_id">@{{satelliteInformations.satellite_id}}</span><span v-else> n/o </span>
						</div>
					</td>
				</tr>
				<tr>
					<td class="table_main_colName">
						<div class="info_Name">
						Int'l Code
						</div>
						<div class="info_Value">
							n/o (add)
						</div>
					</td>
					<td class="table_main_colName">
						<div class="info_Name">
							
						</div>
						<div class="info_Value">

						</div>
					</td>
				</tr>

			</table>	


			<div style="border-color: transparent;
    background-color: #0ca6ff;
    background-image: -webkit-gradient(linear,left top,left bottom,from(#24afff),to(#0b95e6));
    background-image: linear-gradient(180deg,#24afff,#0b95e6);
    color: #fff;
    background-image: none;
    -webkit-box-shadow: none;
    box-shadow: none;
    cursor: progress;"></div>		


		</div>
	</div>
</div>
<div id="map" style="width: 100%; height: 100%;"></div>			  
				
				
				
				
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&key=AIzaSyDrwpP81uNX_HBYe7ncQqRgRsTAOQ5w4LU"></script>
	<script>
		var app = new Vue({
			el: '#app',
			data: {
				satelliteConuter: 0,
				marker: [],
				map: [],
				actualMarker: [],
				map: '',
				satelliteInformations: {
					launch_date: '',
					Apogee: '',
					Inclination: '',
					Perigee: '',
					RCS: '',
					Semi_major_axis: '',
					altitude: '',
					azimuth: '',
					category: 'ISS Station',
					elevation: '',
					latitude: '',
					launch_date_day: '',
					longitude: '',
					name: '',
					satellite_id: '',
					speed: '',
					launch_date_day: ''
				},
				loader: false,
				user_position: ''
			}, 
			methods: {
				close: function() {
					document.getElementById("leftMenu").style.transform = "translateX(-105%)";
				}
				
			},
			computed: {
				

			},
			mounted: function(){
				var scope = this;


				$.get('http://sky-space.cloud/userposition').done(function(data){
					scope.user_position = data;
				
					console.log(scope.user_position);
					map = new google.maps.Map(document.getElementById('map'), {
						zoom: 10,
						mapTypeControl: false,
						disableDefaultUI: true,
						center: {lat: scope.user_position.latitude, lng: scope.user_position.longitude},
						styles: [
						{
							"featureType": "landscape",
							"stylers": [
								{
									"hue": "#FFBB00"
								},
								{
									"saturation": 43.400000000000006
								},
								{
									"lightness": 37.599999999999994
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "road.highway",
							"stylers": [
								{
									"hue": "#FFC200"
								},
								{
									"saturation": -61.8
								},
								{
									"lightness": 45.599999999999994
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "road.arterial",
							"stylers": [
								{
									"hue": "#FF0300"
								},
								{
									"saturation": -100
								},
								{
									"lightness": 51.19999999999999
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "road.local",
							"stylers": [
								{
									"hue": "#FF0300"
								},
								{
									"saturation": -100
								},
								{
									"lightness": 52
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "water",
							"stylers": [
								{
									"hue": "#0078FF"
								},
								{
									"saturation": -13.200000000000003
								},
								{
									"lightness": 2.4000000000000057
								},
								{
									"gamma": 1
								}
							]
						},
						{
							"featureType": "poi",
							"stylers": [
								{
									"hue": "#00FF6A"
								},
								{
									"saturation": -1.0989010989011234
								},
								{
									"lightness": 11.200000000000017
								},
								{
									"gamma": 1
								}
							]
						}
					]  
						});

					$.get('http://sky-space.cloud/get_position').done(function(data){
					var json = data;
					
					scope.satelliteConuter = json.counter;
					var dataGeo = json.data;
					console.log();
					var array = JSON.parse("[" + dataGeo + "]");
					var beaches = array;
					var image = {
					url: 'http://icons.iconarchive.com/icons/icons8/windows-8/16/Maps-Satellite-Sending-Signal-icon.png',
					size: new google.maps.Size(24, 24),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(0, 32)
					};

					var image_ISS = {
					url: 'http://icons.iconarchive.com/icons/goodstuff-no-nonsense/free-space/24/international-space-station-icon.png',
					size: new google.maps.Size(24, 24),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(0, 32)
					};

					var shape = {
						coords: [1, 1, 1, 20, 18, 20, 18, 1],
						type: 'poly'
					};
					var infowindow = new google.maps.InfoWindow();
					var Markers = {};
					var imageChoose;
					for (var i = 0; i < beaches.length; i++) {
						var beach = beaches[i];
						if (beach[0] == 'SPACE STATION |*| 25544') {
							imageChoose = image_ISS;
						} else {
							imageChoose = image;
						}
						marker = new google.maps.Marker({
							position: {lat: beach[1], lng: beach[2]},
							map : map,
							icon: imageChoose,
							shape: shape,
							title: beach[0],
							zIndex: beach[3]
						});
						actualMarker = beach[0];

						google.maps.event.addListener(marker, 'mouseover', (function(marker, i, event) {
							return function() {
								var str = this.title;
								var res = str.split(" |*| ");
								infowindow.setContent(res[0]);
								infowindow.setOptions({maxWidth: 200});
								infowindow.open(map, marker);
							}
						}) (marker, i));
						google.maps.event.addListener(marker, 'mouseout', (function(marker, i, event) {
							return function() {
								infowindow.close();
							}
						}) (marker, i));
						Markers[i] = marker;
							
						

						google.maps.event.addListener(marker, "click", function (event) {
							var styleVal = document.getElementById("leftMenu").style.transform
							if (styleVal == 'translateX(-105%)' || styleVal == ''){
								document.getElementById("leftMenu").style.transform = "translateX(0)";	
							} else {
								document.getElementById("leftMenu").style.transform = "translateX(-105%)";
								setTimeout(function(){ 
									document.getElementById("leftMenu").style.transform = "translateX(0)";
								}, 700);
									
							}

						
							var str = this.title;
							var res = str.split(" |*| ");
							$.get('http://46.101.110.28/satellite/'+res[1]).done(function(data){ 
								scope.satelliteInformations = data.data;
							});
					
														
							
							/*var flightPlanCoordinates = [
							{lat: 37.772, lng: -122.214},
							{lat: 21.291, lng: -157.821},
							{lat: -18.142, lng: 178.431},
							{lat: -27.467, lng: 153.027}
							];
							var flightPath = new google.maps.Polyline({
							path: flightPlanCoordinates,
							geodesic: true,
							strokeColor: '#FF0000',
							strokeOpacity: 1.0,
							strokeWeight: 2
							});
							
							
							flightPath.setMap(map); 
							
							console.log('DSFDSFDS', this.title);
							*/
							
							
						});
						
					}	
					function locate(marker_id) {
						var myMarker = Markers[marker_id];
						var markerPosition = myMarker.getPosition();
						map.setCenter(markerPosition);
						google.maps.event.trigger(myMarker, 'click');
					}

					});
				});
			}
		})
							
				
				





				
				
				</script>
</div>
  </body>
</html>