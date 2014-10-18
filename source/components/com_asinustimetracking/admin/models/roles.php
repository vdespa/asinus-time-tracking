<?php
/**
 * @package		TimeTrack
 * @version 	$Id: roles.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelRoles extends JModel
{
	var $_roles;

	function getRoles(){
		$query = "SELECT * FROM #__asinustimetracking_roles ORDER BY crid";
		$_roles = $this->_getList($query);

		return $_roles;
	}
}?>