<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PersonModel extends Model
{
    
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'person';

    protected $fillable = [
        'id_credential',
        'name',
        'id_gender',
        'cpf',
        'dt_nascimento',
        'whatsapp',
        'email',
        'eklesia',
        'active',
    ];

    protected $hidden = [
        'id_credential',
    ];

    protected function casts(): array
    {
        return [
            'dt_nascimento' => 'date', // Converte dt_nascimento para o tipo date
        ];
    }
}
