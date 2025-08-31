<?php
session_start();

try{
    require_once('.vendor/autoload.php');

    $_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(50));

    if ( isset($_POST['submit']) ) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
        $stmt = $pdo->prepare('
            SELECT uid, password FROM users
                WHERE username = :username;
        ');
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $account = $stmt->fetch();

        if ( $account && password_verify($_POST['password'], $account['password']) ) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
            $_SESSION['uid'] = $account['uid'];
            header('location: dashboard.php');
            exit();
        }

        header('location: home.php');
        exit();
    }
} catch ( Exception $e ) {
    error_log($e->getMessage(), 3, PROJECT_ROOT.'exceptions.log');
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
            <h1>Login</h1>

            <form action="login.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <input name="username" type="text" placeholder="username">
                <br><br>
                <input name="password" type="password" placeholder="password">
                <br><br>
                <button name="submit" type="submit">Submit</button>
            </form>
            <br><br>
            <a href="home.php">Home</a>
        </main>
    </body>
</html>
