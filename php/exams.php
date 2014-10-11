<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Examination Performance</title>
<link rel="stylesheet" href="web.css" />
</head>

<body>  
<h1>Dancing School Website</h1>
<nav>
    <ul>
        <li><a href="enrol.php">enrol student</a></li>
        <li><a href="drop.php">drop student</a></li>
        <li><a href="payment.php">collect payment</a></li>
        <li><a href="exams.php">record examination performance</a></li>
        <li><a href="record.php">show student record</a></li>
        <li><a href="listings.php">list students</a></li>
    </ul>
</nav>
</body></html>

<?php
	#files included
	require ('connect_oo.php');
	include ('validations.php');

	#errors initialised
	$hemailErr = "";
	$shemailErr = "";
	$dateErr = "";
	$styleErr = "";
	$markErr = "";
	$medalErr = "";
	#values
	$hemail="";
	$shemail="";
	$date="";
	$mark="";

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = 0;

	//Hemail
		checkEmail($_POST['hemail'],$hemailErr,$errors);
		if($errors ==0){
			checkExistence($_POST['hemail'],$hemailErr,$errors,2);
		}	
	//Shemail
		checkEmail($_POST['shemail'],$shemailErr,$errors);
		if($errors ==0){
			if($_POST['hemail'] == $_POST['shemail']){
				$errors++;
				$shemailErr="Both dancers cannot be the same student!";
			}
		}
		if($errors ==0){
			checkExistence($_POST['shemail'],$shemailErr,$errors,2);
		}	
	//date
		if(!checkTheDate($_POST['date'])){
        	$errors++;
        	$dateErr="Please enter valid date in format YYYY-MM-DD";
   		 }else{
        	$dateErr="";
    	}
    
    //style
    	if(!isset($_POST['style'])){
			$errors++;
			$styleErr = "Please choose the dance style";
		}else{
			$styleErr = "";
		}
    //level
		if(!isset($_POST['level'])){
			$errors++;
			$medalErr = "Please choose the medal level";
		}else{
			$medalErr = "";
		}
    //Mark
		if(empty($_POST['mark'])){
			$errors++;
			$markErr = "Please choose the marks";
		}else if($_POST['mark'] > 100){
			$errors++;
			$markErr = "Marks cannot be more than 100!";
		}else{
			$markErr = "";
		}
		if($errors > 1){
			$hemail=$_POST['hemail'];
			$shemail=$_POST['shemail'];
			$date=$_POST['date'];
			$mark=$_POST['mark'];
		}
	}
?>

<h1>Record examination performance</h1>

<form method="post" action="exams.php">
<dl>
<dt>Man. (Email of student dancing male steps) </dt>
<dd><input type = "text" name="hemail" value="<?php  echo $hemail; ?>"/><?php echo "<font color=#ff0000>$hemailErr</font>";?></dd>
<dt>Woman. (dances female steps) </dt>
<dd><input type="text" name="shemail" value="<?php  echo $shemail; ?>"/ ><?php echo "<font color=#ff0000>$shemailErr</font>";?> 
</dl>
<dt>Date of examination </dt>
<dd><input type="text" name="date" value="<?php  echo $date; ?>"/><?php echo "<font color=#ff0000>$dateErr</font>";?> </dd>
<dt>Style</dt>
<dd>
<select name="style" size="4">
<option value="BL">Ballroom</option>
<option value="LT">Latin American</option>
</select><?php echo "<font color=#ff0000>$styleErr</font>";?> 
</dd>
<dt>Level</dt>
<dd>
<select name="level">
<option value = "default">Select...</option>
<option value="bronze">Bronze</option>
<option value="silver">Silver</option>
<option value="gold">Gold</option>
</select><?php echo "<font color=#ff0000>$medalErr</font>";?> 
</dd>
<dt>Mark</dt>
<dd><input type="number" name="mark" value="<?php  echo $mark; ?>"/><?php echo "<font color=#ff0000>$markErr</font>";?> 
<dd>
<p>
<button value="press " name="SUBMIT">Submit</button>
<button name="Clear" type="reset">Reset</button>
</p>
</form>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		#if no errors, add values to database
		if($errors ==0){
			$hemail = $mysqli->real_escape_string($_POST['hemail']);
			$shemail = $mysqli->real_escape_string($_POST['shemail']);
			$date = $mysqli->real_escape_string($_POST['date']);
			$level = strtolower($mysqli->real_escape_string($_POST['level']));
			$style = $mysqli->real_escape_string($_POST['style']);
			if($style == "BL"){
				$style = "ballroom";
			}elseif($style == "LT"){
				$style = "latin";
			}
			$mark = $mysqli->real_escape_string($_POST['mark']);
			$stmt = $mysqli->prepare("INSERT INTO Examination(examDate,student1_email,student2_email,MedalLevel,mark,DanceStyle) VALUES(?,?,?,?,?,?);");
			$stmt->bind_param('ssssss',$date,$hemail,$shemail,$level,$mark,$style);
			$OK = $stmt->execute();
			if($OK){
				echo "Examination has been added";
			}else{
				echo "Something went wrong. Please try again";
			}
			$stmt->close();
			$mysqli->close();
			$hemail="";
			$shemail="";
			$date="";
			$mark="";
		}else{
			echo "<font color=#ff0000>One of more errors present in form. Please check.</font>";
		}

	}
?>