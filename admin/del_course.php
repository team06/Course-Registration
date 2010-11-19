<?
include("../include/session.php");
if(!$session->isAdmin()){
	header("Location: ../login.php");
}
$tables = array("courses","lab","years","video");
$toDel = $_GET['cid'];
foreach($tables as $table) {
	$q = "DELETE FROM $table WHERE cid=$toDel";
	$database->query($q);
}
header("Location: index.php?d=mc");
?>
