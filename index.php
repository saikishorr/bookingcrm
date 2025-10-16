<?php
session_start();
include('db.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Meeting Room Booking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>Meeting Room Booking System</header>
    <div class="container">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <input type="text" name="emp_id" placeholder="emp_id" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
        <?php else: ?>
            <p>Welcome, <b><?php echo $_SESSION['name']; ?></b> | <a href="logout.php" class="btn logout-btn">Logout</a></p>
        <?php endif; ?>

        <div id="calendar-container">
            <h3>Room Availability Calendar</h3>
            <table id="calendar">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Room</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $today = date('Y-m-d');
                    $sql = "SELECT r.room_name, b.booking_date, b.status 
                            FROM meeting_rooms r
                            LEFT JOIN bookings b ON r.id = b.room_id AND b.booking_date >= '$today'
                            ORDER BY b.booking_date ASC";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $statusClass = ($row['status'] == 'approved') ? 'booked' : 'available';
                            echo "<tr class='{$statusClass}'>
                                    <td>{$row['booking_date']}</td>
                                    <td>{$row['room_name']}</td>
                                    <td>{$row['status']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No bookings found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
