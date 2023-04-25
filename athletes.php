<html>

<head>
    <title>Manage Athletes</title>
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
            <h2>Find Athlete Details</h2>
            <form method="GET" action="athletes.php"> <!--refresh page when submitted-->
                <input type="hidden" id="findAthlete" name="findAthleteRequest">
                <input type="number" class="input-field" placeholder="Athlete ID" name="participantID">
                <input type="submit" name="findAthlete" value="Search" class="submit-button">
                <!-- displays athlete and participant details -->
            </form>

            <h2>Update Athlete Details</h2>
            <form method="POST" action="athletes.php"> <!--refresh page when submitted-->
                <input type="hidden" id="updateAthlete" name="updateAthleteRequest">
                <input type="number" class="input-field" placeholder="Athlete ID" name="participantID">
                <input type="number" class="input-field" placeholder="Height (cm)" name="height">
                <input type="number" class="input-field" placeholder="Weight (kg)" name="weight">
                <input type="submit" name="updateAthlete" value="Update" class="submit-button">
            </form>
            <h2>Show All Athletes</h2>
            <form method="POST" action="athletes.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="show_button" value="Show All">
            </form>
            <h2> Find Athletes Playing All Sports in a Competition </h2>
            <form method="GET" action="athletes.php"> <!--refresh page when submitted-->
                <input type="hidden" id="findAthleteInAllSports" name="findAthleteInAllSportsRequest">
                <input type="text" class="input-field" placeholder="Competition" name="competitionName">
                <input type="number" class="input-field" placeholder="Year" name="yearVal">
                <input type="submit" name="findAthleteInAllSports" value="Search" class="submit-button">
            </form>



        </div>
        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['show_button'])) {
                connectToDB();
                $query = "SELECT A.participantID, P.firstName, P.lastName, A.height, A.mass
                FROM Person P, Athlete A
                WHERE P.participantID = A.participantID";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }

            function handleFindAthleteRequest()
            {
                global $db_conn;


                $participantID = $_GET['participantID'];
                if (empty($participantID)) {
                    echo "Please enter a participant ID";
                    return;
                }


                $cmdstr = "SELECT A.participantID, P.firstName, P.lastName, A.height, A.mass
                                FROM Person P, Athlete A
                                WHERE P.participantID = A.participantID AND
                                A.participantID = '" . $participantID . "'";
                $result = executePlainSQL($cmdstr);

                $rowCount = oci_fetch_all($result, $res);
                if ($rowCount > 0) {
                    printResult(executePlainSQL($cmdstr));
                } else {
                    echo "Participant with ID $participantID does not exist in the database.";
                }
                OCICommit($db_conn);
            }


            function handleFindAthleteInAllSportsRequest()
            {

                global $db_conn;


                $competitionName = htmlspecialchars($_GET['competitionName'], ENT_QUOTES, 'UTF-8');
                if (empty($competitionName)) {
                    echo "Please enter a competition name";
                    return;
                }

                $yearVal = filter_var($_GET['yearVal'], FILTER_SANITIZE_NUMBER_INT);
                if (empty($yearVal)) {
                    echo "Please enter a year";
                    return;
                }

                $cmdstr = "SELECT a.participantID FROM Athlete a WHERE NOT EXISTS (SELECT s.sportName FROM Sport s, Competition comp, CompetitionSport cs WHERE comp.competitionName = '" . $competitionName . "' AND comp.yearVal = '" . $yearVal . "' AND comp.competitionName = cs.competitionName AND comp.yearVal = cs.yearVal AND s.sportName = cs.sportName AND NOT EXISTS (SELECT c.participantID FROM Competes_In c WHERE c.participantID = a.participantID AND c.sportName = s.sportName AND c.competitionYear = comp.yearVal AND c.competitionName = comp.competitionName ) )";

                $result = executePlainSQL($cmdstr);

                $rowCount = oci_fetch_all($result, $res);
                if ($rowCount > 0) {
                    printResult(executePlainSQL($cmdstr));
                } else {
                    echo "There is no athlete who has played all sports in $competitionName in $yearVal in the database";
                }
                OCICommit($db_conn);
            }


            function handleUpdateAthleteRequest()
            {
                global $db_conn;

                //Getting the values from user and insert data into the table
                $participantID = filter_var($_POST['participantID'], FILTER_SANITIZE_NUMBER_INT);
                $height = isset($_POST['height']) && !empty($_POST['height']) ? filter_var($_POST['height'], FILTER_SANITIZE_NUMBER_FLOAT) : 'NULL';
                $weight = isset($_POST['weight']) && !empty($_POST['weight']) ? filter_var($_POST['weight'], FILTER_SANITIZE_NUMBER_FLOAT) : 'NULL';


                $cmdstr = "SELECT A.participantID, P.firstName, P.lastName, A.height, A.mass
                FROM Person P, Athlete A
                WHERE P.participantID = A.participantID AND
                A.participantID = '" . $participantID . "'";
                $result = executePlainSQL($cmdstr);


                $rowCount = oci_fetch_all($result, $res);
                if ($rowCount > 0) {
                    executePlainSQL("UPDATE Athlete   SET height = "   . $height .    ", 
                        mass = "  . $weight .     "
                         WHERE participantID = " . $participantID);
                    echo "Sucessfully updated Athlete " . $participantID . "!";
                    printResult(executePlainSQL($cmdstr));
                } else {
                    echo "Sorry! There was an error in updating Athlete " . $participantID
                        . ", Please confirm that the Athlete exists.";
                }

                OCICommit($db_conn);
            }


            // HANDLE ALL GET ROUTES
            // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handlePOSTRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('updateAthleteRequest', $_POST)) {
                        handleUpdateAthleteRequest();
                    }
                    disconnectFromDB();
                }
            }
            function handleGETRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('findAthleteRequest', $_GET)) {
                        handleFindAthleteRequest();
                    } else if (array_key_exists('findAthleteInAllSportsRequest', $_GET)) {
                        handleFindAthleteInAllSportsRequest();
                    }
                    disconnectFromDB();
                }
            }

            if (isset($_POST['updateAthlete'])) {
                handlePOSTRequest();
            }
            if (isset($_GET['findAthlete'])) {
                handleGETRequest();
            }
            if (isset($_GET['findAthleteInAllSports'])) {
                handleGETRequest();
            }
            ?>
        </div>
    </div>

</body>

</html>