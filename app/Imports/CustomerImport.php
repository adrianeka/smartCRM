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
            'customer_code' => $row['customer_code'] ?? null,
            'full_name' => $row['full_name'] ?? null,
            'job_title' => $row['job_title'] ?? null,
            'email' => $row['email'] ?? null,
            'website' => $row['website'] ?? null,
            'phone' => $row['phone'] ?? null,
            'whatsapp' => $row['whatsapp'] ?? null,
            'company_name' => $row['company_name'] ?? null,
            'industry' => $row['industry'] ?? null,
            'identity_number' => $row['identity_number'] ?? null,
            'tax_number' => $row['tax_number'] ?? null,
            'gender' => $row['gender'] ?? null,
            'birth_date' => $row['birth_date'] ?? null,
            'address' => $row['address'] ?? null,
            'city' => $row['city'] ?? null,
            'province' => $row['province'] ?? null,
            'postal_code' => $row['postal_code'] ?? null,
            'country' => $row['country'] ?? 'Indonesia',
            'status' => $row['status'] ?? 'Lead',
            'customer_type' => $row['customer_type'] ?? null,
            'source' => $row['source'] ?? null,
            'lead_score' => $row['lead_score'] ?? null,
            'preferred_contact_method' => $row['preferred_contact_method'] ?? null,
            'last_contacted_at' => $row['last_contacted_at'] ?? null,
            'next_follow_up_at' => $row['next_follow_up_at'] ?? null,
            'notes' => $row['notes'] ?? null,
            'is_favorite' => filter_var($row['is_favorite'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
