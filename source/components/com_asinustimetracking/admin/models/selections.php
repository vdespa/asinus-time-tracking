<?php
/**
 * @package		TimeTrack
 * @version 	$Id: selections.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelSelections extends JModel
{
	var $_tablename = '#__asinustimetracking_selection';

	var $_selections;

	function getSelections(){
		$query = "SELECT * FROM $this->_tablename ORDER BY cg_id";
		$_selections = $this->_getList($query);

		return $_selections;
	}
}?>