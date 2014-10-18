<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_asinustimetracking
 * @copyright	Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Valentin Despa - info@vdespa.de
 * @link		http://www.vdespa.de
 * @license 	GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('asinustt.frontend.user', 'com_asinustimetracking'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//JHTML::_('stylesheet', 'timetrack.css', 'components/com_asinustimetracking/css/');

//JHtml::stylesheet('com_asinustimetracking/assets/css/bootstrap.css', array(), true);

//$document->addStyleSheet($url);


$document = JFactory::getDocument();
$document->addStyleSheet(Juri::base() . '/components/com_asinustimetracking/assets/css/bootstrap.css');


// Execute the task.
$controller	= JControllerLegacy::getInstance('AsinusTimeTracking');
$controller->execute(JRequest::getCmd('task', 'display'));
$controller->redirect();