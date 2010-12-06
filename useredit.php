<?
/**
 * UserEdit.php
 *
 * This page is for users to edit their account information
 * such as their password, email address, etc. Their
 * usernames can not be edited. When changing their
 * password, they must first confirm their current password.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 26, 2004
 */
include("include/session.php");
?>

<html>
<title>Honors Academy</title>
<body>

<?
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['useredit'])){
   unset($_SESSION['useredit']);
   
   echo "<h1>User Account Edit Success!</h1>";
   echo "<p><b>$session->username</b>, your account has been successfully updated. "
       ."<a href=\"login.php\">Main</a>.</p>";
}
else{
?>

<?
/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if($session->isAdmin()){
?>

<h1>User Account Edit : <? echo $_GET['user']; ?></h1>
<?
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}
?>
<form action="process.php" method="POST">
<table align="left" border="0" cellspacing="0" cellpadding="3">
<?
if(!$session->isAdmin())
{
?>
<tr>
<td>Current Password:</td>
<td><input type="password" name="curpass" maxlength="30" value="
<?echo $form->value("curpass"); ?>"></td>
<td><? echo $form->error("curpass"); ?></td>
</tr>
<?
}
else
{
?>
<input type="hidden" name="subisadmin" value="1"/>
<?
}
?>
<tr>
<td>New Password:</td>
<td><input type="password" name="newpass" maxlength="30" value="
<? echo $form->value("newpass"); ?>"></td>
<td><? echo $form->error("newpass"); ?></td>
</tr>
<tr>
<td>Email:</td>
<td><input type="text" name="email" maxlength="50" value="
<?
if($form->value("email") == ""){
	global $database;
	$uname = $_GET['user'];
	$result = $database->query("SELECT * FROM users WHERE username = '$uname'");
	$user = mysql_fetch_array($result);
   echo $user['email'];
}else{
   echo $form->value("email");
}
?>">
</td>
<td><? echo $form->error("email"); ?></td>
</tr>
<tr><td colspan="2" align="right">
<input type="hidden" name="subedit" value="1">
<input type="hidden" name="uname" value="<?echo $_GET['user'];?>"/>
<input type="submit" value="Edit Account"></td></tr>
<tr><td colspan="2" align="left"></td></tr>
</table>
</form>
<form action="process.php" method="POST">
<input type="hidden" name="subpromote" value="1"/>
<input type="hidden" name="uname" value="<?echo $_GET['user'];?>"/>
<input type="submit" value="Make Admin"/>
</form>
</form>
<form action="process.php" method="POST">
<input type="hidden" name="subdemote" value="1"/>
<input type="hidden" name="uname" value="<?echo $_GET['user'];?>"/>
<input type="submit" value="Make User"/>
</form>
<form action="process.php" method="POST">
<input type="hidden" name="subemail" value="1"/>
<input type="hidden" name="uname" value="<?echo $_GET['user'];?>"/>
<input type="submit" value="Send"/>
</form>

<?
}
else {
	$user = $_GET['user'];
	header("Location: userinfo.php?$user");
}
}

?>

</body>
</html>
