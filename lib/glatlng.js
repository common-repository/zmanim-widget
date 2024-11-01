/**
 * The following distance and bearing functions are from 
 * http://www.movable-type.co.uk/scripts/latlong.html
 * and were released by Chris Veness under the LGPL.
 * These were modified to Extends the Google Maps GLatLng.
 *
 * TODO - Implement missing functions.
 *
 */
 
//var R = 6371; // earth's mean radius in km (Authalic mean radius)
var R = 6378.137; // earth's mean radius in km WGS84 (equatorial) also used by Google Maps, MS Virtual Earth and Yahoo Maps


/**
 * calculate the distance (in km), between 2 ponts on rhumb line
 *   see http://williams.best.vwh.net/avform.htm#Rhumb
 */
GLatLng.prototype.rhumbLineDistance = function(point) {
	//see http://en.wikipedia.org/wiki/Earth_radius
	//var R = 6378.137; // earth's mean radius in km WGS84 (equatorial) also used by Google Maps, MS Virtual Earth and Yahoo Maps

	var dLat = (point.latRadians() - this.latRadians());
	var dLon = Math.abs(point.lngRadians() - this.lngRadians());
	
	var dPhi = Math.log(Math.tan(point.latRadians()/2+Math.PI/4)/Math.tan(this.latRadians()/2+Math.PI/4));

	var q = dLat/dPhi;
	if (!isFinite(q)) q = Math.cos(this.latRadians());
	// if dLon over 180° take shorter rhumb across 180° meridian:
	if (dLon > Math.PI) dLon = 2*Math.PI - dLon;
	var d = Math.sqrt(dLat*dLat + q*q*dLon*dLon); 
	return d * R;
}


/**
 * Extends the GLatLng to return geodesic distance (in km) between 
 * between itself and another GLatLng, using the Vincenty inverse formula for ellipsoids.
 */
GLatLng.prototype.distanceVincenty = function(point) {
	return vincentyFunctions(point, this, 'distance');
}

/**
 * Extends the GLatLng to return the initial gearing of a geodesic route between 
 * between itself and another GLatLng, using the Vincenty inverse formula for ellipsoids.
 */
GLatLng.prototype.initialBearingVincenty = function(point) {
	return vincentyFunctions(point, this, 'initialBearing');
}

/**
 * Extends the GLatLng to return the final gearing of a geodesic route between 
 * between itself and another GLatLng, using the Vincenty inverse formula for ellipsoids.
 */
GLatLng.prototype.finalBearingVincenty = function(point) {
	return vincentyFunctions(point, this, 'finalBearing');
}

/**
 * FIXME the GLatLng to provide geodesic distance (in km) between 
 * between itself and another GLatLng, using the Vincenty inverse formula for ellipsoids.
 */
function vincentyFunctions(point, origPoint, type) {
	var a = 6378137, b = 6356752.3142,  f = 1/298.257223563;  // WGS-84 ellipsiod
	var L = (point.lngRadians()-origPoint.lngRadians());

	var U1 = Math.atan((1-f) * Math.tan(origPoint.latRadians()));
	var U2 = Math.atan((1-f) * Math.tan(point.latRadians()));
	
	var sinU1 = Math.sin(U1), cosU1 = Math.cos(U1);
	var sinU2 = Math.sin(U2), cosU2 = Math.cos(U2);

	var lambda = L, lambdaP = 2*Math.PI;
	var iterLimit = 20;
	while (Math.abs(lambda-lambdaP) > 1e-12 && --iterLimit>0) {
		var sinLambda = Math.sin(lambda), cosLambda = Math.cos(lambda);
		var sinSigma = Math.sqrt((cosU2*sinLambda) * (cosU2*sinLambda) + 
				(cosU1*sinU2-sinU1*cosU2*cosLambda) * (cosU1*sinU2-sinU1*cosU2*cosLambda));
		if (sinSigma==0) return 0;  // co-incident points
		var cosSigma = sinU1*sinU2 + cosU1*cosU2*cosLambda;
		var sigma = Math.atan2(sinSigma, cosSigma);
		var sinAlpha = cosU1 * cosU2 * sinLambda / sinSigma;
		var cosSqAlpha = 1 - sinAlpha*sinAlpha;
		var cos2SigmaM = cosSigma - 2*sinU1*sinU2/cosSqAlpha;
		if (isNaN(cos2SigmaM)) cos2SigmaM = 0;  // equatorial line: cosSqAlpha=0 (§6)
		var C = f/16*cosSqAlpha*(4+f*(4-3*cosSqAlpha));
		lambdaP = lambda;
		lambda = L + (1-C) * f * sinAlpha *
			(sigma + C*sinSigma*(cos2SigmaM+C*cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)));
  	}
	if (iterLimit==0) return NaN  // formula failed to converge

	var uSq = cosSqAlpha * (a*a - b*b) / (b*b);
	var A = 1 + uSq/16384*(4096+uSq*(-768+uSq*(320-175*uSq)));
	var B = uSq/1024 * (256+uSq*(-128+uSq*(74-47*uSq)));
	var deltaSigma = B*sinSigma*(cos2SigmaM+B/4*(cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)-
		B/6*cos2SigmaM*(-3+4*sinSigma*sinSigma)*(-3+4*cos2SigmaM*cos2SigmaM)));
	var s = b*A*(sigma-deltaSigma);
	var fwdAz = Math.atan2(cosU2*sinLambda, cosU1*sinU2-sinU1*cosU2*cosLambda);
	var revAz = Math.atan2(cosU1*sinLambda, -sinU1*cosU2+cosU1*sinU2*cosLambda);
	if(type == 'initialBearing'){
		return fwdAz.toBearing();
	} else if(type == 'finalBearing'){
		return revAz.toBearing();
	} else if(type == 'distance'){
		return s/1000; //return distance in km
	} else {
		throw "UnsupportedFunction";
	}
}


