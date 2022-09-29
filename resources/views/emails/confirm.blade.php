Hola {{$user->name}}
Ha cambiado su correo electrónico. Por favor, verifique la nueva dirección usando el siguiente enlace:

{{route('verify', $user->verification_token)}}