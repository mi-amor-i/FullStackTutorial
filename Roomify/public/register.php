<?php 


include '../includes/header.php'; 
include '../includes/navbar.php'; 

$message = '';

try {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    	$name = $_POST['user_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];


        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            if (empty($password)) {
                    $message = "Password cannot be empty.";
            }
            elseif (strlen($password) < 8) {
                $message = "Password must be at least 8 characters long.";
            }
            else {

                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)";

                $stmt=$pdo->prepare($sql);
                $stmt->execute([$name,$email,$hashedPassword]);

                $message = "User signed up successfully";
                header('refresh: 1; url=login.php');
            }

        } else {
            echo "Invalid email!";
        }
    }

} catch (Exception $e) {
    $message = "Something went wrong.";
}

?>
<div class="center-container">
    <?php if ($message): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    
    

    <section class="brand">
    	<h1>Roomify</h1>

    	<h2>Create an account</h2>
    	<p>Find your room, secure your stay, and manage life — all in one place.</p>
    </section>
    <section class="form">
    	<?php if ($message): ?>
        <p><?php echo $message; ?></p>
    	<?php endif; ?>
    	<form method="POST">
    		<input type="text" name="user_name" placeholder="Name" required><br>
    		<input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
    		<button type="submit">Continue</button>
    	</form>
    </section>
</div>
<?php include '../includes/footer.php'; ?>
