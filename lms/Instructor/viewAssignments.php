<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$materialID = isset($_GET["materialID"]) ? $_GET["materialID"] : '';


$teacherID = $_SESSION["user"]["userID"];

// Query to fetch assignments for the selected material
$sql = "SELECT 
            assignments.assignmentID,
            assignments.assignmentName,
            assignments.assignmentSize,
            assignments.uploadDate,
            assignments.marks,
            user.fName,
            user.lName,
            user.userID
        FROM assignments
        INNER JOIN user ON assignments.studentID = user.userID
        WHERE assignments.materialID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $materialID);
$stmt->execute();
$result = $stmt->get_result();

$assignments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }
}
$stmt->close();
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assignments</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>View Assignments</p>
                </div>
            </div>
        </div>
        <div class="course-container">
            <div class="header">
                <div class="title">Assignments for Material ID: <?php echo htmlspecialchars($materialID); ?></div>
                <div class="profile"></div>
            </div>
            <div class="section">
                <div class="section-title">Uploaded Assignments</div>
                <?php if (!empty($assignments)): ?>
                    <?php foreach ($assignments as $assignment): ?>
                        <div class="subject-container">
                            <div>
                                <h3><?php echo htmlspecialchars($assignment['userID']).' - 
                                '. htmlspecialchars($assignment['fName'] . ' ' . htmlspecialchars($assignment['lName']) ); ?></h3>
                                <p>Assignment: <?php echo htmlspecialchars($assignment['assignmentName']); ?> (<?php echo number_format($assignment['assignmentSize'] / 1024, 2); ?> KB)</p>
                                <p>Uploaded on: <?php echo htmlspecialchars($assignment['uploadDate']); ?></p>
                                <?php if ($assignment['marks'] !== null): ?>
                                    <p>Marks: <?php echo htmlspecialchars($assignment['marks']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="../uploads/<?php echo htmlspecialchars($assignment['assignmentName']); ?>" download><button>Download</button></a>
                                <?php
                                $buttonStyle = '';
                                $disabledAttribute = '';

                                if ($assignment['marks'] !== null) {
                                    $buttonStyle = 'background-color: gray; color: white;';
                                    $disabledAttribute = 'disabled';
                                }
                                ?>
                                <form action="giveMarks.php" method="post">
                                    <input type="hidden" name="assignmentID" value="<?php echo $assignment['assignmentID']; ?>">
                                    <input type="hidden" name="materialID" value="<?php echo htmlspecialchars($materialID); ?>">

                                    <button name="giveMarks" style="<?php echo $buttonStyle; ?>" <?php echo $disabledAttribute; ?>>Give Marks</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No assignments found for this material.</p>
                <?php endif; ?>
            </div>
            <div class="bottom-box">
                <div class="button"></div>
            </div>
        </div>
    </div>
</body>
</html>