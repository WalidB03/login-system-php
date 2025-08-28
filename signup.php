<?php
try{
    require_once('.vendor/autoload.php');

    if ( isset($_POST['submit']) ) {
         $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
         $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

         $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
         $stmt = $pdo->prepare('
             INSERT INTO users(username, password)
                 VALUES (:username, :password);
         ');
         $stmt->bindParam(':username', $username, PDO::PARAM_STR);
         $stmt->bindParam(':password', $password, PDO::PARAM_STR);
         $stmt->execute();

         header('location: home.php');
         exit();
    }
} catch ( Exception $e ) {
    echo $e->getMessage();
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
            <h1>Signup</h1>

            <form action="signup.php" method="POST">
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
