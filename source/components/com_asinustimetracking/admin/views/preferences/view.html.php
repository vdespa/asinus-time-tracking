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

class AsinusTimeTrackingViewPreferences extends JViewLegacy
{
	function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_PREFERENCES'), 'generic.png');
		JToolBarHelper:: cancel('default', JText::_('COM_ASINUSTIMETRACKING_CANCEL'));
		JToolBarHelper:: save('savepreferences', JText::_('COM_ASINUSTIMETRACKING_SAVE'));

		$model = $this->getModel();

		$pr = $model->getPreferences();
		$this->assignRef('pr', $pr);

		parent::display($tpl);
	}
}