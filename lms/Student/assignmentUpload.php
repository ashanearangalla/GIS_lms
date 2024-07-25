<?php
session_start();
require_once("../db_conn.php");
include("studentSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Student") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$studentID = $_SESSION['user']['userID'];
if (!isset($_POST['materialID'])) {
    echo '<script>window.location = "learningMaterials.php";</script>';
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Assignment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>Upload Assignment</p>
                </div>
            </div>
        </div>
        <div class="course-container">
            <div class="header">
                <div class="title">Upload your assignment here</div>
                <div class="profile"></div>
            </div>
            <div class="section">
                <div class="section-title">Assignment Upload</div>
                <form action="db_model_student.php" method="post" enctype="multipart/form-data">
                    <div class="form-container">
                        <div class="inputs-column">
                           
                            <div class="col1-popup-item">
                                <label class="labels-popup-item" for="file">Upload File:</label>
                                <input class="divided-input-popup-file" type="file" name="file" id="file" required />
                            </div>
                        </div>
                        <div class="button-box-form">
                        <input type="hidden" name="materialID" value="<?php echo htmlspecialchars($_POST['materialID']); ?>">
                        <input type="hidden" name="subjectID" value="<?php echo htmlspecialchars($_POST['subjectID']); ?>">
                            <button type="submit" name="uploadAssignment">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bottom-box">
                <div class="button"></div>
            </div>
        </div>
    </div>
</body>
</html>


