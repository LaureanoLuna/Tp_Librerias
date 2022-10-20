<?php
require __DIR__.'/../utiles\PHPAuth\vendor\autoload.php';
require_once('funciones.php');


include_once('..\utiles\PHPAuth\vendor\autoload.php');
include_once('..\modelo\conector\BaseDatos.php');
$BASEDATOS = new BaseDatos();
$AUTH = new \Delight\Auth\Auth($BASEDATOS);


function loguearse($datos,$auth){

        try {
            if (isset($datos['username'])) {
                $auth->loginWithUsername($datos['username'], $datos['password'], null);
				header('Location: ./indexSeguro.php');
            }
            else {
                return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Nombre de Usuario Requerido</div>";
            }

            return 'ok';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Dirección de Correo Incorrecta</div>";
        }
        catch (\Delight\Auth\UnknownUsernameException $e) {
            return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Nombre de Usuario Desconocido</div>";
        }
        catch (\Delight\Auth\AmbiguousUsernameException $e) {
            return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Nombre de Usuario Ambiguo</div>";
        }
		catch (\Delight\Auth\EmailNotVerifiedException $e) {
			return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Correo NO VERIFICADO</div>";
		}
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Contraseña Incorrecta</div>";
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Demasiadas Consultas</div>";
        }
}

function registrarse($datos,$auth){
	
        try {
            if ($datos['require_verification'] == 1) {
                $callback = function ($selector, $token) {
                    echo '<pre>';
                    echo 'Email confirmation';
                    echo "\n";
                    echo '  >  Selector';
                    echo "\t\t\t\t";
                    echo \htmlspecialchars($selector);
                    echo "\n";
                    echo '  >  Token';
                    echo "\t\t\t\t";
                    echo \htmlspecialchars($token);
                    echo '</pre>';
                };
            }
            else {
                $callback = null;
            }
			// RETORNA UN ARREGLO CON EL TOKEN, SELECTOR Y ALERTA (SETEADO EN NULL)
			return $auth->registerWithUniqueUsername($datos['email'], $datos['password'], $datos['username'], $callback);
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Dirección de Correo Incorrecta</div>"];
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Contraseña Incorrecta</div>"];
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Correo Ya Registrado</div>"];
        }
        catch (\Delight\Auth\DuplicateUsernameException $e) {
			return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Nombre de Usuario Ya Registrado</div>"];
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
			return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Demasiadas consultas</div>"];
        }
}

function confirmarCorreo($datos, $auth){
	try {
		$auth->confirmEmail($datos['selector'], $datos['token']);
		return "<div class='alert alert-success' role='alert'><i class='fa-solid fa-xmark'></i> Correo Verificado!</div>";
	}
	catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
		return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Token Inválido</div>";
	}
	catch (\Delight\Auth\TokenExpiredException $e) {
		return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Token Expirado</div>";
	}
	catch (\Delight\Auth\UserAlreadyExistsException $e) {
		return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Correo Ya Registrado</div>";
	}
	catch (\Delight\Auth\TooManyRequestsException $e) {
		return "<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Demasiadas Consultas</div>";
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
			echo '<pre>';
			echo 'Email confirmation';
			echo "\n";
			echo '  >  Selector';
			echo "\t\t\t\t";
			echo \htmlspecialchars($selector);
			echo "\n";
			echo '  >  Token';
			echo "\t\t\t\t";
			echo \htmlspecialchars($token);
			echo '</pre>';
		});
	}
	// EXCEPCIONES
	catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
		return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Solicitud No Encontrada</div>"];
	}
	catch (\Delight\Auth\TooManyRequestsException $e) {
		return ['id'=>null, 'selector'=>null, 'token'=>null, 'alerta'=>"<div class='alert alert-danger' role='alert'><i class='fa-solid fa-xmark'></i> Demasiadas consultas</div>"];
	}
}

