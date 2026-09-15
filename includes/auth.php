<?php
/**
 * Harbor General - Authentication Helpers
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['staff_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function currentStaffName(): string
{
    return $_SESSION['staff_name'] ?? 'Staff';
}

function currentStaffRole(): string
{
    return $_SESSION['staff_role'] ?? 'Staff';
}

/** Simple flash message helper (session-based) */
function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
