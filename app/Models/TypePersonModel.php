<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TypePersonModel extends Model
{
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'type_person';

    protected $fillable = [
        'name',
        'active',
        'id_credential',
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
