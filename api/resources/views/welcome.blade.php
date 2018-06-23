<!DOCTYPE html>
<html>
  <head>
	<link href="/css/style.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script> 
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Satellite Radar</title>
  </head>
  <body> 
  

<div id="app">

	<div class="topMenu" id="topMenu">
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
				Satellite: <span style="font-weight: bold;">@{{satelliteConuter}}</span>
			</div>
		</div>	
	</div>		

				
				   
				 
	<div class="leftMenu" id="leftMenu">
		<div style="    width: 241px;    margin-top: -21px;    background-image: url(https://www.thetimes.co.uk/imageserver/image/methode%2Ftimes%2Fprod%2Fweb%2Fbin%2Ffe7fba9c-0298-11e8-a2b0-4e5c7848ab02.jpg?crop=5905%2C3321%2C148%2C576&amp;resize=685);height: 132px;    background-size: contain;    padding-top:  10px;    padding-left:  10px;    font-family: 'Roboto', sans-serif;font-size: 9px;">
			@thetimes.co.uk 
		</div>
		<div style="background-color: #222121;margin: -1px;padding: 15px; padding-left: 20px;font-family: 'Roboto', sans-serif;color: #FFD740;font-size: 15px;">
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
			map: ''
			}, 
			methods: {
			
				
			},
			computed: {
				

			},
			mounted: function(){


				
			function getSpeed(x1, dx, dy, h)
			{
					// calculate speed, as it is not computed correctly on the server
					var dlat=dx*Math.PI/180;
					var dlon=dy*Math.PI/180;
					var lat1=x1*Math.PI/180;
					var lat2=(x1+dx)*Math.PI/180;
					var a = Math.sin(dlat/2) * Math.sin(dlat/2) + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dlon/2) * Math.sin(dlon/2); 
					var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
					var speed = (h + 6378.135) * c;
					speed = Math.sqrt(398600.8 / (h + 6378.135));
					return speed;
			}









				var scope = this;
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

							

					$.get('http://46.101.110.28/get_position').done(function(data){
					var json = data;
					
					scope.satelliteConuter = json.counter;


			console.log('dsfds',scope.satelliteConuter);




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
							map : map,
							icon: image,
							shape: shape,
							title: beach[0],
							zIndex: beach[3]
						});
						actualMarker = beach[0];
						
						
							google.maps.event.addListener(marker, "click", function (event) {
								var styleVal = document.getElementById("leftMenu").style.transform
								console.log(styleVal);
								if (styleVal == 'translateX(-105%)' || styleVal == ''){
									document.getElementById("leftMenu").style.transform = "translateX(0)";	
								} else {
									document.getElementById("leftMenu").style.transform = "translateX(-105%)";
								}

							


							$.post("http://46.101.110.28/satellite",
								{
									title: this.title
								},
								function(data, status){
									alert("Data: " + data + "\nStatus: " + status);
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

					});
				
			}
		})
							
				
				





				
				
				</script>
</div>
  </body>
</html>