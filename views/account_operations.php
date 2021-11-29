<?php require __DIR__ . '/top.php' ?>

<div class="container">
    <div class="row mt-3">
        <div class="col-6">

            <?php
            $userUri = str_replace(INSTALL_DIR, '', $_SERVER['REQUEST_URI']);
            $userUri = preg_replace('/\?.*/', '', $userUri);
            $userUri = explode('/', $userUri);
            $acc_action = isset($_GET['add_to']) ? 'add_to' : (isset($_GET['deduct_from']) ? 'deduct_from' : '');
            ?>
            <?php echo $acc_action == 'add_to' ? '<h4 class="mb-3">Pridėti lėšų</h4>' : ($acc_action == 'deduct_from'  ? '<h4 class="mb-3">Nuskaičiuoti lėšas</h4>' : '') ?>

            <form action="<?= URL . 'update/' . $customer_info['id'] ?>" method="POST">

                <div class="form-group">
                    <label for="customer_name">Sąskaitos savininkas</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" disabled value="<?= $customer_info['name'] . ' ' . $customer_info['surname'] ?>">
                </div>
                <div class="form-group">
                    <label for="balance">Sąskaitos likutis</label>
                    <input type="text" class="form-control" name="balance" id="balance" disabled value="<?= $customer_info['balance'] ?>">
                </div>
                <div class="form-group">
                    <label for="add_balance">Įveskite sumą</label>
                    <input type="hidden" name="acc_action" value="<?= $acc_action ?>">
                    <input type="text" class="form-control" name="add_balance" id="add_balance" autocomplete="off">
                </div>
                <button class="btn btn-success"><?php echo $acc_action == 'add_to' ? 'Papildyti' : ($acc_action == 'deduct_from' ? 'Nuskaičiuoti' : '') ?></button>

            </form>
        </div>
    </div>
</div>
<?php require __DIR__ . '/bottom.php' ?>