<?php
session_start();
require_once("../db_conn.php");
include("studentSidemenu.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Student") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

$studentID = $_SESSION['user']['userID'];
$subjectID = isset($_GET["subjectID"]) ? $_GET["subjectID"] : '';

// Fetch subject name based on subjectID
$sqlSubjectName = "SELECT subjectName FROM subject WHERE subjectID = ?";
$stmtSubjectName = $conn->prepare($sqlSubjectName);
$stmtSubjectName->bind_param("i", $subjectID);
$stmtSubjectName->execute();
$resultSubjectName = $stmtSubjectName->get_result();
$subjectName = $resultSubjectName->fetch_assoc()['subjectName'];
$stmtSubjectName->close();

// Fetch student category based on studentID and subjectID
$sqlCategory = "
    SELECT sc.categoryID
    FROM student_category sc
    JOIN subject s ON sc.subjectID = s.subjectID
    WHERE sc.studentID = ? AND sc.subjectID = ?
";
$stmtCategory = $conn->prepare($sqlCategory);
$stmtCategory->bind_param("si", $studentID, $subjectID);
$stmtCategory->execute();
$resultCategory = $stmtCategory->get_result();
$categoryIDs = [];

while ($row = $resultCategory->fetch_assoc()) {
    $categoryIDs[] = $row['categoryID'];
}
$stmtCategory->close();

// Convert category IDs to string for SQL query
$categoryIDString = implode(",", $categoryIDs);

// Fetch category names
$categoryNames = [];
if (!empty($categoryIDString)) {
    $sqlCategoryNames = "SELECT categoryName FROM category WHERE categoryID IN ($categoryIDString)";
    $resultCategoryNames = mysqli_query($conn, $sqlCategoryNames);
    while ($row = mysqli_fetch_assoc($resultCategoryNames)) {
        $categoryNames[] = $row['categoryName'];
    }
}

// Fetch study materials based on student category and subject
$materials = [];
if (!empty($categoryIDString)) {
    $sqlMaterials = "
        SELECT 
            sm.materialID,
            sm.materialName,
            sm.materialSize,
            sm.uploadDate,
            u.unitName,
            t.topicName,
            GROUP_CONCAT(c.categoryName SEPARATOR ', ') AS categories
        FROM study_material sm
        JOIN topic t ON sm.topicID = t.topicID
        JOIN unit u ON t.unitID = u.unitID
        LEFT JOIN material_category mc ON sm.materialID = mc.materialID
        LEFT JOIN category c ON mc.categoryID = c.categoryID
        WHERE u.subjectID = ? AND mc.categoryID IN ($categoryIDString)
        GROUP BY sm.materialID
    ";
    $stmtMaterials = $conn->prepare($sqlMaterials);
    $stmtMaterials->bind_param("i", $subjectID);
    $stmtMaterials->execute();
    $resultMaterials = $stmtMaterials->get_result();

    if ($resultMaterials->num_rows > 0) {
        while ($row = $resultMaterials->fetch_assoc()) {
            $materials[] = $row;
        }
    }
    $stmtMaterials->close();
}

// Fetch study materials added individually for the student
$sqlIndividualMaterials = "
    SELECT 
        sm.materialID,
        sm.materialName,
        sm.materialSize,
        sm.uploadDate,
        t.topicName
    FROM study_material sm
    INNER JOIN topic t ON sm.topicID = t.topicID
    INNER JOIN unit u ON t.unitID = u.unitID
    WHERE sm.studentID = ? AND u.subjectID = ?
";
$stmtIndividualMaterials = $conn->prepare($sqlIndividualMaterials);
$stmtIndividualMaterials->bind_param("si", $studentID, $subjectID);
$stmtIndividualMaterials->execute();
$resultIndividualMaterials = $stmtIndividualMaterials->get_result();

$individualMaterials = [];
if ($resultIndividualMaterials->num_rows > 0) {
    while ($row = $resultIndividualMaterials->fetch_assoc()) {
        $individualMaterials[] = $row;
    }
}
$stmtIndividualMaterials->close();

// Initialize uploadedMaterials array
$uploadedMaterials = array_fill_keys(array_column($materials, 'materialID'), false);

// Check if the student has already uploaded an assignment for each material
foreach ($materials as $material) {
    $sqlCheckUpload = "
        SELECT COUNT(*) as uploadCount
        FROM assignments
        WHERE studentID = ? AND materialID = ?
    ";
    $stmtCheckUpload = $conn->prepare($sqlCheckUpload);
    $stmtCheckUpload->bind_param("si", $studentID, $material['materialID']);
    $stmtCheckUpload->execute();
    $resultCheckUpload = $stmtCheckUpload->get_result();
    $uploadCount = $resultCheckUpload->fetch_assoc()['uploadCount'];
    $uploadedMaterials[$material['materialID']] = $uploadCount > 0;
    $stmtCheckUpload->close();
}

