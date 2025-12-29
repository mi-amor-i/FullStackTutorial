<?php

require'db.php';

try{

if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['add_student'])){
	$name=$_POST['name'];
	$email=$_POST['email'];
	$course=$_POST['course'];

	$sql="INSERT INTO students(name,email,course)VALUES(?,?,?)";
	$stmt = $pdo-> prepare($sql);
	$stmt-> execute([$name,$email,$course]);

	echo"<h3 style='color:green;'>Student added</h3>";

	header("Location:index.php");
}
}
catch(PDOException $e){
	die("Failed to insert student".$e->getMessage());
}
?>














<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Student Database</title>
</head>
<body>
	<form method="POST">
		Name: <input type="text" name="name" required><br>
		Email: <input type="email" name="email" required><br>
		Course: <input type="text" name="course" required><br>
		<button type="submit" name="add_student">Add Student</button>
	</form>

</body>
</html>