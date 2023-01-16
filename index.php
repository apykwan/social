<?php
$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()) {
    echo "Failed to connected" . mysqli_connect_errno();
}

$action =  "INSERT INTO test (name) VALUES('jane')";

$query = mysqli_query($con, $action);

?>

<html>
<head>
    <title>Social</title>
</head>

<body>
    <h1>Hello</h1>
</body>

</html>