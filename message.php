<?
$shlat='';
$shlong = '';
$shoffset = '';
$hebrew= false;
include "functions.php";
$data=get_option('zmanim_widget');
//style 
?>
<style>
div.textright a {
	font-size: 10px;
}
div.textright {
	text-align:right;
}
<?php if (isset($data['zman_hide'])) { print '
ul.zmanim-inner-section ul {
	display: none;
}
ul.zmanim-inner-section a.hiddenz {
	display:none;
}

div.textright a#zman_hideall {
	display:none;
}
';
$hide_exclude = explode('|',$data['hide_exclude']);
foreach ($hide_exclude as $exclude)
{
	print 'ul.zmanim-inner-section ul#zmanim-'.$exclude.' {display:block;}'."\n";
}

}

?>
    <?php if (isset($data['zmanim_hide'])) {
print 'ul#zmanim-today li {display:none;}'."\n";
$zmanim_hide_exclude = explode('|',$data['zmanim_hide_exclude']);
foreach ($zmanim_hide_exclude as $exclude)
{
	print 'ul#zmanim-today li#'.$exclude.' {display:block;}'."\n";
}

}

?>
</style>
<?
include_once("hdate/holidays.php");
if (!function_exists("getHebrewJewishDates")) include_once("hdate/hebrewdate.inc");
$ipi = getenv("REMOTE_ADDR");
if(isset($data['default_date'])) $date_format = get_option("date_format");
else $date_format =$data['date'];
$word = str_split($date_format);
$date_format_local ='';
foreach ($word as $letter)
{
	$new_letter = $letter;
	if (array_key_exists($letter, $convert_format)) $new_letter=$convert_format[$letter];
	$date_format_local .= $new_letter;
}
if(isset($data['default_time'])) $time_format = get_option("time_format");
else $time_format = $data['time'];
/*foreach (locateIp($ipi) as $opt=>$val)
{
        $$opt=$val;
}*/
//if ($shlat=='' || $shlong == '' || $shoffset == ''):
        if(isset($data['lat'])) $shlat=$data['lat'];
        else $shlat=59+26/60;
        if(isset($data['long'])) $shlong=$data['long'];
        else $shlong=24+44/60;
        if(isset($data['offset'])) $shoffset=(int)$data['offset'];
        else $shoffset=2;
	if(isset($data['dst'])) $dst=$data['dst'];
        else $dst='';
	$zman_tallit = 11;
	foreach ($data as $o=>$v)
	{
		if (preg_match('/zman_/',$o)) $$o=$v;
	}
	if (preg_match('/-hebrew/',$data['accent'])) {
		$hebrew=true;
		$data['accent']=preg_replace('/-hebrew/','',$data['accent']);
	}
//endif;

date_default_timezone_set('UTC');
#current time
$ts = mktime();
$ts_zero = mktime(0, 0, 0 ,date("n",$ts), date("j",$ts),date("Y",$ts));

//timestamp according to UTC offset

if (dstWindow($ts, $data['location'])=="true" ){
        if ($data['dst'] == "true")
        $shoffset++;
}
$ts_t = ($ts +(60 * 60 *$shoffset));

//Sunset/sunrize calculations
$sunriseStr_t = date_sunrise($ts_t, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+50/60, $shoffset);
list($sunriseHour_t, $sunriseMin_t) = split(':', $sunriseStr_t);
$sunsetStr_t = date_sunset($ts_t, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+50/60, $shoffset);
list($sunsetHour_t, $sunsetMin_t) = split(':', $sunsetStr_t);

//verify if it is new jewish day or not. In case new - add 1 day.
$time_s = "$sunsetHour_t:$sunsetMin_t";
$time_t = date("H:i", $ts_t);
if ($time_t > $time_s) $ts_t = ($ts_t + 60*60*24);

//includes
include ("hdate/today.php");
include ("week_zmanin.php");
include ("hdate/hdate.php");
include ("weeklytorah.php");
include ("hdate/hol.php");
include ("countomer.php");

if (isset($data['zman_hide']) && $data['zman_hide-button']=="one") {
  print '<div class="textright">';
        print '<a href="#" id="zman_showall" class="show_hide_all ';
        print '" >'.__("Show","zmanim").'</a>';
        print '<a href="#" id="zman_hideall" class="show_hide_all ';
        print '" >'.__("Hide","zmanim").'</a>';
   print '</div>';
}
if ($data['zman_kredits'] == 'on'): ?>
<ul><li>Powered by <a href="http://kosherdev.com">KosherDev.com</a></li></ul>
<?php endif;?>
