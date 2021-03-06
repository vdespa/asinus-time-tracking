<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010 - 2011, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

/**
 * Model of timetrack report
 */
class AsinusTimeTrackingModelTimeTrackList extends JModelLegacy
{

	/**
	 *
	 *Gets list of user's entries
	 */
	function getEntriesList($uid, $max = 0, $startdate, $enddate, $service = -1,
		$selection = -1, $costunit = -1, $day = 1)
	{
		$database = &JFactory::getDBO();

		$query = "SELECT ct_id, cs_id, cg_id,"
			. " UNIX_TIMESTAMP(entry_date) as entry_date,"
			. " UNIX_TIMESTAMP(start_time) as start_time,"
			. " UNIX_TIMESTAMP(end_time) as end_time,"
			. " UNIX_TIMESTAMP(start_pause) as start_pause,"
			. " UNIX_TIMESTAMP(end_pause) as end_pause,"
			. " UNIX_TIMESTAMP(timestamp) as timestamp, e.qty,s.is_worktime, e.remark, e.cc_id, u.* "
			. " from #__asinustimetracking_entries e, #__asinustimetracking_services s, #__asinustimetracking_userservices u "
			. " where e.cs_id = s.csid AND (u.cu_id = e.cu_id AND u.csid=e.cs_id) AND u.cu_id =" . (int) $uid;

		if ($service >= 0)
		{
			$query .= ' AND s.csid=' . (int) $service;
		}

		if ($costunit >= 0)
		{
			$query .= ' AND e.cc_id=' . (int) $costunit;
		}

		if ($selection >= 0)
		{
			$query .= ' AND e.cg_id=' . (int) $selection;
		}

		if ($startdate > 0)
		{
			$query .= " AND UNIX_TIMESTAMP(entry_date) >= " . (int) $startdate;
		}

		if ($enddate > 0)
		{
			$query .= " AND UNIX_TIMESTAMP(entry_date) <= " . (int) $enddate;
		}

		$query .= " ORDER BY entry_date DESC, s.is_worktime DESC, cs_id ASC";

		if ($max > 0)
		{
			$query .= " LIMIT " . (int) $max;
		}

		$database->setQuery($query);
		$result = $database->loadObjectList();

		// replace alternative prices if exist
		for ($i = 0; $i < count($result); $i++)
		{
			$prices = $this
				->_getPriceRangeValue($uid, $result[$i]->cs_id,
					$result[$i]->entry_date);
			if ($prices != null)
			{
				$result[$i]->price = $prices->price;
			}
		}

		return $result;
	}

	function _getPriceRangeValue($cuid = -1, $csid = -1, $aktdate)
	{
		$query = "SELECT * FROM #__asinustimetracking_pricerange" . " WHERE cu_id="
			. (int) $cuid . " AND cs_id=" . (int) $csid
			. " AND UNIX_TIMESTAMP(start_time) <= " . (int) $aktdate
			. " AND UNIX_TIMESTAMP(end_time) >= " . (int) $aktdate;

		$_result = $this->_getList($query);

		return $_result[0];
	}

	/**
	 * Get timetrack user object
	 *
	 * @return mixed
	 */
	function getCtUser()
	{
		$user = JFactory::getUser();

		$query = 'SELECT * FROM #__asinustimetracking_user WHERE uid=' . (int) $user->id;

		$_result = $this->_getlist($query);

		return $_result[0];

	}

	/**
	 * Get the list of days
	 */
	function getEntriesDays($uid, $max, $startdate, $enddate, $costUnit = -1)
	{
		$database = JFactory::getDBO();

		$query = "SELECT entry_date FROM #__asinustimetracking_entries WHERE cu_id=" . (int) $uid;

		if ($startdate > 0)
		{
			$query .= " AND UNIX_TIMESTAMP(entry_date) >= " . (int) $startdate;
		}

		if ($enddate > 0)
		{
			$query .= " AND UNIX_TIMESTAMP(entry_date) <= " . (int) $enddate;
		}

		if ($costUnit >= 0)
		{
			$query .= " AND cc_id=" . (int) $costUnit;
		}

		$query .= " GROUP BY entry_date ORDER BY entry_date DESC";

		if ($max > 0)
		{
			$query .= " LIMIT " . (int) $max;
		}

		$database->setQuery($query);
		$result = $database->loadObjectList();

		return $result;

	}

