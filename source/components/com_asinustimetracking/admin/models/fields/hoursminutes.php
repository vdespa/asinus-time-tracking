<?php
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/*
 * @link http://docs.joomla.org/Adding_custom_fields_to_core_components_using_a_plugin
 */

class JFormFieldHoursminutes extends JFormFieldList {

	//The field class must know its own type through the variable $type.
	protected $type = 'hoursminutes';

	public function getLabel() {
		return 'WIP';
	}

	public function getInput() {
		return 'WIP';
	}
}