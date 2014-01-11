<?php
/**
 * @copyright   Copyright (C) 2013 Jan Linhart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */


// no direct access
defined('_JEXEC') or die;

if(!defined('DS'))
{
	define('DS',DIRECTORY_SEPARATOR);
}

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_gform')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

$controller	= JControllerLegacy::getInstance('Gform');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
