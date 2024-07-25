<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Initialize variables
$className = '';
$calendarYear = '';
$gradeID = '';
$teacherID = '';


// Check if editing an existing class
if (isset($_POST['editClassButton'])) {
    $classID = mysqli_real_escape_string($conn, $_POST['classID']);
    
    $query = "SELECT * FROM class WHERE classID = '$classID'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $className = $row['className'];
        $calendarYear = $row['calendarYear'];
        $gradeID = $row['gradeID'];
        $teacherID = $row['teacherID'];
    }
}

// Fetch grades for the select options
$sqlGrades = "SELECT * FROM grade";
$resultGrades = mysqli_query($conn, $sqlGrades);

// Fetch teachers for the select options, excluding those already assigned to a class
$sqlTeachers = "
    SELECT user.userID, CONCAT(user.fName, ' ', user.lName) AS teacherName
    FROM user
    LEFT JOIN teacher ON user.userID = teacher.teacherID
    LEFT JOIN class ON teacher.teacherID = class.teacherID
    WHERE user.role = 'Teacher' AND (class.teacherID IS NULL OR class.teacherID = '$teacherID')
";
$resultTeachers = mysqli_query($conn, $sqlTeachers);
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo isset($_POST['editClassButton']) ? 'Edit Class' : 'Add Class'; ?></p>
            </div>
        </div>
    </div>

    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo isset($_POST['editClassButton']) ? 'Edit Class' : 'Add Class'; ?></div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">
                <div class="form-container">
                    <input type="hidden" name="classID" value="<?php echo $classID; ?>">
                    <!-- Class Details -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="className">Class Name:</label>
                            <input class="divided-input-popup-item" placeholder="Class Name" type="text" name="className" id="className" required value="<?php echo $className; ?>"/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="calendarYear">Calendar Year:</label>
                            <input class="divided-input-popup-item" placeholder="Calendar Year" type="text" name="calendarYear" id="calendarYear" required value="<?php echo $calendarYear; ?>"/>
                        </div>
                        
                    </div>

                    <div class="inputs-column">


                    <div class="col1-popup-item">
                            <label class="labels-popup-item" for="gradeID">Grade:</label>
                            <select class="divided-input-popup-item" name="gradeID" id="gradeID" required>
                                <option value="" selected hidden>Select Grade</option>
                                <?php while ($row = mysqli_fetch_assoc($resultGrades)) { ?>
                                    <option value="<?php echo $row['gradeID']; ?>" <?php echo $row['gradeID'] == $gradeID ? 'selected' : ''; ?>><?php echo $row['grade']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="teacherID">Class Teacher:</label>
                            <select class="divided-input-popup-item" name="teacherID" id="teacherID" required>
                                <option value="" selected hidden>Select Teacher</option>
                                <?php while ($row = mysqli_fetch_assoc($resultTeachers)) { ?>
                                    <option value="<?php echo $row['userID']; ?>" <?php echo $row['userID'] == $teacherID ? 'selected' : ''; ?>><?php echo $row['teacherName']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="button-box-form">
                            <button name="<?php echo isset($_POST['editClassButton']) ? 'updateClassButton' : 'addClassButton'; ?>" type="submit">Submit</button>
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
    function validateForm() {
        var className = document.getElementById('className').value.trim();
        var calendarYear = document.getElementById('calendarYear').value.trim();
        var gradeID = document.getElementById('gradeID').value.trim();
        var teacherID = document.getElementById('teacherID').value.trim();

        if (className === '' || calendarYear === '' || gradeID === '' || teacherID === '') {
            alert('Please fill in all fields.');
            return false;
        }
        return true;
    }
</script>
</body>
</html>