<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2015, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die;

class AsinusTimeTrackingModelServicesedit extends JModelLegacy
{
	/**
	 * @param int $id
	 *
	 * @return stdClass
	 */
	function getById($id = 0){
		$query = "SELECT * FROM #__asinustimetracking_services WHERE csid=" . (int) $id;
		$_result = $this->_getList( $query );

		if ($_result) {
			return $_result[0];
		} else {
			$service = new stdClass();
			$service->description = '';
			$service->is_worktime = 0;
			$service->csid = 0;
			return $service;
		}
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

		if(( $test[0]->anz > 0 ) || ( $test2[0]->anz > 0 ))
		{
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_ASINUSTIMETRACKING_SERVICES_DELETING_ERROR_MSG'), 'error'
			);
		}
		else
		{
			$db = JFactory::getDBO();
			$query = "DELETE FROM #__asinustimetracking_services WHERE csid=$csid";

			$db->setQuery($query);

			if (!$db->query())
			{
				JError::raiseError( 500, $db->getErrorMsg() );
				return false;
			} else {
				JFactory::getApplication()->enqueueMessage(
					JText::_('COM_ASINUSTIMETRACKING_SERVICES_DELETED_SUCCESS_MSG'), 'message'
				);
			}
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