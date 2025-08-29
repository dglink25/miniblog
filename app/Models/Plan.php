<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {
    protected $table = 'plan';
    protected $fillable = ['name','duration_days','price','is_active'];
}
