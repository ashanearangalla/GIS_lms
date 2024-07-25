<?php
session_start();
require_once("../db_conn.php");

include("sidemenu.php");

// Initialize variables

$subjectName = "";

// Check if editing an existing subject
if (isset($_POST['editSubjectButton']) && isset($_POST['subjectID'])) {
    $subjectID = $_POST['subjectID'];

    // Fetch subject details from the database
    $sqlSubject = "SELECT subjectID, subjectName FROM subject WHERE subjectID = '$subjectID'";
    $resultSubject = mysqli_query($conn, $sqlSubject);

    if ($row = mysqli_fetch_assoc($resultSubject)) {
        $subjectID = $row['subjectID'];
        $subjectName = $row['subjectName'];
    }
}
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo isset($subjectID) ? 'Edit Subject' : 'Add Subject'; ?></p>
            </div>
        </div>
    </div>

    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo isset($subjectID) ? 'Edit Subject' : 'Add Subject'; ?></div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">
                <div class="form-container">
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="subjectName">Subject Name:</label>
                            <input class="divided-input-popup-item" placeholder="Subject Name" type="text" name="subjectName" id="subjectName" value="<?php echo $subjectName; ?>" required/>
                        </div>

                        <div class="button-box-form">
                            <button name="<?php echo isset($subjectID) ? 'updateSubjectButton' : 'addSubjectButton'; ?>" type="submit">
                                <?php echo isset($subjectID) ? 'Update' : 'Submit'; ?>
                            </button>
                            <?php if (isset($subjectID)) { ?>
                                <input type="hidden" name="subjectID" value="<?php echo $subjectID; ?>"/>
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
        var subjectName = document.getElementById('subjectName').value.trim();

        if (subjectName === '') {
            alert('Please fill in the subject name');
            return false;
        }
        return true;
    }
</script>
</body>
</html>