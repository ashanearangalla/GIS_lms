<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Manage Classes</p>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'error'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                showErrorPopup('Error deleting class. Please try again.');
            });
        </script>
    <?php endif; ?>

    <div class="table-section-item">
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>Class ID</th>
                        <th>Class Name</th>
                        <th>Teacher Name</th>
                        <th>Grade</th>
                        <th>EDIT</th>
                        <th>DELETE</th>
                    </tr>
                    <?php
                    // SQL query to join class, teacher, and grade tables
                    $query = "
                        SELECT 
                            class.classID,
                            class.className,
                            CONCAT(user.fName, ' ', user.lName) AS teacherName,
                            grade.grade
                        FROM 
                            class
                        INNER JOIN 
                            teacher ON class.teacherID = teacher.teacherID
                        INNER JOIN 
                            user ON teacher.teacherID = user.userID
                        INNER JOIN 
                            grade ON class.gradeID = grade.gradeID
                    ";
                    
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['classID']}</td>
                                    <td>{$row['className']}</td>
                                    <td>{$row['teacherName']}</td>
                                    <td>{$row['grade']}</td>
                                    <td>
                                        <form action='addClass.php' method='post'>
                                            <input type='hidden' name='classID' value='{$row['classID']}'>
                                            <button id='update' type='submit' name='editClassButton'>Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='db_model_admin.php' method='post' onsubmit='return confirmDelete()'>
                                            <input type='hidden' name='classID' value='{$row['classID']}'>
                                            <button id='update' type='submit' name='deleteClassButton'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No classes found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='addClass.php' method='post'>
                <button name="addNewClass" id="popupButtonItem" class="submit">
                    Add a new Class
                </button>
            </form>
        </div>
    </div>
</div>

<div class="popup-overlay" id="errorPopup">
    <div class="popup-content">
        <h2>Error</h2>
        <p>There was an error processing your request. Please try again later.</p>
        <button onclick="closePopup()">Close</button>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this class?');
    }

    function showErrorPopup(message) {
        document.querySelector("#errorPopup p").innerText = message;
        document.getElementById("errorPopup").style.display = "flex";
    }

    function closePopup() {
        document.getElementById("errorPopup").style.display = "none";
    }
</script>
</body>
</html>