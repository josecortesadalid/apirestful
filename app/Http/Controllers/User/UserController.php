<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return response()->json(['data' => $usuarios], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users', 
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $campos = $request->all();

        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);
        return response()->json(['data' => $usuario], 201); // 201, se realizó la operación de almacenamiento
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::findOrFail($id); // la diferencia entre find y findorfail es que find solo devuelve un null, findorfail dispara la excepción y la respuesta ya sería de tipo 404
        return response()->json(['data' => $usuario], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'email' => 'email|unique:users,email,'. $user->id,  // exceptuando el id del usuario actual. Si el usuario envía su propio email, no fallaría la regla
            'password' => 'min:6|confirmed', 
            'admin' => 'in:'. User::USUARIO_ADMINISTRADOR . ','. User::USUARIO_REGULAR, // que el valor de admin esté incluido en uno de estos dos posible valores
        ];
        $this->validate($request, $rules);

        if($request->has('name')){
            $user->name = $request->name;
        }
        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }
        if($request->has('admin')){ 
            if(!$user->esVerificado()){
                return response()->json(['error' => 'Solo los usuarios verificados pueden cambiar el valor del admin', 'code' => 409], 409); 
                // 409, conflicto con la petición que ha realizado el
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){ // isDirty valida si alguno de los atributos originales ha cambiado con respecto al valor actual
            return response()->json(['error' => 'Se debe introducir al menos un valor diferente para actualizar', 'code' => 422], 422); // 422 petición malformada
        }

        $user->save();
        return response()->json(['data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['data' => $user], 200);
    }
}
