<?php
session_start();
require_once("../db_conn.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

if (isset($_POST['assignmentID']) && isset($_POST['marks'])) {
    $assignmentID = $_POST['assignmentID'];
    $materialID = $_POST['materialID'];
   
    $marks = $_POST['marks'];

    // Update the assignment table with the marks
    $sql = "UPDATE assignments SET marks = ? WHERE assignmentID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $marks, $assignmentID);

    if ($stmt->execute()) {
        echo '<script>alert("Marks updated successfully."); window.location = "viewAssignments.php?materialID='.$materialID.'";</script>';
    } else {
        echo '<script>alert("Failed to update marks."); window.location =  "viewAssignments.php?materialID='.$materialID.'";</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Invalid request."); window.location =  "viewAssignments.php?materialID='.$materialID.'";</script>';
}

$conn->close();
?>