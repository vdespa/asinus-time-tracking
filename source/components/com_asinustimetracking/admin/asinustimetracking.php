<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2015, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_asinustimetracking'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JHTML::_('stylesheet', 'asinustimetracking.css', 'administrator/components/com_asinustimetracking/assets/css/');

$controller = JControllerLegacy::getInstance('AsinusTimeTracking');
$controller->execute(JFactory::getApplication()->input->getCmd('task', 'display'));
$controller->redirect();


