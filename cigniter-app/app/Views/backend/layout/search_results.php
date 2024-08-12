
<div class="container">
    <?php if (!empty($error)): ?>
        <div class="alert alert-warning">
            <?= esc($error) ?>
        </div>
    <?php else: ?>
        <h5>Utilisateurs</h5>
        <?php if (!empty($users)): ?>
            <ul class="list-group">
                <?php foreach ($users as $user): ?>
                    <li class="list-group-item"><?= esc($user['username']) ?> - <?= esc($user['email']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun utilisateur trouvé.</p>
        <?php endif; ?>

        <h5 class="mt-4">Tâches</h5>
        <?php if (!empty($tasks)): ?>
            <ul class="list-group">
                <?php foreach ($tasks as $task): ?>
                    <li class="list-group-item"><?= esc($task['name']) ?> - <?= esc($task['description']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune tâche trouvée.</p>
        <?php endif; ?>

        <h5 class="mt-4">Projets</h5>
        <?php if (!empty($projects)): ?>
            <ul class="list-group">
                <?php foreach ($projects as $project): ?>
                    <li class="list-group-item"><?= esc($project['name']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun projet trouvé.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
