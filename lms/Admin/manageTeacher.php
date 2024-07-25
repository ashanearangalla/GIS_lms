<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Manage Teachers</p>
            </div>
        </div>
        <div class="box-1">
            <div class="search-bar">
                <ul>
                    <li class="search">
                        <form action="teachers.php" method="post" id="searchForm">
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
                        <th>Teacher ID</th>
                        <th>Teacher Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>View Classes</th>
                        <th>EDIT</th>
                        <th>DELETE</th>
                    </tr>
                    <?php
                    // SQL query to join user and teacher tables
                    $query = "
                        SELECT 
                            teacher.teacherID,
                            CONCAT(user.fName, ' ', user.lName) AS teacherName,
                            user.email,
                            user.status
                        FROM 
                            teacher
                        INNER JOIN 
                            user ON teacher.teacherID = user.userID
                    ";
                    
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['teacherID']}</td>
                                    <td>{$row['teacherName']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['status']}</td>
                                    <td>
                                        <a href='viewTeacherClasses.php?teacherID={$row['teacherID']}' id='update'>View Classes</a>
                                    </td>
                                    <td>
                                        <form action='addTeacher.php' method='post'>
                                            <input type='hidden' name='teacherID' value='{$row['teacherID']}'>
                                            <button id='update' type='submit' name='editTeacherButton'>Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='db_model_admin.php' method='post'>
                                            <input type='hidden' name='teacherID' value='{$row['teacherID']}'>
                                            <button id='update' type='submit' name='deleteTeacherButton'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No teachers found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='addTeacher.php' method='post'>
                <button name="addNewTeacher" id="popupButtonItem" class="submit">
                    Add a new Teacher
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>