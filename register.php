<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()) {
    echo "Failed to connected" . mysqli_connect_errno();
}

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
        $query_username = "SELECT username FROM users WHERE username = '$username'";
        $check_username_query = mysqli_query($con, $query_username);

        $i = 0;
        //If username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0) {
            $i++; //Add 1 to i
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, $query_username);
        }

        //Profile picture assignment
        $profile_pic = "";
    }
}
?>

<html>
    <head>
        <title>Welcom to Swirlfeed!</title>
    </head>
    <body>
        <form action="register.php" method="POST">
            <input 
                type="text" 
                name="reg_fname" 
                placeholder="First Name" 
                required
                value="<?php 
                if(isset($_SESSION['reg_fname'])) {
                    echo $_SESSION['reg_fname'];
                }
                ?>"
            >
            <br>
            <?php if(in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) 
            echo "Your first name must be between 2 and 25 characters<br>"; 
            ?>
            <input 
                type="text" 
                name="reg_lname" 
                placeholder="Last Name" 
                required
                value="<?php 
                if(isset($_SESSION['reg_lname'])) {
                    echo $_SESSION['reg_lname'];
                }
                ?>"
            >
            <br>
            <?php if(in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) 
            echo "Your last name must be between 2 and 25 characters<br>"; 
            ?>
            <?php if(in_array("Invalid format<br>", $error_array)) echo "Invalid format<br>"; ?>
            <input 
                type="email" 
                name="reg_email" 
                placeholder="Email" 
                required
                value="<?php 
                if(isset($_SESSION['reg_email'])) {
                    echo $_SESSION['reg_email'];
                }
                ?>"
            >
            <br>
            <input 
                type="email" 
                name="reg_email2" 
                placeholder="Confirm Email" 
                required
                value="<?php 
                if(isset($_SESSION['reg_email2'])) {
                    echo $_SESSION['reg_email2'];
                }
                ?>"
            >
            <br>
            <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>"; ?>
            <?php if(in_array("Invalid format<br>", $error_array)) echo "Invalid format<br>"; ?>
            <?php if(in_array("Emails don't match<br>", $error_array)) echo "Emails don't match<br>"; ?>
            <input type="password" name="reg_password" placeholder="Password" required>
            <br>
            <input type="password" name="reg_password2" placeholder="Confirm Password" required>
            <br>
            <?php if(in_array("Your passwords do not match<br>", $error_array)) echo "Your passwords do not match<br>"; ?>
            <?php if(in_array("Password can only contain English characters or numbers<br>", $error_array)) echo "Password can only contain English characters or numbers<br>"; ?>
            <?php if(in_array("Your password must be tween 4 and 30 characters<br>", $error_array)) echo "Your password must be tween 4 and 30 characters<br>"; ?>
            <input type="submit" name="register_button" value="Register">
        </form>
    </body>
</html>