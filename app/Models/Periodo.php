<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Periodo
 * 
 * @property int $periodo_id
 * @property Carbon $fecha_inicio
 * @property Carbon $fecha_fin
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Compra[] $compras
 * @property Collection|Producto[] $productos
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Periodo extends Model
{

	use HasFactory;

	protected $table = 'periodos';
	protected $primaryKey = 'periodo_id';

	protected $casts = [
		'fecha_inicio' => 'datetime',
		'fecha_fin' => 'datetime',
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'fecha_inicio',
		'fecha_fin',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

	public function compras()
	{
		return $this->hasMany(Compra::class);
	}

	public function productos()
	{
		return $this->hasMany(Producto::class);
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class);
	}
}
