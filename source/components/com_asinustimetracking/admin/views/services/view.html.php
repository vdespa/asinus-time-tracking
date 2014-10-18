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

class AsinusTimeTrackingViewServices extends JView{
	function display($tpl = null) {
		JToolBarHelper::title( JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_SERVICES'), 'generic.png');
		JToolBarHelper :: addNewX('servicesedit', JText::_("COM_ASINUSTIMETRACKING_NEW"));
		JToolBarHelper :: deleteListx(JText::_('COM_ASINUSTIMETRACKING_Q_REMOVE'),'removeservice',JText::_('COM_ASINUSTIMETRACKING_REMOVE'));
		JToolBarHelper :: spacer();
	JToolBarHelper :: custom('overview','ctoverview.png','ctoverview.png', JText::_('COM_ASINUSTIMETRACKING_OVERVIEW'),false);
		JToolBarHelper :: spacer();
		JToolBarHelper :: custom('users','ctuser.png','ctuser.png', JText::_('COM_ASINUSTIMETRACKING_USER'),false);
		JToolBarHelper :: custom('services','ctservice.png','ctservice.png', JText::_('COM_ASINUSTIMETRACKING_SERVICES'),false);
		JToolBarHelper :: custom('roles','ctroles.png','ctroles.png', JText::_('COM_ASINUSTIMETRACKING_USERROLES'),false);
		JToolBarHelper :: custom('selections','ctselection.png','ctselection.png', JText::_('COM_ASINUSTIMETRACKING_PROJECTS'),false);
		JToolBarHelper :: custom('costunits', 'costunit', 'costunit', JText::_('COM_TIMETRACK_COSTUNITS'), false);
		
		$items		=  $this->get( 'Services' );

		$this->assignRef('items',		$items);

		parent::display($tpl);

	}
}