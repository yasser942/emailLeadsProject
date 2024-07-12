<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailtrapSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'mailer',
        'host',
        'port',
        'encryption',
        'from_address',
        'from_name',
    ];
}
