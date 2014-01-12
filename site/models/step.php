<?php
/**
 * @copyright   Copyright (C) 2013 Jan Linhart. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Gform model.
 */
class GformModelStep extends JModelForm
{
    var $_item = null;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_gform');

		// Load state from the request userState on edit or from the passed variable on default
		if (JFactory::getApplication()->input->get('layout') == 'edit') 
		{
		    $id = JFactory::getApplication()->getUserState('com_gform.edit.step.id');
		} 
		else 
		{
		    $id = JFactory::getApplication()->input->get('id');
		    JFactory::getApplication()->setUserState('com_gform.edit.step.id', $id);
		}

		$this->setState('step.id', $id);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) 
			{
				$id = $this->getState('step.id');
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published) 
					{
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
				if(isset($this->_item->next_step))
				{
				    $nextStepArray = explode(',', $this->_item->next_step);
				    //pick randomly one of the items from the array
				    $this->_item->next_step = (int)$nextStepArray[array_rand($nextStepArray)];
				}
			} 
			elseif ($error = $table->getError())
			{
				$this->setError($error);
			}
		}

        $this->adjustFormTag($this->_item->html);

		return $this->_item;
	}

    public function adjustFormTag()
    {
        if(!isset($this->_item->html))
        {
            return false;
        }
        
        $this->_item->html = str_replace('{id}', $this->_item->id, $this->_item->html);
        
        $this->_item->html = str_replace('{sessionid}', JFactory::getSession()->getId(), $this->_item->html);
        
        $link = '<a class="nextStep btn" href="'.JRoute::_('index.php?option=com_gform&task=step.next&id='.$this->_item->id).'">';
        $link .= JText::_('COM_GFORM_BUTTON_NEXT_STEP');
        $link .= '</a>';
        $this->_item->html = str_replace('{next}', $link, $this->_item->html);
        $this->_item->html .= "
                <script>
                    jQuery('a.nextStep').on('click', function(event){
                        event.preventDefault();
                        var url = jQuery(this).attr('href') + '&tmpl=component';
                        jQuery().loadNewStep(url);
                    });            
                </script>
            ";
        
        if($this->_item->countdown > 0)
        {
            $this->_item->html = str_replace('{time}', '<span id="GFormTime">'.$this->_item->countdown.'</span>', $this->_item->html);
            $this->_item->html .= '
                <script>
                    jQuery().countDownGForm(
                        \''.JRoute::_('index.php?option=com_gform&task=step.next&id='.$this->_item->id.'&tmpl=component').'\', 
                        '.$this->_item->countdown.'
                    );             
                </script>
            ';
        }
    }

	public function getTable($type = 'Step', $prefix = 'GformTable', $config = array())
	{   
            $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
            return JTable::getInstance($type, $prefix, $config);
	}     

	/**
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int)$this->getState('step.id');

		if ($id) 
		{
            
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
            if (method_exists($table, 'checkin')) 
            {
                if (!$table->checkin($id)) 
                {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int)$this->getState('step.id');

		if ($id) 
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
            if (method_exists($table, 'checkout')) 
            {
                if (!$table->checkout($user->get('id'), $id)) 
                {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}

		return true;
	}    
    
	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
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
		$data = $this->getData(); 
        
        return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 */
	public function save($data)
	{
		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('step.id');
        $user = JFactory::getUser();

        if($id) 
        {
            //Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'step.'.$id);
        }
        else
        {
            //Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_gform');
        }

        if ($authorised !== true) 
        {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }

		$table = $this->getTable();

        if ($table->save($data) === true) 
        {
            return $id;
        }
        else 
        {
            return false;
        }
	}    
}