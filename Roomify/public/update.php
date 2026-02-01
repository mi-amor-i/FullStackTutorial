<?php
session_start();
include '../includes/header.php';
include '../includes/navbar.php';

$error = '';
$success = '';


$id = $_GET['id'] ?? ''; 

if (!$id) {
    die("Invalid ID");
}
	$sql="SELECT * FROM rooms WHERE room_id=?";
	$stmt= $pdo-> prepare($sql);
	$stmt->execute([$id]);
	$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found");
}

if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['update_room'])){
    $name = $_POST['name'];
	$type = $_POST['type'];
	$capacity = $_POST['capacity'];
	$price = $_POST['price'];
	$status = $_POST['status'];
	$description = $_POST['description'];


	$imagePath = $room['room_image'];

	if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
        if(in_array($_FILES['image']['type'], $allowed)) {
            // Create unique filename
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = "../uploads/" . $fileName;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $fileName;
                // Optionally delete old image
                if($room['room_image'] && file_exists("../uploads/" . $room['room_image'])) {
                    unlink("../uploads/" . $room['room_image']);
                }
            }
        }
    }


    $updateSql = "UPDATE rooms SET room_name = ?, room_type = ?, room_capacity = ?, room_price = ?, room_status = ?, room_description = ?, room_image = ? WHERE id = ?";
    $updateStmt = $pdo->prepare($updateSql);


    if($updateStmt->execute([$name, $type, $capacity, $price, $status, $description, $id])){
    	$success = "Room updated successfully";

    	$stmt->execute([$id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
                $error = "Failed to update room.";
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
	<h3 class="page_head">Update Room</h3>

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
		Name	<input type="text" name="name" value=<?=htmlspecialchars($room['room_name'])?> required><br>
		Type	<select name="type" value=<?=htmlspecialchars($room['room_type'])?> required>
					<option value="">Select Room Type</option>
					<option value="single" <?php echo ($room['room_type'] == 'single') ? 'selected' : ''; ?> >Single Room</option>
					<option value="double" <?php echo ($room['room_type'] == 'double') ? 'selected' : ''; ?> >Double Room</option>
					<option value="suite" <?php echo ($room['room_type'] == 'suite') ? 'selected' : ''; ?> >Suite Room</option>
					<option value="hostel" <?php echo ($room['room_type'] == 'hostel') ? 'selected' : ''; ?> >Hostel</option>
				</select><br>
		Capacity	<input type="number" name="capacity" value=<?=htmlspecialchars($room['room_capacity'])?> required><br>
		Price per day	<input type="number" name="price" required><br>
		Status	<select name="status" value=<?=htmlspecialchars($room['room_status'])?> required>
					<option value="active">Active</option>
					<option value="maintanence">Maintanence</option>
				</select><br>
		Description	<input type="text" name="description" value=<?=htmlspecialchars($room['room_description'])?> required><br>
		Image	<input type="file" name="image" accept="image/jpeg,image/png" required><br>
		<button type="submit" name='update_room'>Update</button>
	</form>
</section>
<?php include '../includes/footer.php'; ?>