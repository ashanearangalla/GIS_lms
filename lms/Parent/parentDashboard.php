<?php
session_start();
require_once("../db_conn.php");
include("parentSidemenu.php");

$studentName = $_SESSION["user"]["studentFName"] . " " . $_SESSION["user"]["studentLName"];
$gradeID = $_SESSION["user"]["gradeID"];
$classID = $_SESSION["user"]["classID"];
$studentID = $_SESSION["user"]["studentID"];

// Fetch class name based on classID
$classSql = "SELECT className FROM class WHERE classID = ?";
$classStmt = $conn->prepare($classSql);
$classStmt->bind_param("i", $classID);
$classStmt->execute();
$classResult = $classStmt->get_result();

$className = '';
if ($classResult->num_rows > 0) {
    $classRow = $classResult->fetch_assoc();
    $className = $classRow['className'];
}
$classStmt->close();

// Fetch subjects based on gradeID
$subjectsSql = "SELECT s.subjectName, s.subjectID FROM grade_subject gs JOIN subject s ON gs.subjectID = s.subjectID WHERE gs.gradeID = ?";
$subjectsStmt = $conn->prepare($subjectsSql);
$subjectsStmt->bind_param("i", $gradeID);
$subjectsStmt->execute();
$subjectsResult = $subjectsStmt->get_result();

$subjects = [];
while ($subjectRow = $subjectsResult->fetch_assoc()) {
    $subjects[] = $subjectRow;
}
$subjectsStmt->close();

// Fetch categories for each subject
$categories = [];
foreach ($subjects as $subject) {
    $subjectID = $subject['subjectID'];
    $categorySql = "
        SELECT c.categoryName 
        FROM student_category sc 
        JOIN category c ON sc.categoryID = c.categoryID 
        WHERE sc.studentID = ? AND sc.subjectID = ?";
    $categoryStmt = $conn->prepare($categorySql);
    $categoryStmt->bind_param("ii", $studentID, $subjectID);
    $categoryStmt->execute();
    $categoryResult = $categoryStmt->get_result();

    if ($categoryResult->num_rows > 0) {
        $categoryRow = $categoryResult->fetch_assoc();
        $categories[$subjectID] = $categoryRow['categoryName'];
    } else {
        $categories[$subjectID] = 'Not Evaluated';
    }
    $categoryStmt->close();
}
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Evaluate Child</p>
            </div>
        </div>  
    </div>
    <div class="heading-box" style="height: 100px; background-color: aliceblue; text-align: left; color: black; font-size: 18px;">
        <div class="box-1">
            <div class="title" style="text-align: left; font-size: 15px;">
                <p>Student Name: <?php echo htmlspecialchars($studentName); ?> </p>
                <p>Class: <?php echo htmlspecialchars($className); ?> </p>
            </div>
        </div>  
    </div>
    <div class="parent-menu">
        <?php foreach ($subjects as $subject): ?>
            <div class="subject-box">
                <h2><?php echo htmlspecialchars($subject['subjectName']); ?></h2>
                <p>Category: <?php echo htmlspecialchars($categories[$subject['subjectID']]); ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='' method='post'>
                <button name="addNewStudent" id="popupButtonItem" class="submit">
                    Add a new Feedback
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('popupButtonItem').addEventListener('click', function() {
        document.getElementById('overlay-2').style.display = 'block';
        document.getElementById('popupContainerItem').style.display = 'block';
    });

    function closePopupItem() {
        document.getElementById('overlay-2').style.display = 'none';
        document.getElementById('popupContainerItem').style.display = 'none';
    }

    function generateTripID() {
        // Add your logic to generate trip ID here
        return true;
    }

    function getTurns(routeID) {
        // Fetch and populate turn IDs based on the selected route
    }
</script>
</body>
</html>
