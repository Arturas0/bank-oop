<?php

namespace Bank;

use Bank\Controllers\BankController;
use Bank\Controllers\LoginController;
use Bank\Messages;
use PDO;

class App
{
    static $pdo;

    public static function start()
    {
        echo 'Labas 1';
        self::db();
        echo 'Labas 2';
        return self::route();
    }


    public static function route()
    {
        $userUri = str_replace(INSTALL_DIR, '', $_SERVER['REQUEST_URI']);
        $userUri = preg_replace('/\?.*/', '', $userUri);
        $userUri = explode('/', $userUri);


        if (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            $userUri[0] == 'login' && count($userUri) == 1
        ) {
            if (LoginController::isLogged()) {
                self::redirect('sarasas');
            }
            return (new LoginController)->show();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            $userUri[0] == 'login' && count($userUri) == 1
        ) {
            return (new LoginController)->doLogin();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            $userUri[0] == 'register' && count($userUri) == 1
        ) {
            if (LoginController::isLogged()) {
                self::redirect('register');
            }
            return (new LoginController)->register();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            $userUri[0] == 'register' && count($userUri) == 1
        ) {
            if (LoginController::isLogged()) {
                self::redirect('sarasas');
            }
            return (new LoginController)->doRegister();
        }


        //Tikriname ar prisijungęs vartotojas
        if (!LoginController::isLogged()) {
            self::redirect('login');
        }

        if (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            $userUri[0] == 'sarasas' && count($userUri) == 1
        ) {
            return self::view('list', (new BankController)->showAll());
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            $userUri[0] == 'create' && count($userUri) == 1
        ) {
            return (new BankController)->create($userData = []);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            $userUri[0] == 'create' && count($userUri) == 1
        ) {
            $userData = ['name' => $_POST['name'], 'surname' => $_POST['surname'], 'identification_number' => $_POST['identification_number'], 'account_number' => $_POST['account_number']];
            $_SESSION['fname'] = $_POST['name'];
            $_SESSION['fsurname'] = $_POST['surname'];
            $_SESSION['fnumber'] = $_POST['account_number'];
            $_SESSION['fidentification_number'] = $_POST['identification_number'];
            return (new BankController)->create($userData);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            $userUri[0] == 'update' && count($userUri) == 1 && (isset($_GET['add_to']) || (isset($_GET['deduct_from'])))
        ) {
            $userId = isset($_GET['add_to']) ? $_GET['add_to'] : (isset($_GET['deduct_from']) ? $_GET['deduct_from'] : '');
            return self::view('account_operations', (new BankController)->show($userId));
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            $userUri[0] == 'update' && count($userUri) == 2
        ) {
            $userData = ['add_balance' => $_POST['add_balance'], 'acc_action' => $_POST['acc_action']];
            return (new BankController)->update($userUri[1], $userData);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            $userUri[0] == 'delete' && count($userUri) == 2
        ) {
            return (new BankController)->delete($userUri[1]);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            $userUri[0] == 'logout' && count($userUri) == 1
        ) {
            return (new LoginController)->doLogout();
        }

        echo '<h2>404 page not found </h2>';
    }


    public static function db()
    {
        $host = getSetting('host');
        $db   = getSetting('db');
        $user = getSetting('user');
        $pass = getSetting('pass');
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($dsn, $user, $pass, $options);
    }

    public static function view($template, $data = [])
    {
        extract($data);
        $appUser = $_SESSION['name'] ?? '';
        $messages = Messages::get();

        require DIR . 'views/' . $template . '.php';
    }

    public static function redirect($where)
    {
        header('Location: ' . URL . $where);
        die;
    }
}
