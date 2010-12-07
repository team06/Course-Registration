<?
include("include/session.php");


global $database, $session, $form;
$uname = $_SESSION['username'];
$cid = $_POST['cid'];

$result = $database->query("SELECT * FROM signups WHERE username='$uname' AND cid=$cid");
if(mysql_num_rows($result) > 0) {
	$form->setError("signup", "You are already registered for that class.");
}
$result = $database->query("SELECT * FROM signups WHERE username='$uname'");
if(mysql_num_rows($result) > 1) {
	$form->setError("signup", "You are already registered for 2 classes.");
}
else if(mysql_num_rows($result) > 0) {
	$timef = time();
	$check = mysql_fetch_array($result);
	$diff = $timef - $check['time'];
	if($diff < (2 * 60 * 60)) {
		$n_time = $check['time']+(2*60*60);
		$n_time = date('h:i', $n_time);
		$form->setError("signup", "You are not allowed to signup for a class. 
			Please wait until after ".$n_time." to signup for another course.");
	}
}
if($form->num_errors > 0) {
	$_SESSION['value_array'] = $_POST;
	$_SESSION['error_array'] = $form->getErrorArray();
	header("Location: listing.php");
}
else {
	$time = time();
	$q = "UPDATE seats SET available=available-1 WHERE cid=$cid AND available > 0";
	$result = $database->query($q);
	if(mysql_affected_rows() == 1) {
		$database->query("INSERT INTO signups VALUES($cid,'$uname',$time)");
		echo '<div align="center">';
		echo '<h1>Registration for course successful</h1>';
		echo '<h3>Please check back in 2 hours to sign up for your next course.';
		echo ' You will be redirected back to the course listings.</h3>';
		echo '</div>';
		header("Refresh: 4; URL=listing.php");
	}
	else
	{
		$form->setError("signup", "* Class is full.");
		$_SESSION['value_array'] = $_POST;
		$_SESSION['error_array'] = $form->getErrorArray();
		header("Location: listing.php");
	}
}
?>
