<?php
/*
Plugin Name: Simple Amazon S3 Upload Form
Plugin URI: http://www.twodoorscreative.com/wordpress-plugin-simple-amazon-s3-upload-form/
Description: Simple form that allows users to upload files to a specific Amazon S3 bucket.
Author: Adam Murray
Author URI: http://twodoorscreative.com
Version:1.2
*/

/*  Copyright 2011  Adam Murray  (email : adam@twodoorscreative.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('S3SIMPLEUPLOAD', dirname(plugin_basename(__FILE__)));

// Specify Hooks/Filters
register_activation_hook(__FILE__,'s3_userDBinfo');
add_action('admin_init', 's3options_init_fn' );
add_action('admin_menu', 's3options_add_page_fn');

//add_action('admin_head','s3upload_style');

//Create new DB fields for user upload info

global $sas3_db_version;
$sas3_db_version = "1.2";

function s3_userDBinfo () {
   global $wpdb;
   global $sas3_db_version;

   $table_name = $wpdb->prefix . "s3userDBinfo";
   
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  upload_time VARCHAR(255) NOT NULL,
	  user_name VARCHAR(55) NULL,
	  email_add VARCHAR(255) NULL,
	  upload_location VARCHAR(12) NULL,
	  custom_form_field_one VARCHAR(255) NULL,
	  custom_form_field_two VARCHAR(255) NULL,
	  upload_url VARCHAR(255) NULL,
	  s3_userIP VARCHAR(255) NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
      
   $installed_ver = get_option( "sas3_db_version" );

      add_option("sas3_db_version", $sas3_db_version);
   }
   
   if( $installed_ver != $sas3_db_version ) {

      $sql = "CREATE TABLE " . $table_name . " (
	 id mediumint(9) NOT NULL AUTO_INCREMENT,
	  upload_time VARCHAR(255) NOT NULL,
	  user_name VARCHAR(55) NULL,
	  email_add VARCHAR(255) NULL,
	  upload_location VARCHAR(12) NULL,
	  custom_form_field_one  VARCHAR(255) NULL,
	  custom_form_field_two VARCHAR(255) NULL,
	  upload_url VARCHAR(255) NULL,
	  s3_userIP VARCHAR(255) NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      update_option( "sas3_db_version", $sas3_db_version );
  }
}

function s3_update_db_check() {
    global $sas3_db_version;
    if (get_site_option('sas3_db_version') != $sas3_db_version) {
        sas3_install();
    }
}
add_action('plugins_loaded', 's3_update_db_check');


/*function s3upload_style() {
	include ('s3style.css')
} */

// Register our settings. Add the settings section, and settings fields
function s3options_init_fn(){
	register_setting('s3plugin_options', 's3plugin_options', 'plugin_options_validate' );
	add_settings_section('upload_settings_section', 'Amazon S3 Upload Settings', 'upload_section_text_fn', __FILE__);
	add_settings_field('plugin_text_string', 'S3 Access Key:', 's3access_string_fn', __FILE__, 'upload_settings_section');
	add_settings_field('plugin_text_pass', 'S3 Secret Key:', 's3secret_string_fn', __FILE__, 'upload_settings_section');
	add_settings_field('s3bucket_dropdown', 'S3 Bucket for User Uploads (on pages and posts):', 's3bucket_dropdown_fn', __FILE__, 'upload_settings_section');
	add_settings_field('admin_s3bucket_dropdown', 'S3 Bucket for Admin Uploads (only in admin section):', 'admin_s3bucket_dropdown_fn', __FILE__, 'upload_settings_section');
	//add_settings_field('s3role_dropdown', 'Minimal User Role for Admin S3 Uploads', 's3role_dropdown_fn', __FILE__, 'main_section');
	
	add_settings_section('form_settings_section', 'Form Settings', 'form_section_text_fn', __FILE__);
	add_settings_field('s3db_custom_field_one' , 'Custom Form Field - 1 (This will appear as the form element title.)', 's3db_custom_field_one_fn', __FILE__, 'form_settings_section');
	add_settings_field('s3db_custom_field_two' , 'Custom Form Field - 2 (This will appear as the form element title.)', 's3db_custom_field_two_fn', __FILE__, 'form_settings_section');
}

//Include Form Files

function s3form_content () {

	include ('s3form.php');

}

function s3bucket_content () {

	include ('s3contents.php');
   
}

function s3upload_info () {

	include ('s3users.php');
	
}
	
// Add sub page to the Settings Menu
function s3options_add_page_fn() {

	/*$user_role = get_option('s3plugin_options');
	$role = $user_role["s3role_dropdown"];*/
	
	add_options_page('Amazon S3 Simple Upload Form Upload Settings', 'S3 Upload Settings', 'manage_options', __FILE__, 's3options_page_fn');
	
	//Add upload form Submenu
	add_media_page( 'S3 Upload Form', 'S3 Upload Form', 'manage_options' , 's3_uploader', 's3form_content');
	add_media_page(' S3 Bucket Contents' , 'S3 Bucket Contents' , 'manage_options' , 's3uploader', 's3bucket_content');
	add_media_page(' S3 Bucket Upload Info', 'S3 Bucket Upload Info', 'manage_options' , 's3_Uploader','s3upload_info');
}


// ************************************************************************************************************

// Callback functions

// Section HTML, displayed before the first option
function  upload_section_text_fn() {
	echo '<p>To set up the upload form properly, first enter your Amazon S3 Access Key and Secret Key. Then choose a bucket where files will be uploaded.</p>';
}

