<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailerLite extends Model
{
    use HasFactory;

    protected $table = 'mailer_lite';
    protected $fillable = ['api_key'];
    public $timestamps = false;
}
