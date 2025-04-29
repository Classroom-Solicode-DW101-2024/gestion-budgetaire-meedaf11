<?php
require_once '../config.php'; 
require_once '../user.php'; 


if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = updateUser($pdo, $_POST);

    if ($result === true) {
        $message = "Profile updated successfully.";
    } else {
        $message = $result; 
    }
}

detailsUser($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../style/profile.css">
</head>
<body>

<div class="profileContainer">
    <h2>Edit Profile</h2>

    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST" class="profileForm">
        <div class="formGroup">
            <label for="nom">Full Name</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_SESSION['user']['nom']) ?>">
        </div>

        <div class="formGroup">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
        </div>

        <div class="formGroup">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="Leave blank if unchanged">
        </div>

        <div class="formGroup">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Leave blank if unchanged">
        </div>

        <button type="submit" class="saveButton">Save Changes</button>
    </form>
</div>

</body>
</html>
