<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Producto
 * 
 * @property int $producto_id
 * @property string $nombre
 * @property string $descripcion
 * @property float $precio
 * @property int $cantidad
 * @property int $proveedor_id
 * @property int $categoria_id
 * @property int $periodo_id
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $actualizado_por
 * @property Carbon|null $fecha_actualizacion
 * @property string|null $bloqueado_por
 * @property Carbon|null $fecha_bloqueo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Categoria $categoria
 * @property Periodo $periodo
 * @property Usuario $usuario
 * @property Collection|DetalleCompra[] $detalle_compras
 * @property Collection|DetalleVenta[] $detalle_ventas
 *
 * @package App\Models
 */
class Producto extends Model
{
	protected $table = 'productos';
	protected $primaryKey = 'producto_id';

	protected $casts = [
		'precio' => 'float',
		'cantidad' => 'int',
		'proveedor_id' => 'int',
		'categoria_id' => 'int',
		'periodo_id' => 'int',
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'precio',
		'cantidad',
		'proveedor_id',
		'categoria_id',
		'periodo_id',
		'creado_por',
		'fecha_creacion',
		'actualizado_por',
		'fecha_actualizacion',
		'bloqueado_por',
		'fecha_bloqueo'
	];

	public function categoria()
	{
		return $this->belongsTo(Categoria::class);
	}

	public function periodo()
	{
		return $this->belongsTo(Periodo::class);
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'proveedor_id');
	}

	public function detalle_compras()
	{
		return $this->hasMany(DetalleCompra::class);
	}

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class);
	}
}
