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

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.utilities.date' );

function createMonthSelect($c_name, $selectid){
	$months = array(JText::_("COM_ASINUSTIMETRACKING_JANUARY"), JText::_("COM_ASINUSTIMETRACKING_FEBRUARY"), JText::_("COM_ASINUSTIMETRACKING_MARCH"), JText::_("COM_ASINUSTIMETRACKING_APRIL"), JText::_("COM_ASINUSTIMETRACKING_MAY"), JText::_("COM_ASINUSTIMETRACKING_JUNE"), JText::_("COM_ASINUSTIMETRACKING_JULY"), JText::_("COM_ASINUSTIMETRACKING_AUGUST"), JText::_("COM_ASINUSTIMETRACKING_SEPTEMBER"), JText::_("COM_ASINUSTIMETRACKING_OCTOBER"), JText::_("COM_ASINUSTIMETRACKING_NOVEMBER"), JText::_("COM_ASINUSTIMETRACKING_DECEMBER"));
	$selected = "";
	$result = "<select class='inputbox' name='$c_name'>";

	for ($midx = 0; $midx < count($months); $midx++) {
		if($midx == $selectid-1){
			$selected = "selected";
		}
		$result .= "<option value='" . ($midx + 1) ."' $selected>$months[$midx]</option> ";
		$selected = "";
	}

	$result .= "</select>";

	return $result;
}

function createYearSelect($c_name, $selectid){
	$selected = "";
	$years = array();

	$maxy = date('Y', time());

	for($idx = $maxy - 3; $idx <= $maxy; $idx++){
		$years[] = $idx;
	}

	arsort($years);

	$result = "<select class='inputbox' name='$c_name'>";

	foreach ($years as $year) {
		if($year == $selectid){
			$selected = "selected";
		}
		$result .= "<option value='$year' $selected>$year</option>";
		$selected = "";

	}
	$result .= "</select>";

	return $result;

}

function createJavaScripts(){
	echo '
<script language="JavaScript" type="text/javascript">
function filterList(){
	document.adminForm.submit();
}

function editEntry(id){
	document.adminForm.cid.value=id; 
	document.adminForm.option="com_asinustimetracking";
	document.adminForm.task.value="timetrackedit";
	document.adminForm.submit();
}

</script>';
}

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


