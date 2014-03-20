<?php
/**
 * @package		TimeTrack
 * @version 	$Id: costunitsedit.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelCostUnitsedit extends JModel{
	var $_tablename = '#__asinustimetracking_costunit';

	function getById($id = 0){
		$query = "SELECT * FROM $this->_tablename WHERE cc_id=" . $id;
		$_result = $this->_getList( $query );

		return $_result[0];
	}

	function merge($ccid = null, $name = '', $description = ''){
		$db = JFactory::getDBO();
			
		$query = "UPDATE $this->_tablename SET description='$description', name='$name' WHERE cc_id=$ccid";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
	}

	function remove($ccid = null){
		$query = "SELECT count(*) as anz from #__asinustimetracking_entries WHERE cc_id=$ccid";

		$test = $this->_getList($query);

		if($test[0]->anz > 0){

			JError::raiseWarning( 100, JText::_('Abhängigkeit vorhanden, Kunde kann nicht gelöscht werden:') . $ccid);
		} else {
			$db = JFactory::getDBO();
			$query = "DELETE FROM $this->_tablename WHERE cc_id=$ccid";

			$db->setQuery($query);
			if (!$db->query())
			{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			}

			JError::raiseNotice( 100, JText::_('Kunde gelöscht') );	
		}
	}

	function create($name = '', $description = ''){
		$db = JFactory::getDBO();
			
		$query = "INSERT INTO $this->_tablename (description) VALUES ('$description')";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
	}
}

?>