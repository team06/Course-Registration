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
   echo "<table width=\"40%\" class=\"users\">\n";
   echo "<tr><td class=\"users\"><b>Username</b></td><td class=\"users\"><b>Level</b></td><td class=\"users\"><b>Email</b></td><td class=\"users\"><b>Delete</b></td><td class=\"users\"><b>Edit</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $ulevel = mysql_result($result,$i,"userlevel") == "1" ? "User" : "Admin";
      $email  = mysql_result($result,$i,"email");
      $time   = date('Y-m-d', mysql_result($result,$i,"timestamp"));

	  echo "<tr><td class=\"users\">$uname</td><td class=\"users\">$ulevel</td><td class=\"users\">$email</td><td class=\"users\">".
		  "<a href=\"del_user.php?u=$uname\" onclick=\"return confirm('Do you want to delete user $uname?');\"><img src=\"del.jpg\" border=\"0\"/></a></td>";
	  echo "<td class=\"users\"><a href=\"\"><img src=\"edit.jpg\" border=\"0\" width=\"25\" height=\"25\"/></a></td></tr>\n";
   }
   echo "</table><br>\n";
}
?>
<html>
<head>
<style type="text/css">
table.courses1
{
position:relative;
}
table.courses2
{
position:relative;
}
table.courses3
{
position:relative;
}
table.users
{
position:relative;
left:20%;
}
table.users, td.users, th.users 
{
border:1px solid black;
border-collapse:collapse;
}
td.users 
{
text-align:center;
}
.sidebar
{
position:relative;
top:25%;
left:17%;
}
ul 
{
list-style-type:none;
margin:0;
padding:0;
float:left;
}
li.header 
{
font-weight:bold;
text-indent:0;
}
li 
{
text-indent:10;
}
a 
{
text-decoration:none;
}

</style>
<script type="text/javascript">
	function help() {
		var f = document.getElementsByName("myform")[0];
		var temp = document.createElement("input");
		temp.setAttribute("type", "hidden");
		temp.setAttribute("name", "desc");
		temp.setAttribute("value", document.getElementById("desc").value);
		if(confirm('Are you sure you want to add this course?')) {
			f.appendChild(temp);
			return true;
		}
		return false;
	}
</script>
</head>
<body>
<div align="center">
<tr><td>
<? 
$session->displayAdminHeader(); 
?>
</td></tr>
</div>
<br>
<br>
<br>
<ul class="sidebar">
<li class="header">Courses</li>
<li><a href="index.php?d=mc">Manage Courses</a></li>
<li><a href="index.php?d=ac">Add Course</a></li>
<li class="header">Users</li>
<li><a href="index.php?d=mu">Manage Users</a></li>
<li><a href="index.php?d=au">Add User</a></li>
<li><a href="index.php?d=af">Add Users From File</a>&nbsp;&nbsp;</li>
</ul>
<?
if($_GET['d'] == 'mc') {
	$q = "SELECT * FROM courses NATURAL JOIN years";
	$result = $database->query($q);
	echo "<table class=\"users\">";
	echo "<tr><th class=\"users\">Title</th><th class=\"users\">Number</th><th class=\"users\">Time</th><th class=\"users\">Semester</th><th class=\"users\">Delete</th><th class=\"users\">Edit</th></tr>";
	while($course = mysql_fetch_array($result)) {
		echo "<tr><td class=\"users\">";
		echo $course['title'];
		echo"</td><td class=\"users\">";
		echo $course['number'];
		echo "</td><td class=\"users\">";
		echo $course['time'];
		echo "</td><td class=\"users\">";
		echo $course['year']." ".($course['semester'] == "s" ? "Spring" : "Fall");
		echo "</td><td class=\"users\">";
		$cid = $course['cid'];
		$title = $course['title'];
		echo "<a href=\"del_course.php?cid=$cid\" onclick=\"return confirm('Do you want to delete $title?');\"><img src=\"del.jpg\" border=\"0\"></a>";
		echo "</td><td class=\"users\">";
		echo "<a href=\"../course.php?cid=$cid\"><img alt=\"Edit Course\" src=\"edit.jpg\" height=\"25\" width=\"25\" border=\"0\"></a>";
		echo "</td></tr>";
	}
	echo "</table>";
}
else if($_GET['d'] == 'mu') {
	displayUsers();	
}
else if($_GET['d'] == 'au') {
	echo '<form action="adminprocess.php" method="POST">';
	echo '<table align="center"><tr><th colspan=2>New User Information</th></tr>';
	echo '<tr><td>Username:&nbsp;&nbsp;</td><td><input type="text" name="uname"/></td></tr>';
	echo '<tr><td>Password:&nbsp;&nbsp;</td><td><input type="password" name="pass"/></td></tr>';
	echo '<tr><td>E-mail:&nbsp;&nbsp;</td><td><input type="text" name="email"/></td></tr></table>';
	echo '<p align="center"><input type="submit" value="Add"/>';
	echo '<input type="hidden" name="subadduser" value="1"/></p></form>';
}
else if($_GET['d'] == 'ac') {
	include("add_course.php");
}
?>
</body>
</html>
