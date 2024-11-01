<?
foreach ($_GET as $i=>$v)
{
	$$i=addslashes($v);
}

$url="http://ws.geonames.org/search?q=$q&maxRows=$limit";
$doc = new DOMDocument();
if($doc->load($url)){
        $value='';
        $array_element = new DOMDocument();
   if (!$array_element=$doc->GetElementsByTagName("geonames")) die("Error opening xml file");
   if (!$hawbid_dom = $array_element->item(0))
   {
        print 'no_data';
   }else {
        $general_dom = $hawbid_dom->getElementsByTagName('geoname');
        $k=0; 
        $update_ar=array("name",
                        "lat",
                        "lng",
                        "countryName",
                        "countryCode"
                );
        foreach ($general_dom as $a)
        {
                $update="";
                foreach ( $update_ar as $value)
                {
                        $update_dom=$general_dom->item($k)->getElementsByTagName($value);
                        $update.=$update_dom->item(0)->nodeValue."|";
                }
                print $update."\n";
                $k++;
        }
   }
}

?>
