var addrEmptyMsg = 'Enter a location or zipcode';
var map, koselMarker, shulMarker, straightPolyline, geodesicPolyline, zoom, maptype;
var xmlDoc, date;
//var daylight; //http://www.daylightmap.com
 
function showJerusalemMap(lat,lng) {
	leaveAddressBar();
	
	if (GBrowserIsCompatible()) {		
		map = new GMap2(document.getElementById("map"));
		//add dailight map overlay from http://www.daylightmap.com
		/*
		var daylight = new daylightMap.daylightLayer();
		daylight.active = false;
		daylight.opacity = 0.25
    		daylight.addToMap(map);
    		*/

    	
		map.disableDoubleClickZoom()
		map.addControl(new GLargeMapControl3D());
		map.addControl(new GHierarchicalMapTypeControl());
		map.addMapType(G_PHYSICAL_MAP);
		map.addControl(new GScaleControl());
		
 		 // ========== move form elements onto map ==================
		var pos = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7,36));
		pos.apply(document.getElementById("searchForm"));
		map.getContainer().appendChild(document.getElementById("searchForm"));
		
		var pos2 = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(50, 7));
		pos2.apply(document.getElementById("linkButton"));
		map.getContainer().appendChild(document.getElementById("linkButton"));
		
		var pos3 = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(110, 7));
		pos3.apply(document.getElementById("searchButton"));
		map.getContainer().appendChild(document.getElementById("searchButton"));
		
		// ============= end  move form elements onto map ===============

		var kosel = new GLatLng(31.77805, 35.235149);
		if (lat == null || lng == null) var shul = parseParameters();
		else var shul = new GLatLng(lat, lng);

		map.setCenter(shul, zoom, maptype);

		koselMarker = new GMarker(kosel, {icon:getSmallRedIcon()});
		shulMarker = new GMarker(shul, {draggable: true});
	
		GEvent.addListener(map, 'click', function(overlay, point) {
			if (point) {
				moveShul(point);
			}
		});
		
		GEvent.addListener(shulMarker,'dragend', function(){
			moveShul(shulMarker.getPoint());
		});
		moveShul(shul);
	}
}

