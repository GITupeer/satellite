<!DOCTYPE html>
<html>
  <head>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script> 
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Satellite Radar</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
	
	
  </head>
  <body> 
  
 <div style="
    background: linear-gradient(141deg, #02183a 0%, #042559 51%, #07347a 75%);
    color: white;
    text-align: left;
    padding: 5px;
    padding-left: 10px;
    position: fixed;
    z-index: 1000;
    width: 100%;
    font-family: 'Roboto', sans-serif;
">
<div id="app">
			<table border="0">
				<tbody>
					<tr>
						<td>
							<img src="https://image.ibb.co/jq7P4o/Maps_Satellites_icon.png" style="width: 41px; height: 41px;">
						</td>
						<td>
							<div style="">Satellite Radar</div>
							<div style="font-size:  10px;">Check position satellite in real time</div>
						</td>
						<td style="padding-left: 25px;">
							<input type="text" name="search" placeholder="Search setallite..." style="border-radius: 4px;padding: 7px;font-size: 10px;width: 200px;">
						</td>
					</tr>
				</tbody>
			</table>
				
				
			<div style="position: fixed; z-index: 1; right: 0; margin-top: -45px; color: #eaebed; margin-right: 17px; font-size: 21px;">
				<div style="text-align: right;">
					<span style="font-size: 9px;">UTC
					</span>
					<?php
					echo date("H:i", strtotime ("+307 minute"));
					?>
				</div>
				<div style="font-size: 12px; text-align: right;">
					Satellite: <span style="font-weight: bold;">{{satelliteConuter}}</span>
				</div>
			</div>	
				

				
				   
				 


			</div>
			<div style="
				background-color: #F5F5F5;
				border-right: 1px solid;
				border-color: #9E9E9E;
				height: 100%;
				width: 250px;
				position: fixed;
				z-index: 2;
				/* right: 0; */
				color: white;
				padding-top:  30px;
				font-size: 11px;
				/* background: linear-gradient(141deg, #02183a 0%, #042559 51%, #07347a 75%); */
				margin-top: 48px;
				box-shadow: 0 14px 8px 0 rgba(0,0,0,0.2), 0 16px 50px 0 rgba(0,0,0,0.19);
			">
				<div style="    width: 241px;    margin-top: -21px;    background-image: url(https://www.thetimes.co.uk/imageserver/image/methode%2Ftimes%2Fprod%2Fweb%2Fbin%2Ffe7fba9c-0298-11e8-a2b0-4e5c7848ab02.jpg?crop=5905%2C3321%2C148%2C576&amp;resize=685);height: 132px;    background-size: contain;    padding-top:  10px;    padding-left:  10px;    font-family: 'Roboto', sans-serif;font-size: 9px;">
					@thetimes.co.uk 
				</div>
				<div style="background-color: #222121;margin: -2px;padding: 15px;font-family: 'Roboto', sans-serif;color: #FFD740;font-size: 15px;">
					Space Station
					<div style="font-size: 10px; margin-top: 2px; color: white;"> ISS SATELLITES </div>
				</div>

			<div style="margin-top: 8px; width: 100%; text-align: center;">

			<table border="0" style="width: 97%; margin: 4px; color: #333;">
				<tr>
					<td  colspan="2" style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 12px; padding-top: 2px; padding-bottom: 2px; font-weight: bold; text-align: center;">
						<center><table border="0"><tr><td><i class="material-icons"> info </i></td><td>Global Informations</td></tr></table></center>
					</td>
				</tr>
				<tr>
					<td style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 10px; font-weight: bold; text-align: center;">
						LUNCH DATE
					</td>
					<td style="width: 49%; background-color: #E0E0E0; padding: 5px; font-family: 'Roboto', sans-serif; font-size: 10px; font-weight: bold; text-align: center;">
						DAY ON ORBIT
					</td>
				</tr>
					<tr>
					<td style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 10px;  text-align: center;">
						20 July 2017
					</td>
					<td style="width: 49%; background-color: #E0E0E0; padding: 5px; font-family: 'Roboto', sans-serif; font-size: 10px; text-align: center;">
						375 days
					</td>
				</tr>
			</table>



			<table border="0" style="width: 97%; margin: 4px; color: #333; margin-top: 10px;">
				<tr>
					<td  colspan="2" style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 12px; padding-top: 2px; padding-bottom: 2px; font-weight: bold; text-align: center;">
						<center><table border="0"><tr><td><i class="material-icons"> gps_fixed </i></td><td>Satellite position</td></tr></table></center>
					</td>
				</tr>
				<tr>
					<td style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 11px; font-weight: bold; text-align: center;">
						Longitude
					</td>
					<td style="width: 49%; background-color: #E0E0E0; padding: 5px; font-family: 'Roboto', sans-serif; font-size: 11px; text-align: center;">
						40,2123
					</td>
				</tr>
				<tr>
					<td style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 11px; font-weight: bold; text-align: center;">
						Latitude
					</td>
					<td style="width: 49%; background-color: #E0E0E0; padding: 5px; font-family: 'Roboto', sans-serif; font-size: 11px; text-align: center;">
						-23,2342
					</td>
				</tr>
					<tr>
					<td style="width: 49%; background-color: #E0E0E0; font-family: 'Roboto', sans-serif; font-size: 11px; font-weight: bold; text-align: center;">
						Altitude
					</td>
					<td style="width: 49%; background-color: #E0E0E0; padding: 5px; font-family: 'Roboto', sans-serif; font-size: 11px; text-align: center;">
						26542,02 km
					</td>
				</tr>
			</table>

			 </div>

			</div>
			  
				<div id="map"></div>
				
				
				
				<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrwpP81uNX_HBYe7ncQqRgRsTAOQ5w4LU&callback=initMap"></script>
				<script>
				
				
			var app = new Vue({
			  el: '#app',
			  data: {
				satelliteConuter: 0
			  }, 
			  methods: function(){
				  
				  
			  },
			  mounted: function(){
					var marker;
					var map;
					var actualMarker;
						function initMap() {
							map = new google.maps.Map(document.getElementById('map'), {
							zoom: 2,
							center: {lat: -33.9, lng: 151.2},
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

							
						
						
						setMarkers(map);
					}
											 
				
				function setMarkers(map) {
					$.get('http://lim.bz/upeer/satellite/api.php').done(function(data){
					var json = JSON.parse(data);
					var dataGeo = json.data;
					console.log();
					var array = JSON.parse("[" + dataGeo + "]");
					var beaches = array;


					var image = {
					url: 'https://image.ibb.co/i9c5uo/sat24x24.png',
					size: new google.maps.Size(24, 24),
					origin: new google.maps.Point(0, 0),
					anchor: new google.maps.Point(0, 32)
					};
					var shape = {
						coords: [1, 1, 1, 20, 18, 20, 18, 1],
						type: 'poly'
					};
					for (var i = 0; i < beaches.length; i++) {
						var beach = beaches[i];
						marker = new google.maps.Marker({
							position: {lat: beach[1], lng: beach[2]},
							animation: google.maps.Animation.DROP,
							map: map,
							icon: image,
							shape: shape,
							title: beach[0],
							zIndex: beach[3]
						});
						actualMarker = beach[0];
						
						/*
						google.maps.event.addListener(marker, "click", function (event) {
							var flightPlanCoordinates = [
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
							
							
							
						});*/
						
					}	
				
					});	

				}			
				
				
			  }
			})
							
				
				





				
				
				</script>
</div>
  </body>
</html>