function  form_section_text_fn() {
	echo '<p></p>';
}

// TEXTBOX - Name: s3plugin_options[s3access_string]
function s3access_string_fn() {
	$options = get_option('s3plugin_options');
	echo "<input id='plugin_text_string' type='text' size='40' name='s3plugin_options[s3access_string]' value='{$options['s3access_string']}' />";
}

// PASSWORD-TEXTBOX - Name: s3plugin_options[s3secret_string]
function s3secret_string_fn() {
	$options = get_option('s3plugin_options');
	echo "<input id='plugin_text_pass' name='s3plugin_options[s3secret_string]' size='40' type='password' value='{$options['s3secret_string']}' />&nbsp;<a href='http://aws-portal.amazon.com/gp/aws/developer/account/index.html/?ie=UTF8&action=access-key'>Login to AWS to retrieve your secret key</a>";
}

// DROP-DOWN-BOX - Name: s3plugin_options[s3bucket_dropdown]
function  s3bucket_dropdown_fn() {
	
if (!class_exists('S3'))require_once('S3.php');
	
	$s3_options = get_option('s3plugin_options');
	$s3key = $s3_options["s3access_string"]; 
	$s3secret = $s3_options["s3secret_string"]; 
	$s3 = new S3($s3key,$s3secret);
	$buckets = $s3->listBuckets();
	
	 // Standard list:
	echo "<select id='s3bucket_dropdown' name='s3plugin_options[s3bucket_dropdown]'>"; 
	foreach ($buckets as $bucket)
		{
	$selected = ($s3_options['s3bucket_dropdown']==$bucket) ? 'selected="selected"' : '';
    echo "<option value='$bucket' $selected>$bucket</option>";
		} 
		echo "</select>";
}

// DROP-DOWN-BOX - Name: s3plugin_options[admin_s3bucket_dropdown]
function  admin_s3bucket_dropdown_fn() {
	
if (!class_exists('S3'))require_once('S3.php');
	
	$s3_options = get_option('s3plugin_options');
	$s3key = $s3_options["s3access_string"]; 
	$s3secret = $s3_options["s3secret_string"]; 
	$s3 = new S3($s3key,$s3secret);
	$admin_buckets = $s3->listBuckets();
	
	 // Standard list:
	echo "<select id='admin_s3bucket_dropdown' name='s3plugin_options[admin_s3bucket_dropdown]'>"; 
	foreach ($admin_buckets as $admin_bucket)
		{
	$selected = ($s3_options['admin_s3bucket_dropdown']==$admin_bucket) ? 'selected="selected"' : '';
    echo "<option value='$admin_bucket' $selected>$admin_bucket</option>";
		} 
		echo "</select>";
}

function s3db_custom_field_one_fn() {
	 
        $options = get_option('s3plugin_options');	
	echo "<input id='s3db_custom_field_one' type='text' size='40' name='s3plugin_options[s3db_custom_field_one]' value='{$options['s3db_custom_field_one']}' />";
}

function s3db_custom_field_two_fn() {
	 
        $options = get_option('s3plugin_options');	
	echo "<input id='s3db_custom_field_two' type='text' size='40' name='s3plugin_options[s3db_custom_field_two]' value='{$options['s3db_custom_field_two']}' />";
}

// DROP-DOWN-BOX - Name: s3plugin_options[s3role__dropdown]
/*function  s3role_dropdown_fn() {
	$options = get_option('s3plugin_options');
	$items = array('manage_options', "manage_categories", "upload_files", "edit_posts", "read");
	echo "<select id='s3role_dropdown' name='s3plugin_options[s3role_dropdown]'>";
	foreach($items as $item) {
		$selected = ($options['s3role_dropdown']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}*/

// Display the admin options page
function s3options_page_fn() {
?>
	<div class="wrap">
	
     <div class="icon32" id="icon-options-general"><br></div>

     <?php    echo "<h2>" . __( 'Amazon S3 Video Upload Settings' ) . "</h2>";?>
       
    <form action="options.php" method="post">
    
		
		<?php settings_fields('s3plugin_options'); ?>
        
		<?php do_settings_sections(__FILE__); ?>
        
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
     
     <br />
     
     <p><span style="font-weight:bold;">AMAZON S3 FAQs</span> : <a href="http://aws.amazon.com/s3/faqs/#How_can_I_get_started_using_Amazon_S3">Click Here</a></p> 
     
      <p class="alignleft" style="width:300px; text-align:center; border:1px solid #404040; padding:2px">If you enjoy using this plugin, please consider making a monetary donation.  It will help with upkeep and improvements to the plugin!  </p><br /> <p class="alignright"> <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="10034264">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form></p>
	
</div>
<?php
}

// Validate user data for some/all of your input fields
function plugin_options_validate($input) {
	// Check our textbox option field contains no HTML tags - if so strip them out
	$input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);	
	return $input; // return validated input
}

//Register S3 Widget
add_action('widgets_init', create_function('', 'return register_widget("saS3widget");'));

//Create Widget for Sidebar Upload Form
class saS3widget extends WP_Widget {
    /** constructor */
    function saS3widget() {
        parent::WP_Widget(false, $name = 'Simple Amazon S3 Upload Form');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
                 <?php include('s3widget_form.php');?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
    }

}

//Shortcode API

add_shortcode('s3form', 's3shortcode_fn');

function s3shortcode_fn() {
	
	ob_start();
	
	include('s3shortcode_form.php');
	$content = ob_get_clean();
	
	return $content;
}



?>