<?
//Gregoriant day, month and year
$gmonth_t = date("m", $ts_t);
$gday_t = date("d", $ts_t);
$gyear_t = date("Y", $ts_t);

//magic with conversion Gregorian to Jewish
$jdCurrent_t = gregoriantojd($gmonth_t, $gday_t, $gyear_t);
$jewishDate_t = jdtojewish($jdCurrent_t);
list($jewishMonth_t, $jewishDay_t, $jewishYear_t) = split('/', $jewishDate_t);
$jewishMonthName_t = getJewishMonthName2($jewishMonth_t, $jewishYear_t);
$h_ts_t = ($ts +(60 * 60 *$shoffset));

$h_date_t = strftime($date_format_local, $h_ts_t); //date("d M Y", $h_ts);
//$h_date_t = date($date_format, $h_ts_t);

$isDiaspora=false;
$postponeShushanPurimOnSaturday=false;

//display data
echo "<ul><li>$h_date_t</li>";
echo "<li>$jewishDay_t $jewishMonthName_t $jewishYear_t</li>";
echo "<li>".getHebrewJewishDates($jewishYear_t, $jewishMonth_t, $jewishDay_t)."</li>";

      $holidays = getJewishHoliday2($jdCurrent_t, $isDiaspora, $postponeShushanPurimOnSaturday);
	$holiday = '';
          if (!empty($holidays)) $holiday = $holidays[0];
       //  if ($holiday!='') echo "<li>$holiday</li>";

echo "</ul>";
echo '<ul class="zmanim-inner-section"><li><b>'.__("Zmanim","zmanim").' ';
if ($hebrew) print translate_z('zmanim','hebrew');
echo '</b></li><li>';
echo '<ul id="zmanim-today">';
//$zman="90";
if ( $zman_alot == "72" ) {
	list($hour,$min) = explode(':',$sunriseStr_t);
	$hour--;
	$min -= 12;
	if ($min <0) {
		$hour--;
		$min += 60;
	}
	if (strlen($min)<2) $min='0'.$min;
        if (strlen($hour)<2) $hour='0'.$hour;
	//$time_2 = $hour.':'.$min;
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
}elseif ( $zman_alot == "90" ) {
	list($hour,$min) = explode(':',$sunriseStr_t);
        $hour--;
        $min -= 30;
        if ($min <0) {
                $hour--;
                $min += 60;
        }
	if (strlen($min)<2) $min='0'.$min;
	if (strlen($hour)<2) $hour='0'.$hour;
        //$time_2 = $hour.':'.$min;
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
}elseif ( $zman_alot == "72prop" ) {
	//$time_2 = implode(':',getProportionalHours($sunriseStr_t,$sunsetStr_t,-1.2));
	list($hour,$min) = getProportionalHours($sunriseStr_t,$sunsetStr_t,-1.2);
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
}else{
	//$time_2 = date_sunrise($ts_t, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+16.1, $shoffset);
	$time_ar = explode(':',date_sunrise($ts_t, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+16.1, $shoffset));
	$hour = $time_ar[0];
	$min = '00';
	if (isset($time_ar[1])) $min = $time_ar[1];
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
}
print '<li id="alot_hashachar">'.translate_z('Alot_HaShachar',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Alot_HaShachar','hebrew');
print '</li>';
list($hour,$min) = explode(':',$sunriseStr_t);
$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="netz">'.translate_z('Netz',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Netz','hebrew');
'</li>';
list($hour,$min) = explode(':',date_sunrise($ts_t, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+$zman_tallit, $shoffset)); //zman_tallit = 11 by default
$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="tallit">'.__("Earliest","zmanim").' '.translate_z('Tallit',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Tallit','hebrew');
'</li>';
//$time_2=implode(':',getProportionalHours($sunriseStr_t,$sunsetStr_t,3));
list($hour,$min) = getProportionalHours($sunriseStr_t,$sunsetStr_t,3);
if ($zman_shma == "MA") {
//	list($hour,$min) = explode(':',$time_2);
	$min -= 36;
	if ($min <0) {
                $hour--;
                $min += 60;
        }
	if (strlen($min)<2) $min='0'.$min;
        if (strlen($hour)<2) $hour='0'.$hour;
        //$time_2 = $hour.':'.$min;
}
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="latest_shma">'.__("Latest","zmanim").' '.translate_z('Shma',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Shma','hebrew');
'</li>';
//$time_2=implode(':',getProportionalHours($sunriseStr_t,$sunsetStr_t,6));
list($hour,$min) = getProportionalHours($sunriseStr_t,$sunsetStr_t,6);
        $time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="hatzot_hayom">'.translate_z('Hatzot_Hayom',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Hatzot_Hayom','hebrew');
