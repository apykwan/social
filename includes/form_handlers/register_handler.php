<?php
//Declaring variables to prevent errors
$fname = ""; //first Name
$lname = ""; //last Name
$em = ""; //email
$em2 = ""; //email 2
$password = ""; //password
$password2 = ""; //password2
$date = ""; //Sign up date
$error_array = array(); //holds error message

if(isset($_POST['register_button'])) {
    //Registration form values

    // First name
    $fname = strip_tags($_POST['reg_fname']);   // Remove html tag
    $fname = str_replace(' ', '', $fname);  // Remove spaces
    $fname = ucfirst(strtolower($fname));
    $_SESSION['reg_fname'] = $fname;

    // Last name
    $lname = strip_tags($_POST['reg_lname']);   // Remove html tag
    $lname = str_replace(' ', '', $lname);  // Remove spaces
    $lname = ucfirst(strtolower($lname));
    $_SESSION['reg_lname'] = $lname;

    // Email
    $em = strip_tags($_POST['reg_email']);   // Remove html tag
    $em = str_replace(' ', '', $em);  // Remove spaces
    $em = ucfirst(strtolower($em));
    $_SESSION['reg_email'] = $em;

    // Email Confirmation
    $em2 = strip_tags($_POST['reg_email2']);   // Remove html tag
    $em2 = str_replace(' ', '', $em2);  // Remove spaces
    $em2 = ucfirst(strtolower($em2));
    $_SESSION['reg_email2'] = $em2;

    // Password
    $password = strip_tags($_POST['reg_password']);   // Remove html tag
    $password = str_replace(' ', '', $password);  // Remove spaces

    // Confirm password
    $password2 = strip_tags($_POST['reg_password2']);   // Remove html tag
    $password2 = str_replace(' ', '', $password2);  // Remove spaces

    $date = date("Y-m-d");  // current date

    if($em == $em2) {
        // Check if email is in valid format
        if(filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            // check if email exists
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            //Count the number of rows returned
            if(mysqli_num_rows($e_check) > 0) {
                array_push($error_array, "Email already in use<br>");
            }
        }
        else {
            array_push($error_array, "Invalid format<br>");
        }
    } 
    else {
        array_push($error_array, "Emails don't match<br>");
    }

    if(strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
    }

    if(strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
    }

    if($password != $password2) {
        array_push($error_array, "Your passwords do not match<br>");
    }
    else {
        if(preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Password can only contain English characters or numbers<br>");
        }
    }

    if(strlen($password) > 30 || strlen($password) < 4) {
        array_push($error_array, "Your password must be tween 4 and 30 characters<br>");
    }

    if(empty($error_array)) {
        $password = md5($password);     //encrypt password before sendnig to database

        //Generate username by concatenating first name and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");

        $i = 0;
        //If username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; //Add 1 to i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$username'");
        }

        //Profile picture assignment
        $rand = rand(1, 2); // Random number between 1 and 2

        if($rand == 1) 
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        else if($rand ==2)
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        
        $query = mysqli_query($con, "INSERT INTO users VALUES('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
        
        array_push($error_array, "<span style='color: #14C800;'>You're are all set! Please login</span>");

        //Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}
?>