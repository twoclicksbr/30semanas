<?php

namespace App\Models;

use App\Traits\FormatsDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class VideoModel extends Model
{
    use HasFactory, Notifiable, FormatsDates;

    protected $table = 'video';

    protected $fillable = [
        'id_credential',
        'name',
        'date',
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
