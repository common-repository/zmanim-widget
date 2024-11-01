<style type="text/css">
		.geodesic {color: #00bb00;}
		.geodesic a:link {color: #00bb00;}
		.geodesic a:visited {color: #006600;}
		.rhumb {color: #0000bb;}
		.rhumb a:link {color: #0000bb;}
		.rhumb a:visited {color: #000088;}
		.gmap{width: 200px; height: 200px; overflow: hidden}
		.infowindow{width:450px; height:190px; overflow:auto ; overflow-x: hidden}
		.footnote{font-size:0.7em}
		.tablehead{background-color:#53868b; color:#ffffff;}
		.odd{background-color:#c6e2ff;}
		.linkToMe a:link {color: #000000;}
		.linkToMe a:visited {color: #000000;}
		.linkToMe {
			background-color:#ffffff;
			border: 1px solid black;
			text-align: center; width: 5em;
			font-size: 12px; font-weight: bold;
			cursor: pointer;
		}
		.gmapButton1 {
			color: black; font-family: Arial,sans-serif; font-size: small; -moz-user-select: none; position: absolute; right: 7px; top: 7px; width: 230px; height: 2px;
		}
		.gmapButton2 {
			border: 1px solid black; position: absolute; background-color: white; text-align: center; width: 4.5em; cursor: pointer; right: 12em;
		}
		.gmapButton3 {
			border-style: solid; border-color: white rgb(176, 176, 176) rgb(176, 176, 176) white; border-width: 1px; font-size: 12px;
		}
	</style>
<div id="map" class="gmap"></div>
<form onsubmit="findGeoLocation(); return false" id="searchForm" style="display:none" action="#">

		<input type="text" size="40" id="address" name="address" value="" onfocus="focusAddressBar();" onblur="leaveAddressBar();"/>
		<input type="button" onclick="findGeoLocation()" value="Search" />
		<!--Show great circle route <input type="checkbox" name="showGeodesic" onclick="toggleGeodesic(this)" /-->
		<!--Show Daylight <input type="checkbox" name="showGeodesic" onclick="toggleDaylight(this)" /-->
		<!--select name="timeZone" onchange="changeTimezone(this)"><option value="">Select Timezone</option><option value="GMT">GMT</option><option selected="selected" value="America/New_York">America/New_York</option></select-->
		<!--input type="button" onclick="updateLink(); location.href=#" value="Link" /-->
	</form>
        <div id="linkButton" onclick="linkTo()" class="gmnoprint gmapButton1">
			<div class="gmapButton2" title="Link to this page">

				<div class="gmapButton3">Link</div>
			</div>
		</div>
		<div id="searchButton" onclick="displaySearchForm()" class="gmnoprint gmapButton1">
			<div class="gmapButton2">
				<div class="gmapButton3">Search</div>
			</div>
		</div>
<script>
//$(document).ready (function(){
        showJerusalemMap(<?php print $data['lat'].', '.$data['long'];?>);
//});
</script>
<?
print '<a href="http://www.kosherjava.com/maps/zmanim.html?lat='.$data['lat'].'&lng='.$data['long'].'&zoom=4" target="_blank">'.__("Open larger map","zmanim").'</a>';
