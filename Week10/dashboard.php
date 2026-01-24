<?php

require 'session.php';
require 'db.php';

$user_email = '';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT email FROM users WHERE id = '$user_id'";
    $stmt = $pdo->query($sql);
    $user = $stmt->fetch();
    if ($user) {
        $user_email = $user['email'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Welcome to my site</h1>
<?php if ($user_email): ?>
    <p>Logged In User : <?php echo htmlspecialchars($user_email, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>

<?php if (!$user_email): ?>
<a href="login.php">
    <button>Login</button>
</a>
<?php else:?>
<a href="logout.php">
    <button>Logout</button>
</a>
<?php endif; ?>
</body>
</html>
