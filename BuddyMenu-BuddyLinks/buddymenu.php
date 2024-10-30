<?php
/*
Plugin Name: BuddyMenu
Plugin URI: http://journalxtra.com/websiteadvice/wordpress/use-buddypress-dynamic-links-in-your-network-with-buddymenu-buddylinks-5316/
Description: BuddyPress widget menu. Does three things really well: BuddyPress menu widget, BuddyPress menu shortcode and BuddyPress dynamic link shortcode. Put a BuddyPress menu or user link in your sidebar, post, page, widget, footer or anywhere else. Needs BuddyPress to work. There is no need to network activate in WP Multisite. Displays when a visitor is logged in.
Version: 1.5.7
Author: Lee Hodson
Author URI: http://vizred.com/

---------------------------------------------------------------------------

Copyright 2013  Lee Hodson  (email : leehodson@vizred.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

---------------------------------------------------------------------------

*/
?>
<?php
function buddymenu_style_fn()  
{  
    wp_register_style( 'buddymenu-style', plugins_url( '/buddymenu-style.css', __FILE__ ), array(), '20120208', 'all' );  
    wp_enqueue_style( 'buddymenu-style' );
}

add_action( 'wp_enqueue_scripts', 'buddymenu_style_fn' );

class buddymenu_widget_class extends WP_Widget {
	function buddymenu_widget_class() {
	 //Load Language
	 load_plugin_textdomain( 'buddymenu-plugin-handle', false, dirname(plugin_basename(__FILE__)) .  '/lang' );
	 $widget_ops = array('description' => __('Shows BuddyPress Menus.', 'buddymenu-plugin-handle') );
	 //Create widget
	 $this->WP_Widget('buddymenu', __('BuddyMenu', 'buddymenu-plugin-handle'), $widget_ops);
	}


// Widget output

