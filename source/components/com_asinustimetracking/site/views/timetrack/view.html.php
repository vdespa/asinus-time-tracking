<?php
/**
 * TimeTrack, Frontend Component
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

jimport('joomla.application.component.view');

class AsinusTimeTrackingViewTimeTrack extends JView
{

 function display($tpl = null)
    {
		// Notify user that he needs to be logged in
		$user = JFactory::getUser();
		if ($user->guest === 1)
		{
			$app = JFactory::getApplication();
			$message = 'You cannot access this page as Guest. Please login.';
			$app->enqueueMessage(JText::_($message), 'error');
		}
		else
		{


        JHTML::_('script', 'asinustimetracking.js', 'components/com_asinustimetracking/assets/js/');

        // Max Age
        $str_age = '-1 day';
        // Maximal age of entry to edit
        $this->maxage = strtotime($str_age);

        $this->model = $this->getModel();
        $this->ctUser = $this->model->getCtUser();
        $this->user = JFactory::getUser();

        // Entry
        $this->ctid = JRequest::getInt('ct_id', 0);
        if ($this->ctid == 'undefined') {
            $this->ctid = 0;
        }
        if ($this->ctid) {
            $this->editEntry = $this->model->getEntryById((int) $this->ctid);
            $this->ctEntryDate = $this->editEntry->entry_date;
        }
        else {
            $this->ctEntryDate = date(time('d.m.Y'));
			// HOTFIX
			$this->editEntry = new stdClass();
			$this->editEntry->cs_id = 0;
			$this->editEntry->cg_id = 0;
			$this->editEntry->ct_id = 0;
			$this->editEntry->start_time = 0;
			$this->editEntry->end_time = 0;
			$this->editEntry->start_pause = 0;
			$this->editEntry->end_pause = 0;
			$this->editEntry->qty = 0;
			$this->editEntry->remark = '';
        }

        // Lists
		//HOTFIX
		$maxage = null;

        $this->servicesList = $this->model->getServicesListByUser($this->ctUser->cuid);
		if (! $this->servicesList)
		{
			$app = JFactory::getApplication()->enqueueMessage('You user has no assignd a price and service. Saving is not possible, please notify your Administrator.', 'error');
		}
        $this->selectionsList = $this->model->getSelectionsList();
        $this->entriesList = $this->model->getEntriesList($this->ctUser->cuid, $maxage);
        $this->costList = $this->model->getCostUnitsList();

        parent::display($tpl);

		}

    }

}

?>