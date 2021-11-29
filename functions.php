<?php

//Validžių asmens kodu generatorius:
//http://runa.lt/useful/asmens_kodas

function generateNewIban(array $ibans): string
{
    $ibanArr = [];

    foreach ($ibans as $iban) {
        $ibanArr[] = $iban['account_number'];
    }

    $countryCode = 'LT';
    $controlNumber = 70;
    $bankCode = 70440;

    do {
        $accountNumberInit = str_pad(rand(1, 99999999999), 11, '0', STR_PAD_LEFT);
    } while (in_array($countryCode . $controlNumber . $bankCode . $accountNumberInit, $ibanArr));

    return $countryCode . $controlNumber . $bankCode . $accountNumberInit;
}
