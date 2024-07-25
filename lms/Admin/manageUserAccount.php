<?php
session_start();
require_once("../db_conn.php");

include("sidemenu.php");

$role = isset($_GET['role']) ? $_GET['role'] : 'Admin';

function fetchUserAccounts($conn, $role) {
    $query = "SELECT userID, fName, lName, email, role, status FROM user WHERE role = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $role);
    $stmt->execute();
    return $stmt->get_result();
}

$users = fetchUserAccounts($conn, $role);
?>

<div class="dashboard-content">
    <div class="heading-box">
        <div class="box-1">
            <div class="title">
                <p>Manage <?php echo ucfirst($role); ?> Accounts</p>
            </div>
        </div>
    </div>

    <div class="table-section-item">
        <div class="sort-button-box">
            <form action="manageUserAccount.php" method="get"><button class="filter" name="role" value="Teacher">Teachers</button></form>
            <form action="manageUserAccount.php" method="get"><button class="filter" name="role" value="Student">Students</button></form>
            <form action="manageUserAccount.php" method="get"><button class="filter" name="role" value="Parent">Parents</button></form>
            <form action="manageUserAccount.php" method="get"><button class="filter" name="role" value="Admin">Admins</button></form>
        </div>
        <div class="table-container-item">
            <div class="table-box">
                <table id="rows-def">
                    <tr id="table-head">
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Change Password</th>
                        <th>Activate/Deactivate</th>
                        
                    </tr>
                    <?php
                    if ($users->num_rows > 0) {
                        while ($row = $users->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['userID']}</td>
                                    <td>{$row['fName']}</td>
                                    <td>{$row['lName']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['status']}</td>
                                    <td>
                                        <form action='changePassword.php' method='post'>
                                            <input type='hidden' name='userID' value='{$row['userID']}'>
                                            <input type='hidden' name='role' value='{$row['role']}'>
                                            <button  id='update' name='changePasswordButtonManage' type='submit'>Change Password</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form action='toggleUserStatus.php' method='post'>
                                            <input type='hidden' name='userID' value='{$row['userID']}'>
                                            <button id='update' type='submit'>";
                                            
                                            if ($row['status'] == 'Active') {
                                                echo "Deactivate";
                                            } else {
                                                echo "Activate";
                                            }
                                            
                                            echo "</button>
                                        </form>
                                    </td>";
                                    
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No accounts found</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>

    <?php if ($role === 'Admin') { ?>
    <div class="bottom-box">
        <div class="button">
            <form action='createAccount.php' method='post'>
                <button name="createAccountButton" id="popupButtonItem" class="submit">
                    Create an Account
                </button>
            </form>
        </div>
    </div>
    <?php } ?>
</div>

</body>
</html>