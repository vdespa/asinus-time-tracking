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

defined('_JEXEC') or die;
?>

<div id="j-main-container" class="span10">
	<?php if (empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
	<?php else : ?>
	<table class="table table-striped" id="articleList">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_ASINUSTIMETRACKING_ID'); ?></th>
			<th><?php echo JText::_('COM_ASINUSTIMETRACKING_NAME'); ?></th>
			<th><?php echo JText::_('COM_ASINUSTIMETRACKING_USERNAME'); ?></th>
			<th><?php echo JText::_('COM_ASINUSTIMETRACKING_ADMIN'); ?></th>
			<th><?php echo JText::_('COM_ASINUSTIMETRACKING_ROLE'); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php
			// FIXME: The model should not be called here!
			$ctUser = $this->model->getOrCreateCtUserByUid($item->id);
			$editLink = JRoute::_('index.php?option=com_asinustimetracking&task=useredit&cid[]='. $ctUser->cuid);
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo $item->id; ?>
				</td>
				<td>
					<a href='<?php echo $editLink; ?>'><?php echo $item->name; ?></a>
				</td>
				<td>
					<?php echo $item->name; ?>
				</td>
				<td>
					<input type="checkbox" id="is_worktime"
						name="is_worktime" value="1" readonly="readonly"
						<?php echo ($ctUser->is_admin == 1) ? 'checked="checked"' : ''; ?>
				</td>
				<td>
					<?php echo $ctUser->roledesc; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>