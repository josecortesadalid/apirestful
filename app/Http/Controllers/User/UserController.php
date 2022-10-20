<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('transform.input' . UserTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-account')->only(['show', 'update']);
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');
    }
    public function index()
    {
        $this->allowedAdminAction();
        $usuarios = User::all();
        // return response()->json(['data' => $usuarios], 200);
        return $this->showAll($usuarios);
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
        // return response()->json(['data' => $usuario], 201); // 201, se realizó la operación de almacenamiento
        return $this->showOne($usuario);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) // antes teníamos puesto $id como parámetro, sin tipo. Es importante el nombre de la variable, debe ser $user
    {
        // $usuario = User::findOrFail($id); Ahora nos ahorramos esta línea, al especificarle el tipo, recibe el id y automáticamente nos devuelve la instancia

        return $this->showOne($user);
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
    public function update(Request $request, User $user)
    {
        // $user = User::findOrFail($id); // En vez de $id, hemos puesto User $user en el parámetro, nos ahorramos esta línea
        $reglas = [
            'email' => 'email|unique:users,email,'. $user->id,  // exceptuando el id del usuario actual. Si el usuario envía su propio email, no fallaría la regla
            'password' => 'min:6|confirmed', 
            'admin' => 'in:'. User::USUARIO_ADMINISTRADOR . ','. User::USUARIO_REGULAR, // que el valor de admin esté incluido en uno de estos dos posible valores
        ];
        $this->validate($request, $reglas);

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
            $this->allowedAdminAction();
            if(!$user->esVerificado()){
                // return response()->json(['error' => 'Solo los usuarios verificados pueden cambiar el valor del admin', 'code' => 409], 409); 
                return $this->errorResponse('Solo los usuarios verificados pueden cambiar el valor del admin', 409);
                
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){ // isDirty valida si alguno de los atributos originales ha cambiado con respecto al valor actual
            // return response()->json(['error' => 'Se debe introducir al menos un valor diferente para actualizar', 'code' => 422], 422); // 422 petición malformada
            return $this->errorResponse('Se debe introducir al menos un valor diferente para actualizar', 422);
        }

        $user->save();
        // return response()->json(['data' => $user], 200);
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $user = User::findOrFail($id);
        $user->delete();
        // return response()->json(['data' => $user], 200);
        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail(); // encuentra el usuario que tenga el token que recibimos

        $user->verified = User::USUARIO_VERIFICADO; // ponle el verificado
        $user->verification_token = null; // quita el token

        $user->save(); 

        return $this->showMessage('La cuenta ha sido verificada');
    }
    public function resend(User $user)
    {
        if ($user->esVerificado()) {
            return $this->errorResponse('Este usuario ya ha sido verificado', 409);
        }

        retry(5, function() use ($user)
        {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('El correo de verificación se ha reenviado');

    }
}
