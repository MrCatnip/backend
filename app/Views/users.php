<h1>Users</h1>
<p><a href="/">← Back to home</a></p>

<?php if (empty($users)): ?>
    <p>No users found.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Age</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->username) ?></td>
                    <td><?= htmlspecialchars($user->first_name) ?></td>
                    <td><?= htmlspecialchars($user->last_name) ?></td>
                    <td><?= htmlspecialchars((string) $user->age) ?></td>
                    <td>
                        <a href="/update?username=<?= urlencode($user->username) ?>">Edit</a>
                        <form method="post" action="/delete" style="display:inline"
                              onsubmit="return confirm('Delete <?= htmlspecialchars($user->username, ENT_QUOTES) ?>?')">
                            <input type="hidden" name="username" value="<?= htmlspecialchars($user->username, ENT_QUOTES) ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
