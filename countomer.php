<?
$JdPesah=jewishtojd(8,15,$jewishYear_t);
$UPesach=jdtounix($JdPesah);

$PDay = date("z",$UPesach);
$TDay = date("z",$ts_t);
$Omer = $TDay - $PDay;

if ($Omer > 1) 
if ($Omer < 49 ){

print '<ul><li><b>';
_e('Count of Omer','zmanim');
if ($hebrew) print ' '.translate_z('Count_Omer',"hebrew");
print "</b></li>";
echo "<li><ul><li>";
print __("Day of Omer #", "zmanim").$Omer;
print "</li></ul></li></ul>";

}
?>
