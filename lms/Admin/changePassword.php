<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

$userID = '';
$password = '';

if (isset($_POST['changePasswordButtonManage'])) {
    $userID = mysqli_real_escape_string($conn, $_POST['userID']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
}

// Handle form submission

?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Change Password</p>
            </div>
        </div>
    </div>

    <div class="course-container">
        <div class="section">
            <div class="section-title">Change Password</div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">
                <div class="form-container">
                    <input type="hidden" name="userID" value="<?php echo $userID; ?>">
                    <input type="hidden" name="role" value="<?php echo $_POST['role']; ?>">
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="newPassword">New Password:</label>
                            <input class="divided-input-popup-item" placeholder="New Password" type="password" name="newPassword" id="newPassword" required/>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="confirmPassword">Confirm Password:</label>
                            <input class="divided-input-popup-item" placeholder="Confirm Password" type="password" name="confirmPassword" id="confirmPassword" required/>
                        </div>
                    </div>

                    <input type='hidden' name='subjectID' value=<?php echo $userID; ?>>
                    <input type='hidden' name='subjectID' value=<?php echo $role; ?>>

                    <div class="button-box-form">
                        <button name="changePasswordButton" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var newPassword = document.getElementById('newPassword').value.trim();
        var confirmPassword = document.getElementById('confirmPassword').value.trim();

        if (newPassword === '' || confirmPassword === '') {
            alert('Please fill in all fields.');
            return false;
        }

        if (newPassword !== confirmPassword) {
            alert('Passwords do not match.');
            return false;
        }

        return true;
    }
</script>
</body>
</html>