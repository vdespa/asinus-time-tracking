<?php
/**
 * @package		TimeTrack
 * @version 	$Id: default.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

function createMinuteSelect($c_name, $selectid=0){

	$minutes = null;

	for ($i = 0; $i < 60; $i++) {
		$minutes[$i] = new stdClass();
		$minutes[$i]->id = $i;

		if($i < 10){
			$minutes[$i]->name = "0$i";
		} else {
			$minutes[$i]->name = "$i";
		}

		$i += 4;

	}

	$result = JHTML::_('select.genericlist', $minutes, $c_name, 'class="inputbox"', 'id', 'name', $selectid);

	return $result;

}

function createHourSelect($c_name, $selectid=8){
	$hours = array();

	for($i = 0; $i <= 23; $i++){
		$hours[$i] = new stdClass();
		$hours[$i]->id = $i;

		if($i < 10){
			$hours[$i]->name = "0$i";
		} else {
			$hours[$i]->name = "$i";
		}
	}

	$result = JHTML::_('select.genericlist', $hours, $c_name, 'class="inputbox"', 'id', 'name', $selectid);

	return $result;

}

?>

<script language="JavaScript" type="text/javascript">

	function hideme(){
		id = document.getElementById("ctService").options[document.getElementById("ctService").selectedIndex].id;
		if(id==1){
			document.getElementById("timedata1").className = "showcell";
			document.getElementById("timedata2").className = "showcell";
			document.getElementById("qtyrow").className = "hidecell";
		} else {
			document.getElementById("timedata1").className = "hidecell";
			document.getElementById("timedata2").className = "hidecell";
			document.getElementById("qtyrow").className = "showcell";
		}
		return false;
	}

	function textCounter(field, counter, maxlimit) {
		if (field.value.length > maxlimit)
			field.value = field.value.substring(0, maxlimit);
		else
			counter.value = maxlimit - field.value.length;
	}

</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="col100">
		<fieldset class="adminform"><legend><?php echo JText::_( 'COM_ASINUSTIMETRACKING_DETAILS' ); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key"><label for="ctdate"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_DATE' ); ?></label></td>
					<td><?php echo JHTML::calendar(date('d.m.Y', $this->item->entry_date), "ct_entrydate", 'ct_cal', ('%d.%m.%Y'), 'size=12 class=inputbox');?></td>
				</tr>
				<tr>
					<td align="right" class="key"><label for="ctService"><?php echo JText::_('Leistung'); ?></label></td>
					<td><select class="inputbox" name="ct_service" id="ctService"
								onchange="javascript:hideme()">
							<?php
							// load user's services
							foreach($this->model->getServicesListByUser($this->item->cu_id) as $service){
								if($this->item->cs_id == $service->csid){
									$selected = 'selected=selected';
								}
								echo "<option value='$service->csid' $selected id='$service->is_worktime'>" . $service->description . "</option>";
								$selected = '';
							};
							?>
						</select></td>
				</tr>
				<tr>
					<td align="right" class="key"><label for="ctCostUnit"><?php echo JText::_('COM_ASINUSTIMETRACKING_COSTUNIT'); ?></label></td>
					<td><select class="inputbox" name="ct_costunit" id="ctCostUnit">
							<?php
							foreach($this->model->getCostUnitsList() as $costUnit){
								if($this->item->cc_id == $costUnit->cc_id){
									$selected = 'selected=selected';
								}
								echo "<option value='$costUnit->cc_id' $selected >" . $costUnit->description . "</option>";
								$selected = '';
							}
							?>
						</select></td>

				</tr>

				<tr>
					<td align="right" class="key"><label for="ctSelection"><?php echo JText::_('COM_ASINUSTIMETRACKING_PROJECT');?></label></td>
					<td><select class="inputbox" name="ct_selection" id="ctSelection">
							<?php
							foreach($this->model->getSelectionsList() as $selection){
								if($this->item->cg_id == $selection->cg_id){
									$selected = 'selected=selected';
								}
								echo "<option value='$selection->cg_id' $selected >" . $selection->description . "</option>";
								$selected = '';
							}
							?>
						</select></td>
				</tr>
				<tr id="timedata1"
					class="<?php if($this->model->getServiceById($this->item->cs_id)->is_worktime){
						echo "showcell";
					} else {
						echo "hidecell";
					};?>">
					<td align="right" class="key"><label for="ct_sh"><?php echo JText::_('COM_ASINUSTIMETRACKING_WORKTIME')?></label></td>
					<td><?php echo createHourSelect('ct_sh', date('G', $this->item->start_time)); ?>
						: <?php echo createMinuteSelect('ct_sm', date('i', $this->item->start_time)); ?>
						- <?php echo createHourSelect('ct_eh', date('G', $this->item->end_time)); ?>
						: <?php echo createMinuteSelect('ct_em', date('i', $this->item->end_time)); ?>
					</td>
				</tr>
				<tr id="timedata2"
					class="<?php if($this->model->getServiceById($this->item->cs_id)->is_worktime){
						echo "showcell";
					} else {
						echo "hidecell";
					};?>">
					<td align="right" class="key"><label for="ct_sh"><?php echo JText::_('COM_ASINUSTIMETRACKING_PAUSE'); ?> (hh:mm)</label></td>
					<td><?php echo createHourSelect('ct_psh', $this->item->h_pause); ?> :<?php echo createMinuteSelect('ct_psm', $this->item->m_pause);?>
					</td>
				</tr>
				<tr id="qtyrow"
					class="<?php
					if(!$this->model->getServiceById($this->item->cs_id)->is_worktime){
						echo 'showcell';
					} else {
						echo 'hidecell';
					}; ?>">
					<td align="right" class="key"><label for="ct_qty"><?php echo JText::_('COM_ASINUSTIMETRACKING_QTY'); ?></label></td>
					<td><input class="text_area" type="text" name="ct_qty" size="12"
							   maxlength="250" value="<?php echo $this->item->qty; ?>" /></td>
				</tr>
				<tr>
					<td align="right" class="key"><?php echo JText::_("COM_ASINUSTIMETRACKING_NOTICE"); ?></td>
					<td><textarea rows="3" cols="32" name="ct_remark" class="text_area"
								  onkeyup='textCounter(this.form.ct_remark,this.form.counter,120);'
								  onkeydown='textCounter(this.form.ct_remark,this.form.counter,120);'><?php echo $this->item->remark; ?></textarea><br />

						<?php echo JText::_("COM_ASINUSTIMETRACKING_CHAR");?>: <input type='text'
																			 name='counter' size='5' value=250></td>
				</tr>
			</table>
		</fieldset>

	</div>
	<input type="hidden" name="ct_id"
		   value="<?php echo $this->item->ct_id; ?>" /> <input type="hidden"
															   name="task" value="timetrack" /> <input type="hidden" name="option"
																									   value="com_asinustimetracking" /> <input type="hidden" value="0"
																																	   name="boxchecked" /> <input type="hidden" name="ct_startdate"
																																								   value="<?php echo $this->ct_startdate;?>" /> <input type="hidden"
																																																					   name="ct_enddate" value="<?php echo $this->ct_enddate;?>" /> <input
		type="hidden" name="ct_ulist" value="<?php echo $this->ctUlist;?>" /> <input
		type="hidden" name="ct_sllist" value="<?php echo $this->ctSllist;?>" />
	<input type="hidden" name="ct_svlist"
		   value="<?php echo $this->ctSvlist;?>" /></form>

<style type="text/css">
	.hidecell {
		display: none;
	}

	.showcell {
		display: table-row;
	}
</style>
