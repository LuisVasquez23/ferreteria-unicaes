<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Unidad_Medida
 * 
 * @property int $unidad_medida_id
 * @property string $descripcion
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Producto[] $productos
 *
 * @package App\Models
 */

class Unidad_Medida extends Model
{
    use HasFactory;


    protected $table = 'unidades_medida';
	protected $primaryKey = 'unidad_medida_id';


    
	protected $casts = [
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'descripcion',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

    public function productos()
	{
		return $this->hasMany(Producto::class);
	}
}
