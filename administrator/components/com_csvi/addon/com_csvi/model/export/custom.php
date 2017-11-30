<?php
/**
 * @package     CSVI
 * @subpackage  CSVI
 *
 * @author      Roland Dalmulder <contact@csvimproved.com>
 * @copyright   Copyright (C) 2006 - 2015 RolandD Cyber Produksi. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link        http://www.csvimproved.com
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_csvi/models/exports.php';

/**
 * Export custom tables.
 *
 * @package     CSVI
 * @subpackage  CSVI
 * @since       6.0
 */
class Com_CsviModelExportCustom extends CsviModelExports
{
	/**
	 * Export the data.
	 *
	 * @return  void.
	 *
	 * @since   6.0
	 */
	protected function exportBody()
	{
		if (parent::exportBody())
		{
			// Build something fancy to only get the fieldnames the user wants
			$userfields = array();
			$exportfields = $this->fields->getFields();

			// Group by fields
			$groupbyfields = json_decode($this->template->get('groupbyfields', '', 'string'));
			$groupby = array();

			if (isset($groupbyfields->name))
			{
				$groupbyfields = array_flip($groupbyfields->name);
			}
			else
			{
				$groupbyfields = array();
			}

			// Sort selected fields
			$sortfields = json_decode($this->template->get('sortfields', '', 'string'));
			$sortby = array();

			if (isset($sortfields->name))
			{
				$sortbyfields = array_flip($sortfields->name);
			}
			else
			{
				$sortbyfields = array();
			}

			foreach ($exportfields as $field)
			{
				switch ($field->field_name)
				{
					// Man made fields, do not export them
					case 'custom':
						break;
					default:
						$userfields[] = $this->db->quoteName($field->field_name);

						if (array_key_exists($field->field_name, $groupbyfields))
						{
							$groupby[] = $this->db->quoteName($field->field_name);
						}

						if (array_key_exists($field->field_name, $sortbyfields))
						{
							$sortby[] = $this->db->quoteName($field->field_name);
						}
						break;
				}
			}

			// Build the query
			$userfields = array_unique($userfields);
			$query = $this->db->getQuery(true);
			$query->select(implode(",\n", $userfields));
			$query->from($this->db->quoteName("#__" . $this->template->get('custom_table')));

			// Group the fields
			$groupby = array_unique($groupby);

			if (!empty($groupby))
			{
				$query->group($groupby);
			}

			// Sort set fields
			$sortby = array_unique($sortby);

			if (!empty($sortby))
			{
				$query->order($sortby);
			}

			// Add export limits
			$limits = $this->getExportLimit();

			// Execute the query
			$this->csvidb->setQuery($query, $limits['offset'], $limits['limit']);
			$this->log->add('Export query' . $query->__toString(), false);

			// Check if there are any records
			$logcount = $this->csvidb->getNumRows();

			if ($logcount > 0)
			{
				while ($record = $this->csvidb->getRow())
				{
					$this->log->incrementLinenumber();

					foreach ($exportfields as $field)
					{
						$fieldname = $field->field_name;

						// Set the field value
						if (isset($record->$fieldname))
						{
							$fieldvalue = $record->$fieldname;
						}
						else
						{
							$fieldvalue = '';
						}

						// Store the field value
						$this->fields->set($field->csvi_templatefield_id, $fieldvalue);
					}

					// Output the data
					$this->addExportFields();

					// Output the contents
					$this->writeOutput();
				}
			}
			else
			{
				$this->addExportContent(JText::_('COM_CSVI_NO_DATA_FOUND'));

				// Output the contents
				$this->writeOutput();
			}
		}
	}
}
