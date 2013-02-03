<?php
/**
 * @version     1.0.0
 * @package     com_gform
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */

// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_gform', JPATH_ADMINISTRATOR);
?>
<?php if( $this->item ) : ?>
    <div class="item_fields">
        <?php if($this->item->showTitle): ?>
        <h1><?php echo $this->item->title; ?></h1>
        <?php endif; ?>
        <div class="html"><?php echo $this->item->html; ?></div>
    </div>
    <?php if(JFactory::getUser()->authorise('core.edit', 'com_gform.step'.$this->item->id)): ?>
	<a href="<?php echo JRoute::_('index.php?option=com_gform&task=step.edit&id='.$this->item->id); ?>">Edit</a>
    <?php endif; ?>
<?php else: ?>
    Could not load the item
<?php endif; ?>
