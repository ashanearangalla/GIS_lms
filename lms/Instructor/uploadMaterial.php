<?php
session_start();
require_once("../db_conn.php");
include("instructorSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Teacher") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$classID = isset($_GET["classID"]) ? $_GET["classID"] : '';
$subjectID = isset($_GET["subjectID"]) ? $_GET["subjectID"] : '';
$studentID = isset($_GET["studentID"]) ? $_GET["studentID"] : '';
$categoryID = isset($_GET["categoryID"]) ? $_GET["categoryID"] : '';

$teacherID = $_SESSION["user"]["userID"];

// Query to fetch class name
$classQuery = "SELECT className FROM class WHERE classID = ?";
$classStmt = $conn->prepare($classQuery);
$classStmt->bind_param("i", $classID);
$classStmt->execute();
$classResult = $classStmt->get_result();

if ($classResult->num_rows > 0) {
    $classRow = $classResult->fetch_assoc();
    $className = $classRow['className'];
} else {
    $className = "Unknown Class";
}
$classStmt->close();

// Query to fetch subject name
$subjectQuery = "SELECT subjectName FROM subject WHERE subjectID = ?";
$subjectStmt = $conn->prepare($subjectQuery);
$subjectStmt->bind_param("i", $subjectID);
$subjectStmt->execute();
$subjectResult = $subjectStmt->get_result();

if ($subjectResult->num_rows > 0) {
    $subjectRow = $subjectResult->fetch_assoc();
    $subjectName = $subjectRow['subjectName'];
} else {
    $subjectName = "Unknown Subject";
}
$subjectStmt->close();

// Fetch categorized materials
$categorizedMaterials = [];
$sqlCategorized = "SELECT 
            study_material.materialID,
            study_material.materialName,
            study_material.materialSize,
            unit.unitName,
            topic.topicName,
            GROUP_CONCAT(category.categoryName SEPARATOR ', ') AS categories
        FROM study_material
        INNER JOIN topic ON study_material.topicID = topic.topicID
        INNER JOIN unit ON topic.unitID = unit.unitID
        LEFT JOIN material_category ON study_material.materialID = material_category.materialID
        LEFT JOIN category ON material_category.categoryID = category.categoryID
        WHERE study_material.classID = ?";

$params = [$classID];
$types = "i";

if (!empty($studentID)) {
    $sqlCategorized .= " AND study_material.studentID = ?";
    $params[] = $studentID;
    $types .= "i";
}

if (!empty($categoryID)) {
    $sqlCategorized .= " AND category.categoryID = ?";
    $params[] = $categoryID;
    $types .= "i";
}

$sqlCategorized .= " GROUP BY study_material.materialID";

$stmtCategorized = $conn->prepare($sqlCategorized);
$stmtCategorized->bind_param($types, ...$params);
$stmtCategorized->execute();
$resultCategorized = $stmtCategorized->get_result();

if ($resultCategorized->num_rows > 0) {
    while ($row = $resultCategorized->fetch_assoc()) {
        $categorizedMaterials[] = $row;
    }
}
$stmtCategorized->close();

// Fetch individual materials
$individualMaterials = [];
$sqlIndividual = "SELECT 
            study_material.materialID,
            study_material.materialName,
            study_material.materialSize,
            unit.unitName,
            topic.topicName
        FROM study_material
        INNER JOIN topic ON study_material.topicID = topic.topicID
        INNER JOIN unit ON topic.unitID = unit.unitID
        WHERE study_material.classID = ? AND study_material.studentID = ?";
$stmtIndividual = $conn->prepare($sqlIndividual);
$stmtIndividual->bind_param("ii", $classID, $studentID);
$stmtIndividual->execute();
$resultIndividual = $stmtIndividual->get_result();

