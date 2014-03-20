<?php
/**
 * @package		TimeTrack for Joomla! 1.5
 * @version 	$Id: timetrack.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DS.'controller.php');

JHTML::_('stylesheet', 'timetrack.css', 'components/com_asinustimetracking/css/');

if($controller = JRequest::getWord('controller')){
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if(file_exist($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

$classname 	= 'AsinusTimeTrackingController'.$controller;
$controller	= new $classname();

$controller->execute(JRequest::getVar('task'));

$controller->redirect();

?>