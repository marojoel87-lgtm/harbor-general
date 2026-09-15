<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM doctors WHERE id = ?");
$stmt->execute([$id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    setFlash('error', 'Doctor not found.');
    header('Location: doctors_manage.php');
    exit;
}

$pageTitle = 'Edit Doctor';
$activeNav = 'doctors';
$departments = ['General Medicine', 'Emergency Care', 'Pediatrics', 'Cardiology', 'Laboratory Services', 'Pharmacy', 'Outpatient Services', 'Orthopedics'];

$errors = [];
$old = $doctor;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['full_name', 'specialty', 'department', 'phone', 'email', 'status'] as $key) {
        $old[$key] = trim($_POST[$key] ?? '');
    }

    if ($old['full_name'] === '') $errors['full_name'] = 'Full name is required.';
    if ($old['specialty'] === '') $errors['specialty'] = 'Specialty is required.';
    if ($old['phone'] === '') $errors['phone'] = 'Phone number is required.';
    if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE doctors SET full_name=?, specialty=?, department=?, phone=?, email=?, status=? WHERE id=?");
        $stmt->execute([$old['full_name'], $old['specialty'], $old['department'], $old['phone'], $old['email'] ?: null, $old['status'], $id]);
        setFlash('success', 'Doctor record updated successfully.');
        header('Location: doctors_manage.php');
        exit;
    }
}

require_once 'includes/app_header.php';
?>

<a href="doctors_manage.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Doctors</a>

<div class="panel">
  <div class="panel-head"><h2>Edit Doctor — Dr. <?= htmlspecialchars($doctor['full_name']) ?></h2></div>
  <div class="panel-body">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Please fix the highlighted fields below.</div>
    <?php endif; ?>

    <form method="POST" action="doctor_edit.php?id=<?= $id ?>" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label class="required" for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" class="<?= isset($errors['full_name']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['full_name']) ?>">
          <?php if (isset($errors['full_name'])): ?><div class="field-error"><?= $errors['full_name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="required" for="specialty">Specialty</label>
          <input type="text" id="specialty" name="specialty" class="<?= isset($errors['specialty']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['specialty']) ?>">
          <?php if (isset($errors['specialty'])): ?><div class="field-error"><?= $errors['specialty'] ?></div><?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="required" for="department">Department</label>
          <select id="department" name="department">
            <?php foreach ($departments as $d): ?>
              <option value="<?= $d ?>" <?= $old['department'] === $d ? 'selected' : '' ?>><?= $d ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label for="status">Availability Status</label>
          <select id="status" name="status">
            <option value="Available" <?= $old['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
            <option value="Unavailable" <?= $old['status'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="required" for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" class="<?= isset($errors['phone']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['phone']) ?>">
          <?php if (isset($errors['phone'])): ?><div class="field-error"><?= $errors['phone'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
          <?php if (isset($errors['email'])): ?><div class="field-error"><?= $errors['email'] ?></div><?php endif; ?>
        </div>
      </div>

      <div style="display:flex;gap:12px;">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="doctors_manage.php" class="btn" style="color:var(--hg-ink-soft);">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require_once 'includes/app_footer.php'; ?>
