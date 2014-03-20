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

class AsinusTimeTrackingViewCostUnitsEdit extends JView{
	function display($tpl = null){
		JToolBarHelper::title( JText::_('COM_ASINUSTIMETRACKING_EDIT_COSTUNIT'), 'generic.png');
		JToolBarHelper :: cancel('costunits', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper :: save('savecostunit', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$model 		= $this->getModel();

		$ccid = JRequest::getVar('cid', array(0), 'get');
		$item = $model->getById((int) $ccid[0]);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}

?>