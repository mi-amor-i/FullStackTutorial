<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/header.php';
include '../includes/navbar.php';

$error = '';
$success = '';

$user_role  = $_GET['room_id'] ?? null;
$user_id = $_SESSION['user_id'];
// $user_name = $_SESSION['user_name'] ?? '';
// $user_email = $_SESSION['user_mail'] ?? '';


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Validate and sanitize inputs
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $room_id = $_POST['room_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $booking_status = 'confirmed';
    $user_name = $_POST['customer_name'];
    $user_mail = $_POST['customer_email'];
    
    // Basic validation
    if(empty($room_id) || empty($start_time) || empty($end_time)) {
        $error = "Please fill all required fields.";
    } elseif ($end_time <= $start_time) {
        $error = "Check-out date must be after check-in date.";
    } else {
        try {
            // Check room availability
            $sql = "SELECT room_id, room_capacity, room_name, room_price FROM rooms 
                    WHERE room_id = ? AND room_status = 'active'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$room_id]);
            $room = $stmt->fetch();
            
            if(!$room) {
                $error = "Selected room is not available.";
            } else {
                // Check for overlapping bookings (only confirmed ones)
                $sql = "SELECT booking_id FROM booking_table 
                        WHERE room_id = ? 
                        AND booking_status = 'confirmed' 
                        AND (
                            (start_time <= ? AND end_time >= ?) OR
                            (start_time >= ? AND start_time < ?)
                        )";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$room_id, $end_time, $start_time, $start_time, $end_time]);
                
                if($stmt->rowCount() > 0) {
                    $error = "Room is already booked for selected dates.";
                } else {
                    // Calculate total amount
                    $start = new DateTime($start_time);
                    $end = new DateTime($end_time);
                    $days = $end->diff($start)->days;
                    $total_amount = $room['room_price'] * $days;
                    
                    // Insert booking
                    $sql = "INSERT INTO booking_table 
                            (user_id, room_id, customer_name, customer_email, start_time, end_time, booking_status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $pdo->prepare($sql);
                    if($stmt->execute([$user_id, $room_id, $user_name, $user_mail, $start_time, $end_time, $booking_status])) {
                        $booking_id = $pdo->lastInsertId();
                        $success = "Booking confirmed successfully! Booking ID: " . $booking_id . 
                                   " Total Amount: NPR " . number_format($total_amount, 2);
                    } else {
                        $error = "Failed to create booking.";
                    }
                }
            }
        } catch(PDOException $e) {
            $error = "Booking error: " . $e->getMessage();
        }
    }
}

// Get available rooms for dropdown
$rooms = [];
try {
    $sql = "SELECT room_id, room_name, room_type, room_price, room_capacity 
            FROM rooms 
            WHERE room_status = 'active' 
            ORDER BY room_type, room_name";
    $stmt = $pdo->query($sql);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error loading rooms: " . $e->getMessage();
}

// Function to capitalize room type properly
function formatRoomType($type) {
    $type = strtolower($type);
    $capitalized = ucwords($type);
    
    // Special cases
    $mapping = [
        'single' => 'Single Room',
        'double' => 'Double Room',
        'suite' => 'Suite Room',
        'hostel' => 'Hostel Room',
        'deluxe' => 'Deluxe Room',
        'executive' => 'Executive Suite',
        'presidential' => 'Presidential Suite'
    ];
    
    return isset($mapping[$type]) ? $mapping[$type] : $capitalized . ' Room';
}
?>


<?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
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
<?php endif; ?>
<section class="add_room">
    <h3 class="page_head">Book a Room</h3>
    
    <?php if ($error): ?>
        <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" id="bookingForm">
        <!-- User ID (hidden if logged in) -->
        <?php if(isset($_SESSION['user_id'])): ?>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
        <?php endif; ?>
        
        <!-- 1. Room Selection -->
        <div class="form-row">
            <div class="form-group">
                <label for="room_id">Select Room *</label>
                <select id="room_id" name="room_id" required>
                    <option value="">-- Select a Room --</option>
                    <?php foreach($rooms as $room): 
                        $formattedType = formatRoomType($room['room_type']);
                        $displayText = $formattedType;
                    ?>
                        <option value="<?php echo $room['room_id']; ?>" 
                                data-name="<?php echo htmlspecialchars($room['room_name']); ?>"
                                data-type="<?php echo htmlspecialchars($room['room_type']); ?>"
                                data-formatted-type="<?php echo htmlspecialchars($formattedType); ?>"
                                data-capacity="<?php echo $room['room_capacity']; ?>"
                                data-price="<?php echo $room['room_price']; ?>">
                            <?php echo htmlspecialchars($displayText); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- 2. Booking Dates -->
        <div class="form-row">
            <div class="form-group">
                <label for="start_time">Check-in Date *</label>
                <input type="date" id="start_time" name="start_time" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="end_time">Check-out Date *</label>
                <input type="date" id="end_time" name="end_time" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="customer_name">Customer Name *</label>
                <input type="text" name="customer_name" value="<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user' && isset($_SESSION['user_name'])) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="end_time">Customer Email *</label>
                <input type="text" name="customer_email" value="<?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user' && isset($_SESSION['user_mail'])) ? htmlspecialchars($_SESSION['user_mail']) : ''; ?>" required>
            </div>
        </div>

        <!-- 3. Room Info Display -->
        <div class="room-info-display">
            <h4>Room Information</h4>
            <div class="info-row">
                <span>Room Name:</span>
                <span id="display_room_name">-</span>
            </div>
            <div class="info-row">
                <span>Room Type:</span>
                <span id="display_room_type">-</span>
            </div>
            <div class="info-row">
                <span>Capacity:</span>
                <span id="display_room_capacity">-</span>
            </div>
            <div class="info-row">
                <span>Price per day:</span>
                <span id="display_room_price">NPR 0.00</span>
            </div>
        </div>
        
        <!-- 4. Booking Summary -->
        <div class="booking-summary">
            <h4>Booking Summary</h4>
            <div class="summary-row">
                <span>Room Price:</span>
                <span id="summary_room_price">NPR 0.00</span>
            </div>
            <div class="summary-row">
                <span>Duration:</span>
                <span id="duration_display">0 days</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span id="total_amount_display">NPR 0.00</span>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit">Confirm Booking</button>
        </div>
    </form>
</section>

<script src="../assets/booking.js"></script>
<?php include '../includes/footer.php'; ?>