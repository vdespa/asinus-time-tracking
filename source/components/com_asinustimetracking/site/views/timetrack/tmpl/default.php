<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_asinustimetracking
 * @copyright	Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Valentin Despa - info@vdespa.de
 * @link		http://www.vdespa.de
 * @license 	GNU General Public License version 3. See LICENSE.txt or http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

jimport( 'joomla.utilities.date' );

$selected = ''; 





$tmp = $this->model->getServiceById($this->editEntry->cs_id);
if (! $tmp)
{
	$tmp = new stdClass();
	$tmp->is_worktime = null;
}
?>

<h1><?php echo JText::_("COM_ASINUSTIMETRACKING_H1_TITLE"); ?> - <?php echo $this->user->name; ?></h1>

<form action="#" method="post" name="form_timetrack">

<table style="height: 110px; width= 100%;">
	<tr>
		<td valign="top">
		<table style="width: 100%">
			<tr>
				<td class="paramlist_key" width="110px" valign="top"><?php echo JText::_("COM_ASINUSTIMETRACKING_DATE"); ?>:</td>
				<td class="paramlist_value" width="235px" valign="top">
				<div
					style='border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;'>

					<?php echo JHTML::calendar(date('d.m.Y', $this->ctEntryDate ? $this->ctEntryDate : date(time('d.m.Y'))), "ct_entrydate", 'ct_cal', ('%d.%m.%Y'), 'size=12 class=inputbox');?>

				</div>
				</td>
			</tr>
			<tr>

				<td width="110px"><?php echo JText::_("COM_ASINUSTIMETRACKING_SERVICE"); ?>:</td>
				<td width="235px" class="paramlist_value" style='border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;'>
				
					<?php
