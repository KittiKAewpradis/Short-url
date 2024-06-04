<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

session_start();
require "helper.php";
checklogin();

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
            margin-top: 50px;
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
            margin-right: 50px;
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
            margin-right: 50px;
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
                        echo "<br><div style='position: relative;'>Your link: " . $row["link"] . "</div>";
                        //echo "<br><div style='margin-right: 90px; position: relative; '>Your link: " . $row["link"] . "</div>";
                        echo "Short URL: <div style='display: inline-block; border: 1px solid #ccc; padding: 10px; margin: 10px 0; text-align: left;'>aka.haadthip.com/" . $row["code"] . "</div>";
                        echo "<img src='https://cdn-icons-png.flaticon.com/512/1834/1834036.png' onclick=\"copyToClipboard('aka.haadthip.com/" . $row["code"] . "')\" style='cursor: pointer; width: 30px; height: 30px; margin-bottom: -10px; margin-left: 5px;' alt='Copy Short URL'>";

                        // ใช้ flexbox เพื่อจัดการตำแหน่งขององค์ประกอบตรงกลาง
                        echo "<div style='display: flex; justify-content: center; align-items: center; margin-top: 10px;'>";

                        // QR Code
                        echo "<br><img src='https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($url) . "&size=150x150' alt='QR Code' style='margin-right: 10px;'>";

                        // ปุ่มต่างๆ
                        echo "<div style='display: flex; flex-direction: column; align-items: center; margin-left: 50px;'>";  // เพิ่ม margin-left เพื่อลดปุ่มไปทางขวา
                        echo "<button class='share' onclick='shareLink(\"$url\")' style='margin-bottom: 10px;'>Share</button>"; // ย้ายปุ่ม Share มาข้างบน
                        echo "<form action='link_action' method='post' onsubmit='return confirmDelete()'>"; // เพิ่ม onsubmit event เพื่อเรียก confirmDelete function
                        echo "<input type='hidden' name='code' value='" . $row["code"] . "'>";
                        echo "<input type='hidden' name='action' value='delete'>";
                        echo "<button class='delete-button' type='submit'>Delete</button>";
                        echo "</form>";
                        echo "</div>";

                        echo "</div>";

                        // เพิ่มเส้นใต้
                        echo "<br><br><hr>";
                    }
                }
                sqlsrv_free_stmt($stmt);
            } else {
                echo "Connection could not be established.\n";
                die(print_r(sqlsrv_errors(), true));
            }

            sqlsrv_close($conn);
            ?>

            <script>
                function confirmDelete() {
                    return confirm('Are you sure you want to delete this link?');
                }
            </script>


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
    <script>
        function copyToClipboard(text) {
            var input = document.createElement('input');
            input.setAttribute('value', text);
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Copied to clipboard');
        }
    </script>
</body>

</html>