  function widget($args, $instance) {

	 	// Check visitor is logged in

	 	if ( is_user_logged_in() ) {

	 		extract($args, EXTR_SKIP);
			echo $before_widget;
			$title = empty($instance['title']) ? __('BuddyMenu', 'buddymenu-plugin-handle') : apply_filters('widget_title', $instance['title']);
			$parameters = array(
				'title' => esc_attr($instance['title']), // Text
				'buddyactivity' => esc_attr($instance['buddyactivity']), // Text
				'buddyforums' => esc_attr($instance['buddyforums']), // Text
				'buddyfriends' => esc_attr($instance['buddyfriends']), // Text
				'buddymessages' => esc_attr($instance['buddymessages']), // Text
				'buddyprofile' => esc_attr($instance['buddyprofile']), // Text
				'buddyprofileedit' => esc_attr($instance['buddyprofileedit']), // Text
				'buddychangeavatar' => esc_attr($instance['buddychangeavatar']), // Text
				'buddysettings' => esc_attr($instance['buddysettings']), // Text
				'horiz' => esc_attr($instance['horiz']), // Test Selector
				'showtitle' => (bool) $instance['showtitle'], // Boolean
				'showactivity' => (bool) $instance['showactivity'], // Boolean
				'showforums' => (bool) $instance['showforums'], // Boolean
				'showfriends' => (bool) $instance['showfriends'], // Boolean
				'showmessages' => (bool) $instance['showmessages'], // Boolean
				'showprofile' => (bool) $instance['showprofile'], // Boolean
				'showprofileedit' => (bool) $instance['showprofileedit'], // Boolean
				'showchangeavatar' => (bool) $instance['showchangeavatar'], // Boolean
				'showsettings' => (bool) $instance['showsettings'] // Boolean
			);

			$showtitle = (bool) $instance['showtitle'];
			if ( $showtitle == '0' ) { $title=''; }

			if ( !empty( $title ) ) {
	 		echo $before_title . $title . $after_title;
			};

			// Call function that does the work

				buddymenu($parameters);

			// End Work

			echo $after_widget;

	 	}
	 	else { }
	 	// End if

  }

// End of widget output

	
//Update widget options
  function update($new_instance, $old_instance) {

		$instance = $old_instance;
		//get old variables
		$instance['title'] = esc_attr($new_instance['title']);
		$instance['buddyactivity'] = esc_attr($new_instance['buddyactivity']);
		$instance['buddyforums'] = esc_attr($new_instance['buddyforums']);
		$instance['buddyfriends'] = esc_attr($new_instance['buddyfriends']);
		$instance['buddymessages'] = esc_attr($new_instance['buddymessages']);
		$instance['buddyprofile'] = esc_attr($new_instance['buddyprofile']);
		$instance['buddyprofileedit'] = esc_attr($new_instance['buddyprofileedit']);
		$instance['buddychangeavatar'] = esc_attr($new_instance['buddychangeavatar']);
		$instance['buddysettings'] = esc_attr($new_instance['buddysettings']);
		$instance['horiz'] = esc_attr($new_instance['horiz']);
		$instance['showtitle'] = $new_instance['showtitle'] ? 1 : 0;
		$instance['showactivity'] = $new_instance['showactivity'] ? 1 : 0;
		$instance['showforums'] = $new_instance['showforums'] ? 1 : 0;
		$instance['showfriends'] = $new_instance['showfriends'] ? 1 : 0;
		$instance['showmessages'] = $new_instance['showmessages'] ? 1 : 0;
		$instance['showprofile'] = $new_instance['showprofile'] ? 1 : 0;
		$instance['showprofileedit'] = $new_instance['showprofileedit'] ? 1 : 0;
		$instance['showchangeavatar'] = $new_instance['showchangeavatar'] ? 1 : 0;
		$instance['showsettings'] = $new_instance['showsettings'] ? 1 : 0;
		return $instance;
  } //end of update
	
//Widget options form
  function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('BuddyMenu','buddymenu-plugin-handle'), 'title' => 'BuddyMenu', 'buddyactivity' => 'Activity',  'buddyforums' => 'Forums', 'buddyfriends' => 'Friends', 'buddymessages'=>'Messages', 'buddyprofile' => 'Profile', 'buddyprofileedit' => 'Edit Profile', 'buddychangeavatar'=>'Change Avatar', 'buddysettings'=>'Settings', 'horiz'=>'Vertical', 'showtitle'=>'0', 'showactivity'=>'0', 'showforums'=>'0', 'showfriends'=>'0', 'showmessages'=>'0', 'showprofile'=>'0', 'showprofileedit'=>'0', 'showchangeavatar'=>'0', 'showsettings'=>'0' ) );
	
		$title = esc_attr($instance['title']);
		$budact = esc_attr($instance['buddyactivity']);
		$budfor = esc_attr($instance['buddyforums']);
		$budfriends = esc_attr($instance['buddyfriends']);
		$budmes = esc_attr($instance['buddymessages']);
		$budprof = esc_attr($instance['buddyprofile']);
		$budprofe = esc_attr($instance['buddyprofileedit']);
		$budchav = esc_attr($instance['buddychangeavatar']);
		$budset = esc_attr($instance['buddysettings']);
		$horiz = esc_attr($instance['horiz']);
		$showtitle = (bool) $instance['showtitle'];
		$showactivity = (bool) $instance['showactivity'];
		$showfor = (bool) $instance['showforums'];
		$showfriends = (bool) $instance['showfriends'];
		$showmessages = (bool) $instance['showmessages'];
		$showprofile = (bool) $instance['showprofile'];
		$showprofileedit = (bool) $instance['showprofileedit'];
		$showchangeavatar = (bool) $instance['showchangeavatar'];
		$showsettings = (bool) $instance['showsettings'];

		?>

		<p>
		   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('<strong>Widget Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		   </label>
		<p>
			<label for="<?php echo $this->get_field_id('showtitle'); ?>"><?php _e('Show widget title?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showtitle'); ?>" name="<?php echo $this->get_field_name('showtitle'); ?>"<?php checked( $showtitle ); ?> />
		</p>		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddyactivity'); ?>"><?php _e('<strong>Activity Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddyactivity'); ?>" name="<?php echo $this->get_field_name('buddyactivity'); ?>" type="text" value="<?php echo $budact; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showactivity'); ?>"><?php _e('Show activity link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showactivity'); ?>" name="<?php echo $this->get_field_name('showactivity'); ?>"<?php checked( $showactivity ); ?> />
		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddyforums'); ?>"><?php _e('<strong>Forums Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddyforums'); ?>" name="<?php echo $this->get_field_name('buddyforums'); ?>" type="text" value="<?php echo $budfor; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showforums'); ?>"><?php _e('Show forums link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showforums'); ?>" name="<?php echo $this->get_field_name('showforums'); ?>"<?php checked( $showfor ); ?> />
		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddyfriends'); ?>"><?php _e('<strong>Friends Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddyfriends'); ?>" name="<?php echo $this->get_field_name('buddyfriends'); ?>" type="text" value="<?php echo $budfriends; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showfriends'); ?>"><?php _e('Show friends link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showfriends'); ?>" name="<?php echo $this->get_field_name('showfriends'); ?>"<?php checked( $showfriends ); ?> />
		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddymessages'); ?>"><?php _e('<strong>Messages Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddymessages'); ?>" name="<?php echo $this->get_field_name('buddymessages'); ?>" type="text" value="<?php echo $budmes; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showmessages'); ?>"><?php _e('Show messagebox link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showmessages'); ?>" name="<?php echo $this->get_field_name('showmessages'); ?>"<?php checked( $showmessages ); ?> />
		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddyprofile'); ?>"><?php _e('<strong>Profile Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddyprofile'); ?>" name="<?php echo $this->get_field_name('buddyprofile'); ?>" type="text" value="<?php echo $budprof; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showprofile'); ?>"><?php _e('Show profile link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showprofile'); ?>" name="<?php echo $this->get_field_name('showprofile'); ?>"<?php checked( $showprofile ); ?> />
		</p>


		<p>
		   <label for="<?php echo $this->get_field_id('buddyprofileedit'); ?>"><?php _e('<strong>Edit Profile Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddyprofileedit'); ?>" name="<?php echo $this->get_field_name('buddyprofileedit'); ?>" type="text" value="<?php echo $budprofe; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showprofileedit'); ?>"><?php _e('Show edit profile link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showprofileedit'); ?>" name="<?php echo $this->get_field_name('showprofileedit'); ?>"<?php checked( $showprofileedit ); ?> />
		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddychangeavatar'); ?>"><?php _e('<strong>Change Avatar Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddychangeavatar'); ?>" name="<?php echo $this->get_field_name('buddychangeavatar'); ?>" type="text" value="<?php echo $budchav; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showchangeavatar'); ?>"><?php _e('Show change avatar link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showchangeavatar'); ?>" name="<?php echo $this->get_field_name('showchangeavatar'); ?>"<?php checked( $showchangeavatar ); ?> />
		</p>



		<p>
		   <label for="<?php echo $this->get_field_id('buddysettings'); ?>"><?php _e('<strong>Settings Title:</strong>');?><br />
			<input class="widefat" id="<?php echo $this->get_field_id('buddysettings'); ?>" name="<?php echo $this->get_field_name('buddysettings'); ?>" type="text" value="<?php echo $budset; ?>" />
		   </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('showsettings'); ?>"><?php _e('Show settings link?', 'buddymenu-plugin-handle');?></label>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showsettings'); ?>" name="<?php echo $this->get_field_name('showsettings'); ?>"<?php checked( $showsettings ); ?> />
		</p>



		<p>
		<label for="<?php echo $this->get_field_id('horiz'); ?>"><?php _e('Horizontal or vertical layout?', 'buddymenu-plugin-handle');?></label>
		<select class="select" id="<?php echo $this->get_field_id('horiz'); ?>" name="<?php echo $this->get_field_name('horiz'); ?>" selected="<?php echo $horiz; ?>">
		  <option value="<?php echo $horiz ?>" selected="<?php echo $horiz; ?>"><?php echo $horiz; ?></option>
		  <option value="Horizontal">Horizontal</option>
		  <option value="Vertical">Vertical</option>
		</select>
		</p>
   <?php
  } //end of form
}

