<?php
session_start();
require_once("../db_conn.php");
include("parentSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Parent") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$studentID = $_SESSION['user']['studentID'];
$gradeID = $_SESSION['user']['gradeID'];

// Fetch subjects based on gradeID
$sqlSubjects = "
    SELECT s.subjectID, s.subjectName
    FROM grade_subject gs
    JOIN subject s ON gs.subjectID = s.subjectID
    WHERE gs.gradeID = '$gradeID'
";

$resultSubjects = $conn->query($sqlSubjects);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Subjects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>View Assignment Marks</p>
                </div>
            </div>
        </div>

        <div class="table-section-item">
            <div class="table-container-item">
                <div class="table-box">
                    <table id="rows-def">
                        <tr id="table-head">
                            <th>Subject ID</th>
                            <th>Subject Name</th>
                            <th>View Materials</th>
                        </tr>
                        <?php
                        if ($resultSubjects->num_rows > 0) {
                            while ($row = $resultSubjects->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['subjectID']}</td>
                                        <td>{$row['subjectName']}</td>
                                        <td>
                                            <form action='viewAssignments.php' method='get'>
                                                <input type='hidden' name='subjectID' value='{$row['subjectID']}'>
                                                <button id='update' type='submit' name='viewMaterials'>View Assignments</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No subjects found for the selected grade.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="bottom-box">
            <div class="button">
                
            </div>
        </div>
    </div>
</body>
</html>
