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
<form action="" method="post" name="adminForm" id="adminForm" class="form-validate">
	<input type="hidden" name="task" value="services" />
	<input type="hidden" name="cgid" value="<?php echo $this->project->cg_id; ?>" />
	<input type="hidden" name="option" value="com_asinustimetracking" />
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span12">
				<div class="control-group">
					<div class="control-label">
						<label for="description"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_TITLE' ); ?></label>
					</div>
					<div class="controls">
						<input class="text_area" type="text" name="description" size="32" maxlength="30"
							value="<?php echo $this->project->description; ?>">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for="state" title="">
							<?php echo JText::_('JSTATUS'); ?>
						</label>
					</div>
					<div class="controls">
						<select name="state">
							<option value="1"<?php if ($this->project->state == 1) echo 'selected="selected"'; ?>><?php echo JText::_('JPUBLISHED'); ?></option>
							<option value="0"<?php if ($this->project->state == 0) echo 'selected="selected"'; ?>><?php echo JText::_('JUNPUBLISHED'); ?></option>
							<option value="2"<?php if ($this->project->state == 2) echo 'selected="selected"'; ?>><?php echo JText::_('JARCHIVED'); ?></option>
							<option value="-2"<?php if ($this->project->state == -2) echo 'selected="selected"'; ?>><?php echo JText::_('JTRASHED'); ?></option>
						</select>
					</div>
				</div>
			</div>
			<div class="span3">
			</div>
		</div>
	</div>
</form>