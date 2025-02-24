<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PersonAddressModel extends Model
{
    
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'person_address';

    protected $fillable = [
        'id_credential',
        'id_person',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'localidade',
        'uf',
        'active',
    ];

    protected $hidden = [
        'id_credential',
    ];

    protected function casts(): array
    {
        return [
            // 
        ];
    }
}
