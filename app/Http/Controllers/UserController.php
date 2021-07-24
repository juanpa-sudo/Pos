<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Validando si un suario es administrado o empleado

        if (auth()->user()->rol == 'empleado') {
            $repuest = [
                'ok' => false,
                'message' => 'no estas autorizado para esta accion',
                'status' => 419
            ];

            return response()->json($repuest);
        }

        $users = User::all();
        $respuest = [
            'ok' => true,
            'usurio-login' => auth()->user(),
            'users' => $users,
            'status' => 200
        ];
        return response()->json($respuest);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    // Crea usuario
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validacion = $this->validar($request->all());
        if ($validacion['isError']) {
            # code...
            $repuest = [
                'ok' => false,
                'errors' => $validacion['error'],
                'status' => 401
            ];

            return response()->json($repuest);
        }



        if (isset($request['photo'])) {
            # code...
            $ruta_imagen = $request['photo']->store('perfil', 'public');
        } else {
            $ruta_imagen = 'perfil/no-image.jpg';
        }


        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->photo = $ruta_imagen;

        $user->save();

        $repuest = [
            'ok' => true,
            'message' => 'Usuario guardado',
            'status' => 200
        ];
        return response()->json($repuest);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        // Verifica si el usuario es empleado o administrador
        if (auth()->user()->rol == 'empleado') {
            $repuest = [
                'ok' => false,
                'message' => 'no estas autorizado para esta accion',
                'status' => 419
            ];

            return response()->json($repuest);
        }

        // Validar las reglas del formulario

        $validacion = $this->validar($request->all());

        if ($validacion['isError']) {
            # code...
            $repuest = [
                'ok' => false,
                'errors' => $validacion['error'],
                'status' => 401
            ];

            return response()->json($repuest);
        }

        $user->name = $request['name'];
        $user->rol = $request['rol'];
        if (isset($request['photo'])) {
            $ruta_imagen = $request['photo']->store('perfil', 'public');
            $user->photo = $ruta_imagen;
        }
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);

        $user->save();

        return response()->json(['ok' => true, 'message' => 'usuario editado', 'user_ediatdo' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
        if (auth()->user()->rol == 'empleado') {
            $repuest = [
                'ok' => false,
                'message' => 'no estas autorizado para esta accion',
                'status' => 419
            ];

            return response()->json($repuest);
        }

        $user->delete();
        return response()->json(['ok' => true, 'message' => 'usuario eliminado', 'user' => $user]);
    }


    // Inicio seccion del usuario
    public function login(Request $request)
    {

        # code...
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $credenciales = $request->only('email', 'password');
        $validate = Validator::make($credenciales, $rules);

        if ($validate->fails()) {
            # code...
            $repuest = ['ok' => true, 'errors' => $validate->errors(), 'status' => 401];

            return response()->json($repuest);
        }
        if (!Auth::attempt($credenciales)) {

            $repuest = [
                'ok' => false,
                'message' => 'contraseña o email incorrectos',
                'status' => 419
            ];

            return response()->json($repuest);
        }

        $token = Auth::user()->createToken('token_prueba')->accessToken;

        $repuest = [
            'ok' => true,
            'user' => Auth::user(),
            'token' => $token
        ];

        return response()->json($repuest);
    }



    // Validar las reglas del formualrio de registro y editar del usuario
    public function validar($data)
    {
        # code...
        $rules = [
            'name' => 'required|string|min:4|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|max:8',
            'photo' => 'file'
        ];


        $validator = Validator::make($data, $rules);

        return [
            'isError' => $validator->fails(),
            'error' => $validator->errors()->all()
        ];
    }
}
