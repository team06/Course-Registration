<?
include("../include/session.php");
if(!$session->isAdmin()){
   header("Location: ../login.php");
}
?>
<html>
<frameset cols="25%, 75%">
<frame src="user_list.php"/>
<frame src="tools.php"/>
</frameset>
</html>
