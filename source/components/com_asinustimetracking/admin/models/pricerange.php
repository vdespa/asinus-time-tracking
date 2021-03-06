<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class AsinusTimeTrackingModelPriceRange extends JModelLegacy
{
	var $_tablename = '#__asinustimetracking_pricerange';

	function getListByUserService($cuid = -1, $csid = -1)
	{
		$query = "SELECT * FROM " . $this->_tablename . " WHERE cu_id=" . (int) $cuid . " AND cs_id=" . (int) $csid;
		$query .= " ORDER BY start_time";
		$_result = $this->_getList($query);

		return $_result;
	}

	function getById($cpid = -1)
	{
		$query = "SELECT p.*, s.description, j.name FROM " . $this->_tablename . " p, #__asinustimetracking_user u, #__asinustimetracking_services s, #__users j WHERE p.cp_id=" . (int) $cpid;
		$query .= " AND p.cu_id=u.cuid AND p.cs_id = s.csid AND j.id=u.uid";
		$_result = $this->_getList($query);

		if (array_key_exists(0, $_result))
		{
			return $_result[0];
		}
		else
		{
			$priceRange = new stdClass();
			$priceRange->name = '';
			$priceRange->description = '';
			$priceRange->start_time = '';
			$priceRange->price = 0;
			return $priceRange;
		}
	}

	function create($start_date = '', $end_date = '', $price = '', $cuid = -1, $csid = -1)
	{
		$db    = JFactory::getDBO();
		$sdate = JFactory::getDate($start_date);
		$edate = JFactory::getDate($end_date);

		$query = "INSERT INTO " . $this->_tablename . " (start_time, end_time, price, cu_id, cs_id) VALUES (" . $db->quote($sdate->toSQL()) . "," . $db->quote($edate->toSQL()) . "," . (float) $price . ", " . (int) $cuid . ", " . (int) $csid . " )";

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}
	}

	/**
	 * Delete Pricerange
	 *
	 * @param int $id
	 */
	function remove($id = -1)
	{
		$db = JFactory::getDBO();

		$query = "DELETE FROM " . $this->_tablename . " WHERE cp_id=" . (int) $id;

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

	}

	/**
	 * Merge PriceRange
	 *
	 * @param $cpid
	 * @param $start_date
	 * @param $end_date
	 * @param $price
	 */
	function merge($cpid = -1, $start_date = '', $end_date = '', $price = '')
	{
		$db    = JFactory::getDBO();
		$sdate = JFactory::getDate($start_date);
		$edate = JFactory::getDate($end_date);

		$query = "UPDATE " . $this->_tablename . " SET "
			. " start_time=" . $db->quote($sdate->toSQL(), false)
			. ", end_time=" . $db->quote($edate->toSQL(), false)
			. ", price=" . (float) $price
			. " WHERE cp_id=" . (int) $cpid;

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError(500, $db->getErrorMsg());

			return false;
		}

	}

}