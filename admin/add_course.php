<?php

if(!$session->isAdmin()) {
	header("Location: ../login.php");
}

function displayHours($where) {
	global $form;
for($i = 8;$i < 13;$i++) {
	if($form->value("$where") != $i) 
		echo "<option value=\"".$i."\">".$i."</option>\n";
	else
		echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
}
for($i = 1;$i < 8;$i++) {
	if($form->value("$where") != ($i+12))
		echo "<option value=\"".($i+12)."\">".$i."</option>\n";
	else
		echo "<option value=\"".($i+12)."\" selected=\"selected\">".$i."</option>\n";
}
}

function displayMinutes($where) {
	global $form;
	for($i = 0;$i < 60;$i+=5) {
	if($i < 10){
		if($form->value("$where") != $i) {
			echo "<option value=\"0".$i."\">0".$i."</option>\n";
		}
		else {
			echo "<option value=\"0".$i."\" selected=\"selected\">0".$i."</option>\n";
		}
	} else {
		if($form->value("$where") != $i) {
			echo "<option value=\"".$i."\">".$i."</option>\n";
		}
		else {
			echo "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
		}

	}
}
}

?>

<?
if($form->num_errors > 0) {

	echo "<div align=\"center\"><font size=\"4\" color=\"#ff0000\">"
		."!*** Error with request, please fix</font><br><br></div>";
}
?>
<form name="myform" action="adminprocess.php" method="POST" enctype="multipart/form-data" onsubmit="return help()">
<table class="courses1" align="center" width="50%" cellspacing="5">
<tr><td><input type="hidden" name="subaddcourse" value="1"/><? echo $form->error("cname");?>Course Title:</td><td><input type="text" size="30" name="cname" value="<?echo $form->value('cname');?>"/></td></tr>
<tr><td></td><td></td></tr>
<tr><td><? echo $form->error("cnumber");?>Course Number:</td><td><input type="text" size="7" name="cnumber" value="<?echo $form->value('cnumber');?>"/>&nbsp;&nbsp;<font size=1>i.e. HIST101</font><td></tr>
<tr><td><? echo $form->error("csection");?>Section Number:<br><font size=1></font></td><td><input type="text" size="2" name="csection" value="<?echo $form->value('csection');?>"/></td></tr>
<tr><td><? echo $form->error("cteacher");?>Teacher:</td><td><input type="text" name="cteacher" value="<?echo $form->value('cteacher')?>"/></td></tr>
<tr><td colspan=2><hr></td></tr>
<tr><td><? echo $form->error("cday");?>Class Day(s):</td><td>
M<input type="checkbox" name="cm" value="M"<?if($form->value('cm') == "M") echo "checked=\"yes\"";?>/>
T<input type="checkbox" name="ct" value="T"<?if($form->value('ct') == "T") echo "checked=\"yes\"";?>/>
W<input type="checkbox" name="cw" value="W"<?if($form->value('cw') == "W") echo "checked=\"yes\"";?>/>
R<input type="checkbox" name="cr" value="R"<?if($form->value('cr') == "R") echo "checked=\"yes\"";?>/>
F<input type="checkbox" name="cf" value="F"<?if($form->value('cf') == "F") echo "checked=\"yes\"";?>/></td></tr>
<tr><td>Start Time:</td><td>
<select name="s_hour">
<?
displayHours('s_hour');
?>
</select>
<select name="s_min">
<?
displayMinutes('s_min');
?>
</select>
</td></tr>
<tr><td><? echo $form->error("stime"); ?>End Time:</td><td>
<select name="e_hour">
<?
displayHours('e_hour');
?>
</select>
<select name="e_min">
<?
displayMinutes('e_min');
?>
</select>
</td></tr>
<tr><td></td></tr>
<tr><td><? echo $form->error("lday"); ?>Lab Day(s):</td><td>
M<input type="checkbox" name="lm" value="M"<?if($form->value('lm') == "M") echo "checked=\"yes\"";?>/>
T<input type="checkbox" name="lt" value="T"<?if($form->value('lt') == "T") echo "checked=\"yes\"";?>/>
W<input type="checkbox" name="lw" value="W"<?if($form->value('lw') == "W") echo "checked=\"yes\"";?>/>
R<input type="checkbox" name="lr" value="R"<?if($form->value('lr') == "R") echo "checked=\"yes\"";?>/>
F<input type="checkbox" name="lf" value="F"<?if($form->value('lf') == "F") echo "checked=\"yes\"";?>/>
None<input type="checkbox" name="ln" value="N"<?if($form->value('ln') == "N") echo "checked=\"yes\"";?>/></td></tr>
<tr><td>Start Time:</td><td>
<select name="ls_hour">
<?
displayHours('ls_hour');
?>
</select>
<select name="ls_min">
<?
displayMinutes('ls_min');
?>
</select>
</td></tr>
<tr><td><? echo $form->error("ltime"); ?>End Time:</td><td>
<select name="le_hour">
<?
displayHours('le_hour');
?>
</select>
<select name="le_min">
<?
displayMinutes('le_min');
?>
</select>
<tr><td colspan=2><hr></td></tr>
</td></tr>
<tr><td>Credits:</td><td><select name="credits">
<?
for($i = 1;$i < 8;$i++) {
	if($form->value('credits') == $i) {
		echo "<option value=\"".$i."\" selected=\"selected\">".$i.".0</option>\n";
	} else {
		echo "<option value=\"".$i."\">".$i.".0</option>\n";
	}
}
?>
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
	if($form->value('year') == $date+$i) echo "\" selected=\"selected\">";
	else echo "\">";
	echo $date+$i;
	echo "</option>";
}
?>
</select>
<select name="semester">
<option value="s"<?if($form->value('time') == "s") echo "selected=\"selected\"";?>>Spring</option>
<option value="f"<?if($form->value('time') == "f") echo "selected=\"selected\"";?>>Fall</option>
</select>
</td></tr>
</table>
<br/>
<table class="courses2" align="center" width="%30">
<tr>
<td><?echo $form->error('desc');?>Course Description:</td>
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
<table class="courses1" align="center" width="%20" cellspacing="5">
<tr><td><?echo $form->error("max");?>Course Max:</td><td><input type="text" size="2" name="max" value="<?echo $form->value("max");?>"/></td></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<tr></tr>
<!--<tr><td>Video:</td><td><input type="file" name="video"/></td></tr>
<tr><td>Syllabus:</td><td><input type="file" name="syllabus"/></td></tr>
<tr></tr>

<tr><td colspan=2><hr></td></tr>-->
<tr><td colspan=2><div align="center"><input type="submit" value="Add Course"/></div></td></tr>
</table>
</form>
