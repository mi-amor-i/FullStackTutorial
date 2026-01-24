<?php

require 'db.php';

$message = '';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];


        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            if (empty($password)) {
                    echo "Password cannot be empty.";
            }
            elseif (strlen($password) < 8) {
                echo "Password must be at least 8 characters long.";
            }
            else {

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO users (email, password) VALUES (?, ?)";

                $stmt=$pdo->prepare($sql);
                $stmt->execute([$email,$hashedPassword]);

                $message = "User signed up successfully";
                header('refresh: 2; url=login.php');
            }

        } else {
            echo "Invalid email!";
        }
    }

} catch (Exception $e) {
    $message = "Something went wrong.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>

<h2>Signup</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email:</label><br>
    <input type="text" name="email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Signup</button>
</form>

<br>
<a href="login.php">Go to Login</a>

</body>
</html>
