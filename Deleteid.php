<?php
header("Content-Type: application/json");

include "connection1.php"; // Database connection

// Check if 'id' is provided
if (!empty($_POST['id'])) {
    $id = intval($_POST['id']); // Sanitize and get the ID

    // Prepare the DELETE query
    $delete_sql = "DELETE FROM `login_details` WHERE `id` = $id";

    if ($con->query($delete_sql) === TRUE) {
        if ($con->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Row deleted successfully."]);
        } else {
            echo json_encode(["status" => "fail", "message" => "No row found with the given ID."]);
        }
    } else {
        echo json_encode(["status" => "fail", "message" => "Query failed: " . $con->error]);
    }
} else {
    echo json_encode(["status" => "fail", "message" => "ID parameter is missing."]);
}

$con->close(); // Close the database connection
?>
