<?php
/**
 * @package     CSVI
 * @subpackage  Fields
 *
 * @author      Roland Dalmulder <contact@csvimproved.com>
 * @copyright   Copyright (C) 2006 - 2015 RolandD Cyber Produksi. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link        http://www.csvimproved.com
 */

defined('_JEXEC') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('CsviForm');

/**
 * elect list form field with templates.
 *
 * @package     CSVI
 * @subpackage  Fields
 * @since       4.0
 */
class JFormFieldCsviTemplates extends JFormFieldCsviForm
{
	/**
	 * Name of the field
	 *
	 * @var    string
	 * @since  4.0
	 */
	protected $type = 'CsviTemplates';

	/**
	 * Get the export templates set for front-end export.
	 *
	 * @return  array  List of templates.
	 *
	 * @since   4.0
	 */
	protected function getOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('csvi_template_id', 'value') . ',' . $db->quoteName('template_name', 'text'))
			->from($db->quoteName('#__csvi_templates'))
			->where($db->quoteName('action') . ' = ' . $db->quote('export'))
			->where($db->quoteName('frontend') . ' = 1')
			->order($db->quoteName('template_name'));
		$db->setQuery($query);
		$templates = $db->loadObjectList();

		return $templates;
	}
}
