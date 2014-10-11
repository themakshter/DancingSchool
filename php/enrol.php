<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Enrol Student</title>
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
    require ('connect_oo.php'); #connect to database
    include ('validations.php');    #some validation functions
    
    #errors initialised
    $emailErr = "";
    $nameErr = "";
    $email2Err = "";
    $genderErr = "";
    $dateErr = "";
    #values initialised
    $name = "";
    $date = "";
    $email = "";
    $email2 = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    #deal with errors 
    $errors = 0;

    #name is empty
    if (empty ($_POST['Name'])) {
        $errors++;
        $nameErr = "Please enter a name";
    } else {
        $nameErr = "";
    }
    #check validity of date
    if(!checkTheDate($_POST['date'])){
        $errors++;
        $dateErr="Please enter valid date in format YYYY-MM-DD";
    }else{
        $dateErr="";
    }
    #check if gender has been selected
    if (!isset ($_POST['gender'])) {
        $errors++;
        $genderErr = "  Please choose a gender";
    } else {
        $genderErr = "";
    }
    #validation checking for email
    checkEmail($_POST['email'],$emailErr,$errors);
    #if email is valid, check if email already not present in database
    if($errors ==0){
            checkExistence($_POST['email'],$emailErr,$errors,1);
        }
    #check confirming email
    if (empty ($_POST['email2'])) {
        $errors++;
        $email2Err = "Please confirm your email";
    }
    #check if emails are equal
    elseif (!($_POST['email'] == $_POST['email2'])) {
        $errors++;
        $email2Err = "E-mails do not match!";
    } else {
        $email2Err = "";
    }
    #if errors are present, don't reset the values
    if($errors > 0){
        $name = $_POST['Name'];
        $date = $_POST['date'];
        $email = $_POST['email'];
        $email2 = $_POST['email2'];
    }

}
?>


<h1> Enrol Student</h1>
<p>
The name, date of birth and gender of the student are recorded.The email is
supplied and it is assumed that every student has a distinct email.
</p>

<form method="post" action="enrol.php" name="enrol">
<dl>
<dt>Name:</dt>
<dd> <input type="text" name="Name" value="<?php  echo $name; ?>" >
    <?php echo "<font color=#ff0000>$nameErr</font>"; ?> </dd>
<dt>Date of birth:</dt>
<dd><input type ="text" name="date" value="<?php  echo $date; ?>" >
    <?php echo "<font color=#ff0000>$dateErr</font>"; ?></dd>
<dt>Gender: </dt>
<dd>Male <input type="radio" name ="gender" value="male">
Female <input type="radio" name ="gender" value="female">
 <?php echo "<font color=#ff0000>$genderErr</font>";  ?></dd>
<dt>Email</dt>
<dd> <input type="text" name="email" value="<?php  echo $email;?>" >
<?php  echo "<font color=#ff0000>$emailErr</font>" ;?> </dd>
<dt>Repeat email</dt>
<dd> <input type="text" name="email2" value="<?php  echo $email2; ?>" > 
    <?php echo "<font color=#ff0000>$email2Err</font>"; ?></dd>
</dl>

<p>
<button value="press " name="SUBMIT">Submit</button>
<button name="Clear" type="reset">Reset</button><?php

if (isset ($_POST['Clear'])) {
    $reset = true;
}
?>
</p> 

</form>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        #if no errors, write to database
        if ($errors == 0) {
        #escape used for security
        $name = $mysqli->real_escape_string($_POST['Name']);
        $date = $mysqli->real_escape_string($_POST['date']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $email2 = $mysqli->real_escape_string($_POST['email2']);
        $status = "current";
        $gender_selected = $mysqli->real_escape_string($_POST['gender']);
        if ($gender_selected == 'male') {
            $gender = "M";
        }
        elseif ($gender_selected == 'female') {
            $gender = "F";
        }
        #query
        $stmt = $mysqli->prepare("INSERT INTO Student VALUES(?,?,?,?,?);");
        $stmt->bind_param('sssss', $name, $date, $gender, $email, $status);
        $OK = $stmt->execute();
        #if query has been executed
        if ($OK)
            echo "<p>Student has been enrolled</p>";
        else
            echo "<p> Something went wrong :(</p>";

        $stmt->close();
        $mysqli->close();
        #values set to null
        $name = "";
        $date = "";
        $email = "";
        $email2 = "";

        } else {
            echo '<p><font color=#ff0000>Cannot add Student: One or more errors present!</font></p>';
        }
    }


?>