add_action( 'widgets_init', create_function('', 'return register_widget("buddymenu_widget_class");') );
//Register Widget


// Code for the widget's output
 function buddymenu($args = '') {
  global $wpdb;
	$defaults = array( 'title' => 'BuddyMenu', 'buddyactivity' => 'Activity', 'buddyforums' => 'Forums', 'buddyfriends' => 'Friends', 'buddymessages'=>'Messages', 'buddyprofile' => 'Profile', 'buddyprofileedit' => 'Edit Profile', 'buddychangeavatar'=>'Change Avatar', 'buddysettings'=>'Settings', 'horiz'=>'Vertical', 'showtitle'=>'0', 'showactivity'=>'0', 'showforums'=>'0', 'showfriends'=>'0', 'showmessages'=>'0', 'showprofile'=>'0', 'showprofileedit'=>'0', 'showchangeavatar'=>'0', 'showsettings'=>'0' );
	$args = wp_parse_args( $args, $defaults );
	extract($args);
	
		$budact = $buddyactivity;
		$budfor = $buddyforums;
		$budfriends = $buddyfriends;
		$budmes = $buddymessages;
		$budprof = $buddyprofile;
		$budprofe = $buddyprofileedit;
		$budchav = $buddychangeavatar;
		$budset = $buddysettings;
		$layout = $horiz;
		$stitle = (bool) $showtitle;
		$sactivity = (bool) $showactivity;
		$sforums = (bool) $showforums;
		$sfriends = (bool) $showfriends;
		$smessages = (bool) $showmessages;
		$sprofile = (bool) $showprofile;
		$sprofileedit = (bool) $showprofileedit;
		$schangeavatar = (bool) $showchangeavatar;
		$ssettings = (bool) $showsettings;
?>

 	    
 	    <div class="buddymenu <?php if ( $layout == 'Vertical') { echo 'bmvertical'; } else { echo 'bmhorizontal'; } ?>">
	    <ul>
		<?php if ($sactivity) { ?>
		<li class="buddy-activity"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>activity/" title="<?php esc_attr_e($budact); ?>"><?php esc_attr_e($budact); ?></a></li>
		<?php }; ?>
		<?php if ($sforums) { ?>
		<li class="buddy-forums"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>activity/" title="<?php esc_attr_e($budfor); ?>"><?php esc_attr_e($budfor); ?></a></li>
		<?php }; ?>
		<?php if ($sfriends) { ?>
		<li class="buddy-friends"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>friends/" title="<?php esc_attr_e($budfriends); ?>"><?php esc_attr_e($budfriends); ?></a></li>
		<?php }; ?>
		<?php if ($smessages) { ?>
		<li class="buddy-messages"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>messages/" title="<?php esc_attr_e($budmes); ?>"><?php esc_attr_e($budmes); ?></a></li>
		<?php }; ?>
		<?php if ($sprofile) { ?>
		<li class="buddy-profile"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>profile/" title="<?php esc_attr_e($budprof); ?>"><?php esc_attr_e($budprof); ?></a></li>
		<?php }; ?>
		<?php if ($sprofileedit) { ?>
		  <li class="buddy-profile-edit"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>profile/edit/" title="<?php esc_attr_e($budprofe); ?>"><?php esc_attr_e($budprofe); ?></a></li>
		<?php }; ?>
		<?php if ($schangeavatar) { ?>
		  <li class="buddy-change-avatar"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>profile/change-avatar/" title="<?php esc_attr_e($budchav); ?>"><?php esc_attr_e($budchav); ?></a></li>
		<?php }; ?>
		<?php if ($ssettings) { ?>
		<li class="buddy-settings"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>settings/" title="<?php esc_attr_e($budset); ?>"><?php esc_attr_e($budset); ?></a></li>
		<?php }; ?>
	    </ul>
	    </div>
<?php

 } // End code for the widget's output



