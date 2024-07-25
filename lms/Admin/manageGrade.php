<?php
session_start();
require_once("../db_conn.php");

include("sidemenu.php");
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Manage Grades</p>
            </div>
        </div>
    </div>

    <div class="table-section-item">
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>Grade ID</th>
                        <th>Grade</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    // SQL query to fetch grades
                    $query = "SELECT gradeID, grade FROM grade";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['gradeID']}</td>
                                    <td>{$row['grade']}</td>
                                    <td>
                                        <form action='addGrade.php' method='post'>
                                            <input type='hidden' name='gradeID' value='{$row['gradeID']}'>
                                            <button id='update' type='submit' name='editGradeButton'>Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='db_model_admin.php' method='post'>
                                            <input type='hidden' name='gradeID' value='{$row['gradeID']}'>
                                            <button id='update' id='edit' type='submit' name='deleteGradeButton'>Delete</button>
                                        </form>
                                        
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No grades found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='addGrade.php' method='post'>
                <button name="addNewGrade" id="popupButtonItem" class="submit">
                    Add a new Grade
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>