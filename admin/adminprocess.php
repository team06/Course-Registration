<?
/**
 * AdminProcess.php
 * 
 * The AdminProcess class is meant to simplify the task of processing
 * admin submitted forms from the admin center, these deal with
 * member system adjustments.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 15, 2004
 */
include("../include/session.php");

class AdminProcess
{
   /* Class constructor */
   function AdminProcess(){
      global $session;
      /* Make sure administrator is accessing page */
      if(!$session->isAdmin()){
         header("Location: ../login.php");
         return;
      }
      /* Admin submitted update user level form */
      if(isset($_POST['subupdlevel'])){
         $this->procUpdateLevel();
      }
      /* Admin submitted delete user form */
      else if(isset($_POST['subdeluser'])){
         $this->procDeleteUser();
      }
      /* Admin submitted delete inactive users form */
      else if(isset($_POST['subdelinact'])){
         $this->procDeleteInactive();
      }
      /* Admin submitted ban user form */
      else if(isset($_POST['subbanuser'])){
         $this->procBanUser();
      }
      /* Admin submitted delete banned user form */
      else if(isset($_POST['subdelbanned'])){
         $this->procDeleteBannedUser();
      }
	  /* Admin submitted file of users to add */
	  else if(isset($_POST['subaddusers'])) {
		$this->addUsersFromFile();
	  }
	  else if(isset($_POST['subaddcourse'])) {
		$this->addCourse();
	  }
      /* Should not get here, redirect to home page */
      else{
         header("Location: ../login.php");
      }
   }

   function addCourse() {
	   global $session, $database, $form;
	   //check if fields are right
	   if($_POST['cname'] == "") {
		   $form->setError("cname", "*");
	   }
	   if($_POST['cnumber'] == "") {
		   $form->setError("cnumber", "*");
	   }
	   //I tried natural birth but it just wouldn't work
	   if($_POST['csection'] == "") {
		   $form->setError("csection", "*");
	   }
	   if($_POST['cteacher'] == "") {
		   $form->setError("cteacher", "*");
	   }
	   if($_POST['desc'] == "") {
			$form->setError("desc", "*");
	   }
	   if(!isset($_POST['cm']) && !isset($_POST['ct']) && !isset($_POST['cw']) && !isset($_POST['cr']) && !isset($_POST['cf']) ) {
		   $form->setError("cday", "*");
	   }
	   if(isset($_POST['ln']) && (isset($_POST['lm']) || isset($_POST['lt']) || isset($_POST['lw']) || isset($_POST['lr']) || isset($_POST['lf']))) {
			$form->setError("lday", "*");
	   }
	   if(!isset($_POST['lm']) && !isset($_POST['lt']) && !isset($_POST['lw']) && !isset($_POST['lr']) && !isset($_POST['lf']) && !isset($_POST['ln']) ) {
		   $form->setError("lday", "*");
	   }
	   if((int)$_POST['s_hour'].$_POST['s_min'] >= (int)$_POST['e_hour'].$_POST['e_min']) {
		   $form->setError("stime", "*");
	   }
	   if(!isset($_POST['ln'])) {
		   if((int)$_POST['ls_hour'].$_POST['ls_min'] >= (int)$_POST['le_hour'].$_POST['le_min']) {
			   $form->setError("ltime", "*");
		   }
	   }
	   if(!preg_match('/[A-Za-z]{4}[0-9]{3}/', $_POST['cnumber'])){
			$form->setError("cnumber", "*");
	   }
	   $days = "";
	   if(isset($_POST['cm'])) {
		   $days = $days.$_POST['cm'];
	   }
	   if(isset($_POST['ct'])) {
		   $days = $days.$_POST['ct'];
	   }
	   if(isset($_POST['cw'])) {
		   $days = $days.$_POST['cw'];
	   }
	   if(isset($_POST['cr'])) {
		   $days = $days.$_POST['cr'];
	   }
	   if(isset($_POST['cf'])) {
		   $days = $days.$_POST['cf'];
	   }
	   $semester = $_POST['semester'];
	   $year = $_POST['year'];
	   $number = $_POST['cnumber'];
	   $section = $_POST['csection'];
	   $name = $_POST['cname'];
	   $time = $_POST['s_hour'].":".$_POST['s_min']."-".$_POST['e_hour'].":".$_POST['e_min'];
	   $credit = $_POST['credits'];
	   $course = Array();
	   $course['name'] = $name;
	   $course['number'] = $number;
	   $course['section'] = $section;
	   $course['credit'] = $credit;
	   $course['days'] = $days;
	   $course['time'] = $time;
	   $course['teacher'] = $_POST['cteacher'];
	   $course['semester'] = $semester;
	   $course['year'] = $year;
	   $course['desc'] = $_POST['desc'];
	   $teacher = $_POST['cteacher'];
	   if($database->confirmCourse($teacher,$semester,$year,$time,$days) == 1) {
		   $form->setError("listing", "$teacher is already teaching a class $semester semester $year at $time on $days");
	   }
	   if($form->num_errors > 0){
		   $_SESSION['value_array'] = $_POST;
		   $_SESSION['error_array'] = $form->getErrorArray();
		   header("Location: add_course.php");
	   }
	   
	   $database->addCourse($course);
	   echo "<h1>Course Added</h1>";
	   echo "You will be automatically redirected back to the Admin Center.<br>If does not happen within 5 seconds please click <a href=\"tools.php\">here</a>";
	   header("Refresh: 4; URL=tools.php");

   }

