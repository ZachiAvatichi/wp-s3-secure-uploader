<div class="wrap">
	
     <div class="icon32" id="icon-options-general"><br></div>

     <?php    echo "<h2>" . __( 'Amazon S3 Video Upload Settings' ) . "</h2>";?>
       
    <form action="options.php" method="post">
		<?php settings_fields('plugin_options'); ?>
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