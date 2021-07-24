<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    //
    public function index()
    {
        # code...
        $categoria = Categoria::all();
        $response = [
            'ok' => true,
            'categoria' => $categoria
        ];
        return response()->json($response);
    }

    public function store(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), ['categoria' => 'required|unique:categorias,categoria']);

        if ($validator->fails()) {
            # code...
            $response = [
                'ok' => false,
                'errors' => $validator->errors()->all(),
                'status' => 400
            ];

            return response()->json($response);
        }

        $categoria = new Categoria;
        $categoria->categoria = $request['categoria'];

        $categoria->save();

        $response = [
            'ok' => true, 'message' => 'Categoria creada correctamente',
            'categoria' => $categoria
        ];
        return response()->json($response);
    }

    public function update(Request $request, Categoria $categoria)
    {
        # code...
        $validator = Validator::make($request->all(), ['categoria' => 'required|unique:categorias,categoria']);
        if ($validator->fails()) {
            $response = [
                'ok' => true,
                'errors' => $validator->errors()->all(),
                'status' => 400
            ];
            return response()->json($response);
        }

        $categoria->categoria = $request['categoria'];
        $categoria->save();
        $response = [
            'ok' => true,
            'message' => 'La categoria ha sido editada correctamente',
            'categoria' => $categoria
        ];
        return response()->json($response);
    }
    public function destroy(Categoria $categoria)
    {
        # code...
        $categoria->delete();

        $response = [
            'ok' => true,
            'message' => 'categoria eliminada',
            'categoria' => $categoria
        ];

        return response()->json($response);
    }
}
