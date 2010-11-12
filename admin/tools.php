<?

include("../include/session.php");

if(!$session->isAdmin()){
   header("Location: ../login.php");
}
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
	</script>
</head>
<body>
<div align="center">
<h1>Admin Center</h1>
<font size="4">Logged in as <b><? echo $session->username; ?></b></font><br><br>
Back to [<a target="top" href="../login.php">Main Page</a>]<br><br>
<?
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}
?>
</div>
</table>
<table align="center" border="0" cellspacing="10" cellpadding="5">
<tr>
<th><h3>Update User Level</h3></th>
<th><h3>Delete User</h3></th>
<th><h3>Add User from File</h3></th>
</tr>
<tr><td>
<?
/**
 * Update User Level
 */
?>
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
<tr>
<td>
<input type="hidden" name="subupdlevel" value="1">
<input type="submit" value="Update Level">
</td></tr>
</form>
</table>
</td>
<td valign="bottom">
<?
/**
 * Delete User
 */
?>
<? echo $form->error("deluser"); ?>
<form action="adminprocess.php" method="POST" onSubmit="return confirm('Do you wish to remove this user from the system? This is not reversable.')">
Username:<br>
<input type="text" name="deluser" maxlength="30" value="<? echo $form->value("deluser"); ?>">
<input type="hidden" name="subdeluser" value="1"><br/>
<input type="submit" value="Delete User">
</form>
</td>
<td>
<?
/**
 * Add users from file
 */
?>
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
<td colspan="3">
<hr>
</td>
</tr>
<tr>
<td align="center" colspan="3">
<h3>Add Course</h3>
<form action="add_course.php">
<input type="submit" value="Add Course"/>
</form>
</td>
</tr>
</table>
</body>
</html>

