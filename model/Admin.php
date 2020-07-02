<?php
// This is a model class for the user representation.
// It contains methods for handling
// authorization and checking whether user is logged in.

// Require DBConnection class.
require_once "DBConnection.php";

class Admin
{
	private $login;
	private $password;

	public function set_login($login) {
		$this->login = $login;
	}

	public function set_password($password) {
		$this->password = md5( md5($password) );
	}

	// Method handles authorization process.
	// If login and password exist in db, a user is
	// authorized with coockies. If not, error returned.
	public function login() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		$error = array();

		$query = "SELECT * FROM auth WHERE ALogin = :ALogin";
		$user = $pdo->prepare($query);

		$user->execute(['ALogin' => $this->login]);

		if( $user = $user->fetch() ) {

			if( $user['APassword'] === $this->password ) {
				setcookie('login', $this->login, time() + 50000, '/');
				setcookie('pass', $this->password, time() + 50000, '/');

				ini_set ("session.use_trans_sid", true);
				session_start();
				$_SESSION['id'] = $user['AId'];

				return $error;
			} else {
				$error[] = "Неверный пароль!";
				return $error;
			}

		} else {
			$error[] = "Неверный логин и пароль!";
			return $error;
		}
	}

	// Method checks if a user is already authorized.
	// If user is authorized, it updates cookies living
	// time.
	public function is_logged_in() {
		$db = new DBConnection();
		$pdo = $db->pdo;

		if(isset($_COOKIE['login']) && isset($_COOKIE['pass'])) {

			$query = "SELECT * FROM auth WHERE ALogin = :ALogin";
			$user = $pdo->prepare($query);

			$user->execute(['ALogin' => $_COOKIE['login']]);

			if($user = $user->fetch()) {

				if($user['APassword'] === $_COOKIE['pass'] ) {
					setcookie('login', $_COOKIE['login'], time() + 50000, '/');
					setcookie('pass', $_COOKIE['pass'], time() + 50000, '/');

					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}

		}
	}

	// Method logs a user out.
	public function logout() {

		setcookie('login', '');
		setcookie('pass', '');

	}

}

?>