/**
 * Extends the GLatLng to provide the rhumb line bearing between itself and another GLatLng
 */
GLatLng.prototype.rhumbLineBearing = function(point) {
	var dLon = (point.lng()-this.lng()).toRad();
	var dPhi = Math.log(Math.tan(point.lat().toRad()/2+Math.PI/4)/Math.tan(this.lat().toRad()/2+Math.PI/4));
	if (Math.abs(dLon) > Math.PI) dLon = dLon>0 ? -(2*Math.PI-dLon) : (2*Math.PI+dLon);
	return Math.atan2(dLon, dPhi).toBearing();
}


/**
 * calculate (initial) bearing between two points
 * Extends the GLatLng to provide the initial geodesic bearing between itself and another GLatLng
 *
 * from: Ed Williams' Aviation Formulary, http://williams.best.vwh.net/avform.htm#Crs
 */
GLatLng.prototype.geodesicBearing = function(point) {
	var dLon = point.lngRadians()-this.lngRadians();
	var y = Math.sin(dLon) * Math.cos(point.latRadians());
	var x = Math.cos(this.latRadians())*Math.sin(point.latRadians()) - Math.sin(this.latRadians())*Math.cos(point.latRadians())*Math.cos(dLon);
	return Math.atan2(y, x).toBearing();
}

/**
 * Returns the antipodal point of any given point
 * Extends the GLatLng to provide this functionality
 */
GLatLng.prototype.antipodal = function() {
	var antipodalLat = -1 * this.lat();
	var antipodalLng = (180 - this.lng()) * -1;
	if (this.lng() < 0) {
		antipodalLng = 180 + this.lng();
	}
	return new GLatLng(antipodalLat, antipodalLng);
}

/**
 * Use Haversine formula to Calculate distance (in km) between two points specified by 
 * latitude/longitude (in numeric degrees)
 *
 * from: Haversine formula - R. W. Sinnott, "Virtues of the Haversine",
 *       Sky and Telescope, vol 68, no 2, 1984
 *       http://www.census.gov/cgi-bin/geo/gisfaq?Q5.1
 *
 */
GLatLng.prototype.haversineDistance = function(point) {
	// see http://en.wikipedia.org/wiki/Earth_radius
	//var R = 6371; // earth's mean radius in km (Authalic mean radius)
	//var R = 6378.137; // earth's mean radius in km WGS84 (equatorial) also used by Google Maps, MS Virtual Earth and Yahoo Maps

	var dLat = point.latRadians()-this.latRadians();
	var dLon = point.lngRadians()-this.lngRadians();
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.cos(this.latRadians()) * Math.cos(point.latRadians()) * 
				Math.sin(dLon/2) * Math.sin(dLon/2);
				
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	return R * c;
}

/**
 * Use spherical law of cosines to calculate distance (in km) between two points specified by latitude/longitude 
 * (in numeric degrees).
 */
GLatLng.prototype.distanceCosineLaw = function(point) {
	//see http://en.wikipedia.org/wiki/Earth_radius
	//var R = 6371; // earth's mean radius in km (Authalic mean radius)
	//var R = 6378.137; // earth's mean radius in km WGS84 (equatorial) also used by Google Maps, MS Virtual Earth and Yahoo Maps
	return Math.acos(Math.sin(this.latRadians())*Math.sin(point.latRadians()) + 
			Math.cos(this.latRadians())*Math.cos(point.latRadians())*Math.cos(point.lngRadians()-this.lngRadians())) * R;
}


