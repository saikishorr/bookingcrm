<?php
session_start();
include 'db.php';

if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin': header("Location: admin.php"); exit;
        case 'manager': header("Location: manager.php"); exit;
        case 'user': header("Location: user.php"); exit;
    }
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_id = trim($_POST['emp_id']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE emp_id='$emp_id' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['emp_id'] = $user['emp_id'];

        switch ($user['role']) {
            case 'admin': header("Location: admin.php"); break;
            case 'manager': header("Location: manager.php"); break;
            case 'user': header("Location: user.php"); break;
        }
        exit;
    } else {
        $error = "❌ Invalid Employee ID or Password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Meeting Room Booking</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>Meeting Room Booking System</header>
<div class="container">
    <h2>Login</h2>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <label>Employee ID:</label>
        <input type="text" name="emp_id" placeholder="Enter Employee ID" required>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
