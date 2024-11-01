<?php

$data = get_option('zmanim_widget');
if (!isset($data['date']))
{
        $data['date'] = get_option('date_format');
        $data['default_date']= true;
}
if (!isset($data['time']))
{
	$data['time'] = get_option('time_format');
	$data['default_time']= true;
}
if (isset($_POST['zman_alot'])):
   foreach ($_POST as $opt=>$val){
    if (preg_match('/zman_.*/',$opt)) $data[$opt] = attribute_escape($val);
    if (preg_match('/zmanim_widget_.*/',$opt))	$data[preg_replace('/zmanim_widget_/','',$opt)] = attribute_escape($val);
  }
//      if (!$_POST['zmanim_widget_dst']) $data['dst'] = "false";
        //sections
	if (!isset($_POST['zman_hide'])) unset($data['zman_hide']);
	if (!isset($_POST['hide-exclude'])) unset($data['hide_exclude']);
	if (isset($_POST['hide-exclude'])) $data['hide_exclude']=implode('|',$_POST['hide-exclude']);
        // zmanim
    if (!isset($_POST['zmanim_hide'])) unset($data['zmanim_hide']);
    if (!isset($_POST['zmanim_week_hide'])) unset($data['zmanim_week_hide']);
	if (!isset($_POST['zmanim-hide-exclude'])) unset($data['zmanim_hide_exclude']);
	if (isset($_POST['zmanim-hide-exclude'])) $data['zmanim_hide_exclude']=implode('|',$_POST['zmanim-hide-exclude']);
        //date
	if (!isset($_POST['default_date'])) unset($data['default_date']);
	if (!isset($_POST['default_time'])) unset($data['default_time']);

        //credits
    if (!isset($_POST['zman_kredits'])) unset($data['zman_kredits']);

    update_option('zmanim_widget', $data);

endif;
?>
</style>
<div class="wrap">
    <div id="icon-options-general" class="icon32">
        <br/>
    </div>
    <div style="width:400px;float:left;">
    <form method="post" action="<?php echo $GLOBALS['PHP_SELF'] . '?page=zmanim-widget/config_admin.php'; ?>">
    <h2>Zmanim widget</h2>

	<h3>Basic options</h3>
	<?php include "config.php";?>
        <h3>Advanced options</h3>
        <label for="zman_alot">Alot haShachar:</label>
                <select name="zman_alot">
                        <?php
                        $alot_ar=array(
				"16"=>"16.1 degrees below horizon",
                                "72"=>"72 minutes before sunrise",
                              	"72prop"=>"72 proportional minutes before sunrise",
				"90"=>"90 minutes before sunrise"
                        );
                        foreach ($alot_ar as $opt=>$param){
                                print '<option value="'.$opt.'" ';
                                if ($opt == $data['zman_alot']) print 'selected="selected"';
                                print '>'.$param.'</option>';
                        }
                        ?>
                </select><br />
	<label for="zman_tallit">Earliest Tallit:</label>
		<select name="zman_tallit">
			<?
			$tallit_ar=array(
				"11"=>"11 degrees below horizon",
				"10.2"=>"10.2 degrees below horizon"
			);
			foreach ($tallit_ar as $opt=>$param){
				print '<option value="'.$opt.'" ';
				if ($opt == $data['zman_tallit']) print 'selected="selected"';
				print '>'.$param.'</option>';
			}
			?>
		</select><br />
	<label for="zman_shma">Praying time:</label>
		<select name="zman_shma">
			<?
			$shma_ar=array(
				"GRO"=>"GR\"O",
				"MA"=>"M\"A"
			);
			foreach ($shma_ar as $opt=>$param){
				print '<option value="'.$opt.'" ';
				if ($opt == $data['zman_shma']) print 'selected="selected"';
				print '>'.$param.'</option>';
			}
			?>
		</select><br />
        <label for="zman_shabend">Shabbat end time:</label>
                <select name="zman_shabend">
                        <?
                        $shma_ar=array(
                                "gr8"=>"8.5 degrees below the horizon",
                                "min45"=>"45 minutes after sunset"
                        );
                        foreach ($shma_ar as $opt=>$param){
                                print '<option value="'.$opt.'" ';
                                if ($opt == $data['zman_shabend']) print 'selected="selected"';
                                print '>'.$param.'</option>';
                        }
                        ?>
                </select><br />
	<h3><?php _e('Date/Time Format configuration',"");?></h3>
	<label for="date">Date:</label>
		<input type="text" name="date" id="date" value="<?
		if (isset($data['default_date'])) print get_option('date_format');
		else print $data['date'];
		?>"
		<?
		if (isset($data['default_date'])) print ' disabled="disabled"';
		?>
		>
		<input type="checkbox" name="default_date" id="default_date" value="on"
		<?php if (isset($data['default_date'])) print ' checked="checked"';?>
		>
		<label for="default_date">Default</label><br />
	<label for="time">time:</label>
		<input type="text" name="time" id="time" value="<?
                if (isset($data['default_time'])) print get_option('time_format');
                else print $data['time'];
                ?>"
                <?
                if (isset($data['default_time'])) print ' disabled="disabled"';
                ?>
                >
                <input type="checkbox" name="default_time" id="default_time" value="on"
                <?php if (isset($data['default_time'])) print ' checked="checked"';?>
                >
                <label for="default_time">Default</label>
	<h3><?php _e('Display configuration',"");?></h3>
	<label for="zman_hide"><?php _e("Hide sections","");?></label>
		<input type="checkbox" name="zman_hide" id="hide" value="on"
		<?php if (isset($data['zman_hide'])) print ' checked="checked"';?>
		>
		<div id="hide-exclude" style="
		<?php if (isset($data['zman_hide'])) print 'display:block;';
		else print 'display:none;';?>"
		>

	<label style="vertical-align:top;"><?php _e("Except following","zmanim");?>:</label>
		<?php $hide_exclude = explode('|',$data['hide_exclude']);
