<?
/**
 * Process.php
 * 
 * The Process class is meant to simplify the task of processing
 * user submitted forms, redirecting the user to the correct
 * pages if errors are found, or if form is successful, either
 * way. Also handles the logout procedure.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 19, 2004
 */
include("include/session.php");

class Process
{
   /* Class constructor */
   function Process(){
      global $session;
      /* User submitted login form */
      if(isset($_POST['sublogin'])){
         $this->procLogin();
      }
      /* User submitted registration form */
      else if(isset($_POST['subjoin'])){
         $this->procRegister();
      }
      /* User submitted forgot password form */
      else if(isset($_POST['subforgot'])){
         $this->procForgotPass();
      }
	  else if(isset($_POST['subisadmin'])) {
		  $this->procEditAccountAdmin();
	  }
	  else if(isset($_POST['subpromote'])) {
		  $this->procPromote();
	  }
	  else if(isset($_POST['subdemote'])) {
		  $this->procDemote();
	  }
	  else if(isset($_POST['subemail'])) {
		  $this->procSendOutPassword();
	  }
      /**
       * The only other reason user should be directed here
       * is if he wants to logout, which means user is
       * logged in currently.
       */
      else if($session->logged_in){
         $this->procLogout();
      }
      /**
       * Should not get here, which means user is viewing this page
       * by mistake and therefore is redirected.
       */
       else{
          header("Location: login.php");
       }
   }

   function procSendOutPassword() {
	   global $session, $mailer, $database;
	   $uname = $_POST['uname'];
	   $result = $database->query("SELECT * FROM users WHERE username='$uname'");
	   $user = mysql_fetch_array($result);
	   $email = $user['email'];
	   $first = $user['first_name'];
	   $last = $user['last_name'];
	   $newpass = $session->generateRandStr(8);

	   $name = $first." ".$last;
	   if($mailer->sendNewPass($name, $uname, $email, $newpass)) {
		   $database->updateUserField($uname, "password", md5($newpass));
	   }
	   echo "<h1>Sent e-mail to $first $last</h1>";
	   header("Refresh: 4; URL=admin/index.php?d=mu");
   }

   function procPromote() {
	   global $database;
	   $uname = $_POST['uname'];
	   $database->query("UPDATE users SET userlevel=9 WHERE username='$uname'");
	   header("Location: admin/index.php?d=mu");
   }

   function procDemote() {
	   global $database, $form;
	   $uname = $_POST['uname'];
	   $result = $database->query("SELECT * FROM users where userlevel=9");
	   if($uname == "admin") {
		   $form->setError("promote", "User admin cannot be changed to User level");
	   }
	   else {
		   $database->query("UPDATE users SET userlevel=1 WHERE username='$uname'");
	   }
	   header("Location: admin/index.php?d=mu");
   }


   /**
    * procLogin - Processes the user submitted login form, if errors
    * are found, the user is redirected to correct the information,
    * if not, the user is effectively logged in to the system.
    */
   function procLogin(){
      global $session, $form;
      /* Login attempt */
      $retval = $session->login($_POST['user'], $_POST['pass'], isset($_POST['remember']));
      
      /* Login successful */
      if($retval){
         header("Location: ".$session->referrer);
      }
      /* Login failed */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procLogout - Simply attempts to log the user out of the system
    * given that there is no logout form to process.
    */
   function procLogout(){
      global $session;
      $retval = $session->logout();
      header("Location: login.php");
   }
   
   /**
    * procRegister - Processes the user submitted registration form,
    * if errors are found, the user is redirected to correct the
    * information, if not, the user is effectively registered with
    * the system and an email is (optionally) sent to the newly
    * created user.
    */
   function procRegister(){
      global $session, $form;
      /* Convert username to all lowercase (by option) */
      if(ALL_LOWERCASE){
         $_POST['user'] = strtolower($_POST['user']);
      }
      /* Registration attempt */
      $retval = $session->register($_POST['user'], $_POST['pass'], $_POST['email']);
      
      /* Registration Successful */
      if($retval == 0){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = true;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else if($retval == 1){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Registration attempt failed */
      else if($retval == 2){
         $_SESSION['reguname'] = $_POST['user'];
         $_SESSION['regsuccess'] = false;
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procForgotPass - Validates the given username then if
    * everything is fine, a new password is generated and
    * emailed to the address the user gave on sign up.
    */
   function procForgotPass(){
      global $database, $session, $mailer, $form;
      /* Username error checking */
      $subuser = $_POST['user'];
      $field = "user";  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered<br>");
      }
      else{
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < 5 || strlen($subuser) > 30 ||
            !eregi("^([0-9a-z])+$", $subuser) ||
            (!$database->usernameTaken($subuser))){
            $form->setError($field, "* Username does not exist<br>");
         }
      }
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
      }
      /* Generate new password and email it to user */
      else{
         /* Generate new password */
         $newpass = $session->generateRandStr(8);
         
         /* Get email of user */
         $usrinf = $database->getUserInfo($subuser);
         $email  = $usrinf['email'];
         
         /* Attempt to send the email with new password */
         if($mailer->sendNewPass($subuser,$email,$newpass)){
            /* Email sent, update database */
            $database->updateUserField($subuser, "password", md5($newpass));
            $_SESSION['forgotpass'] = true;
         }
         /* Email failure, do not change password */
         else{
            $_SESSION['forgotpass'] = false;
         }
      }
      
      header("Location: ".$session->referrer);
   }
   
   /**
    * procEditAccount - Attempts to edit the user's account
    * information, including the password, which must be verified
    * before a change is made.
    */
   function procEditAccount(){
      global $session, $form;
      /* Account edit attempt */
      $retval = $session->editAccount($_POST['curpass'], $_POST['newpass'], $_POST['email']);

      /* Account edit successful */
      if($retval){
         $_SESSION['useredit'] = true;
         header("Location: ".$session->referrer);
      }
      /* Error found with form */
      else{
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
   }
   
   function procEditAccountAdmin(){
      global $session, $form, $database;
      /* Account edit attempt */
	  $subnewpass = $_POST['newpass'];
	  $subnewpass = stripslashes($subnewpass);
	  if(strlen($subnewpass) < 4 && $subnewpass != ""){
		  $form->setError("newpass", "* New Password too short");
	  }
	  /* Check if password is not alphanumeric */
	  else if(!eregi("^([0-9a-z])+$", ($subnewpass = trim($subnewpass))) && $subnewpass != ""){
		  $form->setError("newpass", "* New Password not alphanumeric");
	  }
	  if($form->num_errors > 0) {
		  $_SESSION['value_array'] = $_POST;
		  $_SESSION['error_array'] = $form->getErrorArray();
		  header("Location: useredit.php?user=".$_POST['uname']);
	  } else {

		  if($subnewpass != "") $result1 = $database->updateUserField($_POST['uname'],"password",md5($subnewpass));
		  else $result1 = 1;
		  $result2 = $database->updateUserField($_POST['uname'],"email",$_POST['email']);
		  $result3 = $database->updateUserField($_POST['uname'],"honors_status", $_POST['status']);
		  if($result1 && $result2 && $result3) {
			  $_SESSION['useredit'] = true;
			  header("Location: admin/index.php?d=mu");
		  }
	  }
   }
};

/* Initialize process */
$process = new Process;

?>
