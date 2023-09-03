<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuOption extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'role_id', 'direccion'];

    public function children()
    {
        return $this->hasMany(MenuOption::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(MenuOption::class, 'parent_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
