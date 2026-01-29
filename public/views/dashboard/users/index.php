<h2>Witaj ADMINIE w Oasis!</h2>

<p>Wybierz moduł z menu po lewej stronie.</p>

<h3>Lista użytkowników</h3>

<table class="users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Imię i nazwisko</th>
            <th>Nickname</th>
            <th>Email</th>
            <th>Rola</th>
            <th>Akcje</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->getId() ?></td>
                <td><?= htmlspecialchars($user->getFullName()) ?></td>
                <td><?= htmlspecialchars($user->getNickname()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td><?= htmlspecialchars($user->getRole()) ?></td>

                <td class="actions">
                    <?php if ($user->getRole() !== 'ADMIN'): ?>
                        <a href="/dashboard/admin/users/edit-user?id=<?= $user->getId() ?>"
                            class="btn-edit">
                            Edytuj
                        </a>

                        <a href="/dashboard/admin/users/delete-user?id=<?= $user->getId() ?>"
                            class="btn-delete"
                            onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')">
                            Usuń
                        </a>
                    <?php else: ?>
                        <span class="muted">—</span>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>