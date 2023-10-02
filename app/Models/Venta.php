<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Venta
 * 
 * @property int $venta_id
 * @property float $monto
 * @property int $periodo_id
 * @property int $cliente_id
 * @property int $vendedor_id
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Usuario $usuario
 * @property Periodo $periodo
 *
 * @package App\Models
 */
class Venta extends Model
{

	use HasFactory;

	protected $table = 'ventas';
	protected $primaryKey = 'venta_id';

	protected $casts = [
		'monto' => 'float',
		'periodo_id' => 'int',
		'cliente_id' => 'int',
		'vendedor_id' => 'int',
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'monto',
		'numerosfactura',
		'periodo_id',
		'cliente_id',
		'vendedor_id',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'vendedor_id');
	}
	public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }
	public function detalle_ventas()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

	public function periodo()
	{
		return $this->belongsTo(Periodo::class);
	}
}
