<?php

session_start();
require_once("../db_conn.php");
include("studentSidemenu.php");

// Fetch student details from session
$studentName = $_SESSION['user']['fName'] . ' ' . $_SESSION['user']['lName'];
$classID = $_SESSION['user']['classID'];
$gradeID = $_SESSION['user']['gradeID'];
$imageUrl = $_SESSION['user']['imageurl'];

// Fetch subjects based on gradeID
$sqlSubjects = "
    SELECT s.subjectID, s.subjectName
    FROM grade_subject gs
    JOIN subject s ON gs.subjectID = s.subjectID
    WHERE gs.gradeID = '$gradeID'
";
$resultSubjects = mysqli_query($conn, $sqlSubjects);
$images = ["../images/image5.jpg", "../images/image6.png", "../images/image7.png"];


?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learning Materials</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Learning Materials</p>
            </div>
        </div>
    </div>
    <div class="course-container">
        <div class="header">
            <div class="title">Welcome, <?php echo htmlspecialchars($studentName); ?></div>
            <div class="profile">
                <img style="object-fit: cover;" src="<?php echo '../images/'. $imageUrl; ?>" alt="Profile Picture">
                
                <span><?php echo htmlspecialchars($studentName); ?></span>
            </div>
        </div>

        <div class="section">
            <div class="section-title">All Courses</div>
            <div class="courses">
                <?php while ($row = mysqli_fetch_assoc($resultSubjects)) { 
                    $randomImage = $images[array_rand($images)]; ?>
                    <a href="viewMaterials.php?subjectID=<?php echo $row['subjectID']; ?>" class="course-card">
                    <img src="<?php echo $randomImage; ?>" alt="Course Image">
                        <div class="course-info">
                            <div class="course-title"><?php echo htmlspecialchars($row['subjectName']); ?></div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="bottom-box">
            <div class="button">
            
            </div>
        </div>
    </div>
</div>
</body>
</html>
