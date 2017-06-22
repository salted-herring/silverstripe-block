<?php
/**
 * @file ￼DisplayPublishedStatusExtension.php
 * @author Simon Winter <simon@saltedherring.com>
 *
 * Displays published status. If the owner class is not versioned, displays "unknown".
 */
class DisplayPublishedStatusExtension extends DataExtension
{
    // Cloned from Heyday\VersionedDataObjects\getCMSPublishedState

    public function updateSummaryFields(&$fields)
    {
        $fields = array_merge(
            $fields,
            array(
                'getPublishedStatus' => 'State'
            )
        );
    }

    public function getPublishedStatus()
    {
        $html = new HTMLText('PublishedState');

        if (!method_exists($this->owner, 'isPublished')) {
            return 'unknown';
        }

        if ($this->owner->isPublished()) {
            if ($this->owner->stagesDiffer('Stage', 'Live')) {
                $colour = '#1391DF';
                $text = 'Modified';
            } else {
                $colour = '#18BA18';
                $text = 'Published';
            }
        } else {
            $colour = '#C00';
            $text = 'Draft';
        }

        $html->setValue(sprintf(
            '<span style="color: %s;">%s</span>',
            $colour,
            htmlentities($text)
        ));

        return $html;
    }
}
