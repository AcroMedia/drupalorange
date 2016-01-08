<?php
/**
 * @file
 * Contains \Drupal\acro_living_css\Controller\AcroLivingCSSController.
 */

namespace Drupal\acro_living_css\Controller;

use Drupal\Core\Controller\ControllerBase;

class LivingCSSController extends ControllerBase {
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->t(
        '<p>This is a general site style guide.</p>
<h1>Sample Heading (h1)</h1>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<h2>Sample Heading (h2)</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<h3>Sample Heading (h3)</h3>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<h4>Sample Heading (h4)</h4>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<ul>
  <li>Some bulleted list items.</li>
  <li>Some bulleted list items.</li>
  <li>Some bulleted list items.</li>
</ul>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<ol>
  <li>Some ordered list items.</li>
  <li>Some ordered list items.</li>
  <li>Some ordered list items.</li>
</ol>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit in elit mattis aliquam. Ut tempus sagittis ante quis ornare.</p>
<h2>Admin Tabs</h2>
<ul class="tabs primary clearfix">
  <li><a href="#">View</a></li>
  <li><a href="#">Edit</a></li>
  <li><a href="#">Clone</a></li>
</ul>
<h2>Interactive Tabs</h2>
<ul class="nav nav-tabs" role="tablist">
  <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
  <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
  <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
</ul>
<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="home"><p>Home tab content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit.</p></div>
<div role="tabpanel" class="tab-pane" id="profile"><p>Profile tab content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit.</p></div>
<div role="tabpanel" class="tab-pane" id="messages"><p>Messages tab content. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam consectetur elit.</p></div>
</div>
<h2>Drupal Messages</h2>
<div role="contentinfo" aria-label="Status message" class="messages messages--status">
  <h2 class="visually-hidden">Status message</h2>
  Status message.
</div>
<div role="contentinfo" aria-label="Status message" class="messages messages--warning">
  <h2 class="visually-hidden">Status message</h2>
  Warning message.
</div>
<div role="contentinfo" aria-label="Status message" class="messages messages--error">
  <h2 class="visually-hidden">Status message</h2>
  Error message.
</div>
'),
    );
  }
}
?>




