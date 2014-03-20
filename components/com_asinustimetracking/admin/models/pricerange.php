<?php
/**
 * @package		TimeTrack
 * @version 	$Id: 	pricerange.php 1 29.09.2010 ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelPriceRange extends JModel{
	var $_tablename = '#__asinustimetracking_pricerange';

	function getListByUserService($cuid = -1, $csid = -1){
		$query = "SELECT * FROM " . $this->_tablename . " WHERE cu_id=" . (int) $cuid . " AND cs_id=" . (int) $csid;
		$query .= " ORDER BY start_time";
		$_result = $this->_getList( $query );

		return $_result;
	}

	function getById($cpid = -1){
		$query = "SELECT p.*, s.description, j.name FROM " . $this->_tablename . " p, #__asinustimetracking_user u, #__asinustimetracking_services s, #__users j WHERE p.cp_id=" . (int) $cpid;
		$query .= " AND p.cu_id=u.cuid AND p.cs_id = s.csid AND j.id=u.uid";
		$_result = $this->_getList( $query );

		return $_result[0];
	}

	function create($start_date = '', $end_date = '', $price = '', $cuid = -1, $csid = -1){
		$db = JFactory::getDBO();
		$sdate = JFactory::getDate($start_date);
		$edate = JFactory::getDate($end_date);

		$query = "INSERT INTO " . $this->_tablename . " (start_time, end_time, price, cu_id, cs_id) VALUES (" . $db->quote($sdate->toMySQL()) . ",". $db->quote($edate->toMySQL()) ."," . (float) $price . ", " . (int) $cuid .", ". (int) $csid ." )";

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
	}
	
	/**
	 * Delete Pricerange
	 * @param int $id
	 */
	function remove($id = -1){
		$db = JFactory::getDBO();

		$query = "DELETE FROM " . $this->_tablename . " WHERE cp_id=" . (int) $id;

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

	}

	/**
	 * Merge PriceRange
	 * @param $cpid
	 * @param $start_date
	 * @param $end_date
	 * @param $price
	 */
	function merge($cpid = -1, $start_date = '', $end_date = '', $price = ''){
		$db = JFactory::getDBO();
		$sdate = JFactory::getDate($start_date);
		$edate = JFactory::getDate($end_date);

		$query = "UPDATE " . $this->_tablename . " SET "
		. " start_time=" . $db->quote($sdate->toMySQL(), false)
		. ", end_time=" .  $db->quote($edate->toMySQL(), false)
		. ", price=" . (float) $price
		. " WHERE cp_id=" . (int) $cpid;
			
		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

	}

}