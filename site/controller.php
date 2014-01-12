<?php
/**
 * @copyright   Copyright (C) 2013 Jan Linhart. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class GformController extends JControllerLegacy
{
    public function GformController()
    {
        $doc =& JFactory::getDocument();
        $doc->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js");
        $doc->addScriptDeclaration('jQuery.noConflict();');
        parent::__construct();
    }
}