function moveShul(point){
	GEvent.clearInstanceListeners(shulMarker); /*if not added the shul markes keep getting added, so a move triggers multiple events, and worse, multiple WS calls*/
	loadZmanimXML(point);
	map.clearOverlays();
	shulMarker.setLatLng(point);
	map.addOverlay(shulMarker);
	map.panTo(point);

	map.addOverlay(koselMarker);

	//var koselInfo = '<img src=\'kosel1.jpg\' style=\'float: left; margin-right: 5px;\' alt=\'Kosel Hamaaravi\' title=\'Kosel Hamaaravi\'  />';
	var koselInfo = '<img src=\'kosel9_trim.jpg\' alt=\'Kosel Hamaaravi\' title=\'Kosel Hamaaravi\' width=\'240\' height=\'110\' />';
	koselInfo += '<br/><br/>Latitude: ' + koselMarker.getPoint().lat() + "&deg; (" + koselMarker.getPoint().lat().toLat() + ')'
	koselInfo += '<br/>Longitude: ' + koselMarker.getPoint().lng() + "&deg; (" + koselMarker.getPoint().lng().toLon() + ")";
	GEvent.addListener(koselMarker, "click", function() {
		koselMarker.openInfoWindowHtml(koselInfo);
    	});
    
	var antipodalPoint =  koselMarker.getPoint().antipodal();
	var antiPodalMarker = new GMarker(antipodalPoint, {icon:getSmallBlueIcon()});

	map.addOverlay(antiPodalMarker);
	var antipodalInfo = '<div class="infowindow">This is the <a href=\'http://en.wikipedia.org/wiki/Antipodes\'>antipodal</a> point of the Har Habayis.';
	antipodalInfo += '<br/>Latitude: ' + antipodalPoint.lat() + '&deg; (' + antipodalPoint.lat().toLat() + ')<br/>Longitude: ' +  antipodalPoint.lng() + '&deg; (' + antipodalPoint.lng().toLon() + ')';
	
	antipodalInfo += '<br/><br/>Any direction from this point is the correct direction using the great circle route! ';
	antipodalInfo += 'There are 2 possible rhumb line bearings from anywhere along this longitude.';

	GEvent.addListener(antiPodalMarker, "click", function() {
		antiPodalMarker.openInfoWindowHtml(antipodalInfo);
	});
	
	var shulInfo = "<div class='infowindow'>Latitude: &nbsp; " + point.lat().toFixed(5) + "&deg; (" + point.lat().toLat() + ")";
	shulInfo += '<br/>Longitude: &nbsp; ' + point.lng().toFixed(5) + "&deg; (" + point.lng().toLon() + ')';
	//shulInfo += '<br/>Elevation: &nbsp; ' + xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("timeZoneName") + ' m';
	var rhumbDistance = (point.rhumbLineDistance(koselMarker.getPoint())).toFixed(2) + " km";
	shulInfo += '<span class="footnote"><br/><br/></span>Bearing to Yerushalayim *<br/>';
	shulInfo += ' &nbsp; &nbsp; <span class="rhumb"><a href=\'http://en.wikipedia.org/wiki/Rhumb_line\'>(Rhumb Line)</a>: ' + point.rhumbLineBearing(koselMarker.getPoint()).toFixed(2) + "&deg; (" + rhumbDistance + ") (Levush)</span>";

	var vincentyDistance = point.distanceVincenty(koselMarker.getPoint()).toFixed(2) + " km";
	
	shulInfo += '<br/> &nbsp; &nbsp; <span class="geodesic"><a href=\'http://en.wikipedia.org/wiki/Great_circle\'>(Great Circle)</a>: ' + point.initialBearingVincenty(koselMarker.getPoint()).toFixed(2) + "&deg; (" + vincentyDistance + ") (Emunas Chachamim)</span>";
	shulInfo +='<span class="footnote"><br/><br/>* For additional information see Rabbi Yehuda Herskowitz&#39;s article in Yeshurun v. III p. 586 and Gevuras Moishe by Rabbi Gavriel Goetz</span><div><br/>&copy; 2007 - 2010 Eliyahu Hershfeld</div>';

	shulMarker.closeInfoWindow();
	
	/*add back listener removed earlier - FIXME, should be able to simply remove the click to avoid adding and removing*/
	GEvent.addListener(shulMarker,'dragend', function(){
		moveShul(shulMarker.getPoint());	
	});
	

	GEvent.addListener(shulMarker, "click", function() {
		shulMarker.openInfoWindowTabsHtml([new GInfoWindowTab("Bearing",shulInfo), new GInfoWindowTab("Zmanim",getZmanimInfo()), new GInfoWindowTab("Zmanim++",getZmanimInfoPlus())]);
	});
	//shulMarker.openInfoWindowTabsHtml([new GInfoWindowTab("Bearing",shulInfo), new GInfoWindowTab("Zmanim",getZmanimInfo()), new GInfoWindowTab("Zmanim++",getZmanimInfoPlus())]);
	
	drawPolylines();
}

function getTZDisplay(){
	var timeZoneName =  xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("timeZoneName");
	var timeZoneID =  xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("timeZoneID");
	var timezoneOffset = xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("timeZoneOffset");
	var tzOffsetDisplay = (timezoneOffset == 0?"": " (GMT " + (timezoneOffset>0?"+":"") + timezoneOffset) + (timezoneOffset == 0?"": ")");
	//return timeZoneID + ", " + timeZoneName + tzOffsetDisplay + "<br/>Elevation: " + getElevation();
	return timeZoneID + ", " + timeZoneName + tzOffsetDisplay;
}

function getElevation(){
	var elevation =  xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("elevation");
	elevation = new Number(elevation).toFixed(2);
	return elevation;
}

function getZmanimInfo(){
	if(xmlDoc==null){
		return "<div class='infowindow'>Issue encountered retrieving zmanim data.</span>";
	}

	var zmanimInfo = "<div class='infowindow'>"; 
	zmanimInfo += getTZDisplay();
	
	zmanimInfo +="<table class=\"stripeMe\" style=\"border-width: 0px; \" width=\"99%\">";
	zmanimInfo +="<tr class=\"tablehead\"><td width=\"66%\">Zmanim (" + xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("date") + ")</td><td>Time</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Alos</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos72")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr><td>Sunrise (Sea level)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SeaLevelSunrise")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Sof Zman Shema MG\"A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr><td>Sof Zman Shema GR\"A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaGRA")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Sof Zman Tfila MG\"A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr><td>Sof Zman Tfila GR\"A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaGRA")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Chatzos</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Chatzos")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr><td>Mincha Gedola</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaGedola")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Mincha Ketana</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaKetana")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr><td>Plag Hamincha</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Sunset (Sea Level)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SeaLevelSunset")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr><td>Tzais</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo +="<tr class=\"odd\"><td>Tzais 72</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais72")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfo += "</table>";
	zmanimInfo += "</div>";
	return zmanimInfo;
}


