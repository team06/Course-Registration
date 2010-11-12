<?php
include("../include/session.php");

if(!$session->isAdmin()) {
	header("Location: ../login.php");
}
?>
<html>
<head>
<script type="text/javascript">
	function help() {
		var f = document.getElementsByName("myform")[0];
		var temp = document.createElement("input");
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
<h1>Course Add Form</h1>
<?
if($form->num_errors > 0) {

	echo "<font size=\"4\" color=\"#ff0000\">"
		."!*** Error with request, please fix</font><br><br>";
}
?>
<form name="myform" action="adminprocess.php" method="POST" enctype="multipart/form-data "onsubmit="return help()">
<table align="center" width="50%" cellspacing="5">
<tr><td><? echo $form->error("cname");?>Course Title:</td><td><input type="text" size="30" name="cname" value="<?echo $form->value('cname');?>"/></td></tr>
<tr><td></td><td></td></tr>
<tr><td><? echo $form->error("cnumber");?>Course Number:</td><td><input type="text" size="7" name="cnumber" value="<?echo $form->value('cnumber');?>"/>&nbsp;&nbsp;<font size=1>i.e. HIST101</font><td></tr>
<tr><td><? echo $form->error("csection");?>Section Number:<br><font size=1></font></td><td><input type="text" size="2" name="csection" value="<?echo $form->value('csection');?>"/></td></tr>
<tr><td><? echo $form->error("cteacher");?>Teacher:</td><td<input type="text" name="cteacher" value="<?echo $form->value('cteacher')?>"/></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td><? echo $form->error("cday");?>Class Day(s):</td><td>
M<input type="checkbox" name="cday[]" value="M"/>
T<input type="checkbox" name="cday[]" value="T"/>
W<input type="checkbox" name="cday[]" value="W"/>
R<input type="checkbox" name="cday[]" value="R"/>
F<input type="checkbox" name="cday[]" value="F"/></td></tr>
<tr><td>Start Time:</td><td>
<select name="s_hour">
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">1</option>
<option value="14">2</option>
<option value="15">3</option>
<option value="16">4</option>
<option value="17">5</option>
<option value="18">6</option>
<option value="19">7</option>
</select>
<select name="s_min">
<option value="00">00</option>
<option value="05">05</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="35">35</option>
<option value="40">40</option>
<option value="45">45</option>
<option value="50">50</option>
<option value="55">55</option>
</select>
</td></tr>
<tr><td><? echo $form->error("stime"); ?>End Time:</td><td>
<select name="e_hour">
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">1</option>
<option value="14">2</option>
<option value="15">3</option>
<option value="16">4</option>
<option value="17">5</option>
<option value="18">6</option>
<option value="19">7</option>
</select>
<select name="e_min">
<option value="00">00</option>
<option value="05">05</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="35">35</option>
<option value="40">40</option>
<option value="45">45</option>
<option value="50">50</option>
<option value="55">55</option>
</select>
</td></tr>
<tr><td></td></tr>
<tr><td><? echo $form->error("lday"); ?>Lab Day(s):</td><td>
M<input type="checkbox" name="lday[]" value="M"/>
T<input type="checkbox" name="lday[]" value="T"/>
W<input type="checkbox" name="lday[]" value="W"/>
R<input type="checkbox" name="lday[]" value="R"/>
F<input type="checkbox" name="lday[]" value="F"/>
None<input type="checkbox" name="lday[]" value="N"/></td></tr>
<tr><td>Start Time:</td><td>
<select name="ls_hour">
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">1</option>
<option value="14">2</option>
<option value="15">3</option>
<option value="16">4</option>
<option value="17">5</option>
<option value="18">6</option>
<option value="19">7</option>
</select>
<select name="ls_min">
<option value="00">00</option>
<option value="05">05</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="35">35</option>
<option value="40">40</option>
<option value="45">45</option>
<option value="50">50</option>
<option value="55">55</option>
</select>
</td></tr>
<tr><td><? echo $form->error("ltime"); ?>End Time:</td><td>
<select name="le_hour">
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">1</option>
<option value="14">2</option>
<option value="15">3</option>
<option value="16">4</option>
<option value="17">5</option>
<option value="18">6</option>
<option value="19">7</option>
</select>
<select name="le_min">
<option value="00">00</option>
<option value="05">05</option>
<option value="10">10</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="25">25</option>
<option value="30">30</option>
<option value="35">35</option>
<option value="40">40</option>
<option value="45">45</option>
<option value="50">50</option>
<option value="55">55</option>
</select>
<tr><td colspan=2><hr></td></tr>
</td></tr>
<tr><td>Credits:</td><td><select name="credits">
<option value="1">1.0</option>
<option value="2">2.0</option>
<option value="3">3.0</option>
<option value="4">4.0</option>
<option value="5">5.0</option>
<option value="6">6.0</option>
<option value="7">7.0</option>
</select></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td>Semester:</td><td>
<select name="year">
<?
$date = getdate();
$date = $date['year'];

for($i = 0;$i <= 5;$i++){
	echo "<option value=\"";
	echo $date+$i;
	echo "\">";
	echo $date+$i;
	echo "</option>";
}
?>
</select>
<select name="time">
<option value="s">Spring</option>
<option value="f">Fall</option>
</select>
</td></tr>
</table>
<br/>
<table align="center" width="%30">
<tr>
<td>Course Description:</td>
</tr>
<tr>
<td>
<textarea id="desc" rows="10" cols="60" >
<?if($form->value('desc')==""){
	echo "Enter a description of the course.";
} else {
	echo $form->value('desc');
}
?>
</textarea>
</td>
</tr>
</table>
<br/>
<table align="center" width="%40" cellspacing="5">
<tr><td>Video:</td><td><input type="file" name="video"/></td></tr>
<tr></tr>
<input type="hidden" name="subaddcourse" value="1"/>
<tr><td colspan=2><hr></td></tr>
<tr><td colspan=2><div align="center"><input type="submit" value="Add Course"/></div></td></tr>
</table>
</form>
</body>
</html>
