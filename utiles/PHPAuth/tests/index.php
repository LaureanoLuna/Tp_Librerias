<?php

/*
 * PHP-Auth (https://github.com/delight-im/PHP-Auth)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

/*
 * WARNING:
 *
 * Do *not* use these files from the `tests` directory as the foundation
 * for the usage of this library in your own code. Instead, please follow
 * the `README.md` file in the root directory of this project.
 */

// enable error reporting
\error_reporting(\E_ALL);
\ini_set('display_errors', 'stdout');

// enable assertions
\ini_set('assert.active', 1);
@\ini_set('zend.assertions', 1);
\ini_set('assert.exception', 1);

\header('Content-type: text/html; charset=utf-8');

// IMPORTA LA LIBRERIA
require __DIR__.'/../vendor/autoload.php';
// INSTANCIA DE LA BASE DE DATOS (PUEDE SER REEMPLAZADO POR TU PROPA CLASE BASEDEDATOS)
$db = new \PDO('mysql:dbname=login;host=127.0.0.1;charset=utf8mb4', 'root', '');
// or
// $db = new \PDO('pgsql:dbname=php_auth;host=127.0.0.1;port=5432', 'postgres', 'monkey');
// or
// $db = new \PDO('sqlite:../Databases/php_auth.sqlite');

// CONEXIÓN DE LA LIBRERIA CON LA BASE DE DATOS
$auth = new \Delight\Auth\Auth($db);

// RESULTADOS DE LA CONEXIÓN
$result = \processRequestData($auth);

\showGeneralForm();
\showDebugData($auth, $result);

// LA INSTANCIA DE AUTH VERIFICA SI HAY UNA CUENTA LOGEADA, DE SER ASI MUESTRA LOS FORMULARIOS CORRESPONDIENTES
if ($auth->check()) {
	\showAuthenticatedUserForm($auth);
}
// SINO MUESTRA LOS FORMULARIOS DE REGISTRO
else {
	\showGuestUserForm();
}

