<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");
?>

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
                        <th>Edit</th>
                        <th>DELETE</th>
                    </tr>
                    <?php
                    // SQL query to join user and student tables
                    $query = "
                        SELECT 
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
                    ";
                    
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['studentID']}</td>
                                    <td>{$row['grade']}</td>
                                    <td>{$row['className']}</td>
                                    <td>{$row['studentName']}</td>
                                    <td>{$row['parentName']}</td>
                                    <td>{$row['status']}</td>
                                    <td>
                                        <form action='viewTests.php' method='post'>
                                            <input type='hidden' name='studentID' value='{$row['studentID']}'>
                                            <input type='hidden' name='gradeID' value='{$row['gradeUID']}'>
                                            <button id='update' type='submit' name='viewMarksButton'>View Marks</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='addStudent.php' method='post'>
                                            <input type='hidden' name='studentID' value='{$row['studentID']}'>
                                            <input type='hidden' name='parentID' value='{$row['parentID']}'>
                                            <button id='update' type='submit' name='editStudentButton'>Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='db_model_admin.php' method='post'>
                                  
                                            <input type='hidden' name='studentID' value='{$row['studentID']}'>
                                            <input type='hidden' name='parentID' value='{$row['parentID']}'>
                                            <button id='update' type='submit' name='deleteStudentButton'>Delete</button>
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
            <form action='addStudent.php' method='post'>
                <button name="addNewStudent" id="popupButtonItem" class="submit">
                    Add a new Student
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
