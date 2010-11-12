<?
/**
 * Admin.php
 *
 * This is the Admin Center page. Only administrators
 * are allowed to view this page. This page displays the
 * database table of users and banned users. Admins can
 * choose to delete specific users, delete inactive users,
 * ban users, update user levels, etc.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 26, 2004
 */
include("../include/session.php");

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers(){
   global $database;
   $q = "SELECT username,userlevel,email,timestamp "
       ."FROM ".TBL_USERS." ORDER BY userlevel DESC,username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "Database table empty";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr><td><b>Username</b></td><td><b>Level</b></td><td><b>Email</b></td><td><b>Last Active</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $ulevel = mysql_result($result,$i,"userlevel");
      $email  = mysql_result($result,$i,"email");
      $time   = date('Y-m-d', mysql_result($result,$i,"timestamp"));

      echo "<tr><td>$uname</td><td>$ulevel</td><td>$email</td><td>$time</td></tr>\n";
   }
   echo "</table><br>\n";
}

/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 */
function displayBannedUsers(){
   global $database;
   $q = "SELECT username,timestamp "
       ."FROM ".TBL_BANNED_USERS." ORDER BY username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "Database table empty";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr><td><b>Username</b></td><td><b>Time Banned</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname = mysql_result($result,$i,"username");
      $time  = mysql_result($result,$i,"timestamp");

      echo "<tr><td>$uname</td><td>$time</td></tr>\n";
   }
   echo "</table><br>\n";
}
   
/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAdmin()){
   header("Location: ../login.php");
}
else{
/**
 * Administrator is viewing page, so display all
 * forms.
 */
?>
<html>
<title>Administration</title>
<head>
	<script type="text/javascript">
	function check() {
		if(document.getElementsByName("checkreset")[0].checked) {
			if(confirm('This will delete all students. Do you wish to continue?')) {
				document.getElementsByName("checkreset")[0].checked	= true;
			}
			else {
				document.getElementsByName("checkreset")[0].checked = false;
			}
		}
	}
	function deluser() {
		if(confirm("Do you wish to remove this user from the system? This is not reversable.")) {
			return true;
		}
		else {
			return false;
		}
	}
	</script>
</head>
<body>
<frameset cols="30%, 70%">
<frame src="user_list.php"/>
<frame src="user_list.php"/>
</frameset>
<div align="center">
<h1>Admin Center</h1>
<font size="4">Logged in as <b><? echo $session->username; ?></b></font><br><br>
Back to [<a href="../login.php">Main Page</a>]<br><br>
<?
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}
?>
</div>
<table align="left" border="0" cellspacing="10" cellpadding="5">
<tr><td valign="top" rowspan=8>
</div>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
<td rowspan=8>
</td>
</tr>
<td>
<?
/**
 * Update User Level
 */
?>
<h3>Update User Level</h3>
<? echo $form->error("upduser"); ?>
<table>
<form action="adminprocess.php" method="POST">
<td>
Username:<br>
<input type="text" name="upduser" maxlength="30" value="<? echo $form->value("upduser"); ?>">
</td>
<td>
Level:<br>
<select name="updlevel">
<option value="1">1
<option value="9">9
</select>
</td>
</tr>
<tr>
<td>
<input type="hidden" name="subupdlevel" value="1">
<input type="submit" value="Update Level">
</td></tr>
</form>
</table>
</td>
<tr>
<td>
<hr>
</td>
</tr>
<tr>
<td>
<?
/**
 * Delete User
 */
?>
<h3>Delete User</h3>
<? echo $form->error("deluser"); ?>
<form action="adminprocess.php" method="POST" onSubmit="return confirm('Do you wish to remove this user from the system? This is not reversable.')">
Username:<br>
<input type="text" name="deluser" maxlength="30" value="<? echo $form->value("deluser"); ?>">
<input type="hidden" name="subdeluser" value="1"><br/>
<input type="submit" value="Delete User">
</form>
</td>
</tr>
<tr>
<td>
<hr>
</td>
</tr>
<tr>
<td>
<?
/**
 * Add users from file
 */
?>
<h3>Add Users from File</h3>
<? echo $form->error("addusers"); ?>
<form action="adminprocess.php" method="POST" enctype="multipart/form-data">
Clear Current Students:
<input type="checkbox" name="checkreset" onclick="check()"/><br/>
File:<br>
<input type="file" name="addusers"/><br/>
<input type="hidden" name="subaddusers" value="1"/>
<input type="submit" value="Add Users"/>
</form>
</td>
</tr>
<?
/**
 * Turn sign-up on and off
 */
?>
<tr>
<td>
<hr>
</td>
</tr>
<tr>
<td>
<h3>Add Course</h3>
<form action="add_course.php">
<input type="submit" value="Add Course"/>
</form>
<a href="add_course.php">Add new Course</a>

</td>
</tr>
</table>
</body>
</html>
<?
}
?>

