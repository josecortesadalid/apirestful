<?php

namespace App\Exceptions;


use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    // public function render($request, Throwable $exception) : HttpFoundationResponse | JsonResponse
    // {
        // $response = $this->handleException($request, $exception);

        // app(CorsService::class)->addActualRequestHeaders($response, $request);

        // return $response;
    // }
    // public function handleException($request, Exception $exception){
    //     if($exception instanceof ValidationException){
    //         return $this->convertValidationExceptionToResponse($exception, $request);
    //     }
    //     if($exception instanceof ModelNotFoundException){
    //         $modelo = strtolower(class_basename($exception->getModel())); // solo el nombre de la clase y en minúscula
    //         return $this->errorResponse("No existen instancias de {$modelo} con el id que has especificado", 404);
    //     }
    //     if($exception instanceof AuthenticationException){
    //         return $this->unauthenticated($request, $exception);
    //     }
    //     if($exception instanceof AuthorizationException){
    //         return $this->errorResponse('No tiene permisos para ejecutar esta acción', 403);
    //     }
    //     if($exception instanceof NotFoundHttpException){
    //         return $this->errorResponse('No se encontró la URL especificada', 404);
    //     }
    //     if($exception instanceof MethodNotAllowedHttpException){
    //         return $this->errorResponse('Método no permitido', 405);
    //     }
    //     if($exception instanceof HttpException){ // Importar la que corresponde al Symfony y no Illuminate
    //         return $this->errorResponse($exception->getMessage(), $exception->getStatusCode()); // en vez de ponerle un mensaje, obtenemos el que tenga la excepción, con el código igual
    //     }
    //     if($exception instanceof QueryException){
    //         $codigo = $exception->errorInfo[1];
    //         if ($codigo == 1451) {
    //             return $this->errorResponse('No se puede eliminar el recurso porque está relacionado con otro recurso', 409); 
    //             // le ponemos 409 debido a que es un conflicto, no podemos realizar la eliminación debido a otras problemáticas dentro de nuestro sistema
    //         }
    //     }
        // if ($exception instanceof TokenMismatchException) {
        //     return redirect()->back()->withInput($request->input());
        // }

    //     // Si estamos en modo debug, que render se ejecute para tener más información sobre el error
    //     // Si no estamos en modo debug, retornamos la respuesta de fallo simple
    //     if (config('app.debug')) { // Para acceder a este valor, utilizamos el helper de config. App es el nombre del archivo, debug es el nombre del índice
    //         return parent::render($request, $exception);
    //     }
    //     return $this->errorResponse('Fallo inesperado', 500);

    // }

    

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }
        return $this->errorResponse('No autenticado.', 401);
    }
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }
        return $this->errorResponse($errors, 422);
    }
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}
