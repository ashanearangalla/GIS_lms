<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Fetch classes from the database
$sqlClasses = "SELECT * FROM `class`";
$resultClasses = mysqli_query($conn, $sqlClasses);

// Initialize variables

$parentID = "";
$fName = "";
$lName = "";
$email = "";
$image = "";
$address = "";
$classID = "";
$parentFName = "";
$parentLName = "";
$parentEmail = "";
$contact = "";

// Check if editing an existing student
if (isset($_POST['editStudentButton']) && isset($_POST['studentID']) && isset($_POST['parentID'])) {
    $studentID = $_POST['studentID'];
    $parentID = $_POST['parentID'];
    
    // Fetch student details from the database
    $sqlStudent = "SELECT 
                    s.studentID, 
                    s.parentID,
                    su.fName AS studentFName, 
                    su.lName AS studentLName, 
                    su.email AS studentEmail, 
                    su.imageurl AS studentImage, 
                    s.address AS studentAddress, 
                    s.classID, 
                    pu.fName AS parentFName, 
                    pu.lName AS parentLName, 
                    pu.email AS parentEmail, 
                    p.contact
                   FROM student s
                   JOIN user su ON s.studentID = su.userID
                   LEFT JOIN user pu ON s.parentID = pu.userID
                    LEFT JOIN parent p ON pu.userID = p.parentID
                   WHERE s.studentID = '$studentID'";
    
    $resultStudent = mysqli_query($conn, $sqlStudent);
    
    if ($row = mysqli_fetch_assoc($resultStudent)) {
        $studentID = $row['studentID'];
        $parentID = $row['parentID'];
        $fName = $row['studentFName'];
        $lName = $row['studentLName'];
        $email = $row['studentEmail'];
        $image = $row['studentImage'];
        $address = $row['studentAddress'];
        $classID = $row['classID'];
        $parentFName = $row['parentFName'];
        $parentLName = $row['parentLName'];
        $parentEmail = $row['parentEmail'];
        $contact = $row['contact'];
    }
}
?>
<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo isset($studentID) ? 'Edit Student' : 'Add Student'; ?></p>
            </div>
        </div>
    </div>

    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo isset($studentID) ? 'Edit Student' : 'Add Student'; ?></div>
            <form action="db_model_admin.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-container">
                    <!-- User Details -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="fName">First Name:</label>
                            <input class="divided-input-popup-item" placeholder="First Name" type="text" name="fName" id="fName" value="<?php echo $fName; ?>" required/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="lName">Last Name:</label>
                            <input class="divided-input-popup-item" placeholder="Last Name" type="text" name="lName" id="lName" value="<?php echo $lName; ?>" required/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="email">Email:</label>
                            <input class="divided-input-popup-item" placeholder="Email" type="email" name="email" id="email" value="<?php echo $email; ?>" required/>
                        </div>
                        
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="image">Profile Image:</label>
                            <?php if ($image): ?>
                                <img src="<?php echo '../images/'. $image; ?>" alt="Current Profile Image" style="width: 100px; object-fit:cover; height: 100px; display: block; margin-bottom: 10px;">
                                <input class="divided-input-popup-item" type="file" name="image" id="image" accept="image/*"/>
                            <?php else: ?>
                                <input class="divided-input-popup-item" type="file" name="image" id="image" required accept="image/*"/>
                            <?php endif; ?>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="address">Address:</label>
                            <textarea class="divided-input-popup-item" placeholder="Address" name="address" id="address" required><?php echo $address; ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Student and Parent Details -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="classID">Class:</label>
                            <select class="divided-input-popup-item" name="classID" id="classID" required>
                                <?php while ($row = mysqli_fetch_assoc($resultClasses)) { ?>
                                    <option value="<?php echo $row['classID']; ?>" <?php echo ($row['classID'] == $classID) ? 'selected' : ''; ?>><?php echo $row['className']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="parentFName">Parent's First Name:</label>
                            <input class="divided-input-popup-item" placeholder="Parent's First Name" type="text" name="parentFName" id="parentFName" value="<?php echo $parentFName; ?>" required/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="parentLName">Parent's Last Name:</label>
                            <input class="divided-input-popup-item" placeholder="Parent's Last Name" type="text" name="parentLName" id="parentLName" value="<?php echo $parentLName; ?>" required/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="parentEmail">Parent's Email:</label>
                            <input class="divided-input-popup-item" placeholder="Parent's Email" type="email" name="parentEmail" id="parentEmail" value="<?php echo $parentEmail; ?>" required/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="contact">Parent Contact:</label>
                            <input class="divided-input-popup-item" placeholder="Parent Contact" type="text" name="contact" id="contact" value="<?php echo $contact; ?>" required/>
                        </div>
                        
                        <div class="button-box-form">
                            <button name="<?php echo isset($studentID) ? 'updateStudentButton' : 'addStudentButton'; ?>" type="submit">
                                <?php echo isset($studentID) ? 'Update' : 'Submit'; ?>
                            </button>
                            <?php if (isset($studentID)) { ?>
                                <input type="hidden" name="studentID" value="<?php echo $studentID; ?>"/>
                                <input type="hidden" name="parentID" value="<?php echo $parentID; ?>"/>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="bottom-box">
            <div class="button">
            
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var fName = document.getElementById('fName').value.trim();
        var lName = document.getElementById('lName').value.trim();
        var email = document.getElementById('email').value.trim();
        var image = document.getElementById('image').value.trim();
        var address = document.getElementById('address').value.trim();
        var parentFName = document.getElementById('parentFName').value.trim();
        var parentLName = document.getElementById('parentLName').value.trim();
        var parentEmail = document.getElement
         document.getElementById('parentEmail').value.trim();
        var contact = document.getElementById('contact').value.trim();
        var classID = document.getElementById('classID').value.trim();

        if (fName === '' || lName === '' || email === '' || (!image && !<?php echo json_encode(isset($studentID)); ?>) || address === '' || parentFName === '' || parentLName === '' || parentEmail === '' || contact === '' || classID === '') {
            alert('Please fill in all fields');
            return false;
        }
        return true;
    }
</script>
</body>
</html>