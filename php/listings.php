<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Listings</title>
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

<h1>List students</h1>

<form method="post" action="listings.php">
<select name ="criterion">
<option value ="c" >current students</option>
<option value="l">previous students</option>
</select>
<p>
	<button value="press " name="SUBMIT">Submit</button>
	<button name="Clear" type="reset">Reset</button>
</p> 
</form>
<?php
	#connection
	require ('connect_oo.php');
	$student = "";
	if(isset($_POST['criterion'])){
	$selectedOption = ($_POST['criterion']);
	if($selectedOption == 'c'){
		$student = "current";
	}elseif($selectedOption == 'l'){
		$student = "previous";
	}
	#display all the details
	$stmt = $mysqli->prepare("SELECT * FROM Student WHERE StudentStatus = ?");
	$stmt->bind_param("s",$student);
	$stmt->execute();
	$stmt->bind_result($name,$date,$gender,$email,$status);
	#form of table
	echo "<table border=1 cellpadding=3><tbody><tr><th>Name</th><th>DOB</th><th>Gender</th><th>Email</th><th>Status</th></tr>";
    while ($stmt->fetch()) {
        echo "<tr><td>$name</td><td>$date</td><td>$gender</td><td>$email</td><td>$status</td></tr>";
    }
    echo "</tbody></table>";
    /* close statement */
    $stmt->close();
	/* close connection */
	$mysqli->close();
	}
?>