print '</li>';
//$time_2=implode(':',getProportionalHours($sunriseStr_t,$sunsetStr_t,6.5));
list($hour,$min) = getProportionalHours($sunriseStr_t,$sunsetStr_t,6.5);
if ($zman_shma == "MA") {
//        list($hour,$min) = explode(':',$time_2);
        $min += 6;
        if ($min >59) {
                $hour++;
                $min -= 60;
        }
        if (strlen($min)<2) $min='0'.$min;
        if (strlen($hour)<2) $hour='0'.$hour;
        //$time_2 = $hour.':'.$min;
}
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="mincha_gedola">'.translate_z('Mincha_Gedola',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Mincha_Gedola','hebrew');
print '</li>';
//$time_2=implode(':',getProportionalHours($sunriseStr_t,$sunsetStr_t,9.5));
list($hour,$min) = getProportionalHours($sunriseStr_t,$sunsetStr_t,9.5);
if ($zman_shma == "MA") {
        //list($hour,$min) = explode(':',$time_2);
        $min += 42;
        if ($min >59) {
                $hour++;
                $min -= 60;
        }
        if (strlen($min)<2) $min='0'.$min;
        if (strlen($hour)<2) $hour='0'.$hour;
        //$time_2 = $hour.':'.$min;
}
	$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="mincha_ktana">'.translate_z('Mincha_Ktana',$data['accent']).': '.$time_2;
if ($hebrew) print ' :'.translate_z('Mincha_Ktana','hebrew');
print '</li>';
//$time_2=implode(':',getProportionalHours($sunriseStr_t,$sunsetStr_t,10.75));
list($hour,$min) = getProportionalHours($sunriseStr_t,$sunsetStr_t,10.75);
if ($zman_shma == "MA") {
        //list($hour,$min) = explode(':',$time_2);
        $min += 57;
        if ($min >59) {
                $hour++;
                $min -= 60;
        }
        if (strlen($min)<2) $min='0'.$min;
        if (strlen($hour)<2) $hour='0'.$hour;
        //$time_2 = $hour.':'.$min;
}
$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="plag_hamincha">'.__("Plag Hamincha","zmanim").': '.$time_2;
if ($hebrew) print ' :'.translate_z('Plag_Hamincha',"hebrew");
print '</li>';
list($hour,$min) = explode(':',$sunsetStr_t);
$time_2 = date($time_format, $ts_zero+($hour*3600)+($min*60));
print '<li id="shkiah">'.__("Shkiah","zmanim").': '.$time_2; //$sunsetStr_t;
if ($hebrew) print ' :'.translate_z('Shkiah',"hebrew");
print '</li>';
if ($data['zman_shabend'] == "min45")
{
	$zenith = 90+50/60;
	$diff_min=45;
}else{
	$zenith = 98+50/60;
	$diff_min=0;
}

$resultStr_end = date_sunset($ts_zero, SUNFUNCS_RET_STRING, $shlat, $shlong, $zenith, $shoffset);
list($resultHour_end, $resultMin_end) = split(':', $resultStr_end);
$resultMin_end += $diff_min;

while ($resultMin_end >= 60) {
  $resultMin_end -= 60;
  $resultHour_end++;
}

if ($resultHour_end > 23)
{
	$resultHour_end -= 24;
	$ts_zero += (60 * 60 * 24);
}
$ts_end_shabat = mktime($resultHour_end, $resultMin_end, 0 ,date("n",$ts_zero), date("j",$ts_zero),date("Y",$ts_zero));
$time_2 = date($time_format, $ts_end_shabat);
print '<li id="tzet_hokohavim">'.__("Tzet haKochavim","zmanim").': '.$time_2; //$sunsetStr_t;
//if ($hebrew) print ' :'.translate_z('Shkiah',"hebrew");
print '</li>';
echo "</ul>\n";
if (isset($data['zman_hide']) && $data['zman_hide-button']!="one") {
  print '<div class="textright">';
        print '<a href="#" class="hide zmanim-today';
        if (in_array("today",$hide_exclude)) print ' hiddenz';
        print '" rel="zmanim-today">'.__("Show","zmanim").'</a>';
        print '<a href="#" class="hide zmanim-today';
        if (!in_array("today",$hide_exclude)) print ' hiddenz';
        print '" rel="zmanim-today">'.__("Hide","zmanim").'</a>';
   print '</div>';
}?>
</li></ul>

 