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
	<input type="hidden" name="cuid" value="<?php echo $this->item->cuid;?>" />
	<input type="hidden" name="option" value="com_asinustimetracking" />
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span12">
				<div class="control-group">
					<div class="control-label">
						<label for="name"><?php echo JText::_( 'COM_ASINUSTIMETRACKING_USERNAME' ); ?></label>
					</div>
					<div class="controls">
						<?php echo $this->item->name; ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for="employee_id" title="">
							<?php echo JText::_('COM_ASINUSTIMETRACKING_EMPLOYEE_ID'); ?>
						</label>
					</div>
					<div class="controls">
						<input type="text" name="employee_id" id="employee_id" value="<?php echo $this->item->employee_id; ?>">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for="is_admin" title="">
							<?php echo JText::_('COM_ASINUSTIMETRACKING_ADMIN'); ?>
						</label>
					</div>
					<div class="controls">
						<input type="checkbox" id="is_admin" name="is_admin" value="1"
							<?php if($this->item->is_admin == 1){ echo "checked=checked"; }?> />
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for="crid" title="">
							<?php echo JText::_('COM_ASINUSTIMETRACKING_ROLE'); ?>
						</label>
					</div>
					<div class="controls">
						<select class="inputbox" name="crid" id="crid">
							<?php
							// FIXME: This should not look like this!
							$selected = '';
							// load user's services
							foreach($this->model->getRoles() as $roles){
								if($this->item->crid == $roles->crid){
									$selected = 'selected';
								}
								echo "<option value='$roles->crid' $selected label='$roles->description'>" . $roles->description . "</option>";
								$selected = '';
							};
							?>
						</select>
					</div>
				</div>
			</div>
			<div class="span3">
			</div>
		</div>
	</div>

<?php
// Load serviced and prices
require_once 'default_services.php';
?>
</form>