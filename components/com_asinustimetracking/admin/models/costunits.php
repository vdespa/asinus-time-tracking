<?php
/**
 * @package		TimeTrack
 * @version 	$Id: costunits.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelCostunits extends JModel
{
	var $_costunits;

	function getCostunits(){
		$query = "SELECT * FROM #__asinustimetracking_costunit ORDER BY cc_id";
		$_costunits = $this->_getList($query);

		return $_costunits;
	}
}
?>