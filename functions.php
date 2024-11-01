<?
function getProportionalHours($startOfDay, $endOfDay, $proportionalHour) {
  list($startOfDayHour, $startOfDayMin) = split(':', $startOfDay);
  list($endOfDayHour, $endOfDayMin) = split(':', $endOfDay);
  $startOfDayInMinutesAfterMidnight = $startOfDayHour * 60 + $startOfDayMin;
  $endOfDayInMinutesAfterMidnight = $endOfDayHour * 60 + $endOfDayMin;
  $resultInMinutesAfterMidnight = (int) ($startOfDayInMinutesAfterMidnight +
        (($endOfDayInMinutesAfterMidnight-$startOfDayInMinutesAfterMidnight) * 
         $proportionalHour)/12);
  $Min=$resultInMinutesAfterMidnight%60;
  if ( strlen($Min) < 2 && $Min == 0) $Min=$Min.'0';
  if ( strlen($Min) < 2 && $Min > 0) $Min='0'.$Min;
  return array((int) ($resultInMinutesAfterMidnight/60),
               $Min);
}

function translate_z($word, $lang="ashkenaz")
{
	if ($lang=='') $lang="ashkenaz";
	$translate= Array();
        if (!preg_match('/-hebrew/',$lang)) include "dictionary/".$lang.".php";
        $word_trans='';
        if (array_key_exists($word, $translate)) $word_trans=$translate[$word];
        if ($word_trans=='') {
		include "dictionary/ashkenaz.php";
		if (array_key_exists($word, $translate)) $word_trans=$translate[$word];
	}
        if ($word_trans=='') $word_trans=$word;
        return preg_replace("/[\n\r]/","",$word_trans);
}

function dstWindow ($ts, $location="None")
{
$in_dst="false";
$week_day=date("w",$ts);
$sunday_ts=$ts-(60 * 60 * 24*($week_day));
$month=date("m",$ts);
$sunday_month=date("m",$sunday_ts);
$sunday=date("j", $sunday_ts);
$hour=date("H",$sunday_ts);
$day=date("j",$ts);
$year=date("Y",$ts);
if (preg_match("/.*Israel.*/i",$location))
{
    $jdCurrent_t = gregoriantojd($month, $day, $year);
    $jewishDate_t = jdtojewish($jdCurrent_t);
    list($jewishMonth_t, $jewishDay_t, $jewishYear_t) = split('/', $jewishDate_t);
        //print $jewishDate_t;
    if (($jewishMonth_t >=1) && ($jewishMonth_t <=7))
    {
        $in_dst="true";
        if ($jewishMonth_t >=1)
        {
            $in_dst="false";
            if (($jewishMonth_t == 1) && ($jewishDay_t <= 10))
            {
                $jdYomKipur = jewishtojd(1,10,$jewishYear_t);
                $gregrianYomKipur= jdtogregorian($jdYomKipur);
                list($grMonth,$grDay,$grYear) = split('/', $gregrianYomKipur);
                $YomKipur_ts = mktime(0,0,0,$grMonth, $grDay, $grYear);
                $week_dayYomKipur=date("w",$YomKipur_ts);
                $sundayBeforeYomKipur_ts = $YomKipur_ts-(60 * 60 * 24*($week_dayYomKipur));

                if ($ts < $sundayBeforeYomKipur_ts) $in_dst="true";
            }
        }
        if ($jewishMonth_t ==7)
        {
            if ($jewishDay_t <= 15 ) $in_dst="false";
        }
    }
}else{
    if( ($sunday_month>=3) && ($sunday_month<=10) ) // IS CURRENT DATE INSIDE OF MARCH->OCT WINDOW?
    {
        $in_dst="true";
        if($sunday_month=="3") //IS IT MARCH?
	    {
		    if ($sunday>=25 ) //IS IT THE LAST SUN OF THE MONTH?
            {
                $in_dst="true";
            }else{
			    $in_dst="false";
		    }
	    }
        elseif($sunday_month=="10") //IS IT OCT?
        {
            if ($sunday>=25 ) //IS IT THE LAST SUN OF THE MONTH?
            {
                    $in_dst="false";
            }else{
			        $in_dst="true";
		    }
        }
    }
}
return $in_dst;

}

$convert_format = array (
//day
"d"=>"%d", //01 to 31
"D"=>"%a", //Mon through Sun
"j"=>"%e", //1 to 31
"l"=>"%A", //Sunday through Saturday
//"L"=>"", //sunday through saturday
"N"=>"%u", //1 (for Monday) through 7 (for Sunday)
"S"=>"", //st, nd, rd or th. Works well with j
"w"=>"%w", //0 (for Sunday) through 6 (for Saturday)
"z"=>"%j", //0 through 365
//week
"W"=>"%W", //Example: 42 (the 42nd week in the year)
//Month
"F"=>"%B", //January through December
"m"=>"%m", // 	01 through 12
"M"=>"%b", //Jan through Dec
"n"=>"%m", //1 through 12
"t"=>"", //28 through 31
//Year
"L"=>"", //1 if it is a leap year, 0 otherwise.
"o"=>"%Y", //Examples: 1999 or 2003
"Y"=>"%Y", //Examples: 1999 or 2003
"y"=>"%y" //Examples: 99 or 03
);
?>
