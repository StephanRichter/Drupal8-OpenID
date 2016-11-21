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
// 			'#value' => url('openid/authenticate', array('absolute' => TRUE, 'query' => user_login_destination())), // Drupal 7
			'#value' => \Drupal::url('openid.authenticate', array('destination' => 'user'),array('absolute'=>true)),
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
		$uid 	= null; // initialize uid
		// find uid belonging to openid
		$rows   = db_query("SELECT uid FROM openid_mapping WHERE openid=:openid",array(':openid'=>$openid));
		foreach ($rows as $row){ // we should only get one row here
			$uid = $row->uid;
       		 }
		if ($uid == null) { // when given openid is not assigned with any account
			$form_state->setErrorByName('openid',$this->t('This openid (@openid) is not known to the system',array('@openid'=>$openid)));
			return;
		}
		$form_state->setValue('uid', $uid);
		
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

	// the form has been validated before
	$uid = $form_state->getValue('uid');
	$openid = $form_state->getValue('openid');

	// At this point, we have an openid and also the uid of the user it belongs to.
        // now the openid should be validated.
	
	// log in the user with the known id
/*	$account = $this->userStorage->load($uid);
	$form_state->setRedirect(
		'entity.user.canonical',
		array('user' => $uid)
	);
	user_login_finalize($account);//*/
  }
}

