<?php
/**
 * @copyright   Copyright (C) 2013 Jan Linhart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Gform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
