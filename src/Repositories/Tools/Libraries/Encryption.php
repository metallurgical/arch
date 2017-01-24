<?php

namespace Arch\Repositories\Tools\libraries;

use Hashids\Hashids;
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
	 * Encode value using Unique Identifier like Youtube
	 * Just short look nice instead of base64
	 * @param  [type] $toEncode [value to encode | accept only number(integer or 0, negative number cannot)]
	 * @return [type]           [encoded uid]
	 */
	public static function encode ( $toEncode ) {

		try {

			$hash = new Hashids( self::$salt, 15 );
			$encodedVal = $hashids->encode( $toEncode );
			return $encodedVal;

		}
		catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Decode uid(s)
	 * @param  [type] $toDecode [value to decode]
	 * @return [type]           [decoded value]
	 */
	public static function decode ( $toDecode ) {

		try {

			$hash = new Hashids( self::$salt );
			$decodedVal = $hashids->decode( $toDecode );
			return $decodedVal;

		}
		catch ( Exception $e ) {
			return false;
		}
	}
	/**
	 * Match the Uid with Hash value
	 * @param  [type] $encodedVal [encoded value using encode function]
	 * @param  [type] $hashVal    [hashed value]
	 * @param  string $shaBit     [bit of sha]
	 * @return [type]             [decoded uid]
	 */
	public static function match ( $encodedVal, $hashVal, $shaBit = 'sha256' ) {

		$decodedVal = Encryption::decode( $encodedVal );
		$decHashVal = hash( $shaBit, $decodedVal . self::$salt  );

		if ( $decHashVal == $hashVal ) return $decodedVal;

		return false;
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