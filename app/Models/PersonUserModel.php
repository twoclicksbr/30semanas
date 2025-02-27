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
        'email_verified_at',
        'verification_token',
        'password_reset_token',
        'last_login_at',
    ];

    protected $hidden = [
        'id_credential',
        'password',
        'verification_token',
        'password_reset_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'active' => 'integer',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    // Adicionando a relação com PersonModel
    public function person()
    {
        return $this->belongsTo(PersonModel::class, 'id_person', 'id');
    }
}