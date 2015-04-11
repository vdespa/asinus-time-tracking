<?php
defined('_JEXEC') or die;

/**
 * View class for a list of projects.
 */
class AsinusTimeTrackingViewProjects extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_ASINUSTIMETRACKING_TOOLBAR_PROJECTS'), 'banners-clients.png');
		JToolBarHelper::addNew('project.add');
		JToolBarHelper::editList('project.edit');
	}
}
