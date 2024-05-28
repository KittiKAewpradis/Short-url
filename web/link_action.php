<?php
session_start();
require "helper.php";
checklogin ();
require "connect.php";

$action = $_POST['action'];

if ($action == "add") {
    $link = $_POST['inp_link'];
    addLink($link);
    header("Location: links");
} else if ($action == "delete") {
    $code = $_POST['code'];
    deleteLink($code);
    header("Location: links");
}

function addLink($link){
    $conn = sqlsrv_connect($GLOBALS['serverName'], $GLOBALS['connectionInfo']);
    $value = rand(1000000,2207984167551);
    while (true) {
        if ($conn) {
            // Define your query
            $sql = "INSERT INTO links (value, code, owner, createon, link) VALUES ($value, dbo.fn_num2b58($value), '{$_SESSION["username"]}', GETDATE(), '{$link}')";
            // Execute the query
            try {
                $stmt = sqlsrv_query($conn, $sql);
                sqlsrv_free_stmt($stmt); // Free the statement resources
                break;
            } catch (Exception $e) {
                $value = $value + 1;
            }
            $stmt = sqlsrv_query($conn, $sql);
            sqlsrv_free_stmt($stmt); // Free the statement resources
        } else {
            echo "Connection could not be established.\n";
            die(print_r(sqlsrv_errors(), true));
        }
    }

    sqlsrv_close($conn); // Close the connection
}

function deleteLink($code){
    $conn = sqlsrv_connect($GLOBALS['serverName'], $GLOBALS['connectionInfo']);
    if ($conn) {
        // Define your query
        $sql = "DELETE FROM links where code = '{$code}' AND owner = '{$_SESSION["username"]}'";
        echo $sql;
        $stmt = sqlsrv_query($conn, $sql);
        sqlsrv_free_stmt($stmt); // Free the statement resources    
    } else {
        echo "Connection could not be established.\n";
        die(print_r(sqlsrv_errors(), true));
    }
    sqlsrv_close($conn); // Close the connection
}



?>