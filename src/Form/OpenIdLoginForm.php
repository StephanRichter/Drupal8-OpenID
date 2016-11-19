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
 * Lorem Ipsum block form
 */
class OpenIdLoginForm extends UserLoginForm {

protected $userStorage;



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
      '#description' => $this->t('Post you openid here'),
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

	$openid = $form_state->getValue('openid');
	$uid = null;
	$rows = db_query("SELECT uid FROM {authmap} WHERE provider='openid' AND authname=:authname",array(':authname'=>$openid));
	foreach ($rows as $row){
		$uid = $row->uid;
        }
	if ($uid == null) {
		$form_state->setErrorByName('openid',$this->t('This openid is not known to the system'));
	} else {
		$form_state->setValue('uid', $uid);
	}
	

	
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
	$account = $this->userStorage->load($uid);
	$form_state->setRedirect(
		'entity.user.canonical',
		array('user' => $uid)
	);
	user_login_finalize($account);
  }
}

