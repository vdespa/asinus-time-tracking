<?php
/**
 * @package		TimeTrack
 * @version 	$Id: rolesedit.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelRolesedit extends JModel{
	var $_tablename = '#__asinustimetracking_roles';

	function getById($id = 0){
		$query = "SELECT * FROM $this->_tablename WHERE crid=" . $id;
		$_result = $this->_getList( $query );

		if ($_result)
			return $_result[0];
	}

	function merge($crid = null, $description = ''){
		$db = JFactory::getDBO();
			
		$query = "UPDATE $this->_tablename SET description='$description' WHERE crid=$crid";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
	}

	function remove($crid = null){

		$query = "SELECT count(*) as anz FROM #__asinustimetracking_user WHERE crid=$crid";

		$test = $this->_getList($query);


		if( $test[0]->anz > 0 ){
			JError::raiseWarning( 100, JText::_('Abhängigkeiten vorhanden. Rolle kann nicht gelöscht werden.') );		
		} else {
			$db = JFactory::getDBO();
			$query = "DELETE FROM $this->_tablename WHERE crid=$crid";

			$db->setQuery($query);

			if (!$db->query())
			{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			}

			JError::raiseNotice( 100, JText::_('Rolle gelöscht') );	
		}
	}

	function create($description = ''){
		$db = JFactory::getDBO();
			
		$query = "INSERT INTO $this->_tablename (description) VALUES ('$description')";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

	}
}?>