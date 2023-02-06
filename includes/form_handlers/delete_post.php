<?php
require '../../config/config.php';
 
if(isset($_GET['post_id']))
    $post_id = $_GET['post_id'];
 
if(isset($_POST['result'])) {
    if($_POST['result'] == 'true') {
        $query = mysqli_query($con, "UPDATE posts SET deleted='yes' WHERE id='$post_id'");

        //Deleting image if exist
        $queryImagePath = mysqli_query($con, "SELECT image FROM posts WHERE id='$post_id'");
        $row = mysqli_fetch_array($queryImagePath);

        if (is_file($row['image'])) 
            unlink($row['image']);
    }
}