<?php 
include '../includes/header.php';
session_start();


include '../includes/navbar.php';


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

            $sql = "SELECT * FROM users WHERE user_email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                if (password_verify($password, $user['user_password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_role'] = $user['user_role'];

                    if($user['user_role']=='user'){
                        header('Location: ../public/index.php');
                        exit;
                    }else{
                        header('Location: ../public/dashboard.php');
                        exit;
                    }

                } else {
                    $error = "Invalid email or password";
                }


            } else {
                $error = "Invalid email or password";
            }


        } else{
             echo "Invalid email format";
        }


    }        

} catch (Exception $e) {
    $error = $e->getMessage();
}




?>
<div class="center-container">
    <section class="brand">
    	<h1>Roomify</h1>

    	<h2>Sign in or create an account</h2>
    	<p>Find your room, secure your stay, and manage life — all in one place.</p>
    </section>
    <section class="form">
    	<?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    	<?php endif; ?>
    	<form method="POST">
    		<input type="email" name="email" placeholder="Email" required><br>
    		<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'];?>">
    		<input type="password" name="password" placeholder="Password" required><br>
    		<button type="submit">Continue</button>
    	</form>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
