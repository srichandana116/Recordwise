<?php
$servername = "localhost";
$username="root";
$password= "";
$dbname = "recordwise";
$con= new mysqli($servername, $username, $password, $dbname);
if($con->connect_error){
    die("connection failed");
}