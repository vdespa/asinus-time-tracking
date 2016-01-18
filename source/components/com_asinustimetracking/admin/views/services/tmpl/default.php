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
<form action="index.php" method="post" name="adminForm" id="adminForm">

	<input type="hidden" name="option" value="com_asinustimetracking" />
	<input type="hidden" name="task" value="services" />
	<input type="hidden" name="boxchecked" value="0" />

	<div id="j-main-container" class="span10">
		<?php if (empty($this->services)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="articleList">
				<thead>
				<tr>
					<th><?php echo JText::_('COM_ASINUSTIMETRACKING_ID'); ?></th>
					<th>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th><?php echo JText::_('COM_ASINUSTIMETRACKING_SERVICE'); ?></th>
					<th><?php echo JText::_('COM_ASINUSTIMETRACKING_WORKTIME'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->services as $i => $service) : ?>
					<?php
					$checked = JHTML::_('grid.id', $i, $service->csid);
					$editLink = JRoute::_('index.php?option=com_asinustimetracking&task=servicesedit&cid[]='. $service->csid );
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>
							<?php echo $service->csid; ?>
						</td>
						<td><?php echo $checked; ?></td>
						<td>
							<a href='<?php echo $editLink; ?>'><?php echo $service->description; ?></a>
						</td>
						<td>
							<input type="checkbox" id="is_worktime"
								name="is_worktime" value="1" readonly="readonly" disabled
							<?php echo ($service->is_worktime == 1) ? 'checked="checked"' : ''; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</form>
