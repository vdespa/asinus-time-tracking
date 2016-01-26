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
	<input type="hidden" name="crid" value="<?php echo $this->role->crid; ?>" />
	<input type="hidden" name="task" value="services" />
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
							value="<?php echo $this->role->description; ?>">
					</div>
				</div>
			</div>
			<div class="span3">
			</div>
		</div>
	</div>
</form>