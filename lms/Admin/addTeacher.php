<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Check if a teacher ID is passed for editing
$teacherID = isset($_POST['teacherID']) ? $_POST['teacherID'] : null;
$teacherData = null;

if ($teacherID) {
    // Fetch the teacher data for the given teacher ID
    $query = "SELECT * FROM user WHERE userID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $teacherID);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacherData = $result->fetch_assoc();
}

?>
<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo $teacherID ? 'Edit Teacher' : 'Add Teacher'; ?></p>
            </div>
        </div>
    </div>
    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo $teacherID ? 'Edit Teacher' : 'Add Teacher'; ?></div>
            <form action="db_model_admin.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-container">
                    <!-- User Details -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="fName">First Name:</label>
                            <input class="divided-input-popup-item" placeholder="First Name" type="text" name="fName" id="fName" required value="<?php echo $teacherData['fName'] ?? ''; ?>"/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="lName">Last Name:</label>
                            <input class="divided-input-popup-item" placeholder="Last Name" type="text" name="lName" id="lName" required value="<?php echo $teacherData['lName'] ?? ''; ?>"/>
                        </div>
                    </div>
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="email">Email:</label>
                            <input class="divided-input-popup-item" placeholder="Email" type="email" name="email" id="email" required value="<?php echo $teacherData['email'] ?? ''; ?>"/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="image">Profile Image:</label>
                            <?php if ($teacherData && !empty($teacherData['imageurl'])): ?>
                                <div>
                                    <img src="<?php echo '../images/' . $teacherData['imageurl']; ?>" alt="Current Profile Image" style="max-width: 100px; max-height: 100px;">
                                    <input type="hidden" id="current_image" name="current_image" value="<?php echo $teacherData['imageurl']; ?>">
                                </div>
                            <?php endif; ?>
                            <input class="divided-input-popup-item" type="file" name="image" id="image" accept="image/*"/>
                        </div>
                        <div class="button-box-form">
                            <button name="<?php echo $teacherID ? 'updateTeacherButton' : 'addTeacherButton'; ?>" type="submit">Submit</button>
                        </div>
                        <?php if ($teacherID): ?>
                            <input type="hidden" name="teacherID" value="<?php echo $teacherID; ?>">
                        <?php endif; ?>
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

        if (fName === '' || lName === '' || email === '' || (document.querySelector('[name="addTeacherButton"]') && image === '')) {
            alert('Please fill in all fields');
            return false;
        }
        return true;
    }
</script>
</body>
</html>