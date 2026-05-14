<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: courses.php");
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Course Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .landing-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .landing-page::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: rgba(44, 108, 237, 0.15);
            border-radius: 50%;
            top: -150px;
            right: -150px;
        }

        .landing-page::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(44, 108, 237, 0.1);
            border-radius: 50%;
            bottom: -100px;
            left: -100px;
        }

        .landing-badge {
            background: rgba(44, 108, 237, 0.2);
            border: 1px solid rgba(44, 108, 237, 0.4);
            color: #93c5fd;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            letter-spacing: 1px;
            margin-bottom: 30px;
            z-index: 1;
        }

        .landing-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 60px 70px;
            text-align: center;
            max-width: 680px;
            width: 100%;
            z-index: 1;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }

        .landing-icon {
            font-size: 60px;
            margin-bottom: 20px;
            display: block;
        }

        .landing-card h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.3;
        }

        .landing-card h1 span {
            color: #60a5fa;
        }

        .landing-card p {
            color: #94a3b8;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 40px;
        }

        .landing-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 40px;
            padding: 20px 0;
            border-top: 1px solid rgba(255,255,255,0.08);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .landing-stat {
            text-align: center;
        }

        .landing-stat strong {
            display: block;
            color: #60a5fa;
            font-size: 24px;
            font-weight: 800;
        }

        .landing-stat span {
            color: #64748b;
            font-size: 12px;
        }

        .landing-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .landing-btn-primary {
            padding: 14px 40px;
            background: linear-gradient(135deg, #2c6fed, #1d4ed8);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(44, 108, 237, 0.4);
        }

        .landing-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(44, 108, 237, 0.5);
        }

        .landing-btn-secondary {
            padding: 14px 40px;
            background: rgba(255,255,255,0.08);
            color: white;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            border: 1px solid rgba(255,255,255,0.15);
            transition: 0.3s;
        }

        .landing-btn-secondary:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-3px);
        }

        .landing-footer {
            margin-top: 30px;
            color: #475569;
            font-size: 12px;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="landing-page">
        <div class="landing-badge">🎓 Web Programming Project 2026</div>
        <div class="landing-card">
            <span class="landing-icon">📚</span>
            <h1>Student Course <span>Management</span> System</h1>
            <p>A web-based platform where students can view available courses, enroll in courses, and manage their enrolled courses easily.</p>
            <div class="landing-stats">
                <div class="landing-stat">
                    <strong>15+</strong>
                    <span>Courses</span>
                </div>
                <div class="landing-stat">
                    <strong>PHP</strong>
                    <span>Backend</span>
                </div>
                <div class="landing-stat">
                    <strong>MySQL</strong>
                    <span>Database</span>
                </div>
                <div class="landing-stat">
                    <strong>4</strong>
                    <span>Developers</span>
                </div>
            </div>
            <div class="landing-buttons">
                <a href="login.php" class="landing-btn-primary">🔑 Login</a>
                <a href="register.php" class="landing-btn-secondary">✨ Register</a>
            </div>
        </div>
        <div class="landing-footer">Ece Sürdemir • Zeynep Ekmen • Beyza Günsay • Burçin Sarı</div>
    </div>
</body>
</html>