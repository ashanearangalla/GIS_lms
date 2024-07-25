<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

if (isset($_POST["classID"]) && isset($_POST["subjectID"])) {
    $classID = $_POST["classID"];
    $className = $_POST["className"];
    $subjectID = $_POST["subjectID"];
    $subjectName = $_POST["subjectName"];
    $teacherID = $_SESSION["user"]["userID"];
    
    // Fetch students in the class
    $studentQuery = "SELECT studentID, fName, lName FROM student 
                    INNER JOIN user ON student.studentID = user.userID
                    WHERE classID = ?";
    $stmt = $conn->prepare($studentQuery);
    $stmt->bind_param("i", $classID);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
    }
    $stmt->close();
    $conn->close();
} else {
    echo '<script>window.location = "instructorDashboard.php";</script>';
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materials</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleUploadOptions() {
            const uploadTo = document.querySelector('input[name="uploadTo"]:checked').value;
            document.getElementById('student-options').style.display = uploadTo === 'student' ? 'block' : 'none';
            document.getElementById('category-options').style.display = uploadTo === 'category' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>Upload Materials</p>
                </div>
            </div>
        </div>
        <div class="course-container">
            <div class="header">
                <div class="title">Class: <?php echo htmlspecialchars($className); ?> - Subject: <?php echo htmlspecialchars($subjectName); ?></div>
                <div class="profile"></div>
            </div>
            <div class="section">
                <div class="section-title">Upload Material</div>
                <form action="db_model_instructor.php" method="post" enctype="multipart/form-data">
                    <div class="form-container">
                        <div class="inputs-column">
                            <div class="col1-popup-item">
                                <label class="labels-popup-item" for="unit">Unit Name:</label>
                                <input class="divided-input-popup-item" placeholder="Unit Name" type="text" name="unit" id="unit" required/>
                            </div>
                            <div class="col1-popup-item">
                                <label class="labels-popup-item" for="topic">Topic:</label>
                                <input class="divided-input-popup-item" placeholder="Topic" type="text" name="topic" id="topic" required/>
                            </div>
                            <div class="col1-popup-item">
                                <label class="labels-popup-item" for="fileName">File:</label>
                                <input class="divided-input-popup-file" type="file" name="fileName" id="fileName" required/>
                            </div>
                        </div>
                        <div class="inputs-column">
                            <div class="col1-popup-item">
                                <label class="labels-popup-item">Upload To:</label>
                                <div>
                                    <input type="radio" name="uploadTo" value="student" id="uploadToStudent" onclick="toggleUploadOptions()" required/>
                                    <label for="uploadToStudent">Student</label>
                                    <input type="radio" name="uploadTo" value="category" id="uploadToCategory" onclick="toggleUploadOptions()" required/>
                                    <label for="uploadToCategory">Category</label>
                                </div>
                            </div>
                            <div id="student-options" style="display:none;">
                                <label class="labels-popup-item" for="studentID">Select Student:</label>
                                <select class="divided-input-popup-item" name="studentID" id="studentID">
                                    <option value="">Select Student</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student['studentID']; ?>"><?php echo htmlspecialchars($student['fName'] . " " . $student['lName']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div id="category-options" style="display:none;">
                                <label class="labels-popup-item" for="category">Student Category:</label>
                                <div class="checkbox-popup">
                                    <input class="divided-input-popup-checkbox" type="checkbox" value="Excellent" name="category[]" id="category1" />
                                    <label class="label-checkbox" for="category1">Excellent</label>
                                </div>
                                <div class="checkbox-popup">
                                    <input class="divided-input-popup-checkbox" type="checkbox" value="Good" name="category[]" id="category2" />
                                    <label class="label-checkbox" for="category2">Good</label>
                                </div>
                                <div class="checkbox-popup">
                                    <input class="divided-input-popup-checkbox" type="checkbox" value="Average" name="category[]" id="category3" />
                                    <label class="label-checkbox" for="category3">Average</label>
                                </div>
                                <div class="checkbox-popup">
                                    <input class="divided-input-popup-checkbox" type="checkbox" value="Bad" name="category[]" id="category4" />
                                    <label class="label-checkbox" for="category4">Bad</label>
                                </div>
                            </div>
                            <input type="hidden" name="classID" value="<?php echo htmlspecialchars($classID); ?>">
                            <input type="hidden" name="teacherID" value="<?php echo htmlspecialchars($teacherID); ?>">
                            <input type="hidden" name="subjectID" value="<?php echo htmlspecialchars($subjectID); ?>">
                        </div>
                        <div class="button-box-form">
                            <button type="submit">Submit</button>
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
</body>
</html>
