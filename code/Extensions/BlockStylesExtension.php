<?php
/**
 * @file ￼BlockStyleExtension
 * @author Simon Winter <simon@saltedherring.com>
 *
 * Extension to allow for padding & margin on a block.
 */
class BlockStylesExtension extends DataExtension
{
    private static $db = array(
        'addMarginTop'          =>    'Boolean',
        'addMarginBottom'       =>    'Boolean',
        'addPaddingTop'         =>    'Boolean',
        'addPaddingBottom'      =>    'Boolean'
    );

    public function updateCMSFields(FieldList $fields)
    {
        if (!$fields->fieldByName('Options')) {
            $fields->insertBefore($right = RightSidebar::create('Options'), 'Root');
        }

        $fields->addFieldsToTab('Options', array(
            HeaderField::create('Style', 'Style Options', 4),
            CheckboxField::create('addMarginTop', 'add top margin'),
            CheckboxField::create('addMarginBottom', 'add bottom margin'),
            CheckboxField::create('addPaddingTop', 'add top padding'),
            CheckboxField::create('addPaddingBottom', 'add bottom padding')
        ));
    }
}
