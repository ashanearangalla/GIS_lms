<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$teacherID = $_SESSION["user"]["userID"];
$fName = htmlspecialchars($_SESSION["user"]["fName"]);
$lName = htmlspecialchars($_SESSION["user"]["lName"]);
$profimage = htmlspecialchars($_SESSION["user"]["imageurl"]);

$sql = "SELECT ts.classID, c.className, s.subjectName, s.subjectID
        FROM teacher_subject ts
        JOIN class c ON ts.classID = c.classID
        JOIN subject s ON ts.subjectID = s.subjectID
        WHERE ts.teacherID = '$teacherID'";
$result = mysqli_query($conn, $sql);

$classes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $classes[] = $row;
}

$images = ["../images/image5.jpg", "../images/image6.png", "../images/image7.png"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>View Classes</p>
                </div>
            </div>
        </div>
        <div class="course-container">
            <div class="header">
                <div class="title">Welcome, <?php echo $fName; ?>!</div>
                <div class="profile">
                    <img src="<?php echo '../images/'. htmlspecialchars($_SESSION["user"]["imageurl"]); ?>" alt="Profile Picture">
                    <span><?php echo "$fName $lName"; ?></span>
                </div>
            </div>
            <div class="section">
                <div class="section-title">All Classes</div>
                <div class="courses">
                    <?php foreach ($classes as $class) {
                        $randomImage = $images[array_rand($images)];
                        ?>
                        <div class="course-card">
                            <a href="studentDetails.php?classID=<?php echo $class['classID']; ?>&subjectID=<?php echo  $class['subjectID']; ?>">
                                <img src="<?php echo $randomImage; ?>" alt="Course Image">
                                <div class="course-info">
                                    <div class="course-title">Class: <?php echo $class['className']; ?></div>
                                    <div class="course-description"><?php echo $class['subjectName']; ?></div>
                                </div>
                            </a>
                        </div>
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
