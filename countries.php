<html>

<head>
    <title>Countries</title>
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
            <h2>View Average Athlete Age by Country</h2>
            <form method="GET" action="countries.php" name="countryAverageRequest"> <!--refresh page when submitted-->
                <input type="hidden" id="countryAverage" name="countryAverageRequest">
                <input type="submit" name="countryAverage" value="View" class="submit-button">
            </form>

        </div>
        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            function handleCountryAverageRequest()
            {

                global $db_conn;

                connectToDB();

                $result = executePlainSQL("SELECT T.countryName, AVG(P.age)
                FROM Person P, Team T, Member_Of M, Athlete A
                WHERE P.participantID = M.participantID
                AND T.teamID = M.teamID
                AND P.participantID = A.participantID
                GROUP BY T.countryName");

                printResult($result);

                OCICommit($db_conn);
                disconnectFromDB();
            }

            // HANDLE ALL GET ROUTES
            // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
            function handleGETRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('countryAverageRequest', $_GET)) {
                        handleCountryAverageRequest();
                    }
                    disconnectFromDB();
                }
            }

            if (isset($_GET['countryAverage'])) {
                handleGETRequest();
            }
            ?>
        </div>
    </div>

</body>

</html>