   function addUsersFromFile() {
	   global $session, $database, $form;
	   if($_FILES['addusers']['error'] == 0) {
		   if($_FILES['addusers']['type'] != "text/plain") {
			$form->setError("addusers", "* Incorrect file type");
		   }
	   }
	   else {
		$form->setError("addusers", "* File not found");
	   }
	   if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: tools.php");
       }
	   else {
		   //reads file with emails on each line and parses out the user name
		   //and stores the values in $output
			exec("cut -d@ -f1 ".$_FILES['addusers']['tmp_name'], $output);
			
			if(isset($_POST['checkreset'])) {
				$q = "DELETE FROM users WHERE userlevel='1'";
				$database->query($q);
			}
			$fails = array();
			foreach($output as $name) {
				if($database->addNewUser($name, md5($name), $name."@radford.edu")) {
					echo "Successfully registered " . $name ."<br/>";
				} else {				
					array_push($fails, $name);
				}
			}
			foreach($fails as $fail) {
				echo "Could not regitser ".$fail ." - user already in database<br/>";
			}
			echo "<a href=\"".$session->referrer."\">Return</a>";
	   }
   }

   /**
    * procUpdateLevel - If the submitted username is correct,
    * their user level is updated according to the admin's
    * request.
    */
   function procUpdateLevel(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("upduser");
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: tools.php");
      }
      /* Update user level */
      else{
         $database->updateUserField($subuser, "userlevel", (int)$_POST['updlevel']);
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procDeleteUser - If the submitted username is correct,
    * the user is deleted from the database.
    */
   function procDeleteUser(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("deluser");
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Delete user from database */
      else{
         $q = "DELETE FROM ".TBL_USERS." WHERE username = '$subuser'";
         $database->query($q);
         header("Location: tools.php");
      }
   }
   
   /**
    * procDeleteInactive - All inactive users are deleted from
    * the database, not including administrators. Inactivity
    * is defined by the number of days specified that have
    * gone by that the user has not logged in.
    */
   function procDeleteInactive(){
      global $session, $database;
      $inact_time = $session->time - $_POST['inactdays']*24*60*60;
      $q = "DELETE FROM ".TBL_USERS." WHERE timestamp < $inact_time "
          ."AND userlevel != ".ADMIN_LEVEL;
      $database->query($q);
      header("Location: tools.php");
   }
   
   /**
    * procBanUser - If the submitted username is correct,
    * the user is banned from the member system, which entails
    * removing the username from the users table and adding
    * it to the banned users table.
    */
   function procBanUser(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("banuser");
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Ban user from member system */
      else{
         $q = "DELETE FROM ".TBL_USERS." WHERE username = '$subuser'";
         $database->query($q);

         $q = "INSERT INTO ".TBL_BANNED_USERS." VALUES ('$subuser', $session->time)";
         $database->query($q);
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * procDeleteBannedUser - If the submitted username is correct,
    * the user is deleted from the banned users table, which
    * enables someone to register with that username again.
    */
   function procDeleteBannedUser(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("delbanuser", true);
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".$session->referrer);
      }
      /* Delete user from database */
      else{
         $q = "DELETE FROM ".TBL_BANNED_USERS." WHERE username = '$subuser'";
         $database->query($q);
         header("Location: ".$session->referrer);
      }
   }
   
   /**
    * checkUsername - Helper function for the above processing,
    * it makes sure the submitted username is valid, if not,
    * it adds the appropritate error to the form.
    */
   function checkUsername($uname, $ban=false){
      global $database, $form;
      /* Username error checking */
      $subuser = $_POST[$uname];
      $field = $uname;  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered<br>");
      }
      else{
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < 5 || strlen($subuser) > 30 ||
            !eregi("^([0-9a-z])+$", $subuser) ||
            (!$ban && !$database->usernameTaken($subuser))){
            $form->setError($field, "* Username does not exist<br>");
         }
      }
      return $subuser;
   }
};

/* Initialize process */
$adminprocess = new AdminProcess;

?>
