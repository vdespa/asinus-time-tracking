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
	<input type="hidden" name="task" value="roles" />
	<input type="hidden" name="boxchecked" value="0" />

	<div id="j-main-container" class="span10">
		<?php if (empty($this->projects)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped" id="articleList">
				<thead>
				<tr>
					<th width="1%"><?php echo JText::_('COM_ASINUSTIMETRACKING_ID'); ?></th>
					<th width="1%">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th><?php echo JText::_('COM_ASINUSTIMETRACKING_PROJECT'); ?></th>
					<th><?php echo JText::_('JSTATUS'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($this->projects as $i => $project) : ?>
					<?php
					$checked = JHTML::_('grid.id', $i, $project->cg_id);
					$editLink = JRoute::_('index.php?option=com_asinustimetracking&task=selectionsedit&cid[]='. $project->cg_id );
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>
							<?php echo $project->cg_id; ?>
						</td>
						<td><?php echo $checked; ?></td>
						<td>
							<a href='<?php echo $editLink; ?>'><?php echo $project->description; ?></a>
						</td>
						<td>
							<?php if ($project->state == 1) echo JText::_('JPUBLISHED'); ?>
							<?php if ($project->state == 0) echo JText::_('JUNPUBLISHED'); ?>
							<?php if ($project->state == 2) echo JText::_('JARCHIVED'); ?>
							<?php if ($project->state == -2) echo JText::_('JTRASHED'); ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
</form>
