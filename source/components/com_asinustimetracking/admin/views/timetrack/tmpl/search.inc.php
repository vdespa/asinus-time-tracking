<?php
/**
 * @package        Joomla.Administrator
 * @subpackage     com_asinustimetracking
 *
 * @copyright      Copyright (c) 2014 - 2016, Valentin Despa. All rights reserved.
 * @author         Valentin Despa - info@vdespa.de
 * @link           http://www.vdespa.de
 *
 * @copyright      Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author         Ralf Nickel - info@itrn.de
 * @link           http://www.itrn.de
 *
 * @license        GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

/**
 * @param $ctStartDate
 * @param $ctEndDate
 * @param $uid
 * @param $model
 * @param $sllist
 * @param $svlist
 * @param $rlist
 * @param $ccid
 *
 * @return string
 */
function createSearch($ctStartDate, $ctEndDate, $uid, $model, $sllist, $svlist, $rlist, $ccid){
	$result = "<p>";

	$result .= "<table border=0 class=admintable><tr style='border-bottom: 1px solid gray;'><td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_STARTDATE") . ":</td><td> ";
	$result .= JHTML::calendar(date('d.m.Y', $ctStartDate ? $ctStartDate : date(time('d.m.Y'))), "ct_startdate", 'ct_calstart', ('%d.%m.%Y'), 'size=12 class=inputbox');
	$result .= "</td><td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_ENDDATE") . ":</td><td> ";
	$result .= JHTML::calendar(date('d.m.Y', $ctEndDate ? $ctEndDate : date(time('d.m.Y'))), "ct_enddate", 'ct_calend', ('%d.%m.%Y'), 'size=12 class=inputbox');

	$result .= "</td></tr><tr><td colspan=4 ></td></tr><tr>";

	$userList = $model->getUserList();

	if(count($userList) > 0){
		$result .= "<td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_USER") . ":</td><td class='value'>";
		$result .= "<select class='inputbox' name='ct_ulist'>";
		if($uid == -1){
			$select = "selected=selected";
		}
		$result .= "<option value='-1'>" . JText::_("COM_ASINUSTIMETRACKING_ALL") . "</option>";
		$select = '';
		foreach ($userList as $item) {
			if($uid == $item->cuid){
				$select = "selected=selected";
			}
			$result .= "<option value='$item->cuid' $select >$item->name</option>";
			$select = '';
		}
		$result .="</select></td>";
	}

	$servicesList = $model->getServicesList();

	if(count($servicesList) > 0){
		$result .= "<td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_SERVICE") . ":</td><td class='value'> ";
		$result .= "<select class='inputbox' name='ct_svlist'>";
		if($svlist == -1){
			$select = "selected=selected";
		}
		$result .= "<option value='-1'>" . JText::_("COM_ASINUSTIMETRACKING_ALL") . "</option>";
		$select = '';
		foreach ($servicesList as $item){
			if($item->csid == $svlist){
				$select = "selected=selected";
			}
			$result .= "<option value='$item->csid' $select >$item->description</option>";
			$select = '';
		}
		$result .="</select></td>";
	}

	$rolesList = $model->getRolesList();

	if(count($rolesList) > 0){
		$result .= "</tr><tr><td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_ROLE") . ":</td><td class='value'>";
		$result .= "<select class='inputbox' name='ct_rlist'>";
		if($rlist == -1){
			$select = "selected=selected";
		}
		$result .= "<option value='-1'>" . JText::_("COM_ASINUSTIMETRACKING_ALL") . "</option>";
		$select = '';
		foreach($rolesList as $item){
			if($item->crid == $rlist){
				$select = "selected=selected";
			}
			$result .= "<option value='$item->crid' $select >$item->description</option>";
			$select = '';
		}
		$result .="</select></td>";
	}

	$costUnitsList = $model->getCostUnitsList();

	if(count($costUnitsList) > 0){
		$result .= "<td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_COSTUNIT") . ":</td><td class='value'>";
		$result .= "<select class='inputbox' name='ct_costunit'>";
		if($ccid == -1){
			$select = "selected=selected";
		}
		$result .= "<option value='-1'>" . JText::_("COM_ASINUSTIMETRACKING_ALL") . "</option>";
		$select = '';
		foreach ($costUnitsList as $item){
			if($item->cc_id == $ccid){
				$select = "selected=selected";
			}
			$result .= "<option value='$item->cc_id' $select >$item->description</option>";
			$select = '';
		}
		$result .= "</select></td>";
	}

	$selectionsList = $model->getSelectionsList();

	if(count($selectionsList) > 0){
		$result .= "<tr></tr><td></td><td></td><td class='key'>" . JText::_("COM_ASINUSTIMETRACKING_PROJECT") . ":</td><td class='value'> ";
		$result .= "<select class='inputbox' name='ct_sllist'>";
		if($sllist == -1){
			$select = "selected=selected";
		}
		$result .= "<option value='-1'>" . JText::_("COM_ASINUSTIMETRACKING_ALL") . "</option>";
		$select = '';
		foreach ($selectionsList as $item){
			if($item->cg_id == $sllist){
				$select = "selected=selected";
			}
			$result .= "<option value='$item->cg_id' $select >$item->description</option>";
			$select = '';
		}
		$result .="</select></td>";
	}

	$result .= "</tr><tr><td colspan=4 ></td></tr><tr><td colspan=3></td><td align='right'><input type='button' class='button' value='" . JText::_("COM_ASINUSTIMETRACKING_REFRESH") . "' onclick='javascript:filterList()' />";
	$result .= "</td></tr></table></p>";
	return $result;
}