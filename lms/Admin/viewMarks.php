<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");

// Check if the required POST parameters are set
if (isset($_POST['studentID']) && isset($_POST['testID'])) {
    $studentID = $_POST['studentID'];
    $testID = $_POST['testID'];

    // Fetch the subject and marks data based on studentID and testID
    $queryMarks = "
        SELECT 
            stm.subjectID,
            s.subjectName,
            stm.marks
        FROM 
            student_test_marks stm
        INNER JOIN 
            subject s ON stm.subjectID = s.subjectID
        WHERE 
            stm.studentID = '$studentID' AND stm.testID = '$testID'
    ";

    $resultMarks = $conn->query($queryMarks);
} else {
    echo "No student ID or test ID provided.";
    exit;
}
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>View Marks</p>
            </div>
        </div>
    </div>

    <div class="table-section-item">
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>Subject ID</th>
                        <th>Subject Name</th>
                        <th>Marks</th>
                    </tr>
                    <?php
                    if ($resultMarks->num_rows > 0) {
                        while ($row = $resultMarks->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['subjectID']}</td>
                                    <td>{$row['subjectName']}</td>
                                    <td>{$row['marks']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No marks found for the selected test.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='viewTests.php' method='post'>
                <input type='hidden' name='studentID' value='<?php echo $studentID; ?>'>
                <button name="backToTests" class="submit">
                    Back to Tests
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>