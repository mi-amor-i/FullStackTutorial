<?php

require 'session.php';
require 'db.php';


if(!isset($_SESSION['csrf_token'])){
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$error = '';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $isCSRFValid = isset($_POST['csrf_token'])  && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'],$_POST['csrf_token']);

        $email = $_POST['email'];
        $password = $_POST['password'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                if ($isPasswordValid = password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: dashboard.php');
                    exit;
                } else {
                    $error = "Invalid email or password";
                }
            } else {
                $error = "Invalid email or password";
            }
        } else {
                echo "Invalid email!";
            }
    }        

} catch (Exception $e) {
    $error = "Something went wrong.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email:</label><br>
    <input type="text" name="email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br><br>

    <input type="hidden" name="csrf_token">

    <button type="submit">Login</button>
</form>
<br>
<a href="signup.php">Go to Signup</a>
</body>
</html>
