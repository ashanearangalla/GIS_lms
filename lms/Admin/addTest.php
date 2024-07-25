<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Fetch grades from the database in ascending order
$sqlGrades = "SELECT * FROM `grade` ORDER BY `grade` ASC";
$resultGrades = mysqli_query($conn, $sqlGrades);
?>
<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Add Test</p>
            </div>
        </div>
    </div>
    <div class="course-container">
        <div class="section">
            <div class="section-title">Add Test</div>
            <form action="db_model_admin.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-container">
                    <!-- Test Details -->
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="gradeID">Grade:</label>
                            <select class="divided-input-popup-item" name="gradeID" id="gradeID" required>
                                <option value="" selected hidden>Select Grade</option>
                                <?php while ($row = mysqli_fetch_assoc($resultGrades)) { ?>
                                    <option value="<?php echo $row['gradeID']; ?>"><?php echo $row['grade']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="term">Term:</label>
                            <select class="divided-input-popup-item" name="term" id="term" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="year">Year:</label>
                            <input class="divided-input-popup-item" placeholder="Year" type="number" name="year" id="year" required/>
                        </div>
                        <div class="button-box-form">
                            <button name="addTestButton" type="submit">Submit</button>
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
        const gradeID = document.getElementById('gradeID').value.trim();
        const term = document.getElementById('term').value.trim();
        const year = document.getElementById('year').value.trim();

        if (gradeID === '' || term === '' || year === '') {
            alert('Please fill in all fields');
            return false;
        }
        return true;
    }
</script>
</body>
</html>