<?php
/**
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later
 * @author      Jan Linhart <admin@escope.cz> - http://escope.cz
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$this->hiddenFieldsets = array();
$this->hiddenFieldsets[0] = 'basic-limited';
$this->configFieldsets = array();
$this->configFieldsets[0] = 'editorConfig';

// Create shortcut to parameters.
$params = $this->state->get('params');

$app = JFactory::getApplication();
$input = $app->input;
$assoc = JLanguageAssociations::isEnabled();

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);
$editoroptions = isset($params->show_publishing_options);

if (!$editoroptions)
{
	$params->show_publishing_options = '1';
	$params->show_article_options = '1';
	$params->show_urls_images_backend = '0';
	$params->show_urls_images_frontend = '0';
}

// Check if the article uses configuration settings besides global. If so, use them.
if (isset($this->item->attribs['show_publishing_options']) && $this->item->attribs['show_publishing_options'] != '')
{
	$params->show_publishing_options = $this->item->attribs['show_publishing_options'];
}

if (isset($this->item->attribs['show_article_options']) && $this->item->attribs['show_article_options'] != '')
{
	$params->show_article_options = $this->item->attribs['show_article_options'];
}

if (isset($this->item->attribs['show_urls_images_frontend']) && $this->item->attribs['show_urls_images_frontend'] != '')
{
	$params->show_urls_images_frontend = $this->item->attribs['show_urls_images_frontend'];
}

if (isset($this->item->attribs['show_urls_images_backend']) && $this->item->attribs['show_urls_images_backend'] != '')
{
	$params->show_urls_images_backend = $this->item->attribs['show_urls_images_backend'];
}

?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'step.cancel' || document.formvalidator.isValid(document.id('item-form')))
		{
			<?php //echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gform&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="form-horizontal">
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

		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="return" value="<?php echo $input->getCmd('return'); ?>" />
		<?php echo JHtml::_('form.token'); ?>

		</div>
</form>
