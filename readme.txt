=== Plugin Name ===

Contributors: Adam Murray
Donate link: 
Tags: S3, Amazon, upload, attachment, media, file browser, video uploader, cloudfront
Requires at least: 2.5
Tested up to: 3.1.3
Stable Tag: 1.2

== Description ==

A simple upload form that allows a user to upload files to a specific Amazon S3 bucket.  The user can also browse and download files within the Amazon S3 bucket.

= Feature List =

* Upload files to specific Amazon S3 buckets
* Browse files within a specific Amazon S3 bucket
* Download files from a specific Amazon S3 bucket
* Access Amazon S3 login page
* Insert S3 upload form into sidebar via widget
* Insert S3 upload form into posts and pages via [s3form] shortcode
* Specify bucket destination for admin uploads and user-facing uploads
* View information about users that have used the form on pages or in the sidebar 

= Support =

* Submit questions via contact form (http://www.twodoorscreative.com/contactus/)
* Author is available for professional consulting to meet your configuration, troubleshooting and customization needs.
	
== Installation == 

Simple Amazon S3 Upload Form can be installed automatically via the Plugins tab in your blog administration panel.

= For manual install =

	- Download plugin and place in 'wp-content/plugins' folder.
	- Activate plugin by clicking on "Plugins" on the main administration menu, click on "Installed", find the "Simple Amazon S3 Upload Form" plugin, and 		
	  "Activate.
	  
    CONFIGURATION :

	- Click on "Settings" on the main administration menu.
	- Fill in your Amazon S3 Access Key, Secret Key (will remain hidden), and choose a Bucket Name from the drop-down list for admin uploads and user-facing uploads.
	- Click "Save Changes"

	
    UPLOADING :

	- Click on "Media" on the main administration menu.
	- Click on "S3 Upload Form".
	- Browse for your file, and click upload (will be adding a progress bar later). 
	- Once uploaded, you will receive a confirmation message.  If there is a problem, an error message will appear.
	- An upload form can be placed in posts and pages by placing [s3form] into your post or page.
	- An S3 form can be added to your sidebar by navigating to the "Widgets" section of the WP admin, and dragging the Simple Amazon S3 Upload Form to a specific widget area.
	
    BROWSING FILES :

	- Click on "Media" on the main administration menu.
	- Click on "S3 Bucket Contents".
	- Filenames will be evident, and simply click "Download File" to download each individual file within your bucket.
	
	- I will eventually be paginating the files.
	
== Frequently Asked Questions ==

- I get an error when I try to see the contents of my bucket.  What is the problem?  

	First, try refreshing your main bucket login settings.  This could happen after updating your Wordpress install.

- I can only upload small files. What is the problem?

	Check your php.ini file or super-admin Wordpress settings and adjust accordingly.  This plugin won't affect the size of files that you can upload.

== Screenshots ==

None.

== Changelog ==

1.0.1 - Added file URL to "S3 Bucket Contents", so users could easily embed files into HTML.

1.0.2 - Made file navigation easier by adding Wordpress table and style. Also added file size to display.

1.0.3 - Made compatible with WP 3.0

1.0.4 - Edited user interface, discovered bucket settings must be updated after Wordpress update

1.1 - Added shortcode, added widget, drop-down bucket list allows for more specific uploading, updated settings area

1.1.2 - When user uploads via sidebar or shortcode form, their information is stored in the database for admins.  Will be developing user list and form validation.

1.1.3 - User Info page now works.  Added database fields for user info form.  Will be creating advanced form elements and styling in future releases.

1.2 - Added two custom form fields that can be defined by admins.  User uploads now display all upload info, including custom field information.  Uploads within dashboard display username and email in addition to other info.
