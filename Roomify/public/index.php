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

<section class='front_page_first'>
	<h2>Book it. Stay it. Love it.</h2>
	<p>Welcome to Roomify! Easily check room availability, book your stay, and enjoy hassle-free management.</p>

</section>
<section class='booking_calender'>
	<form method="POST">
		<label>Check In</label>
		<input type="date" class="check" name="check_in">
		<label>Check out</label>
		<input type="date" id="check" name="check_out">
		<!-- <label>Room</label>
		<select>
			<option value="">Select Room Type</option>
			<option value="single">Single Room</option>
			<option value="double">Double Room</option>
			<option value="suite">Suite Room</option>
			<option value="hostel">Hostel</option>
		</select>
		<label>Guests</label>
		<button type="button" class="decrease" data-target="adults">-</button>
	    <input type="number" id="adults" name="adults" value="1" min="1" max="10" readonly>
	    <button type="button" class="increase" data-target="adults">+</button> -->

	    <button type="submit" name="confirmAvailability">Check Availability</button>
	</form>
</section>
<section class='front_page_second'>
	
</section>
<section class="rooms_details">
	<div class="section-header">
        <h3 class="page_head">Available Rooms</h3>
        
        <!-- Filter Controls -->
        <div class="filter-controls">
            <div class="filter-group">
                <label for="filterPrice">Price Range:</label>
                <select id="filterPrice">
                    <option value="all">All Prices</option>
                    <option value="0-5000">Under Rs 5,000</option>
                    <option value="5000-10000">Rs 5,000 - Rs 10,000</option>
                    <option value="10000-15000">Rs 10,000 - Rs 15,000</option>
                    <option value="15000-20000">Rs 15,000 - Rs 20,000</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="filterCapacity">Capacity:</label>
                <select id="filterCapacity">
                    <option value="all">All Capacities</option>
                    <option value="1">1 Person</option>
                    <option value="2">2 People</option>
                    <option value="3">3 People</option>
                    <option value="4">4+ People</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="sortBy">Sort By:</label>
                <select id="sortBy">
                    <option value="name_asc">Name (A-Z)</option>
                    <option value="name_desc">Name (Z-A)</option>
                    <option value="price_low">Price (Low to High)</option>
                    <option value="price_high">Price (High to Low)</option>
                    <option value="capacity_low">Capacity (Low to High)</option>
                    <option value="capacity_high">Capacity (High to Low)</option>
                </select>
            </div>
            
            <button id="resetFilters" class="btn-reset">Reset Filters</button>
        </div>
    </div>
    <?php if (isset($error)): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="rooms-scroll-container">
        <button class="scroll-btn scroll-left" id="scrollLeft">‹</button>
        
        <div class="rooms-container" id="roomsContainer">
            <?php if (empty($rooms)): ?>
                <div class="no-rooms-message">
                    <p>No rooms available at the moment.</p>
                </div>
            <?php else: ?>
            	<?php foreach ($rooms as $room): ?>
                    <div class="room-card" 
                         data-name="<?php echo htmlspecialchars(strtolower($room['room_name'])); ?>"
                         data-price="<?php echo $room['room_price']; ?>"
                         data-capacity="<?php echo $room['room_capacity']; ?>">
                         <div class="room-image">
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
                         <div class="room-content">
                            <h4 class="room-title"><?php echo htmlspecialchars($room['room_name']); ?></h4>
                            
                            <div class="room-features">
                                <div class="feature">
                                    <i class="fas fa-users"></i>
                                    <span><?php echo $room['room_capacity']; ?></span>
                                </div>
                                <div class="feature">
                              
                                    <span><?php echo ucfirst($room['room_type']); ?></span>
                                </div>
                            </div>
                            
                            <div class="room-price">
                                <span class="price">NPR<?php echo number_format($room['room_price'], 2); ?></span>
                                <span class="period">/ night</span>
                            </div>
                            <p class="room-description">
                                <?php 
                                $desc = htmlspecialchars($room['room_description']);
                                echo strlen($desc) > 80 ? substr($desc, 0, 80) . '...' : $desc;
                                ?>
                            </p>
                            
                            <div class="room-actions">
                            	<?php if(isset($_SESSION['user_id'])){ ?>

                                <ul>
                                	<li><a href="booking.php?room_id=<?php echo $room['room_id']; ?>" class="btn-book">Book Now</a></li>
                                	<li><a href="#" class="btn-book check-room" data-room-id="<?= $room['room_id']; ?>">Check Available</a></li>
                                </ul>
                               <?php } else { ?>
                               <ul>
                                	<li><a href="login.php" class="btn-book">Book Now</a></li>

                                </ul>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <button class="scroll-btn scroll-right" id="scrollRight">›</button>
    </div>
   
</section>

<div id="availabilityModal" class="modal" style="display:none;">
    <h3>Check Availability</h3>

    <label>Check In</label>
    <input type="date" id="modal_check_in">

    <label>Check Out</label>
    <input type="date" id="modal_check_out">

    <button id="confirmAvailability">Check</button>
</div>

<script src="../assets/script.js"></script>

<?php include '../includes/footer.php'; ?>