<?php
/**
 * @package     CSVI
 * @subpackage  Maintenance
 *
 * @author      Roland Dalmulder <contact@csvimproved.com>
 * @copyright   Copyright (C) 2006 - 2015 RolandD Cyber Produksi. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link        http://www.csvimproved.com
 */

defined('_JEXEC') or die;
JHtml::_('formbehavior.chosen');
?>
<form method="post" action="<?php echo JRoute::_('index.php?option=com_csvi&view=maintenance'); ?>" id="adminForm" name="adminForm" enctype="multipart/form-data" class="form-horizontal">
	<div class="row-fluid">
		<legend><?php echo JText::_('COM_CSVI_MAKE_CHOICE_MAINTENANCE'); ?></legend>
		<div id="maintenance">
			<?php
			echo JHtml::_(
				'select.genericlist',
				$this->components,
				'component',
				'class="advancedSelect" onchange="document.adminForm.task.value=\'\'; CsviMaint.loadOperation();"',
				'value',
				'text',
				null,
				false,
				true
			);
			?>
			<?php
			echo JHtml::_(
				'select.genericlist',
				$this->options,
				'operation',
				'class="advancedSelect" onchange="document.adminForm.task.value=\'\'; CsviMaint.loadOptions();"'
			);
			?>
		</div>
		<hr />
		<div id="optionfield"></div>
	</div>
	<input type="hidden" name="task" value="read" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />
	<!-- Used to generate the correct cron line -->
	<input type="hidden" name="from" value="maintenance" />
</form>

<!-- Load the modal skeleton -->
<?php
$layout = new JLayoutFile('csvi.modal');
echo $layout->render(
	array(
		'modal-id' => 'warning',
		'modal-header' => JText::_('COM_CSVI_ERROR'),
		'modal-body' => '<span class="error">' . JText::_('COM_CSVI_NO_CHOICE') . '</span>',
		'ok-btn-dismiss' => true
	)
);
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'hidetips')
		{
			if (document.adminForm.task.value == 'hidetips')
			{
				jQuery('.help-block').hide();
				document.adminForm.task.value = '';
			}
			else
			{
				jQuery('.help-block').show();
				document.adminForm.task.value = 'hidetips';
			}

			return false;
		}
		else
		{
			if (jQuery('#csviModal').length > 0)
			{
				jQuery('#csviModal').modal('show');

				jQuery('.ok-btn').on('click', function(e) {
					e.preventDefault();
					jQuery('#csviModal').modal('hide');
					execute(task);
				});

				jQuery('.cancel-btn').on('click', function(e) {
					e.preventDefault();
					jQuery('#csviModal').modal('hide');
				});
			}
			else
			{
				execute(task);
			}
		}
	};

	function execute(task) {
		if (jQuery('#operation').val() === null || jQuery('#operation').val() == '')
		{
			jQuery('#warning').modal('show');

			return false;
		}
		else
		{
			Joomla.submitform(task);
		}
	};
</script>