/**
 * Calculate destination point given start point lat/long (numeric degrees), 
 * bearing (numeric degrees) & distance (in m).
 *
 * from: Vincenty direct formula - T Vincenty, "Direct and Inverse Solutions of Geodesics on the 
 *       Ellipsoid with application of nested equations", Survey Review, vol XXII no 176, 1975
 *       http://www.ngs.noaa.gov/PUBS_LIB/inverse.pdf
 */
GLatLng.prototype.destVincenty = function(brng, dist) {
  var a = 6378137, b = 6356752.3142,  f = 1/298.257223563;  // WGS-84 ellipsiod
  var s = dist;
  var alpha1 = brng.toRad();
  var sinAlpha1 = Math.sin(alpha1), cosAlpha1 = Math.cos(alpha1);
  
  var tanU1 = (1-f) * Math.tan(this.latRadians());
  var cosU1 = 1 / Math.sqrt((1 + tanU1*tanU1)), sinU1 = tanU1*cosU1;
  var sigma1 = Math.atan2(tanU1, cosAlpha1);
  var sinAlpha = cosU1 * sinAlpha1;
  var cosSqAlpha = 1 - sinAlpha*sinAlpha;
  var uSq = cosSqAlpha * (a*a - b*b) / (b*b);
  var A = 1 + uSq/16384*(4096+uSq*(-768+uSq*(320-175*uSq)));
  var B = uSq/1024 * (256+uSq*(-128+uSq*(74-47*uSq)));
  
  var sigma = s / (b*A), sigmaP = 2*Math.PI;
  while (Math.abs(sigma-sigmaP) > 1e-12) {
    var cos2SigmaM = Math.cos(2*sigma1 + sigma);
    var sinSigma = Math.sin(sigma), cosSigma = Math.cos(sigma);
    var deltaSigma = B*sinSigma*(cos2SigmaM+B/4*(cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)-
      B/6*cos2SigmaM*(-3+4*sinSigma*sinSigma)*(-3+4*cos2SigmaM*cos2SigmaM)));
    sigmaP = sigma;
    sigma = s / (b*A) + deltaSigma;
  }

  var tmp = sinU1*sinSigma - cosU1*cosSigma*cosAlpha1;
  var lat2 = Math.atan2(sinU1*cosSigma + cosU1*sinSigma*cosAlpha1, 
      (1-f)*Math.sqrt(sinAlpha*sinAlpha + tmp*tmp));
  var lambda = Math.atan2(sinSigma*sinAlpha1, cosU1*cosSigma - sinU1*sinSigma*cosAlpha1);
  var C = f/16*cosSqAlpha*(4+f*(4-3*cosSqAlpha));
  var L = lambda - (1-C) * f * sinAlpha *
      (sigma + C*sinSigma*(cos2SigmaM+C*cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)));

  //var revAz = Math.atan2(sinAlpha, -tmp);  // final bearing

  return new GLatLng(lat2.toDeg(), this.lng()+L.toDeg());
}


// extend Number object with methods for converting degrees/radians

Number.prototype.toRad = function() {  // convert degrees to radians
  return this * Math.PI / 180;
}

Number.prototype.toDeg = function() {  // convert radians to degrees (signed)
  return this * 180 / Math.PI;
}

Number.prototype.toBearing = function() {  // convert radians to degrees (as bearing: 0...360)
  return (this.toDeg()+360) % 360;
}

Number.prototype.toDMS = function() {  // convert numeric degrees to deg/min/sec
	var d = Math.abs(this);  // (unsigned result ready for appending compass dir'n)
	d += 1/7200;  // add ½ second for rounding
	var deg = Math.floor(d);
	var min = Math.floor((d-deg)*60);
	var sec = Math.floor((d-deg-min/60)*3600);
	// add leading zeros if required
	/* if (deg<100) deg = '0' + deg;
	   if (deg<10) deg = '0' + deg;
	   if (min<10) min = '0' + min;
	   if (sec<10) sec = '0' + sec; */
	return deg + '\u00B0 ' + min + '\u2032 ' + sec + '\u2033';
}

Number.prototype.toLat = function() {  // convert numeric degrees to deg/min/sec latitude
	//return this.toDMS().slice(1) + (this<0 ? 'S' : 'N');  // knock off initial '0' for lat!
	return this.toDMS() + ' ' + (this<0 ? 'S' : 'N');
}

Number.prototype.toLon = function() {  // convert numeric degrees to deg/min/sec longitude
	return this.toDMS() + ' ' + (this>0 ? 'E' : 'W');
}