<?php   
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model{
    protected $table = "clientes";

    protected $fillable = ['nombre', 'apellido', 'edad', 'nacimiento'];

    // public $timestamps = false;
}