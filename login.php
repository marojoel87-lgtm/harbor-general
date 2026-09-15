<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$oldUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldUsername = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($oldUsername === '' || $password === '') {
        $error = 'Please enter both username/email and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$oldUsername, $oldUsername]);
        $staff = $stmt->fetch();

        if ($staff && password_verify($password, $staff['password'])) {
            $_SESSION['staff_id'] = $staff['id'];
            $_SESSION['staff_name'] = $staff['full_name'];
            $_SESSION['staff_role'] = $staff['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username/email or password.';
        }
    }
}

$pageTitle = 'Staff Login';
$activePage = 'login';
require_once 'includes/header.php';
?>

<div class="auth-wrap">
  <div class="auth-card">
    <a href="index.php" class="brand">
      <span class="brand-mark"><i class="fa-solid fa-house-medical"></i></span>
      Harbor <span>General</span>
    </a>
    <h2>Staff Login</h2>
    <p class="sub">Sign in to access the management dashboard.</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <div class="form-group">
        <label class="required" for="username">Username or Email</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($oldUsername) ?>" required autofocus>
      </div>
      <div class="form-group">
        <label class="required" for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>
