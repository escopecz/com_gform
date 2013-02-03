<?php
/**
 * @version     1.0.0
 * @package     com_gform
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class GformController extends JController
{
    public function GformController(){
        $doc =& JFactory::getDocument();
        $doc->addScript("https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js");
        $doc->addScriptDeclaration('jQuery.noConflict();');
        parent::__construct();
    }

}