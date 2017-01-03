<?php

namespace App\Repositories\Tools\libraries;

use Exception;

class Encryption {

	private static $salt;
	/**
	 * Using APP_KEY inside app.php
	 */
	public function __construct() {
		self::$salt = env( 'APP_KEY' );
	}
	/**
	 * Hashing value
	 * @param  [type] $toHash [Any value to hash]
	 * @param  string $shaBit [Default Bit to hash]
	 * @return [type]         [hashed value]
	 */
	public static function hash ( $toHash, $shaBit = 'sha256' ) {
		return hash( $shaBit, $toHash . self::$salt );
	}
	/**
	 * Compare encrypted value with Hash value
	 * @param  [type] $encryptVal [Encrypted value]
	 * @param  [type] $hashVal    [Hashed value]
	 * @param  string $shaBit     [Default bit to hash]
	 * @return [type]             [Decrypted value if TRUE, otherwise FALSE]
	 */
	public static function compare( $encryptVal, $hashVal, $shaBit = 'sha256' ) {		

		$decryptVal = Encryption::decrypt( $encryptVal );
		$decHashVal = hash( $shaBit, $decryptVal . self::$salt  );
		if ( $decHashVal == $hashVal ) return $decryptVal;

		return false;
	}
	/**
	 * Static method to encrypt
	 * @param  [type] $toEncrypt [Value to encrypt]
	 * @return [type]            [Encrypted value]
	 */
	public static function encrypt ( $toEncrypt ) {

		try {

			$encryptVal = encrypt( $toEncrypt );
			return $encryptVal;
		}
		catch ( Exception $e ) {
			return false;
		}
		
	}
	/**
	 * Static method to decrypt
	 * @param  [type] $toDecrypt [Value to decrypt]
	 * @return [type]            [Decrypted value]
	 */
	public static function decrypt ( $toDecrypt ) {
		
		
		try {

			$decryptVal = decrypt( $toDecrypt );
			return $decryptVal;

		}
		catch( Exception $e ) {
			return false;
		}
	}
}