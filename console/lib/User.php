<?php
class User {
	// Permission levels
	const CREATE_FORUM = 2;
	const MOVE_MESSAGE = 4;
	const DELETE_MESSAGE = 8;
	const DELETE_FORUM = 16;
	private $uid; // user id
	private $fields; // other record fields
	private static $htusers = [ 
			'100' => [ 
					'uid' => '100',
					'username' => 'admin',
					'password' => 'yiquanTodo',
					'authKey' => 'test100key',
					'accessToken' => '100-token',
					'isActive' => true,
					'permission' => 31,
					'emailAddr' => '' 
			],
			'101' => [ 
					'uid' => '101',
					'username' => 'demo',
					'password' => 'yiquanTodo',
					'authKey' => 'test101key',
					'accessToken' => '101-token',
					'isActive' => true,
					'permission' => 31,
					'emailAddr' => '' 
			] 
	];
	
	// initialize a User object
	public function __construct($config = []) {
		if (empty ( $config )) {
			$this->uid = null;
			$this->fields = array (
					'username' => '',
					'password' => '',
					'emailAddr' => '',
					'isActive' => false,
					'permission' => 0 
			);
		} else {
			$this->uid = $config ['uid'];
			$this->fields = array (
					'username' => $config ['username'],
					'password' => $config ['password'],
					'emailAddr' => $config ['emailAddr'],
					'isActive' => $config ['isActive'],
					'permission' => $config ['permission'] 
			);
		}
	}
	
	// override magic method to retrieve properties
	public function __get($field) {
		if ($field == 'userId') {
			return $this->uid;
		} else {
			return $this->fields [$field];
		}
	}
	
	// override magic method to set properties
	public function __set($field, $value) {
		if (array_key_exists ( $field, $this->fields )) {
			$this->fields [$field] = $value;
		}
	}
	
	// return if username is valid format
	public static function validateUsername($username) {
		return preg_match ( '/^[A-Z0-9]{2,20}$/i', $username );
	}
	
	// return if email address is valid format
	public static function validateEmailAddr($email) {
		return filter_var ( $email, FILTER_VALIDATE_EMAIL );
	}
	
	// return an object populated based on the record's user id
	public static function getById($userId) {
		if (isset ( self::$htusers [$userId] ))
			return new static ( self::$htusers [$userId] );
		else
			return null;
	}
	
	// return an object populated based on the record's username
	public static function getByUsername($username) {
		foreach ( self::$htusers as $user ) {
			if (strcasecmp ( $user ['username'], $username ) === 0) {
				return new static ( $user );
			}
		}
		return null;
	}
	
	// save the record to the database
	public function save() {
		if ($this->uid) {
			$query = sprintf ( 'UPDATE %sUSER SET USERNAME = "%s", ' . 'PASSWORD = "%s", EMAIL_ADDR = "%s", IS_ACTIVE = %d, ' . 'PERMISSION = %d WHERE USER_ID = %d', DB_TBL_PREFIX, mysql_real_escape_string ( $this->username, $GLOBALS ['DB'] ), mysql_real_escape_string ( $this->password, $GLOBALS ['DB'] ), mysql_real_escape_string ( $this->emailAddr, $GLOBALS ['DB'] ), $this->isActive, $this->permission, $this->uid );
			return mysql_query ( $query, $GLOBALS ['DB'] );
		} else {
			$query = sprintf ( 'INSERT INTO %sUSER (USERNAME, PASSWORD, ' . 'EMAIL_ADDR, IS_ACTIVE, PERMISSION) VALUES ("%s", "%s", ' . '"%s", %d, %d)', DB_TBL_PREFIX, mysql_real_escape_string ( $this->username, $GLOBALS ['DB'] ), mysql_real_escape_string ( $this->password, $GLOBALS ['DB'] ), mysql_real_escape_string ( $this->emailAddr, $GLOBALS ['DB'] ), $this->isActive, $this->permission );
			if (mysql_query ( $query, $GLOBALS ['DB'] )) {
				$this->uid = mysql_insert_id ( $GLOBALS ['DB'] );
				return true;
			} else {
				return false;
			}
		}
	}
	
	// ... the rest of User beyond this point is left unchanged
	// set the record as inactive and return an activation token
	public function setPending() {
		$this->isActive = false;
		$this->save (); // make sure the record is saved
		
		$token = random_text ( 5 );
		$query = sprintf ( 'INSERT INTO %sPENDING (USER_ID, TOKEN) ' . 'VALUES (%d, "%s")', DB_TBL_PREFIX, $this->uid, $token );
		return (mysql_query ( $query, $GLOBALS ['DB'] )) ? $token : false;
	}
	
	// clear the user's pending status and set the record as active
	public function clearPending($token) {
		$query = sprintf ( 'SELECT TOKEN FROM %sPENDING WHERE USER_ID = %d ' . 'AND TOKEN = "%s"', DB_TBL_PREFIX, $this->uid, mysql_real_escape_string ( $token, $GLOBALS ['DB'] ) );
		$result = mysql_query ( $query, $GLOBALS ['DB'] );
		if (! mysql_num_rows ( $result )) {
			mysql_free_result ( $result );
			return false;
		} else {
			mysql_free_result ( $result );
			$query = sprintf ( 'DELETE FROM %sPENDING WHERE USER_ID = %d ' . 'AND TOKEN = "%s"', DB_TBL_PREFIX, $this->uid, mysql_real_escape_string ( $token, $GLOBALS ['DB'] ) );
			if (! mysql_query ( $query, $GLOBALS ['DB'] )) {
				return false;
			} else {
				$this->isActive = true;
				return $this->save ();
			}
		}
	}
	
	// set the record as inactive and return an activation token
	public function setInactive() {
		$this->isActive = false;
		$this->save (); // make sure the record is saved
		
		$token = random_text ( 5 );
		$query = sprintf ( 'INSERT INTO %sPENDING (USER_ID, TOKEN) ' . 'VALUES (%d, "%s")', DB_TBL_PREFIX, $this->uid, $token );
		mysql_query ( $query, $GLOBALS ['DB'] );
		
		return $token;
	}
	
	// clear the user's pending status and set the record as active
	public function setActive($token) {
		$query = sprintf ( 'SELECT TOKEN FROM %sPENDING WHERE USER_ID = %d ' . 'AND TOKEN = "%s"', DB_TBL_PREFIX, $this->uid, mysql_real_escape_string ( $token, $GLOBALS ['DB'] ) );
		$result = mysql_query ( $query, $GLOBALS ['DB'] );
		
		if (! mysql_num_rows ( $result )) {
			mysql_free_result ( $result );
			return false;
		} else {
			mysql_free_result ( $result );
			$query = sprintf ( 'DELETE FROM %sPENDING WHERE USER_ID = %d ' . 'AND TOKEN = "%s"', DB_TBL_PREFIX, $this->uid, mysql_real_escape_string ( $token, $GLOBALS ['DB'] ) );
			mysql_query ( $query, $GLOBALS ['DB'] );
			
			$this->isActive = true;
			$this->save ();
			return true;
		}
	}
}
?>
