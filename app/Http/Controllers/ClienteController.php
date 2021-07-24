<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{

    public function index()
    {
        # code...
        $cliente = Cliente::all();

        return response()->json($cliente);
    }
    //
    public function store(Request $request)
    {
        # code...
        $validator = $this->validator($request->all());
        if ($validator['isError']) {
            # code...
            $response = [
                'ok' => false,
                'errors' => $validator['errors'],
                'status' => 401
            ];
            return response()->json($response);
        }
        $cliente = new Cliente;
        $cliente->nombre = $request['nombre'];
        $cliente->documento = $request['documento'];
        $cliente->email = $request['email'];
        $cliente->telefono = $request['telefono'];
        $cliente->year = $request['year'];

        $cliente->save();

        $response = [
            'ok' => true,
            'message' => 'cliente guardado correctamente',
            'cliente' => $cliente
        ];

        return response()->json($response);
    }

    public function update(Request $request, Cliente $cliente)
    {
        # code...
        $validator = $this->validator($request->all());
        if ($validator['isError']) {
            # code...
            $response = [
                'ok' => false,
                'errors' => $validator['errors'],
                'status' => 401
            ];
            return response()->json($response);
        }

        $cliente->nombre = $request['nombre'];
        $cliente->documento = $request['documento'];
        $cliente->email = $request['email'];
        $cliente->telefono = $request['telefono'];
        $cliente->year = $request['year'];

        $cliente->save();

        $response = [
            'ok' => true,
            'message' => 'cliente editado correctamente',
            'cliente' => $cliente
        ];

        return response()->json($response);
    }


    public function destroy(Cliente $cliente)
    {
        # code...
        $cliente->estado = false;
        $cliente->save();
        $response = [
            'ok' => true,
            'message' => 'cliente eliminado correctamente',
            'cliente' => $cliente
        ];
        return response()->json($response);
    }
    // Validar las reglas del formualrio del cliente
    public function validator($data)
    {
        # code...
        $rules = [
            'nombre' => 'required|min:8',
            'documento' => 'required|min:10',
            'email' => 'required|email',
            'telefon' => 'required|min:10',
            'year' => 'required|date'
        ];
        $validator = Validator::make($data, $rules);

        return [
            'isError' => $validator->fails(),
            'errors' => $validator->errors()->all()
        ];
    }
}
