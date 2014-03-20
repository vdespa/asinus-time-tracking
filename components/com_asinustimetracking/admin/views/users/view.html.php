<?php
/**
 * @package		TimeTrack
 * @version 	$Id: view.html.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

class AsinusTimeTrackingViewUsers extends JView{
	function display($tpl = null) {
		JToolBarHelper::title( JText::_('TimeTrack Benutzer'), 'generic.png');
		JToolBarHelper :: custom('overview','ctoverview.png','ctoverview.png', JText::_('COM_ASINUSTIMETRACKING_OVERVIEW'),false);
		JToolBarHelper :: spacer();
		JToolBarHelper :: custom('users','ctuser.png','ctuser.png', JText::_('COM_ASINUSTIMETRACKING_USER'),false);
		JToolBarHelper :: custom('services','ctservice.png','ctservice.png', JText::_('COM_ASINUSTIMETRACKING_SERVICES'),false);
		JToolBarHelper :: custom('roles','ctroles.png','ctroles.png', JText::_('COM_ASINUSTIMETRACKING_USERROLES'),false);
		JToolBarHelper :: custom('selections','ctselection.png','ctselection.png', JText::_('COM_ASINUSTIMETRACKING_PROJECTS'),false);
		JToolBarHelper :: custom('costunits', 'costunit', 'costunit', JText::_('COM_ASINUSTIMETRACKING_COSTUNITS'), false);
		
		$items		= $this->get( 'Users' );
		$model 		= $this->getModel();

		$this->assignRef('items', $items);
		$this->assignRef('model', $model);

		parent::display($tpl);

	}
}