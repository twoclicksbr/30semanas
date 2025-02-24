<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PersonUserModel extends Model
{
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'person_user';

    protected $fillable = [
        'id_credential',
        'id_person',
        'email',
        'password',
        'active',
    ];

    protected $hidden = [
        'id_credential',
    ];

    protected function casts() : array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
