<?php
ob_start(); //Turn on output buffering
session_start();

$timezone = date_default_timezone_set("America/Los_Angeles");

$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()) {
    echo "Failed to connected" . mysqli_connect_errno();
}

?>