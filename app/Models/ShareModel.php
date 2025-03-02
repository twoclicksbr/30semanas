<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ShareModel extends Model
{
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'share';

    protected $fillable = [
        'id_credential',
        'name',
        'id_gender',
        'id_person',
        'link',
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
