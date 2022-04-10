<!DOCTYPE html>
<html>
    <head>
        <style>
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
                background-color: #f1f1f1;
            }
            
            /* Style the buttons that are used to open the tab content */
            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 14px 16px;
                transition: 0.3s;
            }
            
            /* Change background color of buttons on hover */
            .tab button:hover {
                background-color: #ddd;
            }
            
            /* Create an active/current tablink class */
            .tab button.active {
                background-color: #ccc;
            }
            
            /* Style the tab content */
            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            }
        </style>

        <script>
            function openTab(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
            }
        </script>

        <title>Page Title</title>

    </head>
    <body>
        <?php
        include 'connectdb.php';
        ?>
        <div class="tab">   
            <button class="tablinks" onclick="openTab(event, 'COVID')">COVID</button>
            <button class="tablinks" onclick="openTab(event, 'Vaccines')">Vaccines</button>
            <button class="tablinks" onclick="openTab(event, 'Clinics')">Clinics</button>
            <button class="tablinks" onclick="openTab(event, 'Patients')">Patients</button>
        </div>
  
        <div id="COVID" class="tabcontent">
            <h3>COVID Vaccine Database</h3>
            <p>Enter your OHIP number in the search bar below to begin:</p>
            <form method="post" action="patientcheck.php">
                <input type="text" name="OHIP" required/>
                <input type="submit" value="Check"/>
            </form>
        </div>

        <div id="Vaccines" class="tabcontent">
            <h3>Vaccines</h3>
            <p>This tab allows users to find the vaccines available near them:</p>
            <form action="getvaccine.php" method="post">
                <?php
                    $query = "SELECT * FROM Company";
                    $result = $connection->query($query);
                    while ($row = $result->fetch()) {
                        echo '<input type="radio" name="company" value="';
                        echo $row["name"];
                        echo '">'  . $row["name"] . "<br>";
                    }
                ?>
            <input type="submit" value="Get Info">
        </form>
        </div>
        
        <div id="Clinics" class="tabcontent">
            <h3>Clinics</h3>
            <p>This tab allows users to find clinics as well as the staff associated with them:</p>
            <form action="getclinic.php" method="post">
                <?php
                    $query = "SELECT * FROM VaxClinic";
                    $result = $connection->query($query);
                    while ($row = $result->fetch()) {
                        echo '<input type="radio" name="clinic" value="';
                        echo $row["name"];
                        echo '">'  . $row["name"] . "<br>";
                    }
                ?>
                <input type="submit" value="Get Info">
            </form>
        </div>
        
        <div id="Patients" class="tabcontent">
            <h3>Patients</h3>
            <p>This page allows users to find records of other vaccinated patients:</p>
            <form method="post" action="getpatients.php">
                <input type="text" name="OHIP" required/>
                <input type="submit" value="Search"/>
            </form>
        </div>
        <table>
            <?php
                $company= $_POST["company"];
                $query = 'SELECT * FROM Vaccine, shipsTo WHERE Vaccine.lot=ShipsTo.lotNumber AND Vaccine.producedBy="' . $company . '"';
                $result=$connection->query($query);

                echo "<h3>This vaccine is at the following locations:</h3>";
                while ($row=$result->fetch()) {
                    echo "<tr><td>".$row["clinic"]."</td></tr>";
                    $query2 = 'SELECT COUNT(lotNumber) FROM ShipsTo WHERE ShipsTo.clinic="' . $row["clinic"] . '"';
                    $result2=$connection->query($query2);
                    while ($row2=$result2->fetch()) {
                        echo "<tr><td>Number of Lots: ".$row2["COUNT(lotNumber)"]."</td></tr>";
                    }
                }
            ?>
        </table>
    </body>
</html>