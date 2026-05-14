<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$course_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Already enrolled?
$stmt = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$stmt->execute([$user_id, $course_id]);

if ($stmt->rowCount() > 0) {
    header("Location: courses.php?msg=already");
    exit();
}

// Quota check
$stmt = $pdo->prepare("SELECT quota FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT COUNT(*) FROM enrollments WHERE course_id = ?");
$stmt2->execute([$course_id]);
$enrolled_count = $stmt2->fetchColumn();

if ($enrolled_count >= $course['quota']) {
    header("Location: courses.php?msg=full");
    exit();
}

// Enroll
$stmt = $pdo->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
$stmt->execute([$user_id, $course_id]);

header("Location: courses.php?msg=success");
exit();
?>