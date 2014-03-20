<?php
/**
 * @package		TimeTrack
 * @version 	$Id: usersedit.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class AsinusTimeTrackingModelUsersedit extends JModel{
	var $_tablename = '#__asinustimetracking_user';

	function getById($id = 0){
		$query = "SELECT * FROM $this->_tablename as c, #__users as u, #__asinustimetracking_roles as r WHERE c.uid=u.id AND c.crid=r.crid AND cuid=" . $id;
		$_result = $this->_getList( $query );

		return $_result[0];
	}

	function merge($cuid = null, $crid = null, $is_admin = 0, $preise = array(0)){
		$db = JFactory::getDBO();
			
		$query = "UPDATE $this->_tablename SET crid=$crid, is_admin='$is_admin' WHERE cuid=$cuid";

		$db->setQuery($query);

		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}

		$this->_saveUserServices($cuid, $preise);

	}

	function _saveUserServices($cuid = null, $preise = array(0)){
		$db 	= JFactory::getDBO();

		$query 	= "DELETE FROM #__asinustimetracking_userservices WHERE cu_id=$cuid";
		$db->setQuery($query);
		if (!$db->query())
		{
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
			
		foreach ($preise as $key => $preis) {
			if($preis && $preis > 0){
				$preis = str_replace(",", ".", $preis);
				//$preis = number_format((double)$preis, 2, ".", ",");
				$query = "INSERT INTO #__asinustimetracking_userservices (cu_id, csid, price) VALUES($cuid, $key, $preis)";

				$db->setQuery($query);
				if (!$db->query())
				{
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}
	}

	function getUserServices($cuid = null){
		$query = "SELECT * FROM #__asinustimetracking_userservices ORDER BY csid";
		$_result = $this->_getlist( $query );

		return $_result;
	}

	function getUserPrice($cuid = null, $csid = null){
		$query = "SELECT * FROM #__asinustimetracking_userservices WHERE cu_id=$cuid AND csid=$csid";
		$_result = $this->_getlist( $query );

		if ($_result)
			return $_result[0]->price;
		else
			return null;

	}

	function getServices(){
		$query = "SELECT * FROM #__asinustimetracking_services ORDER BY csid";
		$_result = $this->_getlist( $query );

		return $_result;
	}

	function getRoles(){
		$query = "SELECT * FROM #__asinustimetracking_roles ORDER BY crid";
		$_result = $this->_getlist( $query );

		return $_result;
	}
}?>