// Do Menu Shortcode

  function shortmenu($atts) {

	 	// Check visitor is logged in

	 	if ( is_user_logged_in() ) {

			extract(shortcode_atts(array(
				'bmact' => 'Activity',
				'bmfor' => 'Forums',
				'bmfri' => 'Friends',
				'bmmsg' => 'Messages',
				'bmpro' => 'Profile',
				'bmedpro' => 'Edit Profile',
				'bmchav' => 'Change Avatar',
				'bmset' => 'Settings',
				'bmlay' => 'Vertical',
			), $atts));
?>    
 	    <div class="buddymenu <?php if ( strtolower(esc_attr($bmlay)) == strtolower('Vertical') ) { echo 'bmvertical'; } else { echo 'bmhorizontal'; } ?> buddyshort">
	    <ul>
		<?php if ($bmact !='-1') { ?>
		<li class="buddy-activity"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>activity/" title="<?php esc_attr_e($bmact); ?>"><?php esc_attr_e($bmact); ?></a></li>
		<?php }; ?>
		<?php if ($bmfor !='-1') { ?>
		<li class="buddy-forums"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>forums/" title="<?php esc_attr_e($bmfor); ?>"><?php esc_attr_e($bmfor); ?></a></li>
		<?php }; ?>
		<?php if ($bmfri !='-1') { ?>
		<li class="buddy-friends"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>friends/" title="<?php esc_attr_e($bmfri); ?>"><?php esc_attr_e($bmfri); ?></a></li>
		<?php }; ?>
		<?php if ($bmmsg !='-1') { ?>
		<li class="buddy-messages"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>messages/" title="<?php esc_attr_e($bmmsg); ?>"><?php esc_attr_e($bmmsg); ?></a></li>
		<?php }; ?>
		<?php if ($bmpro !='-1') { ?>
		<li class="buddy-profile"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>profile/" title="<?php esc_attr_e($bmpro); ?>"><?php esc_attr_e($bmpro); ?></a></li>
		<?php }; ?>
		<?php if ($bmedpro !='-1') { ?>
		<li class="buddy-profile-edit"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>profile/edit/" title="<?php esc_attr_e($bmedpro); ?>"><?php esc_attr_e($bmedpro); ?></a></li>
		<?php }; ?>
		<?php if ($bmchav !='-1') { ?>
		<li class="buddy-change-avatar"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>profile/change-avatar/" title="<?php esc_attr_e($bmchav); ?>"><?php esc_attr_e($bmchav); ?></a></li>
		<?php }; ?>
		<?php if ($bmset !='-1') { ?>
		<li class="buddy-settings"><a href="<?php echo bp_loggedin_user_domain( '/' ) ?>settings/" title="<?php esc_attr_e($bmset); ?>"><?php esc_attr_e($bmset); ?></a></li>
		<?php }; ?>
	    </ul>
	    </div>
<?php

	 	}
	 	// End if

  }


