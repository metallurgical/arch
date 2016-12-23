<?php

namespace Arch\Repositories\Shareables;

use Arch\Repositories\Tools\BaseQueryBuilder as BaseQueryBuilderTrait;
use Arch\Repositories\Contract\BaseContract as BaseInterface;
use Arch\Repositories\Exceptions\BaseShareablesException;
use DB;
/**
 * Creating interface for Base Shareable/Base Contract
 * --------------------------------------
 * To Developers : React as a Base Abstract class
 * 				   This class implements the method inside BaseContract/BaseInterface
 * 				   And this class only implement the method that shareables for all
 * 				   Model
 */
abstract class BaseShareables implements BaseInterface{

	use BaseQueryBuilderTrait;
	// model instance
	protected $model;
	// table tied to model | or using table name as qury builder
	protected $table;
	// table tied to model | or using table name as qury builder For alias
	protected $tableAlias = null;
	// available where condition can be used
	private $whereCollection = [ 
			'where',
			'whereIn', 
			'whereNotIn', 
			'orWhere', 
			'whereNull', 
			'whereNotNull', 
			'whereBetween', 
			'whereNotBetween', 
			'whereDate',
			'whereMonth',
			'whereDay',
			'whereYear'
		];
	// private method scoping only used
	// in baseShareable only
	private $internalMethod = [
			'__call',
			'__construct',
			'_filterPrivateFunction',
			'_matchedMethod'
		];
	/**
	 * Triggered when method does not exist getting called
	 * by the repositories
	 * This is for debugging purpose
	 * Comment it out when in production MODE
	 * @param  [type] $methodName [description]
	 * @param  [type] $args       [description]
	 * @return [type]             [description]
	 */
	public function __call( $methodName, $args ) {	
		// get this class's name
		$parentClass           = get_class();
		// get instance's class name
		// the one wo instantiated 
		// and called the method
		$currentObj            = get_called_class();
		// store all the available method
		// of current class
		$availableMethodChild  = '';
		// store all the available method
		// of parent class
		$availableMethodParent = '';
		// store the matched child method
		$matchedMethodChild    = '';
		// store the matched parent method
		$matchedMethodParent   = '';
		// get all the child method
		$childMethod           = get_class_methods( $currentObj );
		// get all the parent method
		$parentMethod          = get_class_methods( $parentClass );
		// filter out child method who is 
		// the value differ with parent value
		$childTrueMethod       = array_diff( $childMethod, $parentMethod );
		// never include private method 
		// as private method only for internal use
		// both parent & child
		$availableMethodParent = $this->_filterPrivateFunction( $parentMethod );
		$availableMethodChild = $this->_filterPrivateFunction( $childTrueMethod );
		// iterate over all the available method in both parent and child
		// lookup for matching name
		// not really accurate but enough for now
		$matchedMethodParent = $this->_matchedMethod( $availableMethodParent, $methodName );
		$matchedMethodChild = $this->_matchedMethod( $availableMethodChild, $methodName );		
		// take the method and convert it 
		// into string join with \n separated
		$parentStr = ( count( $matchedMethodParent ) !== 0 ) ? implode( "\n", $matchedMethodParent ) : implode( "\n", $availableMethodParent );		
		$childStr = ( count( $matchedMethodChild ) !== 0 ) ? implode( "\n", $matchedMethodChild ) : implode( "\n", $availableMethodChild );
		// if above string return empty or null
		// then write default message
		$parentStr = ( $parentStr !== '' ) ? $parentStr : 'No Methods Available';
		$childStr  = ( $childStr !== '' ) ? $childStr : 'No Methods Available ';

		if ( $parentClass == 'BaseShareables' && $currentObj == 'BaseShareables' ) {

			throw new BaseShareablesException( null, "Debugging Started : \n\n Method `$methodName` does not exist in class" . get_called_class() . ". \n\nSystem said, do you mean any of these methods : \n\n" . $str . ". \n\nOtherwise please check your SPELLING dude!!" );
		}
		else {
			throw new BaseShareablesException(  null, "Debugging Started : \n\n Method `$methodName` does not exist in class " . get_called_class() . " and in parent class " . get_class() . ".  \n\nSystem said, do you mean any of these methods in :  \n\n a) Current Class [" . get_called_class() . "] : \n\n" . $childStr . " \n\n b) Parent Class [" . get_class() . "] : \n\n " . $parentStr . "\n\nOtherwise please check your SPELLING dude!!" );
		}
		
	}
	/**
	 * Filter out private method and traits method
	 * As the private and traits method are only for
	 * internal used only
	 * @param  [type] $relatedMethod [description]
	 * @return [type]                [description]
	 */
	private function _filterPrivateFunction ( $relatedMethod ) {
		
		$availableMethod = array();
		// never include private method 
		// as private method only for internal use only
		// parent
		foreach( $relatedMethod as $val ) {
			
			// skip private method
			if ( in_array( $val, $this->internalMethod ) !== false ) { }

			else { 
				// skip trait method
				// trait method also for 
				// internal used only
				if ( strpos( $val, 'db' ) !== false ) {

					$originalVal = substr( $val, 2 );

					if ( in_array( lcfirst( $originalVal ), $this->whereCollection ) !== false ) {} else { 				
						array_push( $availableMethod, $val );				
					}

				}
				// insert only related method
				else
			    	array_push( $availableMethod, $val );
								
			}
		}
		
		return $availableMethod;

	}
	/**
	 * Matched method
	 * @param  [type] $methods    [description]
	 * @param  [type] $methodName [description]
	 * @return [type]             [description]
	 */
	private function _matchedMethod ( $methods, $methodName ) {

		$matchedMethod = array();
		// iterate over all the available method related class
		// lookup for matching name
		// not really accurate but enough for now
		foreach( $methods as $val ) {	
				
			if ( strpos( $val, $methodName ) !== false ) {
				array_push( $matchedMethod, $val );
			}
			
		}

		return $matchedMethod;

	}
	/**
	 * [Fetching by ID]
	 * @param  [Integer] $id [ID]
	 * @return [Collection]  [User Collection]
	 */
	public function find ( $id ) {
		return $this->model->find( $id );
	}

