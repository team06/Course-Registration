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
		."<div align=\"center\">[<a href=\"login.php\">Main</a>] &nbsp;&nbsp;[<a href=\"userinfo.php?user=$session->username\">My Account</a>] &nbsp;&nbsp;";
	//    ."[<a href=\"useredit.php\">Edit Account</a>] &nbsp;&nbsp;";
	if($session->isAdmin()){
		echo "[<a href=\"admin/admin.php\">Admin Center</a>] &nbsp;&nbsp;";
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
		echo "<table align=\"center\"><tr><td><a href=\"listing.php\">Go back</a></td></tr></table>";
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
	echo "<table align=\"center\">";
	echo "<tr><th>Year</th><th>Semester</th></tr>";
	echo "<tr><td>";
	echo "<select name=\"year\">";
	getYearRange();
	echo "</select>";
	echo "</td><td>";
	echo "<select name=\"semester\">";
	echo "<option value=\"s\">Spring</option>";
	echo "<option value=\"f\">Fall</option>";
	echo "</select>";
	echo "</td><td>";
	echo "<input type=\"submit\" value=\"Load\">";
	echo "</td></tr>";
	echo "</table>";
}
?>
<html>
<title>Honors Academy</title>
<body>
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
</body>
</html>
