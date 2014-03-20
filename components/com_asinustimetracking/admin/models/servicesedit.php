<?php
/**
 * @package		TimeTrack
 * @version 	$Id: servicesedit.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelServicesedit extends JModel
{
	function getById($id = 0){
		$query = "SELECT * FROM #__asinustimetracking_services WHERE csid=" . $id;
		$_result = $this->_getList( $query );

		if ($_result)
			return $_result[0];
		else
			return null;
	}

	function merge($csid = null, $description = '', $is_worktime=false){
		$db = JFactory::getDBO();
			
		$query = "UPDATE #__asinustimetracking_services SET description='$description', is_worktime='$is_worktime' WHERE csid=$csid";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
	}

	function remove($csid = null){

		$query = "SELECT count(*) as anz FROM #__asinustimetracking_entries WHERE cs_id=$csid";

		$test = $this->_getList($query);
		$query = "SELECT count(*) as anz FROM #__asinustimetracking_userservices WHERE csid=$csid";
		$test2 = $this->_getList($query);

		if(( $test[0]->anz > 0 ) || ( $test2[0]->anz > 0 )){
			JError::raiseWarning( 100, 'Abhängigkeiten vorhanden. Leistung kann nicht gelöscht werden.' );		
		} else {
			$db = JFactory::getDBO();
			$query = "DELETE FROM #__asinustimetracking_services WHERE csid=$csid";

			$db->setQuery($query);

			if (!$db->query())
			{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			}

			JError::raiseNotice( 100, 'Leistung gelöscht' );	
		}
	}

	function create($description = '', $is_worktime='0'){
		$db = JFactory::getDBO();
		//$is_worktime = $is_worktime ? 'true' : 'false';

		$query = "INSERT INTO #__asinustimetracking_services (description, is_worktime) VALUES ('$description', '$is_worktime')";

		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

	}

}
?>