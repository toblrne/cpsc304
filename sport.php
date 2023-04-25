<html>

<head>
    <title>Manage Sports</title>
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
            <h2>Find Sport</h2>
            <form method="GET" action="sport.php"> <!--refresh page when submitted-->
                <input type="hidden" id="findSport" name="findSportRequest">
                <input type="text" class="input-field" placeholder="Sport Name" name="sportName" pattern="[a-zA-Z]+" title="Please enter letters only">
                <input type="submit" name="findSport" value="Search" class="submit-button">
            </form>

            <h2>Show All Sports</h2>
            <form method="POST" action="sport.php"> <!--refresh page when submitted-->
                <input type="submit" class="submit-button" name="show_button" value="Show All">
            </form>
        </div>
        <div class="table-display">
            <?php
            include 'util/db.php';
            include 'util/print.php';

            if (isset($_POST['show_button'])) {
                connectToDB();
                $query = "SELECT * FROM Sport";
                $result = executePlainSQL($query);
                printResult($result);
                disconnectFromDB();
            }

            function handleFindSportRequest()
            {
                global $db_conn;

                //Getting the values from user and insert data into the table
                $tuple = array(
                    ":bind1" => htmlspecialchars($_GET['sportName'], ENT_QUOTES, 'UTF-8'),
                );

                $alltuples = array(
                    $tuple
                );

                $sportName = htmlspecialchars($_GET['sportName'], ENT_QUOTES, 'UTF-8');
                if (empty($sportName)) {
                    echo "Please enter a sport";
                    return;
                }

                $cmdstr = "SELECT sportName FROM Sport WHERE LOWER(sportName) LIKE '%" . strtolower($sportName) . "%'";

                $result = executePlainSQL($cmdstr);

                $rowCount = oci_fetch_all($result, $res);
                if ($rowCount == 1) {
                    printResult(executePlainSQL($cmdstr));
                } else if ($rowCount > 0) {
                    echo "<div class='tableContainer'>";
                    echo "<center><b>Here are the sports that contain '$sportName':</b></center><br/>";
                    printResult(executePlainSQL($cmdstr));
                    echo "</div>";
                } else {
                    echo "sports containing '$sportName' do not exist in the database.";
                }
                OCICommit($db_conn);
            }


            function handleGETRequest()
            {
                if (connectToDB()) {
                    if (array_key_exists('findSportRequest', $_GET)) {
                        handleFindSportRequest();
                    }
                    disconnectFromDB();
                }
            }

            if (isset($_GET['findSport'])) {
                handleGETRequest();
            }
            ?>
        </div>
    </div>

</body>

</html>