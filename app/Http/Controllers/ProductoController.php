<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    //

    public function index()
    {
        # code...
        $producto = Producto::with('categoria')->get();
        return response()->json($producto);
    }

    public function store(Request $request)
    {
        # code...
        $rules = [
            'imagen' => 'required|file',
            'descripcion' => 'required|unique:productos,descripcion',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required',
            'precio_compra' => 'required',
            'precio_venta' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            # code...
            $response = [
                'ok' => false,
                'errors' => $validator->errors()->all(),
                'status' => 401
            ];
            return response()->json($response);
        }

        $ruta_imagen = $request['imagen']->store('productos', 'public');

        $producto = new Producto;
        $producto->imagen = $ruta_imagen;
        $producto->codigo = $request['codigo'];
        $producto->descripcion = $request['descripcion'];
        $producto->categoria_id = $request['categoria_id'];
        $producto->stock = $request['stock'];
        $producto->precio_compra = $request['precio_compra'];
        $producto->precio_venta = $request['precio_venta'];

        $producto->save();
        $response = [
            'ok' => true,
            'message' => 'producto guardado correctamente',
            'producto' => $producto
        ];
        return response()->json(response($response));
    }

    public function update(Request $request, Producto $producto)
    {
        # code...
        $rules = [
            'imagen' => 'file',
            'descripcion' => 'required|unique:productos,descripcion',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'required',
            'precio_compra' => 'required',
            'precio_venta' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            # code...descripcion
            $response = [
                'ok' => false,
                'message' => 'no se actualizo el producto',
                'errors' => $validator->errors()->all(),
                'status' => '401'
            ];
            return response()->json($response);
        }
        $producto->descripcion = $request['descripcion'];
        $producto->categoria_id = $request['categoria_id'];
        $producto->stock = $request['stock'];
        $producto->precio_compra = $request['precio_compra'];
        $producto->precio_venta = $request['precio_venta'];

        $producto->save();

        $response = [
            'ok' => true,
            'message' => 'Producto editado correctamente',
            'producto' => $producto
        ];
        return response()->json($response);
    }

    public function destroy(Producto $producto)
    {
        # code...
        $producto->delete();
        $response = [
            'ok' => true,
            'message' => 'producto eliminado correctamente',
            'producto' => $producto
        ];
        return response()->json($response);
    }
}
