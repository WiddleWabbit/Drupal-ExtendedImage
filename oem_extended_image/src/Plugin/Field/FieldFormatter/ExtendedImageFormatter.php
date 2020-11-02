<?php

namespace Drupal\oem_extended_image\Plugin\Field\FieldFormatter;

use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'image' formatter.
 *
 * @FieldFormatter(
 *   id = "extendedimage_formatter",
 *   label = @Translation("Extended Image"),
 *   field_types = {
 *     "extendedimagefield"
 *   },
 * )
 */
class ExtendedImageFormatter extends ImageFormatter {

    public function viewElements(FieldItemListInterface $items, $langcode) {

        // Get parent elements
        $elements = parent::viewElements($items, $langcode);
        $files = $this->getEntitiesToView($items, $langcode);

        foreach ($elements as $delta => $entity) {
            $elements[$delta]['#theme'] = 'extended_image';
        }

        return $elements;

    }

}
