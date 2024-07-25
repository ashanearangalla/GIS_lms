<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$classID = isset($_GET['classID']) ? $_GET['classID'] : '';
$subjectID = isset($_GET['subjectID']) ? $_GET['subjectID'] : '';

$sql = "SELECT 
            student.studentID,
            student.classID,
            student.gradeID AS gradeUID,

            class.className,
            grade.grade,
            CONCAT(user.fName, ' ', user.lName) AS studentName,
            CONCAT(parent.fName, ' ', parent.lName) AS parentName,
            user.status,
            student.parentID
        FROM 
            student
        INNER JOIN 
            user ON student.studentID = user.userID
        INNER JOIN 
            grade ON student.gradeID = grade.gradeID
        INNER JOIN 
            class ON student.classID = class.classID

        LEFT JOIN 
            user AS parent ON student.parentID = parent.userID
        WHERE 
            student.classID = '$classID'";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Manage Students</p>
            </div>
        </div>
        <div class="box-1">
            <div class="search-bar">
                <ul>
                    <li class="search">
                        <form action="trips.php" method="post" id="searchForm">
                            <i onclick="submitForm()" class="bx bx-search-alt-2"></i>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="table-section-item">
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>Student ID</th>
                        <th>Class ID</th>
                        <th>Grade ID</th>
                        <th>Student Name</th>
                        <th>Parent Name</th>
                        
                        <th>STATUS</th>
                        <th>View Marks</th>
                        
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['studentID']}</td>
                                    <td>{$row['className']}</td>
                                    <td>{$row['grade']}</td>
                                    <td>{$row['studentName']}</td>
                                    <td>{$row['parentName']}</td>
                                     
                                    <td>{$row['status']}</td>
                                    <td>
                                        <form action='studentTests.php' method='post'>
                                            <input type='hidden' name='studentID' value='{$row['studentID']}'>
                                            <input type='hidden' name='gradeID' value='{$row['gradeUID']}'>
                                            <button id='update' type='submit' name='viewMarksButton'>View Marks</button>
                                        </form>
                                    </td>
                                    
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No students found</td></tr>";
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
