<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 * @copyright      Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @copyright      Copyright (C) 2011, Informationstechnik Ralf Nickel
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die ('Restricted access');

jimport('joomla.application.component.controller');

class AsinusTimeTrackingController extends JController
{

	public function display($cachable = false, $safeurlparams = false)
	{
		// Check if user is guest
		if ($this->checkIfUserIsGuest() === true) {
			return false;
		}

		/*
		JRequest::setVar(JRequest::setVar('view', 'timetrack'));
		parent::display(true);
		*/

		// Set the default view name and format from the Request.
		$vName		= JRequest::getCmd('view', 'timetrack');
		JRequest::setVar('view', $vName);

		parent::display($cachable, $safeurlparams);

		return $this;
	}

	/**
	 * Check if user is guest and redirect to login.
	 *
	 * This is a fallback for the case of allowing public access to the frontend via ACL.
	 */
	protected function checkIfUserIsGuest()
	{
		// Notify user that he needs to be logged in
		$user = JFactory::getUser();
		if ($user->guest === 1) {
			// Redirect to login page.
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_ASINUSTIMETRACKING_ERROR_NO_GUEST_ALLOWED'), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
			return true;
		}
		return false;
	}



	function _checkAdminUser()
	{
		$db = JFactory::getDBO();
		$juser = JFactory::getUser();

		$query = "SELECT * FROM #__asinustimetracking_user WHERE uid ="
			. (int)$juser->id;

		$db->setQuery($query);

		$result = $db->loadObjectList();

		if (count($result)) {
			if ($result[0]->is_admin) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function _isMyEntry()
	{
		$ctid = JRequest::getInt('ct_id', -1);
		$model = $this->getModel('timetrack');
		$entry = $model->getEntryById($ctid);

		$user = $model->getCtUser();

		if ($user->cuid == $entry->cu_id) {
			return true;
		}
		return false;
	}

	function edit()
	{
		if ($this->_checkAdminUser() or $this->_isMyEntry()) {
			JRequest::setVar('view', 'timetrack');
			$this->display();
		} else {
			JError::raiseError(500, "Entry not found for User");
		}
	}

	function delete()
	{
		$db = JFactory::getDBO();

		$ctid = JRequest::getInt('ct_id', -1);

		$query = "DELETE FROM #__asinustimetracking_entries WHERE ct_id=" . (int)$ctid;

		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}

		//parent::display();
		$this
			->setRedirect(
				JRoute::_(
					"index.php?option=com_asinustimetracking&view=timetrack"));

	}

	function submit()
	{
		jimport('joomla.utilities.date');
		$usermodel = $this->getModel('timetrack');

		$user = $usermodel->getCtUser();
		//JFactory::getUser();

		$db = JFactory::getDBO();

		$ctid = JRequest::getInt('ct_id', -1);
		$entrydate = JRequest::getString('ct_entrydate', '');
		$service = JRequest::getString('ct_service', '');
		$sh = JRequest::getInt('ct_sh', 0);
		$sm = JRequest::getInt('ct_sm', 0);
		$eh = JRequest::getInt('ct_eh', 0);
		$em = JRequest::getInt('ct_em', 0);
		$psh = JRequest::getInt('ct_psh', 0);
		$psm = JRequest::getInt('ct_psm', 0);
		$peh = JRequest::getInt('ct_peh', 0);
		$pem = JRequest::getInt('ct_pem', 0);
		$qty = Jrequest::getFloat('ct_qty', 0);
		$cg = JRequest::getInt('ct_selection', 0);
		$remark = JRequest::getString('ct_remark', '');
		$costunit = JRequest::getInt('ct_costunit', -1);

		$fentrydate = new JDate($entrydate);

		if ($ctid == 0 or $ctid == 'undefined') {
			$query = "INSERT INTO #__asinustimetracking_entries
		(entry_date, cu_id, cs_id, cg_id, start_time, end_time, start_pause, end_pause, qty, remark, cc_id)"
				. " VALUES('" . $fentrydate->toMySQL() . "',"
				. (int)$user->cuid . "," . $db->quote($service) . ","
				. (int)$cg . ", '1970-1-1 " . (int)$sh . ":" . (int)$sm
				. ":00'" . ", '1970-1-1 " . (int)$eh . ":" . (int)$em
				. ":00'" . ", '1970-1-1 " . (int)$psh . ":" . (int)$psm
				. ":00'" . ", '1970-1-1 " . (int)$peh . ":" . (int)$pem
				. ":00'" . "," . (float)$qty . "," . $db->quote($remark)
				. "," . (int)$costunit . " )";
		} else {
			if ($this->_isMyEntry()) {
				$query = "UPDATE #__asinustimetracking_entries SET
				entry_date='" . $fentrydate->toMySQL()
					. "',
				cs_id=$service,
				cg_id=$cg,
				start_time='1970-1-1 $sh:$sm:00',
				end_time='1970-1-1 $eh:$em:00',
				start_pause='1970-1-1 $psh:$psm:00',
				end_pause='1970-1-1 $peh:$pem:00',
				qty=$qty,
				remark='$remark',
				cc_id=$costunit
				WHERE ct_id=" . (int)$ctid;

				JRequest::setVar('ct_id');
			} else {
				JError::raiseError(500, "Entry not found for User");
			}
		}

		$db->setQuery($query);

		if (!$db->query()) {
			JError::raiseError(500, $db->getErrorMsg());
			return false;
		}

		$this
			->setRedirect(
				JRoute::_(
					"index.php?option=com_asinustimetracking&view=timetrack"));
		//	parent::display(true);
	}

}