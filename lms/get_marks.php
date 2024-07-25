<?php
header('Content-Type: application/json');

// Include the database connection
require_once("dbconnection.php");

// Get student ID safely
$student_id = isset($_GET['studentID']) ? intval($_GET['studentID']) : 0;

// SQL query to fetch marks and subject names
$sql = "
    SELECT s.subName, m.mark
    FROM marks m
    JOIN subject s ON m.subID = s.subID
    WHERE m.studentID = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Error preparing statement: " . $mysqli->error);
}

$stmt->bind_param('s', $student_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$mysqli->close();
?>
