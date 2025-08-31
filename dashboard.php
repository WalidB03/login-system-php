<?php
session_start();

if ( !isset($_SESSION['uid']) ) {
    header('location: home.php');
    exit();
}

if ( time() - $_SESSION['last_regeneration'] > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
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

            <a href="home.php">Home</a><br>
            <a href="logout.php">Logout</a>
        </main>
    </body>
</html>
