<?php
// connect to database
$link = mysqli_connect("localhost", "--------", "---------------------", "--------")
or die('Could not connect');
// retrieve the email to delete
$delete_email = $_POST["deleteEmail"];
// delete the record from the database
mysqli_query($link, "DELETE FROM PATRON WHERE p_email = '$delete_email'");
// close the database connection
mysqli_close($link); ?>
