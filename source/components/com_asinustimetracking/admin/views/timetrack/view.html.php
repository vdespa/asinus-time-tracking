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

require_once(JPATH_COMPONENT . '/models/preferences.php');

class AsinusTimeTrackingViewTimeTrack extends JViewLegacy
{
	protected $model;
	protected $cfg;
	protected $ctUser;
	protected $ctUlist;
	protected $ctSllist;
	protected $ctSvlist;
	protected $ctRlist;
	protected $ctCostUnit;
	protected $ctStartDate;
	protected $ctEndDate;

	/**
	 * @inheritdoc
	 */
	function display($tpl = null)
	{
		if (AsinustimetrackingBackendHelper::isLegacyVersion() === true)
		{
			$this->displayLegacy();
			return true;
		}

		// Initialize variables
		$cfg_model = new AsinusTimeTrackingModelPreferences;
		$this->cfg       = $cfg_model->getPreferences();

		$this->model    = $this->getModel();
		$this->ctUser   = $this->model->getCtUser();
		$this->ctUlist  = JRequest::getInt('ct_ulist', $this->ctUser->cuid);
		$this->ctSllist = JRequest::getInt('ct_sllist', -1);
		$this->ctSvlist = JRequest::getInt('ct_svlist', -1);
		$this->ctRlist  = JRequest::getInt('ct_rlist', -1);
		$this->ctCostUnit    = JRequest::getInt('ct_costunit', -1);

		$this->ctStartDate = JRequest::getVar('ct_startdate', null);
		$this->ctEndDate   = JRequest::getVar('ct_enddate', null);

		// Set date selection
		$this->ctStartDate = JRequest::getVar('ct_startdate', null);
		if (!$this->ctStartDate)
		{
			$this->ctStartDate = strtotime(date('Y-m') . '-' . $this->cfg['first_day']);
		}
		else
		{
			$this->ctStartDate = strtotime($this->ctStartDate) ? strtotime($this->ctStartDate)
				: $this->ctStartDate;
		}

		$this->ctEndDate = JRequest::getVar('ct_enddate', null);
		if (!$this->ctEndDate)
		{
			$this->ctEndDate = time();
		}
		else
		{
			$this->ctEndDate = strtotime($this->ctEndDate) ? strtotime($this->ctEndDate)
				: $this->ctEndDate;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * @inheritdoc
	 */
	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_TIMETRACK'), 'users');
		JToolBarHelper::deleteList(JText::_('COM_ASINUSTIMETRACKING_Q_REMOVE'), 'removeentry', JText::_('COM_ASINUSTIMETRACKING_REMOVE'));
		JToolBarHelper::preferences('com_asinustimetracking');
	}

	/**
	 * Deprecated display method
	 *
	 * @deprecated
	 * @param null|string $tpl
	 */
	function displayLegacy($tpl = 'legacy')
	{
		$cfg_model = new AsinusTimeTrackingModelPreferences;
		$cfg       = $cfg_model->getPreferences();

		$model    = $this->getModel();
		$ctUser   = $model->getCtUser();
		$ctUlist  = JRequest::getInt('ct_ulist', $ctUser->cuid);
		$ctSllist = JRequest::getInt('ct_sllist', -1);
		$ctSvlist = JRequest::getInt('ct_svlist', -1);
		$ctRlist  = JRequest::getInt('ct_rlist', -1);
		$ct_cc    = JRequest::getInt('ct_costunit', -1);

		$ct_startdate = JRequest::getVar('ct_startdate', null);
		$ct_enddate   = JRequest::getVar('ct_enddate', null);

		// Set date selection
		$ct_startdate = JRequest::getVar('ct_startdate', null);
		if (!$ct_startdate)
		{
			//$ct_startdate = strtotime("first day");
			$ct_startdate = strtotime(date('Y-m') . '-' . $cfg['first_day']);
		}
		else
		{
			$ct_startdate = strtotime($ct_startdate) ? strtotime($ct_startdate)
				: $ct_startdate;
		}
		$this->assignref('ctStartDate', $ct_startdate);

		$ct_enddate = JRequest::getVar('ct_enddate', null);
		if (!$ct_enddate)
		{
			$ct_enddate = time();
		}
		else
		{
			$ct_enddate = strtotime($ct_enddate) ? strtotime($ct_enddate)
				: $ct_enddate;
		}
		$this->assignref('ctEndDate', $ct_enddate);

		$this->assignRef('model', $model);
		$this->assignRef('ctUser', $ctUser);
		$this->assignRef('ctUlist', $ctUlist);
		$this->assignRef('ctSllist', $ctSllist);
		$this->assignRef('ctSvlist', $ctSvlist);
		$this->assignref('ctRlist', $ctRlist);
		$this->assignref('ctCostUnit', $ct_cc);

		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_TIMETRACK'),
			'generic.png');
		JToolBarHelper::deleteList(JText::_('COM_ASINUSTIMETRACKING_Q_REMOVE'),
			'removeentry', JText::_('COM_ASINUSTIMETRACKING_REMOVE'));

		$bar = JToolBar::getInstance('toolbar');

		// XXX: date selection
		/*
		$bar
			->appendButton('link', 'archive', JText::_('COM_ASINUSTIMETRACKING_CSV'),
				"index.php?option=com_asinustimetracking&format=csv&ct_ulist=$ctUlist&ct_sllist=$ctSllist&ct_svlist=$ctSvlist&ct_startdate=$ct_startdate&ct_enddate=$ct_enddate&ct_rlist=$ctRlist&ct_cc=$ct_cc");
		*/

		$bar->appendButton('link', 'archive', JText::_('COM_ASINUSTIMETRACKING_MONTHLYREPORT'),
			"index.php?option=com_asinustimetracking&view=monthlyreport");

		JToolBarHelper::spacer();
		JToolBarHelper::custom('preferences', 'archive', 'archive',
			JText::_('COM_ASINUSTIMETRACKING_PREFERENCES'), false);
		JToolBarHelper::preferences('com_asinustimetracking');
		$user = JFactory::getUser();
		$this->assignRef('user', $user);

		$this->assignRef('cfg', $cfg);

		parent::display($tpl);

	}

}
