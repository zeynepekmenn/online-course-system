<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) {
    header("Location: courses.php");
    exit();
}

$check = $pdo->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ?");
$check->execute([$user_id, $id]);
$is_enrolled = $check->rowCount() > 0;

/* EASY MIDTERM RESULT - EMAIL'E GÖRE OTOMATİK FARKLI NOT */
$user_stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch();

$email = $user['email'] ?? "unknown";

$generated_score = 50 + (crc32($email . $id) % 51);

$midterm_result = [
    'midterm_score' => $generated_score
];

$class_average = 74.50;

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_enrolled) {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $already = $pdo->prepare("SELECT * FROM reviews WHERE user_id = ? AND course_id = ?");
    $already->execute([$user_id, $id]);

    if ($already->rowCount() > 0) {
        $error = "You have already reviewed this course!";
    } else {
        $ins = $pdo->prepare("INSERT INTO reviews (user_id, course_id, rating, comment) VALUES (?, ?, ?, ?)");
        $ins->execute([$user_id, $id, $rating, $comment]);
        $success = "Your review has been submitted!";
    }
}

$reviews_stmt = $pdo->prepare("
    SELECT reviews.*, users.username FROM reviews
    JOIN users ON reviews.user_id = users.id
    WHERE reviews.course_id = ?
    ORDER BY reviews.created_at DESC
");
$reviews_stmt->execute([$id]);
$reviews = $reviews_stmt->fetchAll();

$avg_rating = 0;
if (count($reviews) > 0) {
    $avg_rating = array_sum(array_column($reviews, 'rating')) / count($reviews);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($course['course_name']) ?></title>
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

    <h2><?= htmlspecialchars($course['course_name']) ?></h2>

    <?php if(count($reviews) > 0): ?>
    <div style="text-align:center; margin-bottom:20px;">
        <span style="font-size:36px;">⭐</span>
        <span style="font-size:28px; font-weight:bold; color:#2c3e50;">
            <?= number_format($avg_rating, 1) ?>
        </span>
        <span style="color:#888; font-size:14px;">
            / 5 (<?= count($reviews) ?> reviews)
        </span>
    </div>
    <?php endif; ?>

    <table>
        <tr><th>Course Code</th><td><?= htmlspecialchars($course['course_code']) ?></td></tr>
        <tr><th>Instructor</th><td><?= htmlspecialchars($course['instructor']) ?></td></tr>
        <tr><th>Credit</th><td><?= htmlspecialchars($course['credit']) ?></td></tr>
        <tr><th>Quota</th><td><?= htmlspecialchars($course['quota']) ?></td></tr>
    </table>

    <br>

    <h3>📊 Midterm Results</h3>

    <div style="background:#f9f9f9; border:1px solid #ddd; border-radius:8px; padding:20px; margin-top:15px;">
        <?php if($is_enrolled): ?>

            <p>
                🧑‍🎓 <strong>Your Midterm Score:</strong>
                <?= htmlspecialchars($midterm_result['midterm_score']) ?>
            </p>

            <p>
                📈 <strong>Class Average:</strong>
                <?= number_format($class_average, 2) ?>
            </p>

        <?php else: ?>

            <p style="color:#e74c3c;">
                🔒 You must enroll in this course to see the midterm results.
            </p>

        <?php endif; ?>
    </div>

    <br>

    <h3>📄 Course Materials</h3>

    <div style="background:#f9f9f9; border:1px solid #ddd; border-radius:8px; padding:20px; margin-top:15px;">
        <?php if($is_enrolled): ?>

            <p>📘 <strong>Lecture Notes - Week 1.pdf</strong> <span style="color:#aaa; margin-left:10px;">2.3 MB</span></p>
            <p>📘 <strong>Lecture Notes - Week 2.pdf</strong> <span style="color:#aaa; margin-left:10px;">1.8 MB</span></p>
            <p>📘 <strong>Lecture Notes - Week 3.pdf</strong> <span style="color:#aaa; margin-left:10px;">3.1 MB</span></p>
            <p>📝 <strong>Assignment 1.pdf</strong> <span style="color:#aaa; margin-left:10px;">0.5 MB</span></p>
            <p>📝 <strong>Midterm Study Guide.pdf</strong> <span style="color:#aaa; margin-left:10px;">1.2 MB</span></p>

        <?php else: ?>

            <p style="color:#e74c3c;">🔒 You must enroll in this course to access the materials.</p>
            <a href="enroll.php?id=<?= $course['id'] ?>" class="btn">Enroll Now</a>

        <?php endif; ?>
    </div>

    <br>

    <h3>💬 Reviews</h3>

    <?php if($is_enrolled): ?>
    <div style="background:#f9f9f9; border:1px solid #ddd; border-radius:8px; padding:20px; margin:15px 0;">
        <h4 style="margin-bottom:15px;">Leave a Review</h4>

        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <?php if($success) echo "<p class='success'>$success</p>"; ?>

        <form method="POST">
            <label style="font-weight:bold;">Rating:</label><br>

            <select name="rating" style="margin:10px 0 15px; padding:8px; border-radius:6px; border:1px solid #ddd; width:100%;">
                <option value="5">⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                <option value="4">⭐⭐⭐⭐ (4 - Good)</option>
                <option value="3">⭐⭐⭐ (3 - Average)</option>
                <option value="2">⭐⭐ (2 - Poor)</option>
                <option value="1">⭐ (1 - Terrible)</option>
            </select>

            <textarea name="comment" placeholder="Write your review here..."></textarea>
            <button type="submit">Submit Review</button>
        </form>
    </div>
    <?php endif; ?>

    <?php if(count($reviews) > 0): ?>

        <?php foreach($reviews as $review): ?>
        <div style="background:white; border:1px solid #eee; border-radius:8px; padding:15px; margin-bottom:10px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <strong>👤 <?= htmlspecialchars($review['username']) ?></strong>
                <span style="color:#f39c12;"><?= str_repeat('⭐', $review['rating']) ?></span>
            </div>

            <p style="color:#555;"><?= htmlspecialchars($review['comment']) ?></p>
            <small style="color:#aaa;"><?= $review['created_at'] ?></small>
        </div>
        <?php endforeach; ?>

    <?php else: ?>

        <p class="empty-message">No reviews yet. Be the first to review!</p>

    <?php endif; ?>

    <br>

    <a href="courses.php" style="color:#3498db;">← Back to Courses</a>

</div>

</body>
</html>