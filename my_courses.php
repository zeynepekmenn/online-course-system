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
$stmt = $pdo->prepare("
    SELECT courses.* 
    FROM courses 
    JOIN enrollments ON courses.id = enrollments.course_id 
    WHERE enrollments.user_id = ?
");
$stmt->execute([$user_id]);
$my_courses = $stmt->fetchAll();

$total_credits = 0;
foreach($my_courses as $c) {
    $total_credits += $c['credit'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Courses</title>
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
    <div class="container">
        <h2>My Enrolled Courses</h2>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'unenrolled'): ?>
    <p class="success">✅ Successfully unenrolled from the course!</p>
<?php endif; ?>

        <div class="stats-container">
            <div class="stat-card">
                <h3>📚 Enrolled Courses</h3>
                <p><?= count($my_courses) ?></p>
            </div>
            <div class="stat-card">
                <h3>⭐ Total Credits</h3>
                <p><?= $total_credits ?></p>
            </div>
        </div>

        <?php if(count($my_courses) == 0): ?>
            <p class="empty-message">You have not enrolled in any courses yet.</p>
            <br>
            <a href="courses.php" class="btn">Browse Available Courses</a>
        <?php else: ?>
            <table>
                <tr>
                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Instructor</th>
                    <th>Credit</th>
                    <th>Action</th>
                </tr>
                <?php foreach($my_courses as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['course_code']) ?></td>
                    <td><?= htmlspecialchars($course['course_name']) ?></td>
                    <td><?= htmlspecialchars($course['instructor']) ?></td>
                    <td><?= htmlspecialchars($course['credit']) ?></td>
                    <td>
                        <a href="unenroll.php?id=<?= $course['id'] ?>"
                           style="color:#e74c3c;"
                           onclick="return confirm('Are you sure you want to unenroll from this course?')">
                           ❌ Unenroll
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>