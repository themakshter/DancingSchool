<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Student Payments</title>
<link rel="stylesheet" href="web.css" />
</head>

<body>  
<h1><a href "index.html"></a>Dancing School Website</h1>
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
	require ('connect_oo.php');
	include ('validations.php');
	$submitted = false;
	$emailErr = "";
	$paymentErr = "";
	$amountErr = "";
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errors = 0;
		checkEmail($_POST['email'],$emailErr,$errors);
		if($errors ==0){
			checkExistence($_POST['email'],$emailErr,$errors,2);
		}	
		if(!isset($_POST['Payment_type'])){
			$errors++;
			$paymentErr = "Please choose a method of payment";
		}else{
			$paymentErr = "";
		}
		if(empty($_POST['amount'])){
			$errors++;
			$amountErr = "Please enter amount you want to pay";
		}else{
			$amountErr = "";
		}	
		
	}

?>


<h1>Collect payment</h1>
<form method="post" action="payment.php" name="payment">

<dl>
<dt>Student email: </dt>
<dd><input type="text" name="email" value="<?php


if (isset ($_POST['email']) && !$submitted)
	echo $_POST['email'];
else
	echo "";
?>"/> <?php  echo "<font color=#ff0000>$emailErr</font>";?> </dd>
<dt>Payment type </dt>
<dd>
<select name="Payment type" size="4">
<option value="Mastercard">MasterCard</option>
<option value="Visa">Visa</option>
<option value="Cash">Cash</option>
</select><?php  echo "<font color=#ff0000>$paymentErr</font>"; ?>
</dd>
<dt>Amount:</dt>
<dd> <input type="text" name="amount" value="<?php


if (isset ($_POST['amount']) && !$submitted)
	echo $_POST['amount'];
else
	echo "";
?>">Pounds</input><?php  echo "<font color=#ff0000>$amountErr</font>";?></dd>
</dl>
<dt>Produce listing of payments by this student <input type="checkbox" name="listing"/> </dt>

<p>
<button value="press " name="SUBMIT">Submit</button>
<button name="Clear" type="reset">Reset</button>
</p> 
</form>

<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if($errors == 0){
				$email = $mysqli->real_escape_string($_POST['email']);
				$amount = $mysqli->real_escape_string($_POST['amount']);
				$type = strtolower($mysqli->real_escape_string($_POST['Payment_type']));
				$date = date("Y-m-d H:i:s"); 
				$stmt = $mysqli->prepare("INSERT INTO Payment(paymentdate,amount,student_email,PaymentType) VALUES(?,?,?,?);");
				$stmt->bind_param('ssss', $date , $amount , $email , $type);
				$OK = $stmt->execute();
        		if ($OK)
          		  	echo "<p>Payment has been made</p>";
        		else
          	  		echo "<p> Something went wrong :(</p>";

        		$stmt->close();
        		if(isset($_POST['listing'])){
        			$stmt2 = $mysqli->prepare("SELECT paymentdate,amount,PaymentType FROM Payment WHERE student_email = ?");
        			$stmt2->bind_param('s',$email);
        			$stmt2->execute();
        			$stmt2->bind_result($pDate,$pAmount,$pType);
        			echo "<p><b>Payment History</b><br><table border=1 cellpadding=3><tbody><tr><th>Payment Date</th><th>Amount(£)</th><th>Payment Type</th></tr>";
        			while($stmt2->fetch()){
  						echo "<tr><td>$pDate</td><td>$pAmount</td><td>$pType</td></tr>";      			
        			}
        			echo "</tbody></table></p>";
        			$stmt2->close();

        		}
        		$mysqli->close();
			}else{
				echo "<font color=#ff0000>Errors in form! Please check.</font>";
			}
	}

?>