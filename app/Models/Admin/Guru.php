<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    Protected $table = 'Guru';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nip',
        'email',
        'password',
        'nama_guru',
        'foto'
    ];

    protected $hidden = [
        'password',
    ];
}
