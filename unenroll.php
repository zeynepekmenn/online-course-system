<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$course_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ?");
$stmt->execute([$user_id, $course_id]);

header("Location: my_courses.php?msg=unenrolled");
exit();
?>