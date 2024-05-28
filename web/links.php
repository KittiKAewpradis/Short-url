<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

session_start();
require "helper.php";
checklogin ();

require "connect.php";

//$links = [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            overflow: auto; 
        }

        h1 {
            color: #333;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        input[type="url"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #2E8B57;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .delete-button {
            background-color: #f72f2f;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #2E8B57;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .container-2 {
            background-color: #f7f7f7;
            max-height: 400px; 
            overflow-y: auto; 
        }

        nav {
            width: 100%;
            padding: 10px 0;
            background-color: #2E8B57; 
            color: white;
            text-align: left;
            padding-left: 10%; 
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        nav h1 {
            margin: 0;
            font-size: 24px;
        }

        .share {
            background-color: #3498db;
            color: #fff;
        }

    </style>
</head>
<body>
    <nav>
        <h1>HAADTHIP</h1>
    </nav>
    <br>
    <div class="container">
        <h1>Hello, <?= $_SESSION["name"] ?></h1>
        <a href="logout">Logout</a>
        <form action="link_action" method="post" onsubmit="return validateUrl()">
            <input type="url" placeholder="Enter link" id="inp_link" name="inp_link" required> 
            <input type="hidden" id="action" name="action" value="add">
            <button type="submit" id="btn_add">Add</button>
        </form>
        <div class="container-2">
<?php
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn) {
    $sql = "SELECT * FROM links WHERE owner = '{$_SESSION["username"]}'";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        echo "Error executing query: " . sqlsrv_errors()[0]['message'];
    } else {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $url = "https://aka.haadthip.com/" . $row["code"];
            echo "<br><br><img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($url) . "&size=150x150' alt='QR Code'>";
            echo "<br><br>aka.haadthip.com/".$row["code"]."<br>".$row["link"];
            echo "<form action='link_action' method='post'>";
            echo "<input type='hidden' name='code' value='" . $row["code"] . "'>";
            echo "<input type='hidden' name='action' value='delete'>";
            echo "<button class='delete-button' type='submit'>Delete</button>";
            echo "</form>";
            echo "<br><button class='share' onclick='shareLink(\"$url\")'>Share</button>";
        }
    }
    sqlsrv_free_stmt($stmt); 
} else {
    echo "Connection could not be established.\n";
    die(print_r(sqlsrv_errors(), true));
}

sqlsrv_close($conn);
?>
        </div>
    </div>
    <script>
        function validateUrl() {
            const urlInput = document.getElementById('inp_link');
            const url = urlInput.value;

            try {
                new URL(url);
                return true;
            } catch (_) {
                alert('Please enter a valid URL.');
                return false;
            }
        }

        function shareLink(url) {
            if (navigator.share) {
                navigator.share({
                    title: 'Check out this link',
                    text: 'I found this link and thought you might be interested!',
                    url: url
                })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing:', error));
            } else {
                alert("Web Share API is not supported in this browser. You can manually copy the link.");
            }
        }
    </script>
</body>
</html>
