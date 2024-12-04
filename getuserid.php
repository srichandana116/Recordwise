<?php
include 'connection1.php';

$select_sql = "SELECT * FROM login_details WHERE user_id='" . $_POST["user_id"] . "'";
$result = $con->query($select_sql);

if ($result->num_rows > 0) {
    $questions = [];
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row; // Collect rows into the $questions array
    }
    $jsonData = json_encode($questions); // Encode the $questions array into JSON
    echo $jsonData;
} else {
    echo "No Data Found";
}

$con->close();
?>
