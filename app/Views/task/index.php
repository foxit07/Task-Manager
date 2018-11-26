<?= $this->layout('layout'); ?>

<div class="container">
    <div class="table-wrapper">
        <div class="table-title">
            <?= flash(); ?>
            <div class="row">
                <div class="col-sm-6">
                    <h2>Менеджер <b>Задач</b></h2>
                </div>
                <div class="col-sm-6">
                    <?php
                   // epr(auth());
                    ?>
                    <?php if(!auth()->getUsername()): ?>
                        <a href="#login" class="btn btn-info" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Войти</span></a>
                    <?php else: ?>
                        <div class="btn btn-info"> <?=auth()->getUsername() ?></div>
                        <a href="/logout" class="btn btn-info" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Выйти</span>  </a>
                    <?php endif; ?>
                    <a href="/create" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Добавить новую задачу</span></a>
                   <!-- <a href="#deleteEmployeeModal" class="btn btn-danger" data-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a> -->
                </div>
            </div>
        </div>
        <table  id="table" data-toggle="table" class="table table-striped table-hover">
            <thead>
            <tr>
                <th data-order='desc' data-field="id" data-sortable="true">Имя</th>
                <th data-order='desc' data-field="email" data-sortable="true">Email</th>
                <th>Задача</th>
                <th>Картинка</th>
                <th>Выполнено</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($tasks as $task): ?>

            <?php
                //epr($tasks)
            ?>
            <tr>


                <td><?= $task['user']['username'] ?></td>
                <td><?= $task['user']['email'] ?></td>
                <td><?= $task['text'] ?></td>
                <td>
                    <?php if(!empty($task['img_path'])): ?>
                    <img src="<?= config('uploadsFolder') . $task['img_path'] ?>" class="img-thumbnail" width="320" height="240" >
                    <?php else: ?>
                    Нет картинки
                    <?php endif; ?>
                </td>
                <td>
                     <span class="custom-checkbox">
                         <input type="checkbox"  name="done" <?php if ($task['done'] == 1) echo 'checked';?>  disabled>
                         <label for="checkbox1"></label>
                     </span>
                </td>
                <td>
                   <?php if(auth()->getStatus() == 1): ?>
                        <a href="task/<?= $task['id'] ?>/edit"class="edit"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                        <a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12"></div>
        </div>
        <div class="clearfix">
            <div class="hint-text">Showing <b><?= $showCount ?></b> out of <b><?= $totalCount ?></b> entries</div>
            <ul class="pagination">
                 <?= $paginator; ?>
            </ul>
        </div>

    </div>

    <!-- Edit Modal HTML -->
   <!-- <div id="addEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/task/store" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Добавить задачу</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Задача</label>
                            <textarea class="form-control" required name="text"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Изображение</label>
                            <input type="text" class="form-control" name="image" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Отмена">
                        <input type="submit" class="btn btn-success" value="Добавить">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="editEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Employee</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-info" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal HTML -->
    <div id="deleteEmployeeModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form  action="/task/<?= $task['id'] ?>/destroy" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Удаление задачи</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Вы уверенны, что хотите удалить задачу?</p>
                        <p class="text-warning"><small>Это действие не возвратимое.</small></p>
                    </div>
                    <div class="modal-footer">

                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Отмена">
                            <input type="submit" class="btn btn-danger" value="Удалить">

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Login -->
    <div id="login" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/login" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Авторизация</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                            <label>Пароль</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Отмена">
                        <input type="submit" class="btn btn-info" value="Вход">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>