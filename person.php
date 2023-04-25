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
            <h2>Delete Person</h2>
            <form method="POST" action="person.php"> <!--refresh page when submitted-->
                <input type="hidden" id="deletePerson" name="deletePersonRequest">
                <input type="text" class="input-field" placeholder="Participant ID" name="participantID">
                <input type="submit" class="submit-button" name="deletePerson_button" value="Delete">
            </form>
            <h2>Show Athletes</h2>
            <form method="POST" action="person.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="showAthlete_button" value="Show All">
            </form>
            <h2>Show Coaches</h2>
            <form method="POST" action="person.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="showCoach_button" value="Show All">
            </form>


            <h2>Show All People</h2>
            <form method="POST" action="person.php">
                <input type="submit" class="submit-button" name="show_button" value="Show All">
            </form>

        </div>
        <div class="table-display">
            <?php

            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['show_button'])) {
                connectToDB();
                $query = "SELECT * FROM Person";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }
            if (isset($_POST['showAthlete_button'])) {
                connectToDB();
                $query = "SELECT * FROM Athlete";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }
            if (isset($_POST['showCoach_button'])) {
                connectToDB();
                $query = "SELECT * FROM Coach";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }

            function handleDeletePersonRequest()
            {
                global $db_conn;
                $tupleToDel = filter_var($_POST['participantID'], FILTER_SANITIZE_NUMBER_INT);
                $result = executePlainSQL("SELECT * FROM Person WHERE participantID = $tupleToDel");
                $rowCount = oci_fetch_all($result, $res);
                if ($rowCount > 0) {
                    executePlainSQL("DELETE FROM Person WHERE participantID = $tupleToDel");
                    OCICommit($db_conn);
                    echo "<p>Tuple with Person $tupleToDel deleted.</p>";
                } else {
                    echo "<p>Error: Tuple with Person $tupleToDel does not exist.</p>";
                }
            }

            // HANDLE ALL POST ROUTES
            // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handlePOSTRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('deletePersonRequest', $_POST)) {
                        $participantID = filter_var($_POST['participantID'], FILTER_SANITIZE_NUMBER_INT);
                        if (empty($participantID)) {
                            echo "<p>Error: Participant ID cannot be empty.</p>";
                        } else {
                            handleDeletePersonRequest();
                        }
                    }
                    disconnectFromDB();
                }
            }

            if (isset($_POST['deletePerson_button'])) {
                handlePOSTRequest();
            }
            ?></div>
    </div>

    <?php

    ?>
</body>

</html>