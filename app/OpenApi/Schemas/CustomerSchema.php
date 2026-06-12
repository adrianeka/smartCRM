<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Customer',
    title: 'Customer',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'customer_code', type: 'string', example: 'CUST-001'),
        new OA\Property(property: 'full_name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', example: 'john@example.com'),
        new OA\Property(property: 'phone', type: 'string', example: '08123456789'),
        new OA\Property(property: 'company_name', type: 'string', nullable: true, example: 'PT Example'),
        new OA\Property(property: 'status', type: 'string', example: 'Lead'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class CustomerSchema {}
