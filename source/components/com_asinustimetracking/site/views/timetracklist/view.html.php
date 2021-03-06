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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class AsinusTimeTrackingViewTimeTrackList extends JViewLegacy
{

	function display($tpl = null)
	{
		die('x');
		// Config
		require_once(JPATH_COMPONENT . '/models/preferences.php');

		$cfg_model = new TimeTrackModelPreferences;
		$cfg = $cfg_model->getPreferences();

		$model 		=& $this->getModel();

		$ctUser 	= $model->getCtUser();

		$this->assignRef('model', $model);
		$this->assignRef('ctUser', $ctUser);

		$user 		=& JFactory::getUser();
		$this->assignRef('user', $user);

		$ct_cc = JRequest::getInt('ct_cc', 0);
		if(!$ct_cc){
			$ct_cc = -1;
		}
		$this->assignRef('ctcc', $ct_cc);

		// Set date selection
		$ct_startdate = JRequest::getVar('ct_startdate', null);
		if(!$ct_startdate){
			//$ct_startdate = strtotime("-1 Month");
			$ct_startdate = strtotime(date('Y-m') . '-' . $cfg['first_day']);
		} else {
			$ct_startdate = strtotime($ct_startdate);
		}
		$this->assignref('ctStartDate', $ct_startdate);

		$ct_enddate = JRequest::getVar('ct_enddate', null);
		if(!$ct_enddate){
			$ct_enddate = time();
		} else {
			$ct_enddate = strtotime($ct_enddate);
		}
		$this->assignref('ctEndDate', $ct_enddate);

		$this->assignRef('cfg', $cfg);

		// Lists
		$servicesList = $model->getServicesListByUser($ctUser->cuid);
		$this->assignRef('servicesList', $servicesList);

		$selectionList = $model->getSelectionsList();
		$this->assignRef('selectionsList', $selectionList);

		$entriesDays = $model->getEntriesDays($ctUser->cuid, 0, $ct_startdate, $ct_enddate, $ct_cc);
		$this->assignRef('entriesDays', $entriesDays);

		$costList = $model->getCostUnitsList();
		$this->assignRef('costList', $costList);

		parent::display($tpl);


	}

}

?>