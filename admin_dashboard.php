<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();
$total_courses = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$total_students = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$total_enrollments = $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
       <a href="admin_dashboard.php" style="font-size:18px; margin-right:auto;">🎓 Admin Panel</a>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_course.php">Add Course</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h2>Course Management</h2>
        <div class="stats-container">
            <div class="stat-card">
                <h3>📚 Total Courses</h3>
                <p><?= $total_courses ?></p>
            </div>
            <div class="stat-card">
                <h3>👨‍🎓 Total Students</h3>
                <p><?= $total_students ?></p>
            </div>
            <div class="stat-card">
                <h3>✅ Total Enrollments</h3>
                <p><?= $total_enrollments ?></p>
            </div>
        </div>
        <a href="add_course.php" class="btn">+ Add New Course</a>
        <table>
            <tr>
                <th>Code</th>
                <th>Course Name</th>
                <th>Instructor</th>
                <th>Credit</th>
                <th>Quota</th>
                <th>Enrolled</th>
                <th>Actions</th>
            </tr>
            <?php foreach($courses as $course): ?>
            <?php
                $enrolled = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
                $enrolled->execute([$course['id']]);
                $enrolled_count = $enrolled->fetchColumn();
            ?>
            <tr>
                <td><?= htmlspecialchars($course['course_code']) ?></td>
                <td><?= htmlspecialchars($course['course_name']) ?></td>
                <td><?= htmlspecialchars($course['instructor']) ?></td>
                <td><?= htmlspecialchars($course['credit']) ?></td>
                <td><?= htmlspecialchars($course['quota']) ?></td>
                <td><?= $enrolled_count . '/' . $course['quota'] ?></td>
                <td>
                    <a href="edit_course.php?id=<?= $course['id'] ?>">✏️ Edit</a>
                    <a href="delete_course.php?id=<?= $course['id'] ?>" 
                       style="color:#e74c3c;"
                       onclick="return confirm('Are you sure you want to delete this course?')">🗑️ Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>