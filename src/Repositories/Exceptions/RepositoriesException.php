<?php

namespace App\Repositories\Exceptions;

use Arch\Repositories\Exceptions\BaseException;
/**
 * Exception For Repositories
 * - Repositories code started with prefix 2, eg : 201
 * ================================
 * Error code should follow bellow :
 * - you may define your own message, but 
 *   be constructive and related to the problems
 * 
 * 201 - Function not exist
 * 202 - Parameter not matched
 * 203 - All arguments/parameter for this methods is compulsory
 * 204 - Return type is compulsory
 * 205 - Wrong data type were passed in at arguments
 * 206 - Some of parameters not passed
 * 207 -
 * 208 -
 * 209 -
 * 
 */
class RepositoriesException extends BaseException {

	// this method does not require
	// message arguments, just passed in
	// error code
	public function __construct ( $code = null, $msg = null, Exception $previous = null ) {
		// list all user defined message
		// must be prefix with msg and 
		// suffix with error code number
		// key-value pair array( 'msg201' => 'Describing the error we want to thrown' )
		$this->msgCollection = [
			'msg201' => 'Function not exist',
			'msg202' => 'Parameter not matched',
			'msg203' => 'All arguments/parameters for this methods is compulsary',
			'msg204' => 'Return type is compulsory',
			'msg205' => 'Wrong data type were passed in at arguments'
		];

		// all the available error code
		// for this exception only
		$this->errorCode = [ 201, 202, 203, 204, 205, 206, 207, 208, 209 ];

		parent::__construct( $code, $msg, $previous );
	}
	
	
	
}