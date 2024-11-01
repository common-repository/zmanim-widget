<?
/*
 * User: misha
 * Date: 8/23/11
 * Time: 6:38 AM 
*/
$ts_day = mktime();
if ($data['zmanim_week_hide']!=''):
for ($i=1;$i<=6;$i++)
{
    #current time
    $day_night = 60*60*24;
    $ts_day+=$day_night;
    //print $ts_day.' '.$day_night;


    //timestamp according to UTC offset

    $ts_t_day = ($ts_day +(60 * 60 *$shoffset));

    $ts_zero = mktime(0, 0, 0 ,date("n",$ts_day), date("j",$ts_day),date("Y",$ts_day));

    //Sunset/sunrize calculations
$sunriseStr_t = date_sunrise($ts_t_day, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+50/60, $shoffset);
list($sunriseHour_t, $sunriseMin_t) = split(':', $sunriseStr_t);
$sunsetStr_t = date_sunset($ts_t_day, SUNFUNCS_RET_STRING, $shlat, $shlong, 90+50/60, $shoffset);
list($sunsetHour_t, $sunsetMin_t) = split(':', $sunsetStr_t);

    include ("hdate/day.php");
}
endif;
?>