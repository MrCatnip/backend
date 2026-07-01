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
                        <button type="button" class="delete-user"
                                data-username="<?= htmlspecialchars($user->username, ENT_QUOTES) ?>">Delete</button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <script>
        document.querySelectorAll('.delete-user').forEach((button) => {
            button.addEventListener('click', async () => {
                const username = button.dataset.username;
                if (!confirm(`Delete ${username}?`)) return;

                const response = await fetch('/delete?username=' + encodeURIComponent(username), {
                    method: 'DELETE', // real DELETE verb
                });
                const data = await response.json();

                if (data.success) window.location.reload();
            });
        });
    </script>
<?php endif ?>
