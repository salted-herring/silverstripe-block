<?php
/**
 * @filesource ￼Block.php
 * @author Leo Chen <leo@saltedherring.com>
 *
 * Base Block DBO that all blocks extend.
 */
class Block extends DataObject
{
    private static $db = array(
      'SortOrder'             =>    'Int',
      'Description'           =>    'Varchar(128)'
    );

    private static $many_many = array(
      'Pages'                 =>    'Page'
    );

    private static $default_sort = array(
      'SortOrder'             =>    'ASC',
      'ID'                    =>    'DESC'
    );

    private static $create_table_options = array(
      'MySQLDatabase'         => 'ENGINE=MyISAM'
    );

    private static $extensions = array(
      'StandardPermissions'
    );

    private static $summary_fields = array(
      'BlockType',
      'Title',
      'Description',
      'Published'
    );

    private static $field_labels = array(
      'BlockType'            =>    'Block type',
    );

    public function BlockType()
    {
        return $this->singular_name();
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root', 'Pages');
        $fields->removeFieldsFromTab('Root.Main', array('SortOrder'));

        $fields->addFieldToTab('Root.Main',
            LiteralField::create('Status', 'Published: ' . $this->Published()), 'Title');

        $description = $fields->fieldByName('Root.Main.Description')->setDescription('A brief outline of what the block is used for.');

        return $fields;
    }

    // public function onBeforeWrite()
    // {
    //     parent::onBeforeWrite();
    //
    //     if (empty($this->byPass)) {
    //         $this->readmode = Versioned::get_reading_mode();
    //         Versioned::set_reading_mode('Stage.Stage');
    //     }
    // }
    //
    // public function onAfterWrite()
    // {
    //     parent::onAfterWrite();
    //     if (isset($this->readmode)) {
    //         Versioned::set_reading_mode('Stage.' . $this->readmode);
    //     }
    // }

    public function forTemplate()
    {
        return $this->renderWith(array($this->getClassName(), 'BaseBlock'));
    }

    public function frontendEditable()
    {
        $member = Member::currentUser();
        return $this->canEdit($member) && Config::inst()->get('Block', 'FrontendEditable');
    }

    public function Type2Class()
    {
        return strtolower(str_replace(' ', '-', $this->singular_name()));
    }

    public function doPublish()
    {
        $this->writeToStage('Live');
    }

    public function Published()
    {
        return $this->isPublished() ? 'Yes' : 'No';
    }

    public function isPublished()
    {
        if (!empty(Versioned::get_by_stage('Block', 'Live')->byID($this->ID))) {
            return true;
        }

        return false;
    }
}
