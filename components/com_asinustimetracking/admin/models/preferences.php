<?php
/**
 * @package		TimeTrack
 * @version 	$Id: preferences.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelPreferences extends JModel{

	var $_values = array();
	var $_tablename = "#__asinustimetracking_config";

	function getPreferences(){

		$_values = array();

		// Mock values
		$_values['first_day'] = '01';
		$_values['currency'] = 'CURRENTY NOT DEFINED';

		$query = "SELECT * FROM $this->_tablename";

		$data = $this->_getList($query);

		if ($data)
		{
			foreach ($data as $citem) {
				switch (strtoupper($citem->value)) {
					case "TRUE":
						$_val = TRUE;
						break;
					case "FALSE":
						$_val = FALSE;
						break;
					default:
						$_val = $citem->value;
						break;
				}
				$_values[$citem->name] = $_val;
			}
		}


		return $_values;
	}

	function save($data){
		$db = JFactory::getDBO();
		$query = "DELETE FROM $this->_tablename";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}


		foreach ($data as $name => $value) {
			$query = "INSERT INTO $this->_tablename (name, value) VALUES ('$name', '$value')";
			
			$db->setQuery($query);
			if (!$db->query())
			{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			}
		}


		JError::raiseNotice( 100, JText::_('Konfiguration gespeichert') );
	}

}