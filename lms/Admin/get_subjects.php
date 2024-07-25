<?php
require_once("../db_conn.php");

if (isset($_GET['gradeID'])) {
    $gradeID = intval($_GET['gradeID']);
    
    $sqlSubjects = "SELECT * FROM `subject` WHERE `gradeID` = $gradeID";
    $resultSubjects = mysqli_query($conn, $sqlSubjects);
    
    $subjects = [];
    while ($row = mysqli_fetch_assoc($resultSubjects)) {
        $subjects[] = ['subjectID' => $row['subjectID'], 'subjectName' => $row['subjectName']];
    }
    
    header('Content-Type: application/json');
    echo json_encode($subjects);
} else {
    echo json_encode(['error' => 'Invalid grade ID']);
}
?>