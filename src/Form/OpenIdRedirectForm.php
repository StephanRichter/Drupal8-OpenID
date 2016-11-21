<?php

/**
 * @file
 * Contains \Drupal\openid\Form\OpenIdRedirectForm used by openid.inc
 */

namespace Drupal\openid\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * This form is an intermediate Form used during openid validation and ist automatically submitted via javascript
 */

class OpenIdRedirectForm extends FormBase{
	/**
	 * {@inheritdoc}
	 */
	public function getFormId(){
		return 'openid_redirect_form';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state){
		$build_info = $form_state->getBuildInfo();
		if (is_array($build_info['args'])){
			$args = $build_info['args'][0];
		} else {
			$args = array();
		}
		$url     = (isset($args['url']))     ? $args['url']    :'';
		$message = (isset($args['message']))? $args['message']:array();



  $form['#action'] = $url;
  $form['#method'] = "post";
  foreach ($message as $key => $value) {
    $form[$key] = array(
      '#type' => 'hidden',
      '#name' => $key,
      '#value' => $value,
    );
  }
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#prefix' => '<noscript><div>',
    '#suffix' => '</div></noscript>',
    '#value' => t('Send'),
  );

  return $form;

	}

	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state){
		// TODO
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state){
		// TODO
	}
}
