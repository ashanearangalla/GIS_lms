<?php
session_start();
require_once("../db_conn.php");

include("sidemenu.php");

// Initialize variables
$grade = "";
$gradeID = null;

// Check if editing an existing grade
if (isset($_POST['editGradeButton']) && isset($_POST['gradeID'])) {
    $gradeID = $_POST['gradeID'];

    // Fetch grade details from the database
    $sqlGrade = "SELECT gradeID, grade FROM grade WHERE gradeID = ?";
    $stmtGrade = $conn->prepare($sqlGrade);
    $stmtGrade->bind_param("i", $gradeID);
    $stmtGrade->execute();
    $resultGrade = $stmtGrade->get_result();

    if ($row = $resultGrade->fetch_assoc()) {
        $gradeID = $row['gradeID'];
        $grade = $row['grade'];
    }
    $stmtGrade->close();
}

// Fetch all subjects from the database
$sqlSubjects = "SELECT * FROM subject ORDER BY subjectName ASC";
$resultSubjects = mysqli_query($conn, $sqlSubjects);

// Fetch existing subjects for the grade if editing
$existingSubjects = [];
if ($gradeID) {
    $sqlExistingSubjects = "SELECT gs.subjectID, s.subjectName FROM grade_subject gs JOIN subject s ON gs.subjectID = s.subjectID WHERE gs.gradeID = ?";
    $stmtExistingSubjects = $conn->prepare($sqlExistingSubjects);
    $stmtExistingSubjects->bind_param("i", $gradeID);
    $stmtExistingSubjects->execute();
    $resultExistingSubjects = $stmtExistingSubjects->get_result();

    while ($row = $resultExistingSubjects->fetch_assoc()) {
        $existingSubjects[$row['subjectID']] = $row['subjectName'];
    }
    $stmtExistingSubjects->close();
}
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p><?php echo isset($gradeID) ? 'Edit Grade' : 'Add Grade'; ?></p>
            </div>
        </div>
    </div>

    <div class="course-container">
        <div class="section">
            <div class="section-title"><?php echo isset($gradeID) ? 'Edit Grade' : 'Add Grade'; ?></div>
            <form action="db_model_admin.php" method="post" onsubmit="return validateForm()">
                <div class="form-container">
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="grade">Grade:</label>
                            <input class="divided-input-popup-item" placeholder="Grade" type="text" name="grade" id="grade" value="<?php echo htmlspecialchars($grade); ?>" required/>
                        </div>
                    </div>
                    
                    <div class="inputs-column">
                        <div class="col1-popup-item">
                            <label class="labels-popup-item" for="subjects">Subjects:</label><br>
                            <button type="button" id="addSubjectButton">Add Subject <i class="bi bi-plus"></i></button>
                        </div>
                        <div class="col1-popup-item-dropdown-box" id="subjectsContainer">
                            <!-- Display existing subjects for editing -->
                            <?php foreach ($existingSubjects as $subjectID => $subjectName): ?>
                                <div class="subjectInput">
                                    <select class="divided-input-popup-item-drop" name="subjectID<?php echo $subjectID; ?>" required>
                                        <option value="<?php echo $subjectID; ?>" selected><?php echo htmlspecialchars($subjectName); ?></option>
                                        <?php mysqli_data_seek($resultSubjects, 0); // Reset result set pointer ?>
                                        <?php while ($row = mysqli_fetch_assoc($resultSubjects)): ?>
                                            <?php if ($row['subjectID'] !== $subjectID): ?>
                                                <option value="<?php echo $row['subjectID']; ?>"><?php echo htmlspecialchars($row['subjectName']); ?></option>
                                            <?php endif; ?>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="button" id="subjectRemoveButton" onclick="removeSubject(this)">-</button>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="button-box-form">
                            <button name="<?php echo isset($gradeID) ? 'updateGradeButton' : 'addGradeButton'; ?>" type="submit">
                                <?php echo isset($gradeID) ? 'Update' : 'Submit'; ?>
                            </button>
                            <?php if (isset($gradeID)) { ?>
                                <input type="hidden" name="gradeID" value="<?php echo $gradeID; ?>"/>
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
    document.addEventListener("DOMContentLoaded", function () {
        const addSubjectButton = document.getElementById("addSubjectButton");
        let totalSubjects = <?php echo count($existingSubjects); ?>; // Initialize total subjects count based on existing subjects

        addSubjectButton.addEventListener("click", function () {
            if (totalSubjects < 8) { // Check if the subject count is less than 8
                addSubjectInput();
                totalSubjects++;
            } else {
                alert("You can only add up to 8 subjects.");
            }
        });

        function addSubjectInput() {
            const subjectsContainer = document.getElementById("subjectsContainer");
            const newSubjectInput = document.createElement("div");
            newSubjectInput.classList.add("subjectInput");

            const subjectSelect = document.createElement("select");
            subjectSelect.classList.add("divided-input-popup-item-drop");
            subjectSelect.setAttribute("name", `subjectID${totalSubjects}`);
            subjectSelect.setAttribute("required", "required");

            <?php mysqli_data_seek($resultSubjects, 0); // Reset result set pointer ?>
            <?php while ($row = mysqli_fetch_assoc($resultSubjects)): ?>
                const option<?php echo $row['subjectID']; ?> = document.createElement('option');
                option<?php echo $row['subjectID']; ?>.value = '<?php echo $row['subjectID']; ?>';
                option<?php echo $row['subjectID']; ?>.textContent = '<?php echo htmlspecialchars($row['subjectName']); ?>';
                subjectSelect.appendChild(option<?php echo $row['subjectID']; ?>);
            <?php endwhile; ?>

            const removeButton = document.createElement("button");
            removeButton.setAttribute("type", "button");
            removeButton.setAttribute("id", "subjectRemoveButton");
            removeButton.textContent = "-";
            removeButton.addEventListener("click", function () {
                subjectsContainer.removeChild(newSubjectInput);
                totalSubjects--;
            });

            newSubjectInput.appendChild(subjectSelect);
            newSubjectInput.appendChild(removeButton);
            subjectsContainer.appendChild(newSubjectInput);
        }

        // Function to remove subject input
        function removeSubject(button) {
            const subjectInput = button.parentNode;
            subjectInput.parentNode.removeChild(subjectInput);
            totalSubjects--;
        }
    });

    function validateForm() {
        var grade = document.getElementById('grade').value.trim();
        if (grade === '') {
            alert('Please fill in the grade');
            return false;
        }

        var subjects = document.querySelectorAll('select[name^="subjectID"]');
        var subjectSet = new Set();

        for (var i = 0; i < subjects.length; i++) {
            var subjectValue = subjects[i].value;
            if (subjectSet.has(subjectValue)) {
                alert('Duplicate subjects are not allowed.');
                return false;
            }
            subjectSet.add(subjectValue);
        }
        return true;
    }
</script>
</body>
</html>
