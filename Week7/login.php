<?php

require 'db.php';

if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['login'])){
	$name = $_POST['name'];
	$password = $_POST['password'];

	$sql = "SELECT * FROM students WHERE full_name=?";

	$stmt= $pdo->prepare($sql);
	$stmt->execute([$name]);
	$student = $stmt->fetch();

	if($student){

		$hashedPassword = $student['password_hash'];

		$isPasswordValid = password_verify($password, $hashedPassword);

		if($isPasswordValid){
			session_start();
			$_SESSION['logged_in']=true;
			$_SESSION['name']=$student['name'];


			header("Location:dashboard.php");

		}
		else{
			echo "Invalid password. Please Try Again!";
		}

	}else{
		echo "Invalid name";
	}


}

?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Page</title>
	<style>
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
		    color: black;
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
<body>
	<nav>
		<ul>
			<li>
				<a href="register.php">Register</a>
				<a href="login.php">Login</a>
			</li>
		</ul>
	</nav>
<h1>Login</h1>
	<form method="POST">
		Name: <input type="text" name="name" required>
		Password: <input type="password" name="password" required>
		<button type="submit" name="login">Login</button>
	</form>
</body>
</html>