function getZmanimInfoPlus(){
	if(xmlDoc==null){
		return "<div class='infowindow'>Issue encountered retrieving zmanim data.</span>";
	}
	var zmanimInfoPlus = "<div class='infowindow'>"; 
	
	zmanimInfoPlus += getTZDisplay();
	//zmanimInfoPlus += " &nbsp; Date: &nbsp; " + xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("date");
	zmanimInfoPlus +="<table style=\"border-width: 0px;\" width=\"99%\">";
	zmanimInfoPlus +="<tr class=\"tablehead\"><td width=\"66%\">Zmanim (" + xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("date") + ")</td><td>Time</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 26&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos26Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Alos 120 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos120")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 120 minutes zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos120Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Alos 19.8&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos19Point8Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 90 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos90")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Alos 90 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos90Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 96 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos96")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Alos 96 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos96Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 18&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos18Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Alos 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos72")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Alos 72 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos72Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Alos 60 minutes </td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Alos60")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Misheyakir 11.5&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Misheyakir11Point5Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Misheyakir 11&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Misheyakir11Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Misheyakir 10.2&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Misheyakir10Point2Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sunrise (" + getElevation() + " Meters)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Sunrise")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sunrise (Sea Level)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SeaLevelSunrise")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma Alos16.1&deg; to sunset</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaAlos16Point1ToSunset")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma MG&quot;A 120 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA120Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma Alos 16.1&deg; to Tzais Geonim 7.083&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaAlos16Point1ToTzaisGeonim7Point083Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma MG&quot;A 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma MG&quot;A 19.8&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA19Point8Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma MG&quot;A 96 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA96Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma MG&quot;A 90 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA90Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma MG&quot;A 96 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA96MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma MG&quot;A 90 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA90MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma MG&quot;A 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA72Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma 3 Hours Before Chatzos</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShma3HoursBeforeChatzos")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma MG&quot;A 72 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaMGA72MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Shma GR&quot;A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaGRA")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Shma (Kol Eliyahu)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanShmaKolEliyahu")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Tfila MG&quot;A 120 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA120Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Tfila MG&quot;A 19.8&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA19Point8Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Tfila MG&quot;A 96 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA96Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Tfila MG&quot;A 90 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA90Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Tfila MG&quot;A 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Tfila MG&quot;A 96 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA96MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Tfila MG&quot;A 90 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA90MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Tfila MG&quot;A 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA72Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Tfila 2 Hours Before Chatzos</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfila2HoursBeforeChatzos")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sof Zman Tfila MG&quot;A 72 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaMGA72MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sof Zman Tfila GR&quot;A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SofZmanTfilaGRA")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Chatzos</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Chatzos")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Mincha Gedola GR&quot;A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaGedola")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Mincha Gedola 30 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaGedola30Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Mincha Gedola (30 minutes minimum)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaGedolaGreaterThan30")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Mincha Gedola 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaGedola72Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Mincha Gedola 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaGedola16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Mincha Ketana GR&quot;A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaKetana")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Mincha Ketana 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaKetana72Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Mincha Ketana 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("MinchaKetana16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha Alos to Sunset</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagAlosToSunset")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha GR&quot;A</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha Alos 16.1 to Tzais Geonim 7.083&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagAlos16Point1ToTzaisGeonim7Point083Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 72 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha72MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha 60 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha60Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha72Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha 90 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha90MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 96 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha96MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 18&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha18Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha 90 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha90Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 96 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha96Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha 19.8&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha19Point8Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 120 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha120MinutesZmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Plag Hamincha 26&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha26Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Plag Hamincha 120 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("PlagHamincha120Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Sunset (Sea Level)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("SeaLevelSunset")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Sunset (" + getElevation() + " Meters)</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Sunset")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Bain Hasmashos Rabainu Tam 13.5 minutes Before 7.083&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("BainHasmashosRT13Point5MinutesBefore7Point083Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Bain Hasmashos Rabainu Tam 2 Stars</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("BainHasmashosRT2Stars")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Bain Hasmashos Rabainu Tam 58.5 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("BainHasmashosRT58Point5Minutes")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Bain Hasmashos Rabaanu Tam 13&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("BainHasmashosRT13Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais Geonim 5.95&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("TzaisGeonim5Point95Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais Geonim 7.083&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("TzaisGeonim7Point083Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>TzaisGeonim 8.5&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("TzaisGeonim8Point5Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 60 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais60")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais 72 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais72")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 72 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais72Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais 90 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais90")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 90 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais90Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais 96 minutes</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais96")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 96 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais96Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais 16.1&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais16Point1Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 18&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais18Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais 19.8&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais19Point8Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 120 minutes </td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais120")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr class=\"odd\"><td>Tzais 120 minutes Zmanios</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais120Zmanis")[0].childNodes[0].nodeValue) + "</td></tr>";
	zmanimInfoPlus +="<tr><td>Tzais 26&deg;</td><td>" + getLocalTime(xmlDoc.getElementsByTagName("Tzais26Degrees")[0].childNodes[0].nodeValue) + "</td></tr>";

	zmanimInfoPlus += "</table>";
	zmanimInfoPlus += "</div>";
	return zmanimInfoPlus;
}
function drawPolylines(){
	//remove polylines first
	if(geodesicPolyline){
		map.removeOverlay(geodesicPolyline);
	}
	if(straightPolyline){
		map.removeOverlay(straightPolyline);
	}
	var points=[koselMarker.getPoint(), shulMarker.getPoint()];
	straightPolyline = new GPolyline(points, "#0000ff", 3);
	map.addOverlay(straightPolyline); //straight GPolyline

	geodesicPolyline = new GPolyline(points,"#00ff00", 3, 1,{geodesic:true});
	map.addOverlay(geodesicPolyline); //geodesic GPolyline

}


