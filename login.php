<?
/**
 * Main.php
 *
 * This is an example of the main page of a website. Here
 * users will be able to login. However, like on most sites
 * the login form doesn't just have to be on the main page,
 * but re-appear on subsequent pages, depending on whether
 * the user has logged in or not.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 26, 2004
 */
include("include/session.php");
?>

<html>
<title>Honors Academy</title>
<head>
<script type="text/javascript">
// <![CDATA[
	function openWindow(url,width,height,options,name) {
		width = width ? width : 800;
		height = height ? height : 600;
		options = options ? options : 'resizable=yes';
		name = name ? name : 'openWindow';
		window.open(
			url,
			name,
			'screenX='+(screen.width-width)/2+',screenY='+(screen.height-height)/2+',width='+width+',height='+height+','+options
		)
	}
// ]]>
</script>
<style type="text/css">
.left
{
position:absolute;
left:25%;
}
.left_side
{
position:absolute;
top:0px;
left:0px;
background-color:#0033CC;
width:15%;
height:100%;
}
.right_side
{
position:absolute;
top:0px;
right:0px;
background-color:#0033CC;
width:15%;
height:100%;
}
.bottom
{
position:absolute;
bottom:0px;
background-color:#0033CC;
width:90%;
height:10%;
}
table, td
{
text-align:center;
}
</style>
</head>
<body>

<div align="center">

<?
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
if(!$session->displayHeader()) {
?>
<h1>Login</h1>
<?
/**
 * User not logged in, display the login form.
 * If user has already tried to login, but errors were
 * found, display the total number of errors.
 * If errors occurred, they will be displayed.
 */
if($form->num_errors > 0){
   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
   echo '<br/><font size="2" color="#FF0000">';
   echo $form->error("time");
   echo '</font>';
}
?>
<form action="process.php" method="POST">
<table align="center" border="0" cellspacing="0" cellpadding="3">
<tr><td>Username:</td><td><input type="text" name="user" maxlength="30" value="<? echo $form->value("user"); ?>"></td><td><? echo $form->error("user"); ?></td></tr>
<tr><td>Password:</td><td><input type="password" name="pass" maxlength="30" value="<? echo $form->value("pass"); ?>"></td><td><? echo $form->error("pass"); ?></td></tr>
<tr><td colspan="2" align="left"><input type="checkbox" name="remember" <? if($form->value("remember") != ""){ echo "checked"; } ?>>
<font size="2">Remember me next time &nbsp;&nbsp;&nbsp;&nbsp;
<input type="hidden" name="sublogin" value="1">
<input type="submit" value="Login"></td></tr>
<tr><td colspan="2" align="left"><br><font size="2">[<a href="forgotpass.php">Forgot Password?</a>]</font></td><td align="right"></td></tr>
<!--<tr><td colspan="2" align="left"><br>Not registered? <a href="register.php">Sign-Up!</a></td></tr>-->
</table>
</form>
<?
}
if(!$session->isAdmin() && $session->logged_in) {
	echo '<div align="center">';
	echo '<h3>Courses registered for</h3>';
	echo '</div>';
	$q = "SELECT * FROM signups INNER JOIN courses ON signups.cid = courses.cid WHERE username='".$session->username."'";
	$results = $database->query($q);
	echo '<table width="40%" align="center">';
	echo '<tr><th><b>Title</b></th><th><b>Days</b></th><th><b>Time</b></th><th><b>Teacher</b></th><th><b>Drop</b></th></tr>';
	while($info = mysql_fetch_array($results)) {
		$title = $info['title'];
		$days = $info['days'];
		$time = $info['time'];
		$teacher = $info['teacher'];
		$cid = $info['cid'];
		echo "<tr><td>$title</td><td>$days</td><td>$time</td><td>$teacher</td><td><a onclick=\"return confirm('Are you sure you want to drop this course?');\" href=\"drop.php?cid=$cid\">X</a></td></tr>";
	}
	echo "</table>";
}
/**
 * Just a little page footer, tells how many registered members
 * there are, how many users currently logged in and viewing site,
 * and how many guests viewing site. Active users are displayed,
 * with link to their user information.
 */
echo "<div align=\"center\"><br><br>";
echo "<b>Member Total:</b> ".$database->getNumMembers()."<br>";
echo "There are $database->num_active_users registered members and ";
echo "$database->num_active_guests guests viewing the site.<br><br></div>";

//include("include/view_active.php");

?>
</div>
</body>
</html>