	/**
	 * get all times of a day
	 *
	 * @return object of(description, price, start_time, end_time, timevalue, start_pause, end_pause, pausevalue
	 */
	function getDayTimes($uid, $date, $costUnit = -1)
	{
		$database = JFactory::getDBO();

		$query = "SELECT s.description, s.csid, (SELECT u.price FROM #__asinustimetracking_userservices AS u WHERE u.csid = s.csid AND u.cu_id=e.cu_id ) as price,"
			. " start_time, end_time, sum(((MINUTE(end_time) / 60 + HOUR ( end_time)) - (MINUTE(start_time) / 60 + HOUR(start_time)))) AS timevalue,"
			. " start_pause, end_pause, sum(((MINUTE(end_pause) / 60 + HOUR (end_pause)) - (MINUTE(start_pause) / 60 + HOUR(start_pause)))) AS pausevalue"
			. " FROM #__asinustimetracking_entries e, #__asinustimetracking_services s"
			. " WHERE e.cs_id = s.csid AND e.cu_id =" . (int) $uid . " AND entry_date = '"
			. (int) $date . "' AND s.is_worktime=1 ";

		if ($costUnit >= 0)
		{
			$query .= " AND cc_id=" . (int) $costUnit;
		}

		$query .= " GROUP BY e.entry_date, cs_id ORDER BY s.description ASC, cs_id ASC";

		$database->setQuery($query);
		$result = $database->loadObjectList();

		// replace alternative prices if exist
		for ($i = 0; $i < count($result); $i++)
		{
			$prices = $this
				->_getPriceRangeValue($uid, $result[$i]->csid, strtotime($date));
			if ($prices != null)
			{
				$result[$i]->price = $prices->price;
			}
		}

		return $result;
	}

	/**
	 * Get Services of a day by user
	 *
	 * @return object description, price, qty, nsum
	 */
	function getDayServices($uid, $date, $costUnit = -1)
	{

		$database = JFactory::getDBO();

		$query = "SELECT ( SELECT u.price FROM #__asinustimetracking_userservices AS u"
			. " WHERE u.csid = s.csid AND u.cu_id=e.cu_id ) AS price,"
			. " s.description, s.csid, SUM(e.qty ) as qty "
			. " FROM #__asinustimetracking_entries e, #__asinustimetracking_services s"
			. " WHERE e.cs_id = s.csid AND e.cu_id=". (int) $uid. " AND"
			. " entry_date = '" . (int) $date . "' AND" . " s.is_worktime=0";

		if ((int) $costUnit >= 0)
		{
			$query .= " AND cc_id=" . (int) $costUnit;
		}
		$query .= " GROUP BY e.entry_date, cs_id"
				. " ORDER BY s.description ASC, cs_id ASC";

		$database->setQuery($query);
		$result = $database->loadObjectList();

		// replace alternative prices if exist
		for ($i = 0; $i < count($result); $i++)
		{
			$prices = $this->_getPriceRangeValue($uid, $result[$i]->csid, $date);
			if ($prices != null)
			{
				$result[$i]->price = $prices->price;
			}
		}

		return $result;
	}

	/**
	 * Get selection/project by id
	 *
	 * @param int $id
	 *
	 * @return object selection
	 */
	function getSelectionById($id)
	{
		$query = "SELECT * FROM #__asinustimetracking_selection WHERE cg_id=" . (int) $id;

		$_result = $this->_getList($query);

		return $_result[0];
	}

	/**
	 * Get service by id
	 */

	function getServiceById($sid)
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_services where csid=" . (int) $sid;

		$database->setQuery($query);
		$result = $database->loadObject();

		return $result;

	}

	/**
	 * Get list of services assigned to user
	 */
	function getServicesListByUser($uid)
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_userservices u, #__asinustimetracking_services s " .
				 "WHERE u.csid = s.csid AND u.cu_id=" . (int) $uid;

		$database->setQuery($query);

		$result = $database->loadObjectList();

		return $result;

	}

	/**
	 * Get list of selections
	 */
	function getSelectionsList()
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_selection";

		$database->setQuery($query);

		$result = $database->loadObjectList();

		return $result;
	}

	function getCostUnitsList()
	{
		$database = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_costunit ORDER BY cc_id";

		$database->setQuery($query);

		$result = $database->loadObjectList();

		return $result;

	}

	function getCostUnitById($id)
	{
		$query = "SELECT * from #__asinustimetracking_costunit where cc_id=" . (int) $id;

		$_result = $this->_getList($query);

		return $_result[0];
	}
}