function findGeoLocation(){
	var geocoder = new GClientGeocoder();
	var address = document.getElementById('address').value;
	if (address == '' || address == addrEmptyMsg) {
		alert(addrEmptyMsg);
		return;
	}
	geocoder.getLocations(address, showAddressOnMap);
}

// called when address bar is focused
function focusAddressBar() {
	var address = document.getElementById('address');
	address.style.color = '#000000';
	if (address.value == addrEmptyMsg)
		address.value = '';
}


// called when address bar lose focus
function leaveAddressBar() {
	var address = document.getElementById('address');
	if (address.value == '' || address.value == addrEmptyMsg) {
		address.style.color = '#999999';
		address.value = addrEmptyMsg;
	}
}

function showAddressOnMap(response) {
	if (!response || response.Status.code != 200) 
		alert('Address not found');
	else {
		place = response.Placemark[0];
		point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
		if(place.AddressDetails){
			map.setCenter(point, 8+ place.AddressDetails.Accuracy);
		} else {
			map.setCenter(point, 8);
		}
		displaySearchForm();//hide search
		moveShul(point);
	}
}

function displaySearchForm(){
	if(document.getElementById("searchForm").style.display=="block"){
		document.getElementById("searchForm").style.display="none"
	} else {
		document.getElementById("searchForm").style.display="block";
	}
}

function getSmallRedIcon(){
	var redIcon = new GIcon();
	redIcon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
	redIcon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
	redIcon.iconSize = new GSize(12, 20);
	redIcon.shadowSize = new GSize(22, 20);
	redIcon.iconAnchor = new GPoint(6, 20);
	redIcon.infoWindowAnchor = new GPoint(5, 1);
	return redIcon;
}

function getSmallBlueIcon(){
	var blueIcon = new GIcon();
	blueIcon.image = "http://labs.google.com/ridefinder/images/mm_20_blue.png";
	blueIcon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
	blueIcon.iconSize = new GSize(12, 20);
	blueIcon.shadowSize = new GSize(22, 20);
	blueIcon.iconAnchor = new GPoint(6, 20);
	blueIcon.infoWindowAnchor = new GPoint(5, 1);
	return blueIcon;
}


function writeValue(id, value){
	document.getElementById(id).innerHTML = value;
}

