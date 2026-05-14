<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $instructor = $_POST['instructor'];
    $credit = $_POST['credit'];
    $quota = $_POST['quota'];
    if ($course_code && $course_name && $instructor && $credit && $quota) {
        $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name, instructor, credit, quota) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$course_code, $course_name, $instructor, $credit, $quota]);
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <span style="color:white; font-size:18px; margin-right:auto;">🎓 Admin Panel</span>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_course.php">Add Course</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container" style="max-width:550px;">
        <h2>➕ Add New Course</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="course_code" placeholder="Course Code" required>
            <input type="text" name="course_name" placeholder="Course Name" required>
            <input type="text" name="instructor" placeholder="Instructor" required>
            <input type="number" name="credit" placeholder="Credit" required>
            <input type="number" name="quota" placeholder="Quota" required>
            <button type="submit">Add Course</button>
        </form>
        <br>
        <a href="admin_dashboard.php" style="color:#3498db;">← Back to Dashboard</a>
    </div>
</body>
</html>