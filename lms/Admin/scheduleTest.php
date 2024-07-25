<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Tests</p>
            </div>
        </div>
        <div class="box-1">
            <div class="search-bar">
                <ul>
                    <li class="search">
                        <form action="tests.php" method="post" id="searchForm">
                            <i onclick="submitForm()" class="bx bx-search-alt-2"></i>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="table-section-item">
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>Test ID</th>
                        <th>Grade</th>
                        <th>Term</th>
                        <th>Year</th>
                        <th>GIVE MARKS</th>
                        <th>DELETE</th>
                    </tr>
                    <?php
                    // SQL query to fetch test information along with grade and subject names
                    $query = "
                        SELECT 
                            tt.testID,
                            g.grade,
                            g.gradeID,
                            tt.term,
                            tt.year
                        FROM 
                            term_test tt
                        INNER JOIN 
                            grade g ON tt.gradeID = g.gradeID
                    ";

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<form action='addMarks.php' method='post'>
                            <tr>
                                    <td>{$row['testID']}</td>
                                    <td>{$row['grade']}</td>
                                    <td>{$row['term']}</td>
                                    <td>{$row['year']}</td>
                                    <td>
                                        <input type='hidden' name='testID' value='{$row['testID']}'>
                                        <input type='hidden' name='gradeID' value='{$row['gradeID']}'>
                                        <button id='update' name='giveMarksButton'>Give Marks</button>
                                    </td>
                                    <td>
                                        
                                            <button id='bin' name='deleteSpecialBus'><i class='ri-delete-bin-line'></i></button>

                                    </td>
                                </tr></form>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No tests found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <div class="bottom-box">
        <div class="button">
            <form action='addTest.php' method='post'>
                <button name="addNewTest" id="popupButtonItem" class="submit">
                    Add a new Test
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>