<?php
//include("holidays_$lang.inc");
//include("holidays.inc");
//if ($lang == yd) include("holidays.inc");

$isDiaspora = true;
$postponeShushanPurimOnSaturday = true;
$gyear = date("Y", $ts_t);
$weekdayNames = array(__("Sunday","zmanim"), __("Monday","zmanim"), __("Tuesday","zmanim"), __("Wednesday","zmanim"),
                        __("Thursday","zmanim"), __("Friday","zmanim"), __("Saturday","zmanim"));
$header = "Nearest date";

$gmonth = date("m", $ts_t);
$gday = date("d", $ts_t+(60 * 60 *24));

echo "<ul class=\"zmanim-inner-section\"><li><b>".__("Holidays","zmanim");
if ($hebrew) print ' '.translate_z('Holidays',"hebrew");
echo "</b></li><li><ul id=\"zmanim-holidays\">";

$holidays = getJewishHoliday2($jdCurrent_t, $isDiaspora, $postponeShushanPurimOnSaturday);
	$holiday = '';
          if (!empty($holidays)) $holiday = $holidays[0];
         if ($holiday!='') echo "<li>".__("Today","zmanim").": $holiday</li>";

for ($gmonth; $gmonth <= 12; $gmonth++) {
    $lastGDay = cal_days_in_month(CAL_GREGORIAN, $gmonth, $gyear);
    for ($gday; $gday <= $lastGDay; $gday++) {
$jdCurrent = gregoriantojd($gmonth, $gday, $gyear);
      $jewishDate = jdtojewish($jdCurrent);
      list($jewishMonth, $jewishDay, $jewishYear) = split('/', $jewishDate);
      $jewishMonthName = getJewishMonthName2($jewishMonth, $jewishYear);
      $holidays = getJewishHoliday2($jdCurrent, $isDiaspora, $postponeShushanPurimOnSaturday);
//echo "$gmonth $jewishDate";
	
	if (count($holidays) > 0) {
	$jdCurrent = gregoriantojd($gmonth, $gday, $gyear);
      $weekdayNo = jddayofweek($jdCurrent, 0);
      $weekdayName = $weekdayNames[$weekdayNo];
	$h_ts = mktime(0, 0, 0, $gmonth, $gday, $gyear, -1);
	$h_date = strftime($date_format_local, $h_ts); //date("d M Y", $h_ts);
	//$h_date = date($date_format, $h_ts);
          $holiday = $holidays[0];
          echo "<li>".__("Upcoming date","zmanim").": $holiday</li>";
        echo "<li>".__("will come", "zmanim")." ".__("on","zmanim")." $weekdayName<br />$h_date<br />$jewishDay $jewishMonthName $jewishYear</li>";
	break 2;
        }
      }
$gday = 1;
}
?>
</ul>
<?
if (isset($data['zman_hide']) && $data['zman_hide-button']!="one") {
  print '<div class="textright">';
        print '<a href="#" class="hide zmanim-holidays';
        if (in_array("holidays",$hide_exclude)) print ' hiddenz';
        print '" rel="zmanim-holidays">'.__("Show","zmanim").'</a>';
        print '<a href="#" class="hide zmanim-holidays';
        if (!in_array("holidays",$hide_exclude)) print ' hiddenz';
        print '" rel="zmanim-holidays">'.__("Hide","zmanim").'</a>';
   print '</div>';
}?>
</li>
</ul>
