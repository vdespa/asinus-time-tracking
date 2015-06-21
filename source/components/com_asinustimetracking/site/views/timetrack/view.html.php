<?php
/**
 * @package        Joomla.Site
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2015, Valentin Despa. All rights reserved.
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

jimport('joomla.application.component.view');

// Load helper.
require_once JPATH_SITE . '/components/com_asinustimetracking/helpers/AsinustimetrackingHelper.php';

class AsinusTimeTrackingViewTimeTrack extends JViewLegacy
{
	protected $state;

	protected $maxEditInDays;

	protected $settings;

	protected $imagesLocation;

	function display($tpl = null)
	{
		// Settings
		$this->settings                = new stdClass();
		$this->settings->show_quantity = AsinustimetrackingHelper::getParameter('show_quantity', 0);
		$this->maxEditInDays           = AsinustimetrackingHelper::getParameter('record_max_edit_days', 2);

		// Images
		$this->imagesLocation = JURI::base() . 'components/com_asinustimetracking/assets/images/';

		$this->state = $this->get('State');
		$this->items = $this->get('Items');

		JHTML::_('script', 'asinustimetracking.js', 'components/com_asinustimetracking/assets/js/');

		// Max Age
		$str_age = '-1 day';
		// Maximal age of entry to edit
		$this->maxage = strtotime($str_age);

		$this->model  = $this->getModel();
		$this->ctUser = $this->model->getCtUser();
		$this->user   = JFactory::getUser();

		// Entry
		$this->ctid = JRequest::getInt('ct_id', 0);
		if ($this->ctid == 'undefined')
		{
			$this->ctid = 0;
		}
		if ($this->ctid)
		{
			$this->editEntry   = $this->model->getEntryById((int) $this->ctid);
			$this->ctEntryDate = $this->editEntry->entry_date;
		}
		else
		{
			$this->ctEntryDate = date(time('d.m.Y'));
			// HOTFIX
			$this->editEntry              = new stdClass();
			$this->editEntry->cs_id       = 0;
			$this->editEntry->cg_id       = 0;
			$this->editEntry->ct_id       = 0;
			$this->editEntry->cc_id       = 0;
			$this->editEntry->start_time  = strtotime('1970-01-01 00:00');
			$this->editEntry->end_time    = strtotime('1970-01-01 00:00');
			$this->editEntry->start_pause = strtotime('1970-01-01 12:00');
			$this->editEntry->end_pause   = strtotime('1970-01-01 12:45');
			$this->editEntry->qty         = 0;
			$this->editEntry->remark      = '';
		}

		// Lists
		//HOTFIX
		$maxage = null;

		$this->servicesList = $this->model->getServicesListByUser($this->ctUser->cuid);
		if (!$this->servicesList)
		{
			JFactory::getApplication()->enqueueMessage('Your user has no assigned a price and service. Saving is not possible, please notify your Administrator.', 'error');

			return;
		}
		$this->selectionsList = $this->model->getSelectionsList();
		$this->entriesList    = $this->model->getEntriesList($this->ctUser->cuid, $maxage);
		$this->costList       = $this->model->getCostUnitsList();

		parent::display($tpl);
	}
}
