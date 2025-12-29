<?php

require 'db.php';
$id=$_GET['id']??'';

if (!$id) {
    die("Invalid ID");
}
	$sql="SELECT * FROM students WHERE id=?";
	$stmt= $pdo-> prepare($sql);
	$stmt->execute([$id]);
	$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found");
}

if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['add_student'])){
    $name=$_POST['name'];
	$email=$_POST['email'];
	$course=$_POST['course'];

    $updateSql = "UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute([$name, $email, $course, $id]);

    header("Location:index.php");
}


?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Student Databa</title>
</head>
<body>
	<form method="POST">
		Name: <input type="text" name="name" value=<?=htmlspecialchars($student['name'])?> required><br>
		Email: <input type="email" name="email" value=<?=htmlspecialchars($student['email'])?> required><br>
		Course: <input type="text" name="course" value=<?=htmlspecialchars($student['course'])?> required><br>
		<button type="submit" name="add_student">Update</button>
	</form>
</body>
</html>