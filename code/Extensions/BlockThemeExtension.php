<?php
/**
 * @file BlockThemeExtension
 * @author Simon Winter <simon@saltedherring.com>
 *
 * A theme extension to allow required blocks to have theming.
 * A theme simply adds one or more css classes to a rendered template.
 *
 * To configure the available themes, add a section to the config.yml file:
 *
 * Block:
 * ...
 *   Themes:
 *     blackBlue:
 *       title: "Black Blue"
 *       css: "black-blue"
 *     brown:
 *       title: "Brown y'all"
 *       css: "brown"
 */
class BlockThemeExtension extends Extension
{
    private static $db = array(
    'Theme' => 'Varchar(20)'
  );

    public function updateCMSFields(FieldList $fields)
    {
        // Make sure that the option field exists
        if (!$fields->fieldByName('Options')) {
            $fields->insertBefore($right = RightSidebar::create('Options'), 'Root');
        }

        // Add themes if they exist.
        $config = Config::inst()->get('Block', 'Themes');

        if (!empty($config)) {
            $styles = array();

            foreach ($config as $key => $val) {
                $styles[$val['css']] = $val['title'];
            }

            if (!empty($styles)) {
                $fields->addFieldsToTab('Options',
                    DropdownField::create('Theme', 'Theme', $styles)->setEmptyString('(Select one)')
                );
            }
        }
    }
}
