<?php
session_start();

include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$room_id  = $_GET['room_id'] ?? null;
$check_in = $_POST['check_in'] ?? null;
$check_out = $_POST['check_out'] ?? null;

if (!$check_in || !$check_out) {
    $error = "Please select check-in and check-out dates.";
}

try{

    if ($room_id) {

        $sql = "
            SELECT * FROM rooms r
            WHERE r.room_id = ?
            AND r.room_status = 'active'
            AND r.room_id NOT IN (
                SELECT b.room_id FROM bookings b
                WHERE NOT (
                    b.check_out <= ?
                    OR b.check_in >= ?
                )
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$room_id, $check_in, $check_out]);

        if ($stmt->rowCount() === 0) {
                echo json_encode([
                    'available' => false,
                    'redirect' => 'rooms.php'
                ]);
            } else {
                echo json_encode([
                    'available' => true,
                    'redirect' => 'booking.php?room_id=' . $room_id
                ]);
            }
            exit;
    }

        $sql = "
            SELECT * FROM rooms r
            WHERE room_status = 'active'
            AND r.room_id NOT IN (
                SELECT b.room_id 
                FROM bookings b
                WHERE NOT (
                    b.check_out <= ?
                    OR b.check_in >= ?
                )
            )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$check_in, $check_out]);
        $available_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $_SESSION['available_rooms'] = $rooms;
        echo json_encode(['redirect' => 'rooms.php']);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Server error']);
}

?>