// Check uploaded status for individual materials
$individualUploadedMaterials = [];
foreach ($individualMaterials as $material) {
    $sqlCheckUpload = "
        SELECT COUNT(*) as uploadCount
        FROM assignments
        WHERE studentID = ? AND materialID = ?
    ";
    $stmtCheckUpload = $conn->prepare($sqlCheckUpload);
    $stmtCheckUpload->bind_param("si", $studentID, $material['materialID']);
    $stmtCheckUpload->execute();
    $resultCheckUpload = $stmtCheckUpload->get_result();
    $uploadCount = $resultCheckUpload->fetch_assoc()['uploadCount'];
    $individualUploadedMaterials[$material['materialID']] = $uploadCount > 0;
    $stmtCheckUpload->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Study Materials</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        window.onload = function() {
            <?php if (isset($_SESSION["upload_status"])): ?>
                alert("<?php echo $_SESSION['upload_status']; ?>");
                <?php unset($_SESSION["upload_status"]); ?>
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>Study Materials</p>
                </div>
            </div>
        </div>
        <div class="course-container">
            <div class="header">
                <div class="title">Materials for Subject: <?php echo htmlspecialchars($subjectName); ?></div>
                <div class="profile">
                    <img style="object-fit: cover;" src="<?php echo '../images/'. $_SESSION['user']['imageurl']; ?>" alt="Profile Picture">
                    <span><?php echo htmlspecialchars($_SESSION['user']['fName'] . ' ' . $_SESSION['user']['lName']); ?></span>
                </div>
            </div>
            <div class="section">
                <div class="section-title">Study Materials</div>
                <?php if (!empty($materials) || !empty($individualMaterials)): ?>
                    <?php foreach ($materials as $material): ?>
                        <div class="subject-container">
                            <div>
                                <h3><?php echo htmlspecialchars($material['unitName']); ?></h3>
                                <h2><?php echo htmlspecialchars($material['topicName']); ?></h2>
                                <p>File: <?php echo htmlspecialchars($material['materialName']); ?> (<?php echo number_format($material['materialSize'] / 1024, 2); ?> KB)</p>
                                <p>Category: <?php echo htmlspecialchars($material['categories']); ?></p>
                                <p>Uploaded on: <?php echo htmlspecialchars($material['uploadDate']); ?></p>
                            </div>
                            <div>
                                <a href="../uploads/<?php echo htmlspecialchars($material['materialName']); ?>" download><button>Download</button></a>
                                <form action="assignmentUpload.php" method="post">
                                    <input type="hidden" name="materialID" value="<?php echo $material['materialID']; ?>">
                                    <input type="hidden" name="subjectID" value="<?php echo $subjectID; ?>">
                                    
                                    <button name="uploadBtn" style="<?php echo $uploadedMaterials[$material['materialID']] ? 'background-color: gray; color: white;' : ''; ?>" <?php if ($uploadedMaterials[$material['materialID']]) echo 'disabled'; ?>>Upload</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                    <div class="section-title">Individual Study Materials</div>
                    <?php foreach ($individualMaterials as $material): ?>
                        <div class="subject-container individual-material">
                            <div>
                                <h2><?php echo htmlspecialchars($material['topicName']); ?></h2>
                                <p>File: <?php echo htmlspecialchars($material['materialName']); ?> (<?php echo number_format($material['materialSize'] / 1024, 2); ?> KB)</p>
                                <p>Uploaded on: <?php echo htmlspecialchars($material['uploadDate']); ?></p>
                            </div>
                            <div>
                                <a href="../uploads/<?php echo htmlspecialchars($material['materialName']); ?>" download><button>Download</button></a>
                                <form action="assignmentUpload.php" method="post">
                                    <input type="hidden" name="materialID" value="<?php echo $material['materialID']; ?>">
                                    <input type="hidden" name="subjectID" value="<?php echo $subjectID; ?>">
                                    
                                    <button name="uploadBtn2" style="<?php echo $individualUploadedMaterials[$material['materialID']] ? 'background-color: gray; color: white;' : ''; ?>" <?php if ($individualUploadedMaterials[$material['materialID']]) echo 'disabled'; ?>>Upload</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No materials found for this subject.</p>
                <?php endif; ?>
            </div>
            <div class="bottom-box">
                <div class="button"></div>
            </div>
        </div>
    </div>
</body>
</html>