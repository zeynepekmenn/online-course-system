<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();
if (!$course) {
    header("Location: admin_dashboard.php");
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
        $stmt = $pdo->prepare("UPDATE courses SET course_code = ?, course_name = ?, instructor = ?, credit = ?, quota = ? WHERE id = ?");
        $stmt->execute([$course_code, $course_name, $instructor, $credit, $quota, $id]);
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
    <title>Edit Course</title>
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
        <h2>✏️ Edit Course</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="course_code" value="<?= htmlspecialchars($course['course_code']) ?>" required>
            <input type="text" name="course_name" value="<?= htmlspecialchars($course['course_name']) ?>" required>
            <input type="text" name="instructor" value="<?= htmlspecialchars($course['instructor']) ?>" required>
            <input type="number" name="credit" value="<?= htmlspecialchars($course['credit']) ?>" required>
            <input type="number" name="quota" value="<?= htmlspecialchars($course['quota']) ?>" required>
            <button type="submit">Update Course</button>
        </form>
        <br>
        <a href="admin_dashboard.php" style="color:#3498db;">← Back to Dashboard</a>
    </div>
</body>
</html>