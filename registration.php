<?php
session_start();            // nothing may be printed before this line
if (isset($_SESSION['login'])) {
    header('location:index.php');
    die();
}

$pageTitle  = 'Registration';
$activePage = 'login';
require_once __DIR__ . '/includes/header.php';

// Error flag and message set by actions/registration.php
$registrationFailed = isset($_SESSION['registration']) && $_SESSION['registration'] == false;
$registrationError  = $_SESSION['registration_error'] ?? 'Invalid parameters. Please check your data and try again.';

// What the visitor typed before the error (the password is never kept)
$old = $_SESSION['registration_old'] ?? [];
unset($_SESSION['registration'], $_SESSION['registration_error'], $_SESSION['registration_old']);
$val = function (string $key) use ($old): string {
    return e($old[$key] ?? '');
};

// Select lists
$stmt = $pdo->prepare("SELECT GenderID, GenderName FROM genders");
$stmt->execute();
$genders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT CountryID, CountryName FROM countries");
$stmt->execute();
$countries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
    <div class="panel auth-card" style="max-width: 680px;">
        <h1 class="h3 fw-bold mb-1">Create your account</h1>
        <p class="text-secondary mb-4">It only takes a minute.</p>

        <?php if ($registrationFailed): ?>
            <div class="alert alert-danger" role="alert"><?= e($registrationError) ?></div>
        <?php endif; ?>

        <form action="actions/registration.php" method="POST" autocomplete="off">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="fname" class="form-label">First name</label>
                    <input name="fname" type="text" class="form-control" id="fname" placeholder="Enter your name" value="<?= $val('fname') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="lname" class="form-label">Last name</label>
                    <input name="lname" type="text" class="form-control" id="lname" placeholder="Enter your last name" value="<?= $val('lname') ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Enter your phone" value="<?= $val('phone') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input name="address" type="text" class="form-control" id="address" placeholder="Enter your address" value="<?= $val('address') ?>" required>
                </div>

                <!-- readonly until focus: stops the browser from filling in a saved login (for example the phpMyAdmin "root" account on localhost) -->
                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input name="username" type="text" class="form-control" id="username" placeholder="Choose a username"
                           value="<?= $val('username') ?>" autocomplete="off" readonly
                           onfocus="this.removeAttribute('readonly');" required>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" id="password" placeholder="At least 6 characters"
                           autocomplete="new-password" readonly
                           onfocus="this.removeAttribute('readonly');" required>
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select name="gender" id="gender" class="form-select">
                        <?php if (!$genders): ?>
                            <option value="">None</option>
                        <?php else: ?>
                            <?php foreach ($genders as $gender): ?>
                                <option value="<?= (int) $gender['GenderID'] ?>"
                                    <?= (string) ($old['gender'] ?? '') === (string) $gender['GenderID'] ? 'selected' : '' ?>>
                                    <?= e($gender['GenderName']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <select name="country" id="country" class="form-select">
                        <?php if (!$countries): ?>
                            <option value="">None</option>
                        <?php else: ?>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= (int) $country['CountryID'] ?>"
                                    <?= (string) ($old['country'] ?? '') === (string) $country['CountryID'] ? 'selected' : '' ?>>
                                    <?= e($country['CountryName']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-accent w-100 btn-lg mt-4">Create account</button>
        </form>

        <p class="text-center text-secondary mt-4 mb-0">
            Already registered? <a href="login.php">Login</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
