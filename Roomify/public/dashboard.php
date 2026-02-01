<?php 
session_start();


include '../includes/header.php'; 
include '../includes/navbar.php'; 

if(!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin'){
    header('Location: ../public/index.php');
    exit();
}

try {


	$sql_users = "SELECT COUNT(*) as total_users FROM users";
    $stmt_users = $pdo->query($sql_users);
    $total_users = $stmt_users->fetch(PDO::FETCH_ASSOC)['total_users'];


    $sql_staff = "SELECT COUNT(*) as staff FROM users WHERE user_role = 'staff' ";
    $stmt_staff = $pdo->query($sql_staff);
    $total_staff = $stmt_staff->fetch(PDO::FETCH_ASSOC)['staff'];

    $sql_guest = "SELECT COUNT(*) as guest FROM users WHERE user_role = 'user' ";
    $stmt_guest = $pdo->query($sql_guest);
    $total_guest = $stmt_guest->fetch(PDO::FETCH_ASSOC)['guest'];

    // Total Rooms
    $sql_rooms = "SELECT COUNT(*) as total_rooms FROM rooms";
    $stmt_rooms = $pdo->query($sql_rooms);
    $total_rooms = $stmt_rooms->fetch(PDO::FETCH_ASSOC)['total_rooms'];

    $sql_active = "SELECT COUNT(*) as active_rooms FROM rooms WHERE room_status = 'active' ";
    $stmt_active = $pdo->query($sql_active);
    $total_active_rooms = $stmt_active->fetch(PDO::FETCH_ASSOC)['active_rooms'];


    $sql_maintainance = "SELECT COUNT(*) as maintainance_rooms FROM rooms WHERE room_status = 'maintanence' ";
    $stmt_maintainance = $pdo->query($sql_maintainance);
    $total_maintainance_rooms = $stmt_maintainance->fetch(PDO::FETCH_ASSOC)['maintainance_rooms'];


    $sql_active_bookings = "SELECT COUNT(*) as active_bookings FROM booking_table 
                           WHERE booking_status = 'confirmed' 
                           AND end_time >= CURDATE()";
    $stmt_active_bookings = $pdo->query($sql_active_bookings);
    $active_bookings = $stmt_active_bookings->fetch(PDO::FETCH_ASSOC)['active_bookings'];

   
    $available_for_bookings =$total_rooms-$active_bookings;

    $monthly_revenue = 0;

    $sql_revenue = "SELECT COALESCE(SUM(r.room_price * DATEDIFF(b.end_time, b.start_time)), 0) as monthly_revenue
                   FROM booking_table b
                   JOIN rooms r ON b.room_id = r.room_id
                   WHERE b.booking_status = 'confirmed'
                   AND MONTH(b.created_at) = MONTH(CURRENT_DATE())
                   AND YEAR(b.created_at) = YEAR(CURRENT_DATE())";
    $stmt_revenue = $pdo->query($sql_revenue);
    if ($stmt_revenue) {
        $result = $stmt_revenue->fetch(PDO::FETCH_ASSOC);
        $monthly_revenue = $result ? $result['monthly_revenue'] : 0;
    } else {
        $monthly_revenue = 0;
    };


    
   
    

}catch(PDOException $e){
	$error = $e->getMessage();
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

<section>
	<div class="dashboard-stats">
	    <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-home"></i>
            </div>
            <h3 class="stat-title">Rooms Overview</h3>
            <div class="stat-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo htmlspecialchars($total_rooms); ?></div>
                    <div class="stat-label">Total Rooms</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number active"><?php echo htmlspecialchars($total_active_rooms); ?></div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number maintenance"><?php echo htmlspecialchars($total_maintainance_rooms); ?></div>
                    <div class="stat-label">Maintenance</div>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h3 class="stat-title">Current Bookings</h3>
            <div class="stat-grid">
                <div class="stat-item">
                    <div class="stat-number occupied"><?php echo htmlspecialchars($active_bookings); ?></div>
                    <div class="stat-label">Occupied</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number available"><?php echo htmlspecialchars($available_for_bookings); ?></div>
                    <div class="stat-label">Available</div>
                </div>
                <div class="stat-item">
                    <div class="occupancy-rate">
                        <?php 
                        $occupancy_rate = $total_rooms > 0 ? ($active_bookings / $total_rooms) * 100 : 0;
                        echo number_format($occupancy_rate, 1) . '%';
                        ?>
                    </div>
                    <div class="stat-label">Occupancy Rate</div>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <h3 class="stat-title">Monthly Revenue</h3>
            <div class="revenue-display">
                <div class="revenue-amount">NPR <?php echo number_format($monthly_revenue, 2); ?></div>
                <div class="revenue-period">This Month</div>
            </div>
            <div class="revenue-breakdown">
                <?php if($monthly_revenue > 0 && $active_bookings > 0): ?>
                    <div class="breakdown-item">
                        <span>Average per booking:</span>
                        <span>NPR <?php echo number_format($monthly_revenue / $active_bookings, 2); ?></span>
                    </div>
                <?php endif; ?>
                <div class="breakdown-item">
                    <span>Active bookings:</span>
                    <span><?php echo $active_bookings; ?></span>
                </div>
            </div>
        </div>
        
        <!-- User Statistics -->
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="stat-title">User Management</h3>
            <div class="user-stats">
                <div class="user-stat-item">
                    <div class="user-stat-number total"><?php echo htmlspecialchars($total_users); ?></div>
                    <div class="user-stat-label">Total Users</div>
                </div>
                <div class="user-stat-breakdown">
                    <div class="user-role">
                        <span class="role-badge staff"><?php echo htmlspecialchars($total_staff); ?></span>
                        <span class="role-label">Staff Members</span>
                    </div>
                    <div class="user-role">
                        <span class="role-badge guest"><?php echo htmlspecialchars($total_guest); ?></span>
                        <span class="role-label">Guest Users</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




<?php include '../includes/footer.php'; ?>