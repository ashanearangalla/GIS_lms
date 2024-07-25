<?php
session_start();
// Assuming you have already established a database connection in $conn
require_once("../db_conn.php");


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {



        if (isset($_POST["addGradeButton"])) {
            // Add new grade
            $grade = mysqli_real_escape_string($conn, $_POST['grade']);
            $sqlInsertGrade = "INSERT INTO grade (grade) VALUES ('$grade')";
            if (mysqli_query($conn, $sqlInsertGrade)) {
                $gradeID = mysqli_insert_id($conn); // Get the last inserted grade ID
    
                // Insert each selected subject into grade_subject table
                $totalSubjects = count($_POST) - 2; // -2 for grade and addGradeButton
                for ($i = 0; $i < $totalSubjects; $i++) {
                    if (isset($_POST["subjectID$i"])) {
                        $subjectID = mysqli_real_escape_string($conn, $_POST["subjectID$i"]);
                        $insertSubjectQuery = "INSERT INTO grade_subject (gradeID, subjectID) VALUES ('$gradeID', '$subjectID')";
                        mysqli_query($conn, $insertSubjectQuery);
                    }
                }
    
                // Redirect after successful insertion
                header("Location: manageGrade.php");
                exit;
            } else {
                echo "Error inserting grade: " . mysqli_error($conn);
            }
        } elseif (isset($_POST["updateGradeButton"])) {
            // Update existing grade
            $gradeID = mysqli_real_escape_string($conn, $_POST['gradeID']);
            $grade = mysqli_real_escape_string($conn, $_POST['grade']);
            $sqlUpdateGrade = "UPDATE grade SET grade = '$grade' WHERE gradeID = '$gradeID'";
            if (mysqli_query($conn, $sqlUpdateGrade)) {
                // Clear existing subjects for this grade
                $clearSubjectsQuery = "DELETE FROM grade_subject WHERE gradeID = '$gradeID'";
                mysqli_query($conn, $clearSubjectsQuery);
    
                // Insert each selected subject into grade_subject table
                $totalSubjects = count($_POST) - 3; // -3 for gradeID, grade, and updateGradeButton
                for ($i = 0; $i < $totalSubjects; $i++) {
                    if (isset($_POST["subjectID$i"])) {
                        $subjectID = mysqli_real_escape_string($conn, $_POST["subjectID$i"]);
                        $insertSubjectQuery = "INSERT INTO grade_subject (gradeID, subjectID) VALUES ('$gradeID', '$subjectID')";
                        mysqli_query($conn, $insertSubjectQuery);
                    }
                }
    
                // Redirect after successful update
                header("Location: manageGrade.php");
                exit;
            } else {
                echo "Error updating grade: " . mysqli_error($conn);
            }
        }
    


    if (isset($_POST['deleteGradeButton'])) {
        $gradeID = mysqli_real_escape_string($conn, $_POST['gradeID']);
        $sqlDeleteGrade = "DELETE FROM grade WHERE gradeID = '$gradeID'";
        if (mysqli_query($conn, $sqlDeleteGrade)) {
            // Redirect after successful deletion
            header("Location: manageGrade.php");
            exit;
        } else {
            echo "Error deleting grade: " . mysqli_error($conn);
        }
    }


    if (isset($_POST["addSubjectButton"])) {
        // Add new subject
        $subjectName = mysqli_real_escape_string($conn, $_POST['subjectName']);
        $sqlInsertSubject = "INSERT INTO subject (subjectName) VALUES ('$subjectName')";
        if (mysqli_query($conn, $sqlInsertSubject)) {
            // Redirect after successful insertion
            header("Location: manageSubject.php");
            exit;
        } else {
            echo "Error inserting subject: " . mysqli_error($conn);
        }
    } elseif (isset($_POST["updateSubjectButton"])) {
        // Update existing subject
        $subjectID = mysqli_real_escape_string($conn, $_POST['subjectID']);
        $subjectName = mysqli_real_escape_string($conn, $_POST['subjectName']);
        $sqlUpdateSubject = "UPDATE subject SET subjectName = '$subjectName' WHERE subjectID = '$subjectID'";
        if (mysqli_query($conn, $sqlUpdateSubject)) {
            // Redirect after successful update
            header("Location: manageSubject.php");
            exit;
        } else {
            echo "Error updating subject: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["deleteSubjectButton"])) {
        $subjectID = mysqli_real_escape_string($conn, $_POST['subjectID']);
        $sqlDeleteSubject = "DELETE FROM subject WHERE subjectID = '$subjectID'";
        if (mysqli_query($conn, $sqlDeleteSubject)) {
            // Redirect after successful deletion
            header("Location: manageSubject.php");
            exit;
        } else {
            echo "Error deleting subject: " . mysqli_error($conn);
        }
    }

    // Function to generate unique student ID
    function generateStudentID($conn)
    {
        $sql = "SELECT MAX(studentNo) AS max_student_no FROM `student`";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $maxStudentNo = $row['max_student_no'];
        if ($maxStudentNo === null) {
            $newStudentNo = 1;
        } else {
            $newStudentNo = intval($maxStudentNo) + 1;
        }
        return $newStudentNo;
    }

    function generateAdminID($conn)
    {
        $sql = "SELECT MAX(adminNo) AS max_admin_no FROM `admin`";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $maxAdminNo = $row['max_admin_no'];
        if ($maxAdminNo === null) {
            $newAdminNo = 1;
        } else {
            $newAdminNo = intval($maxAdminNo) + 1;
        }
        return $newAdminNo;
    }

    // Function to generate unique parent ID
    function generateParentID($conn)
    {
        $sql = "SELECT MAX(parentNo) AS max_parent_no FROM `parent`";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $maxParentNo = $row['max_parent_no'];
        if ($maxParentNo === null) {
            $newParentNo = 1;
        } else {
            $newParentNo = intval($maxParentNo) + 1;
        }
        return $newParentNo;
    }

    // Function to generate random password
    function generatePassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $password;
    }

    if (isset($_POST["addStudentButton"])) {
        // Retrieve form data
        $fName = mysqli_real_escape_string($conn, $_POST['fName']);
        $lName = mysqli_real_escape_string($conn, $_POST['lName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $parentFName = mysqli_real_escape_string($conn, $_POST['parentFName']);
        $parentLName = mysqli_real_escape_string($conn, $_POST['parentLName']);
        $parentEmail = mysqli_real_escape_string($conn, $_POST['parentEmail']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $classID = $_POST['classID'];
    
        // Generate passwords
        $password = generatePassword();
        $parentPassword = generatePassword();
    
        // Generate unique IDs and numbers
        $studentNo = generateStudentID($conn);
        $parentNo = generateParentID($conn);
        $studentID = 's' . $studentNo;
        $parentID = 'p' . $parentNo;
    
        // Handle file upload
        $img_upload_path = ''; // Define upload path
        if (isset($_FILES['image'])) {
            $image = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_ex = pathinfo($image, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png", "webp");
    
            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . "." . $img_ex_lc;
                $img_upload_path = "../images/" . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
            } else {
                echo "Image format not allowed.";
                exit;
            }
        }
    
        // Retrieve gradeID from class table
        $sqlGetGradeID = "SELECT gradeID FROM `class` WHERE classID = '$classID'";
        $result = mysqli_query($conn, $sqlGetGradeID);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $gradeID = $row['gradeID'];
        } else {
            echo "Error fetching gradeID: " . mysqli_error($conn);
            exit;
        }
    
        // Insert into user table (for parent)
        $sqlInsertParentUser = "INSERT INTO `user` (userID, fName, lName, email, password, role) 
                               VALUES ('$parentID', '$parentFName', '$parentLName', '$parentEmail', '$parentPassword', 'Parent')";
        if (mysqli_query($conn, $sqlInsertParentUser)) {
            // Insert into parent table
            $sqlInsertParent = "INSERT INTO `parent` (parentID, parentNo, contact) 
                                VALUES ('$parentID', '$parentNo', '$contact')";
            if (mysqli_query($conn, $sqlInsertParent)) {
                // Insert into user table (for student)
                $sqlInsertStudentUser = "INSERT INTO `user` (userID, fName, lName, email, password, imageurl) 
                                        VALUES ('$studentID', '$fName', '$lName', '$email', '$password', '$new_img_name')";
                if (mysqli_query($conn, $sqlInsertStudentUser)) {
                    // Insert into student table with gradeID
                    $sqlInsertStudent = "INSERT INTO `student` (studentID, classID, parentID, studentNo, address, gradeID) 
                                        VALUES ('$studentID', '$classID', '$parentID', '$studentNo', '$address', '$gradeID')";
                    if (mysqli_query($conn, $sqlInsertStudent)) {
                        // Redirect after successful insertion
                        header("Location: manageStudent.php");
                        exit;
                    } else {
                        echo "Error inserting student: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error inserting user (student): " . mysqli_error($conn);
                }
            } else {
                echo "Error inserting parent: " . mysqli_error($conn);
            }
        } else {
            echo "Error inserting user (parent): " . mysqli_error($conn);
        }
    }



    if (isset($_POST["updateStudentButton"])) {
        // Retrieve form data
        $studentID = mysqli_real_escape_string($conn, $_POST['studentID']);
        $parentID = mysqli_real_escape_string($conn, $_POST['parentID']);
        $fName = mysqli_real_escape_string($conn, $_POST['fName']);
        $lName = mysqli_real_escape_string($conn, $_POST['lName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $parentFName = mysqli_real_escape_string($conn, $_POST['parentFName']);
        $parentLName = mysqli_real_escape_string($conn, $_POST['parentLName']);
        $parentEmail = mysqli_real_escape_string($conn, $_POST['parentEmail']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);
        $classID = $_POST['classID'];
        $new_img_name = "";

        // Handle file upload if a new image is provided
        if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
            $image = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_ex = pathinfo($image, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png", "webp");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . "." . $img_ex_lc;
                $img_upload_path = "../images/" . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
            } else {
                echo "Image format not allowed.";
                exit;
            }
        }

        // Retrieve gradeID from class table
        $sqlGetGradeID = "SELECT gradeID FROM `class` WHERE classID = '$classID'";
        $result = mysqli_query($conn, $sqlGetGradeID);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $gradeID = $row['gradeID'];
        } else {
            echo "Error fetching gradeID: " . mysqli_error($conn);
            exit;
        }

        // Update parent information
        $sqlUpdateParentUser = "UPDATE `user` SET fName='$parentFName', lName='$parentLName', email='$parentEmail' WHERE userID='$parentID'";
        if (mysqli_query($conn, $sqlUpdateParentUser)) {
            $sqlUpdateParent = "UPDATE `parent` SET contact='$contact' WHERE parentID='$parentID'";
            if (mysqli_query($conn, $sqlUpdateParent)) {
                // Update student information
                $sqlUpdateStudentUser = "UPDATE `user` SET fName='$fName', lName='$lName', email='$email'";
                if ($new_img_name != "") {
                    $sqlUpdateStudentUser .= ", imageurl='$new_img_name'";
                }
                $sqlUpdateStudentUser .= " WHERE userID='$studentID'";
                
                if (mysqli_query($conn, $sqlUpdateStudentUser)) {
                    $sqlUpdateStudent = "UPDATE `student` SET classID='$classID', address='$address', gradeID='$gradeID' WHERE studentID='$studentID'";
                    if (mysqli_query($conn, $sqlUpdateStudent)) {
                        // Redirect after successful update
                        header("Location: manageStudent.php");
                        exit;
                    } else {
                        echo "Error updating student: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error updating user (student): " . mysqli_error($conn);
                }
            } else {
                echo "Error updating parent: " . mysqli_error($conn);
            }
        } else {
            echo "Error updating user (parent): " . mysqli_error($conn);
        }
    }

    if (isset($_POST["deleteStudentButton"])) {
        $studentID = mysqli_real_escape_string($conn, $_POST['studentID']);
        $parentID = mysqli_real_escape_string($conn, $_POST['parentID']);
        $sqlDeleteStudent = "DELETE FROM user WHERE userID = '$studentID'";
        $sqlDeleteParent = "DELETE FROM user WHERE userID = '$parentID'";
        if (mysqli_query($conn, $sqlDeleteStudent)) {

            if (mysqli_query($conn, $sqlDeleteParent)) {
                header("Location: manageStudent.php");
                exit;
            } else {
                echo "Error deleting student: " . mysqli_error($conn);
            }


        } else {
            echo "Error deleting student: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["addTestButton"])) {
        // Sanitize and retrieve POST data
        $gradeID = mysqli_real_escape_string($conn, $_POST['gradeID']);
        $term = mysqli_real_escape_string($conn, $_POST['term']);
        $year = mysqli_real_escape_string($conn, $_POST['year']);

        // Insert into term_test table
        $insertTestQuery = "INSERT INTO `term_test` (`gradeID`, `term`, `year`) VALUES ('$gradeID', '$term', '$year')";
        if (mysqli_query($conn, $insertTestQuery)) {
            // Redirect or show success message
            // Example: redirect to a success page
            header("Location: scheduleTest.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }


    if (isset($_POST["createAccount"])) {
        // Sanitize and validate inputs
        $adminNo = generateAdminID($conn);
        $adminID = 'a' . $adminNo;
        $fName = mysqli_real_escape_string($conn, $_POST['fName']);
        $lName = mysqli_real_escape_string($conn, $_POST['lName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
      
    
        // Insert the new admin user into the database
        $query = "INSERT INTO user (userID, fName, lName, email, password, role, status) VALUES (?, ?, ?, ?, ?, 'Admin', 'Active')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $adminID, $fName, $lName, $email, $password);
    
        if ($stmt->execute()) {

            $sqlInsertAdmin = "INSERT INTO `admin` (adminID, adminNo) 
                                 VALUES ('$adminID', '$adminNo')";
            if (mysqli_query($conn, $sqlInsertAdmin)) {
                header("Location: manageUserAccount.php");
                exit;
            } else {
                echo "Error inserting admin: " . mysqli_error($conn);
            }

            
        } else {
            echo "<script>alert('Error creating account. Please try again.');</script>";
        }
    
        $stmt->close();
    }

    // Function to calculate grade based on marks
    function calculateGrade($marks) {
        if ($marks >= 80) {
            return 'A';
        } elseif ($marks >= 60) {
            return 'B';
        } elseif ($marks >= 40) {
            return 'C';
        } else {
            return 'F';
        }
    }
    
    // Function to get category ID based on grade
    function getCategoryID($conn, $grade) {
        $category = '';
        switch ($grade) {
            case 'A':
                $category = 'Excellent';
                break;
            case 'B':
                $category = 'Good';
                break;
            case 'C':
                $category = 'Average';
                break;
            case 'F':
                $category = 'Bad';
                break;
        }
        $sql = "SELECT categoryID FROM category WHERE categoryName = '$category'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['categoryID'];
        }
        return null;
    }



    if (isset($_POST["addMarksButton"])) {
        $testID = $_POST['testID'];
        $gradeID = $_POST['gradeID'];
        $classID = $_POST['classID'];
        $studentID = $_POST['studentID'];
    
        
    
        // Insert marks and grades into student_test table and update student_category table
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'subject_') === 0) {
                $subjectID = str_replace('subject_', '', $key);
                $marks = $value;
                $grade = calculateGrade($marks);
    
                // Insert into student_test table
                $sql = "INSERT INTO student_test_marks (studentID, testID, subjectID, marks, grade) VALUES ('$studentID', '$testID', '$subjectID', '$marks', '$grade')";
                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
    
                // Get category ID
                $categoryID = getCategoryID($conn, $grade);
                if ($categoryID !== null) {
                    // Check if the composite key (studentID, subjectID) exists in student_category table
                    $checkSql = "SELECT * FROM student_category WHERE studentID = '$studentID' AND subjectID = '$subjectID'";
                    $checkResult = $conn->query($checkSql);
                    if ($checkResult->num_rows > 0) {
                        // Update the existing record
                        $updateSql = "UPDATE student_category SET categoryID = '$categoryID' WHERE studentID = '$studentID' AND subjectID = '$subjectID'";
                        if ($conn->query($updateSql) !== TRUE) {
                            echo "Error: " . $updateSql . "<br>" . $conn->error;
                        }
                    } else {
                        // Insert new record
                        $insertSql = "INSERT INTO student_category (studentID, subjectID, categoryID) VALUES ('$studentID', '$subjectID', '$categoryID')";
                        if ($conn->query($insertSql) !== TRUE) {
                            echo "Error: " . $insertSql . "<br>" . $conn->error;
                        }
                    }
                }
            }
        }
    
        // Redirect back to the form or another page
        header("Location: scheduleTest.php");
        exit();
    }


    if (isset($_POST['updateMarksButton'])) {
        $testID = $_POST['testID'];
        $studentID = $_POST['studentID'];
    
        // Update marks and categories in student_test_marks and student_category tables
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'subject_') === 0) {
                $subjectID = str_replace('subject_', '', $key);
                $marks = $value;
                $grade = calculateGrade($marks);
    
                // Update or insert into student_test_marks table
                $updateSql = "UPDATE student_test_marks SET marks = '$marks', grade = '$grade' WHERE studentID = '$studentID' AND testID = '$testID' AND subjectID = '$subjectID'";
                $insertSql = "INSERT INTO student_test_marks (studentID, testID, subjectID, marks, grade) VALUES ('$studentID', '$testID', '$subjectID', '$marks', '$grade') ON DUPLICATE KEY UPDATE marks = '$marks', grade = '$grade'";
                
                if ($conn->query($updateSql) !== TRUE && $conn->query($insertSql) !== TRUE) {
                    echo "Error updating record: " . $conn->error;
                }
    
                // Update or insert into student_category table
                $categoryID = getCategoryID($conn, $grade);
                if ($categoryID !== null) {
                    $updateCategorySql = "UPDATE student_category SET categoryID = '$categoryID' WHERE studentID = '$studentID' AND subjectID = '$subjectID'";
                    $insertCategorySql = "INSERT INTO student_category (studentID, subjectID, categoryID) VALUES ('$studentID', '$subjectID', '$categoryID') ON DUPLICATE KEY UPDATE categoryID = '$categoryID'";
                    
                    if ($conn->query($updateCategorySql) !== TRUE && $conn->query($insertCategorySql) !== TRUE) {
                        echo "Error updating category: " . $conn->error;
                    }
                }
            }
        }
    
        // Redirect back to scheduleTest.php or another appropriate page
        header("Location: manageStudent.php");
        exit();
    }
    
    







    function generateTeacherID($conn)
    {
        $sql = "SELECT MAX(teacherNo) AS max_teacher_no FROM `teacher`";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $maxTeacherNo = $row['max_teacher_no'];
        if ($maxTeacherNo === null) {
            $newTeacherNo = 1;
        } else {
            $newTeacherNo = intval($maxTeacherNo) + 1;
        }
        return $newTeacherNo;
    }

    // Function to generate random password


    if (isset($_POST["addTeacherButton"])) {
        // Retrieve form data
        $fName = mysqli_real_escape_string($conn, $_POST['fName']);
        $lName = mysqli_real_escape_string($conn, $_POST['lName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        // Generate password
        $password = generatePassword();

        // Generate unique ID and number
        $teacherNo = generateTeacherID($conn);
        $teacherID = 't' . $teacherNo;

        // Handle file upload
        $img_upload_path = ''; // Define upload path
        if (isset($_FILES['image'])) {
            $image = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_ex = pathinfo($image, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png", "webp");

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . "." . $img_ex_lc;
                $img_upload_path = "../images/" . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
            } else {
                echo "Image format not allowed.";
                exit;
            }
        }

        // Insert into user table (for teacher)
        $sqlInsertTeacherUser = "INSERT INTO `user` (userID, fName, lName, email, password, imageurl, role) 
                                VALUES ('$teacherID', '$fName', '$lName', '$email', '$password', '$new_img_name', 'Teacher')";
        if (mysqli_query($conn, $sqlInsertTeacherUser)) {
            // Insert into teacher table
            $sqlInsertTeacher = "INSERT INTO `teacher` (teacherID, teacherNo) 
                                 VALUES ('$teacherID', '$teacherNo')";
            if (mysqli_query($conn, $sqlInsertTeacher)) {
                // Redirect after successful insertion
                header("Location: manageTeacher.php");
                exit;
            } else {
                echo "Error inserting teacher: " . mysqli_error($conn);
            }
        } else {
            echo "Error inserting user (teacher): " . mysqli_error($conn);
        }
    }

    if (isset($_POST["updateTeacherButton"])) {
        // Editing an existing teacher logic
    
        // Retrieve form data
        $teacherID = mysqli_real_escape_string($conn, $_POST['teacherID']);
        $fName = mysqli_real_escape_string($conn, $_POST['fName']);
        $lName = mysqli_real_escape_string($conn, $_POST['lName']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        
        // Handle file upload if a new image is provided
        $new_img_name = '';
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $image = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $img_ex = pathinfo($image, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array("jpg", "jpeg", "png", "webp");
    
            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . "." . $img_ex_lc;
                $img_upload_path = "../images/" . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
    
                // Update imageurl in the user table
                $sqlUpdateImage = "UPDATE `user` SET imageurl = '$new_img_name' WHERE userID = '$teacherID'";
                if (!mysqli_query($conn, $sqlUpdateImage)) {
                    echo "Error updating image: " . mysqli_error($conn);
                    exit;
                }
            } else {
                echo "Image format not allowed.";
                exit;
            }
        }
    
        // Update other details in the user table
        $sqlUpdateUser = "UPDATE `user` SET fName = '$fName', lName = '$lName', email = '$email' WHERE userID = '$teacherID'";
        if (mysqli_query($conn, $sqlUpdateUser)) {
            // Redirect after successful update
            header("Location: manageTeacher.php");
            exit;
        } else {
            echo "Error updating user (teacher): " . mysqli_error($conn);
        }
    } 

    if (isset($_POST['assignTeacherButton'])) {
        $teacherID = mysqli_real_escape_string($conn, $_POST['teacherID']);
        $classID = mysqli_real_escape_string($conn, $_POST['classID']);
        $subjectID = mysqli_real_escape_string($conn, $_POST['subjectID']);
    
        // Check if the assignment already exists
        $queryCheckAssignment = "SELECT * FROM teacher_subject WHERE classID = '$classID' AND subjectID = '$subjectID'";
        $resultCheckAssignment = $conn->query($queryCheckAssignment);
    
        if ($resultCheckAssignment->num_rows > 0) {
            echo "Teacher is already assigned to this class and subject.";
        } else {
            // Insert assignment into teacher_subject table
            $queryAssign = "INSERT INTO teacher_subject (teacherID, classID, subjectID) VALUES ('$teacherID', '$classID', '$subjectID')";
            
            // Attempt to execute the insert query
            if ($conn->query($queryAssign) === TRUE) {
                // Redirect to view page after successful insertion
                header("Location: viewTeacherClasses.php?teacherID=$teacherID");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    if (isset($_POST['deleteTeacherButton'])) {
        $teacherID = mysqli_real_escape_string($conn, $_POST['teacherID']);
        
        // Delete the teacher from the teacher_subject table first (if exists)
        $deleteTeacherSubjectQuery = "DELETE FROM teacher_subject WHERE teacherID = '$teacherID'";
        $conn->query($deleteTeacherSubjectQuery);
    
        // Delete the teacher from the teacher table
        $deleteTeacherQuery = "DELETE FROM teacher WHERE teacherID = '$teacherID'";
        $resultTeacherDelete = $conn->query($deleteTeacherQuery);
    
        // Delete the teacher from the user table
        $deleteUserQuery = "DELETE FROM user WHERE userID = '$teacherID'";
        $resultUserDelete = $conn->query($deleteUserQuery);
    
        if ($resultTeacherDelete && $resultUserDelete) {
            // Redirect to the manage teachers page with a success message
            header("Location: manageTeacher.php?status=success");
        } else {
            // Redirect to the manage teachers page with an error message
            header("Location: manageTeacher.php?status=error");
        }
        exit();
    }
    
    if (isset($_POST['deleteTeacherClassButton'])) {
        $teacherID = mysqli_real_escape_string($conn, $_POST['teacherID']);
        $classID = mysqli_real_escape_string($conn, $_POST['classID']);
        $subjectID = mysqli_real_escape_string($conn, $_POST['subjectID']);
    
        // Delete the assignment from teacher_subject table
        $query = "DELETE FROM teacher_subject WHERE teacherID = '$teacherID' AND classID = '$classID' AND subjectID = '$subjectID'";
        if ($conn->query($query) === TRUE) {
            header("Location: viewTeacherClasses.php?teacherID=$teacherID");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }


    // Handle adding a new class
if (isset($_POST['addClassButton'])) {
    $className = mysqli_real_escape_string($conn, $_POST['className']);
    $calendarYear = mysqli_real_escape_string($conn, $_POST['calendarYear']);
    $gradeID = mysqli_real_escape_string($conn, $_POST['gradeID']);
    $teacherID = mysqli_real_escape_string($conn, $_POST['teacherID']);

    // Check if the class already exists
    $queryCheckClass = "SELECT * FROM class WHERE className = '$className' AND calendarYear = '$calendarYear'";
    $resultCheckClass = $conn->query($queryCheckClass);

    if ($resultCheckClass->num_rows > 0) {
        header("Location: addClass.php?status=error");
        exit();
    } else {
        // Insert new class into the class table
        $queryAddClass = "INSERT INTO class (className, calendarYear, gradeID, teacherID) VALUES ('$className', '$calendarYear', '$gradeID', '$teacherID')";
        if ($conn->query($queryAddClass) === TRUE) {
            header("Location: manageClass.php");
            exit();
        } else {
            header("Location: addClass.php?status=error");
            exit();
        }
    }
}

// Handle updating an existing class
if (isset($_POST['updateClassButton'])) {
    $classID = mysqli_real_escape_string($conn, $_POST['classID']);
    $className = mysqli_real_escape_string($conn, $_POST['className']);
    $calendarYear = mysqli_real_escape_string($conn, $_POST['calendarYear']);
    $gradeID = mysqli_real_escape_string($conn, $_POST['gradeID']);
    $teacherID = mysqli_real_escape_string($conn, $_POST['teacherID']);

    // Update the class
    $queryUpdateClass = "UPDATE class SET className = '$className', calendarYear = '$calendarYear', gradeID = '$gradeID', teacherID = '$teacherID' WHERE classID = '$classID'";
    if ($conn->query($queryUpdateClass) === TRUE) {
        header("Location: manageClass.php");
        exit();
    } else {
        header("Location: addClass.php?status=error");
        exit();
    }
}

// Handle deleting a class
if (isset($_POST['deleteClassButton'])) {
    $classID = mysqli_real_escape_string($conn, $_POST['classID']);

    // Delete the class
    $queryDeleteClass = "DELETE FROM class WHERE classID = '$classID'";
    if ($conn->query($queryDeleteClass) === TRUE) {
        header("Location: manageClass.php");
        exit();
    } else {
        header("Location: manageClass.php?status=error");
        exit();
    }
}

if (isset($_POST['changePasswordButton'])) {
    $newPassword = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirmPassword']);
    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if ($newPassword === $confirmPassword) {
        

        $sql = "UPDATE user SET password = '$newPassword' WHERE userID = '$userID'";
        if (mysqli_query($conn, $sql)) {
            echo "Password updated successfully.";
            // Redirect to manage user accounts page after updating
            
        } else {
            echo "Error updating password: " . mysqli_error($conn);
        }
        header("Location: manageUserAccount.php?role=" . $role);

        
    } else {
        echo "Passwords do not match.";
    }
}



    // Close database connection
    mysqli_close($conn);
}
?>