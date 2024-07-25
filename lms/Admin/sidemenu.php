
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="stylead.css" />
    <link rel="stylesheet" href="../Admin/styleshee.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tomorrow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" 
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" 
    crossorigin="anonymous"></script>
    
</head>

<body>
    <div class="dashboard-container">
        <div class="sidemenu">
            <div class="logo">
                <img class="sub-logo" src="../images/logo.png" >
                <div class="logo-name">
                    <h2>Guidance International School</h2>
                </div>
            </div>
            
            <ul class="sidemenu-list">
                <li>
                    <a href="manageGrade.php"><i class="bx bxs-user-pin"></i>&nbsp; Manage Grade</a>
                </li>
                <li>
                    <a href="manageSubject.php"><i class='bx bxs-category-alt'></i>&nbsp; Manage Subject</a>
                </li>
                
                <li>
                    <a href="manageClass.php"><i class="bx bxs-dashboard"></i>&nbsp; Manage Class</a>
                </li>
                <li>
                    <a href="manageTeacher.php"><i class="bx bxs-category"></i>&nbsp; Manage Teacher</a>
                </li>
                <li id="active-main">
                    <a id="active-sub" href="manageStudent.php"><i class="bx bxs-tv"></i>&nbsp; Manage Student</a>
                </li>
                <li>
                    <a href="scheduleTest.php"><i class="bx bxs-cart-alt"></i>&nbsp; Tests</a>
                </li>
                
                <li>
                    <a href="manageUserAccount.php"><i class='bx bxs-category-alt'></i>&nbsp; User Accounts</a>
                </li>
                
                
                <li>
                    <a href="../logout.php"><i class="bx bxs-log-out"></i>&nbsp; Logout</a>
                </li>
            </ul>
            <div class="email">
                <a href=""><i id="ac" class="bx bxs-user-circle"></i>&nbsp; 
                    <?php echo '' . $_SESSION["user"]["fName"] .' '. $_SESSION["user"]["lName"].'';?></a>
            </div>
        </div>
       