<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class CodeGenerator
{
    public static function uniqueFourDigit(string $modelClass, string $column = 'contractor_code'): string
    {
        do {
            $code = str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while ($modelClass::where($column, $code)->exists());

        return $code;
    }

    public static function patientCode(): string
    {
        return 'PAT'.now()->format('ymd').strtoupper(substr(uniqid(), -4));
    }

    public static function prescriptionNo(): string
    {
        return 'RX'.now()->format('ymdHis').random_int(10, 99);
    }
}
