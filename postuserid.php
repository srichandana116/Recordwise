<?php
include "connection1.php";

// Get the ID from the POST request
$id = $_POST['id'];

if (!empty($id)) {
    // Prepare the DELETE SQL query
    $delete_sql = "DELETE FROM `login_details` WHERE `id` = '$id'";

    if ($con->query($delete_sql) === TRUE) {
        echo "success";
    } else {
        echo "fail: " . $con->error;
    }
} else {
    echo "fail: ID parameter is missing.";
}

$con->close(); // Close the connection
?>
