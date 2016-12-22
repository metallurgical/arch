<?php

namespace Arch\Repositories\Tools;

/**
 * Trait for BaseShareables abstract class
 * --------------------------------------
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
 * Note
 * ----------
 * - $arrValue in an array. Its data is a value of $whereCollection's value, not key.
 * 	  => Only applied for all the above example except [whereNull,whereNotNull]
 * 	  
 * - $stringValue is a normal string. Its data is a value of $whereCollection's value, 
 * and should be the name of table's column.
 *    => Only applied for [whereNull,whereNotNull] only
 */

trait BaseQueryBuilder {

	private $query = null;

	/**
	 * Set instance of Query
	 * @param [type] $query [description]
	 */
	public function setQuery ( $query ) {

		if ( $this->query != null ) return $this->query;

		$this->query = $query;

		return $this->query;

	}
	/**
	 * Where Condition
	 * @param  [type]  $arrValue [array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhere ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->where( $arrValue );

	}
	/**
	 * WhereIn Condition
	 * @param  [type]  $arrValue [array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereIn ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereIn( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Where Not In Condition
	 * @param  [type]  $arrValue [array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereNotIn ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereNotIn( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Or Where
	 * @param  [type]  $arrValue [array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbOrWhere ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		$builder = $this->query;

		if ( count( $arrValue ) > 1 ) {			

			foreach ( $arrValue as $key => $value ) {				
				$builder->orWhere( [$value] );
			}

		}
		else
			$builder->orWhere( $arrValue );


		return $builder;
		
	}
	/**
	 * Select Null column/value
	 * @param  [type]  $stringValue [String - column's name]
	 * @param  boolean $query       [false as default]
	 * @return [type]               [description]
	 */
	public function dbWhereNull ( $stringValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereNull( $stringValue );
		
	}
	/**
	 * Select not null value
	 * @param  [type]  $stringValue [string - column's name]
	 * @param  boolean $query       [description]
	 * @return [type]               [description]
	 */
	public function dbWhereNotNull ( $stringValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereNotNull( $stringValue );
		
	}
	/**
	 * Select data in between
	 * @param  [type]  $arrValue [array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereBetween ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereBetween( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Select data not in between
	 * @param  [type]  $arrValue [array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereNotBetween ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereNotBetween( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Select data by date
	 * @param  [type]  $arrValue [[array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereDate ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereDate( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Select data by month
	 * @param  [type]  $arrValue [[array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereMonth ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereMonth( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Select data by day
	 * @param  [type]  $arrValue [[array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereDay ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereDay( $arrValue[0], $arrValue[1] );
		
	}
	/**
	 * Select data by year
	 * @param  [type]  $arrValue [[array value]
	 * @param  boolean $query    [false as default]
	 * @return [type]            [description]
	 */
	public function dbWhereYear ( $arrValue, $query = false ) {

		if ( $query ) $this->setQuery( $query );

		return $this->query->whereYear( $arrValue[0], $arrValue[1] );
		
	}
}
