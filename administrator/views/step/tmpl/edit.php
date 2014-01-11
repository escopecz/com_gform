<?php

/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_gform/assets/css/gform.css');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'step.cancel' || document.formvalidator.isValid(document.id('step-form'))) {
			Joomla.submitform(task, document.getElementById('step-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gform&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="step-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_GFORM_LEGEND_STEP'); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?></li>
                <li><?php echo $this->form->getLabel('showTitle'); ?>
				<?php echo $this->form->getInput('showTitle'); ?></li>
				<li><?php echo $this->form->getLabel('countdown'); ?>
				<?php echo $this->form->getInput('countdown'); ?></li>
                <li><?php echo $this->form->getLabel('next_step'); ?>
				<?php echo $this->form->getInput('next_step'); ?></li>
				<li><?php echo $this->form->getLabel('html'); ?>
				<?php echo $this->form->getInput('html'); ?></li>
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>
            </ul>
		</fieldset>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>
