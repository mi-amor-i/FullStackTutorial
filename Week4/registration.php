
<?php
// Initialize variables
$nameErr = $emailErr = $passwordErr = $confirmErr = "";
$fileErr = "";
$name = $email = "";
$successMsg = "";

// Run validation when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //  NAME VALIDATION 
    if (empty($_POST["name"])) {
        $nameErr = "Name is required.";
    } else {
        $name = htmlspecialchars($_POST["name"]);
    }

    //  EMAIL VALIDATION
    if (empty($_POST["email"])) {
        $emailErr = "Email is required.";
    } else {
        $email = htmlspecialchars($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format.";
        }
    }

    //  PASSWORD VALIDATION 
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required.";
    } else {
        $password = $_POST["password"];

        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long.";
        } elseif (!preg_match("/[\W]/", $password)) {
            $passwordErr = "Password must contain at least one special character.";
        }
    }

    //  CONFIRM PASSWORD 
    if (empty($_POST["confirm_password"])) {
        $confirmErr = "Please confirm your password.";
    } else {
        $confirmPassword = $_POST["confirm_password"];

        if ($password !== $confirmPassword) {
            $confirmErr = "Passwords do not match.";
        }
    }

    //  IF NO ERRORS, PROCESS REGISTRATION 
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmErr)) {

        // Attempt to read users.json
        $jsonData = @file_get_contents("users.json");

        if ($jsonData === false) {
            $fileErr = "Error: Unable to read users.json file.";
        } else {
            $usersArray = json_decode($jsonData, true);

            if (!is_array($usersArray)) {
                $usersArray = [];
            }

            // Hash password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create associative array for the new user
            $newUser = [
                "name"     => $name,
                "email"    => $email,
                "password" => $hashedPassword
            ];

            // Add user to array
            $usersArray[] = $newUser;

            // Convert back to JSON
            $newJSON = json_encode($usersArray, JSON_PRETTY_PRINT);

            // Save to file
            if (@file_put_contents("users.json", $newJSON) === false) {
                $fileErr = "Error: Could not write to users.json file.";
            } else {
                // Successful registration
                $successMsg = "Registration successful!";
                // Clear form fields
                $name = $email = "";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>

<h2>User Registration Form</h2>

<?php if (!empty($successMsg)): ?>
    <div class="success"><?php echo $successMsg; ?></div>
<?php endif; ?>

<?php if (!empty($fileErr)): ?>
    <div class="file-error"><?php echo $fileErr; ?></div>
<?php endif; ?>

<form action="" method="post">

    <label>Name:</label><br>
    <input type="text" name="name" value="<?php echo $name; ?>">
    <span class="error"><?php echo $nameErr; ?></span>
    <br><br>

    <label>Email:</label><br>
    <input type="text" name="email" value="<?php echo $email; ?>">
    <span class="error"><?php echo $emailErr; ?></span>
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password">
    <span class="error"><?php echo $passwordErr; ?></span>
    <br><br>

    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password">
    <span class="error"><?php echo $confirmErr; ?></span>
    <br><br>

    <button type="submit">Register</button>

</form>

</body>
</html>
