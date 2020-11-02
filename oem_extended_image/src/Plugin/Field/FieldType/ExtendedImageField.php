<?php

namespace Drupal\oem_extended_image\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides the field type of Symbol.
 *
 * @FieldType(
 *   id = "extendedimagefield",
 *   label = @Translation("Extended Image Field"),
 *   default_formatter = "extendedimage_formatter",
 *   default_widget = "extendedimage_widget",
 *   list_class = "\Drupal\file\Plugin\Field\FieldType\FileFieldItemList",
 *   constraints = {"ReferenceAccess" = {}, "FileValidation" = {}},
 * )
 */

class ExtendedImageField extends ImageItem {

    /**
     * {@inheritdoc}
     */
    public static function defaultFieldSettings() {
    // Set a default value for the field setting we are going to add to toggle whether the description field shows
    // These are used to determine what field settings are saved

        // Get the parent field settings
        $settings = parent::defaultFieldSettings();
        // Add our setting to show/hide the description field
        $settings['field_description'] = 0;
        // Add the description field to the default image field settings
        $settings['default_image']['description'] = '';
        // Return the updated settings
        return $settings;

    }

    /**
     * {@inheritdoc}
     */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Property Definitions defines the data the field will contain
    // We add new data to the default contained data here

        // Get the parent properties
        $properties = parent::propertyDefinitions($field_definition);
        // Add a new property for our description text field to the field definition
        $properties['description'] = DataDefinition::create('string')
          ->setLabel(t('Description'))
          ->setDescription(t('A description of the symbol, if entered it shows underneath.'));
        // Return our updated property definitions
        return $properties;

    }

    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $field_definition) {
    // Schema definition defines how the data will be stored by Drupal
    // We need to add a new column to the database table for the new property

        // Get the parent schema
        $schema = parent::schema($field_definition);
        // Add the database column for the table
        $schema['columns']['description'] = array(
            'type' => 'varchar',
            'length' => '512',
            'description' => 'Description text for the symbol.',
        );
        // Return the schema including our new column
        return $schema;

    }

    /**
     * {@inheritdoc}
     */
    public function fieldSettingsForm(array $form, FormStateInterface $form_state) {

        //Get the original field settings form
        $element = parent::fieldSettingsForm($form, $form_state);

        // Get the current settings
        $settings = $this->getSettings();

        // Add the description field enabling checkbox
        $element['field_description'] = [
            '#type' => 'checkbox',
            '#title' => t('<em>Description</em> field'),
            '#default_value' => $settings['field_description'],
            '#description' => t('Short description of the field that may be displayed.'),
            '#weight' => 8,
        ];

        // Return the modified form element
        return $element;

    }

    /**
     * {@inheritdoc}
     */
    protected function defaultImageForm(array &$element, array $settings) {
    // Modifies the default image form to add the new description field
    // &$ references the variable and modifies it, instead of taking a copy

        // Run the parent class
        parent::defaultImageForm($element, $settings);

        // Add our new field to it, no need to return as we are modifying the element directly
        $element['default_image']['description'] = [
            '#title' => t('Description'),
            '#type' => 'textfield',
            '#default_value' => $settings['default_image']['description'],
            '#description' => t('A description of the image that has been added.'),
            '#maxlength' => '512',
        ];

    }

}
