<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$assignmentID = isset($_POST["assignmentID"]) ? $_POST["assignmentID"] : '';
$materialID = isset($_POST["materialID"]) ? $_POST["materialID"] : '';


// Query to fetch assignment details and related information
$sql = "SELECT 
            assignments.assignmentID,
            assignments.assignmentName,
            assignments.assignmentSize,
            assignments.uploadDate,
            assignments.studentID,
            user.fName,
            user.lName,
            study_material.materialName,
            topic.topicName,
            unit.unitName
        FROM assignments
        INNER JOIN user ON assignments.studentID = user.userID
        INNER JOIN study_material ON assignments.materialID = study_material.materialID
        INNER JOIN topic ON study_material.topicID = topic.topicID
        INNER JOIN unit ON topic.unitID = unit.unitID
        WHERE assignments.assignmentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assignmentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $assignment = $result->fetch_assoc();
} else {
    echo '<script>alert("Assignment not found."); window.location = "uploadMaterials.php";</script>';
    die();
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Give Marks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>Give Marks</p>
                </div>
            </div>
        </div>
        <div class="course-container">
            <div class="header">
                <div class="title">Assignment Details</div>
                <div class="profile"></div>
            </div>
            <div class="section">
                <div class="section-title">Assignment Information</div>
                <div class="subject-container">
                    <div>
                        <h3><?php echo htmlspecialchars($assignment['fName'] . ' ' . htmlspecialchars($assignment['lName'])); ?></h3>
                        <p>Unit: <?php echo htmlspecialchars($assignment['unitName']); ?></p>
                        <p>Topic: <?php echo htmlspecialchars($assignment['topicName']); ?></p>
                        <p>Material: <?php echo htmlspecialchars($assignment['materialName']); ?></p>
                        <p>Assignment: <?php echo htmlspecialchars($assignment['assignmentName']); ?> (<?php echo number_format($assignment['assignmentSize'] / 1024, 2); ?> KB)</p>
                        <p>Uploaded on: <?php echo htmlspecialchars($assignment['uploadDate']); ?></p>
                    </div>
                </div>
                <div class="section-title">Give Marks</div>
                <form action="updateMarks.php" method="post">
                    <input type="hidden" name="assignmentID" value="<?php echo htmlspecialchars($assignment['assignmentID']); ?>">
                    <input type="hidden" name="materialID" value="<?php echo htmlspecialchars($_POST['materialID']); ?>">
                   
                    <input type="hidden" name="studentID" value="<?php echo htmlspecialchars($assignment['studentID']); ?>">
                    <label for="marks">Marks:</label>
                    <input style="height: 30px; margin-top: 5px; margin-bottom: 10px;" type="number" id="marks" name="marks" required>
                    <button type="submit" name="submitMarks">Submit Marks</button>

                </form>
            </div>
            <div class="bottom-box">
                <div class="button"></div>
            </div>
        </div>
    </div>
</body>
</html>