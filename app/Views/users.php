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
                    <td><a href="/update?username=<?= urlencode($user->username) ?>">Edit</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
