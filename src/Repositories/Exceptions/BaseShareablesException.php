<?php

namespace Arch\Repositories\Exceptions;

use Arch\Repositories\Exceptions\BaseException;
/**================================
 * Exception For BaseShareable
 * - Baseshareable code started with prefix 3, eg : 301
 * ================================
 * Error code should follow bellow :
 * - you may define your own message, but 
 *   be constructive and related to the problems
 * 
 * 301 - 
 * 302 - 
 * 303 - 
 * 304 - 
 * 305 -
 * 306 -
 * 307 -
 * 308 -
 * 309 -
 * 
 */

class BaseShareablesException extends BaseException {

	public function __construct ( $code = null, $msg = null, Exception $previous = null ) {
		// list all user defined message
		// must be prefix with msg and 
		// suffix with error code number
		// key-value pair array( 'msg301' => 'Describing the error we want to thrown' )
		$this->msgCollection = [];

		// all the available error code
		// for this exception only
		$this->errorCode = [];

		parent::__construct( $code, $msg, $previous );
	}
	
	
}