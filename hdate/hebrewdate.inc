<?php
/*
KosherJava
http://www.kosherjava.com

This program is free software; you can redistribute it and/or modify it under the terms of the
GNU General Public License as published by the Free Software Foundation; either version 2 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if
not, write to the Free Software Foundation, Inc. 59 Temple Place - Suite 330, Boston, MA
02111-1307, USA or connect to: http://www.fsf.org/copyleft/gpl.html
*/

/*	$convertedDate = "";
	if($jewishDateStyle == "HEBREW_AND_GREGORIAN"){
		$convertedDate = getHebrewJewishDates($hebrewYear, $hebrewMonth, $hebrewDay, $altHebrewYear, $altHebrewMonth);
	} else if($jewishDateStyle == "JEWISH_AND_GREGORIAN"){
		$convertedDate = getLatinJewishDates($hebrewYear, $hebrewMonth, $hebrewDay, $altHebrewYear, $altHebrewMonth);
	}
*/
function getLatinJewishDates($year, $month="", $day="", $altYear="", $altMonth="") {
	$hebrewMonthName = getJewishMonthName($month, $year, $useAshkenaziSpelling);
	if(empty($day) && $altMonth !=0 && $altYear !=0 && $month != $altMonth && $year != $altYear) { //elul tishrai
		$hebrewMonthName .= " ";
		$hebrewMonthName .= $hebrewYear;
		$hebrewYear = " / ";
		$hebrewYear .= getJewishMonthName($altMonth, $altYear, $useAshkenaziSpelling);
		$hebrewYear .= " ";
		$hebrewYear .= $altYear;
	} else if(empty($day) && $altMonth !=0 && $month != $altMonth) {
		$hebrewMonthName .= " / ";
		$hebrewMonthName .= getJewishMonthName($altMonth, $altYear, $useAshkenaziSpelling);
	}

	$convertedDate  = preg_replace("/D/", $day, "D M Y");
	$convertedDate = preg_replace("/M/", $hebrewMonthName.",", $convertedDate );
	$convertedDate = preg_replace("/Y/", $year, $convertedDate );
	return $convertedDate;
}

function getHebrewJewishDates($year, $month="", $day="", $altYear="", $altMonth="") {
	$sb = "";//"<span lang=\"he-IL\" dir=\"rtl\">"; //probably not needed with utf-8
	if($day != "") {
		$sb .= getHebrewJewishDay($day);
		$sb .= " ";
	}
	if($month != "") {
		if($day != "") { //jewish date is exact
			$sb .= getHebrewJewishMonth($month, $year);
			$sb .= " ";
		} else { //only month and not day
			$sb .= getHebrewJewishMonth($month, $year);
			if($altMonth != "" && $altMonth != $month) {
				$sb .= " / ";
				$sb .= getHebrewJewishMonth($altMonth, $altYear);
			}
			$sb .= " ";
		}
	}
	if($month=="") {
		$sb .= getHebrewJewishYear($year - 1);
		$sb .= " / ";
		$sb .= getHebrewJewishYear($year);
	} else if($altMonth!=0 && $month != $altMonth && $altYear !=0 && $altYear != $year && $day == "") {
		$sb .= getHebrewJewishYear($year);
		$sb .= " / ";
		$sb .= getHebrewJewishYear($altYear);
	} else {
		$sb .= getHebrewJewishYear($year);
	}

//	$sb .= "</span>";
	return $sb;
}

function getHebrewJewishDay($day) {
	$jTens = array("", "&#1497;", "&#1499;", "&#1500;", "&#1502;", "&#1504;", "&#1505;", "&#1506;", "&#1508;", "&#1510;");
	$jTenEnds = array("", "&#1497;", "&#1498;", "&#1500;", "&#1501;", "&#1503;", "&#1505;", "&#1506;", "&#1507;", "&#1509;");
	$tavTaz = array("&#1496;&quot;&#1493;", "&#1496;&quot;&#1494;");
	$jOnes = array("", "&#1488;", "&#1489;", "&#1490;", "&#1491;", "&#1492;", "&#1493;", "&#1494;", "&#1495;", "&#1496;");

	$sb = "";
	if($day < 10) { //single digit days get single quote appended
		$sb .= $jOnes[$day];
		$sb .= "'";
	} else if($day == 15) { //special case 15
		$sb .= $tavTaz[0];
	} else if($day == 16) { //special case 16
		$sb .= $tavTaz[1];
	} else {
		$tens = $day / 10;
		$sb .= $jTens[$tens];
		if($day % 10 == 0) { // 10 or 20 single digit append single quote
			$sb .= "'";
		} else if($day > 10) { // >10 display " between 10s and 1s
			$sb .= "&quot;";
		}
		$day = $day % 10; //discard 10s
		$sb .= $jOnes[$day];
	}
	return $sb;
}

