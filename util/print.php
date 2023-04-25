<?php

function printResult($result)
{ //prints results from a select statement
    // modified from: https://www.php.net/manual/en/function.oci-execute.php
    echo "<div style='overflow-x:auto;'>";
    echo "<table style='text-align: center;margin-left: auto;margin-right: auto;'>";
    $header = false;

    while ($row = OCI_Fetch_Array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        if (!$header) {
            echo "<tr>";
            foreach (array_keys($row) as $col) {
                echo "<th style='text-align: center; '>" . ($col !== null ? htmlentities($col, ENT_QUOTES) : "&nbsp;") . "</th>\n";
            }
            echo "</tr>";
            $header = true;
        }

        echo "<tr>";
        foreach ($row as $item) {
            echo "<td style='text-align: center;'>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>";
    }
    echo "</table>";
}

function printResultWithCount($result, $msg)
{ //prints results from a select statement
    // modified from: https://www.php.net/manual/en/function.oci-execute.php
    echo "<div class='table-container'>";
    echo "<table style='text-align: center;margin-left: auto;margin-right: auto;'>";
    $header = false;

    $i = 0;
    while ($row = OCI_Fetch_Array($result, OCI_ASSOC + OCI_RETURN_NULLS)) {
        if (!$header) {
            echo "<tr>";
            foreach (array_keys($row) as $col) {
                echo "<th style='text-align: center; '>" . ($col !== null ? htmlentities($col, ENT_QUOTES) : "&nbsp;") . "</th>\n";
            }
            echo "</tr>";
            $header = true;
        }

        echo "<tr>";
        foreach ($row as $item) {
            echo "<td style='text-align: center;'>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>";
        $i++;
    }
    echo "</table>";
    echo "<h4>$i result(s) found $msg</h4>";
    echo "</div>";
}