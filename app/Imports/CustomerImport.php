<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Customer([
            'customer_code' => $row['customer_code'],
            'full_name' => $row['full_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'company_name' => $row['company_name'],
            'status' => $row['status'],
        ]);
    }
}
