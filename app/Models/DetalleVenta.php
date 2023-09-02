<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetalleVenta
 * 
 * @property int $detalle_venta_id
 * @property int $cantidad
 * @property float $precio
 * @property int $compra_id
 * @property int $producto_id
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Compra $compra
 * @property Producto $producto
 *
 * @package App\Models
 */
class DetalleVenta extends Model
{
	protected $table = 'detalle_ventas';
	protected $primaryKey = 'detalle_venta_id';

	protected $casts = [
		'cantidad' => 'int',
		'precio' => 'float',
		'compra_id' => 'int',
		'producto_id' => 'int',
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'cantidad',
		'precio',
		'compra_id',
		'producto_id',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

	public function compra()
	{
		return $this->belongsTo(Compra::class);
	}

	public function producto()
	{
		return $this->belongsTo(Producto::class);
	}
}
