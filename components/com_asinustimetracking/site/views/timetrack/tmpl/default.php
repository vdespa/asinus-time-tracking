<?php
/**
 * @package		TimeTrack for Joomla! 1.5
 * @version 	$Id: default.php 1 2010-09-22 14:50:00Z ralf $
 * @copyright	Copyright (C) 2010, Informationstechnik Ralf Nickel
 * @author		Ralf Nickel - info@itrn.de
 * @link		http://www.itrn.de
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.utilities.date' );

$selected = ''; 

$mins = array();
foreach (range(0,55,5) as $value) {
	$min = new stdClass();
    $min->id = $value;
    $min->value = sprintf("%02d", $value);
    $mins[] = $min;
}

$hours = array();
foreach (range(0,23) as $value){
    $hour = new stdClass();
    $hour->id = $value;
    $hour->value = sprintf("%02d", $value);
    $hours[] = $hour;
}



$tmp = $this->model->getServiceById($this->editEntry->cs_id);
if (! $tmp)
{
	$tmp = new stdClass();
	$tmp->is_worktime = null;
}
?>

<h1><?php echo JText::_("COM_ASINUSTIMETRACKING_H1_TITLE"); ?> - <?php echo $this->user->name; ?></h1>

<form action="index.php" method="post"
	name="form_timetrack">

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
				    <?php echo JHtml::_('select.genericlist', $this->costList, 'ct_costunit', null, 'cc_id', 'description', $this->editEntry->cg_id); ?>
				
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
			 
			        <?php echo JHtml::_('select.genericlist', $hours, 'ct_sh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 8 : date('G',$this->editEntry->start_time)); ?> :
                    <?php echo JHtml::_('select.genericlist', $mins, 'ct_sm', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->start_time)); ?> -

                    <?php echo JHtml::_('select.genericlist', $hours, 'ct_eh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 8 : date('G',$this->editEntry->end_time)); ?> :
        		    <?php echo JHtml::_('select.genericlist', $mins, 'ct_em', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->end_time)) . " " . JText::_("COM_ASINUSTIMETRACKING_OCLOCK"); ?>
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

					<?php echo JHtml::_('select.genericlist', $hours , 'ct_psh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 12 : date('G',$this->editEntry->start_pause)); ?> : 
					<?php echo JHtml::_('select.genericlist', $mins , 'ct_psm', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->start_pause)); ?> -

					<?php echo JHtml::_('select.genericlist', $hours , 'ct_peh', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 12 : date('G',$this->editEntry->end_pause)); ?> :
					<?php echo JHtml::_('select.genericlist', $mins , 'ct_pem', 'style="width: 3.2em;"', 'id', 'value', $this->editEntry == null ? 0 : date('i',$this->editEntry->end_pause)); ?>
					
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
		<td align="right"><input type="reset" value="Abbruch" class="ttbutton" onclick="javascript:doReset()" style="cursor: pointer; <?php if($this->editEntry){echo 'display:inline';} else { echo 'display:none';}?>" />
		<input value="<?php echo JText::_('COM_ASINUSTIMETRACKING_SAVE'); ?>"
			type="button" class="ttbutton" style="cursor: pointer;"
			onclick="javascript:sendEntryValues(<?php echo $this->editEntry->ct_id; ?>)" />

		</td>
	</tr>

</table>
<!-- List View -->
<hr width="100%"></hr>
<div align="right"><?php echo JText::_("COM_ASINUSTIMETRACKING_ONLY_EDITABLE_WILL_SHOWN"); ?>
<a href="index.php?option=com_asinustimetracking&view=timetracklist"
	class="ttbutton" style="text-decoration: none;"><?php echo JText::_("COM_ASINUSTIMETRACKING_REPORT"); ?></a><br />
</div>
<table class="contentpane" style="width: 100%">
	<thead>
		<tr>
			<td class="sectiontableheader" width="100"><?php echo JText::_("COM_ASINUSTIMETRACKING_DATE"); ?></td>
			<td class="sectiontableheader" width="*"><?php echo JText::_("COM_ASINUSTIMETRACKING_SERVICE")." (" . JText::_("COM_ASINUSTIMETRACKING_PROJECT")." - ". JText::_("COM_ASINUSTIMETRACKING_CUSTOMER").")"; ?></td>
			<td class="sectiontableheader" width="100"><?php echo JText::_("COM_ASINUSTIMETRACKING_BEGIN") . " / " . JText::_("COM_ASINUSTIMETRACKING_QTY"); ?></td>
			<td class="sectiontableheader" width="100"><?php echo JText::_("COM_ASINUSTIMETRACKING_END"); ?></td>
			<td class="sectiontableheader" width="150"><?php echo JText::_("COM_ASINUSTIMETRACKING_PAUSE_FROMTO"); ?></td>
			<td class="sectiontableheader" width="80"><?php echo JText::_("COM_ASINUSTIMETRACKING_EDIT") . " / " . JText::_("COM_ASINUSTIMETRACKING_DELETE"); ?></td>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	// List of user's entries
	if(count($this->entriesList) > 0){
		foreach ($this->entriesList as $entry){
			
			$a_service = $this->model->getServiceById($entry->cs_id);

			?>
		<tr class="<?php echo "row" . $k; ?>" style="<?php if($this->editEntry->ct_id == $entry->ct_id){ echo "background: #F0F0F0; font-weight:bold;";}?>">
			<td valign="top"><?php echo date('d.m.Y', $entry->entry_date); ?></td>
			<td valign="top"><?php echo $a_service->description; ?><br />
			(<?php echo $this->model->getSelectionById($entry->cg_id)->description . " - " . $this->model->getCostUnitById($entry->cc_id)->description; ?>)<br />
			<?php echo $entry->remark ? "<textarea class='inputbox' rows=2 cols=30 readonly=readonly>".$entry->remark."</textarea>" : ""; ?></td>
			<td valign="top"><?php echo $a_service->is_worktime ? date('H:i', $entry->start_time). " ". JText::_("COM_ASINUSTIMETRACKING_OCLOCK") : $entry->qty . " x"; ?></td>
			<td valign="top"><?php echo $a_service->is_worktime ? date('H:i', $entry->end_time). " ". JText::_("COM_ASINUSTIMETRACKING_OCLOCK") : ""; ?></td>

			<td valign="top"><?php echo $a_service->is_worktime ? date('H:i', $entry->start_pause) . " - " . date('H:i', $entry->end_pause). " ". JText::_("COM_ASINUSTIMETRACKING_OCLOCK") : ""; ?></td>
			<td align="right" valign="top" style="width: 90px;"><?php
			if ($entry->timestamp >= $this->maxage) {
			    ?>
				<input type="image" src="components/com_asinustimetracking/assets/images/edit.gif" value="ed" class="ttbutton" style="text-decoration: none;" onclick="javascript:editEntry('<?php echo $entry->ct_id; ?>')"/>
				<input type="image" src="components/com_asinustimetracking/assets/images/delete.gif" value="del" class="ttbutton" onclick="javascript:deleteEntry('<?php echo $entry->ct_id; ?>')" />
				<?php 
			}

			?></td>

		</tr>
		<?php
		$k = 1 - $k;
		}
	}
	?>
		<tr>
			<td colspan="6">
			<hr />
			</td>
		</tr>
	</tbody>
</table>
<br />

<input type="hidden" name="option" value="com_asinustimetracking"><br />
<input type="hidden" name="view" value="timetrack" /><br />
<input type="hidden" name="task" value="submit" /><br />
<input type="hidden" name="ct_id" value="<?php echo $this->ctid; ?>" /><br />
<input type="hidden" name="Itemid"
	value="<?php echo JRequest::getInt('Itemid'); ?>" /><br />
</form>
