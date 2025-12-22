<?php
function formatName($name) {
return ucwords(trim($name));
}


function validateEmail($email) {
return filter_var($email, FILTER_VALIDATE_EMAIL);
}


function cleanSkills($string) {
$skills = explode(',', $string);
return array_map('trim', $skills);
}


function saveStudent($name, $email, $skillsArray) {
$line = $name . "|" . $email . "|" . implode(',', $skillsArray) . "\n";
file_put_contents('students.txt', $line, FILE_APPEND);
}


function uploadPortfolioFile($file) {
$allowed = ['application/pdf', 'image/jpeg', 'image/png'];
if (!in_array($file['type'], $allowed)) {
throw new Exception('Invalid file type');
}


if ($file['size'] > 2 * 1024 * 1024) {
throw new Exception('File too large');
}


$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newName = 'portfolio_' . time() . '.' . $ext;



return $newName;
}
?>