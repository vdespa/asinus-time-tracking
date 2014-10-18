<?php
/**
 * @package		TimeTrack
 * @version 	$Id: services.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelServices extends JModel
{
	var $_services;

	function getServices(){
		$query = "SELECT * FROM #__asinustimetracking_services";
		$this->_services = $this->_getList( $query );

		return $this->_services;
	}

	

}?>