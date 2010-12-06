<html>
<head>
<style type="text/css">
table, td
{
text-align:center;
}
</style>
</head>
<?
include("include/session.php");

$cid = $_GET['cid'];

$course = mysql_fetch_array($database->query("SELECT * FROM courses WHERE cid=$cid"));

$title = $course['title'];
$number = $course['number'];
$days = $course['days'];
$time = $course['time'];

echo '<div align="center">';
echo "<h1>$title</h1>";
echo '</div>';
echo '<table align="center" cellpadding="5" cellspacing="5">';
echo "<tr><td><b>Course Number:</b>&nbsp;&nbsp;</td><td>$number</td></tr>";
echo "<tr><td><b>Time:</b></td><td>$days $time</td></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "<tr></tr>";
echo "</table>";
echo '<table width="60%" align="center" cellpadding="5" cellspacing="5">';
echo "<tr><td><b>Name</b></td><td><b>SID</b></td><td><b>Username</b></td><td><b>E-mail</b></td></tr>";
$result = $database->query("SELECT * FROM signups NATURAL JOIN users WHERE cid=$cid ORDER BY last_name, first_name");
while($user= mysql_fetch_array($result)) {

	if(!isset($user['first_name'])) {
		echo "<tr><td colspan=\"4\">".$uname['uname']." was delete from the system</td></tr>";
	} else {
		$name = $user['first_name']." ".$user['last_name'];
		$sid = $user['sid'];
		$username = $user['username'];
		$email = $user['email'];
		echo "<tr><td>$name</td><td>$sid</td><td>$username</td><td>$email</td></tr>";
	}
}
echo '</table>';
?>
</html>
