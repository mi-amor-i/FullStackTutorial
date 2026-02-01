<?php include '../includes/header.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<header>
	<nav>
		<h1><a href="index.php">Roomify</a></h1>


		<?php if($current_page !="login.php" && $current_page !="register.php"): ?>

		<input type="text" placeholder="Search.." name="search">
	<?php endif; ?>
		<ul>
			<?php if (!isset($_SESSION['user_id'])) { ?>
				<li><a href="register.php">Sign Up</a></li>
				<li><a href="login.php">Login</a></li>
	
            <?php } else { ?>
            	<li><a href="logout.php">Logout</a></li>

            <?php } ?>
			
		</ul>
	</nav>
</header>