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
?>
<!-- Filters -->
<form action="<?php echo JRoute::_('index.php?option=com_asinustimetracking&view=timetrack'); ?>" method="post" name="filterForm" id="filterForm">
	<fieldset id="filter-bar">
		<select name="filter_month" class="inputbox" onchange='this.form.submit()'>
			<?php echo JHtml::_('select.options', AsinustimetrackingHelper::getMonthOptions(), 'value', 'text', $this->state->get('filter.month'));?>
		</select>
		/
		<input type="text"
			   name="filter_year"
			   id="filter_year"
			   maxlength="4"
			   size="6"
			   value="<?php echo $this->escape($this->state->get('filter.year')); ?>"
			   title="<?php echo JText::_('COM_ASINUSTIMETRACKING_YEAR'); ?>"
			   onchange="this.form.submit()"
		/>
	</fieldset>
	<a
		href="<?php echo JRoute::_('index.php?option=com_asinustimetracking&task=monthlyreport.generate&format=excel&filter_month=' .
			$this->state->get('filter.month') . '&filter_year=' . $this->state->get('filter.year')); ?>">
		Generate monthly report (Excel)
	</a>
</form>

<?php if (is_array($this->items) && ! empty($this->items)) : ?>
	<table class="table table-condensed">
		<thead>
			<tr>
				<th><?php echo JText::_("COM_ASINUSTIMETRACKING_DATE"); ?></th>
				<th><?php echo JText::_("COM_ASINUSTIMETRACKING_SERVICE")." (" . JText::_("COM_ASINUSTIMETRACKING_PROJECT")." - ". JText::_("COM_ASINUSTIMETRACKING_CUSTOMER").")"; ?></th>
				<th><?php echo JText::_("COM_ASINUSTIMETRACKING_BEGIN"); ?></th>
				<th><?php echo JText::_("COM_ASINUSTIMETRACKING_END"); ?></th>
				<th><?php echo JText::_("COM_ASINUSTIMETRACKING_PAUSE_FROMTO"); ?></th>
				<th><?php echo JText::_("COM_ASINUSTIMETRACKING_EDIT") . " / " . JText::_("COM_ASINUSTIMETRACKING_DELETE"); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $item) : ?>
			<?php
			$isCurrentlyEdited = ($this->editEntry->ct_id == $item->ct_id) ? true : false;
			if ($isCurrentlyEdited === true)
			{
				$trEditclass = 'success';
			}
			else
			{
				$trEditclass = '';
			}
			?>
			<tr class="<?php echo $trEditclass; ?>">
				<td><?php echo $item->entry_date->format('d.m.Y'); ?></td>
				<td>
					<?php echo $item->service_name; ?><br />
					(<?php echo $item->project_name; ?> - <?php echo $item->customer_name; ?>)<br />
					<i><?php echo $item->remark; ?></i>
				</td>
				<td><?php echo $item->start_time->format('H:i'); ?></td>
				<td><?php echo $item->end_time->format('H:i'); ?></td>
				<td><?php echo $item->start_pause->format('H:i'); ?> - <?php echo $item->end_pause->format('H:i'); ?></td>
				<td>
					<?php
					$canEdit = $item->timestamp > date_create()->modify("- $this->maxEditInDays days") ? true : false;
					?>
					<?php if ($canEdit === true) : ?>
						<input type="image" src="components/com_asinustimetracking/assets/images/edit.gif" value="ed" class="ttbutton" style="text-decoration: none;" onclick="javascript:editEntry('<?php echo $item->ct_id; ?>')"/>
						<input type="image" src="components/com_asinustimetracking/assets/images/delete.gif" value="del" class="ttbutton" onclick="javascript:deleteEntry('<?php echo $item->ct_id; ?>')" />
					<?php endif ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<?php echo JText::_('Empty result set.'); ?>
<?php endif ?>