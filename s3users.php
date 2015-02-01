<div class="wrap">
	
    <?php    echo "<h2>" . __( 'S3 User Uploads' ) . "</h2>" ; 
    
	    global $post, $wpdb;	
            $s3_options = get_option('s3plugin_options');
	    $s3customfield_one = $s3_options["s3db_custom_field_one"];
	    $s3customfield_two = $s3_options["s3db_custom_field_two"];
    
    ?>
            
            <table class='widefat'>
				<thead>
					<tr>
						<th>Upload ID</th>
						<th>Time</th>
						<th>Location</th>
						<th>User Name</th>
						<th>Email</th>
						<?php if ($s3customfield_one) { ?>
						<th><?php echo $s3customfield_one ;?></th>
						<?php } else { ?> <th></th> <?php } ; ?>
						<?php if ($s3customfield_two) { ?>
						<th><?php echo $s3customfield_two ;?></th>
						<?php } else { ?> <th></th> <?php } ; ?>
						<th>Filename</th>
						<th>User IP</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Upload ID</th>
						<th>Time</th>
						<th>Location</th>
						<th>User Name</th>
						<th>Email</th>
						<?php if ($s3customfield_one) { ?>
						<th><?php echo $s3customfield_one ;?></th>
						<?php } else { ?> <th></th> <?php } ; ?>
						<?php if ($s3customfield_two) { ?>
						<th><?php echo $s3customfield_two ;?></th>
						<?php } else { ?> <th></th> <?php } ; ?>
						<th>Filename</th>
						<th>User IP</th>
					</tr>
				</tfoot>
				<tbody>
            <?php
	    
	global $wpdb;
        
	$s3table = " ". $wpdb->prefix . "s3userDBinfo";
		
	$s3users = $wpdb->get_results( "SELECT id, upload_time, user_name, email_add, upload_location, custom_form_field_one, custom_form_field_two, upload_url, s3_userIP FROM " .$wpdb->prefix. "s3userDBinfo" );
	
        foreach ($s3users as $s3user){
	
            //output a list of uploads
            echo "<tr>
                    <td>" . $s3user->id . "</td>
                    <td>" . $s3user->upload_time . "</td>
		    <td>" . $s3user->upload_location . "</td>
                    <td>" . $s3user->user_name . "</td>
                    <td>" . $s3user->email_add . "</td>
		    <td>" . $s3user->custom_form_field_one . "</td>
		    <td>" . $s3user->custom_form_field_two . "</td>
		    <td>" . $s3user->upload_url . "</td>
                    <td>" . $s3user->s3_userIP . "</td>
                  </tr>";
        }
	
    ?>
            
               
        </tbody>
                  </table>

</div>