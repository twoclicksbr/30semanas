<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class GenderModel extends Model
{
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'gender';

    protected $fillable = [
        'id_credential',
        'name',
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