function parseParameters(){
	// set some defaults in case there are no parameters
	var lat = 40.095965;//BMG, lakewood
	var lng = -74.222130;//BMG, lakewood
	zoom=4;
	maptype = G_NORMAL_MAP;
	// If there are any parameters at the end of the URL, they will be in  location.search
	// looking something like  "?lat=50&lng=-3&zoom=10&maptype=h"
	// skip the first character, we are not interested in the "?"
	var query = location.search.substring(1);

	// split the rest at each "&" character to give a list of  "argname=value"  pairs
	var pairs = query.split("&");
	for (var i=0; i<pairs.length; i++) {
		// break each pair at the first "=" to obtain the argname and value
		var pos = pairs[i].indexOf("=");
		var argname = pairs[i].substring(0,pos).toLowerCase();
		var value = pairs[i].substring(pos+1).toLowerCase();

		// process each possible argname
		if (argname == "date") {date = value;}
		if (argname == "lat") {lat = parseFloat(value);}
		if (argname == "lng") {lng = parseFloat(value);}
		if (argname == "zoom") {zoom = parseInt(value);}
		if (argname == "type") {
			if (value == "m") {maptype = G_NORMAL_MAP;}
			if (value == "k") {maptype = G_SATELLITE_MAP;}
			if (value == "h") {maptype = G_HYBRID_MAP;}
			if (value == "p") {maptype = G_PHYSICAL_MAP;}
		}
	}
	return new GLatLng(lat,lng);
}

/**
 * returns a link to the current map. It currently centers the map on the "shul" pointer
 */
function getLink() {
	var link=location.pathname
		+ "?lat=" + shulMarker.getPoint().lat()
		+ "&lng=" + shulMarker.getPoint().lng()
		+ "&zoom=" + map.getZoom();
	if(!(map.getCurrentMapType().getUrlArg() == "m")){
		link += "&type=" + map.getCurrentMapType().getUrlArg();
	}
	return link;
}

function linkTo(){
	//document.getElementById('link').href = getLink();
	top.location.href=getLink();
}

function toggleDaylight(checkbox){
	if (checkbox.checked) {
		daylight.active = true;
    	daylight.refresh();
	} else {
		daylight.active = false;
		daylight.refresh();
	}
	moveShul(shulMarker.getPoint()); //needed up update infowindow text
}

function loadZmanimXML(point) {
	if(date == null) {
		dateObj = new Date(); //current date
		date = dateObj.getFullYear() + "-" + (dateObj.getMonth() + 1) + "-" + dateObj.getDate();
	}
	xmlDoc = null; //reset zmanim
	//var url="/java-kosherja/zmanim/zmanimXMLOutput5.jsp?lat=" + point.lat() + "&lng=" + point.lng() + "&date=" + date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
	var url="http://www.kosherjava.com/java-kosherja/zmanim/zmanimXMLOutput5.jsp?lat=" + point.lat() + "&lng=" + point.lng() + "&date=" + date;

	$.get(url, function(data){//use jQuery 1.4
		xmlDoc = data;
		if(xmlDoc.getElementsByTagName("Zmanim")[0].getAttribute("timeZoneID") == "GMT"){
			alert("Could not locate the time zone. Defaulting to GMT. The service used to look up time zones only works on dry land.");
		}
 	});
}

//debug xml
/*function getXMLNodeSerialisation(xmlNode) {
  var text = false;
  try {
    // Gecko-based browsers, Safari, Opera.
    var serializer = new XMLSerializer();
    text = serializer.serializeToString(xmlNode);
  }
  catch (e) {
    try {
      // Internet Explorer.
      text = xmlNode.xml;
    }
    catch (e) {}
  }
  return text;
}*/

/**
 * format XSD DateTime as a time
 */
function parseIso8601Time(xsdDateTime) {
	if(xsdDateTime == "null"){
		return xsdDateTime;
	} else {
		return xsdDateTime.substr(11, 8);
	}
}

/**
 * adjusts the time for the timezone offset
 */
function getLocalTime(xsdDateTime){
	if(xsdDateTime == "null"){
		return "N/A";
	}
	
	if(xsdDateTime == "N/A"){
		return "N/A";
	}
	var dateString = parseIso8601Time(xsdDateTime);
	var hours = dateString.substr(0, 2);
	if( hours.indexOf("0") ==0){
			hours= hours.substr(1, 1);
	}
	hours = parseInt(hours);
	if(hours == 0){
		hours = 24;
	}
	if(hours < 0) {
			hours += 24;
	}
	if(hours > 24){
		hours -= 24;
	}
	
	var minutes = dateString.substr(3, 2);
	var seconds = dateString.substr(6, 2);
	return formatAMPM(hours, minutes, seconds);
	
}

/**
 * converts 24 hour time to AM/PM
 */
function formatAMPM(hours, minutes, seconds){
	if(hours==0){amPm=" AM"; hours = 12}
	else if(hours <= 11){amPm=" AM"}
	else if(hours == 12){amPm=" PM"; hours = 12}
	else if(hours >= 13){amPm=" PM"; hours -= 12}
	return hours + ":" + minutes + ":" + seconds + amPm;
}
