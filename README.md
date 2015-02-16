# wp-s3-secure-uploader
Anonymous WordPress Upload Form over the opensourced plugin 'Simple Amazon S3 Upload Form'

Initial commit is the original source code, as published here https://wordpress.org/plugins/amazon-s3-simple-upload-form/  by Adam Murray, which has given us permission to do as we wish.
Check out readme.txt for the original plugin readme.

## Changes to files
The work we've done was mostly to *s3shortcode_form.php*:
1. Saving only needed information to S3.
2. Sending a report email to the leaks box operator (Tomer)
3. Added Captcha.

We've also modified *S3.php*: 
1. Force SSE - Server Side Encryption (AES256, for now) for all uploads.

**Note that this is THE ONLY FORM WE USE, so we didn't bother changing the others.**

## Usage:
1. Replace placeholder GOOGLE_RECAPTCHA_SECRET_HERE with your google recaptcha secret.
1.1. Replace placeholder GOOGLE_RECAPTCH_DATA_SITE_KEY_HERE with your google recaptcha site-key.
2. Replace placeholder SMTP_PASSWORD_HERE and SMTP server, mailbox etc. with your SMTP settings.
2.1. Make sure to use your own local SMTP server, and send mails to your own mailbox.
3. Replace placeholder AWS_CUSTOMER_NUMBER with your aws customer number.
4. Configure AWS access and secret keys in wp-config.php. See original plugin readme.


## Future work will revolve around:
- [ ] Add documentation about website archtcture.
- [ ] Modifing SSE to use keystore (amazon)


## Files outside this repository:
1. The config lines in wp-config that state AWS Access and Secure keys.
2. Wordpress-HTTPS Plugin to enforce SSL for this page. see (http://www.mvied.com/projects/wordpress-https/). It is open sourced as well.


## Tests done so far:
1. We've made sure to use the latest php (5.6).
2. Make sure openssl is loaded ok.
3. Make sure amazon upload user has minimum permissions ()
4. Make sure SSE works. bucket has versioning setup. uploads are private.
5. Make sure AWS master user is authenticated using two-factor authentication.
