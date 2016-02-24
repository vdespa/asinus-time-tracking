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

require_once 'search.inc.php';
require_once 'table.inc.php';

// Load JavaScript
JHtml::script(JUri::base() . 'components/com_asinustimetracking/assets/js/timetrackedit.js', true);
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div>
		<?php
		// Display filters / search
		echo createSearch($this->ctStartDate, $this->ctEndDate, $this->ctUlist, $this->model, $this->ctSllist, $this->ctSvlist, $this->ctRlist, $this->ctCostUnit);
		?>
		<hr>
		<?php
		// Display all users
		if($this->ctUlist == -1){
			$cuserlist = $this->model->getUserList();
			$sumsum = 0;
			$itemsums = array();

			foreach ($cuserlist as $cuser) {
				$elist = $this->model->getEntriesList($cuser->cuid, 0, $this->ctStartDate, $this->ctEndDate, $this->ctSvlist, $this->ctSllist, $this->ctCostUnit);
				if(($this->ctRlist == -1 || $cuser->crid == $this->ctRlist) && count($elist) > 0 ){
					echo "<h2>$cuser->name</h2>";
					$table = _createTableContent($elist, $this->model, $cuser->cuid, $this->ctStartDate, $this->ctEndDate, $this->ctSllist, $this->ctSvlist, $this->cfg);
					echo $table->content;
					echo '<table width="100%" class="table table-striped"><thead>';
					echo "<tr><th></th><th width=150></th><th width=150>" . JText::_("COM_ASINUSTIMETRACKING_TOTAL") . " ". $cuser->name . "</th></thead><tbody>";


					foreach ($table->itemsums as $sitem) {
						echo "<tr><td align=right>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " ". $sitem->desc . "</td><td align=right width=150>" . number_format($sitem->qty,2) . " x </td><td align=right width=150>" . number_format($sitem->value,2) . " " . $this->cfg['currency'] . " </td></tr>";

						if(array_key_exists($sitem->desc, $itemsums) && $itemsums[$sitem->desc] && (count($itemsums[$sitem->desc]) > 0))
						{
							$itemsums[$sitem->desc]->value = $itemsums[$sitem->desc]->value + (double)$sitem->value;
							$itemsums[$sitem->desc]->qty = $itemsums[$sitem->desc]->qty + (double)$sitem->qty;
						} else {
							$itemsums[$sitem->desc] = new stdClass();
							$itemsums[$sitem->desc]->desc = $sitem->desc;
							$itemsums[$sitem->desc]->value = (double)$sitem->value;
							$itemsums[$sitem->desc]->qty = (double)$sitem->qty;
						}
					}
					echo '<tr><td align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_USER") . '</b></td><td colspan=2 align=right><b> ' . number_format($table->gsum, 2) . ' '. $this->cfg['currency'] . ' </b></td></tr></tbody></table>';

					$sumsum = $sumsum + $table->gsum;
				}
			}
			echo '<hr><hr><table width="100%" class="table-striped">';
			if($itemsums && (count($itemsums) > 0)){
				foreach ($itemsums as $ssitem) {
					echo "<tr><td align=right>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " " . $ssitem->desc . "</td><td align=right width=150>" . number_format($ssitem->qty,2) . " x </td><td align=right width=150>" . number_format($ssitem->value,2) . " " . $this->cfg['currency'] . " </td></tr>";
				}
			}
			echo '<tr><td align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_ALL_USER") . '</b></td><td align=right colspan=2><b>' . number_format($sumsum, 2) . ' '. $this->cfg['currency'] . ' </b></td></tr></table>';
		}
		// Display a single user
		else
		{
			$cuser = $this->model->getCtUserById($this->ctUlist);
			echo "<h2>$cuser->name</h2>";
			$table = _createTableContent($this->model->getEntriesList($this->ctUlist, 0, $this->ctStartDate, $this->ctEndDate, $this->ctSvlist, $this->ctSllist, $this->ctCostUnit), $this->model, $this->ctUlist, $this->ctStartDate, $this->ctEndDate, $this->ctSllist, $this->ctSvlist, $this->cfg);
			echo $table->content;
			echo '<table width="100%" class="table-striped">';
			foreach ($table->itemsums as $sitem) {
				echo "<tr><td align=right>" . JText::_("COM_ASINUSTIMETRACKING_SUM") . " " . $sitem->desc . "</td><td align=right width=150>" . number_format($sitem->qty,2) . " x </td><td align=right width=150>" . number_format($sitem->value,2) . " " . $this->cfg['currency'] . " </td></tr>";
			}

			echo '<tr><td align=right><b>' . JText::_("COM_ASINUSTIMETRACKING_TOTAL_USER") . '</b></td><td colspan=2 align=right width=300><b> ' . number_format($table->gsum, 2) . ' '. $this->cfg['currency'] . ' </b></td></tr></table>';

		}
		?>
	</div>

	<!-- Submit Fields -->
	<input type="hidden" name="option" value="com_asinustimetracking">
	<input type="hidden" name="task" value="default" />
	<input type="hidden" value="0" name="boxchecked" />
</form>
