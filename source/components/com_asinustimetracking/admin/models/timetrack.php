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

jimport('joomla.application.component.modellist');

/**
 * Model of timetrack list view
 */
class AsinusTimeTrackingModelTimeTrack extends JModelList
{

	/**
	 * Gets list of user's entries
	 *
	 * @param int $uid			   user id
	 * @param int $max			   max age of entries
	 * @param timestamp $startdate date of start
	 * @param timestamp $enddate   date of end
	 * @param int $service 		   service id
	 * @param int $selection 	   selection id
	 * @param int $costUnit 	   costunit id
	 *
	 * @return array object of entries
	 */
	function getEntriesList($uid, $max = 0, $startdate, $enddate, $service, $selection, $costUnit = -1)
	{
		$db = JFactory::getDBO();

		$query = "SELECT ct_id, cs_id, cg_id, e.cc_id, UNIX_TIMESTAMP(entry_date) as entry_date," . " UNIX_TIMESTAMP(start_time) as start_time,"
			. " UNIX_TIMESTAMP(end_time) as end_time, UNIX_TIMESTAMP(start_pause) as start_pause, UNIX_TIMESTAMP(end_pause) as end_pause,"
			. " UNIX_TIMESTAMP(timestamp) as timestamp, e.qty,s.is_worktime, e.remark, u.* "
			. " from #__asinustimetracking_entries e, #__asinustimetracking_services s, #__asinustimetracking_userservices u "
			. " where e.cs_id = s.csid AND (u.cu_id = e.cu_id AND u.csid=e.cs_id) AND u.cu_id =" . (int) $uid;

		if ($service >= 0) {
			$query .= " AND s.csid=" . (int) $service;
		}

		if ($selection >= 0) {
			$query .= " AND e.cg_id=" . (int) $selection;
		}

		if ($costUnit >= 0) {
			$query .= " AND e.cc_id=" . (int) $costUnit;
		}

		if ($startdate > 0) {
			$query .= " AND UNIX_TIMESTAMP(entry_date) >= " . (int) $startdate;
		}

		if ($enddate > 0) {
			$query .= " AND UNIX_TIMESTAMP(entry_date) <= " . (int) $enddate;
		}

		$query .= " ORDER BY entry_date DESC, s.is_worktime DESC, cs_id ASC";

		if ($max > 0) {
			$query .= " LIMIT " . (int) $max;
		}

		$db->setQuery($query);
		$result = $db->loadObjectList();

		// replace alternative prices if exist
		for ($i = 0; $i < count($result); $i++) {
			$prices = $this->_getPriceRangeValue($uid, $result[$i]->cs_id, $result[$i]->entry_date);
			if ($prices != null) {
				$result[$i]->price = $prices->price;
			}
		}

		return $result;
	}

	function _getPriceRangeValue($cuid = -1, $csid = -1, $aktdate)
	{
		$query = "SELECT * FROM #__asinustimetracking_pricerange" . " WHERE cu_id=" . (int) $cuid . " AND cs_id=" . (int) $csid
			. " AND UNIX_TIMESTAMP(start_time) <= " . (int) $aktdate . " AND UNIX_TIMESTAMP(end_time) >= " . (int) $aktdate;

		$_result = $this->_getList($query);

		if (array_key_exists(0, $_result))
		{
			return $_result[0];
		}
		else
		{
			return null;
		}

	}

	function getUserList()
	{

		$query = "SELECT * FROM #__asinustimetracking_user c INNER JOIN #__users u ON u.id=c.uid";
		$_result = $this->_getList($query);

		return $_result;

	}

	function getCtUserById($id)
	{
		$query = "SELECT * FROM #__asinustimetracking_user c INNER JOIN #__users u ON u.id=c.uid WHERE c.cuid=" . (int) $id;
		$_result = $this->_getList($query);

		if ($_result)
		{
			return $_result[0];
		}
		else {
			$ctUser = new stdClass();
			$ctUser->name = '';
			return $ctUser;
		}
	}

	/**
	 * Get logged in ctUser
	 */
	function getCtUser()
	{
		$user = JFactory::getUser();

		$query = "SELECT * FROM #__asinustimetracking_user WHERE uid=" . (int) $user->id;

		$_result = $this->_getlist($query);

		if ($_result)
		{
			return $_result[0];
		}
		else {
			$ctUser = new stdClass();
			$ctUser->cuid = 0;
			return $ctUser;
		}
	}

	function getSelectionById($id)
	{
		$query = 'SELECT * FROM #__asinustimetracking_selection WHERE cg_id=' . (int) $id;

		$_result = $this->_getList($query);

		if (array_key_exists(0, $_result))
		{
			return $_result[0];
		} else {
			$selection = new stdClass();
			$selection->description = '';
			return $selection;
		}
	}

	/**
	 * Get list of selections
	 */
	function getSelectionsList()
	{
		$db = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_selection";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Get list of services
	 */
	function getServicesList()
	{
		$db = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_services";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	function getRolesList()
	{
		$db = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_roles";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;

	}

	/**
	 * Get service by id
	 */

	function getServiceById($sid)
	{
		$db = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_services where csid=" . (int) $sid;

		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;

	}

	function getCostUnitsList()
	{
		$db = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_costunit ORDER BY cc_id";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;

	}

	function getCostUnitById($id)
	{
		$db = JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_costunit where cc_id=" . (int) $id;

		$_result = $this->_getList($query);

		return $_result[0];
	}

}