// 
function processRequestData(\Delight\Auth\Auth $auth) {
	if (isset($_POST)) {
		if (isset($_POST['action'])) {
			//################################### LOGUEO ###################################
			if ($_POST['action'] === 'login') {
				//Si el usuario eligio la opcion de ser recordado
				if ($_POST['remember'] == 1) {
					// MANTIENE LOGEADO POR UN AÑO
					$rememberDuration = (int) (60 * 60 * 24 * 365.25);
				}
				else {
					// NO MANTIENE LOGEADO
					$rememberDuration = null;
				}

				try {
					if (isset($_POST['email'])) {
						//SI EL USUARIO SE LOGEO CON EL EMAIL
						$auth->login($_POST['email'], $_POST['password'], $rememberDuration);
					}
					elseif (isset($_POST['username'])) {
						//SI EL USUARIO SE LOGEO CON EL NOMBRE DE USUARIO
						$auth->loginWithUsername($_POST['username'], $_POST['password'], $rememberDuration);
					}
					else {
						//SI NO SE ENCONTRÓ NI EL EMAIL NI EL NOMBRE DE USUARIO
						return 'either email address or username required';
					}

					return 'ok';
				}
				// EXCEPCIONES
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
			//################################### REGISTRO ###################################
			else if ($_POST['action'] === 'register') {
				try {
					//SI REQUIERE VERIFICACIÓN
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

					//SI NO SE MARCÓ NINGUNA OPCIÓN SOBRE EL NOMBRE DE USUARIO LA SETEA EN 0
					if (!isset($_POST['require_unique_username'])) {
						$_POST['require_unique_username'] = '0';
					}

					//SI NO SE MARCÓ LA OPCIÓN NOMBRE DE USUARIO ÚNICO
					if ($_POST['require_unique_username'] == 0) {
						return $auth->register($_POST['email'], $_POST['password'], $_POST['username'], $callback);
					}
					//SI SE MARCÓ LA OPCIÓN NOMBRE DE USUARIO ÚNICO
					else {
						return $auth->registerWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username'], $callback);
					}
				}
				//EXCEPCIONES
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
			//################################### CONFIRMACIÓN CORREO ###################################
			else if ($_POST['action'] === 'confirmEmail') {
				try {
					if (isset($_POST['login']) && $_POST['login'] > 0) {
						if ($_POST['login'] == 2) {
							// MANTIENE LOGEADO POR UN AÑO
							$rememberDuration = (int) (60 * 60 * 24 * 365.25);
						}
						else {
							// NO MANTIENE LOGEADO
							$rememberDuration = null;
						}
						// RETORNA TOKEN Y SELECTOR CON DURACIÓN DE LOGEO
						return $auth->confirmEmailAndSignIn($_POST['selector'], $_POST['token'], $rememberDuration);
					}
					else {
						// RETORNA SOLO TOKEN Y SELECTOR
						return $auth->confirmEmail($_POST['selector'], $_POST['token']);
					}
				}
				// EXCEPCIONES
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
			//################################### REENVIAR CONFIRMACIÓN EMAIL ###################################
			else if ($_POST['action'] === 'resendConfirmationForEmail') {
				try {
					// GENERA NUEVO TOKEN Y SELECTOR
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
				// EXCEPCIONES
				catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
					return 'no request found';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			//################################### REENVIAR CONFIRMACIÓN POR USER ID ###################################
			else if ($_POST['action'] === 'resendConfirmationForUserId') {
				try {
					// GENERA NUEVO TOKEN Y SELECTOR
					$auth->resendConfirmationForUserId($_POST['userId'], function ($selector, $token) {
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
				// EXCEPCIONES
				catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
					return 'no request found';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			//################################### OLVIDE LA CONTRASEÑA ###################################
			else if ($_POST['action'] === 'forgotPassword') {
				try {
					// GENERA NUEVO TOKEN Y SELECTOR
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
				// EXCEPCIONES
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
			//################################### RESETEAR LA CONTRASEÑA ###################################
			else if ($_POST['action'] === 'resetPassword') {
				try {
					if (isset($_POST['login']) && $_POST['login'] > 0) {
						if ($_POST['login'] == 2) {
							// MANTIENE LOGEADO POR UN AÑO
							$rememberDuration = (int) (60 * 60 * 24 * 365.25);
						}
						else {
							// NO MANTIENE LOGEADO DESPUÉS DE CERRAR DE SESIÓN
							$rememberDuration = null;
						}
						// CAMBIA LA CONTRASEÑA E INICIA SESIÓN
						return $auth->resetPasswordAndSignIn($_POST['selector'], $_POST['token'], $_POST['password'], $rememberDuration);
					}
					else {
						// CAMBIA LA CONTRASEÑA
						return $auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);
					}
				}
				// EXCEPCIONES
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
					return 'invalid token';
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
					return 'token expired';
				}
				catch (\Delight\Auth\ResetDisabledException $e) {
					return 'password reset is disabled';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			//################################### PUEDE RESETEAR LA CONTRASEÑA? ###################################
			else if ($_POST['action'] === 'canResetPassword') {
				try {
					// VERIFICA SI LOS TOKEN Y SELECTOR INGRESADOS SON VALIDOS PARA EL CAMBIO DE CONTRASEÑA
					$auth->canResetPasswordOrThrow($_POST['selector'], $_POST['token']);

					return 'yes';
				}
				// EXCEPCIONES
				catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
					return 'invalid token';
				}
				catch (\Delight\Auth\TokenExpiredException $e) {
					return 'token expired';
				}
				catch (\Delight\Auth\ResetDisabledException $e) {
					return 'password reset is disabled';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			//################################### RECONFIRMAR CONTRASEÑA ###################################
			else if ($_POST['action'] === 'reconfirmPassword') {
				try {
					// VERIFICA SI LA CONTRASEÑA INGRESA ES CORRECTA
					return $auth->reconfirmPassword($_POST['password']) ? 'correct' : 'wrong';
				}
				// EXCEPCIONES
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\TooManyRequestsException $e) {
					return 'too many requests';
				}
			}
			//################################### CAMBIAR CONTRASEÑA ###################################
			else if ($_POST['action'] === 'changePassword') {
				try {
					// CAMBIA LA ANTIGUA CONTRASEÑA POR UNA NUEVA
					$auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

					return 'ok';
				}
				// EXCEPCIONES
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
			//################################### CAMBIAR CONTRASEÑA ###################################
			else if ($_POST['action'] === 'changePasswordWithoutOldPassword') {
				try {
					// CAMBIA LA CONTRASEÑA DIRECTAMENTE SIN NECESIDAD DE INGRESAR LA ANTIGUA
					$auth->changePasswordWithoutOldPassword($_POST['newPassword']);

					return 'ok';
				}
				// EXCEPCIONES
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
				catch (\Delight\Auth\InvalidPasswordException $e) {
					return 'invalid password';
				}
			}
			//################################### CAMBIAR EMAIL ###################################
			else if ($_POST['action'] === 'changeEmail') {
				try {
					// GENERA TOKEN Y SELECTOR PARA EL CAMBIO DE EMAIL
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
				// EXCEPCIONES
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
			//################################### DESACTIVAR CAMBIO DE CONTRASEÑA  ###################################
			else if ($_POST['action'] === 'setPasswordResetEnabled') {
				try {
					// ESTA SI ESTA OPCIÓN ESTA DESACTIVADA NO SE GENERARÁN TOKENS PARA EL CAMBIO DE CONTRASEÑA
					$auth->setPasswordResetEnabled($_POST['enabled'] == 1);

					return 'ok';
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}
			}
			//################################### CERRAR SESIÓN  ###################################
			else if ($_POST['action'] === 'logOut') {
				// CIERRA SESIÓN *c va a mimir*
				$auth->logOut();

				return 'ok';
			}
			else if ($_POST['action'] === 'logOutEverywhereElse') {
				try {
					$auth->logOutEverywhereElse();
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'logOutEverywhere') {
				try {
					$auth->logOutEverywhere();
				}
				catch (\Delight\Auth\NotLoggedInException $e) {
					return 'not logged in';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'destroySession') {
				$auth->destroySession();

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.createUser') {
				try {
					if (!isset($_POST['require_unique_username'])) {
						$_POST['require_unique_username'] = '0';
					}

					if ($_POST['require_unique_username'] == 0) {
						return $auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
					}
					else {
						return $auth->admin()->createUserWithUniqueUsername($_POST['email'], $_POST['password'], $_POST['username']);
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
			}
			else if ($_POST['action'] === 'admin.deleteUser') {
				if (isset($_POST['id'])) {
					try {
						$auth->admin()->deleteUserById($_POST['id']);
					}
					catch (\Delight\Auth\UnknownIdException $e) {
						return 'unknown ID';
					}
				}
				elseif (isset($_POST['email'])) {
					try {
						$auth->admin()->deleteUserByEmail($_POST['email']);
					}
					catch (\Delight\Auth\InvalidEmailException $e) {
						return 'unknown email address';
					}
				}
				elseif (isset($_POST['username'])) {
					try {
						$auth->admin()->deleteUserByUsername($_POST['username']);
					}
					catch (\Delight\Auth\UnknownUsernameException $e) {
						return 'unknown username';
					}
					catch (\Delight\Auth\AmbiguousUsernameException $e) {
						return 'ambiguous username';
					}
				}
				else {
					return 'either ID, email address or username required';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.addRole') {
				if (isset($_POST['role'])) {
					if (isset($_POST['id'])) {
						try {
							$auth->admin()->addRoleForUserById($_POST['id'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
					}
					elseif (isset($_POST['email'])) {
						try {
							$auth->admin()->addRoleForUserByEmail($_POST['email'], $_POST['role']);
						}
						catch (\Delight\Auth\InvalidEmailException $e) {
							return 'unknown email address';
						}
					}
					elseif (isset($_POST['username'])) {
						try {
							$auth->admin()->addRoleForUserByUsername($_POST['username'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownUsernameException $e) {
							return 'unknown username';
						}
						catch (\Delight\Auth\AmbiguousUsernameException $e) {
							return 'ambiguous username';
						}
					}
					else {
						return 'either ID, email address or username required';
					}
				}
				else {
					return 'role required';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.removeRole') {
				if (isset($_POST['role'])) {
					if (isset($_POST['id'])) {
						try {
							$auth->admin()->removeRoleForUserById($_POST['id'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
					}
					elseif (isset($_POST['email'])) {
						try {
							$auth->admin()->removeRoleForUserByEmail($_POST['email'], $_POST['role']);
						}
						catch (\Delight\Auth\InvalidEmailException $e) {
							return 'unknown email address';
						}
					}
					elseif (isset($_POST['username'])) {
						try {
							$auth->admin()->removeRoleForUserByUsername($_POST['username'], $_POST['role']);
						}
						catch (\Delight\Auth\UnknownUsernameException $e) {
							return 'unknown username';
						}
						catch (\Delight\Auth\AmbiguousUsernameException $e) {
							return 'ambiguous username';
						}
					}
					else {
						return 'either ID, email address or username required';
					}
				}
				else {
					return 'role required';
				}

				return 'ok';
			}
			else if ($_POST['action'] === 'admin.hasRole') {
				if (isset($_POST['id'])) {
					if (isset($_POST['role'])) {
						try {
							return $auth->admin()->doesUserHaveRole($_POST['id'], $_POST['role']) ? 'yes' : 'no';
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
					}
					else {
						return 'role required';
					}
				}
				else {
					return 'ID required';
				}
			}
			else if ($_POST['action'] === 'admin.getRoles') {
				if (isset($_POST['id'])) {
					try {
						return $auth->admin()->getRolesForUserById($_POST['id']);
					}
					catch (\Delight\Auth\UnknownIdException $e) {
						return 'unknown ID';
					}
				}
				else {
					return 'ID required';
				}
			}
			else if ($_POST['action'] === 'admin.logInAsUserById') {
				if (isset($_POST['id'])) {
					try {
						$auth->admin()->logInAsUserById($_POST['id']);

						return 'ok';
					}
					catch (\Delight\Auth\UnknownIdException $e) {
						return 'unknown ID';
					}
					catch (\Delight\Auth\EmailNotVerifiedException $e) {
						return 'email address not verified';
					}
				}
				else {
					return 'ID required';
				}
			}
			else if ($_POST['action'] === 'admin.logInAsUserByEmail') {
				if (isset($_POST['email'])) {
					try {
						$auth->admin()->logInAsUserByEmail($_POST['email']);

						return 'ok';
					}
					catch (\Delight\Auth\InvalidEmailException $e) {
						return 'unknown email address';
					}
					catch (\Delight\Auth\EmailNotVerifiedException $e) {
						return 'email address not verified';
					}
				}
				else {
					return 'Email address required';
				}
			}
			else if ($_POST['action'] === 'admin.logInAsUserByUsername') {
				if (isset($_POST['username'])) {
					try {
						$auth->admin()->logInAsUserByUsername($_POST['username']);

						return 'ok';
					}
					catch (\Delight\Auth\UnknownUsernameException $e) {
						return 'unknown username';
					}
					catch (\Delight\Auth\AmbiguousUsernameException $e) {
						return 'ambiguous username';
					}
					catch (\Delight\Auth\EmailNotVerifiedException $e) {
						return 'email address not verified';
					}
				}
				else {
					return 'Username required';
				}
			}
			else if ($_POST['action'] === 'admin.changePasswordForUser') {
				if (isset($_POST['newPassword'])) {
					if (isset($_POST['id'])) {
						try {
							$auth->admin()->changePasswordForUserById($_POST['id'], $_POST['newPassword']);
						}
						catch (\Delight\Auth\UnknownIdException $e) {
							return 'unknown ID';
						}
						catch (\Delight\Auth\InvalidPasswordException $e) {
							return 'invalid password';
						}
					}
					elseif (isset($_POST['username'])) {
						try {
							$auth->admin()->changePasswordForUserByUsername($_POST['username'], $_POST['newPassword']);
						}
						catch (\Delight\Auth\UnknownUsernameException $e) {
							return 'unknown username';
						}
						catch (\Delight\Auth\AmbiguousUsernameException $e) {
							return 'ambiguous username';
						}
						catch (\Delight\Auth\InvalidPasswordException $e) {
							return 'invalid password';
						}
					}
					else {
						return 'either ID or username required';
					}
				}
				else {
					return 'new password required';
				}

				return 'ok';
			}
			else {
				throw new Exception('Unexpected action: ' . $_POST['action']);
			}
		}
	}

	return null;
}

// MUESTRA INFORMACIÓN SOBRE LAS CONSULTAS GENERADAS EN LOS FORMULARIOS
function showDebugData(\Delight\Auth\Auth $auth, $result) {
	echo '<pre>';

	// RESULTADO DE LA ÚLTIMA OPERACIÓN
	echo 'Last operation' . "\t\t\t\t";
	\var_dump($result);
	// ID DE LA SESIÓN
	echo 'Session ID' . "\t\t\t\t";
	\var_dump(\session_id());
	echo "\n";

	// SI ALGUIEN ESTA LOGEADO
	echo '$auth->isLoggedIn()' . "\t\t\t";
	\var_dump($auth->isLoggedIn());
	// RESULTADO DEL CHECKEO DE LOGEO
	echo '$auth->check()' . "\t\t\t\t";
	\var_dump($auth->check());
	echo "\n";

	// ID DEL USUARIO
	echo '$auth->getUserId()' . "\t\t\t";
	\var_dump($auth->getUserId());
	echo '$auth->id()' . "\t\t\t\t";
	\var_dump($auth->id());
	echo "\n";

	// EMAIL DEL USUARIO LOGEADO
	echo '$auth->getEmail()' . "\t\t\t";
	\var_dump($auth->getEmail());
	// NOMBRE DE USUARIO DEL LOGEO
	echo '$auth->getUsername()' . "\t\t\t";
	\var_dump($auth->getUsername());

	echo '$auth->getStatus()' . "\t\t\t";
	echo \convertStatusToText($auth);
	echo ' / ';
	\var_dump($auth->getStatus());

	echo "\n";

	echo 'Roles (super moderator)' . "\t\t\t";
	\var_dump($auth->hasRole(\Delight\Auth\Role::SUPER_MODERATOR));

	echo 'Roles (developer *or* manager)' . "\t\t";
	\var_dump($auth->hasAnyRole(\Delight\Auth\Role::DEVELOPER, \Delight\Auth\Role::MANAGER));

	echo 'Roles (developer *and* manager)' . "\t\t";
	\var_dump($auth->hasAllRoles(\Delight\Auth\Role::DEVELOPER, \Delight\Auth\Role::MANAGER));

	echo 'Roles' . "\t\t\t\t\t";
	echo \json_encode($auth->getRoles()) . "\n";

	echo "\n";

	echo '$auth->isRemembered()' . "\t\t\t";
	\var_dump($auth->isRemembered());
	echo '$auth->getIpAddress()' . "\t\t\t";
	\var_dump($auth->getIpAddress());
	echo "\n";

	echo 'Session name' . "\t\t\t\t";
	\var_dump(\session_name());
	echo 'Auth::createRememberCookieName()' . "\t";
	\var_dump(\Delight\Auth\Auth::createRememberCookieName());
	echo "\n";

	echo 'Auth::createCookieName(\'session\')' . "\t";
	\var_dump(\Delight\Auth\Auth::createCookieName('session'));
	echo 'Auth::createRandomString()' . "\t\t";
	\var_dump(\Delight\Auth\Auth::createRandomString());
	echo 'Auth::createUuid()' . "\t\t\t";
	\var_dump(\Delight\Auth\Auth::createUuid());

	echo '</pre>';
}

function convertStatusToText(\Delight\Auth\Auth $auth) {
	if ($auth->isLoggedIn() === true) {
		if ($auth->getStatus() === \Delight\Auth\Status::NORMAL && $auth->isNormal()) {
			return 'normal';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::ARCHIVED && $auth->isArchived()) {
			return 'archived';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::BANNED && $auth->isBanned()) {
			return 'banned';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::LOCKED && $auth->isLocked()) {
			return 'locked';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::PENDING_REVIEW && $auth->isPendingReview()) {
			return 'pending review';
		}
		elseif ($auth->getStatus() === \Delight\Auth\Status::SUSPENDED && $auth->isSuspended()) {
			return 'suspended';
		}
	}
	elseif ($auth->isLoggedIn() === false) {
		if ($auth->getStatus() === null) {
			return 'none';
		}
	}

	throw new Exception('Invalid status `' . $auth->getStatus() . '`');
}

// FORMULARIO QUE RECARGA EL SITIO Y ACTUALIZA LAS CONSULTAS
function showGeneralForm() {
	echo '<form action="" method="get" accept-charset="utf-8">';
	echo '<button type="submit">Refresh</button>';
	echo '</form>';
}

//FORMULARIO SI EL USUARIO ESTÁ LOGUEADO
function showAuthenticatedUserForm(\Delight\Auth\Auth $auth) {
	// CONFIRMAR CONTRASEÑA
	// SOLO INGRESAS LA CONTRASEÑA Y SI ES CORRECTA TE DIRÁ "OK"
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="reconfirmPassword" />';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<button type="submit">Reconfirm password</button>';
	echo '</form>';

	// CAMBIAS TU ANTIGUA CONTRASEÑA POR UNA NUEVA, SIENDO NECESARIO INGRESAR LA PRIMERA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changePassword" />';
	echo '<input type="text" name="oldPassword" placeholder="Old password" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password</button>';
	echo '</form>';

	// CAMBIAS TU ANTIGUA CONTRASEÑA POR UNA NUEVA, SOLO TIENES QUE INGRESAR LA NUEVA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changePasswordWithoutOldPassword" />';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password without old password</button>';
	echo '</form>';

	// CAMBIAS EL CORREO POR UNO NUEVO, SOLO TIENES QUE INGRESAR EL NUEVO
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="changeEmail" />';
	echo '<input type="text" name="newEmail" placeholder="New email address" /> ';
	echo '<button type="submit">Change email address</button>';
	echo '</form>';

	// MUESTRA FORMULARIOS PARA CONFIRMAR CORREOS
	\showConfirmEmailForm();

	// ACTIVA O DESACTIVA LA OPCIÓN DE CAMBIAR CONTRASEÑA FUERA DEL LOGEO
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="setPasswordResetEnabled" />';
	echo '<select name="enabled" size="1">';
	echo '<option value="0"' . ($auth->isPasswordResetEnabled() ? '' : ' selected="selected"') . '>Disabled</option>';
	echo '<option value="1"' . ($auth->isPasswordResetEnabled() ? ' selected="selected"' : '') . '>Enabled</option>';
	echo '</select> ';
	echo '<button type="submit">Control password resets</button>';
	echo '</form>';

	// CERRAR SESIÓN
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOut" />';
	echo '<button type="submit">Log out</button>';
	echo '</form>';

	// CERRAR SESIÓN 2
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOutEverywhereElse" />';
	echo '<button type="submit">Log out everywhere else</button>';
	echo '</form>';

	// CERRAR SESIÓN 3 MAS CERRADO QUE NUNCA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="logOutEverywhere" />';
	echo '<button type="submit">Log out everywhere</button>';
	echo '</form>';

	// FORMULARIO PARA DESTRUIR SESIÓN
	\showDestroySessionForm();
}

// FORMULARIO SI NADIE SE LOGUEO AÚN
function showGuestUserForm() {
	echo '<h1>Public</h1>';

	// LOGEO CON CORREO Y CONTRASEÑA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="login" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<select name="remember" size="1">';
	echo '<option value="0">Remember (keep logged in)? — No</option>';
	echo '<option value="1">Remember (keep logged in)? — Yes</option>';
	echo '</select> ';
	echo '<button type="submit">Log in with email address</button>';
	echo '</form>';

	// LOGEO CON NOMBRE DE USUARIO Y CONTRASEÑA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="login" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<select name="remember" size="1">';
	echo '<option value="0">Remember (keep logged in)? — No</option>';
	echo '<option value="1">Remember (keep logged in)? — Yes</option>';
	echo '</select> ';
	echo '<button type="submit">Log in with username</button>';
	echo '</form>';

	// REGISTRO
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="register" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<input type="text" name="username" placeholder="Username (optional)" /> ';
	echo '<select name="require_verification" size="1">';
	echo '<option value="0">Require email confirmation? — No</option>';
	echo '<option value="1">Require email confirmation? — Yes</option>';
	echo '</select> ';
	echo '<select name="require_unique_username" size="1">';
	echo '<option value="0">Username — Any</option>';
	echo '<option value="1">Username — Unique</option>';
	echo '</select> ';
	echo '<button type="submit">Register</button>';
	echo '</form>';

	// FORMULARIOS PARA CONFIRMAR CORREO
	\showConfirmEmailForm();

	// OLVIDÉ LA CONTRASEÑA - ESTO SE ACTIVA O DESACTIVA EN EL LOGEO
	// GENERA NUEVO TOKEN Y SELECTOR
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="forgotPassword" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Forgot password</button>';
	echo '</form>';

	// INGRESAS TOKEN Y SELECTOR SEGUIDO DE LA NUEVA CONTRASEÑA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="resetPassword" />';
	echo '<input type="text" name="selector" placeholder="Selector" /> ';
	echo '<input type="text" name="token" placeholder="Token" /> ';
	echo '<input type="text" name="password" placeholder="New password" /> ';
	echo '<select name="login" size="1">';
	echo '<option value="0">Sign in automatically? — No</option>';
	echo '<option value="1">Sign in automatically? — Yes</option>';
	echo '<option value="2">Sign in automatically? — Yes (and remember)</option>';
	echo '</select> ';
	echo '<button type="submit">Reset password</button>';
	echo '</form>';

	// VERIFICA SI LOS TOKEN Y SELECTOR INGRESADOS SON PARA CAMBIO DE CONTRASEÑA
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="canResetPassword" />';
	echo '<input type="text" name="selector" placeholder="Selector" /> ';
	echo '<input type="text" name="token" placeholder="Token" /> ';
	echo '<button type="submit">Can reset password?</button>';
	echo '</form>';

	// FORMULARIO DE DESTRUIR SESIÓN
	\showDestroySessionForm();

	// FORMULARIOS DE ADMIN
	echo '<h1>Administration</h1>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.createUser" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<input type="text" name="password" placeholder="Password" /> ';
	echo '<input type="text" name="username" placeholder="Username (optional)" /> ';
	echo '<select name="require_unique_username" size="1">';
	echo '<option value="0">Username — Any</option>';
	echo '<option value="1">Username — Unique</option>';
	echo '</select> ';
	echo '<button type="submit">Create user</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.deleteUser" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<button type="submit">Delete user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.deleteUser" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Delete user by email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.deleteUser" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<button type="submit">Delete user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.addRole" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Add role for user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.addRole" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Add role for user by email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.addRole" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Add role for user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.removeRole" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Remove role for user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.removeRole" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Remove role for user by email</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.removeRole" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Remove role for user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.hasRole" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<select name="role">' . \createRolesOptions() . '</select>';
	echo '<button type="submit">Does user have role?</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.getRoles" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<button type="submit">Get user\'s roles</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.logInAsUserById" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<button type="submit">Log in as user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.logInAsUserByEmail" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Log in as user by email address</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.logInAsUserByUsername" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<button type="submit">Log in as user by username</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.changePasswordForUser" />';
	echo '<input type="text" name="id" placeholder="ID" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password for user by ID</button>';
	echo '</form>';

	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="admin.changePasswordForUser" />';
	echo '<input type="text" name="username" placeholder="Username" /> ';
	echo '<input type="text" name="newPassword" placeholder="New password" /> ';
	echo '<button type="submit">Change password for user by username</button>';
	echo '</form>';
}

// FORMULARIO PARA CONFIRMACIÓN DE EMAIL
function showConfirmEmailForm() {
	// CUANDO TE REGISTRAS SE GENERA UN SELECTOR Y TOKEN, DEBES INGRESARLOS PARA VERIFICAR EL CORREO
	// LOS TOKENS Y SELECTORES SE GENERAN PARA ESE EMAIL EN PARTICULAR
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="confirmEmail" />';
	echo '<input type="text" name="selector" placeholder="Selector" /> ';
	echo '<input type="text" name="token" placeholder="Token" /> ';
	echo '<select name="login" size="1">';
	echo '<option value="0">Sign in automatically? — No</option>';
	echo '<option value="1">Sign in automatically? — Yes</option>';
	echo '<option value="2">Sign in automatically? — Yes (and remember)</option>';
	echo '</select> ';
	echo '<button type="submit">Confirm email</button>';
	echo '</form>';

	// SOLICITA OTRO TOKEN Y SELECTOR PARA EL CORREO INGRESADO
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="resendConfirmationForEmail" />';
	echo '<input type="text" name="email" placeholder="Email address" /> ';
	echo '<button type="submit">Re-send confirmation</button>';
	echo '</form>';

	// SOLICITA OTRO TOKEN Y SELECTOR PARA EL CORREO INGRESADO
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="resendConfirmationForUserId" />';
	echo '<input type="text" name="userId" placeholder="User ID" /> ';
	echo '<button type="submit">Re-send confirmation</button>';
	echo '</form>';

	// PUEDES SOLICITAR UNA VEZ MÁS EL TOKEN Y SELECTOR APARTE DE LOS GENERADOS UNA VEZ TERMINADO EL REGISTRO
	// PUEDES USAR EL TOKEN Y SELECTOR DE CUALQUIER RONDA (LOS GENERADOS EN EL REGISTRO O LOS GENERADOS EN LA SOLICITUD)
}

// FORMULARIO PARA DESTRUIR LA SESIÓN
function showDestroySessionForm() {
	echo '<form action="" method="post" accept-charset="utf-8">';
	echo '<input type="hidden" name="action" value="destroySession" />';
	echo '<button type="submit">Destroy session</button>';
	echo '</form>';
}

function createRolesOptions() {
	$out = '';

	foreach (\Delight\Auth\Role::getMap() as $roleValue => $roleName) {
		$out .= '<option value="' . $roleValue . '">' . $roleName . '</option>';
	}

	return $out;
}























// ⠄⠄⠄⠄⠄⠄⠄⠄⠄⢀⣤⣶⣿⣿⣿⣿⣿⣿⣿⣶⣄⠄⠄⠄⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⠄⠄⠄⢀⣴⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣧⠄⠄⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⠄⠄⢀⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣧⠄⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⠄⣴⡿⠛⠉⠁⠄⠄⠄⠄⠈⢻⣿⣿⣿⣿⣿⣿⣿⠄⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⢸⣿⡅⠄⠄⠄⠄⠄⠄⠄⣠⣾⣿⣿⣿⣿⣿⣿⣿⣷⣶⣶⣦⠄⠄⠄
// ⠄⠄⠄⠄⠸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣇⠄⠄
// ⠄⠄⠄⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄
// ⠄⠄⠄⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄
// ⠄⠄⠄⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄
// ⠄⠄⠄⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄
// ⠄⠄⠄⠄⠄⠘⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄
// ⠄⠄⠄⠄⠄⠄⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡟⠛⠛⠛⠃⠄⠄
// ⠄⠄⠄⠄⠄⣼⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⢰⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡇⠄⠄⠄⠄⠄
// ⠄⠄⠄⢀⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄⠄⠄⠄
// ⠄⠄⠄⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⢻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡆⠄⠄⠄⠄
// ⠄⠄⢠⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠃⠄⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠇⠄⠄⠄⠄
// ⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⡿⠟⠁⠄⠄⠄⠻⣿⣿⣿⣿⣿⣿⣿⡿⠄⠄⠄⠄⠄
// ⠄⠄⢸⣿⣿⣿⣿⣿⡿⠋⠄⠄⠄⠄⠄⠄⠄⠙⣿⣿⣿⣿⣿⣿⡇⠄⠄⠄⠄⠄
// ⠄⠄⢸⣿⣿⣿⣿⣿⣧⡀⠄⠄⠄⠄⠄⠄⠄⢀⣾⣿⣿⣿⣿⣿⡇⠄⠄⠄⠄⠄
// ⠄⠄⢸⣿⣿⣿⣿⣿⣿⣿⡄⠄⠄⠄⠄⠄⠄⣿⣿⣿⣿⣿⣿⣿⣷⠄⠄⠄⠄⠄
// ⠄⠄⠸⣿⣿⣿⣿⣿⣿⣿⣷⠄⠄⠄⠄⠄⢰⣿⣿⣿⣿⣿⣿⣿⣿⠄⠄⠄⠄⠄
// ⠄⠄⠄⢿⣿⣿⣿⣿⣿⣿⡟⠄⠄⠄⠄⠄⠸⣿⣿⣿⣿⣿⣿⣿⠏⠄⠄⠄⠄⠄
// ⠄⠄⠄⠈⢿⣿⣿⣿⣿⠏⠄⠄⠄⠄⠄⠄⠄⠙⣿⣿⣿⣿⣿⠏⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⠘⣿⣿⣿⣿⡇⠄⠄⠄⠄⠄⠄⠄⠄⣿⣿⣿⣿⡏⠄⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⠄⢸⣿⣿⣿⣧⠄⠄⠄⠄⠄⠄⠄⢀⣿⣿⣿⣿⡇⠄⠄⠄⠄⠄⠄⠄
// ⠄⠄⠄⠄⠄⣸⣿⣿⣿⣿⣆⠄⠄⠄⠄⠄⢀⣾⣿⣿⣿⣿⣿⣄⠄⠄⠄⠄⠄⠄
// ⠄⣀⣀⣤⣾⣿⣿⣿⣿⡿⠟⠄⠄⠄⠄⠄⠸⣿⣿⣿⣿⣿⣿⣿⣷⣄⣀⠄⠄⠄
// ⠸⠿⠿⠿⠿⠿⠿⠟⠁⠄⠄⠄⠄⠄⠄⠄⠄⠄⠉⠉⠛⠿⢿⡿⠿⠿⠿⠃⠄⠄