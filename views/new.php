<?php require __DIR__ . '/top.php' ?>
<?php require __DIR__ . '/../functions.php' ?>

<div class="container">
    <div class="row mt-3">
        <div class="col-6">
            <h4 class="mb-3">Naujos sąskaitos sukūrimas</h4>
            <form action="<?= URL . 'create' ?>" method="POST">
                <fieldset>
                    <div class="form-group">
                        <label for="name">Kliento vardas</label>
                        <input class="form-control" type="text" name="name" id="name" value="<?= $_SESSION['fname'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="surname">Kliento Pavardė</label>
                        <input class="form-control" type="text" name="surname" id="surname" value="<?= $_SESSION['fsurname'] ?? '' ?>">
                    </div>
                    <div class=" form-group">
                        <label for="c_personal_code">Asmens kodas</label>
                        <input class="form-control" type="text" name="identification_number" id="c_personal_code" value="<?= $_SESSION['fidentification_number'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Sąskaitos numeris</label>
                        <input class="form-control" type="text" name="account_number" readonly value="<?= $_SESSION['fnumber'] ?? implode(" ", str_split(generateNewIban($ibans), 4)) ?>">
                    </div>

                    <button class="btn btn-success mt-3">Sukurti vartotoją</button>
                </fieldset>
            </form>

        </div>
    </div>
</div>