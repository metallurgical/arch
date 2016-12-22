<?php

namespace Arch\Repositories\Contract;
/**
 * Creating interface for Base Contract
 * --------------------------------------
 * BaseShareable should implement this Interface
 * To Developers : You need to define all the base method here.
 * 				   Something like simple CRUD(Create, Read, Update, Delete )
 * 				   That sharables accross all the data model|Bussiness logic
 */
interface BaseContract {
	/**
	 * [Fetch By ID]
	 * @param  [Integer] $id [Id]
	 * @return [type]     [Nothing]
	 */
	public function find ( $id );
	/**
	 * [Fetching all data]
	 * @return [Collection] [User Collection]
	 */
	public function all ();
	/**
	 * [Complex select]
	 * @param  [column fields | accept array]
	 * @param  [column fields | accept array]
	 * @param  [column fields | accept array]
	 * @return [Collection]
	 */
	public function select ( $select = false, $where = false, $join = false, $groupBy = false, $orderBy = false, $skipTake = false );
	/**
	 * [Delete specific data]
	 * @return [] []
	 */
	public function delete ($id);
	/**
	 * Delete record using Any fields as Condition
	 * @param  array  $arrValue [Array value]
	 * @return [type]           [description]
	 */
	public function deleteWhere ( $arrValue = [] );
	/**
	 * [Select all the value inside database, then after that
	 * filter the result to only take certain rows by its value]
	 * @param  array  $arrValue [Exception Value]
	 * @return [Filtered Collection]           [description]
	 */
	public function except ( $arrValue = [] );
	/**
	 * [Select all the data using where condition]
	 * @param  array  $where [Array of condition]
	 * @return [Collection]        [description]
	 */
	public function where ( $where = [] );
	/**
	 * [Create Method | Insert Method ]
	 * @param  array  $arrValue [Array of column's field]
	 * @return [type]           [description]
	 */
	public function create ( $arrValue = [] );
	/**
	 * Insert Method | Alias to create && insert method
	 * @param  array   $arrValue [Array of column's field | Can inert multiple record at once]
	 * @param  boolean $id       [for postgresSQL, insertGetId expect incrementing column 
	 *                            to be named as ID, if would like to fetch sequence Id other than ID, 
	 *                            you may pass those field on second this parameter, leave blank if default ]
	 * @return [type]            [Return ID]
	 */
	public function insertGetId ( $arrValue = [], $id = false );
	/**
	 * Insert Method | Alias to insertGetId && Create Method
	 * @param  array  $arrValue [Array of column's field | Can inert multiple record at once]
	 * @return [type]           [Boolean]
	 */
	public function insert ( $arrValue = [] );
	/**
	 * Update fields by ID
	 * @param  [type] $id       [Id of table]
	 * @param  array  $arrValue [Columns's field]
	 * @return [type]           [Boolean]
	 */
	public function update ( $id, array $arrValue );
	/**
	 * Update fields by Any fields
	 * @param  array  $whereCond    [Array of condition, accept multiple field]
	 * @param  array  $arrValue [Columns to be update]
	 * @return [type]           [Boolean]
	 */
	public function updateWhere ( $whereCond = [], $arrValue = [] );
	/**
	 * Increment value | Will increment by 1 if amount not specified, if specified otherwise
	 * @param  boolean $column      [Column to increment]
	 * @param  array   $otherFields [Optional column to update | accept array]
	 * @param  Integer $amount      [Integer value]
	 * @return [type]               [Boolean]
	 */
	public function increment ( $column = false, $otherFields = [], $amount = false );
	/**
	 * Decrement value | Will increment by 1 if amount not specified, if specified otherwise
	 * @param  boolean $column      [Column to increment]
	 * @param  array   $otherFields [Optional column to update | accept array]
	 * @param  Integer $amount      [Integer value]
	 * @return [type]               [Boolean]
	 */
	public function decrement ( $column = false, $otherFields = [], $amount = false );
	/**
	 * Remove all rows and reset the auto-incrementing ID to zero
	 * @return [type] [boolean]
	 */
	public function truncate ();
	/**
	 * Alias to truncate method
	 * @return [type] [description]
	 */
	public function reset ();
	

}