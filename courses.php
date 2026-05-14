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
$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Available Courses</title>
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
        <h2>Available Courses</h2>
        <?php if(isset($_GET['msg'])): ?>
    <?php if($_GET['msg'] == 'success'): ?>
        <p class="success">✅ Successfully enrolled in the course!</p>
    <?php elseif($_GET['msg'] == 'already'): ?>
        <p class="error">⚠️ You are already enrolled in this course!</p>
    <?php elseif($_GET['msg'] == 'full'): ?>
        <p class="error">❌ This course is full!</p>
    <?php endif; ?>
<?php endif; ?>
        <table>
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Instructor</th>
                <th>Credit</th>
                <th>Quota</th>
                <th>Action</th>
            </tr>
            <?php foreach($courses as $course): ?>
            <?php
                $check = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
                $check->execute([$user_id, $course['id']]);
                $is_enrolled = $check->rowCount() > 0;
                $quota_check = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
                $quota_check->execute([$course['id']]);
                $enrolled_count = $quota_check->fetchColumn();
                $is_full = $enrolled_count >= $course['quota'];
            ?>
            <tr>
                <td><?= htmlspecialchars($course['course_code']) ?></td>
                <td>
                    <a href="course_detail.php?id=<?= $course['id'] ?>">
                        <?= htmlspecialchars($course['course_name']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($course['instructor']) ?></td>
                <td><?= htmlspecialchars($course['credit']) ?></td>
                <td><?= $enrolled_count . '/' . $course['quota'] ?></td>
                <td>
                    <?php if ($is_enrolled): ?>
                        <span class="success-text">✅ Enrolled</span>
                    <?php elseif ($is_full): ?>
                        <span style="color:#e74c3c; font-weight:bold;">Full</span>
                    <?php else: ?>
                        <a href="enroll.php?id=<?= $course['id'] ?>">Enroll</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>