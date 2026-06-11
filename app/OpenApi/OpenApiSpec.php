<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Smart CRM API',
    description: 'Backend Connector API Documentation'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Local Development Server'
)]
#[OA\PathItem(path: '/api')]

#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]

class OpenApiSpec {}
