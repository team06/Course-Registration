<?
include("include/session.php");

if(!$session->logged_in) {
	header("Location: login.php");
}

if(!isset($_GET['cid'])) {
	echo "<h1>No course found</h1>";
	echo "<h3>You will be redirected back to the course listing</h3>";
	header("Refresh: 4 URL=listing.php");
}
$cid = $_GET['cid'];
$q = "SELECT * FROM courses NATURAL JOIN years WHERE cid=$cid";
$result = $database->query($q);
if(mysql_num_rows($result) < 1) {
	echo "<h1>No course found</h1>";
	echo "<h3>You will be redirected back to the course listing</h3>";
	header("Refresh: 4 URL=listing.php");
}
$course = mysql_fetch_array($result);
$title = $course['title'];
$number = $course['number'];
$section = $course['section'];
$days = $course['days'];
$time = $course['time'];
$teacher = $course['teacher'];
$credits = $course['credits'];
$desc = $course['description'];
$result = $database->query("SELECT * FROM lab WHERE cid = $cid");
if(mysql_num_rows($result) > 0) {
	$lab = mysql_fetch_array($result);
	$l_days = $lab['lab'];
	$l_time = $lab['time'];
}
?>
<html>
<title>
<? echo $title; ?>
</title>
<head>
<style type="text/css">
.left
{
position:relative;
left:25%;
}
.left_side
{
position:relative;
top:0px;
left:0px;
background-color:#0033CC;
width:23%;
height:100%;
}
.right_side
{
position:relative;
top:0px;
right:0px;
background-color:#0033CC;
width:23%;
height:100%;
}
.bottom
{
position:relative;
bottom:0px;
background-color:#0033CC;
width:90%;
height:10%;
}
p
{
width:50%;
text-align:center;
}
</style>
</head>
<body>
<?
echo '<div align="center">';
$session->displayHeader();
echo '</div>';
echo "<h3 align=\"center\">$title</h3>";
echo "<table class=\"left\"><tr><td><b>Course Number:</b></td><td>$number-$section</td></tr>";
echo "<tr><td><b>Class Time:</b></td><td>$days $time</td></tr>";
if(isset($lab)) echo "<tr><td><b>Lab Time:</b></td><td>$l_days $l_time</td></tr>";
echo "<tr><td><b>Instructor:</b></td><td>$teacher</td></tr>";
echo "<tr><td><b>Credits:</b></td><td>$credits</td></tr></table>";
echo "<br><br><br><br><br>";
echo '<div align="center">';
echo '<b>Course Description</b>';
echo '</div>';
echo "<p class=\"left\">$desc</p>";

if($session->isAdmin()) {
	echo '<div align="center">';
	echo '<a href="print.php?cid='.$_GET['cid'].'" target="_blank">View Roster</a>';
}
?>
</body>
</html>
