<?php
/**
 * Harbor General - Database Connection
 * Update these values to match your local XAMPP / MySQL setup.
 */
$DB_HOST = 'localhost';
$DB_NAME = 'harbor_general';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:40px;color:#8a1f1f;background:#fdeeee;border:1px solid #f3caca;border-radius:8px;margin:40px;">
        <h2 style="margin-top:0;">Database Connection Failed</h2>
        <p>Harbor General could not connect to the database. Please make sure MySQL is running in XAMPP and that the <code>harbor_general</code> database has been imported.</p>
        <p style="color:#555;font-size:14px;">Technical detail: ' . htmlspecialchars($e->getMessage()) . '</p>
    </div>');
}
