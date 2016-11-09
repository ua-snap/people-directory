<?php
/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct URL of the current node.
 * - $display_submitted: Whether submission information should be displayed.
 * - $submitted: Submission information created from $name and $date during
 *   template_preprocess_node().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type; for example, "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type; for example, story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode; for example, "full", "teaser".
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined; for example, $node->body becomes $body. When needing to
 * access a field's raw values, developers/themers are strongly encouraged to
 * use these variables. Otherwise they will have to explicitly specify the
 * desired field language; for example, $node->body['en'], thus overriding any
 * language negotiation rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 *
 * @ingroup templates
 */
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php if ((!$page && !empty($title)) || !empty($title_prefix) || !empty($title_suffix) || $display_submitted): ?>
  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page && !empty($title)): ?>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>
    <?php if ($display_submitted): ?>
    <span class="submitted">
      <?php print $user_picture; ?>
      <?php print $submitted; ?>
    </span>
    <?php endif; ?>
  </header>
  <?php endif; ?>
  <?php
    // Hide comments, tags, and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    hide($content['field_tags']);
  ?>

    <div class="group-left col-lg-3 col-md-3 col-sm-4 col-xs-12">
      <?php print render($content['field_photo']); ?>
      <div class="field field-name-field-contact-information field-type-entityreference field-label-hidden">
        <div class="field-items">
          <div class="field-item even">
            <?php if (!empty($node->field_edir_name)): ?>
              <?php $edir_phone = $node->field_edir_name['und'][0]['entity']->field_phone; ?>
              <?php if (!empty($edir_phone)): ?>
                <div class="field field-name-field-phone field-type-text field-label-inline clearfix">
                  <div class="field-label">
                    Phone:&nbsp;
                  </div>
                  <div class="field-items">
                    <?php print $edir_phone['und'][0]['value']; ?>
                  </div>
                </div>
              <?php endif; ?>
              <?php $edir_location = $node->field_edir_name['und'][0]['entity']->field_location; ?>
              <?php if (!empty($edir_location)): ?>
              <div class="field field-name-field-location field-type-text field-label-inline clearfix">
                <div class="field-label">
                  Location:&nbsp;
                </div>
                <div class="field-items">
                  <?php print $edir_location['und'][0]['value']; ?>
                </div>
              </div>
              <?php endif; ?>
              <?php $edir_email = $node->field_edir_name['und'][0]['entity']->field_email; ?>
              <?php if (!empty($edir_email)): ?>
              <div class="field field-name-field-email field-type-email field-label-inline clearfix">
                <div class="field-label">
                  Email:&nbsp;
                </div>
                <div class="field-items">
                  <?php
                    $email = $node->field_edir_name['und'][0]['entity']->field_email['und'][0]['email'];
                    print l($email, 'mailto:' . $email);
                  ?>
                </div>
              </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="field field-name-field-linkedin field-type-link-field field-label-hidden">
        <?php print render($content['field_linkedin']); ?>
      </div>
    </div>
    <div class="group-right col-lg-9 col-md-9 col-sm-8 col-xs-12">
        <div class="field field-name-title-field field-type-text field-label-hidden">
          <?php print render($content['title_field']); ?>
        </div>
        <?php if (!empty($node->field_edir_name)): ?>
          <?php $edir_position_title = $node->field_edir_name['und'][0]['entity']->field_position_title; ?>
          <?php if (!empty($edir_position_title)): ?>
            <div class="field field-name-field-position-title field-type-text field-label-hidden">
              <?php print $edir_position_title['und'][0]['value']; ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <?php print render($content['field_expertise']); ?>
        <?php print render($content['group_publications']); ?>
        <?php if (!empty($node->field_edir_name)): ?>
          <?php $edir_biography = $node->field_edir_name['und'][0]['entity']->field_biography; ?>
          <?php if (!empty($edir_biography)): ?>
            <div class="field field-name-field-biography field-type-text field-label-hidden">
              <?php print $edir_biography['und'][0]['value']; ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <?php print render($content['group_other']); ?>
    </div>

  <?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
  <footer>
    <?php print render($content['field_tags']); ?>
    <?php print render($content['links']); ?>
  </footer>
  <?php endif; ?>
  <?php print render($content['comments']); ?>
</article>
