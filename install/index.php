<?
include("../include/database.php");
$index = 0;
$queries = array();
$lines = file("database.sql");
if(!$lines) {
	echo 'Failed to load queries from file (database.sql).';
	die();
}
foreach($lines as $line) {
	if(empty($line)) {
		continue;
	}
	$line = trim($line);
	if(count($queries) <= $index) {
		array_push($queries, $line."\n");
	} else {
		$queries[$index] .= $line."\n";	
	}
	// Create a new array item for each query:
	if(substr($line, -1) == ';') {
		$index++;
	}
}
foreach($queries as $sql) {
	$database->query($sql);
}
echo "Tables Successfully created. Please delete this install folder.<br>";
echo " Please follow this link to finish installing. <a href=\"../chat/install.php\">Link</a><br>";
echo "Default login is<br> user: admin<br>password: admin<br> Please change the password after logging in.<br>";
?>
