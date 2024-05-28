<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

require "web/connect.php";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn) {
    // echo "Connection established.\n";

    // Define your query
    $sql = "SELECT * FROM links where code = '{$_GET["c"]}'";

    // Execute the query 
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        echo "Error executing query: " . sqlsrv_errors()[0]['message'];
    } else {
        // Process the results (assuming you want to fetch all rows)
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if ($row) {
            // Process data from the row using associative array
            // Example: echo "ID: " . $row["id"] . ", Name: " . $row["name"] . "\n";
            echo $row['link'];
            Header("Location: ".$row['link']);
        } else {
            Header("Location: web/welcome");
        }
        
    }

    sqlsrv_free_stmt($stmt); // Free the statement resources
} else {
    echo "Connection could not be established.\n";
    die(print_r(sqlsrv_errors(), true));
}

sqlsrv_close($conn); // Close the connection
?>