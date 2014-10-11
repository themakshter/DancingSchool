<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dropping Students</title>
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
#connection established , errors initialised and validation checking
require ('connect_oo.php');
include ('validations.php');
$emailErr = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = 0;
		checkEmail($_POST['email'],$emailErr,$errors);
		if($errors ==0){
			checkExistence($_POST['email'],$emailErr,$errors,2);
		}	
}
?>


<h1>Drop Student</h1>
<p>The
email is supplied and the student is marked as left.
</p>

<form method="post" action="drop.php" name="dropStudent">
<p>Enter email<input type="text" name="email">
	<?php  echo "<font color=#ff0000>$emailErr</font>";?> </p>
<p>
<button value="press " name="SUBMIT">Exterminate</button>
<button name="Clear" type="reset">Reset</button>
</p>

</form>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		#query is no errors
		if ($errors == 0) {
			$email = $mysqli->real_escape_string($_POST['email']);
			$stmt = $mysqli->prepare("UPDATE Student SET StudentStatus = 'previous' WHERE email = ?");
			$stmt->bind_param("s",$email);
			$OK = $stmt->execute();
			if($OK)
				echo "Student has been successfully dropped";
			else
				echo "Something went wrong - Student could not be dropped. Please try again.";
			$stmt->close();
			$mysqli->close();
		} else {
			echo "<font color=#ff0000>Errors present! Please fix</font>";
		}
	}	


?>