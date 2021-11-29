<?php require __DIR__ . '/top.php' ?>

<div class="container">
    <div class="row justify-content-md-center mt-5">
        <div class="col-4">
            <div class="card m-2">
                <div class="card-body">
                    <h5 class="card-title">Registracija</h5>
                    <form action="<?= URL . 'register' ?>" method="POST">
                        <div class="form-group">
                            <label for="user">Vartotojo vardas</label>
                            <input type="text" class="form-control" name="user" id="user">
                        </div>
                        <div class="form-group">
                            <label for="email">El. paštas</label>
                            <input type="text" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label for="pass">Slaptažodis</label>
                            <input type="password" class="form-control" name="pass" id="pass">
                        </div>
                        <button type="submit" class="btn btn-success">Registruotis</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require __DIR__ . '/bottom.php' ?>