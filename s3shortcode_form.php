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
	// VALIDATING RE-CAPTCHA
	$g_recaptcha_url = ("https://www.google.com/recaptcha/api/siteverify?" .
			    "secret=*****************************************&response=" . 
			    $_POST['g-recaptcha-response'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
	$json = file_get_contents($g_recaptcha_url);
	$obj = json_decode($json);
	$success = $obj->success;
	if (!$success){
		echo "<div id='setting-error-settings_updated' class='updated settings-error'><strong>התרחשה שגיאה בעת העלאת הקובץ (האם אתה רובוט?), אנא נסה שנית. תודה.</strong></div>";
	} else {
		// Go ON AND SUBMIT - IT'S PROBABLY A HUMAN..
		$s3customfield_one = $s3_options["s3db_custom_field_one"];
		$s3customfield_two = $s3_options["s3db_custom_field_two"];
		$s3table = $wpdb->prefix . 's3userDBinfo';

		//retreive post variables and get ready to upload file
		$fn = trim($_FILES['s3filename']['name']);
		if("" !== $fn) {
			// Fixing filename to remove identifying info
			// And adding domain name to distinguish prod/dev
			$ext = end(explode(".", $fn));
			$fileName = $_SERVER['SERVER_NAME']."/leaks/".time().".".$ext;

			$fileTempName = $_FILES['s3filename']['tmp_name'];
			if ($s3->putObjectFile($fileTempName, "$s3bucket", $fileName, S3::ACL_PRIVATE)) {	   
				echo "<div id='setting-error-settings_updated' class='updated settings-error'><strong>תודה. הקובץ הועלה בהצלחה.</strong></div>";
			} else {
				echo "<div id='setting-error-settings_updated' class='updated settings-error'><strong>התרחשה שגיאה בעת העלאת הקובץ, אנא נסה שנית. תודה.</strong></div>";
			}
		} else {
			// No file
			$fileName = $_SERVER['SERVER_NAME']."/leaks/".time().".NoFile.msg.txt";
			echo "<div id='setting-error-settings_updated' class='updated settings-error'><strong>תודה, ההודעה נקלטה.</strong></div>";
		}
			
		// Uploading message details to s3 as well
		$message = "";
		$message .= "Name: ".$_POST['uname'] ."\r\n";
		$message .= "Email: ".$_POST['email_add'] ."\r\n";
		$message .= "Location: ".$_POST['custom_form_field_one'] ."\r\n";
		$message .= "Description: ".$_POST['custom_form_field_two'] ."\r\n";
		$message .= "Date: ".date( 'Y-m-d H:i:s', time()) ."\r\n";
		$messageFileName = $fileName.".msg.txt";
		$s3->putObject($message, "$s3bucket", $messageFileName, S3::ACL_PRIVATE);

		// Create notification mail:
		require_once('./wp-includes/class-phpmailer.php');
		require_once('./wp-includes/class-smtp.php');
		$phpmailer = new PHPMailer();
		$phpmailer->isSMTP(); //switch to smtp
		$phpmailer->Host = 'mail.shkifut.info';
		$phpmailer->SMTPAuth = true;
		$phpmailer->Port = 25;
		//$phpmailer->SMTPDebug = 4;
		$phpmailer->Username = 'leaks@shkifut.info';
		$phpmailer->Password = '******************';
		$phpmailer->SMTPSecure = false;
		$phpmailer->From = 'leaks@shkifut.info';
		$phpmailer->FromName='Leaks Mailbox';
		$phpmailer->SetFrom("leaks@shkifut.info");
		$phpmailer->Subject = "New Leak";
		$phpmailer->MsgHTML("A new leak uploaded as: ".$messageFileName." <br> Access via S3 Console: https://***********.signin.aws.amazon.com/console/s3/ <br>");
		$phpmailer->AddAddress("tomer@shkifut.info","Tomer");

		if(!$phpmailer->Send()) {
			// Debug messages
			//echo 'Message could not be sent.';
			//echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
		}
				
				
	} // End of else (if !success)

} // End of ISSET POST

?>
	<script src="https://www.google.com/recaptcha/api.js?hl=iw" async defer></script>
   	<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
		<input name="upload_location" type="hidden" value="PAGE/POST" />
		<p><label for="uname">שם:</label><br/><input type="text" name="uname" value="" placeholder="או כינוי"/></p>
		<p><label for="email_add">מייל:  (ניתן לא לציין מייל או לפתוח כתובת חדשה ולבדוק אותה ממחשב ציבורי)</label><br/><input type="text" name="email_add" value="" placeholder="לא חובה"/></p>
		<?php if ($s3customfield_one) { ?>
	      		<p><label for="custom_form_field_one">מיקום בו נלקחה התמונה או התוכן:</label><br/><input type="text" name="custom_form_field_one" value="" /></p><br/>
		<?php } else {} ;?>
		<?php if ($s3customfield_two) { ?>
			<p><label for="custom_form_field_two">תיאור:</label><br/><textarea cols="40" rows="5" name="custom_form_field_two" value="" ></textarea></p><br/>
		<?php } else {} ;?>
		<p><input name="s3filename" type="file" /><br/></p>
		<div class="g-recaptcha" data-sitekey="6LdYbAATAAAAAFg3sSrA4ctJ7Gache2rcZGTOBHg"></div>
		<br/>
		<p><input name="Submit" type="submit" value="שלח"></p>
	</form>