function getHebrewJewishMonth($month, $year) {
	$jMonths = array("&#1514;&#1513;&#1512;&#1497;",
				"&#1495;&#1513;&#1493;&#1503;",
				"&#1499;&#1505;&#1500;&#1493;",
				"&#1496;&#1489;&#1514;",
				"&#1513;&#1489;&#1496;",
				"&#1488;&#1491;&#1512; &#1488;'",
				"&#1488;&#1491;&#1512; &#1489;'",
				"&#1504;&#1497;&#1505;&#1503;",
				"&#1488;&#1497;&#1497;&#1512;",
				"&#1505;&#1497;&#1493;&#1503;",
				"&#1514;&#1502;&#1493;&#1494;",
				"&#1488;&#1489;",
				"&#1488;&#1500;&#1493;&#1500;",
				"&#1488;&#1491;&#1512;"); //last 1 is Adar for non leap year

	if($month == 6) { // if Adar check for leap year
		if(isJewishLeapYear($year)) {
			return $jMonths[5]; //if it is leap year return default php "Adar A"
		} else { // non leap year
			return $jMonths[13];
		}
	} else { // non Adar months
		return $jMonths[$month - 1];
	}
}

function getHebrewJewishYear($year) {
	$DISPLAY_JEWISH_THOUSANDS = false;

	$jAlafim = "&#1488;&#1500;&#1508;&#1497;&#1501;"; //word ALAFIM in Hebrew for display on years evenly divisable by 1000
	$jHundreds = array("", "&#1511;", "&#1512;", "&#1513;", "&#1514;", "&#1514;&#1511;", "&#1514;&#1512;","&#1514;&#1513;", "&#1514;&#1514;", "&#1514;&#1514;&#1511;");
	$jTens = array("", "&#1497;", "&#1499;", "&#1500;", "&#1502;", "&#1504;", "&#1505;", "&#1506;", "&#1508;", "&#1510;");
	$jTenEnds = array("", "&#1497;", "&#1498;", "&#1500;", "&#1501;", "&#1503;", "&#1505;", "&#1506;", "&#1507;", "&#1509;");
	$tavTaz = array("&#1496;&quot;&#1493;", "&#1496;&quot;&#1494;");
	$jOnes = array("", "&#1488;", "&#1489;", "&#1490;", "&#1491;", "&#1492;", "&#1493;", "&#1494;", "&#1495;", "&#1496;");

	$singleDigitYear = isSingleDigitJeiwshYear($year);
	$thousands = $year / 1000; //get # thousands

	$sb = "";
	//append thousands to String
	if($year % 1000 == 0) { // in year is 5000, 4000 etc
		$sb .= $jOnes[$thousands];
		$sb .= "'";
		$sb .= "&#160;";
		$sb .= $jAlafim; //add # of thousands plus word thousand (overide alafim boolean)
	} else if($DISPLAY_JEWISH_THOUSANDS) { // if alafim boolean display thousands
		$sb .= $jOnes[$thousands];
		$sb .= "'"; //append thousands quote
		$sb .= "&#160;";
	}
	$year = $year % 1000;//remove 1000s
	$hundreds = $year / 100; // # of hundreds
	$sb .= $jHundreds[$hundreds]; //add hundreds to String
	$year = $year % 100; //remove 100s
	if($year == 15) { //special case 15
		$sb .= $tavTaz[0];
	} else if($year == 16) { //special case 16
		$sb .= $tavTaz[1];
	} else {
		$tens = $year / 10;
		if($year % 10 == 0) { // if evenly divisable by 10
			if($singleDigitYear == false) {
				$sb .= $jTenEnds[$tens]; // use end letters so that for example 5750 will end with an end nun
			} else {
				$sb .= $jTens[$tens]; // use standard letters so that for example 5050 will end with a regular nun
			}
		} else {
			$sb .= $jTens[$tens];
			$year = $year % 10;
			$sb .= $jOnes[$year];
		}
	}
	if($singleDigitYear == true) {
		$sb .= "'"; //append single quote
	} else { // append double quote before last digit
		$pos1 = strrpos($sb, "&");
		$sb = substr($sb, 0, $pos1) . "&quot;" . substr($sb, $pos1);
	}
	return $sb;
}

function isSingleDigitJeiwshYear($year) {
	$shortYear = $year %1000; //discard thousands
	//next check for all possible single Hebrew digit years
	if($shortYear < 11 || ($shortYear <100 && $shortYear % 10 == 0)  || ($shortYear <= 400 && $shortYear % 100 == 0) ) {
		return true;
	} else {
		return false;
	}
}


function getJewishMonthName($month, $year, $useAshkenaziSpelling) {
	$ashkenazMonths = array("Tishrei", "Cheshvan", "Kislev", "Teves", "Shevat", "Adar I", "Adar II", "Nisan", "Iyar", "Sivan", "Tamuz", "Av", "Elul", "Adar");
	$sefardMonths = array("Tishrei", "Heshvan", "Kislev", "Tevet", "Shevat", "Adar I", "Adar II", "Nisan", "Iyar", "Sivan", "Tamuz", "Av", "Elul", "Adar");
	$monthNames = $ashkenazMonths;
	if(! $useAshkenaziSpelling) {
		$monthNames = $sefardMonths;
	}
	if($month == 6) { // if Adar check for leap year
		if(isJewishLeapYear($year)) {
			return $monthNames[5];
		} else {
			return $monthNames[13];
		}
	} else {
		if (isset($monthNames[$month - 1])) return $monthNames[$month - 1];
		else return $monthNames[$month];
	}

}

function isJewishLeapYear($year) {
	if($year % 19 == 0 || $year % 19 == 3 || $year % 19 ==6 || $year % 19 == 8 || $year % 19 == 11
			|| $year % 19 == 14 || $year % 19 == 17) { // 3rd, 6th, 8th, 11th, 14th, 17th or 19th years of 19 year cycle
		return true;
	} else { // non leap year
		return false;
	}
}

?>
