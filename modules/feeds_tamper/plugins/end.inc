<?php

/**
 * @file
 * Returns last element of an array.
 */

$plugin = array(
  'form' => 'feeds_tamper_end_form',
  'callback' => 'feeds_tamper_end_callback',
  'name' => 'Get last element',
  'multi' => 'direct',
  'category' => 'List',
);

function feeds_tamper_end_form($importer, $element_key, $settings) {
  $form = array();
  $form['help'] = array(
    '#markup' => t('Get the last element of an array, or do nothing if the field is not an array.'),
  );
  return $form;
}

function feeds_tamper_end_callback($result, $item_key, $element_key, &$field, $settings, $source) {
  if(is_array($field)) {
    $field = end($field);
  }
}
