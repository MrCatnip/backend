<?php
/**
 * @var string $heading
 * @var string $action
 * @var string $method   HTTP verb sent via fetch() (POST create, PUT update)
 * @var string $submitLabel
 * @var array<string, array{label: string, type: string, max: int, readonly?: bool}> $fields
 * @var array<string, string> $old
 */
?>
<h1><?= htmlspecialchars($heading) ?></h1>

<form id="user-form" action="<?= htmlspecialchars($action) ?>" data-method="<?= htmlspecialchars($method) ?>">
    <?php foreach ($fields as $name => $meta): ?>
        <?php $readonly = !empty($meta['readonly']) ? ' readonly' : ''; ?>
        <label>
            <?= htmlspecialchars($meta['label']) ?>
            <?php if ($meta['type'] === 'int'): ?>
                <input type="number" name="<?= $name ?>" min="0" max="<?= (int) $meta['max'] ?>"
                       value="<?= htmlspecialchars($old[$name] ?? '') ?>"<?= $readonly ?>>
            <?php else: ?>
                <input type="text" name="<?= $name ?>" maxlength="<?= (int) $meta['max'] ?>"
                       value="<?= htmlspecialchars($old[$name] ?? '') ?>"<?= $readonly ?>>
            <?php endif ?>
        </label>
        <p class="error" data-error-for="<?= $name ?>"></p>
    <?php endforeach ?>

    <button type="submit"><?= htmlspecialchars($submitLabel) ?></button>
</form>

<script>
    document.getElementById('user-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const form = event.target;

        // Clear any previous error messages.
        form.querySelectorAll('.error').forEach((el) => (el.textContent = ''));

        const payload = Object.fromEntries(new FormData(form));
        const response = await fetch(form.action, {
            method: form.dataset.method, // real POST / PUT verb
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        });
        const data = await response.json();

        if (data.success) {
            window.location.href = '/users';
            return;
        }

        // Show validation errors next to their fields (inputs keep their values).
        for (const [field, message] of Object.entries(data.errors ?? {})) {
            const el = form.querySelector(`[data-error-for="${field}"]`);
            if (el) el.textContent = message;
        }
    });
</script>
