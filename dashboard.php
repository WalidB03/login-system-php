<?php
session_start();

if ( !isset($_SESSION['uid']) ) {
    header('location: home.php');
    exit();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login System</title>
    </head>

    <body>
        <main>
            <h1>Dashboard!</h1>

            <a href="home.php">Home</a>
        </main>
    </body>
</html>
