<?php
session_start();
require_once("../db_conn.php");

$classID = isset($_POST['classID']) ? $_POST['classID'] : '';
$testID = isset($_POST['testID']) ? $_POST['testID'] : '';

// Query to fetch students who haven't been marked for the given test
$sqlStudents = "
    SELECT s.studentID, u.fName, u.lName
    FROM student s
    INNER JOIN user u ON s.studentID = u.userID
    LEFT JOIN student_test_marks stm ON s.studentID = stm.studentID AND stm.testID = '$testID'
    WHERE s.classID = '$classID' AND stm.studentID IS NULL
";

$resultStudents = mysqli_query($conn, $sqlStudents);

$response = '<option value="" selected hidden>Select Student</option>';
while ($row = mysqli_fetch_assoc($resultStudents)) {
    $response .= '<option value="' . $row['studentID'] . '">' . $row['fName'] . ' ' . $row['lName'] . '</option>';
}
echo $response;
?>