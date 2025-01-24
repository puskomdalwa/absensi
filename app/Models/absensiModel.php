<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiModel extends Model
{
    use HasFactory;
    protected $table = 'absensi';
    protected $fillable = [
        'users_id',
        'tgl_absen',
        'pagi',
        'sore',
        'latitude',
        'longitude',
        'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
