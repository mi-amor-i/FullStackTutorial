<?php include 'header.php'; ?>


<h3>Student List:</h3><br>
<?php
if (file_exists('students.txt')) {
    $lines = file('students.txt');
    foreach ($lines as $line) {
        list($name, $email, $skills) = explode('|', $line);
        // Just display the skills string directly (it's already comma-separated)
        echo "<p><strong>$name</strong><br>Email: $email<br>Skills: $skills</p><hr>";
    }
}
?>


<?php include 'footer.php'; ?>
