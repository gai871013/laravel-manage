<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLogs extends Model
{
    //
    protected $table = 'operation_logs';
    protected $fillable = [
        'user_id',
        'path',
        'method',
        'ip',
        'input'
    ];
}
