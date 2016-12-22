<?php

namespace Arch\Repositories\Exceptions;

use Exception;
/**==========================================
 * Exception For Repositories, BaseShareable 
 * ==========================================
 * - Repositories code started with prefix 2, eg : 201
 * - Baseshareable code started with prefix 3, eg : 301 
 * 
 */
class BaseException extends Exception {
	// list all user defined message
	// must be prefix with msg and 
	// suffix with error code number
	// this properties must be defined its data
	// inside respective exception, not
	// inside this class
	protected $msgCollection = [];
	// all the available error code
	// this properties must be defined its data
	// inside respective exception, not
	// inside this class
	protected $errorCode = [];
	// message properties store message
	// from exception class itself
	protected $message;
	// just past in the error code
	// instead of self-describing message
	public function __construct ( $code = null, $message = null, Exception $previous = null ) {		
		// checking for error code
		// passed in exist or not		
		if ( $code ) {
			// checking for caller error code
			// if exist
			if ( in_array( $code, $this->errorCode ) !== false )
				// throw an error using universal message
				parent::__construct( 'Error thrown in ' . get_called_class() . ". \n\n  Said : ".$this->msgCollection[ 'msg'.$code ], $code, $previous );
			else
				// thrown default exception
				throw new Exception( 'Error code not found' );
		}
		else {
			// this section only for exception
			// who have custom message instead of
			// the default message exception
			if ( !$code )
				parent::__construct( $message, $code, $previous );
			else
				parent::__construct( 'Error thrown in ' . get_called_class() . ". \n\n  Said : ".$this->msgCollection[ 'msg'.$code ], $code, $previous );
		}

	}

	// custom string representation of object
	// if some of users tried to echo
	// the string object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}