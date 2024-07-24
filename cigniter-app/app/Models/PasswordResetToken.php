<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetToken extends Model
{
    protected $table            = 'password_reset_tokens';
    protected $DBGroup       = 'default';
    protected $allowedFields    = ['email','token'];
}
