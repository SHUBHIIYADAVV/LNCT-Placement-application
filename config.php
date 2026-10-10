<?php
// Connects to MySQL. Every page includes this file.
session_start();
$conn = new mysqli("localhost", "root", "", "placement_db");
if ($conn->connect_error) {
    die("Database connection failed");
}
