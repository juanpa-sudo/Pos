<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'codigo',
        'descripcion',
        'categoria_id',
        'stock',
        'precio_compra',
        'precio_venta'
    ];

    public function categoria()
    {
        # code...
        return $this->belongsTo(Categoria::class);
    }
}
