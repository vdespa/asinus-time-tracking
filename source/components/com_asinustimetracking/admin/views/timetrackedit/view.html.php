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

class AsinusTimeTrackingViewTimeTrackedit extends JViewLegacy
{
	function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_ENTRY'), 'generic.png');
		JToolBarHelper:: custom('overview', 'archive.png', 'archive.png', JText::_('COM_ASINUSTIMETRACKING_CANCEL'), false);
		JToolBarHelper:: save('submit', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$model        = $this->getModel();
		$ctSllist     = JRequest::getInt('ct_sllist', -1);
		$ctSvlist     = JRequest::getInt('ct_svlist', -1);
		$ct_startdate = JRequest::getVar('ct_startdate', 0);
		$ct_enddate   = JRequest::getVar('ct_enddate', 0);
		$ct_cc        = JRequest::getInt('ct_cc', 0);

		$this->assignRef('ctSllist', $ctSllist);
		$this->assignRef('ctSvlist', $ctSvlist);
		$this->assignRef('ct_startdate', $ct_startdate);
		$this->assignRef('ct_enddate', $ct_enddate);
		$this->assignRef('ctcc', $ct_cc);
		$this->assignRef('model', $model);

		$crid = JRequest::getVar('cid', array(0));
		$item = $model->getById((int) $crid[0]);
		$this->assignRef('ctUlist', $item->cu_id);
		$this->assignRef('item', $item);

		parent::display($tpl);
	}
}