function _createTableContent($list, $model, $ctUser, $startdate, $enddate, $sllist, $svlist, $cfg){
	// init
	$result = new stdClass();
	$result->gsum = 0;
	$result->content = "";
	$usersum = 0;
	$result->timeuser = 0;
	$result->servuser = 0;
	$result->itemsums = array();

	$rowsum = 0;

	$result->content = '<table width="100%" class="adminlist">
	<thead>
		<tr>
			<th width="20"></th>
			<th width="100">' . JText::_("COM_ASINUSTIMETRACKING_DATE") . '</th>
			<th width="250">' . JText::_("COM_ASINUSTIMETRACKING_SERVICE") . '</th>
			<th width="100">' . JText::_("COM_ASINUSTIMETRACKING_START") . '</th>
			<th width="100">' . JText::_("COM_ASINUSTIMETRACKING_END") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_PAUSE_FROMTO") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_PRICE") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_QTY") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_SUM") . '</th>
			<th width="150">' . JText::_("COM_ASINUSTIMETRACKING_NOTICE") . '</th>
			<th width="*"></th>
		</tr>
	</thead>
	<tbody>';

	$k = 0;
	// List of user's entries
	for($i=0, $n=count($list); $i < $n; $i++){


		$entry =& $list[$i];

		$checked 	= JHTML::_('grid.id',   $i, $entry->ct_id );
		// TODO: date selection
		$link = JRoute::_( "index.php?option=com_asinustimetracking&task=timetrackedit&cid[]=" . $entry->ct_id . "&ct_startdate=" . $startdate . "&ct_enddate=" . $enddate . "&ct_sllist=" . $sllist . "&ct_svlist=" . $svlist);

		$a_service = $model->getServiceById($entry->cs_id);


		// Table Row
		$result->content .= "<tr class='row$k'>";
		$result->content .= "<td>$checked</td>";
		$result->content .= "<td valign='top'>" . date('d.m.Y', $entry->entry_date) ."</td>";
		$result->content .= "<td><a href=$link>$a_service->description</a><br>(" . $model->getSelectionById($entry->cg_id)->description . " - " . $model->getCostUnitById($entry->cc_id)->description . ")</td>";

		//
		$result->content .= "<td valign='top'>";
		if ($a_service->is_worktime) {
			$result->content .= date('H:i', $entry->start_time) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK");
		} else {
			$result->content .= "&nbsp;";
		}
		$result->content .= "</td>";

		// Arbeitszeit
		$result->content .= "<td valign='top'>";
		if ($a_service->is_worktime) {
			$result->content .= date('H:i', $entry->end_time) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK");
		}
		$result->content .= "</td>";

		// Pausenzeit
		$result->content .= "<td valign='top'>";
		if ($a_service->is_worktime) {
			$result->content .= date('H:i', $entry->start_pause) . " - " . date('H:i', $entry->end_pause) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK");
		}
		$result->content .= "</td>";

		// Preis
		$result->content .= "<td align=right>". number_format((double) $entry->price, 2) ." " . $cfg['currency'] . " </td>";

		// Anzahl
		if($entry->is_worktime){
			$q = round((($entry->end_time - $entry->start_time) - ($entry->end_pause - $entry->start_pause)) / 3600, 2);
		} else {
			$q = $entry->qty;
		}

		$result->content .= "<td align=right>" . number_format($q, 2) . " x </td>";

		// Zeilensumme
		$rowsum = (double)$entry->price * $q;
		$result->content .= "<td align=right>" . number_format($rowsum, 2) . " " . $cfg['currency'] . " </td>";


		// Bemerkung
		$result->content .= "<td><textarea class='inputbox' rows=2 cols=30 readonly=readonly>" . $entry->remark . "</textarea></td>";

		$result->content .= '<td valign="bottom">';
		$result->content .= "</td></tr>";

		// End Table Row

		$usersum = round((double)$usersum + (double)$rowsum,2);
		$result->gsum = ((double)$result->gsum + (double)$rowsum * 1);

		$k = 1 - $k;

		// HOTFIX
		if (array_key_exists($i+1, $list))
		{
			if($entry->entry_date <> $list[$i+1]->entry_date){
				$result->content .=  "<tr style=background-color: #c1c1c1;><td colspan=11 align=right><b>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . ": " . number_format($usersum, 2) . " " . $cfg['currency'] . " </b></td></tr>";
				$usersum = 0;
			}
		}


		if(array_key_exists($a_service->description, $result->itemsums) && $result->itemsums[$a_service->description] && (count($result->itemsums[$a_service->description]) > 0))
		{
			$result->itemsums[$a_service->description]->value = $result->itemsums[$a_service->description]->value + (double)$rowsum;
			$result->itemsums[$a_service->description]->qty = $result->itemsums[$a_service->description]->qty + (double)$q;
		} else {
			// hotfix
			if (! $a_service)
			{
				$a_service = new stdClass();
				$a_service->description = '';
			}
			// HOTIFX @
			@$result->itemsums[$a_service->description]->desc = $a_service->description ;
			$result->itemsums[$a_service->description]->value = (double)$rowsum;
			$result->itemsums[$a_service->description]->qty = (double)$q;
		}

	}

	$result->content .= "<tr><td colspan=11><hr /></td></tr></tbody></table>";

	return $result;

}
createJavaScripts();

?>