// Clean shortcode() Function to return it instead of echoing it

  function cleanshortmenu($atts){
    ob_start();
    shortmenu($atts);
    $output_menu=ob_get_contents();
    ob_end_clean();

  return $output_menu;

  }

  add_shortcode('buddymenu', 'cleanshortmenu');

// End Menu Shortcode





// Do Link Shortcode

  function shortlink($atts) {

	 	// Check visitor is logged in

	 	if ( is_user_logged_in() ) {

			extract(shortcode_atts(array(
				'bllink' => 'profile',
				'bltitle' => '',
				'bltext' => 'your profile',
				'blq' => '',
			), $atts));

			if ( esc_attr($blq) == 'act') { $bllink='activity'; $bltitle='Your Activity'; $bltext='your activity'; };
			if ( esc_attr($blq) == 'for') { $bllink='forums'; $bltitle='Your Forums'; $bltext='your forums'; };
			if ( esc_attr($blq) == 'fri') { $bllink='friends'; $bltitle='Your Friends'; $bltext='your friends'; };
			if ( esc_attr($blq) == 'msg') { $bllink='messages'; $bltitle='Your Messages'; $bltext='your messages'; };
			if ( esc_attr($blq) == 'pro') { $bllink='profile'; $bltitle='Your Profile'; $bltext='your profile'; };
			if ( esc_attr($blq) == 'edpro') { $bllink='profile/edit'; $bltitle='Edit Your Profile'; $bltext='edit your profile'; };
			if ( esc_attr($blq) == 'chav') { $bllink='profile/change-avatar'; $bltitle='Change Your Avatar'; $bltext='change your avatar'; };
			if ( esc_attr($blq) == 'set') { $bllink='settings'; $bltitle='Your Settings'; $bltext='your settings'; };

?>    

		<a href="<?php esc_attr_e(bp_loggedin_user_domain()) ?><?php echo esc_attr($bllink); ?>/" title="<?php esc_attr_e($bltitle); ?>"><?php esc_attr_e($bltext); ?></a>
<?php

	 	}
	 	// End Visitor Logged In If
else {
	 	// If Visitor is Logged Out

			extract(shortcode_atts(array(
				'blolink' => wp_login_url(),
				'blotitle' => '',
				'blotext' => 'login to view this text',
			), $atts));
?>
	 	<a href="<?php esc_attr_e($blolink); ?>" title="<?php esc_attr_e($blotitle); ?>"><?php esc_attr_e($blotext); ?></a>
<?php
	 	}
	 	// End Visitor Logged Out If

  }

  function cleanshortlink($atts){
    ob_start();
    shortlink($atts);
    $link_output=ob_get_contents();
    ob_end_clean();

  return $link_output;

  }

add_shortcode('buddylink', 'cleanshortlink');

// End Link Shortcode


 
?>