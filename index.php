<html>

<head>
    <title>SCMS</title>
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
            <form method="POST" class="form-display">
                <div> Select a Table:</div>
                <select name="tables" class="select-display" onchange="this.form.submit()">
                    <?php
                    include 'util/db.php';
                    include 'util/print.php';

                    connectToDB();

                    $selected_table = isset($_POST['tables']) ? $_POST['tables'] : null;
                    $result = executePlainSQL("SELECT table_name FROM user_tables ORDER BY table_name");
                    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                        $selected = ($row[0] == $selected_table) ? 'selected' : '';
                        echo '<option value="' . $row[0] . '" ' . $selected . '>' . $row[0] . '</option>';
                    }

                    disconnectFromDB();

                    ?>
                </select>
                
                <div> Select Columns: </div>
                <div class="checkboxes">
                    <?php
                    if ($selected_table) {
                        $query = "SELECT column_name FROM USER_TAB_COLUMNS WHERE table_name='$selected_table'";
                        $result = executePlainSQL($query);
                        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                            $column_name = $row[0];
                            $checked = isset($_POST[$column_name]) ? 'checked' : '';
                            echo "<label for='$column_name'>  $column_name </label>";
                            echo "<input type='checkbox' name='$column_name' $checked>";
                        }
                    }
                    ?>

                </div>

                <input type="submit" name="submit_button" value="Submit" class="submit-button">
                <?php
                if (isset($_POST['submit_button'])) {
                    $selected_table = $_POST['tables'];
                    $selected_columns = "";
                    foreach ($_POST as $key => $value) { // checks if checkbox checked or not
                        if ($key !== 'tables' && $key !== 'submit_button' && $value == 'on') {
                            $column_name = str_replace('_', '', $key);
                            $selected_columns .= $column_name . ', ';
                        }
                    }
                    $selected_columns = rtrim($selected_columns, ', ');
                    $query = "SELECT " . $selected_columns . " FROM " . $selected_table;
                    $result = executePlainSQL($query);
                    printResult($result);
                }
                $selected_columns = "";


                ?>
            </form>
        </div>
    </div>

    <?php

    include 'util/db.php';
    include 'util/print.php';

    ?>
</body>

</html>