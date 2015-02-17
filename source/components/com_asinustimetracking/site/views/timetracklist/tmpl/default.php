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

require_once JPATH_COMPONENT . '/views/timetracklist/tmpl/ui.php';
?>
 
<?php echo UI::getJavaScripts($this->entriesDays, $this->model, $this->ctUser); ?>

<h1><?php echo JText::_("COM_ASINUSTIMETRACKING_TITLE"); ?> <?php echo $this->user->name; ?></h1>

<form action="index.php" method="post"
	name="form_timetracklist"><br />
<?php echo UI::getSearchPanel($this->ctStartDate, $this->ctEndDate, $this->ctUser->cuid, $this->ctfm, $this->cttm, $this->costList, $this->ctcc); ?>
 <?php $res = UI::getDataTable($this->entriesDays, $this->model, $this->ctUser, $this->ctcc, $this->cfg);
echo $res->content; ?>

<!-- Submit Fields --> <input type="hidden" name="option"
	value="com_asinustimetracking"> <br />
<input type="hidden" name="view" value="timetracklist" /> <br />
<input type="hidden" name="task" value="default" /> <br />
<input type="hidden" name="ct_id" value="<?php echo $this->ctid; ?>" />
<br />
<input type="hidden" name="Itemid"
	value="<?php echo JRequest::getInt('Itemid', 0); ?>" /> <br />
</form>
