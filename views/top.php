<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bankas v2</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/css/style.css">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous">
    </script>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand navbar-nav ml-auto">Bankas</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL . 'sarasas' ?>">Sąskaitų sąrašas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL . 'create' ?>" name="new_account">Nauja sąskaita</a>
                    </li>
                </ul>
            </div>
            <?php if ($appUser) : ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo '<span>Prisijungęs darbuotojas:</span> ' . $appUser ?></a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                            <li class="justify-content-end">
                                <?php if ($appUser) : ?>
                                    <form action="<?= URL . 'logout' ?>" method="POST">
                                        <button class="btn btn-link" type="submit">Atsijungti</button>
                                    </form>
                            </li>
                        <?php endif ?>
                        </ul>
                    </li>
                </ul>
            <?php endif ?>
        </div>
    </nav>

    <?php if (!empty($messages)) : ?>
        <div class="container">
            <div class="row justify-content-center mt-2">
                <div class="col">
                    <?php foreach ($messages as $message) : ?>
                        <div class="alert alert-<?= $message['type'] ?>" role="alert">
                            <?= $message['msg'] ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php else : ?>

        <div class="m-5">
        </div>

    <?php endif ?>

    <?php if (isset($_SESSION['errors'])) : ?>
        <div class="container">
            <div class="row justify-content-center mt-2">
                <div class="col">
                    <?php foreach ($_SESSION['errors'] as $error) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $error  ?>
                        </div>
                    <?php endforeach ?>
                    <?php unset($_SESSION['errors']) ?>
                </div>
            </div>
        </div>
    <?php else : ?>

        <div class="m-5">
        </div>

    <?php endif ?>