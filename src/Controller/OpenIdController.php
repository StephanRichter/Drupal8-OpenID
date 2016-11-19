<?php
/**
 * @file
 * Contains \Drupal\OpenId\Controller\OpeinIdController
 */

namespace Drupal\openid\Controller;

use Drupal\Core\Controller\ControllerBase;

class OpenIdController extends ControllerBase {
  public function debug() {
    return array(
        '#type' => 'markup',
        '#markup' => $this->t('Hello, World!'),
    );
  }
}

