<?php
// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Springfield High School - Matugga</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Professional styling using Flexbox and responsiveness -->
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            background-color: #87CEEB;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            flex-wrap: wrap;
        }

        header .logo {
            display: flex;
            align-items: center;
        }

        header .logo img {
            height: 50px;
            margin-right: 15px;
        }

        header h1 {
            font-size: 1.5em;
            margin: 0;
            white-space: nowrap;
        }

        @media screen and (max-width: 600px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            header h1 {
                font-size: 1.2em;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="log.png" alt="School Logo">
            <h1>Springfield High School - Matugga</h1>
        </div>
    </header>
