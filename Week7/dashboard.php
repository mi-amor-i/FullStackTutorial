<?php
session_start();

$isUserLoggedIn = $_SESSION['logged_in'];
$name = $_SESSION['name'];

if(!$isUserLoggedIn){
	$message = "Please login first!";
	header('Refresh:2 url:login.php');
}
else{
    $message = "Welcome ".$name;
}

// Check cookie for theme preference
$theme = 'light-mode'; // default value
if (isset($_COOKIE['theme'])) {
    $theme = $_COOKIE['theme'];
}

if (isset($_POST['change_theme'])) {
    if($theme=='light-mode'){
    	$new_theme = 'dark-mode';
    }else{
    	$new_theme = 'light-mode';
    }
    setcookie('theme', $new_theme, time() + (86400 * 30), "/");
    $theme = $new_theme;
}






?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <style>
        /* Theme-specific CSS */
        body.light-mode {
            background-color: white;
            color: black;
        }
        
        body.dark-mode {
            background-color: black;
            color: white;
        }
        
        /* Basic styling */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        
        .header {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        

        
        .content {
            padding: 20px;
        }

        
        .theme-info {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        nav {
		    padding: 15px;
		}

		nav ul {
		    list-style: none;
		    display: flex;
		    justify-content: flex-end;
		    gap: 10px;
		    margin: 0;
		    padding: 0;
		}

		nav a {
		    padding: 10px 20px;
		    border-radius: 5px;
		    text-decoration: none;
		    font-weight: bold;
		}

		nav a:hover {
		    background-color: #0056b3;
		}
        

    </style>
</head>
<body class="<?php echo $theme; ?>">
	<nav>
		<ul>
			<li>
				<a href="register.php">Register</a>
				<a href="login.php">Login</a>
			</li>
			<li>
				<form method="POST" style="display: inline;">
                <button type="submit" name="change_theme" class="theme-btn">
                    Switch to <?php echo $theme === 'light-mode' ? 'dark-mode' : 'light-mode'; ?> Mode
                </button>
            </form>
			</li>
		</ul>
	</nav>
    <div class="header">
        <h2>Welcome to Dashboard</h2>
    </div>
    
    
    <div class="theme-info">
        <strong>Current Theme:</strong> <?php echo $theme === 'dark-mode' ? 'Dark Mode' : 'Light Mode'; ?>
    </div>
    

</body>
</html>