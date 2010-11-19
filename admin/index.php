<?
include("../include/session.php");
if(!$session->isAdmin()){
   header("Location: ../login.php");
}
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
   echo "<table align=\"center\" width=\"40%\">\n";
   echo "<tr><td><b>Username</b></td><td><b>Level</b></td><td><b>Email</b></td><td><b>Delete</b></td><td><b>Edit</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $ulevel = mysql_result($result,$i,"userlevel") == "1" ? "User" : "Admin";
      $email  = mysql_result($result,$i,"email");
      $time   = date('Y-m-d', mysql_result($result,$i,"timestamp"));

	  echo "<tr><td>$uname</td><td>$ulevel</td><td>$email</td><td>".
		  "<a href=\"del_user.php?u=$uname\" onclick=\"return confirm('Do you want to delete user $uname?');\"><img src=\"del.jpg\" border=\"0\"/></a></td>";
	  echo "<td><a href=\"\"><img src=\"edit.jpg\" border=\"0\" width=\"25\" height=\"25\"/></a></td></tr>\n";
   }
   echo "</table><br>\n";
}
?>
<html>
<head>
<style type="text/css">
table, th, td {
border:1px solid black;
border-collapse:collapse;
}
td {
padding:5;
text-align:center;
}
ul {
list-style-type:none;
margin:0;
padding:0;
float:left;
}
li.header {
font-weight:bold;
text-indent:0;
}
li {
text-indent:10;
}
a {
text-decoration:none;
}
</style>
</head>
<body>
<? 
$session->displayAdminHeader(); 
?>
<br>
<br>
<br>
<ul>
<li class="header">Courses</li>
<li><a href="index.php?d=mc">Manage Courses</a></li>
<li><a href="index.php?d=ac">Add Course</a></li>
<li class="header">Users</li>
<li><a href="index.php?d=mu">Manage Users</a></li>
<li><a href="index.php?d=au">Add User</a></li>
<li><a href="index.php?d=af">Add User From File</a>&nbsp;&nbsp;</li>
</ul>
<?
if($_GET['d'] == 'mc') {
	$q = "SELECT * FROM courses NATURAL JOIN years";
	$result = $database->query($q);
	echo "<table align=\"left\">";
	echo "<tr><th>Title</th><th>Number</th><th>Section</th><th>Days</th><th>Time</th><th>Teacher</th><th>Semester</th><th>Delete</th><th>Edit</th></tr>";
	while($course = mysql_fetch_array($result)) {
		echo "<tr><td>";
		echo $course['title'];
		echo"</td><td>";
		echo $course['number'];
		echo "</td><td>";
		echo $course['section'];
		echo "</td><td>";
		echo $course['days'];
		echo "</td><td>";
		echo $course['time'];
		echo "</td><td>";
		echo $course['teacher'];
		echo "</td><td>";
		echo $course['year']." ".($course['semester'] == "s" ? "Spring" : "Fall");
		echo "</td><td>";
		$cid = $course['cid'];
		$title = $course['title'];
		echo "<a href=\"del_course.php?cid=$cid\" onclick=\"return confirm('Do you want to delete $title?');\"><img alt=\"Remove Course\" src=\"del.jpg\" border=\"0\"></a>";
		echo "</td><td>";
		echo "<a href=\"\"><img alt=\"Edit Course\" src=\"edit.jpg\" height=\"25\" width=\"25\" border=\"0\"></a>";
		echo "</td></tr>";
	}
	echo "</table>";
}
else if($_GET['d'] == 'mu') {
	displayUsers();	
}
?>
</body>
</html>
