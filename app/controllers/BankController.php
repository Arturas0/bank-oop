<?php

namespace Bank\Controllers;

use App\DB\DataBase;
use Bank\Messages;
use Bank\App;
use Bank\Validator;
use Bank\Controllers\LoginController;

class BankController implements DataBase
{
    function create(array $userData): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $sql = "SELECT
            account_number
            FROM
            customers
            ";

            $stmt = App::$pdo->query($sql);
            $ibans = $stmt->fetchAll();
            App::view('new', ['ibans' => $ibans]);
        } else {

            $name = htmlspecialchars($userData['name']);
            $surname = htmlspecialchars($userData['surname']);
            $identification_number = htmlspecialchars($userData['identification_number']);
            $accNumber = htmlspecialchars(str_replace(' ', '', $userData['account_number']));

            $validator = new Validator($_POST);
            $validator->minLength([
                ['name', 3, 'vardas'],
                ['surname', 3, 'pavardė']
            ]);
            $validator->checkPIN($identification_number);

            if ($validator->fails()) {
                $_SESSION['errors'] = $validator->getErrors();
                App::redirect('create');
            }

            //Get all identification numbers from database
            $sql = "SELECT identification_number
            FROM
            customers
            ";
            $stmt = App::$pdo->query($sql);
            $idNumbers = $stmt->fetchAll();
            $identification_numbersArr = [];

            foreach ($idNumbers as $idNumber) {
                $identification_numbersArr[] = $idNumber['identification_number'];
            }

            if (!in_array($identification_number, $identification_numbersArr)) {
                $sql = "INSERT INTO
                customers
                (`name`, surname, identification_number, account_number)
                VALUES (:name, :surname, :personalCode, :accountNumber)
                ";

                $stmt = App::$pdo->prepare($sql);
                $stmt->execute(['name' => $name, 'surname' => $surname, 'personalCode' => $identification_number, 'accountNumber' => $accNumber]);

                Messages::add('success', 'Nauja sąskaita sukurta sėkmingai');
                LoginController::cleanUser();
                App::redirect('sarasas');
            } else {
                Messages::add('danger', 'Klientas su asmens kodu ' . $identification_number . ', jau užregistruotas!');
            }
            App::redirect('create');
        }
    }


    function update(int $userId, array $userData): void
    {
        LoginController::cleanUser();

        $fbalance = str_replace(',', '.', $userData['add_balance']);
        $addToBalance = (is_numeric($fbalance) && $fbalance > 0) ? $fbalance : 0;
        $acc_action = trim($_POST['acc_action']);

        $sql = "SELECT
        balance AS current_balance
        FROM
        customers
        WHERE id = :userId
        ";
        $stmt = App::$pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $currentBalance = $stmt->fetch()['current_balance'];

        $newBalance = $currentBalance;

        if ($acc_action == 'add_to') {
            if ($addToBalance != 0) {
                $newBalance = $currentBalance + $addToBalance;
                Messages::add('success', 'Lėšos pridėtos sėkmingai');
            } else {
                Messages::add('danger', 'Į sumos laukelį įvesti ne skaitmenys!');
            }
        } elseif ($acc_action == 'deduct_from') {
            if ($addToBalance != 0) {
                if (($currentBalance - $addToBalance) >= 0) {
                    $newBalance = $currentBalance - $addToBalance;
                    Messages::add('success', 'Lėšos nuskaitytos sėkmingai!');
                } else {
                    Messages::add('danger', 'Sąskaitoje nėra pakankamai lėšų. Nuskaitymas negalimas!');
                }
            } else {
                Messages::add('danger', 'Į sumos laukelį įvesti ne skaitmenys!');
            }
        }

        $sql = "UPDATE
        customers
        SET balance = :newBalance
        WHERE id = :userId
        ";

        $stmt = App::$pdo->prepare($sql);
        $stmt->execute(['newBalance' => $newBalance, 'userId' => $userId]);
        App::redirect('sarasas');
    }


    function delete(int $userId): void
    {
        $sql = "SELECT
        balance AS current_balance
        FROM
        customers
        WHERE id = :userId
        ";
        $stmt = App::$pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $currentBalance = $stmt->fetch()['current_balance'];

        if ($currentBalance == 0) {
            $sql = "DELETE
                    FROM customers
                    WHERE id = :userId
            ";
            $stmt = App::$pdo->prepare($sql);
            $stmt->execute(['userId' => $userId]);

            Messages::add('success', 'Kliento sąskaita ištrinta!');
        } else {
            Messages::add('danger', 'Kliento sąskaitoje yra lėšų. Sąskaitos ištrinti negalima!');
        }
        App::redirect('sarasas');
    }


    function show(int $userId): array
    {
        LoginController::cleanUser();

        $sql = "SELECT
        id, `name`, surname, account_number, balance
        FROM
        customers
        WHERE id = :userId";

        $stmt = App::$pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);
        $customerInfo = $stmt->fetch();

        return  ['customer_info' => $customerInfo];
    }


    function showAll(): array
    {
        LoginController::cleanUser();

        $sql = "SELECT
        id, `name`, surname, identification_number, account_number, balance
        FROM
        customers
        ORDER BY surname ASC
        ";

        $stmt = App::$pdo->query($sql);
        $customers = $stmt->fetchAll();

        return  ['customers' => $customers];
    }
}
