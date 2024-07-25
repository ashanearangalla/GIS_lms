<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Check if editMarks button is set and get studentID and testID from POST
if (isset($_POST['editMarks'])) {
    $studentID = $_POST['studentID'];
    $testID = $_POST['testID'];
    $gradeID = $_POST['gradeID'];

    // Fetch student details and gradeID from student table
    $queryStudent = "SELECT * FROM student
                     INNER JOIN 
                    user ON student.studentID = user.userID
                     WHERE studentID = '$studentID'";
    $resultStudent = mysqli_query($conn, $queryStudent);

    if ($resultStudent->num_rows > 0) {
        $student = $resultStudent->fetch_assoc();
        $gradeID = $student['gradeID'];

        // Fetch subjects based on gradeID
        $sqlSubjects = "
            SELECT s.subjectID, s.subjectName
            FROM grade_subject gs
            JOIN subject s ON gs.subjectID = s.subjectID
            WHERE gs.gradeID = '$gradeID'
        ";
        $resultSubjects = mysqli_query($conn, $sqlSubjects);

        // Fetch marks for the given student, testID, and subjects from student_test_marks table
        $sqlMarks = "
            SELECT stm.subjectID, stm.marks
            FROM student_test_marks stm
            WHERE stm.studentID = '$studentID' AND stm.testID = '$testID'
        ";
        $resultMarks = mysqli_query($conn, $sqlMarks);
        $marks = [];
        while ($row = mysqli_fetch_assoc($resultMarks)) {
            $marks[$row['subjectID']] = $row['marks'];
        }
    } else {
        echo "Student not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Edit Marks</p>
            </div>
        </div>
    </div>
    <div class="course-container">
        <div class="section">
            <div class="section-title">Edit Marks</div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="testID" value="<?php echo $testID; ?>">
                <input type="hidden" name="gradeID" value="<?php echo $gradeID; ?>">
                <input type="hidden" name="studentID" value="<?php echo $studentID; ?>">
                <div class="form-container">
                    <!-- Display Student Information -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item">Student Name:</label>
                            <span><?php echo $student['fName'] . ' ' . $student['lName']; ?></span>
                        </div>
                    </div>

                    <!-- Display Subjects and Marks -->
                    <div class="inputs-column" id="subjectsContainer">
                        <?php while ($row = mysqli_fetch_assoc($resultSubjects)) {
                            $subjectID = $row['subjectID'];
                            $subjectName = $row['subjectName'];
                            $mark = isset($marks[$subjectID]) ? $marks[$subjectID] : ''; ?>
                            <div class="col1-popup-item">
                                <label class="labels-popup-item"><?php echo $subjectName; ?>:</label>
                                <input class="divided-input-popup-item" placeholder="Marks" type="number"
                                       name="subject_<?php echo $subjectID; ?>" id="subject_<?php echo $subjectID; ?>"
                                       value="<?php echo $mark; ?>" required/>
                            </div>
                        <?php } ?>
                        <div class="button-box-form">
                            <button name="updateMarksButton" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        // Validate marks (if needed)
        return true;
    }
</script>

</body>
</html>
