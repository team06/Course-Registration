<?
include("include/session.php");

$cid = $_GET['cid'];

$q = "DELETE FROM signups WHERE username='".$session->username."'";
$database->query($q);
$q = "UPDATE seats SET available=available+1 WHERE cid=$cid";
$database->query($q);

header("Location: login.php");
?>
