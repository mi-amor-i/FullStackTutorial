<?php

session_start();
include '../includes/header.php';
include '../includes/navbar.php';


try {
    $sql = "SELECT * FROM rooms WHERE room_status = 'active' ORDER BY room_name";
    $stmt = $pdo->query($sql);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching rooms: " . $e->getMessage();
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


<section class="rooms-management">
    <h3 class="page_head">Rooms Management</h3>
    
    <?php if (isset($error)): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <div class="rooms-list-container">
        <?php if (empty($rooms)): ?>
            <div class="no-rooms-message">
                <p>No rooms available at the moment.</p>
            </div>
        <?php else: ?>
            <table class="rooms-table">
                <thead>
                    <tr>
                        <th>Room Image</th>
                        <th>Room Details</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rooms as $room): 
                        $status_class = ($room['room_status'] == 'active') ? 'status-active' : 'status-maintenance';
                        $status_text = ($room['room_status'] == 'active') ? 'Active' : 'Maintenance';
                    ?>
                        <tr class="room-row">
                            <td class="room-cell-image">
                                <div class="room-list-image">
                                    <?php if (!empty($room['room_image']) && file_exists("uploads/" . $room['room_image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($room['room_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($room['room_name']); ?>"
                                             onerror="this.src='../uploads/default-room.jpg'">
                                    <?php else: ?>
                                        <div class="room-image-placeholder">
                                            <i class="fas fa-bed"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <td class="room-cell-details">
                                <div class="room-list-info">
                                    <h4 class="room-list-name"><?php echo htmlspecialchars($room['room_name']); ?></h4>
                                    <div class="room-list-features">
                                        <div class="room-feature">
                                            <i class="fas fa-users"></i>
                                            <span>Capacity: <?php echo $room['room_capacity']; ?> people</span>
                                        </div>
                                        <div class="room-feature">
                                            <i class="fas fa-door-closed"></i>
                                            <span>Type: <?php echo ucfirst($room['room_type']); ?></span>
                                        </div>
                                    </div>
                                    <p class="room-list-description">
                                        <?php echo htmlspecialchars($room['room_description']); ?>
                                    </p>
                                </div>
                            </td>
                            
                            <td class="room-cell-status">
                                <span class="room-status <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            
                            <td class="room-cell-price">
                                <div class="room-list-price">
                                    <span class="room-price-amount">NPR<?php echo number_format($room['room_price'], 2); ?></span>
                                    <span class="room-price-period">/ night</span>
                                </div>
                            </td>
                            
                            <td class="room-cell-actions">
                                <div class="room-list-actions">
                                    <a href="../public/update.php?id=<?php echo $room['room_id']; ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="../public/delete.php?id=<?php echo $room['room_id']; ?>" 
                                       class="btn-delete" 
                                       onclick="return confirm('Are you sure you want to delete this room?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                    <a href="toggle_status.php?id=<?php echo $room['room_id']; ?>" class="btn-status">
                                        <?php echo ($room['room_status'] == 'active') ? 'Set Maintenance' : 'Set Active'; ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
