<?php
require('config/config.php');
require('includes/form_handlers/register_handler.php');
require('includes/form_handlers/login_handler.php');
?>

<html>
    <head>
        <title>Welcom to Socialfeed!</title>
        <link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="assets/js/register.js"></script>
    </head>
    <body>
        <?php
        if(isset($_POST["register_button"])) {
            echo '
                <script>
                    $(document).ready(() => {
                        $("#first").hide();
                        $("#second").show();
                    });
                </script>
            ';
        }
        ?>

        <div class="wrapper">   
            <div class="login_box">
                <div class="login-header">
                    <h1>Socialfeed!</h1>
                    Login or sign up below!
                </div>
                <div id="first">
                    <form action="register.php" method="POST">
                        <input 
                            type="email" 
                            name="log_email" 
                            placeholder="Email Address"
                            value="<?php 
                            if(isset($_SESSION['log_email'])) {
                                echo $_SESSION['log_email'];
                            }
                            ?>"
                            required
                        >
                        <br>
                        <input type="password" name="log_password" placeholder="Password" required>
                        <br>
                        <input type="submit" name="login_button" value="Login">
                        <br>
                        <?php if(in_array("Email or password was incorrect<br>", $error_array)) echo "Email or password was incorrect<br>" ?>
                        <a href="#" id="signup" class="signup">Need an account? Register Here!</a>
                    </form>
                </div>

                <div id="second">
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
                        <br>
                        <?php if(in_array("<span style='color: #14C800;'>You're are all set! Please login</span>", $error_array)) echo "<span style='color: #14C800;'>You're are all set! Please login</span>"; ?>
                        <a href="#" id="signin" class="signin">Already have an account? Sign in Here!</a>
                    </form>
                </div>
            </div>
        </div>
        
    </body>
</html>