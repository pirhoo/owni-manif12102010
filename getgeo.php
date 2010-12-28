<!DOCTYPE html> 
<html> 
<head> 
<title>get location tools</title> 
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/standard.css" rel="stylesheet" type="text/css" /> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript">

  var map;
  var markersArray = [];
	var geocoder;

$(document).ready(function () {

	$('#location').focus();
	$('#searchform').submit(
   		function() {
			$('#info').html('');
      		loc = $('#location').val();
			//alert('jdhsqds');
			jumpTo(loc);
   		}
	)
})
 
  function initialize() {
    var france = new google.maps.LatLng(47, 2,1);
	geocoder = new google.maps.Geocoder();
    var mapOptions = {
      zoom: 6,
      center: france,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"),
        mapOptions);
 
    google.maps.event.addListener(map, 'click', function(event) {
	if (markersArray) {	
		for (i in markersArray) {
		        markersArray[i].setMap(null);
		      }
	}
    addMarker(event.latLng);
	ln = event.latLng.lng();
	la = event.latLng.lat();
	text = '<br>&nbsp;&nbsp;&nbsp;&nbsp;longitude : ' + ln + " latitude : " + la;
	$('#info').html(text);

    });
  }
  
	
	function jumpTo(location) {
       geocoder.geocode({ address: location }, function(results, status) {
         map.fitBounds(results[0].geometry.viewport);
       });
     }
  
  function addMarker(location) {
    marker = new google.maps.Marker({
      position: location,
      map: map
    });
    markersArray.push(marker);
  }
</script> 
</head> 
<body onload="initialize();"> 
 	<div id="map_canvas" style="width:700px; height:600px"></div> 
<form id='searchform' action="javascript:jumpTo()">
<input name='location' id='location' type="textbox"/><input type="submit" id='search' value='search'>
</form>
<div style="font-size:20px" id='info'><div>
</body> 
</html>