	/**
	 * [Fetching all data]
	 * @return [Collection] [User Collection]
	 */
	public function all ( ) {
		
		return $this->model->all();
		
	}
	/**
	 * Set Model Instance if no model was attached
	 * @param [type] $modelInstance [description]
	 */
	public function setModel ( $modelInstance ) {
		return $this->model = $modelInstance;
	}

	public function setTableAlias ( $aliasName ) {

		if ( strpos( $aliasName, 'as' ) )
			$this->tableAlias = $aliasName;
		else
			$this->tableAlias = ' as ' . $aliasName;

		return $this->tableAlias;
	}
	/**
	 * [Complex select]
	 * @param  [column fields | accept array]
	 * @param  [column fields | accept array]
	 * @param  [column fields | accept array]
	 * @return [Collection]
	 * ==========================================================
	 * Parameter Usage example :
	 * ==========================================================
	 * 
	 * 1) Select
	 * ------------------------
	 * $select = ['column1', 'column2', 'columnN',.....]
	 * 
	 * 
	 * 2) Where
	 * ------------------------
	 * $whereCollection = array( 
	 *		'whereIn' => array('field',['1', '2', '3'] ),
	 *		'where' => array(['a', '=', '2'],['a', '=', '2'], ['a', '=', '2']), 
	 *		'whereNotIn' =>  array('field',['1', '2', '3']),
	 *		'orWhere' => array(['a', '=', '2'],['a', '=', '2'], ['a', '=', '2']),
	 *		'whereNull' => 'field', 
	 *		'whereNotNull' =>  'field',
	 *		'whereBetween' => array('field',['from', 'to']),
	 *		'whereNotBetween' => array('field',['from', 'to']), 
	 *		'whereDate' =>  array('field','value eg: 2016-10-15'),
	 *		'whereMonth' => array('field','value eg: 7'),
	 *		'whereDay' => array('field','value eg :15'), 
	 *		'whereYear' =>  array('field','value eg:2016')
	 *	);
	 *
	 * 
	 * 3) Join
	 * ------------------------
	 * $joinCollection = array( 
	 *		'join' => array(['table','firstTable.column', '=', 'secondTable.column'],
	 *						['table','firstTable.column', '=', 'secondTable.column'], 
	 *						['table','firstTable.column', '=', 'secondTable.column']),
	 *		'leftJoin' => array(['table','firstTable.column', '=', 'secondTable.column'],
	 *							['table','firstTable.column', '=', 'secondTable.column'], 
	 *							['table','firstTable.column', '=', 'secondTable.column'])
	 *	);
	 *
	 * 
	 * 4) GroupBy
	 * ------------------------
	 * $groupByCollection = array( 
	 *		'groupBy' => array(['a', 'b', 'c', 'd']),
	 *		'having' => array(['a', '=', '2'],['a', '<', '2'], ['a', '>', '2'])
	 *	);
	 *
	 *  5) OrderBy
	 * ------------------------
	 * $orderBy = array( 
	 *		'orderBy' => array(
	 *							['fieldToOrder1', 'DESC/ASC'],
	 *							['fieldToOrder2', 'DESC/ASC'], 
	 *						 	['fieldToOrder3', 'DESC/ASC']
	 *						),
	 *	);
	 *
	 * 6) Skip and Take / Offset and Limit
	 * ------------------------
	 * $skipTakeCollection = array( 
	 *		'skip' => array(['a']),
	 *		'take' => array(['a']),
	 *		'offset' => array(['a']),
	 *		'limit' => array(['a']),
	 *	);
	 */
	public function select ( $select = false, $where = false, $join = false, $groupBy = false, $orderBy = false, $skipTake = false) {

		if ( $select ){

			$table = $this->model->getTable();

			if ( $this->tableAlias && $this->tableAlias != null )
				$query = DB::table( $table . $this->tableAlias );
			else
				$query = DB::table( $table );

			if ( $where ){				

				$this->setQuery( $query );
				
				foreach( $where as $key => $value ) {

					if ( in_array( $key, $this->whereCollection ) ) {
						// this will output 
						// dbWhere, dbWherein, etc........
						// and autmatically instance of query
						// inside the BaseQueryBuilderTrait					
						$this->{ 'db'.ucfirst( $key ) }( $value );
					}

	        	}				

			}

			if ( $join ) {

				foreach ( $join as $key => $joinSet ) {
					
					if ( $key == 'join' ) {

						foreach ( $joinSet as $key => $joinSetJoin ) {
							
							$query->join( $joinSetJoin[0], $joinSetJoin[1], $joinSetJoin[2], $joinSetJoin[3]);

						}

					}
					else if ( $key == 'leftJoin' ) {

						foreach ( $joinSet as $key => $joinSetLeft ) {

							$query->leftJoin( $joinSetLeft[0], $joinSetLeft[1], $joinSetLeft[2], $joinSetLeft[3]);

						}

					}

	        	}
				
			}

			if ( $groupBy ) {

				foreach ( $groupBy as $key => $groupBySet ) {

					if ( $key == 'groupBy' ) {

						foreach ( $groupBySet as $key => $groupBySetGroupBy ) {

							$query->groupBy( $groupBySetGroupBy );

						}

					}
					else if( $key == 'having' ) {

						foreach ( $groupBySet as $key => $groupBySethaving ) {

							$query->groupBy( $groupBySethaving[0], $groupBySethaving[1], $groupBySethaving[2] );

						}

					}

				}

			}

			if ( $orderBy ) {
				
				foreach ( $orderBy as $key => $orderBySet ) {

					if ( $key == 'orderBy' ) {

						foreach ( $orderBySet as $key => $orderBySetOrderBy ) {

							$query->orderBy( $orderBySetOrderBy[0], $orderBySetOrderBy[1]);

						}

					}

				}

			}

			if ( $skipTake ) {

				foreach ( $skipTake as $key => $skipTakeSet ) {

					if ( $key == 'skip' || $key == 'offset' ) {

						foreach ( $skipTakeSet as $key => $skipTakeSetSkip ) {

							$query->skip( $skipTakeSetSkip[0] );

						}

					}
					else if( $key == 'take' || $key == 'limit' ) {

						foreach ( $skipTakeSet as $key => $skipTakeSetTake ) {

							$query->take( $skipTakeSetTake[0]);

						}

					}
 
				}

			}

			$query->select( $select );
	        return $query->get();
	    }

		return false;
	}
	/**
	 * [Delete record using ID as condition]
	 * @param  [Integer/hash] $id [ID]
	 * @return []  []
	 */
	public function delete ( $id ) {
		
		$collModel = $this->model->find( $id );

		if( $collModel->delete() ){
			return true;
		}
		else{
			return false;
		}
	}
	/**
	 * Delete record using Any fields as Condition
	 * @param  array  $arrValue [Array value]
	 * @return [type]           [description]
	 */
	public function deleteWhere ( $arrValue = [] ) {

		if (  !empty( $arrValue ) ) {

			$table = $this->model->getTable();
			$query = DB::table( $table );
			
			foreach( $arrValue as $key => $where ) {				
            	$query->where( $where[0], $where[1], $where[2] );
        	}

        	$flag = $query->delete();
        	if ( $flag ) return true;
			else return false;
		}

		return false;
	}
	/**
	 * [Select all the value inside database, then after that
	 * filter the result to only take certain rows by its value]
	 * @param  array  $arrValue [Exception Value]
	 * @return [Filtered Collection]           [description]
	 */
	public function except ( $arrValue = [] ) {

		if (  !empty( $arrValue ) ) {

			$data = $this->all();
			return $data->except( $arrValue );
		}

		return false;
	}
	/**
	 * [Select all the data using where condition]
	 * @param  array  $where [Array of condition]
	 * @return [Collection]        [description]
	 */
	public function where ( $where = [] ) {

		$table = $this->model->getTable();

		if ( !empty( $where ) ) {

			return DB::table( $table )->where( $where )->get();
		}

		return false;
		
	}
	/**
	 * [Create Method | Insert Method ]
	 * @param  array  $arrValue [Array of column's field]
	 * @return [type]           [description]
	 */
	public function create ( $arrValue = [] ) {

		if ( !empty( $arrValue ) ) {

			$instance = $this->model->create( $arrValue );
			
			if ( $instance ) {

				if ( !is_null( $instance->id ) )
					return $instance->id;
				else {
					return true;
				}
			}

			return false; 
		}

		return false;
	}
	/**
	 * Insert Method | Alias to create && insert method
	 * @param  array   $arrValue [Array of column's field | Can inert multiple record at once]
	 * @param  boolean $id       [for postgresSQL, insertGetId expect incrementing column 
	 *                            to be named as ID, if would like to fetch sequence Id other than ID, 
	 *                            you may pass those field on second this parameter, leave blank if default ]
	 * @return [type]            [Return ID]
	 */
	public function insertGetId ( $arrValue = [], $id = false ) {

		if ( !empty( $arrValue ) ) {

			$table = $this->model->getTable();

			if ( $id ) {
				$id = DB::table( $table )->insertGetId( $arrValue, $id );
			}
			else {
				$id = DB::table( $table )->insertGetId( $arrValue );
			}
			

			return $id;
		}

		return false;
	}
	/**
	 * Insert Method | Alias to insertGetId && Create Method
	 * @param  array  $arrValue [Array of column's field | Can insert multiple record at once]
	 * @return [type]           [Boolean]
	 */
	public function insert ( $arrValue = [] ) {

		if ( !empty( $arrValue ) ) {

			$table = $this->model->getTable();

			$flag = DB::table( $table )->insert( $arrValue );			

			if ( $flag ) return true;
			else return false;
		}

		return false;
	}
	/**
	 * Update fields by ID
	 * @param  [type] $id       [Id of table]
	 * @param  array  $arrValue [Columns's field]
	 * @return [type]           [Boolean]
	 */
	public function update ( $id, array $arrValue ) {

		if ( !empty( $arrValue ) ) {

			$instance = $this->find( $id );
			$flag = $instance->update( $arrValue );

			if ( $flag ) return true;
			else return false;
		}

		return false;
	}
	/**
	 * Update fields by Any fields
	 * @param  array  $whereCond    [Array of condition, accept multiple field]
	 * @param  array  $arrValue [Columns to be update]
	 * @return [type]           [Boolean]
	 */
	public function updateWhere ( $whereCond = [], $arrValue = [] ) {

		if (  !empty( $arrValue ) && !empty( $whereCond ) ) {

			$table = $this->model->getTable();
			$query = DB::table( $table );
			
			foreach( $whereCond as $key => $where ) {				
            	$query->where( $where[0], $where[1], $where[2] );
        	}

        	$flag = $query->update( $arrValue );

        	if ( $flag ) return true;
			else return false;

		}

		return false;
	}
	/**
	 * Increment value | Will increment by 1 if amount not specified, if specified otherwise
	 * @param  boolean $column      [Column to increment]
	 * @param  array   $otherFields [Optional column to update | accept array]
	 * @param  Integer $amount      [Integer value]
	 * @return [type]               [Boolean]
	 */
	public function increment ( $column = false, $otherFields = [], $amount = false ) {

		if ( $column != false ) {

			$table = $this->model->getTable();
			$query = DB::table( $table );
			// if both true
			if ( $amount != false && !empty( $otherFields ) ) {
				$flag = $query->increment( $column, $amount, $otherFields );
			}
			// if amount false && otherFields not empty
			else if ( $amount == false && !empty( $otherFields ) ) {
				$flag = $query->increment( $column, 1, $otherFields );
			}
			// if amount true && other fields empty
			else if ( $amount != false && empty( $otherFields ) ) {
				$flag = $query->increment( $column, $amount );
			}
			// if amount false && other fields empty
			else if ( $amount == false && empty( $otherFields ) ) {
				$flag = $query->increment( $column );
			}

			if ( $flag ) return true;
			else return false;
		}

		return false;
			
	}
	/**
	 * Decrement value | Will increment by 1 if amount not specified, if specified otherwise
	 * @param  boolean $column      [Column to increment]
	 * @param  array   $otherFields [Optional column to update | accept array]
	 * @param  Integer $amount      [Integer value]
	 * @return [type]               [Boolean]
	 */
	public function decrement ( $column = false, $otherFields = [], $amount = false ) {

		if ( $column != false ) {

			$table = $this->model->getTable();
			$query = DB::table( $table );
			// if both true
			if ( $amount != false && !empty( $otherFields ) ) {
				$flag = $query->decrement( $column, $amount, $otherFields );
			}
			// if amount false && otherFields not empty
			else if ( $amount == false && !empty( $otherFields ) ) {
				$flag = $query->decrement( $column, 1, $otherFields );
			}
			// if amount true && other fields empty
			else if ( $amount != false && empty( $otherFields ) ) {
				$flag = $query->decrement( $column, $amount );
			}
			// if amount false && other fields empty
			else if ( $amount == false && empty( $otherFields ) ) {
				$flag = $query->decrement( $column );
			}

			if ( $flag ) return true;
			else return false;
		}
		
		return false;
			
	}
	/**
	 * Remove all rows and reset the auto-incrementing ID to zero
	 * @return [type] [boolean]
	 */
	public function truncate () {

		$table = $this->model->getTable();
		$query = DB::table( $table );
		$flag = $query->truncate();
		if ( $flag ) return true;
		else return false;

	}
	/**
	 * Alias to truncate method
	 * @return [type] [description]
	 */
	public function reset () {
		return $this->truncate();
	}


}