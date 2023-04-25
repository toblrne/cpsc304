<html>

<head>
    <title>Manage Teams</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="global.css">
    <link rel="icon" href="https://www.notion.so/icons/soccer_gray.svg?mode=light">
</head>

<body>
    <?php
    readfile('components/navbar.html');
    ?>

    <div class="select-container">
        <div class="dropdown-display">
            <h2>List Team Members</h2>
            <form method="GET" action="teams.php"> <!--refresh page when submitted-->
                <input type="hidden" id="listTeamMembers" name="listTeamRequest">
                <input type="text" class="input-field" placeholder="Team ID" name="teamID">
                <input type="submit" class="submit-button" name="listTeamMembers" value="List Team">
            </form>
            <h2>Insert New Team</h2>
            <form method="POST" action="teams.php"> <!--refresh page when submitted-->
                <input type="hidden" id="insertTeam" name="insertTeamRequest">
                <input type="text" class="input-field" placeholder="Team ID" name="teamID">
                <input type="number" class="input-field" placeholder="Capacity" name="capacity">
                <input type="text" class="input-field" placeholder="Team Name" name="teamName">
                <input type="number" class="input-field" placeholder="Year Established" name="established">
                <input type="text" class="input-field" placeholder="Sport" name="sportName" pattern="[a-zA-Z]+" title="Please enter letters only">
                <input type="text" class="input-field" placeholder="Country" name="countryName" pattern="[a-zA-Z]+" title="Please enter letters only">
                <input type="submit" class="submit-button" name="insertTeam" value="Insert Team">
            </form>
            <h2>Show All Teams</h2>
            <form method="POST" action="teams.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="show_button" value="Show All">
            </form>
            <h2>Show All Sports</h2>
            <form method="POST" action="teams.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="show_button2" value="Show All">
            </form>
            <h2>Show All Countries</h2>
            <form method="POST" action="teams.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="show_button3" value="Show All">
            </form>
            <h2>Find Teams With at Least...</h2>
            <form method="GET" action="teams.php"> <!--refresh page when submitted-->
                <input type="hidden" id="findLargeTeams" name="findLargeTeamsRequest">
                <input type="number" class="input-field" placeholder="# Minimum members" name="numMembers">
                <input type="submit" class="submit-button" name="findLargeTeams" value="Find">
            </form>
            <h2>Find teams whose oldest member <br> has the smallest age among all teams.</h2>
            <form method="POST" action="teams.php"> <!--refresh page when submitted-->
                <input type="hidden" id="findMember" name="findSportNestedRequest">
                <input type="submit" name="findMember_button" value="Search" class="submit-button">
            </form>
        </div>
        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            function handleFindLargeTeamsRequest()
            {
                global $db_conn;

                $x = filter_var($_GET['numMembers'], FILTER_SANITIZE_NUMBER_INT);

                $cmdstr = "SELECT T.teamID, T.teamName, COUNT(*) as Members
                FROM Team T, Member_Of M
                WHERE M.teamID = T.teamID
                GROUP BY T.teamID, T.teamName
                HAVING " . $x . " <= COUNT(*)";
                $result = executePlainSQL($cmdstr);
                printResultWithCount($result, "with at least $x members.");
                OCICommit($db_conn);
            }

            function handleListTeamRequest()
            {
                global $db_conn;
                $cmdstr = "SELECT P.participantID, P.firstName, P.lastName, P.age
                           FROM Person P, Team T, Member_Of M
                           WHERE P.participantID = M.participantID AND
                           M.teamID = T.teamID AND
                            T.teamID = '" . htmlspecialchars($_GET['teamID'], ENT_QUOTES, 'UTF-8') . "'";
                $result = executePlainSQL($cmdstr);
                printResultWithCount($result, "");
                OCICommit($db_conn);
            }
            function handleInsertTeamRequest()
            {
                global $db_conn;

                $teamID = htmlspecialchars($_POST['teamID'], ENT_QUOTES, 'UTF-8');
                $capacity = isset($_POST['capacity']) && !empty($_POST['capacity']) ? filter_var($_POST['capacity'], FILTER_SANITIZE_NUMBER_INT) : 'NULL';
                $teamName = isset($_POST['teamName']) && !empty($_POST['teamName']) ? htmlspecialchars($_POST['teamName'], ENT_QUOTES, 'UTF-8') : 'NULL';
                $established = isset($_POST['established']) && !empty($_POST['established']) ? filter_var($_POST['established'], FILTER_SANITIZE_NUMBER_INT) : 'NULL';
                $sportName = htmlspecialchars($_POST['sportName'], ENT_QUOTES, 'UTF-8');
                $countryName = htmlspecialchars($_POST['countryName'], ENT_QUOTES, 'UTF-8');


                if (empty($teamID)) {
                    echo "please input a Team ID";
                    return;
                }
                if (empty($sportName)) {
                    echo "please input a Sport";
                    return;
                }
                if (empty($countryName)) {
                    echo "please input a Country";
                    return;
                }

                $check = executePlainSQL("SELECT COUNT(*) as COUNT FROM Team WHERE teamID = '$teamID'");
                $check_row = OCI_Fetch_Array($check, OCI_BOTH);
                $count = $check_row['COUNT'];
                // check for duplicates
                if ($count > 0) {
                    echo "$teamID already exists";
                } else {
                    $findSport = executePlainSQL("SELECT COUNT(*) FROM Sport WHERE sportName = '" . $sportName . "'");
                    $row = oci_fetch_row($findSport);
                    // insert into Sport if sport doesn't exist
                    if ($row[0] == 0) {
                        $insertIntoSport = "INSERT INTO Sport(sportName) VALUES ('$sportName')";
                        executePlainSQL($insertIntoSport);
                        echo "<div class='tableContainer'>";
                        echo "Added '$sportName' to Sport table!";
                        printResult(executePlainSQL("SELECT * FROM Sport"));
                        echo "</div>";
                    }

                    $findCountry = executePlainSQL("SELECT COUNT(*) FROM Country WHERE countryName = '" . $countryName . "'");
                    $row = oci_fetch_row($findCountry);
                    // insert into Sport if sport doesn't exist
                    if ($row[0] == 0) {
                        $insertIntoCountry = "INSERT INTO Country(countryName) VALUES ('$countryName')";
                        executePlainSQL($insertIntoCountry);
                        echo "<div class='tableContainer'>";
                        echo "Added '$countryName' to Country table!";
                        printResult(executePlainSQL("SELECT * FROM Country"));
                        echo "</div>";
                    }

                    $cmdstr = "INSERT INTO Team(teamID, capacity, teamName, established, sportName, countryName)
                    VALUES ('$teamID', $capacity, '$teamName', $established, '$sportName', '$countryName')";
                    executePlainSQL($cmdstr);
                    echo "<div class='tableContainer'>";
                    echo "New team added!";
                    printResult(executePlainSQL("SELECT * FROM Team WHERE sportName = '" . $sportName . "'"));
                    echo "</div>";
                    OCICommit($db_conn);
                }
            }
            // HANDLE ALL GET ROUTES
            // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handleGETRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('listTeamRequest', $_GET)) {
                        handleListTeamRequest();
                    }

                    if (array_key_exists('insertTeamRequest', $_POST)) {
                        handleInsertTeamRequest();
                    }
                    if (array_key_exists('findLargeTeamsRequest', $_GET)) {
                        handleFindLargeTeamsRequest();
                    }

                    disconnectFromDB();
                }
            }

            if (isset($_GET['listTeamMembers']) || isset($_GET['findLargeTeams']) || isset($_POST['insertTeam'])) {
                handleGETRequest();
            }

            if (isset($_POST['show_button'])) {
                connectToDB();
                $query = "SELECT * FROM Team";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }
            if (isset($_POST['show_button2'])) {
                connectToDB();
                $query = "SELECT * FROM Sport";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }
            if (isset($_POST['show_button3'])) {
                connectToDB();
                $query = "SELECT * FROM Country";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }
            if (isset($_POST['findMember_button'])) {
                global $db_conn;
                connectToDB();
                $query = "SELECT T.teamID, MAX(P.age)
                            FROM Person P, Team T, Member_Of M
                            WHERE P.participantID = M.participantID
                            AND T.teamID = M.teamID
                            GROUP BY T.teamID
                            HAVING MAX(P.age) <= ALL(SELECT MAX(Pp.age)
                                                    FROM Person Pp, Team Tt, Member_Of Mm
                                                    WHERE Pp.participantID = Mm.participantID
                                                    AND Tt.teamID = Mm.teamID
                                                    GROUP BY Tt.teamID)";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
                OCICommit($db_conn);
            }
            ?>
        </div>
    </div>

</body>

</html>