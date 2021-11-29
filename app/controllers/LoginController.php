<?php

namespace Bank;

namespace Bank\Controllers;

use Bank\App;
use Bank\Messages;

class LoginController
{
    static private function logIn()
    {
        $user = $_POST['user'];

        $sql = "SELECT user, pass
        FROM
        users 
        WHERE user='$user'
        ";

        $stmt = App::$pdo->query($sql);
        $user = $stmt->fetch();

        if (password_verify($_POST['pass'],  $user['pass'])) {
            $_SESSION['name'] = $user['user'];
            $_SESSION['logged'] = 1;
            return true;
        }
        Messages::add('danger', 'Vartotojo vardas ar slaptažodis nėra teisingas');
        return false;
    }

    public function show()
    {
        App::view('login');
    }

    public static function isLogged()
    {
        return isset($_SESSION['logged']) &&  $_SESSION['logged'] == 1;
    }


    public function doLogin()
    {
        $ok = self::logIn();

        if (!$ok) {
            App::redirect('login');
        }
        App::redirect('sarasas');
    }

    public function doLogout()
    {
        unset($_SESSION['name'], $_SESSION['logged']);
        App::redirect('login');
    }


    public function register()
    {
        App::view('register');
    }

    public function doRegister()
    {
        $user = $_POST['user'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

        $sql = "SELECT user, email
        FROM
        users 
        WHERE user = '$user' OR email = '$email'
        ";
        $stmt = App::$pdo->query($sql);

        if (!$stmt->fetch()) {
            $sql = "INSERT INTO
            users
            (user, email, pass)
            VALUES
            ('$user', '$email', '$pass')
            ";
            App::$pdo->query($sql);
            Messages::add('success', 'Vartotojas sukurtas sėkmingai');
            App::redirect('sarasas');
        }
        Messages::add('danger', 'Vartotojas su nurodytu vardu ar el. paštu jau egzistuoja');
        App::redirect('register');
    }

    public static function cleanUser()
    {
        unset($_SESSION['fname'], $_SESSION['fsurname'], $_SESSION['fidentification_number'], $_SESSION['fnumber']);
    }
}
