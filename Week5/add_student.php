<?php
include 'header.php';
include 'functions.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
try {
$name = formatName($_POST['name']);
$email = $_POST['email'];
$skills = cleanSkills($_POST['skills']);


if (!$name || !validateEmail($email)) {
throw new Exception('Invalid input');
}


saveStudent($name, $email, $skills);
echo "<p>Student saved successfully!</p>";
} catch (Exception $e) {
echo "<p>Error: " . $e->getMessage() . "</p>";
}
}
?>
<form method="post">
<label>Name:</label> <input type="text" name="name" required><br><br>
<label>Email:</label> <input type="email" name="email" required><br><br>
<label>Skills (comma-separated): </label><input type="text" name="skills" required><br><br>
<button type="submit">Save Student</button>
</form>


<?php include 'footer.php'; ?>
