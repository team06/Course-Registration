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
?>
<html>
<title>
<? echo $title; ?>
</title>
<head>
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
width:23%;
height:100%;
}
.right_side
{
position:absolute;
top:0px;
right:0px;
background-color:#0033CC;
width:23%;
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
p
{
width:50%;
text-align:center;
}
</style>
</head>
<body>
<?

echo "<div class=\"right_side\"></div>";
echo "<div class=\"left_side\"></div>";
echo "<h3 align=\"center\">$title</h3>";
echo "<table class=\"left\"><tr><td>$number-$section</td></tr>";
echo "<tr><td>$days $time</td></tr>";
echo "<tr><td>$teacher</td></tr>";
echo "<tr><td>$credits</td></tr></table>";
echo "<br><br><br><br><br><br><br>";
echo "<p class=\"left\">$desc</p>";
$q = "SELECT * FROM lab WHERE cid=$cid";

$q = "SELECT * FROM videos WHERE cid=$cid";

echo "<div class=\"bottom\"></div>";
?>
</body>
</html>
