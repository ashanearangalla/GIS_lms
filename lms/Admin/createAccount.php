<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Initialize variables
$userID = '';
$fName = '';
$lName = '';
$email = '';
$password = '';


?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Create Admin Account</p>
            </div>
        </div>
    </div>

    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo isset($_POST['editClassButton']) ? 'Edit Class' : 'Add Class'; ?></div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">

                <div class="form-container">
                    <div class="inputs-column">

                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="fName">First Name:</label>
                            <input class="divided-input-popup-item" type="text" name="fName" id="fName" placeholder="First Name" required value="<?php echo $fName; ?>" />
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="lName">Last Name:</label>
                            <input class="divided-input-popup-item" type="text" name="lName" id="lName" placeholder="Last Name" required value="<?php echo $lName; ?>" />
                        </div>

                    </div>
                    <div class="inputs-column">
                        
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="email">Email:</label>
                            <input class="divided-input-popup-item" type="email" name="email" id="email" placeholder="Email" required value="<?php echo $email; ?>" />
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="password">Password:</label>
                            <input class="divided-input-popup-item" type="password" name="password" placeholder="Password" id="password" required />
                        </div>
                        <div class="button-box-form">
                            <button name="createAccount" type="submit">Create Account</button>
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
</div>

<script>
    function validateForm() {
        
        var fName = document.getElementById('fName').value.trim();
        var lName = document.getElementById('lName').value.trim();
        var email = document.getElementById('email').value.trim();
        var password = document.getElementById('password').value.trim();

        if (fName === '' || lName === '' || email === '' || password === '') {
            alert('Please fill in all fields.');
            return false;
        }

        return true;
    }
</script>
</body>

</html>