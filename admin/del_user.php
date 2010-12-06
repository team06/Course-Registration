<?
include("../include/session.php");
if(!$session->isAdmin()){
	header("Location: ../login.php");
}
$toDel = $_GET['u'];
if($toDel != "admin") {
	$q = "DELETE FROM users WHERE username='$toDel'";
	$database->query($q);
	$q = "DELETE FROM signups WHERE username='$toDel'";
	$database->query($q);
}
header("Location: index.php?d=mu");
?>
