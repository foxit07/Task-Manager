<?= $this->layout('layout'); ?>

<?= flash(); ?>

<form action="/task/<?= $task['id'] ?>/update" method="post">
    <div class="modal-header">
        <h4 class="modal-title">Редактирование задачи</h4>
    </div>
    <div class="modal-body">
        <div class="form-group">
              <span class="custom-checkbox">
                  <input type="checkbox" id="checkbox1" name="done" <?php if ($task['done'] == 1) echo 'checked';?>  >
                  <label for="checkbox1"> Выполнено</label>
              </span>
        </div>
        <div class="form-group">
            <label>Имя</label>
            <input type="text" class="form-control" name="username" required value='<?= $task['user']['username'] ?>'">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required value='<?= $task['user']['email'] ?>'>
        </div>
        <div class="form-group">
            <label>Задача</label>
            <textarea class="form-control" required name="text" ><?= $task['text'] ?></textarea>
        </div>
        <div class="form-group">
            <label>Изображение</label>
            <input type="text" class="form-control" name="image" required>
        </div>
    </div>
    <div class="modal-footer">
        <a href="/" class="btn btn-default">На главную</a>
        <input type="submit" class="btn btn-success" value="Изменить">
    </div>
</form>
