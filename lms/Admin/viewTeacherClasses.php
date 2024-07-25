<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Check if the teacherID is present in the URL
if (isset($_GET['teacherID'])) {
    $teacherID = $_GET['teacherID'];

    // Fetch the teacher's name
    $queryTeacherName = "SELECT fName, lName FROM user WHERE userID = '$teacherID' AND role = 'Teacher'";
    $resultTeacherName = $conn->query($queryTeacherName);
    $teacherName = '';
    if ($resultTeacherName->num_rows > 0) {
        $row = $resultTeacherName->fetch_assoc();
        $teacherName = $row['fName'] . ' ' . $row['lName'];
    }

    // Fetch the class and subject data based on teacherID
    $queryTeacherClasses = "
        SELECT 
            class.classID,
            class.className,
            subject.subjectID,
            subject.subjectName
        FROM 
            teacher_subject
        INNER JOIN 
            class ON teacher_subject.classID = class.classID
        INNER JOIN 
            subject ON teacher_subject.subjectID = subject.subjectID
        WHERE 
            teacher_subject.teacherID = '$teacherID'
    ";

    $resultTeacherClasses = $conn->query($queryTeacherClasses);

    // Fetch all classes and subjects for the select options
    $queryClasses = "SELECT classID, className FROM class";
    $resultClasses = $conn->query($queryClasses);

    $querySubjects = "SELECT subjectID, subjectName FROM subject";
    $resultSubjects = $conn->query($querySubjects);
} else {
    echo "No teacher ID provided.";
    exit;
}
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo $teacherName; ?></p>
            </div>
        </div>
    </div>

    <div class="table-section-item">
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>Class Name</th>
                        <th>Subject Name</th>
                        <th>Delete</th>
                    </tr>
                    <?php
                    if ($resultTeacherClasses->num_rows > 0) {
                        while ($row = $resultTeacherClasses->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['className']}</td>
                                    <td>{$row['subjectName']}</td>
                                    <td>
                                        <form action='db_model_admin.php' method='post'>
                                            <input type='hidden' name='teacherID' value='$teacherID'>
                                            <input type='hidden' name='classID' value='{$row['classID']}'>
                                            <input type='hidden' name='subjectID' value='{$row['subjectID']}'>
                                            <button name='deleteTeacherClassButton' type='submit'>Delete</button>
                                        </form>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No classes assigned to this teacher.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Assign Class and Subject to Teacher</div>
        <form action="db_model_admin.php" method="post">
            <input type="hidden" name="teacherID" value="<?php echo $teacherID; ?>">
            <div class="form-container">
                <div class="inputs-column">
                    <div class="col1-popup-item">
                        <label class="labels-popup-item" for="classID">Class:</label>
                        <select class="divided-input-popup-item" name="classID" id="classID" required>
                            <?php
                            if ($resultClasses->num_rows > 0) {
                                while ($row = $resultClasses->fetch_assoc()) {
                                    echo "<option value='{$row['classID']}'>{$row['className']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col1-popup-item">
                        <label class="labels-popup-item" for="subjectID">Subject:</label>
                        <select class="divided-input-popup-item" name="subjectID" id="subjectID" required>
                            <?php
                            if ($resultSubjects->num_rows > 0) {
                                while ($row = $resultSubjects->fetch_assoc()) {
                                    echo "<option value='{$row['subjectID']}'>{$row['subjectName']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="button-box-form">
                        <button name="assignTeacherButton" type="submit">Assign</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='manageTeacher.php' method='post'>
                <button name="backToTeachers" class="submit">
                    Back to Teachers
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>