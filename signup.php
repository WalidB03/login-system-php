<?php
session_start();

try {
    require_once('.vendor/autoload.php');

    $_SESSION['csrf_token'] = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(50));

    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );


    if ( isset($_POST['submit']) ) {

        if ( !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '') ) {
            exit('CSRF Attempt DETECTED!');
        }

        if ( $_POST['password'] !== $_POST['confirm_password'] ) {
            exit('Password Confirmation Failed!');
        }

        $pass_regex = '/^[^\s]{8,}$/';
        $regexp = ['options' => ['regexp' => '/^[A-Za-z][A-Za-z0-9_-]{2,31}$/']];

        // required inputs
        $username = filter_var(trim($_POST['username']), FILTER_VALIDATE_REGEXP, $regexp) ?: NULL;
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL) ?: NULL;
        $password = preg_match($pass_regex, $_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : NULL;

        // optional inputs
        $first_name = filter_var(trim($_POST['first_name']), FILTER_VALIDATE_REGEXP, $regexp) ?: NULL;
        $last_name = filter_var(trim($_POST['last_name']), FILTER_VALIDATE_REGEXP, $regexp) ?: NULL;
        $birth_date = (DateTime::createFromFormat('Y-m-d', $_POST['birth_date']) ?: NULL)?->format('Y-m-d');

        if ($username && $email && $password) {
            $stmt = $pdo->prepare('
                INSERT INTO users(username, email, password, first_name, last_name, birth_date)
                    VALUES (:username, :email, :password, :first_name, :last_name, :birth_date);
            ');
            $stmt->execute(compact('username', 'email', 'password', 'first_name', 'last_name', 'birth_date'));
        }

        header('location: home.php');
        unset($_SESSION['csrf_token']);
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
            <h1>Signup</h1>

            <form action="signup.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <input name="first_name" type="text" placeholder="First Name (Optional)"><br><br>
                <input name="last_name" type="text" placeholder="Last Name (Optional)"><br><br>
                <input name="birth_date" type="date"><br><br>
                <input name="username" type="text" placeholder="Username" required><br><br>
                <input name="email" type="email" placeholder="Email" required><br><br>
                <input name="password" type="password" placeholder="Password" required><br><br>
                <input name="confirm_password" type="password" placeholder="Confirm Your Password" required><br><br>
                <button name="submit" type="submit">Submit</button>
            </form>
            <br><br>
            <a href="home.php">Home</a>
        </main>
    </body>
</html>

