<?php
$server = 'localhost';
$userName = 'root';
$password = '';
$dbName = 'online_store';

$conn = new mysqli($server, $userName, $password, $dbName);
if($conn->connect_error)
{
    die("Connection failed" . $conn->connect_error);
}
?>