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
 * Model of timetrack edit view
 */
class AsinusTimeTrackingModelTimeTrackedit extends JModelLegacy
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
			. " EXTRACT(HOUR FROM end_pause) as h_pause,"
			. " EXTRACT(MINUTE FROM end_pause) as m_pause,"
			. " remark FROM $this->_tablename" . " WHERE ct_id=" . (int) $id;
		$_result = $this->_getList($query);

		return $_result[0];
	}

	function remove($id)
	{
		$db = JFactory::getDBO();
		$query = "DELETE FROM $this->_tablename WHERE ct_id=" . (int) $id;

		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}

		JFactory::getApplication()->enqueueMessage(
			JText::_('COM_ASINUSTIMETRACKING_TIMETRACK_DELETED_SUCCESS_MSG'), 'message'
		);

	}

	/*
	 * Get logged in ctUser
	 */
	function getCtUser()
	{
		$user = JFactory::getUser();

		$query = "SELECT * FROM #__asinustimetracking_user WHERE uid=" . (int) $user->id;

		$_result = $this->_getlist($query);

		return $_result[0];

	}

	/**
	 * Get list of selections
	 */
	function getSelectionsList()
	{
		$db = JFactory::getDBO();

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
		$db = JFactory::getDBO();

		$query = 'SELECT * from #__asinustimetracking_userservices u, #__asinustimetracking_services s ' .
				 'WHERE u.csid = s.csid AND u.cu_id='. (int) $uid . ' ' .
				 'ORDER BY s.csid';

		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;

	}

	function getServiceById($sid)
	{
		$db = JFactory::getDBO();

		$query = 'SELECT * from #__asinustimetracking_services where csid=' . (int) $sid;

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