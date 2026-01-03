<?php

require 'db.php';

try{
	if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['add_student'])){

	$student_id = $_POST['student_id'];
	$name = $_POST['name'];
	$password = $_POST['password'];
	$hashedPassword = password_hash($password, PASSWORD_BCRYPT);


	$sql = "INSERT INTO students (student_id, full_name, password_hash) VALUES (?,?,?)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$student_id,$name,$hashedPassword]);

	header("Refresh:2 url=login.php");
}
}catch(PDOException $e){
	die("Database error".$e->getMessage());
}

?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register</title>
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
	<h1>Add Student</h1>
	<form method="POST">
		Student ID: <input type="text" name="student_id" required>
		Name: <input type="text" name="name" required>
		Password: <input type="password" name="password" required>
		<button type="submit" name="add_student">Add Student</button>
	</form>
</body>
</html>