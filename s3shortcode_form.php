    <?php 
global $post, $wpdb, $current_user;
	
	
        $s3_options = get_option('s3plugin_options');
	$s3key = $s3_options["s3access_string"]; 
	$s3secret = $s3_options["s3secret_string"]; 
	$s3bucket = $s3_options["s3bucket_dropdown"];
	$s3customfield_one = $s3_options["s3db_custom_field_one"];
	$s3customfield_two = $s3_options["s3db_custom_field_two"];
	$s3table = $wpdb->prefix . 's3userDBinfo';
    
	
	//include the S3 class
	
			if (!class_exists('S3'))require_once('S3.php');
		
			
			//instantiate the class
			$s3 = new S3($s3key, $s3secret);
			
			
			//check whether a form was submitted
			if(isset($_POST['Submit'])){
			    
				$s3customfield_one = $s3_options["s3db_custom_field_one"];
				$s3customfield_two = $s3_options["s3db_custom_field_two"];
				$s3table = $wpdb->prefix . 's3userDBinfo';
				
				//retreive post variables
				
				$user_name = $_POST['user_name'];
				$email_add = $_POST['email_add'];
				$upload_location = $_POST['upload_location'];
				$custom_form_field_one = $_POST['custom_form_field_one'];
				$custom_form_field_two = $_POST['custom_form_field_two'];
				$upload_time = date( 'Y-m-d H:i:s', time());
				$upload_url = $_FILES['s3filename']['name'];
				$s3_user_ip = $_SERVER["REMOTE_ADDR"];
				
				$s3_user_info = array();
				$s3_user_info['user_name'] = $user_name;
				$s3_user_info['email_add'] = $email_add;
				$s3_user_info['upload_location'] = $upload_location;
				$s3_user_info['custom_form_field_one'] = $custom_form_field_one;
				$s3_user_info['custom_form_field_two'] = $custom_form_field_two;
				$s3_user_info['upload_time'] = date("Y-m-d, H:i:s");
				$s3_user_info['upload_url'] = $upload_url;
				$s3_user_info['s3_userIP'] = $s3_user_ip;
				$s3table = $wpdb->prefix . 's3userDBinfo';
				
				$wpdb->insert($s3table, $s3_user_info);
			
				//retreive post variables
				$s3folder = $_POST['s3folder'];
				$fileName = $_FILES['s3filename']['name'];
				$fileTempName = $_FILES['s3filename']['tmp_name'];
				
				//create a new bucket
				$s3->putBucket("$s3bucket", S3::ACL_PUBLIC_READ);
				
				//move the file
				if ($s3->putObjectFile($fileTempName, "$s3bucket", $fileName, S3::ACL_PUBLIC_READ))

                               
{	   
					echo "<div id='setting-error-settings_updated' class='updated settings-error'><strong>Thank you, your file was successfully uploaded.</strong></div>";
					
				}else{
					echo "<div id='setting-error-settings_updated' class='updated settings-error'><strong>Something went wrong while uploading your file... sorry please try again.</strong></div>";
				}
			}

?>

   	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
	    
	       <input name="upload_location" type="hidden" value="PAGE/POST" />	    
	    
	      <p><label for="user_name">Name:</label><br/><input type="text" name="user_name" value="" /></p>
	      <p><label for="email_add">Email:</label><br/><input type="text" name="email_add" value="" /></p>
	      <?php if ($s3customfield_one) { ?>
	      <p><label for="custom_form_field_one"><?php echo $s3customfield_one ;?></label><br/><input type="text" name="custom_form_field_one" value="" /></p><br/>
	      <?php } else {} ;?>
	      <?php if ($s3customfield_two) { ?>
	      <p><label for="custom_form_field_two"><?php echo $s3customfield_two ;?></label><br/><input type="text" name="email_add" value="custom_form_field_one" /></p><br/>
	      <?php } else {} ;?>
	      <p><input name="s3filename" type="file" /><br/></p>
	      <p><input name="Submit" type="submit" value="Upload"></p>
	</form>

