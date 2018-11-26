<?= $this->layout('layout'); ?>

            <?= flash(); ?>
            <form action="/task/store" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title">Добавить задачу</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Имя</label>
                        <input type="text" class="form-control" name="username" required ">
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
                        <div class="field">
                            <label>Картинка</label>
                            <div class="file is-normal has-name">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="image">
                                    <span class="file-cta">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="/" class="btn btn-default">На главную</a>
                    <input type="submit" class="btn btn-success" value="Добавить">
                </div>
            </form>
