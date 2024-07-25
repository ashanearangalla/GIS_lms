<?php
require_once("../db_conn.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file uploaded without errors
    if (isset($_FILES["fileName"]) && $_FILES["fileName"]["error"] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["fileName"]["name"]);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is allowed
        $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf", "docx");
        if (!in_array($file_type, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG, GIF, DOCX and PDF files are allowed.";
        } else {
            // Move the uploaded file into the specified directory
            if (move_uploaded_file($_FILES["fileName"]["tmp_name"], $target_file)) {
                // File upload success, now store the information in the database
                $filename = $_FILES["fileName"]["name"];
                $filesize = $_FILES["fileName"]["size"];
                $filetype = $_FILES["fileName"]["type"];
                $unit = $_POST['unit'];
                $topic = $_POST['topic'];
                $subjectID = $_POST['subjectID'];
                $classID = $_POST['classID'];
                $teacherID = $_POST['teacherID'];
                $studentID = isset($_POST['studentID']) ? $_POST['studentID'] : NULL;

                // Insert unit if not exists
                $unit_query = "INSERT INTO unit (subjectID, unitName) VALUES ($subjectID, '$unit')
                               ON DUPLICATE KEY UPDATE unitID=LAST_INSERT_ID(unitID)";
                $conn->query($unit_query);
                $unitID = $conn->insert_id;

                // Insert topic if not exists
                $topic_query = "INSERT INTO topic (unitID, topicName) VALUES ($unitID, '$topic')
                                ON DUPLICATE KEY UPDATE topicID=LAST_INSERT_ID(topicID)";
                $conn->query($topic_query);
                $topicID = $conn->insert_id;

                // Handle category checkboxes
                $categories = isset($_POST['category']) ? $_POST['category'] : [];
                $categoryIDs = [];
                foreach ($categories as $category) {
                    $category_query = "SELECT categoryID FROM category WHERE categoryName='$category'";
                    $category_result = $conn->query($category_query);
                    if ($category_result->num_rows > 0) {
                        $category_row = $category_result->fetch_assoc();
                        $categoryIDs[] = $category_row['categoryID'];
                    }
                }

                // Insert material
                $material_query = "INSERT INTO study_material (classID, studentID, topicID, teacherID, materialName, materialSize, materialType, uploadDate)
                                   VALUES ($classID, " . ($studentID ? "'$studentID'" : "NULL") . ", $topicID, '$teacherID', '$filename', $filesize, '$filetype', NOW())";
                $conn->query($material_query);
                $materialID = $conn->insert_id;

                // Insert materialID and categoryID into the material_category table if categories are selected
                if (empty($studentID) && !empty($categoryIDs)) {
                    foreach ($categoryIDs as $categoryID) {
                        $material_category_query = "INSERT INTO material_category (materialID, categoryID) VALUES ($materialID, $categoryID)";
                        $conn->query($material_category_query);
                    }
                }

                if ($conn->affected_rows > 0) {
                    echo "File has been uploaded and data inserted.";
                } else {
                    echo "Sorry, there was an error while uploading information to the database.";
                }

                $conn->close();
                header("Location: uploadMaterial.php?upload=success");
                exit(); 
            } else {
                echo "Sorry, there was an error while uploading your file.";
            }
        }
    } else {
        echo "No file was uploaded.";
    }
}
?>
