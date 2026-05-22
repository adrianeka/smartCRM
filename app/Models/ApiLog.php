<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $method
 * @property string $endpoint
 * @property int $status_code
 * @property string|null $ip_address
 * @property array<array-key, mixed>|null $request_body
 * @property array<array-key, mixed>|null $response_body
 * @property string|null $error_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereRequestBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereResponseBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereUserId($value)
 * @mixin \Eloquent
 */
class ApiLog extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'endpoint',
        'status_code',
        'ip_address',
        'request_body',
        'response_body',
        'error_message',
    ];

    protected $casts = [
        'request_body' => 'array',
        'response_body' => 'array',
    ];
}
