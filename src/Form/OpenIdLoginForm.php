<?php

/**
 * @file
 * Contains \Drupal\openid\Form\OpenIdLoginForm
 */

namespace Drupal\openid\Form;

use Drupal\user\Form\UserLoginForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\UserStorageInterface;

/**
 * This form presents the user a single field, where he/she can enter an openid, which the is used to log in the user.
 * This Class is extending UserLoginForm, since this easily allows us to acces the userStorage.
 */
class OpenIdLoginForm extends UserLoginForm {

	/**
	 * {@inheritdoc}
	 */
	public function getFormId() {
		return 'openid_login_form';
	}

	/**
	 * {@inheritdoc}
	 * Lorem ipsum generator block.
	 */
	public function buildForm(array $form, FormStateInterface $form_state) {

		$return_to = \Drupal::url('openid.authenticate', array('destination' => 'user'),array('absolute'=>true));
		
		if (isset($_GET['openid']))
			\Drupal::moduleHandler()->invoke('openid','begin',[$_GET['openid'],$return_to,$form_state]); // invokes openid_begin in openid.module
		// How many paragraphs?
		// $options = new array();
		$form['openid'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('OpenId'),
			'#default_value' => '',
			'#description' => $this->t('Post your openid here'),
		);
		
		$form['openid.return_to'] = array(
			'#type' => 'hidden',
			'#value' => $return_to,
		);

		// Submit
		$form['submit'] = array(
			'#type' => 'submit',
			'#value' => $this->t('Login'),
		);

		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state) {

		$openid = $form_state->getValue('openid'); // get the openid entered by the user
		
		$return_to = $form_state->getValue('openid.return_to');
		if (empty($return_to)){
			$return_to = '';
		}
		\Drupal::moduleHandler()->invoke('openid','begin',array($openid,$return_to,$form_state)); // invokes openid_begin in openid.module
	}

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
	// This does not do anything, all actions are taken care of by openid_begin called from the validation function.
  }
}