// echo JHtml::_('select.genericlist', $this->servicesList, 'ct_service', 'onchange="javascript:hideme()"', 'cs_id', 'description', $this->editEntry->cs_id);
					
						echo "<select class='inputbox' name='ct_service' onchange='javascript:hideme()' id='ct_service'>";
						if($this->editEntry->cs_id == -1){
							$select = "selected='selected'";
						}
						$select = '';
						foreach ($this->servicesList as $item){
							if($item->csid == $this->editEntry->cs_id){
								$select = "selected=selected";
							}
							echo "<option value='$item->csid' $select id='$item->is_worktime'>$item->description</option>";
							$select = '';
						}
						echo "</select>";
					?>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_("COM_ASINUSTIMETRACKING_CUSTOMER")?>:</td>
				<td width="235px" class="paramlist_value" style='border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;'>
				    <?php echo JHtml::_('select.genericlist', $this->costList, 'ct_costunit', null, 'cc_id', 'description', $this->editEntry->cc_id); ?>
				</td>
			</tr>

			<tr>
				<td><?php echo JText::_("COM_ASINUSTIMETRACKING_PROJECT"); ?>:</td>
				<td width="235px" class="paramlist_value" style='border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;'>
                    <?php echo JHtml::_('select.genericlist', $this->selectionsList, 'ct_selection', null, 'cg_id', 'description', $this->editEntry->cg_id); ?>
				</td>
			</tr>
		</table>
		</td>
		<td valign="top">
		<table style="width: 100%;">
			<?php if ($this->settings->show_quantity == 1) :?>
			<tr id="qtyrow"
				class='<?php 
			// Qty Row
			if(!$this->editEntry){
				echo "hidecell";
			} else if(!$tmp->is_worktime){
				echo "showcell";
			} else {
				echo "hidecell";				
			};
			// End Style
			?>'>
				<td class="paramlist_key" width="110"><?php echo JText::_("COM_ASINUSTIMETRACKING_QTY"); ?>:</td>
				<td class="paramlist_value" width="235" style='border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;'>
				<input type="text" size="5" name="ct_qty" class="inputbox" value="<?php echo $this->editEntry->qty == '' ? 1 : $this->editEntry->qty; ?>" />
				</td>
			</tr>
			<?php endif; ?>

			<tr id='timedata1'
				class='<?php 
			if(!$this->editEntry){
				echo "showcell";
			} else {
			    echo ($tmp->is_worktime) ? "showcell" : "hidecell";
			}
			?>'>
				<td class="paramlist_key" valign="top"><?php echo JText::_("COM_ASINUSTIMETRACKING_WORKTIME_FROMTO"); ?>:</td>
				<td class="paramlist_value" valign="top" style="border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;">
				<div id="tde">
			 
			        <?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getHoursOptions(), 'ct_sh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 8 : date('G',$this->editEntry->start_time)); ?> :
                    <?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getMinutesOptions(), 'ct_sm', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->start_time)); ?> -

                    <?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getHoursOptions(), 'ct_eh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 8 : date('G',$this->editEntry->end_time)); ?> :
        		    <?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getMinutesOptions(), 'ct_em', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->end_time)) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK"); ?>
				</div>
				</td>
			</tr>

			<tr id='timedata2'
				class='
			<?php 
			if(!$this->editEntry){
				echo "showcell";
			}else { 
			    echo ($tmp->is_worktime) ? "showcell" : "hidecell";
			}
			// End Style
			?>'>
				<td class="paramlist_key"><?php echo JText::_("COM_ASINUSTIMETRACKING_PAUSE_FROMTO"); ?>:</td>
				<td class="paramlist_value" style='border: 1px solid #F0F0F0; height: 30px; background-color: #F0F0F0;'>
				<div id="pde">

					<?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getHoursOptions() , 'ct_psh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 12 : date('G',$this->editEntry->start_pause)); ?> :
					<?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getMinutesOptions() , 'ct_psm', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->start_pause)); ?> -

					<?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getHoursOptions() , 'ct_peh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 12 : date('G',$this->editEntry->end_pause)); ?> :
					<?php echo JHtml::_('select.genericlist', AsinustimetrackingHelper::getMinutesOptions() , 'ct_pem', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->end_pause)); ?>
					
					<?php echo " ". JText::_("COM_ASINUSTIMETRACKING_OCLOCK"); ?>
				</div>
				</td>
			</tr>
			<!-- Remark --> 
			<tr>
				<td class="paramlist_key"><?php echo JText::_("COM_ASINUSTIMETRACKING_NOTICE"); ?>:</td>
				<td class="paramlist_value">
				<div style='border: 1px solid #F0F0F0; background-color: #F0F0F0;'>

				<textarea class="inputbox" rows="3" cols="32" id="ct_remark"
					name="ct_remark"
					onkeydown="textCounter(this.form.ct_remark,this.form.counter,120);"
					onkeyup='textCounter(this.form.ct_remark,this.form.counter,120);'><?php echo $this->editEntry->remark; ?></textarea><br />
					<?php echo JText::_("COM_ASINUSTIMETRACKING_CHAR"); ?><input type='text'
					style="border: none; background-color: #F0F0F0;" class="inputbox"
					name='counter' size='5' value=120></div>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><input type="reset" value="<?php echo JText::_('COM_ASINUSTIMETRACKING_CANCEL'); ?>" class="ttbutton" onclick="javascript:doReset()" style="cursor: pointer; <?php if($this->editEntry){echo 'display:inline';} else { echo 'display:none';}?>" />
		<input value="<?php echo JText::_('COM_ASINUSTIMETRACKING_SAVE'); ?>"
			type="button" class="ttbutton" style="cursor: pointer;"
			onclick="javascript:sendEntryValues(<?php echo $this->editEntry->ct_id; ?>)" />

		</td>
	</tr>

</table>

	<input type="hidden" name="option" value="com_asinustimetracking"><br />
	<input type="hidden" name="view" value="timetrack" /><br />
	<input type="hidden" name="task" value="submit" /><br />
	<input type="hidden" name="ct_id" value="<?php echo $this->ctid; ?>" /><br />
	<input type="hidden" name="Itemid" value="<?php echo JRequest::getInt('Itemid'); ?>" /><br />
</form>

<!-- List View -->
<hr width="100%">
<?php require_once 'listview.inc.php'; ?>


