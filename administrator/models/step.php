<?php

/**
 * @copyright   Copyright (C) 2013 Jan Linhart. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Gform model.
 */
class GformModelstep extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 */
	protected $text_prefix = 'COM_GFORM';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'Step', $prefix = 'GformTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_gform.step', 'step', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
        {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_gform.edit.step.data', array());

		if (empty($data)) 
        {
			$data = $this->getItem();

		}
		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) 
        {
			//Do any procesing on fields here if needed
		}

        if(isset($item->next_step))
        {
            $item->next_step = explode(',', $item->next_step);
        }

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) 
        {
			// Set ordering to the last item if not set
			if (@$table->ordering === '') 
            {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__gform_steps');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}
		}
	}
    
    public function save($data) 
    {
        $data['html'] = $this->adjustFormTag($data['html']);

        if(isset($data['next_step']) && $data['next_step'])
        {
            $data['next_step'] = implode(',', $data['next_step']);
        }

        parent::save($data);
        return true;
    }

    public function adjustFormTag($html)
    {
        if (!isset($html))
        {
            return false;
        }

        $html = trim($html);
        $doc = new DOMDocument();
        $doc->validateOnParse = true;
        $doc->loadHTML($html);

        if($doc->getElementById('ss-form'))
        {
            $form = '
                <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;" 
                onload="jQuery().submitGForm(\'index.php?option=com_gform&task=step.next&id={id}\')">
                </iframe>

                <form id="FGorm" action="'.$doc->getElementById('ss-form')->getAttribute('action').'" method="post"
                target="hidden_iframe" onsubmit="submitted=true;">
            ';
            $form .= $this->extract_id($html, 'ss-form');
            $form .= '</form>';

            return $form;
        }
        return $html;
    }

    /**	 
    * Extract an element by ID from an HTML document
    * Thanks http://codjng.blogspot.com/2009/10/unicode-problem-when-using-domdocument.html
    *
    * @param string $content A website
    *
    * @return string HTML content
    */
   function extract_id( $content, $id ) 
   {
       // use mb_string if available
       if ( function_exists( 'mb_convert_encoding' ) )
               $content = mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8');
       $dom= new DOMDocument();
       $dom->loadHTML( $content );
       //$dom->preserveWhiteSpace = false;	
       $element = $dom->getElementById( $id );
       $innerHTML = $this->innerHTML( $element );
       return( $innerHTML ); 
   }

   /**	 
    * Helper, returns the innerHTML of an element
    *
    * @param object DOMElement
    *
    * @return string one element's HTML content
    */
   function innerHTML( $contentdiv ) 
   {
       $r = '';
       $elements = $contentdiv->childNodes;
       
       foreach( $elements as $element ) 
       { 
           if ( $element->nodeType == XML_TEXT_NODE ) 
           {
               $text = $element->nodeValue;
               $r .= $text;
           }	 
           // FIXME we should return comments as well
           elseif ( $element->nodeType == XML_COMMENT_NODE ) 
           {
               $r .= '';
           }	 
           else 
           {
               $r .= '<';
               $r .= $element->nodeName;
               if ( $element->hasAttributes() ) 
               { 
                   $attributes = $element->attributes;
                   foreach ( $attributes as $attribute )
                       $r .= " {$attribute->nodeName}='{$attribute->nodeValue}'" ;
               }	 
               $r .= '>';
               $r .= $this->innerHTML( $element );
               $r .= "</{$element->nodeName}>";
           }	 
       }	 
       return $r;
   }
}
