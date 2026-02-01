<?php
session_start();
include '../includes/header.php';
include '../includes/navbar.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

	$name = $_POST['name'];
	$type = $_POST['type'];
	$capacity = $_POST['capacity'];
	$price = $_POST['price'];
	$description = $_POST['description'];
	$status = $_POST['status'];

	if(!isset($_FILES['image']) || !isset($_FILES['image']['error']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE ){
		$error = "No file uploaded";
	}else{

		$file = $_FILES['image'];
		$allowedExtensions = ['jpg', 'jpeg', 'png'];
		$maxSize = 2 * 1024 * 1024;
		$uploadDir = __DIR__ . '/uploads/';


		if (!is_dir($uploadDir)) {
			if (!mkdir($uploadDir, 0755, true)) {
            	$error = "Upload directory is missing or not writable.";
            }
        } else {

            // File info
            $fileName = $file['name'];
            $fileTmp  = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($fileError !== 0) {
                $error = "File upload error.";
            } elseif (!in_array($fileExt, $allowedExtensions)) {
                $error = "Invalid file format. Only PDF, JPG, PNG allowed.";
            } elseif ($fileSize > $maxSize) {
                $error = "File size exceeds 2MB limit.";
            } else {
            	$newFileName = "roomify_" 
                    . strtolower(str_replace(' ', '_', pathinfo($fileName, PATHINFO_FILENAME)))
                    . "_" . time()
                    . "." . $fileExt;

                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $destination)) {
                    $success = "File uploaded successfully!";
                } else {
                    $error = "Failed to move uploaded file.";
                }
            }
        }
	}

		try{


                $sql = "INSERT INTO rooms (room_name, room_type, room_capacity, room_price, room_status, room_description, room_image) VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt=$pdo->prepare($sql);
                $stmt->execute([$name,$type,$capacity,$price,$status,$description,$newFileName]);

                $message = "Added new room";
                header('refresh: 2; url=dashboard.php');
            }catch(PDOException $e){
            	 $error = "Database error "; 
            }
}

?>

<section class="second_navbar">
	<ul>
		<li><a href="dashboard.php">Dashboard</a></li>
		<li><a href="add.php">Add New Room</a></li>
		<li><a href="rooms.php">Edit Room</a></li>
		<li><a href="edit.php">Edit User</a></li>
		<li><a href="booking.php">Book a room</a></li>
		<li><a href="booked.php">Booked List</a></li>
	</ul>
</section>

<section class="add_room">
	<h3 class="page_head">Add New Room</h3>

    <?php if ($error): ?>
        <div style="color: red; padding: 10px; background: #ffe6e6; border-radius: 5px; margin: 10px 0;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div style="color: green; padding: 10px; background: #e6ffe6; border-radius: 5px; margin: 10px 0;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
	<form method="POST" enctype="multipart/form-data">
		Name	<input type="text" name="name" required><br>
		Type	<select name="type" required>
					<option value="">Select Room Type</option>
					<option value="single">Single Room</option>
					<option value="double">Double Room</option>
					<option value="suite">Suite Room</option>
					<option value="hostel">Hostel</option>
				</select><br>
		Capacity	<input type="number" name="capacity" required><br>
		Price per day	<input type="number" name="price" required><br>
		Status	<select name="status" required>
					<option value="active">Active</option>
					<option value="maintanence">Maintanence</option>
				</select><br>
		Description	<input type="text" name="description" required><br>
		Image	<input type="file" name="image" accept="image/jpeg,image/png" required><br>
		<button type="submit">Add</button>
	</form>
</section>
<?php include '../includes/footer.php'; ?>