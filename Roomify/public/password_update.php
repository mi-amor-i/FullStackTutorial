<?php

require '../config/db.php';
// Email to update
$targetEmail = 'admin@roomify.com';
$newPlainPassword = 'admin@123'; 

try {


	 $hashedPassword = password_hash($newPlainPassword, PASSWORD_BCRYPT);
    
    // Update query
    $sql = "UPDATE users SET user_password = :password WHERE user_email = :email";
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':email', $targetEmail);



    if ($stmt->execute()) {
        $affectedRows = $stmt->rowCount();
        
        if ($affectedRows > 0) {
            echo "Password successfully updated for: $targetEmail<br>";
            echo "Hashed password: $hashedPassword";
        } else {
            echo "No user found with email: $targetEmail";
        }
    }
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>