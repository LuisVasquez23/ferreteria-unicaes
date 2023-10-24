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
 * Class Producto
 * 
 * @property int $producto_id
 * @property string $nombre
 * @property string $descripcion
 * @property float $precio
 * @property int $cantidad
 * @property int $proveedor_id
 * @property int $categoria_id
 * @property int $estante_id
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
 * @property Estante $estante
 * @property Unidad_Medida $unidades_medida
 * @property Periodo $periodo
 * @property Usuario $usuario
 * @property Collection|DetalleCompra[] $detalle_compras
 * @property Collection|DetalleVenta[] $detalle_ventas
 *
 * @package App\Models
 */
class Producto extends Model
{

	use HasFactory;

	protected $table = 'productos';
	protected $primaryKey = 'producto_id';

	protected $casts = [
		'cantidad' => 'int',
		'proveedor_id' => 'int',
		'categoria_id' => 'int',
		'estante_id' => 'int',
		'unidad_medida_id' => 'int',
		'periodo_id' => 'int',
		'fecha_creacion' => 'datetime',
		'fecha_actualizacion' => 'datetime',
		'fecha_bloqueo' => 'datetime'
	];

	protected $fillable = [
		'nombre',
		'descripcion',
		'cantidad',
		'img_path',
		'proveedor_id',
		'categoria_id',
		'estante_id',
		'unidad_medida_id',
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
		return $this->belongsTo(Categoria::class, 'categoria_id');
	}

	public function estante()
	{
		return $this->belongsTo(Estante::class, 'estante_id');
	}

	public function medida()
	{
		return $this->belongsTo(Unidad_Medida::class, 'unidad_medida_id');
	}

	public function periodo()
	{
		return $this->belongsTo(Periodo::class, 'periodo_id');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'proveedor_id');
	}

	public function detalle_compras()
	{
		return $this->hasMany(DetalleCompra::class, 'producto_id');
	}

	public function detalle_ventas()
	{
		return $this->hasMany(DetalleVenta::class, 'producto_id', 'producto_id');
	}
}
