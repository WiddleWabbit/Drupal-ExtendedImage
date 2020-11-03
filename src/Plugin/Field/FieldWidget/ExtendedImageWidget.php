<?php

namespace Drupal\oem_extended_image\Plugin\Field\FieldWidget;

use Drupal\image\Plugin\Field\FieldWidget\ImageWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Provides the field widget for Symbol field.
 *
 * @FieldWidget(
 *   id = "extendedimage_widget",
 *   label = @Translation("Extended Image"),
 *   description = @Translation("An Image field with a text field for a description"),
 *   field_types = {
 *     "extendedimagefield"
 *   }
 * )
 */
class ExtendedImageWidget extends ImageWidget {

    /**
     * {@inheritdoc}
     */
    public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // The formElement method returns the form for a single field widget (Used to render the form in the Admin Interface of Drupal)
    // We need to add our new field to this
        
        // Get the parents form elements
        $element = parent::formElement($items, $delta, $element, $form, $form_state);

        // Get the field settings
        $field_settings = $this->getFieldSettings();

        // Add the field setting for the description field to the array, so that the process function can access it to see if it is enabled
        $element['#field_description'] = $field_settings['field_description'];

        // Return the updated widget
        return $element;

    }

     /**
     * {@inheritdoc}
     */
    public static function process($element, FormStateInterface $form_state, $form) {

        $item = $element['#value'];
        $item['fids'] = $element['fids']['#value'];

        // Add the render array for our new field
        $element['description'] = array(
            '#title' => t('Description'),
            '#type' => 'textfield',
            '#default_value' => isset($item['description']) ? $item['description'] : '',
            '#description' => t('A description of the image that has been added.'),
            // #access renders to page only under certain conditions
            // $item[fids] (Does it have an image specified)
            // $element[#field_description] (is the #field_description setting set to 1?)
            '#access' => (bool) $item['fids'] && $element['#field_description'],
            '#maxlength' => '512',
            '#weight' => '-10',
        );

        // Return the processed image as per Parents method
        return parent::process($element, $form_state, $form);

    }

}
