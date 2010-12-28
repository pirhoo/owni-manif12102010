<?php
error_reporting (0);
ini_set('display_errors', 0);

if ($_GET['embed'] == '1') {
	$width_container = '594';
	$width_map = '390';
	$height_map = '600';
	$zoom = 5;
}
else {
	$width_container = '898';
	$width_map = '700';
	$height_map = '700';
	$zoom = 6;
}
if ($_GET['embed'] == 'rue89') {
	$width_container = '470';
	$width_map = '270';
	$height_map = '600';
	$zoom = 5;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
	<style type="text/css">
	strong.city {display: block;text-align:center;font-size: 22px;color:#000;margin-bottom:10px;}
	strong.person {display: block;text-align: center;font-size:14px; color:#f00;}
	.what {display: block;text-align: center;}
	 li { 
		list-style-type:none; 
	}
	</style>
</head>
<body>
<div id="container" style="width:<?php echo $width_container;?>px; font-family:Arial,
sans-serif; font-size:11px; border:1px solid black;position : relative">
 <table id="cm_mapTABLE"> 
	<tbody> 
	<tr id="cm_mapTR">
		<td> 
			<div id="cm_map" style="width:<?php echo $width_map;?>px; height:<?php echo $height_map;?>px"></div>
		</td>
		<td valign=top style="width:200;valign:top;font-size:13px;">
			<li> 
		        <label for="showmanif"><img src='images/mf.png'/>&nbsp;Manifestation</label> 
		        <input onchange="toggleMarkerManager('mf')" type="checkbox" checked id="showmanif" name="showmanif" /> 
		     </li>
			<li> 
		        <label for="showtest"><img src='images/tc.png'/>&nbsp;Transports en commun</label> 
		        <input onchange="toggleMarkerManager('tc')" type="checkbox" checked id="showtc" name="showtc" /> 
		     </li>
			<li> 
		        <label for="showtest"><img src='images/tf.png'/>&nbsp;Transports de fret</label> 
		        <input onchange="toggleMarkerManager('tf')" type="checkbox" checked id="showtf" name="showtf" /> 
		     </li>
			<li> 
		        <label for="showtest"><img src='images/el.png'/>&nbsp;Energie - Electricit&eacute;</label> 
		        <input onchange="toggleMarkerManager('el')" type="checkbox" checked id="showel" name="showel" /> 
		     </li>
			<li> 
		        <label for="showtest"><img src='images/rf.png'/>&nbsp;Energie - Raffineries</label> 
		        <input onchange="toggleMarkerManager('rf')" type="checkbox" checked id="showrf" name="showrf" /> 
		     </li>
			<li> 
		        <label for="showtest"><img src='images/ly.png'/>&nbsp;Lyc&eacute;e</label> 
		        <input onchange="toggleMarkerManager('ly')" type="checkbox" checked id="showly" name="showly" /> 
		     </li>
			<li> 
				<label for="showtest"><img src='images/uv.png'/>&nbsp;Universit&eacute;</label> 
				<input onchange="toggleMarkerManager('uv')" type="checkbox" checked id="showuv" name="showuv" /> 
			</li>
			<li> 
				<label for="showtest"><img src='images/pu.png'/>&nbsp;Secteur Public</label> 
				<input onchange="toggleMarkerManager('pu')" type="checkbox" checked id="showpu" name="showpu" /> 
			</li>
				<li> 
					<label for="showtest"><img src='images/pv.png'/>&nbsp;Secteur Priv&eacute;</label> 
					<input onchange="toggleMarkerManager('pv')" type="checkbox" checked id="showpv" name="showpv" /> 
			</li>
			<li> 
				<label for="showtest"><img src='images/gris.png'/>&nbsp;Ev&eacute;nement termin&eacute;</label> 
				<input onchange="toggleMarkerManager('eventfinish')" type="checkbox" checked id="showeventfinish" name="showeventfinish" /> 
			</li>
			<br/>
			<input type="button" name="uncheckAllAuto" id="uncheckAllAuto" value="Tout d&eacute;selectionner"/>
			<input type="button" name="checkAllAuto" id="checkAllAuto" value="Tout s&eacute;lectionner"/>
			<br/>
			
			<li>
				<form id='searchform' action="javascript:jumpTo()">
				<?php
					$vloc = '';
					if  (isset($_GET['s'])) {
						$vloc = htmlentities($_GET['s']);
					}
				?>
				<br/>&nbsp;Recherche (ville): <input name='location' value='<?php echo $vloc;?> 'id='location' type="textbox"/><input type="submit" id='search' value='search'>
				</form>
			</li><br/><br/>
			<a href="http://owni.fr/2010/10/11/application-carte-blocages-manifestations-greves-12oct/#formulaire" target='_blank'>Signalez nous d'autres points de blocage</a>
			<br/><br/>
			<a target='_blank' href="http://owni.fr"><img border="0" width="190px" src='images/owni_logo.gif'/></a>
			<a target='_blank' href="http://mediapart.fr"><img border="0" width="190px" src='images/mediapart.jpg'/></a>
		</td>
	</tr> 
	</tbody>
</table>
</div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=fr"></script>
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="jfeed/build/dist/jquery.jfeed.js"></script>

<script type="text/javascript">

var geocoder;
var map;
var result;

var markersArray = [];
markersArray['mf'] = [];
markersArray['tc'] = [];
markersArray['tf'] = [];
markersArray['el'] = [];
markersArray['rf'] = [];
markersArray['ly'] = [];
markersArray['uv'] = [];
markersArray['pu'] = [];
markersArray['pv'] = [];
markersArray['eventfinish'] = [];

var showMarker = [];
showMarker['mf'] =true;
showMarker['tc'] =true;
showMarker['tf'] =true;
showMarker['el'] =true;
showMarker['rf'] =true;
showMarker['ly'] =true;
showMarker['uv'] =true;
showMarker['pu'] =true;
showMarker['pv'] =true;
showMarker['eventfinish'] =true;

function jumpTo(location) {
   geocoder.geocode({ address: location }, function(results, status) {
	if (results[0]) {
    	window.map.fitBounds(results[0].geometry.viewport);
	}
   });
 }

$(document).ready(function () {
	
	$('#location').focus();
	$('#searchform').submit(
   		function() {
			loc = $('#location').val();
			//alert('jdhsqds');
			jumpTo(loc);
   		}
	)

	$('#uncheckAllAuto').click(
   		function()
   		{
      		$("INPUT[type='checkbox']").attr('checked', false);   
			for (typeofblocage in showMarker) {
				showMarker[typeofblocage] = false;
				for (i in markersArray[typeofblocage]) {
			        markersArray[typeofblocage][i].setMap(null);
			      }
			}

   		}
	)
	$('#checkAllAuto').click(
   		function()
   		{
      			$("INPUT[type='checkbox']").attr('checked', true);  
				for (typeofblocage in showMarker) {
					showMarker[typeofblocage] = true;
					for (i in markersArray[typeofblocage]) {
				        markersArray[typeofblocage][i].setMap(map);
				      }
				} 
   		}

	)
});


function toggleMarkerManager(typeofblocage) {
  showMarker[typeofblocage] = !showMarker[typeofblocage];
  if (showMarker[typeofblocage]) {
		if (markersArray[typeofblocage]) {
	      for (i in markersArray[typeofblocage]) {
	        markersArray[typeofblocage][i].setMap(map);
	      }
	    }
   	
    } else {
  		 if (markersArray[typeofblocage]) {
		    for (i in markersArray[typeofblocage]) {
		        markersArray[typeofblocage][i].setMap(null);
		   	}
		}
    }
 }

var param_wsId = "od6";
var param_ssKey = "0AlfVwndVSyr8dGVNa0x0enZ1YWdLNzcwOFpFelg1TUE";
var param_titleColumn = "type";
var param_descriptionColumn = "description";
var param_latColumn = "latitude";
var param_lngColumn = "longitude";
var param_startColumn = "start";
var param_visibleColumn = "visible";
var param_endColumn = "end";
var param_source = "source";

function cm_load() {  
	var latlng = new google.maps.LatLng(47,2.1); 

	var myOptions = {                                                    
		zoom: <?php echo $zoom;?>,                                                           
		center: latlng,                                                    
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControlOptions: {
		     style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
		navigationControl: true,
		streetViewControl: false
		
	         
	};    
	window.map = new google.maps.Map(document.getElementById("cm_map"), myOptions);
	geocoder = new google.maps.Geocoder();
	window.map.scrollwheel = false;
	window.infoWindow = new google.maps.InfoWindow;
	
	<?php 
		if  (isset($_GET['s'])) {
			$searchVille = htmlentities($_GET['s']);
			echo "jumpTo('" . $searchVille ."');"; 
		}
	?>
	
	jQuery.getFeed({
        url: 'lycee.xml',
        success: function(feed) {

           	for(var i = 0; i < feed.items.length; i++) {

                var item = feed.items[i];
 				myLatlng = item.georss.trim();
				title = item.title;
				coords = myLatlng.match(/(\-?[0-9\.]{1,})\s(\-?[0-9\.]{1,})/i);
			//	alert(coords[1]);
			//	alert(coords[2]);
				var myLatlng = new google.maps.LatLng(coords[1],coords[2]);
				var image = 'images/gris.png';
				
				var marker = new google.maps.Marker({
					position: myLatlng, 
					map: window.map, 
					icon : image,
					title: title
				});
				html = title + "</br><br/>source : <a href='http://www.unl-fr.org/index.php?option=com_content&view=article&id=350:carte-de-france-des-lycees-mobilises-etou-bloques-contre-la-reforme-des-retraites-du-gouvernement-jeudi-dernier-&catid=42:accueilpcpal' target='_blank'>UNL</a>";
				//  jQuery('#content').append(html);
				bindInfoWindow(marker, map, infoWindow, html);
				markersArray['ly'].push(marker);
            }

          // jQuery('#content').append(html);
        }    
    });
	cm_getJSON();                                                   
}

function bindInfoWindow(marker, map, infoWindow, html) {
  google.maps.event.addListener(marker, 'click', function() {
    infoWindow.setContent(html);
    infoWindow.open(map, marker);
  });
}

function cm_loadMapJSON(json) {
 for (var i = 0; i < json.feed.entry.length; i++) {
    var entry = json.feed.entry[i];
    if(entry["gsx$" + param_latColumn]) {
		var label = entry["gsx$"+param_titleColumn].$t;
	//	$('#content').append(entry["gsx$"+ param_descriptionColumn].$t); 
	
		var lat = parseFloat(entry["gsx$" + param_latColumn].$t);
		var lng = parseFloat(entry["gsx$" + param_lngColumn].$t);
		var myLatlng = new google.maps.LatLng(lat,lng);
		var html = "<div style='font-size:12px;padding:0;width: 300px; height:100px'>"; 
		var image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/circular/bluecirclemarker.png";
		
		var image = 'images/gris.png';
		html += '<img src="' + image + '"</>';
		if(entry["gsx$" + param_descriptionColumn].$t) {
			html += "&nbsp;&nbsp;" + entry["gsx$"+ param_descriptionColumn].$t;
		}
			if(entry["gsx$" + param_source].$t) {
				html += "<br/><br/><span style='font-size:13px;align:right'><a target='_blank' href=\"" + entry["gsx$"+ param_source].$t + "\">source</a></span>";
			}
		html += "</div>";
		dateauj = new Date().getTime();
		var startblocage = new Date(entry["gsx$" + param_startColumn].$t).getTime();
		var endblocage = new Date(entry["gsx$" + param_endColumn].$t).getTime();
		 if(entry["gsx$" + param_visibleColumn].$t == '1') {
			if (dateauj > startblocage && dateauj < endblocage) {
				var marker = new google.maps.Marker({
					position: myLatlng, 
					map: map, 
					icon : image,
					title: entry["gsx$"+ param_descriptionColumn].$t
				});
				bindInfoWindow(marker, map, infoWindow, html);
				markersArray[entry["gsx$"+param_titleColumn].$t].push(marker);
			}
			if (dateauj > endblocage) {
				var image = 'images/gris.png';
				var marker = new google.maps.Marker({
					position: myLatlng, 
					map: map, 
					icon : image,
					title: entry["gsx$"+ param_descriptionColumn].$t
				});
				bindInfoWindow(marker, map, infoWindow, html);
				markersArray[entry["gsx$"+param_titleColumn].$t].push(marker);
				markersArray['eventfinish'].push(marker);
			}
		}
	}
}
//alert(i);
/*
var infoNbTotal = document.createElement("div");
infoNbTotal.setAttribute("width","100%");
infoNbTotal.setAttribute("display","block");
infoNbTotal.setAttribute("z-index","999999");
infoNbTotal.setAttribute("position","absolute");
infoNbTotal.setAttribute("left","0");
infoNbTotal.setAttribute("bottom","0");
infoNbTotal.setAttribute("opacity",".7");
infoNbTotal.setAttribute("color","#FFFFFF");
infoNbTotal.setAttribute("text-align","center");
infoNbTotal.setAttribute("background","#000000");
infoNbTotal.setAttribute("font-size","20px");
//document.getElementById("container").appendChild(infoNbTotal);
//infoNbTotal.innerHTML = "Total (organisation) : <strong>"+number_format(nbTotalselonOrga, 0, '.', ' ')+" pers.</strong>&nbsp;&nbsp;&nbsp;&nbsp;";
//infoNbTotal.innerHTML += "Total (police) : <strong>"+number_format(nbTotalselonPolice, 0, '.', ' ')+" pers.</strong>&nbsp;&nbsp;&nbsp;&nbsp;";
//infoNbTotal.innerHTML += "<span style=\"color: red;\">En moyenne :</span> <strong style=\"color: red;\">"+number_format(((nbTotalselonOrga+nbTotalselonPolice)/2), 0, '.', ' ')+" pers.</strong>";
*/
  //cm_map.setZoom(cm_map.getBoundsZoomLevel(bounds));
  //cm_map.setCenter(bounds.getCenter());
}

function cm_getJSON() {
  var script = document.createElement('script');
  script.setAttribute('src', 'http://spreadsheets.google.com/feeds/list'
                         + '/' + param_ssKey + '/' + param_wsId + '/public/values' +
                        '?alt=json-in-script&callback=cm_loadMapJSON');
  script.setAttribute('id', 'jsonScript');
  script.setAttribute('type', 'text/javascript');
  document.documentElement.firstChild.appendChild(script);
}
setTimeout('cm_load()', 500); 
</script>
<div id='content'></div>

</body> </html>