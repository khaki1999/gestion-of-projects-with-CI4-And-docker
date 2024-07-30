<form id="editTaskForm">
    <input type="hidden" name="task_id" value="<?= esc($task['id']) ?>">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    <div class="form-group">
        <label for="name">Nom de la tâche</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= esc($task['name']) ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control" id="description" name="description" required><?= esc($task['description']) ?></textarea>
    </div>
    <div class="form-group">
        <label for="project_id">Projet</label>
        <select class="form-control" id="project_id" name="project_id" required>
            <?php foreach ($projects as $project): ?>
                <option value="<?= $project['id'] ?>" <?= $task['project_id'] == $project['id'] ? 'selected' : '' ?>>
                    <?= esc($project['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="user_id">Utilisateur</label>
        <select class="form-control" id="user_id" name="user_id" required>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>" <?= in_array($user['id'], $userIds) ? 'selected' : '' ?>>
                    <?= esc($user['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour la tâche</button>
</form>

<script>
    document.getElementById('editTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        fetch('<?= route_to('tasks.update') ?>', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tâche mise à jour avec succès.');
                // Vous pouvez aussi rediriger ou mettre à jour l'interface utilisateur ici
            } else {
                alert('Erreur lors de la mise à jour.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
</script>
