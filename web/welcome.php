<?php
session_start();

if (isset($_SESSION["name"])) {
    header("Location: links");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <style>
        body, html {
            height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            background-color: #f0f2f5; 
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 40px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        .container h1 {
            color: #2E8B57; 
            font-size: 36px;
            margin-bottom: 20px;
        }

        .container p {
            color: #606770; 
            font-size: 14px;
            margin-bottom: 30px;
        }

        input[type=text], input[type=password] {
            width: 100%;
            padding: 15px;
            margin: 5px 0 15px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #f1f1f1;
        }

        input[type=text]:focus, input[type=password]:focus {
            background-color: #e7f3ff;
            outline: none;
            border: 1px solid #2E8B57;
        }

        .btn {
            background-color: #2E8B57;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #276649; 
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
    </style>
</head>
<body>
    <nav>
        <h1>HAADTHIP</h1>
    </nav>
    <div class="container">
        <h1>Welcome!</h1>
        <p>Lorem ipsum dolor sit amet, fuga non iusto modi doloribus culpa, hic et voluptates explicabo laborum deserunt ipsa sit delectus veritatis?</p>
        <form action="login" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <input type="submit" class="btn" value="Log In">
        </form>
    </div>
</body>
</html>