<form action="index.php" method="post"
	name="adminForm"><br />
	<div id="editcell"><?php
		// XXX: start- und enddate lokal referenzieren
		$sd = $this->ctStartDate;
		$ed = $this->ctEndDate;

		echo createSearch($sd, $ed, $this->ctUlist, $this->model, $this->ctSllist, $this->ctSvlist, $this->ctRlist, $this->ctCostUnit);

		if($this->ctUlist == -1){
			$cuserlist = $this->model->getUserList();
			$sumsum = 0;

			foreach ($cuserlist as $cuser) {
				$elist = $this->model->getEntriesList($cuser->cuid, 0, $sd, $ed, $this->ctSvlist, $this->ctSllist, $this->ctCostUnit);
				if(($this->ctRlist == -1 || $cuser->crid == $this->ctRlist) && count($elist) > 0 ){
					echo "<h2>$cuser->name</h2>";
					$table = _createTableContent($elist, $this->model, $cuser->cuid, $sd, $ed, $this->ctSllist, $this->ctSvlist, $this->cfg);
					echo $table->content;
					echo '<table width="100%" class="adminlist"><thead>';
					echo "<tr><th></th><th width=150></th><th width=150>" . JText::_("COM_ASINUSTIMETRACKING_TOTAL") . " ". $cuser->name . "</th></thead><tbody>";


					foreach ($table->itemsums as $sitem) {
						echo "<tr><td align=right>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " ". $sitem->desc . "</td><td align=right width=150>" . number_format($sitem->qty,2) . " x </td><td align=right width=150>" . number_format($sitem->value,2) . " " . $this->cfg['currency'] . " </td></tr>";

						if($itemsums[$sitem->desc] && (count($itemsums[$sitem->desc]) > 0))
						{
							$itemsums[$sitem->desc]->value = $itemsums[$sitem->desc]->value + (double)$sitem->value;
							$itemsums[$sitem->desc]->qty = $itemsums[$sitem->desc]->qty + (double)$sitem->qty;
						} else {
							$itemsums[$sitem->desc]->desc = $sitem->desc;
							$itemsums[$sitem->desc]->value = (double)$sitem->value;
							$itemsums[$sitem->desc]->qty = (double)$sitem->qty;
						}
					}
					echo '<tr><td align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_USER") . '</b></td><td colspan=2 align=right><b> ' . number_format($table->gsum, 2) . ' '. $this->cfg['currency'] . ' </b></td></tr></tbody></table>';

					$sumsum = $sumsum + $table->gsum;
				}
			}
			echo '<hr><hr><table width="100%" class="adminlist">';
			if($itemsums && (count($itemsums) > 0)){
				foreach ($itemsums as $ssitem) {
					echo "<tr><td align=right>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " " . $ssitem->desc . "</td><td align=right width=150>" . number_format($ssitem->qty,2) . " x </td><td align=right width=150>" . number_format($ssitem->value,2) . " " . $this->cfg['currency'] . " </td></tr>";
				}
			}
			echo '<tr><td align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_ALL_USER") . '</b></td><td align=right colspan=2><b>' . number_format($sumsum, 2) . ' '. $this->cfg['currency'] . ' </b></td></tr></table>';
		} else {
			$cuser = $this->model->getCtUserById($this->ctUlist);
			echo "<h2>$cuser->name</h2>";
			$table = _createTableContent($this->model->getEntriesList($this->ctUlist, 0, $sd, $ed, $this->ctSvlist, $this->ctSllist, $this->ctCostUnit), $this->model, $this->ctUlist, $sd, $ed, $this->ctSllist, $this->ctSvlist, $this->cfg);
			echo $table->content;
			echo '<table width="100%" class="adminlist">';
			foreach ($table->itemsums as $sitem) {
				echo "<tr><td align=right>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " " . $sitem->desc . "</td><td align=right width=150>" . number_format($sitem->qty,2) . " x </td><td align=right width=150>" . number_format($sitem->value,2) . " " . $this->cfg['currency'] . " </td></tr>";
			}

			echo '<tr><td align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_USER") . '</b></td><td colspan=2 align=right width=300><b> ' . number_format($table->gsum, 2) . ' '. $this->cfg['currency'] . ' </b></td></tr></table>';

		}
		?></div>
	<!-- Submit Fields --> <input type="hidden" name="option"
		value="com_asinustimetracking"> <br />
	<input type="hidden" name="task" value="default" /> <br />
	<input type="hidden" value="0" name="boxchecked" /></form>