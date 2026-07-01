<?php
/**
 * @var string $heading
 * @var string $action
 * @var string $submitLabel
 * @var array<string, array{label: string, type: string, max: int, readonly?: bool}> $fields
 * @var array<string, string> $old
 * @var array<string, string> $errors
 */
?>
<h1><?= htmlspecialchars($heading) ?></h1>
<p><a href="/">← Back to home</a></p>

<form method="post" action="<?= htmlspecialchars($action) ?>">
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
        <?php if (isset($errors[$name])): ?>
            <p class="error"><?= htmlspecialchars($errors[$name]) ?></p>
        <?php endif ?>
    <?php endforeach ?>

    <button type="submit"><?= htmlspecialchars($submitLabel) ?></button>
</form>
