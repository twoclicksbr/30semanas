<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CredentialModel extends Model
{
    
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'credential';

    protected $fillable = [
        'username',
        'token',
        'active',
    ];

    protected $hidden = [
        // 
    ];

    protected function casts() : array
    {
        return [
            'token' => 'hashed',
        ];
    }
}
