<?php
	#returns boolean depending on validity of date
    function checkTheDate( $postedDate) {
   		if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$postedDate) ) {
      	list( $year , $month , $day ) = explode('-',$postedDate);
      	return( checkdate( $month , $day , $year ) );
   		} else {
      	return( false );
   		}
	}

    #checks email validity error variable passed by reference
	function checkEmail($email,&$emailErr,&$errors){
		#if email is not empty
        if (empty ($email)) {
        	$errors++;
        	$emailErr = "Please enter an email address";
    	}
    	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        	$errors++;
        	$emailErr = "E-mail address is not valid!";
    	} else {
            	$emailErr = "";
        	}
    	}
        #check if email exists or not, changes error message according to requirement
	function checkExistence($email,&$emailErr,&$errors,$choice){
			global $mysqli;
        	$stmt = $mysqli->prepare("SELECT * FROM Student WHERE email = ?");
        	$stmt->bind_param('s', $email);
        	$stmt->execute();
        	$stmt->store_result();
        	$count = $stmt->num_rows;
        	$stmt->close();
        	if ($count > 0 && $choice ==1) {
            	$errors++;
            	$emailErr = "This E-mail address already exists. Please enter another.";
        	} elseif($count ==0 && $choice ==2){
        		$errors++;
        		$emailErr = "This student is not present in the database";
        	}
	}






?>