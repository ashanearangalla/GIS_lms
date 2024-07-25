<?php
session_start();
require_once("../db_conn.php");

include("sidemenu.php");
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Manage Subjects</p>
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
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    // SQL query to fetch subjects
                    $query = "SELECT subjectID, subjectName FROM subject";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['subjectID']}</td>
                                    <td>{$row['subjectName']}</td>
                                    <td>
                                        <form action='addSubject.php' method='post'>
                                            <input type='hidden' name='subjectID' value='{$row['subjectID']}'>
                                            <button id='update' type='submit' name='editSubjectButton'>Edit</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='db_model_admin.php' method='post'>
                                            <input type='hidden' name='subjectID' value='{$row['subjectID']}'>
                                            <button id='update' type='submit' name='deleteSubjectButton'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No subjects found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='addSubject.php' method='post'>
                <button name="addNewSubject" id="popupButtonItem" class="submit">
                    Add a new Subject
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>