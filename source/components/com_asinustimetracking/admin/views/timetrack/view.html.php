<?php
/**
 * @package		TimeTrack
 * @version 	$Id: view.html.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class AsinusTimeTrackingViewTimeTrack extends JView
{
	function display($tpl = null)
	{
		require_once(JPATH_COMPONENT . DS . 'models' . DS . 'preferences.php');

		$cfg_model = new AsinusTimeTrackingModelPreferences;
		$cfg = $cfg_model->getPreferences();

		$model = $this->getModel();
		$ctUser = $model->getCtUser();
		$ctUlist = JRequest::getInt('ct_ulist', $ctUser->cuid);
		$ctSllist = JRequest::getInt('ct_sllist', -1);
		$ctSvlist = JRequest::getInt('ct_svlist', -1);
		$ctRlist = JRequest::getInt('ct_rlist', -1);
		$ct_cc = JRequest::getInt('ct_costunit', -1);

		$ct_startdate = JRequest::getVar('ct_startdate', null);
		$ct_enddate = JRequest::getVar('ct_enddate', null);

		// Set date selection
		$ct_startdate = JRequest::getVar('ct_startdate', null);
		if (!$ct_startdate) {
			//$ct_startdate = strtotime("first day");
			$ct_startdate = strtotime(date('Y-m') . '-' . $cfg['first_day']);
		}
		else {
			$ct_startdate = strtotime($ct_startdate) ? strtotime($ct_startdate)
				: $ct_startdate;
		}
		$this->assignref('ctStartDate', $ct_startdate);

		$ct_enddate = JRequest::getVar('ct_enddate', null);
		if (!$ct_enddate) {
			$ct_enddate = time();
		}
		else {
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
		JToolBarHelper::deleteListx(JText::_('COM_ASINUSTIMETRACKING_Q_REMOVE'),
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
		JToolBarHelper::custom('overview', 'ctoverview.png', 'ctoverview.png',
			JText::_('COM_ASINUSTIMETRACKING_OVERVIEW'), false);
		JToolBarHelper::spacer();
		JToolBarHelper::custom('users', 'ctuser.png', 'ctuser.png',
			JText::_('COM_ASINUSTIMETRACKING_USER'), false);
		JToolBarHelper::custom('services', 'ctservice.png', 'ctservice.png',
			JText::_('COM_ASINUSTIMETRACKING_SERVICES'), false);
		JToolBarHelper::custom('roles', 'ctroles.png', 'ctroles.png',
			JText::_('COM_ASINUSTIMETRACKING_USERROLES'), false);
		JToolBarHelper::custom('selections', 'ctselection.png', 'ctselection.png',
			JText::_('COM_ASINUSTIMETRACKING_PROJECTS'), false);
		JToolBarHelper::custom('costunits', 'costunit', 'costunit',
			JText::_('COM_ASINUSTIMETRACKING_COSTUNITS'), false);
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

?>