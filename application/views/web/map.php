<html>
    <head>
        <script>
        function drawPolylines(map){

	var app_id = "<?php echo $app_id;?>";
        var imei_no = "<?php echo $imei_no;?>"
        var date_time = "<?php echo $date_time;?>"
        var base_url = "<?= base_url() ?>"
        var ajax_url = base_url+'tracking/gettrackingrec/'+app_id+'?imei_no='+imei_no+'&date_time='+date_time;
	$.ajax({
		//url: "http://dev.dataplug.itu.edu.pk/tracking/gettrackingrec/3980?imei_no=&date_time=2016-12-14", 
		url: ajax_url, 
	 
		type: "get",
		dataType: "json"
	}).done(function(response){
		
		localStorage.setItem('distance',0);	
		arr = response.records;
		
		routes={};
		pathCoordinates = [];

		 // Define the LatLng coordinates for the polygon's path.
 
		 // uncomment to show geo fence
        // var triangleCoords = [
        //   {lng: 74.33375, lat: 31.50282},
        //   {lng: 72.32271, lat: 31.49976},
        //   {lng: 74.3286, lat: 31.48541},
        //   {lng: 74.3474, lat: 30.48577},
        //   {lng: 74.33764, lat: 34.5049}
        // ];

        // console.log(JSON.stringify(triangleCoords));
        // // Construct the polygon.
        // var bermudaTriangle = new google.maps.Polygon({
        //   paths: triangleCoords,
        //   strokeColor: '#FF0000',
        //   strokeOpacity: 0.8,
        //   strokeWeight: 2,
        //   fillColor: '#ffcc33',
        //   fillOpacity: 0.35
        // });
        // bermudaTriangle.setMap(map);


		for (i=0; i < arr.length; i=i+1){
 			
 			r=arr[i];
 			routeId=r.routeId;
 			if(routeId==null || routeId==undefined)
 			{
 				routeId="abc";
 			}
 			if(routes[routeId]==null || routes[routeId]==undefined)
 			{
 				routes[routeId]={};
 				routes[routeId].pathCoordinates=[];
 			}
 			// console.log(arr[i].InGeoFence);
 			// if(arr[i].InGeoFence=="YES")
 			// {


				routes[routeId].pathCoordinates.push({
					lat: parseFloat(arr[i].lat),
					lng: parseFloat(arr[i].lng),
					time:arr[i].gpsTime,
					speed:arr[i].speed,
					distance:arr[i].distance,
					distanceGeo:arr[i].distanceGeo
				});
			// }
		}

		console.log(routes);

		for(r in routes)
		{

			pathCoordinates=routes[r].pathCoordinates
			var path = new google.maps.Polyline({
				path: pathCoordinates,
				geodesic: true,
				strokeColor: '#'+(Math.random()*0xFFFFFF<<0).toString(16),
				strokeOpacity: 0.75,
				strokeWeight: 5
			});

			var marker1 = new google.maps.Marker({
			   position: pathCoordinates[0],
			   map: map,
			   title: "Start",
			   label:"S",
			   animation: google.maps.Animation.DROP,
			  // icon: "http://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Marker-Inside-Chartreuse-icon.png"
			   
			});

			var marker2 = new google.maps.Marker({
			   position: pathCoordinates[pathCoordinates.length - 1],
			   map: map,
			   title: "End",
			   label:"E",
			   animation: google.maps.Animation.DROP,
			   // icon: "http://icons.iconarchive.com/icons/icons-land/vista-map-markers/32/Map-Marker-Marker-Outside-Pink-icon.png"
			  // icon: "http://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Marker-Inside-Pink-icon.png"
			});

			lastPoint=pathCoordinates[pathCoordinates.length - 1];


			for(i in pathCoordinates)
			{
				point=pathCoordinates[i];
						var marker2 = new google.maps.Marker({
					   position:point,
					   // icon:"http://icons.iconarchive.com/icons/fatcow/farm-fresh/32/bullet-green-icon.png",
					   map: map,
					   title: "Point"+point.lat+" , "+point.lng+" | Time: "+point.time+" | Speed: "+point.speed+" | Distance: "+point.distance+" | GEO: "+point.distanceGeo
					});
			}

			var lengthInMeters = google.maps.geometry.spherical.computeLength(path.getPath());
			if(localStorage.getItem('distance')==null)
			{
				
				d=localStorage.getItem('distance');
				d=parseFloat(d);
				d=d+lengthInMeters;
				localStorage.setItem('distance',d);
			}
			else{
				d=localStorage.getItem('distance');
				d=parseFloat(d);
				d=d+lengthInMeters;
				localStorage.setItem('distance',d);
			}
			
			
			console.log("distance=" +lengthInMeters);
			$('.distanceBox').html("<h3>Distance Tracked By Google Map</h3>"+(parseFloat(localStorage.getItem('distance'))/1000).toFixed(2)+"KM"+
				"<h3>Distance Tracked In Device</h3>"+lastPoint.distance+"KM"+""
				// "<h3>Distance Tracked IN GeoFence</h3>"+lastPoint.distanceGeo+"KM"
				);
			path.setMap(map);

		}

		


		
	});
}



function initMap() {
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 14,
		center: {lat: 31.49, lng: 74.30},
		mapTypeId: 'terrain'
	});

	drawPolylines(map);
}
        </script>
    </head>
<body>
	<style>
		#map {
			height: 100%;
		}
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		.distanceBox{
			position:fixed;
			top:0px;
			right:0px;
			height:250px;
			width:300px;
			background:#fff;
			z-index:1000000;
			/*border:3px solid #333;*/
			border-radius:5px;
			padding:10px;
			opacity:0.9;
			color:crimson;
		}
	</style>
	<div class="distanceBox"></div>
	<div id="map"></div>


	<script
	src="https://code.jquery.com/jquery-3.1.1.min.js"
	integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
	crossorigin="anonymous"></script>



<!--	<script type="text/javascript" src="<?= base_url() ?>assets/js/map/js.js" ></script>-->

	<script async defer
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDd2gNkeBamtSUO1yGOtAzbU-czuBdWOEs&callback=initMap&libraries=geometry">
</script>
</body>
</html>