?>
		<select name="hide-exclude[]" multiple="true" size="4" style="height:8em;">
			<option value="today" <?php if (in_array("today",$hide_exclude)) print 'selected="selected"';?>>Zmanim</option>
			<option value="hdate" <?php if (in_array("hdate",$hide_exclude)) print 'selected="selected"';?>>Shabbos</option>
			<option value="weeklytorah" <?php if (in_array("weeklytorah",$hide_exclude)) print 'selected="selected"';?>>Weekly Torah</option>
			<option value="holidays" <?php if (in_array("holidays",$hide_exclude)) print 'selected="selected"';?>>Holidays</option>
		</select>
		<br />
	<label style="vertical-align:top;"><?php _e("Show/Hide button","zmanim");?>:</label>
		<select name="zman_hide-button" >
			<option value="each" <?php if ($data['zman_hide-button'] == 'each') print 'selected="selected"';?>><?php _e("Each separately","zmanim");?></option>
			<option value="one" <?php if ($data['zman_hide-button'] == 'one') print 'selected="selected"';?>><?php _e("One for all","zmanim");?></option>
		</select>
		</div>
<!--ZMANIM hiding -->
	 <label for="zmanim_hide"><?php _e("Hide zmanim","");?></label>
		<input type="checkbox" name="zmanim_hide" id="zmanim_hide" value="on"
		<?php if (isset($data['zmanim_hide'])) print ' checked="checked"';?>
		>
		<div id="zmanim-hide-exclude" style="
		<?php if (isset($data['zmanim_hide'])) print 'display:block;';
		else print 'display:none;';?>"
		>
    <label style="vertical-align:top;"><?php _e("Except following","zmanim");?>:</label>
		<?php $zmanim_hide_exclude = explode('|',$data['zmanim_hide_exclude']);
?>
		<select name="zmanim-hide-exclude[]" multiple="true" size="4" style="height:8em;">
			<option value="alot_hashachar" <?php if (in_array("alot_hashachar",$zmanim_hide_exclude)) print 'selected="selected"';?>>Alot HaShachar</option>
			<option value="netz" <?php if (in_array("netz",$zmanim_hide_exclude)) print 'selected="selected"';?>>Netz</option>
			<option value="tallit" <?php if (in_array("tallit",$zmanim_hide_exclude)) print 'selected="selected"';?>>Latest Tallit</option>
			<option value="latest_shma" <?php if (in_array("latest_shma",$zmanim_hide_exclude)) print 'selected="selected"';?>>Latest Shma</option>
			<option value="hatzot_hayom" <?php if (in_array("hatzot_hayom",$zmanim_hide_exclude)) print 'selected="selected"';?>>Hatzot HaYom</option>
			<option value="mincha_gedola" <?php if (in_array("mincha_gedola",$zmanim_hide_exclude)) print 'selected="selected"';?>>Mincha Gedola</option>
			<option value="mincha_ktana" <?php if (in_array("mincha_ktana",$zmanim_hide_exclude)) print 'selected="selected"';?>>Mincha Ktana</option>
			<option value="plag_hamincha" <?php if (in_array("plag_hamincha",$zmanim_hide_exclude)) print 'selected="selected"';?>>Plag HaMincha</option>
			<option value="shkiah" <?php if (in_array("shkiah",$zmanim_hide_exclude)) print 'selected="selected"';?>>Shkiah</option>
			<option value="tzet_hokohavim" <?php if (in_array("tzet_hokohavim",$zmanim_hide_exclude)) print 'selected="selected"';?>>Tzet haKochavim</option>
		</select>
	</div>
	<br />
	<label for="zmanim_week_hide"><?php _e("Show Week zmanim","");?></label>
		<input type="checkbox" name="zmanim_week_hide" id="zmanim_week_hide" value="on"
		<?php if (isset($data['zmanim_week_hide'])) print ' checked="checked"';?>
		>
	<!--<h3><?php _e('Map configuration',"");?></h3>
	<label for="zman_map_key">Google map API key:</label>
		<input name="zman_map_key" value="<?php print $data['zman_map_key'];?>">
	<p>Get your key for Google map api <a target="_blank" href="http://code.google.com/apis/maps/signup.html">here</a></p>
<p class="submit">-->

    <h3><?php _e('Credits',"");?></h3>
	<label for="zman_kredits">Show "Powered by: KosherDev.com" label</label>
		<input type="checkbox" name="zman_kredits" id="zman_kredits" value="on"
				<?php if (isset($data['zman_kredits'])) print ' checked="checked"';?>
				>

        <input type="submit" name="Submit" class="button-primary" value="Save Changes" id="submitCalendarAdd"/>
        </p>

        </form>
        </div>
        <div style="width: 200px; float:left;">
        Please support further development of Jewish related software.<br />
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form>
        
        </div>


</div>
<script>
jQuery('#default_date').click(function() {
	state=jQuery(this).attr('checked');
	if (!state) jQuery('#date').removeAttr('disabled');
	else {
		jQuery('#date').attr('disabled', true);
		jQuery('#date').val('<?php print get_option('date_format');?>');
	}
});
jQuery('#default_time').click(function() {
        state=jQuery(this).attr('checked');
        if (!state) jQuery('#time').removeAttr('disabled');
        else {
                jQuery('#time').attr('disabled', true);
                jQuery('#time').val('<?php print get_option('time_format');?>');
        }
});
jQuery('input#hide').click(function() {
	jQuery('div#hide-exclude').toggle();
});
jQuery('input#zmanim_hide').click(function() {
	jQuery('div#zmanim-hide-exclude').toggle();
});
</script>
