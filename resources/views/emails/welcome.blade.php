Hola {{$user->name}}
Gracias por crear la cuenta. 

Verifícala usando el siguiente enlace:

{{route('verify', $user->verification_token)}}