/*
function processRequestData($auth) {
	if (isset($_POST)) {
		if (isset($_POST['action'])) {
			if ($_POST['action'] === 'login') {
				if ($_POST['remember'] == 1) {
					// keep logged in for one year
					$rememberDuration = (int) (60 * 60 * 24 * 365.25);
				}
				else {
					// do not keep logged in after session ends
					$rememberDuration = null;
				}

				try {
					if (isset($_POST['email'])) {
						$auth->login($_POST['email'], $_POST['password'], $rememberDuration);
					}
					elseif (isset($_POST['username'])) {
						$auth->loginWithUsername($_POST['username'], $_POST['password'], $rememberDuration);
					}
					else {
						return 'either email address or username required';
					}

					return 'ok';
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'wrong email address';
				}
				catch (\Delight\Auth\UnknownUsernameException $e) {
					return 'unknown username';
				}
				catch (\Delight\Auth\AmbiguousUsernameException $e) {
					return 'ambiguous username';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'wrong password';
				}
				catch (\Delight\Auth\EmailNotVerifiedException $e) {
					return 'email address not verified';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'register') {
				try {
					if ($_POST['require_verification'] == 1) {
						$callback = function ($selector, $token) {
							echo '<pre>';
							echo 'Email confirmation';
							echo "\n";
							echo '  >  Selector';
							echo "\t\t\t\t";
							echo \htmlspecialchars($selector);
							echo "\n";
							echo '  >  Token';
							echo "\t\t\t\t";
							echo \htmlspecialchars($token);
							echo '</pre>';
						};
					}
					else {
						$callback = null;
					}

					if (!isset($_POST['require_unique_username'])) {
						$_POST['require_unique_username'] = '0';
					}

					if ($_POST['require_unique_username'] == 0) {
						return $auth->register($_POST['email'], $_POST['password'], $_POST['username'], $callback);
					}
					else {
						return $auth->registerWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username'], $callback);
					}
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'email address already exists';
				}
				catch (\Delight\Auth\DuplicateUsernameException $e) {
					return 'username already exists';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'confirmEmail') {
				try {
					if (isset($_POST['login']) && $_POST['login'] > 0) {
						if ($_POST['login'] == 2) {
							// keep logged in for one year
							$rememberDuration = (int) (60 * 60 * 24 * 365.25);
						}
						else {
							// do not keep logged in after session ends
							$rememberDuration = null;
						}

						return $auth->confirmEmailAndSignIn($_POST['selector'], $_POST['token'], $rememberDuration);
					}
					else {
						return $auth->confirmEmail($_POST['selector'], $_POST['token']);
					}
				}
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
					return 'invalid token';
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
					return 'token expired';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'email address already exists';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'resendConfirmationForEmail') {
				try {
					$auth->resendConfirmationForEmail($_POST['email'], function ($selector, $token) {
						echo '<pre>';
						echo 'Email confirmation';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
					return 'no request found';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'forgotPassword') {
				try {
					$auth->forgotPassword($_POST['email'], function ($selector, $token) {
						echo '<pre>';
						echo 'Password reset';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\EmailNotVerifiedException $e) {
					return 'email address not verified';
				}
				catch (\Delight\Auth\ResetDisabledException $e) {
					return 'password reset is disabled';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'changePassword') {
				try {
					$auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

					return 'ok';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password(s)';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'changeEmail') {
				try {
					$auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
						echo '<pre>';
						echo 'Email confirmation';
						echo "\n";
						echo '  >  Selector';
						echo "\t\t\t\t";
						echo \htmlspecialchars($selector);
						echo "\n";
						echo '  >  Token';
						echo "\t\t\t\t";
						echo \htmlspecialchars($token);
						echo '</pre>';
					});

					return 'ok';
				}
				catch (\Delight\Auth\InvalidEmailException $e) {
					return 'invalid email address';
				}
				catch (\Delight\Auth\UserAlreadyExistsException $e) {
					return 'email address already exists';
				}
				catch (\Delight\Auth\EmailNotVerifiedException $e) {
					return 'account not verified';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			else if ($_POST['action'] === 'logOut') {
				$auth->logOut();

				return 'ok';
			}
			else if ($_POST['action'] === 'destroySession') {
				$auth->destroySession();

				return 'ok';
			}else {
				throw new Exception('Unexpected action: ' . $_POST['action']);
			}
		}
	}
	return null;
}
*/
