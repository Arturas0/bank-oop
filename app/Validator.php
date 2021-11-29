<?php

namespace Bank;

class Validator
{
    private $errors = [];
    private $requests;

    public function __construct(array $requests)
    {
        $this->requests = $requests = array_map(fn ($el) =>
        htmlspecialchars($el), $requests);
    }

    public function required(array $fields): void
    {
        foreach ($fields as $field) {
            if ($this->requests[$field] == '' || is_null($this->requests[$field])) {
                array_push($this->errors, "Laukas {$field} neužpildytas");
            }
        }
    }

    public function minLength(array $fields): void
    {
        foreach ($fields as $field) {
            if (strlen($this->requests[$field[0]]) < $field[1]) {
                array_push($this->errors, "Laukelis {$field[2]} turi turėti bent {$field[1]} simbolius");
            }
        }
    }

    public function checkPIN(string $inputPIN): bool
    {
        if (!strlen($inputPIN) == 0 && substr($inputPIN, 3, 2) <= 12 && substr($inputPIN, 5, 2) <= 31) {
            $coeff1 = 1234567891;
            $coeff2 = 3456789123;

            foreach (range(1, 2) as $round) {
                $S = 0;
                $coeffArr = array_map('intval', str_split(${'coeff' . $round}));

                foreach (range(0, strlen($inputPIN) - 2) as $num) {
                    $S += $inputPIN[$num] * $coeffArr[$num];
                }

                $K = $S % 11;
                if (($K != 10 && $inputPIN[10] == $K && $round == 1) || ($K == 10 && $inputPIN[10] == 0 && $round == 2)) {
                    return true;
                } else {
                    array_push($this->errors, "Asmens kodas nėra teisingas");
                    return false;
                }
            }
        }
        array_push($this->errors, "Asmens kodas nėra teisingas");
        return false;
    }

    public function fails(): bool
    {
        if (!count($this->errors) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
