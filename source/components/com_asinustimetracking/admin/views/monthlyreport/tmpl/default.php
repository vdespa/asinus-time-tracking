<form action="<?php echo JRoute::_('index.php?option=com_asinustimetracking'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_ASINUSTIMETRACKING_MONTHLYREPORT_REPORT_FILTERS'); ?></legend>
			<ul class="adminformlist">

				<li>
					<label for="filter_user"><?php echo JText::_('COM_ASINUSTIMETRACKING_EMPLOYEE_NAME'); ?></label>
					<select name="filter_user" class="inputbox">
						<?php echo JHtml::_('select.options', AsinustimetrackingHelper::getEmployeeOptions(), 'value', 'text');?>
					</select>
				</li>

				<li>
					<label for="filter_customer"><?php echo JText::_('COM_ASINUSTIMETRACKING_COSTUNITS'); ?></label>
					<select name="filter_customer" class="inputbox">
						<?php echo JHtml::_('select.options', AsinustimetrackingHelper::getCustomerOptions(), 'value', 'text', $this->state->filter_customer);?>
					</select>
				</li>

				<li>
					<label for="filter_month"><?php echo JText::_('COM_ASINUSTIMETRACKING_MONTH'); ?></label>
					<select name="filter_month" class="inputbox">
						<?php echo JHtml::_('select.options', AsinustimetrackingHelper::getMonthOptions(), 'value', 'text', $this->state->filter_month);?>
					</select>
				</li>

				<li>
					<label for="filter_month"><?php echo JText::_('COM_ASINUSTIMETRACKING_YEAR'); ?></label>
					<input type="text"
						   name="filter_year"
						   id="filter_year"
						   maxlength="4"
						   size="6"
						   value="<?php echo $this->state->filter_year; ?>"
						   title="<?php echo JText::_('COM_ASINUSTIMETRACKING_YEAR'); ?>"
						/>
				</li>
			</ul>
			<div class="clr"> </div>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="format" value="excel" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>