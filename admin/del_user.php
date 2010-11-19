<?
include("../include/session.php");
if(!$session->isAdmin()){
	header("Location: ../login.php");
}
$toDel = $_GET['u'];
$q = "DELETE FROM users WHERE username='$toDel'";
echo $q;
$database->query($q);
header("Location: index.php?d=mu");
?>
