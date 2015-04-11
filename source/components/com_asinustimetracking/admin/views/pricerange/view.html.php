<?php
/**
 * @package        Joomla.Administrator
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

class AsinusTimeTrackingViewPriceRange extends JViewLegacy
{

	function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_EDIT_PROJECT'), 'generic.png');
		JToolBarHelper:: cancel('useredit', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper:: save('savepricerange', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$cpid = JRequest::getInt('ct_cpid', -1);
		$cuid = JRequest::getInt('ct_cuid', -1);
		$csid = JRequest::getInt('ct_csid', -1);

		$model = $this->getModel();

		$item = $model->getById($cpid);

		// Hotfix
		if (!$item)
		{
			$item              = new stdClass();
			$item->name        = '';
			$item->description = '';
			$item->start_time  = '';
			$item->price       = 0;
		}

		$this->assignRef('item', $item);
		$this->assignRef('cuid', $cuid);
		$this->assignRef('csid', $csid);
		$this->assignRef('cpid', $cpid);

		parent::display($tpl);
	}

}