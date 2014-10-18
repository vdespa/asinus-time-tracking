<?php
/**
 * TimeTrack, Backend Component
 *
 * PHP version 5
 *
 * @category  Component
 * @package   TimeTrack
 * @author    Ralf Nickel <info@itrn.de>
 * @copyright 2011 Ralf Nickel
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version   SVN: $Id$
 * @link      http://www.itrn.de
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Model of timetrack list view
 *
 * @category Class
 * @package  TimeTrack
 * @author   Ralf Nickel <rn@itrn.de>
 * @license  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.itrn.de
 */
class AsinusTimeTrackingModelTimeTrack extends JModel
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

		//"select * from jos_timetrack_entries e, jos_timetrack_user u, jos_timetrack_userservices s where e.cu_id=91 AND e.cu_id = u.cuid AND (e.cu_id = s.cu_id AND e.cs_id = s.csid)"

		$query = "SELECT ct_id, cs_id, cg_id, e.cc_id, UNIX_TIMESTAMP(entry_date) as entry_date," . " UNIX_TIMESTAMP(start_time) as start_time,"
			. " UNIX_TIMESTAMP(end_time) as end_time, UNIX_TIMESTAMP(start_pause) as start_pause, UNIX_TIMESTAMP(end_pause) as end_pause,"
			. " UNIX_TIMESTAMP(timestamp) as timestamp, e.qty,s.is_worktime, e.remark, u.* "
			. " from #__asinustimetracking_entries e, #__asinustimetracking_services s, #__asinustimetracking_userservices u "
			. " where e.cs_id = s.csid AND (u.cu_id = e.cu_id AND u.csid=e.cs_id) AND u.cu_id =" . $uid;

		if ($service >= 0) {
			$query .= " AND s.csid=$service";
		}

		if ($selection >= 0) {
			$query .= " AND e.cg_id=$selection";
		}

		if ($costUnit >= 0) {
			$query .= " AND e.cc_id=$costUnit";
		}

		if ($startdate > 0) {
			$query .= " AND UNIX_TIMESTAMP(entry_date) >= " . $startdate;
		}

		if ($enddate > 0) {
			$query .= " AND UNIX_TIMESTAMP(entry_date) <= " . $enddate;
		}

		$query .= " ORDER BY entry_date DESC, s.is_worktime DESC, cs_id ASC";

		if ($max > 0) {
			$query .= " LIMIT " . $max;
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
			. " AND UNIX_TIMESTAMP(start_time) <= " . $aktdate . " AND UNIX_TIMESTAMP(end_time) >= " . $aktdate;

		$_result = $this->_getList($query);

		return $_result[0];
	}

	function getUserList()
	{

		$query = "SELECT * FROM #__asinustimetracking_user c INNER JOIN #__users u ON u.id=c.uid";
		$_result = $this->_getList($query);

		return $_result;

	}

	function getCtUserById($id)
	{
		$query = "SELECT * FROM #__asinustimetracking_user c INNER JOIN #__users u ON u.id=c.uid WHERE c.cuid=$id";
		$_result = $this->_getList($query);

		return $_result[0];
	}

	/**
	 * Get logged in ctUser
	 */
	function getCtUser()
	{
		$user = JFactory::getUser();

		$query = "SELECT * FROM #__asinustimetracking_user WHERE uid=$user->id";

		$_result = $this->_getlist($query);

		return $_result[0];

	}

	function getSelectionById($id)
	{
		$query = "SELECT * FROM #__asinustimetracking_selection WHERE cg_id=$id";

		$_result = $this->_getList($query);

		return $_result[0];
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

		$query = "SELECT * from #__asinustimetracking_services where csid=" . $sid;

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

		$query = "SELECT * from #__asinustimetracking_costunit where cc_id=$id";

		$_result = $this->_getList($query);

		return $_result[0];
	}

}