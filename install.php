<?php
/**
 * Magaram Real Estate - One-Click Database Setup & Seeder
 */
require_once __DIR__ . '/includes/config.php';

$message = '';
$status = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Connect to MySQL server (without selecting DB first)
        $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $sqlFile = __DIR__ . '/database.sql';
        if (!file_exists($sqlFile)) {
            throw new Exception("database.sql file not found!");
        }

        $sql = file_get_contents($sqlFile);
        
        // Execute queries
        $pdo->exec($sql);

        $message = "Database 'magaram_db' and all required tables successfully initialized! Default admin created (admin / admin123).";
        $status = 'success';
    } catch (Exception $e) {
        $message = "Installation failed: " . $e->getMessage() . "<br><small>Tip: Ensure MySQL is running in XAMPP Control Panel.</small>";
        $status = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup | Magaram Real Estate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #080808;
            padding: 20px;
        }
        .install-box {
            max-width: 540px;
            width: 100%;
            background: #141414;
            border: 1px solid var(--border-gold);
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.8);
        }
    </style>
</head>
<body>
    <div class="install-box">
        <img src="assets/img/magaram-logo.svg" alt="Magaram Crest" width="60" height="60" style="margin: 0 auto 16px auto;">
        <h2 style="font-size: 1.8rem; margin-bottom: 8px;">Database Setup</h2>
        <p style="color: var(--text-muted); font-size: 0.92rem; margin-bottom: 24px;">
            Initialize the database schema, seed luxury sample properties, and configure default settings.
        </p>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $status; ?>" style="text-align: left; margin-bottom: 24px;">
                <?= $message; ?>
            </div>
            <?php if ($status === 'success'): ?>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <a href="index.php" class="btn btn-primary btn-sm">View Website</a>
                    <a href="admin/login.php" class="btn btn-outline btn-sm">Admin Login</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($status !== 'success'): ?>
            <form method="POST">
                <div style="background: #1c1c1c; padding: 16px; border-radius: 6px; text-align: left; font-size: 0.85rem; color: #ccc; margin-bottom: 24px;">
                    <div><strong>Host:</strong> <?= DB_HOST; ?>:<?= DB_PORT; ?></div>
                    <div><strong>Database:</strong> <?= DB_NAME; ?></div>
                    <div><strong>User:</strong> <?= DB_USER; ?></div>
                    <div><strong>Default Admin:</strong> admin / admin123</div>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    Install & Seed Database
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

