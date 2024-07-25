<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Get the gradeID and testID from POST request
$gradeID = isset($_POST['gradeID']) ? $_POST['gradeID'] : '';
$testID = isset($_POST['testID']) ? $_POST['testID'] : '';

// Initialize variables
$classID = '';
$studentID = '';
$marks = array();

// Check if editing existing marks
if (isset($_POST['editMarks'])) {
    $studentID = $_POST['studentID'];

    // Fetch existing marks for the student and test
    $sqlMarks = "SELECT * FROM marks WHERE testID = '$testID' AND studentID = '$studentID'";
    $resultMarks = mysqli_query($conn, $sqlMarks);

    if (mysqli_num_rows($resultMarks) > 0) {
        while ($row = mysqli_fetch_assoc($resultMarks)) {
            $subjectID = $row['subjectID'];
            $marks[$subjectID] = $row['marks'];
        }
    }
}

// Fetch classes based on gradeID
$sqlClasses = "SELECT * FROM `class` WHERE `gradeID` = '$gradeID'";
$resultClasses = mysqli_query($conn, $sqlClasses);

// Fetch subjects based on gradeID
$sqlSubjects = "
    SELECT s.subjectID, s.subjectName
    FROM grade_subject gs
    JOIN subject s ON gs.subjectID = s.subjectID
    WHERE gs.gradeID = '$gradeID'
";
$resultSubjects = mysqli_query($conn, $sqlSubjects);
?>
<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo isset($_POST['editMarks']) ? 'Edit Marks' : 'Add Marks'; ?></p>
            </div>
        </div>
    </div>
    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo isset($_POST['editMarks']) ? 'Edit Marks' : 'Add Marks'; ?></div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="testID" value="<?php echo $testID; ?>">
                <input type="hidden" name="gradeID" value="<?php echo $gradeID; ?>">
                <input type="hidden" name="studentID" value="<?php echo $studentID; ?>">
                <div class="form-container">
                    <!-- Class and Student Selection -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="classID">Class:</label>
                            <select class="divided-input-popup-item" name="classID" id="classID" required onchange="fetchStudents(this.value)">
                                <option value="" selected hidden>Select Class</option>
                                <?php while ($row = mysqli_fetch_assoc($resultClasses)) { ?>
                                    <option value="<?php echo $row['classID']; ?>" <?php echo ($row['classID'] == $classID) ? 'selected' : ''; ?>><?php echo $row['className']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="studentID">Student:</label>
                            <select class="divided-input-popup-item" name="studentID" id="studentID" required>
                                <option value="" selected hidden>Select Student</option>
                                <!-- Students will be populated dynamically -->
                            </select>
                        </div>
                    </div>

                    <!-- Subjects and Marks -->
                    <div class="inputs-column" id="subjectsContainer">
                        <?php while ($row = mysqli_fetch_assoc($resultSubjects)) { ?>
                            <div class="col1-popup-item">
                                <label class="labels-popup-item" for="subject_<?php echo $row['subjectID']; ?>"><?php echo $row['subjectName']; ?>:</label>
                                <input class="divided-input-popup-item" placeholder="Marks" type="number" name="subject_<?php echo $row['subjectID']; ?>" id="subject_<?php echo $row['subjectID']; ?>" value="<?php echo isset($marks[$row['subjectID']]) ? $marks[$row['subjectID']] : ''; ?>" required/>
                            </div>
                        <?php } ?>
                        <div class="button-box-form">
                            <button name="<?php echo isset($_POST['editMarks']) ? 'updateMarksButton' : 'addMarksButton'; ?>" type="submit"><?php echo isset($_POST['editMarks']) ? 'Update' : 'Submit'; ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="bottom-box">
            <div class="button">
            
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Fetch students when page loads if studentID is set
        var studentID = '<?php echo $studentID; ?>';
        if (studentID !== '') {
            fetchStudents('<?php echo $classID; ?>');
        }
    });

    function fetchStudents(classID) {
        if (classID === '') {
            document.getElementById('studentID').innerHTML = '<option value="" selected hidden>Select Student</option>';
            return;
        }

        // Fetch students via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_students_filter.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('studentID').innerHTML = this.responseText;
            }
        };
        xhr.send('classID=' + classID + '&testID=' + '<?php echo $testID; ?>'); // Send classID and testID
    }

    function validateForm() {
        var classID = document.getElementById('classID').value.trim();
        var studentID = document.getElementById('studentID').value.trim();
        if (classID === '' || studentID === '') {
            alert('Please select a class and a student.');
            return false;
        }
        return true;
    }
</script>
</body>
</html>