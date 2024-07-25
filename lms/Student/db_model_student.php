<?php
session_start();
require_once("../db_conn.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "Student") {
    echo '<script>window.location = "../login.php";</script>';
    die();
}

if (isset($_POST["uploadAssignment"])) {
    $materialID = $_POST["materialID"];
    $subjectID = $_POST["subjectID"];
    $studentID = $_SESSION['user']['userID'];

    // Check if file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "pdf" => "application/pdf", "doc" => "application/msword", "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        $fileName = $_FILES["file"]["name"];
        $fileType = $_FILES["file"]["type"];
        $fileSize = $_FILES["file"]["size"];

        // Verify file extension
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            die("Error: Please select a valid file format.");
        }

        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($fileSize > $maxsize) {
            die("Error: File size is larger than the allowed limit.");
        }

               // Verify MIME type of the file
               if (in_array($fileType, $allowed)) {
                // Check whether file exists before uploading it
                
                    move_uploaded_file($_FILES["file"]["tmp_name"], "../uploads/" . $fileName);
                    
                    // Insert assinment details into the database
                    $sql = "INSERT INTO assignments (materialID, studentID, assignmentName, assignmentSize, assignmentType, uploadDate) VALUES (?, ?, ?, ?, ?, NOW())";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("issss", $materialID, $studentID, $fileName, $fileSize, $fileType);
                        if ($stmt->execute()) {
                            $_SESSION["upload_status"] = "Success: Assignment uploaded successfully.";
                        } else {
                            $_SESSION["upload_status"] = "Error: Could not save the assignment to the database.";
                        }
                        $stmt->close();
                    } else {
                        $_SESSION["upload_status"] = "Error: Could not prepare the SQL statement.";
                    }
    
                    header("Location: viewMaterials.php?subjectID=$subjectID");
                    exit();
                
            } else {
                $_SESSION["upload_status"] = "Error: There was a problem uploading your file - please try again.";
                header("Location: viewMaterials.php?subjectID=$subjectID");
                exit();
            }
        } else {
            $_SESSION["upload_status"] = "Error: " . $_FILES["file"]["error"];
            header("Location: viewMaterials.php?subjectID=$subjectID");
            exit();
        }
    } else {
        $_SESSION["upload_status"] = "Error: No file uploaded.";
        header("Location: viewMaterials.php?subjectID=$subjectID");
        exit();
    }
    
    $conn->close();
    ?>