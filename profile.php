<?php
require 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SESSION['role'] == 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt2 = $pdo->prepare("
    SELECT courses.* FROM courses
    JOIN enrollments ON courses.id = enrollments.course_id
    WHERE enrollments.user_id = ?
");
$stmt2->execute([$user_id]);
$my_courses = $stmt2->fetchAll();

$total_credits = 0;
foreach($my_courses as $c) {
    $total_credits += $c['credit'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color:#f4f6f9;">
    <nav>
        <span style="color:white; font-size:18px; margin-right:auto;">🎓 Course System</span>
        <a href="courses.php">Available Courses</a>
        <a href="my_courses.php">My Courses</a>
        <a href="profile.php">My Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container" style="max-width:650px;">
        <div style="text-align:center; padding: 30px 0;">
            <div style="width:90px; height:90px; background:linear-gradient(135deg,#2c3e50,#3498db); border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:36px; color:white; margin-bottom:15px;">
                👤
            </div>
            <h2 style="margin-bottom:5px;"><?= htmlspecialchars($user['username']) ?></h2>
            <p style="color:#888;"><?= htmlspecialchars($user['email']) ?></p>
            <span style="background:#eafaf1; color:#1e8449; padding:5px 15px; border-radius:20px; font-size:13px; font-weight:bold;">
                🎓 Student
            </span>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h3>📚 Courses</h3>
                <p><?= count($my_courses) ?></p>
            </div>
            <div class="stat-card">
                <h3>⭐ Credits</h3>
                <p><?= $total_credits ?></p>
            </div>
        </div>

        <?php if(count($my_courses) > 0): ?>
        <h3 style="margin:20px 0 10px;">Enrolled Courses</h3>
        <table>
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Credit</th>
            </tr>
            <?php foreach($my_courses as $course): ?>
            <tr>
                <td><?= htmlspecialchars($course['course_code']) ?></td>
                <td><?= htmlspecialchars($course['course_name']) ?></td>
                <td><?= htmlspecialchars($course['credit']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p class="empty-message">No courses enrolled yet.</p>
        <?php endif; ?>

        <br>
        <a href="courses.php" style="color:#3498db;">← Back to Courses</a>
    </div>
</body>
</html>