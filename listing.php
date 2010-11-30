<?php
include("include/session.php");

function getYearRange() {
	global $database;
	$q = "SELECT DISTINCT year FROM `years` ORDER BY year ASC";
	$result = $database->query($q);
	while($list = mysql_fetch_array($result)) {
		$year = $list['year'];
		echo "<option value=\"$year\">$year</option>";
	}
}

if($session->logged_in) {
	echo "<div align=\"center\">";
	echo "<h1>Honors Academy Course Registration</h1>";
	echo "Welcome <b>$session->username</b>, you are logged in. <br><br>"
		."<div align=\"center\">[<a href=\"login.php\">Main</a>] &nbsp;&nbsp;";
	//    ."[<a href=\"useredit.php\">Edit Account</a>] &nbsp;&nbsp;";
	if($session->isAdmin()){
		echo "[<a href=\"admin/\">Admin Center</a>] &nbsp;&nbsp;";
	}
	echo "[<a href=\"listing.php\">Courses</a>] &nbsp;&nbsp;";
	echo "[<a href=\"process.php\">Logout</a>]</div></div>";
	echo "<br>";
	echo "<br>";
	echo "<br>";

} else {
	header('Location: login.php');
}

function displayCourses() {
	global $database;
	if($_POST != Array()) {
		echo "<div align=\"center\"><a href=\"listing.php\">Go back</a></div>";
		echo "<table align=\"center\" cellspacing=\"5\" cellpadding=\"5\" border=\"1\">";
		echo "<tr><th>Course Number</th><th>Section</th><th>Title</th><th>Credits</th><th>Days</th><th>Time</th><th>Instructor</th></tr>";
		$y = $_POST['year'];
		$s = $_POST['semester'];
		$q = "SELECT * FROM years NATURAL JOIN courses WHERE year = '$y' AND semester = '$s'";
		$result = $database->query($q);
		while($course = mysql_fetch_array($result)) {
			$title = $course['title'];
			$number = $course['number'];
			$section = $course['section'];
			$time = $course['time'];
			$days = $course['days'];
			$credits = $course['credits'];
			$teacher = $course['teacher'];
			echo "<tr><td>$number</td><td>$section</td><td>$title</td><td>$credits</td><td>$days</td><td>$time</td><td>$teacher</td></tr>";
		}
		echo "</table>";
	}
}

function displaySelect() {
	if($_POST != Array()) return;
	echo "<div align=\"center\">Select year and semester</div><br>";
	echo "<div align=\"center\">";
	echo "<select name=\"year\">";
	getYearRange();
	echo "</select>";
	echo "<select name=\"semester\">";
	echo "<option value=\"s\">Spring</option>";
	echo "<option value=\"f\">Fall</option>";
	echo "</select>";
	echo "<input type=\"submit\" value=\"Load\">";
	echo "</div>";
}
?>
<html>
<title>Honors Academy</title>
<head>
<style type="text/css">
table, td, th
{
border:1px solid black;
border-collapse:collapse;
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
</style>
</head>
<body>
<div class="left_side"></div>
<div class="right_side"></div>
<form action="listing.php" method="POST">
<? displaySelect(); ?>
</form>
<? if($_POST != Array()) {
	$year = $_POST['year'];
	$sem = $_POST['semester'] == "s" ? "Spring" : "Fall";
	echo "<h1 align=\"center\">$sem $year</h1>"; 
}
?>
<? displayCourses(); ?>
<div class="bottom"></div>
</body>
</html>
