<?php require __DIR__ . '/top.php' ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h5 class="justify-content-center">Banko klientų sąskaitų sąrašas</h5>
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Vardas</th>
                        <th scope="col">Pavardė</th>
                        <th scope="col">Sąskaitos numeris</th>
                        <th scope="col">Sąskaitos likutis</th>
                        <th scope="col">Veiksmai su sąskaita</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($customers as $customer) : ?>
                        <tr>
                            <td><?= $customer['name'] ?> </td>
                            <td><?= $customer['surname'] ?> </td>
                            <td><?= implode(" ", str_split($customer['account_number'], 4)) ?> </td>
                            <td><?= $customer['balance'] ?? 0 ?> </td>
                            <td>
                                <div class="row">
                                    <div class="form-group">
                                        <a class="btn btn-link form-control" href=" <?= URL . 'update?add_to=' . $customer['id'] ?>">Pridėti lėšų</a>
                                    </div>
                                    <div class="form-group">
                                        <a class="btn btn-link form-control" href=" <?= URL . 'update?deduct_from=' . $customer['id'] ?>">Nuskaičiuoti lėšas</a>
                                    </div>
                                    <div class="form-group">
                                        <form action="<?= URL . 'delete/' . $customer['id'] ?>" method="POST">
                                            <button class="btn btn-danger form-control" type="submit" name="btn_delete">Ištrinti</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require __DIR__ . '/bottom.php' ?>