<?php
/**
 * @file
 * The primary PHP file for this theme.
 */

function directory_form_alter(&$form, $form_state, $form_id) {
  if($form['#id'] == 'views-exposed-form-people-page') {
    foreach ($form['field_expertise_tid']['#options'] as $key => &$option) {
      if ($key === 'All') {
        $option = '- Expertise -';
      }
    }
  }
}
