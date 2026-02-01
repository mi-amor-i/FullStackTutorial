<?php
session_start();
include '../includes/header.php';
include '../includes/navbar.php';

$error = '';
$success = '';

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