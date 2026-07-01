<h1>Register a user</h1>
<p><a href="/">← Back to home</a></p>

<form method="post" action="/register">
    <label>
        Username
        <input type="text" name="username" maxlength="20"
               value="<?= htmlspecialchars($old['username'] ?? '') ?>">
    </label>
    <?php if (isset($errors['username'])): ?>
        <p class="error"><?= htmlspecialchars($errors['username']) ?></p>
    <?php endif ?>

    <label>
        First name
        <input type="text" name="first_name" maxlength="255"
               value="<?= htmlspecialchars($old['first_name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['first_name'])): ?>
        <p class="error"><?= htmlspecialchars($errors['first_name']) ?></p>
    <?php endif ?>

    <label>
        Last name
        <input type="text" name="last_name" maxlength="255"
               value="<?= htmlspecialchars($old['last_name'] ?? '') ?>">
    </label>
    <?php if (isset($errors['last_name'])): ?>
        <p class="error"><?= htmlspecialchars($errors['last_name']) ?></p>
    <?php endif ?>

    <label>
        Age
        <input type="number" name="age" min="0" max="150"
               value="<?= htmlspecialchars($old['age'] ?? '') ?>">
    </label>
    <?php if (isset($errors['age'])): ?>
        <p class="error"><?= htmlspecialchars($errors['age']) ?></p>
    <?php endif ?>

    <button type="submit">Register</button>
</form>
