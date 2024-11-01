<style>
input.zmanim {
	width: 120px;
}
</style>
<script type='text/javascript' src='<?php print WP_PLUGIN_URL ;?>/zmanim-widget/lib/autocomplete/jquery.autocomplete.js'></script>
<script type='text/javascript' src='<?php print WP_PLUGIN_URL ;?>/zmanim-widget/lib/main_admin.js'></script>
<link rel="stylesheet" type="text/css" href="<?php print WP_PLUGIN_URL ;?>/zmanim_widget/lib/autocomplete/jquery.autocomplete.css" />

        <?php $data = get_option('zmanim_widget');?>
	<p><label>Accent<select name="zmanim_widget_accent">
	<?php
		$accent_ar=array("ashkenaz", "sephard","ashkenaz-hebrew","sephard-hebrew");
		foreach ($accent_ar as $accent)
		{
			print '<option value="'.$accent.'"';
			if ($accent == $data['accent']) print 'selected="selected"';
			print '>'.$accent.'</option>';
		}
	?>
	</select></label></p>
Enter your location:<br />
	<p><label>Location name<input name="zmanim_widget_location" class="zmanim" id="location"
type="text" value="<?php echo $data['location']; ?>" /></label></p>
        <p><label>Latitude<input name="zmanim_widget_lat" class="zmanim" id="lat"
type="text" value="<?php echo $data['lat']; ?>" /></label></p>
	<p><label>Longitude<input name="zmanim_widget_long" class="zmanim" id="long"
type="text" value="<?php echo $data['long']; ?>" /></label></p>
	<p><label>UTC Offset<input name="zmanim_widget_offset" class="zmanim"
type="text" value="<?php echo $data['offset']; ?>" /></label></p>
	<p><label>DST<input name="zmanim_widget_dst" class="zmanim"
type="checkbox" value="true" <?php if($data['dst']=='true') echo 'checked="checked"'; ?> /></label></p>
	<?php
   foreach ($_POST as $opt=>$val){
    $data[preg_replace('/zmanim_widget_/','',$opt)] = attribute_escape($val);
  }
//	if (!$_POST['zmanim_widget_dst']) $data['dst'] = "false";
    update_option('zmanim_widget', $data);
?>
