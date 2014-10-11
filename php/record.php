<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Exam Record</title>
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
	#connection
	require ('connect_oo.php');
	include ('validations.php');
	$emailErr = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	#validation
	$errors = 0;
		checkEmail($_POST['email'],$emailErr,$errors);
		if($errors ==0){
			checkExistence($_POST['email'],$emailErr,$errors,2);
		}	
}

?>

<h1>Student record query</h1>
<p>
Given a student email return the name and details of all the examinations they have taken.
In this list show the name of the partner, the style, level and mark.
</p>

<form method="post" action="record.php">

Email: <input type="text" name="email" /><?php echo "<font color=#ff0000>$emailErr</font>";	?>

<p>
<button value="press " name="SUBMIT">Submit</button>
<button name="Clear" type="reset">Reset</button>
</p>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if ($errors == 0) {
		$email = $mysqli->real_escape_string($_POST['email']);
		#name of student found
		$stmt3 = $mysqli->prepare("SELECT name FROM Student WHERE email = ?");
		$stmt3->bind_param('s',$email);
		$stmt3->execute();
		$stmt3->bind_result($studName);
		while($stmt3->fetch()){
			$sName = $studName;
		}
		$stmt3->close();
		echo "<p>Exam record for <b>$sName</b><br><br>";
		//male steps
		#query to combine the two tables to show the result
		$stmt = $mysqli->prepare("SELECT name, examDate, DanceStyle, MedalLevel, Mark
                                        FROM Examination, Student
                                        WHERE Examination.student1_email = ? AND Examination.student2_email = Student.Email;");
		$stmt->bind_param('s',$email);
		$stmt->execute();
		$stmt->bind_result($name,$date,$medal,$mark,$style);
		echo "While dancing male steps: <br>";
		echo "<table border=1 cellpadding=3><tbody><tr><th>Examination Date</th><th>Partner</th><th>Level</th><th>Mark</th><th>Dance Style</th></tr>";
		while($stmt->fetch()){
			echo "<p><tr><td>$date</td><td>$name</td><td>$medal</td><td>$mark</td><td>$style</td></tr>";
		}
		echo "</tbody></table><br>";

		$stmt->close();
		//female steps
		$stmt2 = $mysqli->prepare("SELECT name, examDate, DanceStyle, MedalLevel, Mark
                                        FROM Examination, Student
                                        WHERE Examination.student2_email = ? AND Examination.student1_email = Student.Email;");
		$stmt2->bind_param('s',$email);
		$stmt2->execute();
		$stmt2->bind_result($name,$date,$medal,$mark,$style);
		echo "While dancing female steps:";
		echo "<table border=1 cellpadding=3><tbody><tr><th>Examination Date</th><th>Partner</th><th>Level</th><th>Mark</th><th>Dance Style</th></tr>";
		while($stmt2->fetch()){
			echo "<p><tr><td>$date</td><td>$name</td><td>$medal</td><td>$mark</td><td>$style</td></tr>";
		}
		echo "</tbody></table></p>";
		$stmt2->close();
		$mysqli->close();
	} else {
		echo "<font color=#ff0000>Errors present! Please fix</font>";
	}
}
?>
