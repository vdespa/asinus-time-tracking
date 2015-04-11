<?php
/**
 * @package      Asinus Time-Tracking
 *
 * @author       Valentin Despa - info@vdespa.de
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (c) 2014, Valentin Despa. All rights reserved.
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 *
 * This installer script is largely based on the script created for Projektfork by Tobias Kuhn
 * @link https://github.com/projectfork/Projectfork/blob/dev/source/components/com_projectfork/script.php
 */

defined('_JEXEC') or die;

/**
 * Class pkg_asinustimetrackingInstallerScript
 */
class pkg_asinustimetrackingInstallerScript
{
	/**
	 * Minimum supported version
	 *
	 * @var string
	 */
	protected $minSupportedVersion = '2.5.5';

	/**
	 * Maximum supported version
	 *
	 * @var string
	 */
	protected $maxSupportedVersion = '4.0.0';

	/**
	 * Called before any type of action
	 *
	 * @param     string              $route      Which action is happening (install|uninstall|discover_install)
	 * @param     jadapterinstance    $adapter    The object responsible for running this script
	 *
	 * @return    boolean                         True on success
	 */
	public function preflight($route, JAdapterInstance $adapter)
	{
		// Installing component manifest file version
		$this->release = $adapter->get( "manifest" )->version;

		// Joomla version check
		if (!version_compare(JVERSION, $this->minSupportedVersion, 'ge'))
		{
			$adapter->get('parent')->abort('Unsupported version! Asinus Time-Tracking requires Joomla ' . $this->minSupportedVersion . ' or newer.');
			return false;
		}
		if (version_compare(JVERSION, $this->maxSupportedVersion, '>='))
		{
			$adapter->get('parent')->abort('Unsupported version! Asinus Time-Tracking does not currently support Joomla 3. Please use Joomla 2.5.');
			return false;
		}

		// Abort if the component being installed is not newer than the currently installed version
		if ($route == 'update')
		{
			$oldRelease = $this->getParam('version');
			$rel = $oldRelease . ' to ' . $this->release;
			if ( version_compare( $this->release, $oldRelease, 'le' ) )
			{
				$adapter->get('parent')->abort('Incorrect version sequence. Cannot upgrade ' . $rel);
				return false;
			}
		}

		if (JDEBUG) {
			JProfiler::getInstance('Application')->mark('before' . ucfirst($route) . 'Asinus Time-Tracking');
		}

		return true;
	}

	/*
	 * Get a variable from the manifest from the manifest cache.
	 */
	function getParam( $name ) {
		$db = JFactory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "asinustimetracking"');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[$name];
	}


	/**
	 * Called after any type of action
	 *
	 * @param     string              $route      Which action is happening (install|uninstall|discover_install)
	 * @param     jadapterinstance    $adapter    The object responsible for running this script
	 *
	 * @return    boolean                         True on success
	 */
	public function postflight($route, JAdapterInstance $adapter)
	{
		if (JDEBUG) {
			JProfiler::getInstance('Application')->mark('after' . ucfirst($route) . 'Asinus Time-Tracking');

			$buffer = JProfiler::getInstance('Application')->getBuffer();
			$app    = JFactory::getApplication();

			foreach ($buffer as $mark)
			{
				$app->enqueueMessage($mark, 'debug');
			}
		}

		return true;
	}
}