if ($resultIndividual->num_rows > 0) {
    while ($row = $resultIndividual->fetch_assoc()) {
        $individualMaterials[] = $row;
    }
}
$stmtIndividual->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materials</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleMaterials() {
            var categorizedMaterials = document.getElementById('categorized-materials');
            var individualMaterials = document.getElementById('individual-materials');
            if (document.getElementById('categorizedRadio').checked) {
                categorizedMaterials.style.display = 'block';
                individualMaterials.style.display = 'none';
            } else {
                categorizedMaterials.style.display = 'none';
                individualMaterials.style.display = 'block';
            }
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
                <div class="title">Class: <?php echo htmlspecialchars($className) . ' ' . htmlspecialchars($subjectName); ?></div>
                <div class="profile">
                    <form action="uploadForm.php" method="post">
                        <input type="hidden" name="classID" value="<?php echo htmlspecialchars($classID); ?>">
                        <input type="hidden" name="subjectID" value="<?php echo htmlspecialchars($subjectID); ?>">
                        <input type="hidden" name="className" value="<?php echo htmlspecialchars($className); ?>">
                        <input type="hidden" name="subjectName" value="<?php echo htmlspecialchars($subjectName); ?>">
                        <button type="submit">Upload</button>
                    </form>
                </div>
            </div>
            <div class="section">
                <div class="section-title">Uploaded Materials</div>
                <form method="get" action="">
                    <input type="hidden" name="classID" value="<?php echo htmlspecialchars($classID); ?>">
                    <input type="hidden" name="subjectID" value="<?php echo htmlspecialchars($subjectID); ?>">
                    <label for="studentID">Filter by Student ID:</label>
                    <input type="text" id="studentID" name="studentID" value="<?php echo htmlspecialchars($studentID); ?>">
                    <label for="categoryID">Filter by Category ID:</label>
                    <input type="text" id="categoryID" name="categoryID" value="<?php echo htmlspecialchars($categoryID); ?>">
                    <button type="submit">Filter</button>
                </form>
                <div class="toggle-container">
                    <input type="radio" id="categorizedRadio" name="materialType" value="categorized" onclick="toggleMaterials()" checked>
                    <label for="categorizedRadio">Categorized Materials</label>
                    <input type="radio" id="individualRadio" name="materialType" value="individual" onclick="toggleMaterials()">
                    <label for="individualRadio">Individual Materials</label>
                </div>
                <div id="categorized-materials">
                    <?php if (!empty($categorizedMaterials)): ?>
                        <?php foreach ($categorizedMaterials as $material): ?>
                            <div class="subject-container">
                                <div>
                                    <h3><?php echo htmlspecialchars($material['unitName']); ?></h3>
                                    <h2><?php echo htmlspecialchars($material['topicName']); ?></h2>
                                    <p>File: <?php echo htmlspecialchars($material['materialName']); ?> (<?php echo number_format($material['materialSize'] / 1024, 2); ?> KB)</p>
                                    <p>Category: <?php echo htmlspecialchars($material['categories']); ?></p>
                                </div>
                                <a href="viewAssignments.php?materialID=<?php echo $material['materialID']?>"><button name="viewBtn">View Answers</button></a>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No categorized materials found for this class.</p>
                    <?php endif; ?>
                </div>
                <div id="individual-materials" style="display: none;">
                    <?php if (!empty($individualMaterials)): ?>
                        <?php foreach ($individualMaterials as $material): ?>
                            <div class="subject-container">
                                <div>
                                    <h3><?php echo htmlspecialchars($material['unitName']); ?></h3>
                                    <h2><?php echo htmlspecialchars($material['topicName']); ?></h2>
                                    <p>File: <?php echo htmlspecialchars($material['materialName']); ?> (<?php echo number_format($material['materialSize'] / 1024, 2); ?> KB)</p>
                                </div>
                                <a href="viewAssignments.php?materialID=<?php echo $material['materialID']?>"><button name="viewBtn">View Answers</button></a>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No individual materials found for this class.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="bottom-box">
                <div class="button"></div>
            </div>
        </div>
    </div>
</body>
</html>
