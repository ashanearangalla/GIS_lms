<?php
session_start();
require_once("../db_conn.php");
include("parentSidemenu.php");

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
            a.marks,
            t.topicName,
            GROUP_CONCAT(c.categoryName SEPARATOR ', ') AS categories
        FROM study_material sm
        JOIN topic t ON sm.topicID = t.topicID
        JOIN unit u ON t.unitID = u.unitID
        LEFT JOIN material_category mc ON sm.materialID = mc.materialID
        LEFT JOIN category c ON mc.categoryID = c.categoryID
        LEFT JOIN assignments a ON sm.materialID = a.materialID AND a.studentID = ?
        WHERE u.subjectID = ? AND mc.categoryID IN ($categoryIDString)
        GROUP BY sm.materialID
    ";
    $stmtMaterials = $conn->prepare($sqlMaterials);
    $stmtMaterials->bind_param("si", $studentID, $subjectID);
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
        t.topicName,
        u.unitName,
        a.marks
    FROM study_material sm
    INNER JOIN topic t ON sm.topicID = t.topicID
    INNER JOIN unit u ON t.unitID = u.unitID
    LEFT JOIN assignments a ON sm.materialID = a.materialID AND a.studentID = ?
    WHERE sm.studentID = ? AND u.subjectID = ?
";
$stmtIndividualMaterials = $conn->prepare($sqlIndividualMaterials);
$stmtIndividualMaterials->bind_param("ssi", $studentID, $studentID, $subjectID);
$stmtIndividualMaterials->execute();
$resultIndividualMaterials = $stmtIndividualMaterials->get_result();

$individualMaterials = [];
if ($resultIndividualMaterials->num_rows > 0) {
    while ($row = $resultIndividualMaterials->fetch_assoc()) {
        $individualMaterials[] = $row;
    }
}
$stmtIndividualMaterials->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Assignments</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-content">
        <div class="heading-box">
            <div class="box-1">
                <div class="title">
                    <p>Assignments for <?php echo htmlspecialchars($subjectName); ?></p>
                </div>
            </div>
        </div>

        <div class="table-section-item">
            <div class="table-container-item">
                <div class="table-box">
                    <table id="rows-def">
                        <tr id="table-head">
                            <th>Unit Name</th>
                            <th>Topic Name</th>
                            <th>Material Name</th>
                            <th>Upload Date</th>
                            <th>Status</th>
                            <th>Marks</th>
                        </tr>
                        <?php
                        $allMaterials = array_merge($materials, $individualMaterials);
                        if (count($allMaterials) > 0) {
                            foreach ($allMaterials as $material) {
                                $status = isset($material['marks']) ? "Submitted" : "Not Marked";
                                $marks = isset($material['marks']) ? $material['marks'] : "-";
                                echo "<tr>
                                        <td>{$material['unitName']}</td>
                                        <td>{$material['topicName']}</td>
                                        <td>{$material['materialName']}</td>
                                        <td>{$material['uploadDate']}</td>
                                        <td>{$status}</td>
                                        <td>{$marks}</td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No materials found for the selected subject.</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="bottom-box">
            <div class="button">
                <!-- Add any additional buttons or controls here -->
            </div>
        </div>
    </div>
</body>
</html>