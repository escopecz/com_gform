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
?>

<?php if($this->items) : ?>

    <div class="items">

        <ul class="items_list">

            <?php foreach ($this->items as $item) :?>

                
				<li><a href="<?php echo JRoute::_('index.php?option=com_gform&view=step&id=' . (int)$item->id); ?>"><?php echo $item->title; ?></a></li>

            <?php endforeach; ?>

        </ul>

    </div>

     <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
    <?php if(JFactory::getUser()->authorise('core.create', 'com_gform.step')): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_gform&task=step.edit&id=0'); ?>">Add</a>
	<?php endif; ?>
<?php else: ?>
    
    There are no items in the list

<?php endif; ?>