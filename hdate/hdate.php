<?
$weekday = date("N", $ts);
$leftshabat = (5 - $weekday);
$endshabat = (6 - $weekday);
if ($weekday == 7)
{
	$leftshabat = 5;
	$endshabat = 6;
}

$ts_start = ($ts + (60 * 60 * 24 * $leftshabat)+(60 * 60 *$shoffset));
$ts_end = ($ts + (60 * 60 * 24 * $endshabat)+(60 * 60 *$shoffset));

$tslocal = date("Z", $ts_start); 
$timediff_end=0;
/*if (dstWindow($ts_start)=="true" && $data['dst']=='true'){
        $timediff_end++;
}*/

/*COUNTING CANDLE LIGHT TIME ON FRIDAY NIGHT*/

$resultStr = date_sunset($ts_start, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+50/60, $shoffset);

list($resultHour, $resultMin) = split(':', $resultStr);
$resultHour += $timediff_end;
$resultMin -= 18;

if ($resultMin < 0) {
  $resultMin += 60;
  $resultHour--;
}

/*COUNTING END OF SHABBAT*/

$tslocal = date("Z", $ts_end);
$timediff_end=1;
if (dstWindow($ts_end)=="true" && $data['dst']=='true'){
//        $timediff_end++;
}

if ($data['zman_shabend'] == "min45") 
{
	$zenith = 90+50/60;
	$diff_min=45;
}else{
	$zenith = 98+50/60;
	$diff_min=0;
}

$resultStr_end = date_sunset($ts_end, SUNFUNCS_RET_STRING, $shlat, $shlong, $zenith, $shoffset);
list($resultHour_end, $resultMin_end) = split(':', $resultStr_end);
//$resultHour_end += $timediff_end;
$resultMin_end += $diff_min;

while ($resultMin_end >= 60) {
  $resultMin_end -= 60;
  $resultHour_end++;
}

if ($resultHour_end > 23)
{
	$resultHour_end -= 24;
	$ts_end += (60 * 60 * 24);
}

$sh_date = strftime($date_format_local, $ts_start); //date("d M", $ts_start);
$sh_date_end = strftime($date_format_local, $ts_end);//date("d M", $ts_end);
//$sh_date = date($date_format,$ts_start);
//$sh_date_end = date($date_format,$ts_end);

$currentHour = date("G", ($ts+(60 * 60 *$shoffset)));
$currentMin = date("i", ($ts+(60 * 60 *$shoffset)));
//$currentHour =23;
$leftHour=($resultHour - $currentHour);
$leftMin=($resultMin - $currentMin);
if ($leftMin < 0) {$leftMin += 60;
$leftHour --;}
if (strlen($leftMin) <2) $leftMin="0$leftMin";

if ($leftshabat > 1) $nday=__("It will come in","zmanim").' '.$leftshabat.' '.__("days","zmanim");
if ($leftshabat == 5) $nday=__("It will come in","zmanim").' '.__("5 days","zmanim");
if ($leftshabat == 1) $nday=__("It will come","zmanim")." ".__("tomorrow","zmanim");
if ($leftshabat <1) $nday=__("It will come in","zmanim")." $leftHour:$leftMin"; 

if ( strlen($resultMin) < 2 && $resultMin == 0) $resultMin=$resultMin.'0';
if ( strlen($resultMin) < 2 && $resultMin > 0) $resultMin='0'.$resultMin; 
if ( strlen($resultMin_end) < 2 && $resultMin_end == 0) $resultMin_end=$resultMin_end.'0';
if ( strlen($resultMin_end) < 2 && $resultMin_end > 0) $resultMin_end='0'.$resultMin_end;

if ($weekday == 6 && "$currentHour$currentMin" < "$resultHour_end$resultMin_end") $nday=translate_z('Shabbat',$data['accent']);
elseif ($weekday == 5 && "$currentHour$currentMin" > "$resultHour$resultMin") $nday=translate_z('Shabbat',$data['accent']);
if ($weekday > 6 ) $nday=__("It will come in","zmanim").' 6 '.__("days","zmanim");

print '<ul class="zmanim-inner-section"><li><b>'.translate_z('Shabbat',$data['accent']);
if ($hebrew) print ' '.translate_z('Shabbat',"hebrew");
print "</b></li>";
echo "<li>
<ul id=\"zmanim-hdate\">
<li>$nday.</li>";
$ts_start_shabat = mktime($resultHour, $resultMin, 0 ,date("n",$ts_start), date("j",$ts_start),date("Y",$ts_start));
print "<li>".__("Candle light","zmanim")." : $sh_date ".date($time_format,$ts_start_shabat); //$resultHour:$resultMin";
if ($hebrew) print ' :'.translate_z('Candle_light',"hebrew");
print "</li>";
$ts_end_shabat = mktime($resultHour_end, $resultMin_end, 0 ,date("n",$ts_end), date("j",$ts_end),date("Y",$ts_end));
print "<li>".translate_z('Shabbat',$data['accent'])." ".__("ends","zmanim").": $sh_date_end ".date($time_format,$ts_end_shabat); //$resultHour_end:$resultMin_end";
if ($hebrew) print ' :'.translate_z('Shabat_ends',"hebrew");
echo "</li></ul>";?>
<?
if (isset($data['zman_hide'])  && $data['zman_hide-button']!="one") {
  print '<div class="textright">';
        print '<a href="#" class="hide zmanim-hdate';
        if (in_array("hdate",$hide_exclude)) print ' hiddenz';
        print '" rel="zmanim-hdate">'.__("Show","zmanim").'</a>';
        print '<a href="#" class="hide zmanim-hdate';
        if (!in_array("hdate",$hide_exclude)) print ' hiddenz';
        print '" rel="zmanim-hdate">'.__("Hide","zmanim").'</a>';
   print '</div>';
}?>
</li></ul>
