<?php
session_start();
require_once("db_conn.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['login'])) {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $errors = [];

        // Validate form inputs
        if (empty($username) || empty($password)) {
            $errors["login_incorrect"] = "Please fill in all required fields";
        } else {
            // Fetch user from the database
            $sql = "SELECT * FROM user WHERE userID='$username'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);

                // Verify password
                if ($password != $row["password"]) {
                    $errors["login_incorrect"] = "Incorrect login info!";
                }
            } else {
                $errors["login_incorrect"] = "Incorrect login info!";
            }
        }

        if ($errors) {
            $_SESSION["errors_login"] = $errors;
            header("Location: login.php?login=unsuccess");
            die();
        }

        // Base session variables for all users
        $_SESSION["user"] = [
            "userID" => $row["userID"],
            "email" => htmlspecialchars($row["email"]),
            "fName" => htmlspecialchars($row["fName"]),
            "lName" => htmlspecialchars($row["lName"]),
            "role" => htmlspecialchars($row["role"]),
            "imageurl" => htmlspecialchars($row["imageurl"])
        ];

        // Additional session variables for students
        if ($row["role"] === 'Student') {
            $studentSql = "SELECT classID, gradeID FROM student WHERE studentID = ?";
            $studentStmt = $conn->prepare($studentSql);
            $studentStmt->bind_param("s", $row["userID"]);
            $studentStmt->execute();
            $studentResult = $studentStmt->get_result();

            if ($studentResult->num_rows > 0) {
                $studentRow = $studentResult->fetch_assoc();
                $_SESSION["user"]["classID"] = $studentRow["classID"];
                $_SESSION["user"]["gradeID"] = $studentRow["gradeID"];
            }
            $studentStmt->close();
        }

        // Additional session variables for parents
        if ($row["role"] === 'Parent') {
            $parentSql = "
                SELECT 
                    s.studentID, 
                    s.classID, 
                    s.gradeID, 
                    u.fName AS studentFName, 
                    u.lName AS studentLName 
                FROM 
                    student s 
                JOIN 
                    user u ON s.studentID = u.userID 
                WHERE 
                    s.parentID = ?";
            $parentStmt = $conn->prepare($parentSql);
            $parentStmt->bind_param("s", $row["userID"]);
            $parentStmt->execute();
            $parentResult = $parentStmt->get_result();

            if ($parentResult->num_rows > 0) {
                $parentRow = $parentResult->fetch_assoc();
                $_SESSION["user"]["studentID"] = $parentRow["studentID"];
                $_SESSION["user"]["classID"] = $parentRow["classID"];
                $_SESSION["user"]["gradeID"] = $parentRow["gradeID"];
                $_SESSION["user"]["studentFName"] = htmlspecialchars($parentRow["studentFName"]);
                $_SESSION["user"]["studentLName"] = htmlspecialchars($parentRow["studentLName"]);
            }
            $parentStmt->close();
        }

        // Redirect based on user role
        switch ($row["role"]) {
            case 'Admin':
                echo '<script>window.location = "Admin/manageGrade.php";</script>';
                break;
            case 'Teacher':
                echo '<script>window.location = "Instructor/instructorDashboard.php";</script>';
                break;
            case 'Student':
                echo '<script>window.location = "Student/studentDashboard.php";</script>';
                break;
            case 'Parent':
                echo '<script>window.location = "Parent/parentDashboard.php";</script>';
                break;
            default:
                echo '<script>window.location = "index.php";</script>';
        }
        die();
    }
} else {
    header("Location: index.php");
    die();
}
?>