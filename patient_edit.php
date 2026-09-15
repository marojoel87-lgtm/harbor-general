<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch();

if (!$patient) {
    setFlash('error', 'Patient not found.');
    header('Location: patients.php');
    exit;
}

$pageTitle = 'Edit Patient';
$activeNav = 'patients';
$errors = [];
$old = $patient;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['full_name', 'gender', 'dob', 'phone', 'email', 'address', 'emergency_contact'] as $key) {
        $old[$key] = trim($_POST[$key] ?? '');
    }

    if ($old['full_name'] === '') $errors['full_name'] = 'Full name is required.';
    if (!in_array($old['gender'], ['Male', 'Female', 'Other'], true)) $errors['gender'] = 'Please select a valid gender.';
    if ($old['dob'] === '') $errors['dob'] = 'Date of birth is required.';
    if ($old['phone'] === '') $errors['phone'] = 'Phone number is required.';
    if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Please enter a valid email address.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE patients SET full_name=?, gender=?, dob=?, phone=?, email=?, address=?, emergency_contact=? WHERE id=?");
        $stmt->execute([
            $old['full_name'], $old['gender'], $old['dob'], $old['phone'],
            $old['email'] ?: null, $old['address'] ?: null, $old['emergency_contact'] ?: null, $id
        ]);
        setFlash('success', 'Patient record updated successfully.');
        header('Location: patient_view.php?id=' . $id);
        exit;
    }
}

require_once 'includes/app_header.php';
?>

<a href="patient_view.php?id=<?= $id ?>" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Patient Profile</a>

<div class="panel">
  <div class="panel-head"><h2>Edit Patient — <?= htmlspecialchars($patient['full_name']) ?></h2></div>
  <div class="panel-body">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> Please fix the highlighted fields below.</div>
    <?php endif; ?>

    <form method="POST" action="patient_edit.php?id=<?= $id ?>" novalidate>
      <div class="form-row">
        <div class="form-group">
          <label class="required" for="full_name">Full Name</label>
          <input type="text" id="full_name" name="full_name" class="<?= isset($errors['full_name']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['full_name']) ?>">
          <?php if (isset($errors['full_name'])): ?><div class="field-error"><?= $errors['full_name'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="required" for="gender">Gender</label>
          <select id="gender" name="gender">
            <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
              <option value="<?= $g ?>" <?= $old['gender'] === $g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="required" for="dob">Date of Birth</label>
          <input type="date" id="dob" name="dob" class="<?= isset($errors['dob']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars(date('Y-m-d', strtotime($old['dob']))) ?>">
          <?php if (isset($errors['dob'])): ?><div class="field-error"><?= $errors['dob'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label class="required" for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" class="<?= isset($errors['phone']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['phone']) ?>">
          <?php if (isset($errors['phone'])): ?><div class="field-error"><?= $errors['phone'] ?></div><?php endif; ?>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
          <?php if (isset($errors['email'])): ?><div class="field-error"><?= $errors['email'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label for="emergency_contact">Emergency Contact</label>
          <input type="text" id="emergency_contact" name="emergency_contact" value="<?= htmlspecialchars($old['emergency_contact'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
      </div>

      <div style="display:flex;gap:12px;">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="patient_view.php?id=<?= $id ?>" class="btn" style="color:var(--hg-ink-soft);">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php require_once 'includes/app_footer.php'; ?>
