<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");



if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$teacherID = $_SESSION["user"]["userID"];
$teacherName = $_SESSION["user"]["fName"];

$teacherID = $_SESSION["user"]["userID"];
$fName = htmlspecialchars($_SESSION["user"]["fName"]);
$lName = htmlspecialchars($_SESSION["user"]["lName"]);
$profimage = htmlspecialchars($_SESSION["user"]["imageurl"]);

// Fetch the classes associated with the teacher
$sql = "SELECT class.classID, class.className, subject.subjectName 
        FROM class
        INNER JOIN teacher_subject ON class.classID = teacher_subject.classID
        INNER JOIN subject ON teacher_subject.subjectID = subject.subjectID
        WHERE teacher_subject.teacherID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacherID);
$stmt->execute();
$result = $stmt->get_result();

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }
}
$stmt->close();
$conn->close();
$images = ["../images/image5.jpg", "../images/image6.png", "../images/image7.png"];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>Instructor Dashboard</p>
                </div>
            </div>
        </div>
        <div class="course-container">
        <div class="header">
                <div class="title">Welcome, <?php echo $fName; ?>!</div>
                <div class="profile">
                    <img src="<?php echo '../images/'. $profimage; ?>" alt="Profile Picture">
                    <span><?php echo "$fName $lName"; ?></span>
                </div>
            </div>
            <div class="section">
                <div class="section-title">Recently Accessed Classes</div>
                <div class="courses">
                    <?php if (!empty($classes)): ?>
                        <?php foreach (array_slice($classes, 0, 3) as $class): 
                             $randomImage = $images[array_rand($images)]; ?>
                            
                            <div class="course-card">
                                
                                <img src="<?php echo $randomImage; ?>" alt="Course Image">
                                <div class="course-info">
                                    <div class="course-title">Class: <?php echo htmlspecialchars($class['className']); ?></div>
                                    <div class="course-description"><?php echo htmlspecialchars($class['subjectName']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No classes found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="section">
                <div class="section-title">All Classes</div>
                <div class="courses">
                    <?php if (!empty($classes)): ?>
                        <?php foreach ($classes as $class): ?>
                            <div class="course-card">
                                <img src="<?php echo $randomImage; ?>" alt="Course Image">
                                <div class="course-info">
                                    <div class="course-title">Class: <?php echo htmlspecialchars($class['className']); ?></div>
                                    <div class="course-description"><?php echo htmlspecialchars($class['subjectName']); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No classes found.</p>
                    <?php endif; ?>
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
