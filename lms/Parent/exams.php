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

// Fetch term_test data based on gradeID
$queryTermTests = "
    SELECT 
        term_test.testID,
        term_test.term,
        g.grade,
        term_test.year,
        term_test.gradeID
    FROM 
        term_test
    INNER JOIN 
        grade g ON term_test.gradeID = g.gradeID
    WHERE 
        term_test.gradeID = '$gradeID'
";

$resultTermTests = $conn->query($queryTermTests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Tests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>View Tests</p>
                </div>
            </div>
        </div>

        <div class="table-section-item">
            <div class="table-container-item">
                <div class="table-box">
                    <table id="rows-def">
                        <tr id="table-head">
                            <th>Test ID</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Date</th>
                            <th>View Marks</th>
                        </tr>
                        <?php
                        if ($resultTermTests->num_rows > 0) {
                            while ($row = $resultTermTests->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['testID']}</td>
                                        <td>Grade {$row['grade']}</td>
                                        <td>{$row['term']} Term</td>
                                        <td>{$row['year']}</td>
                                        <td>
                                            <form action='studentMarks.php' method='post'>
                                                <input type='hidden' name='studentID' value='{$studentID}'>
                                                <input type='hidden' name='testID' value='{$row['testID']}'>
                                                <button id='update' type='submit' name='viewMarks'>View Marks</button>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No tests found for the selected grade.</td></tr>";
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
