<div class="wrap">
	
    <?php    echo "<h2>" . __( 'S3 Bucket Contents' ) . "</h2>";
    
     $s3_options = get_option('s3plugin_options');
     $s3key = $s3_options["s3access_string"]; 
     $s3secret = $s3_options["s3secret_string"]; 
     $s3bucket = $s3_options["s3bucket_dropdown"];  
    
    ?>
    
            
            <table class='widefat'>
				<thead>
					<tr>
						<th>Filename</th>
						<th>Size</th>
						<th>URL</th>
						<th>Download</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>Filename</th>
						<th>Size</th>
						<th>URL</th>
						<th>Download</th>
					</tr>
				</tfoot>
				<tbody>
            
            <?php  
        
        //include the S3 class
                if (!class_exists('S3'))require_once('S3.php');
            
                
                //instantiate the class
                $s3 = new S3($s3key, $s3secret);
    
        // Get the contents of our bucket
        $bucket_contents = $s3->getBucket($s3bucket);
        
        foreach ($bucket_contents as $file){
        
            $fname = $file['name'];
            $fsize = $file['size'];
            $furl = "http://".$s3bucket.".s3.amazonaws.com/".$fname;
            
            //output a link to the file
            echo "<tr id='s3bucket_contents'>
                    <td>$fname</td>
                    <td>$fsize</td>
                    <td><span style='font-weight:bold;'>$furl</span></td>
                    <td><strong><a href=\"$furl\">Download File</a></strong></td>
                  </tr>";
        }
    ?>
               
        </tbody>
                  </table>

</div>