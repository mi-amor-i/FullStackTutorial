
<?php
require'db.php';
try{
$sql="SELECT * FROM students";
$stmt=$pdo->query($sql);
$students = $stmt->fetchAll();
}
catch(PDOException $e){
	die("Unable to f=get data from database".$e->getMessage());
}
?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Studentb Database</title>
</head>
<body>
<h1 style="color: red;">Welcome to Student Database!</h1>

<table>
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>Course</th>
		<th>Edit</th>
	</tr>
	<?php foreach ($students as $student):?>
	<tr>
		<td><?=$student['name']?></td>
		<td><?=$student['email']?></td>
		<td><?=$student['course']?></td>
		<td><a href="edit.php?id=<?=$student['id']?>">Edit</a>
		<a href="delete.php?id=<?=$student['id']?>">Delete</a></td>
	</tr>
	<?php endforeach ?>
</table>
	<br>
	<a href="create.php">Add New Student</a>
</body>
</html>

