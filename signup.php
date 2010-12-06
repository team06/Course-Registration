<?
include("include/session.php");


global $database, $session, $form;
$uname = $_SESSION['username'];
$cid = $_POST['cid'];

$result = $database->query("SELECT * FROM signups WHERE username='$uname'");
if(mysql_num_rows($result) > 2) {
	$form->setError("signup", "You are already registered for 2 classes.");
}
else if(mysql_num_rows($result) > 1) {
	$timef = time();
	$check = mysql_fetch_array($result);
	$diff = $timef - $check['time'];
	if($diff < (2 * 60 * 60)) {
		$n_time = $check['time']+(2*60*60);
		$n_time = date('h:i', $n_time);
		$form->setError("signup", "You are not allowed to signup for a class. 
			Please wait until ".$n_time." to signup for another course.");
	}
}
$result = $database->query("SELECT * FROM signups WHERE username='$uname' AND cid=$cid");
if(mysql_num_rows($result) > 0) {
	$form->setError("signup", "You are already registered for that class.");
}
if($form->num_errors > 0) {
	header("Location: listing.php");
}
else {
	$time = time();
	$q = "UPDATE seats SET available=available-1 WHERE cid=$cid AND available > 0";
	$result = $database->query($q);
	if(mysql_affected_rows() == 1) {
		$database->query("INSERT INTO signups VALUES($cid,'$uname',$time)");
	}
	echo "$uname<br>";
	echo "$cid<br>";
	echo "$time<br>";
}
?>
