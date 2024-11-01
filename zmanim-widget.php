<?php
/*
 Plugin Name: Zmanim widget 
 Plugin URI: http://kosherdev.com/category/zmanim-widget/
 Description: Allows to show zmanim, upcoming hollidays and current Torah chapter on your Wordpress site.
 Version: 1.14.3
 Author: Misha Beshkin
 Author URI: http://misha.beshkin.lv/
 */

/*
 This file is part of zmanim-widget.

    zmanim-widget is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    zmanim-widget is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with zmanim-widget.  If not, see <http://www.gnu.org/licenses/>.
 */
?>
<?php
//error_reporting(E_ALL);

add_action( 'init', 'check_widget' );
add_action("widgets_init", array('zmanim_widget', 'register'));
//add_action("widgets_init", array('zmanim_widget_map', 'register'));

class zmanim_widget {
  function control(){
	include "config.php";
  }
  function options_page () {
	add_options_page('Zmanim widget Advanced Options', 'Zmanim Widget', 8,dirname(__FILE__)."/config_admin.php" );
  }
  function widget($args){
    echo $args['before_widget'];
	$data=get_option('zmanim_widget');
    echo $args['before_title'] . __('Luach for ','zmanim') .$data['location']. $args['after_title'];
	include "message.php";
    echo $args['after_widget'];
  }
  function register(){
   // wp_enqueue_script ("zmanim_autocomplete", WP_PLUGIN_URL . "/zmanim-widget/lib/autocomplete/jquery.autocomplete.js", array("jquery"));
    wp_enqueue_script ("zmanim_main", WP_PLUGIN_URL . "/zmanim-widget/lib/main.js", array("jquery"));
    register_sidebar_widget('Zmanim widget', array('zmanim_widget', 'widget'));
    register_widget_control('Zmanim widget', array('zmanim_widget', 'control'));
    add_action('admin_menu', array('zmanim_widget', 'options_page'));
	
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
                                $moFile = dirname(__FILE__) . "/localization/zmanim-" . $currentLocale . ".mo";
                                if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('zmanim', $moFile);
                        }
  }
}

/*class zmanim_widget_map {
  function control(){
        include "config.php";
  }
  function widget($args){
    echo $args['before_widget'];
        $data=get_option('zmanim_widget');
    echo $args['before_title'] . __('Zmanim map ','zmanim') .$data['location']. $args['after_title'];
        include "map.php";
    echo $args['after_widget'];
  }
  function nokey($args){
    echo $args['before_widget'];
    echo $args['before_title'] . __('Zmanim map ','zmanim') .$data['location']. $args['after_title'];
	print '<p>Get your key for Google map api <a target="_blank" href="http://code.google.com/apis/maps/signup.html">here</a></p>';
    echo $args['after_widget'];
  }
  function register(){
	$data=get_option('zmanim_widget');
	$key=$data['zman_map_key'];
	if ($key!=''){
		register_sidebar_widget('Zmanim map widget', array('zmanim_widget_map', 'widget'));
	}else{
		register_sidebar_widget('Zmanim map widget', array('zmanim_widget_map', 'nokey'));
	}
	register_widget_control('Zmanim map widget', array('zmanim_widget_map', 'control'));
  }
}*/

function check_widget() {
    if( is_active_widget( '', '', 'zmanim-map-widget' ) ) { // check if search widget is used
        $data=get_option('zmanim_widget');
        $key=$data['zman_map_key'];
        wp_enqueue_script ("google","http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=".$key);
        wp_enqueue_script ("kosherjava_zmanim",WP_PLUGIN_URL . "/zmanim-widget/lib/zmanim.js",array("jquery"));
        wp_enqueue_script ("kosherjava_latlng",WP_PLUGIN_URL . "/zmanim-widget/lib/glatlng.js",array("jquery"));
    }
}



?>
