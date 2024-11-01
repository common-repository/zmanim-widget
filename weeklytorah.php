<?
$shabbat_day = date("d-M-Y", $ts_end);
if ($data['weekly_portion_date'] != $shabbat_day)
{
	$data=get_option('zmanim_widget');
	$fn=dirname(__FILE__) . "/dictionary/fullkriyah.csv";
	$file_ar=file($fn);
	$portion_ar=preg_grep("/".$shabbat_day.".*Haftara.*/",file($fn));
	$weekly_portion = explode(',', reset($portion_ar));
	$data['weekly_portion_date']=$shabbat_day;
	$data['weekly_portion_name']=str_replace('"','',$weekly_portion[1]);
	$data['weekly_havtara_name'] = str_replace('"','',$weekly_portion[3]);
	update_option('zmanim_widget', $data);
}
$weekly_portion_name = $data['weekly_portion_name'];
$weekly_havtara_name = $data['weekly_havtara_name'];

print '<ul class="zmanim-inner-section"><li><b>';
_e('Weekly Torah reading','zmanim');
if ($hebrew) print ' '.translate_z('Weekly_Torah',"hebrew");
print "</b></li>";
echo "<li><ul id=\"zmanim-weeklytorah\"><li>";
if ($weekly_portion_name != '') {
	print __('Chapter','zmanim').": ".$weekly_portion_name.'</li><li>';
	print __('Haftara','zmanim').": ".$weekly_havtara_name;
}else{
	_e('This week there is a different order of reading Torah','zmanim');
}
print "</li></ul>";
if (isset($data['zman_hide']) && $data['zman_hide-button']!="one") {
  print '<div class="textright">';
	print '<a href="#" class="hide zmanim-weeklytorah';
	if (in_array("weeklytorah",$hide_exclude)) print ' hiddenz';
	print '" rel="zmanim-weeklytorah">'.__("Show","zmanim").'</a>';
	print '<a href="#" class="hide zmanim-weeklytorah';
	if (!in_array("weeklytorah",$hide_exclude)) print ' hiddenz';
	print '" rel="zmanim-weeklytorah">'.__("Hide","zmanim").'</a>';
   print '</div>';
}?>
</li></ul>


