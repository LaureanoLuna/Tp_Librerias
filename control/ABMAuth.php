<?php
require __DIR__.'/../utiles\PHPAuth\vendor\autoload.php';
include_once('..\utiles\PHPAuth\vendor\autoload.php');
include_once('..\modelo\conector\BaseDatos.php');
$BASEDATOS = new BaseDatos();
$AUTH = new \Delight\Auth\Auth($BASEDATOS);

class ABMAuth
{
    function loguearse($datos,$auth){
        try {
            if (isset($datos['username'])) {
                $auth->loginWithUsername($datos['username'], $datos['password'], null);
				header('Location: ./indexSeguro.php');
            }
            else {
                return ['alerta'=> "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Nombre de Usuario Requerido</div>"];
            }
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Dirección de Correo Incorrecta</div>"];
        }
        catch (\Delight\Auth\UnknownUsernameException $e) {
            return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Nombre de Usuario Desconocido</div>"];
        }
        catch (\Delight\Auth\AmbiguousUsernameException $e) {
            return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Nombre de Usuario Ambiguo</div>"];
        }
		catch (\Delight\Auth\EmailNotVerifiedException $e) {
			return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Correo NO VERIFICADO</div>"];
		}
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Contraseña Incorrecta</div>"];
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-face-dizzy'></i> Demasiadas Consultas</div>"];
        }
}

function registrarse($datos,$auth){
	
        try {
            if ($datos['require_verification'] == 1) {
                $callback = function ($selector, $token) {
                    \htmlspecialchars($selector);
					\htmlspecialchars($token);
                };
            }
            else {
                $callback = null;
            }
			// RETORNA UN ARREGLO CON EL TOKEN, SELECTOR Y ALERTA (SETEADO EN NULL)
			return $auth->registerWithUniqueUsername($datos['email'], $datos['password'], $datos['username'], $callback);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Dirección de Correo Incorrecta</div>"];
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Contraseña Incorrecta</div>"];
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Correo Ya Registrado</div>"];
        }
        catch (\Delight\Auth\DuplicateUsernameException $e) {
			return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i>Nombre de Usuario Ya Registrado</div>"];
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
			return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-face-dizzy'></i> Demasiadas consultas</div>"];
        }
}

function confirmarCorreo($datos, $auth){
	try {
        $duracion = 100000;
		$auth->confirmEmailAndSignIn($datos['selector'], $datos['token'], $duracion);
		return ['alerta'=>null];
	}
	catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
		return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Token Inválido</div>"];
	}
	catch (\Delight\Auth\TokenExpiredException $e) {
		return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Token Expirado</div>"];
	}
	catch (\Delight\Auth\UserAlreadyExistsException $e) {
		return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Correo Ya Registrado</div>"];
	}
	catch (\Delight\Auth\TooManyRequestsException $e) {
		return ['alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-face-dizzy'></i> Demasiadas Consultas</div>"];
	}
}

function cerrarSesion($auth){
	$auth->logOut();
	header('Location: ./indexInicio.php');
	return 'Cerraste Sesión Exitosamente!';
}

function generarToken($datos, $auth){
	try {
		// GENERA NUEVO TOKEN Y SELECTOR 
		// RETORNA UNA ARREGLO CON EL NUEVO TOKEN, SELECTOR Y ALERTA(SETEADO EN NULL)
		return $auth->resendConfirmationForEmail($datos['email'], function ($selector, $token) {
			\htmlspecialchars($selector);
			\htmlspecialchars($token);
		});
	}
	// EXCEPCIONES
	catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
		return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-person-circle-xmark'></i> Solicitud No Encontrada</div>"];
	}
	catch (\Delight\Auth\TooManyRequestsException $e) {
		return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-face-dizzy'></i> Demasiadas consultas</div>"];
	}
}

}
