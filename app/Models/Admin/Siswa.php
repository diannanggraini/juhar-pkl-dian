<?php

namespace App\Models\Admin;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Siswa extends Authenticatable
{
    use HasFactory;
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_pembimbing',
        'nisn',
        'password',
        'nama_siswa',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];

    public function pembimbingSiswa()
    {
        return $this->belongsTo(Pembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }

    public function kegiatan()
    {
        return $this->hasMany(kegiatan::class, 'id_siswa', 'id_siswa');
    }
}
