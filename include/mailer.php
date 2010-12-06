<? 
/**
 * Mailer.php
 *
 * The Mailer class is meant to simplify the task of sending
 * emails to users. Note: this email system will not work
 * if your server is not setup to send mail.
 *
 * If you are running Windows and want a mail server, check
 * out this website to see a list of freeware programs:
 * <http://www.snapfiles.com/freeware/server/fwmailserver.html>
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */
include("database.php");

class Mailer
{
   /**
    * sendWelcome - Sends a welcome message to the newly
    * registered user, also supplying the username and
    * password.
    */
   function sendWelcome($user, $email, $pass){
      $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
      $subject = "Honors Academy - Welcome!";
      $body = $user.",\n\n"
             ."Welcome! You've just registered at Jpmaster77's Site "
             ."with the following information:\n\n"
             ."Username: ".$user."\n"
             ."Password: ".$pass."\n\n"
             ."If you ever lose or forget your password, a new "
             ."password will be generated for you and sent to this "
             ."email address, if you would like to change your "
             ."email address you can do so by going to the "
             ."My Account page after signing in.\n\n"
             ."- Honors Academy";

      return mail($email,$subject,$body,$from);
   }
   
   /**
    * sendNewPass - Sends the newly generated password
    * to the user's email address that was specified at
    * sign-up.
    */
   function sendNewPass($name, $user, $email, $pass){
	   global $database;
	   $from = "From: ".EMAIL_FROM_NAME." <".EMAIL_FROM_ADDR.">";
	   $result = mysql_fetch_array($database->query("SELECT * FROM dates"));
	   $start = date('l \t\h\e jS \of F, Y \a\t h:i A', $result['start_time']);
	   $end = date('l \t\h\e jS \of F, Y \a\t h:i A', $result['end_time']);
      $subject = "Honors Academy Course Registration - Your new password";
      $body = $name.",\n\n"
             ."We've generated a new password for you. "
             ."You can use this new password with your "
			 ."username to log in to the Honors Academy Course "
			 ."Registration System"."\n\n"
             ."Username: ".$user."\n"
			 ."New Password: ".$pass."\n\n"
			 ."Registration starts $start and ends $end\n\n"
             ."- Honors Academy";
             
      return mail($email,$subject,$body,$from);
   }
};

/* Initialize mailer object */
$mailer = new Mailer;
 
?>
