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
	<input type="hidden" name="ct_cpid" value="<?php echo $this->cpid; ?>" />
	<input type="hidden" name="ct_csid" value="<?php echo $this->csid; ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $this->cuid; ?>" />
	<input type="hidden" name="ct_cuid" value="<?php echo $this->cuid; ?>" />
	<input type="hidden" name="task" value="pricerange" />
	<input type="hidden" name="option" value="com_asinustimetracking" />
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span12">
				<?php if ($this->priceRange->name && $this->priceRange->description) : ?>
				<?php printf("<h3>%s, %s</h3>", $this->priceRange->name, $this->priceRange->description); ?>
				<?php endif;?>
				<div class="control-group">
					<div class="control-label">
						<label for=""><?php echo JText::_( 'Start' ); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::calendar(date('d.m.Y', $this->cpid > - 1 ? strtotime($this->priceRange->start_time) : strtotime("first day of last month")), "ct_startdate", 'ct_cal_start', ('%d.%m.%Y'), 'size=12 class=inputbox'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for=""><?php echo JText::_( 'Ende' ); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::calendar(date('d.m.Y', $this->cpid > -1 ? strtotime($this->priceRange->end_time) : strtotime("last day of last month")), "ct_enddate", 'ct_cal_end', ('%d.%m.%Y'), 'size=12 class=inputbox'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<label for="ct_price"><?php echo JText::_( 'Preis' ); ?></label>
					</div>
					<div class="controls">
						<input type="text" value="<?php echo $this->priceRange->price; ?>" name="ct_price">
					</div>
				</div>
			</div>
			<div class="span3">
			</div>
		</div>
	</div>
</form>