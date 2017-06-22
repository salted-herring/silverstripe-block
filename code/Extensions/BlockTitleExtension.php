<?php
/**
 * @file ￼
 *
 */
class BlockTitleExtension extends DataExtension
{
    private static $db = array(
        'Title'         => 'Varchar(128)',
        'DisplayTitle'  => 'Boolean',
        'TitleTags'     => 'Enum("h2,h3,h4,h5,h6,p,span")'
    );
}
