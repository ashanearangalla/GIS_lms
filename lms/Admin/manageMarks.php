<?php
session_start();
require_once("../db_conn.php");
include("sidemenu.php");
?>
        <div class="dashboard-content">
            <div class="heading-box">
                <div class="box-1">
        
                    <div class="title">
                        <p>Manage Marks</p>
                        <!-- <h2>Welcome To 4You</h2> -->
                    </div>
                </div>
                <div class="box-1">
                    <div class="search-bar">
                        <ul>
                            <li class="search">
                                <!--Search bar-->
        
                                <form action="trips.php" method="post" id="searchForm">
                                <!-- <input type="text" <?php echo isset($_POST["search"]) ? "value='$search'" : ""; ?> placeholder="Search trips" name="search" required id="search-input" /> -->
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
                                <th>Subject ID</th>
                                <th>Class ID</th>
                                <th>Subject</th>
                                <th>STATUS</th>                       
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bottom-box">
                <div class="button"><form action='' method='post'>
                    <button name="addNewStudent" id="popupButtonItem" class="submit">
                        Add a new Mark Sheet
                    </button></form>
                </div>
        
            </div>
        
        
        </div>
        
        
        
        <div id="overlay-2"></div>
        <div id="popupContainerItem">
            <div id="popupContent-item">
                <div class="popup-header">
                    <h2>Add New Class</h2>
                    <span class="close-icon" onclick="closePopupItem()">&#10006;</span>
                </div>
                <form action="admin_db_model.php" method="post" id="addNewClass" onsubmit="">
                    <div class="popup-content-item">
                        <div class="add-category-form-item">
                            <div class="inputs-popup-item">
                                <div class="inputs-popup-item-box1">
                                    <div class="col1-popup-item">
                                        <label class="labels-popup-item-trips" for="className">Class Name</label>
                                        <input class="divided-input-popup-item-class" type="text" id="className" name="className" placeholder="Enter Class Name">
                                    </div>
                                    <div class="col1-popup-item">
                                        <label class="labels-popup-item-trips" for="classTeacher">Class Teacher</label>
                                        <input class="divided-input-popup-item-class" type="text" id="classTeacher" name="classTeacher" placeholder="Enter Class Teacher">
                                    </div>
                                    <div class="col1-popup-item">
                                        <label class="labels-popup-item-trips" for="noOfStudent">Number of Students</label>
                                        <input class="divided-input-popup-item-class" type="text" id="noOfStudent" name="noOfStudent" placeholder="Enter Number of Students">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="popup-footer">
                            <div class="button-popup-footer">
                                <p id="error" style="margin-bottom: 5px; font-size: 20px; text-align: center; "></p>
                                <button type="submit" name="">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
        <script>
            document.getElementById('popupButtonItem').addEventListener('click', function() {
                document.getElementById('overlay-2').style.display = 'block';
                document.getElementById('popupContainerItem').style.display = 'block';
            });

            function closePopupItem() {
                document.getElementById('overlay-2').style.display = 'none';
                document.getElementById('popupContainerItem').style.display = 'none';
            }

           

            document.getElementById('addNewClass').addEventListener('submit', function(event) {
                event.preventDefault();
                // Add your form submission logic here
                // Close the popup if needed
                // closePopupItem();
            });
        </script>
</body>













