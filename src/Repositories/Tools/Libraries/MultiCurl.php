<?php

namespace Arch\Repositories\Tools\Libraries;

use Exception;



class MultiCurl {
	/**
	 * Array of Urls
	 * @var null
	 */
	private $urls           = null;
	/**
	* Results/Response
	* @var array
	*/
	private $results        = array();
	/**
	 * The whole set of Result/Response
	 * @var array
	 */
	private $resultSet 		= array();
	/**
	* Curl instance/ID(s)
	* @var array
	*/
	private $curly          = array();	
	/**
	* Default Items value per request
	* @var integer
	*/
	private $perRequest     = 5;
	/**
	* Default callback value
	* @var null
	*/
	private $callback       = null;
	/**
	* Spliced data that send to run method
	* @var array
	*/
	private $splicedUrls    = array();
	/**
	* Default multicurl operation is set to Batch Request
	* @var boolean
	*/
	private $batchOperation = true;

	
	/**
	 * Construct accept 2 parameters
	 * @param [array]  $urls       [array of urls]
	 * @param integer $perRequest [number of items per request]
	 */
    public function __construct( $urls = false, $perRequest = false ) {

	    $this->urls = $urls;

	    if ( $urls ) {
	    	
	    	if ( !is_array( $urls ) )
	    		throw new Exception( 'First parameter must be array type' );

	    }
	    else
	    	throw new Exception( 'First parameter is required. Accept array of URL(s) only' );

	    // if provided the data
	    if ( $perRequest ) {
	    	// check for value zero 
	    	// or below than that
    		if ( $perRequest <= 0 )
    			throw new Exception( '1 request per second MIN, please provide value more than 0.' );    	
    		// check if per request value
    		// is more than url provided
	    	if ( $perRequest > count( $urls ) )
	    		throw new Exception( 'Url provided less then the number of request provided. Please specify number of request less than or equal' );	    	
	    	// finally assing to private member
	    	$this->perRequest = $perRequest;

	    }
	    


    }
    /**
     * Change default batch operation
     * Either true or false
     * @param [type] $bool [description]
     */
    public function setBatchOperation ( $bool ) {

    	if ( !is_bool( $bool ) )
    		throw new \Exception( 'Accept only boolean value.');

    	$this->batchOperation = $bool;

    	return $this;
    	
    }
    /**
     * Public method expose to outside
     * Execute curl
     * @param  [function] $callback [callback function to execute per request]
     * @return [type]           [description]
     */
    public function execute ( $callback = null ) {

    	if ( !is_null( $callback ) ) {
    		// if callback was provided
    		// assign it private member
	    	if ( is_callable( $callback ) )
	    		$this->callback = $callback;

	    }
	    // call the method
	    $result = $this->recursiveRequest();

    	return $result;
    }    
    /**
     * Recursive Function to execute
     * @return [type]       [description]
     */
    private function recursiveRequest () { 

    	if ( $this->batchOperation ) {

	    	if ( count( $this->urls ) >= $this->perRequest )  {
		    	// take portion data as we
		    	// need to request curl batch by batch
				$splicedUrls = array_splice( $this->urls, 0, $this->perRequest );
				// call run method for
				// curl execution
				$result = $this->run( $splicedUrls );
				// if callback was provided
				// then run the callback and 
				// passed out the result
				if ( !is_null( $this->callback ) )
					call_user_func( $this->callback, $this->results );	
				// if data still exist after splice
				// then call again this method
				// depend on data size
				if ( !empty( $this->urls ) ) {

					sleep( 2 ); // go sleep in your bed for 2 seconds only. Hahahahah...
					// if data size same length with 
					// batch request's number, then call
					// this method instead
					if ( count( $this->urls ) >= $this->perRequest ) {   					
						$result = $this->recursiveRequest();
					}
					// otherwise just execute data directly
					else {
						// call run
						$result = $this->run( $this->urls );
						// if callback was provided
						// then run the callback and 
						// passed out the result
						if ( !is_null( $this->callback ) )
							call_user_func( $this->callback, $this->results );

					}
				}

				return $result;

			}		
					
			// call run
			$result = $this->run( $this->urls );
			// if callback was provided
			// then run the callback and 
			// passed out the result
			if ( !is_null( $this->callback ) )
				call_user_func( $this->callback, $this->results );		
			

			return $result;
		}
		else {

			// call run
			$result = $this->run( $this->urls );
			// if callback was provided
			// then run the callback and 
			// passed out the result
			if ( !is_null( $this->callback ) )
				call_user_func( $this->callback, $this->results );		
			

			return $result;

		}
			
	}
	/**
	 * Initialize curl
	 * @return [type]       [description]
	 */
    private function init () {

    	//reset to default state
    	$this->curly = array();
    	
    	$mh = curl_multi_init();

	    foreach ( $this->splicedUrls as $id => $url ) {	    	

	        $this->curly[$id] = curl_init();

	        curl_setopt( $this->curly[ $id ], CURLOPT_URL,            $url );
	        curl_setopt( $this->curly[ $id ], CURLOPT_HEADER,         0 );
	        curl_setopt( $this->curly[ $id ], CURLOPT_RETURNTRANSFER, true );
	        curl_setopt( $this->curly[ $id ], CURLOPT_TIMEOUT,        2 );
	        curl_setopt( $this->curly[ $id ], CURLOPT_USERAGENT,      'CurlRequest' );
	        curl_setopt( $this->curly[ $id ], CURLOPT_REFERER,        $url );
	        curl_setopt( $this->curly[ $id ], CURLOPT_AUTOREFERER,    true );
	        curl_setopt( $this->curly[ $id ], CURLOPT_RETURNTRANSFER, true );
	        curl_multi_add_handle( $mh, $this->curly[ $id ] );

	    }

	    // reset temp variable to its
	    // default state
	    $this->splicedUrls = array();

	    return $mh;

    }
    /**
     * Execute curl and return response
     * @param  [array] $urls [array of urls]
     * @return [type]       [description]
     */
    public function run ( $urls ) {

    	// reset result to default
    	$this->results = [];
    	// assign it to temp variable 
    	// for init method's used
    	$this->splicedUrls = $urls;

    	$mh = $this->init();

	    $running = null;
	    // execute curl
	    do {

	        $mm = curl_multi_exec( $mh, $running );

	    } while( $running > 0 );

	    // do checking for next request
	    // if exist, then run execute again
	    while ( $running && $mm == CURLM_OK ) {

	        if (curl_multi_select($mh) != -1) {

	            do {

	                $mm = curl_multi_exec($mh, $running);

	            } while ( $running > 0 );
	        }
	    }
	    // fetching the result and
	    // store result set into array container
	    foreach( $this->curly as $id => $c ) {

	        $this->results[$id] = curl_multi_getcontent( $c );	        
	        array_push( $this->resultSet, $this->results[$id] );

	        curl_multi_remove_handle( $mh, $c );

	    }
	    // close connection when finish
	    curl_multi_close( $mh );

	    if ( $this->batchOperation )
	    	return $this->results;
	    else
	    	return $this->resultSet;

    }

}