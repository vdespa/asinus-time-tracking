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
 * Model of timetrack edit view
 *
 * @category Class
 * @package  TimeTrack
 * @author   Ralf Nickel <rn@itrn.de>
 * @license  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link     http://www.itrn.de
 */
class AsinusTimeTrackingModelTimeTrackedit extends JModel
{
	var $_tablename = '#__asinustimetracking_entries';

	function getById($id = 0)
	{
		$query = "SELECT ct_id, qty, cu_id, cs_id, cg_id, cc_id,"
			. " UNIX_TIMESTAMP(entry_date) as entry_date,"
			. " UNIX_TIMESTAMP(start_time) as start_time,"
			. " UNIX_TIMESTAMP(end_time) as end_time,"
			. " UNIX_TIMESTAMP(start_pause) as start_pause,"
			. " UNIX_TIMESTAMP(end_pause) as end_pause,"
			. " remark FROM $this->_tablename" . " WHERE ct_id=" . $id;
		$_result = $this->_getList($query);

		return $_result[0];
	}

	function remove($id)
	{
		$db = JFactory::getDBO();
		$query = "DELETE FROM $this->_tablename WHERE ct_id=$id";

		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}

		JError::raiseNotice(100, 'Eintrag gelöscht');

	}

	/*
	 * Get logged in ctUser
	 */
	function getCtUser()
	{
		$user = &JFactory::getUser();

		$query = "SELECT * FROM #__asinustimetracking_user WHERE uid=$user->id";

		$_result = $this->_getlist($query);

		return $_result[0];

	}

	/**
	 * Get list of selections
	 */
	function getSelectionsList()
	{
		$db = &JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_selection ORDER BY cg_id";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 *
	 * Get list of services assigned to user
	 */
	function getServicesListByUser($uid)
	{
		$db = &JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_userservices u, #__asinustimetracking_services s WHERE u.csid = s.csid AND u.cu_id="
			. $uid . " ORDER BY s.csid";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;

	}

	function getServiceById($sid)
	{
		$db = &JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_services where csid=" . $sid;

		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;

	}

	function getCostUnitsList()
	{
		$db = &JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_costunit ORDER BY cc_id";

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;

	}

	function getCostUnitById($id)
	{
		$db = &JFactory::getDBO();

		$query = "SELECT * from #__asinustimetracking_costunit where cc_id=$id";

		$_result = $this->_getList($query);

		return $_result[0];
	}

}

?>