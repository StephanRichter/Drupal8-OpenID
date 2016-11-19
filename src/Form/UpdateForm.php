<?php

/**
 * @file
 * Contains \Drupal\openid\Form\UpdateForm
 */

namespace Drupal\openid\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Lorem Ipsum block form
 */
class UpdateForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'openid_update_form';
  }

  /**
   * {@inheritdoc}
   * Lorem ipsum generator block.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $user = \Drupal::currentUser();
    $uid = $user->id();

    $rows = db_query("SELECT authname FROM {authmap} WHERE provider='openid' AND uid=:uid", array(':uid' => $uid));
    foreach ($rows as $row){
	$openid = $row->authname;
    }

    // How many paragraphs?
    // $options = new array();
    $form['openid'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('OpenId'),
      '#default_value' => $openid,
      '#description' => $this->t('Post you openid here'),
    );

    // Submit
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Update'),
    );

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $uid = \Drupal::currentUser()->id();

    $openid = $form_state->getValue('openid');
    db_query("UPDATE {authmap} SET authname = :authname WHERE provider='openid' AND uid = :uid",array(':authname'=>$openid,':uid'=>$uid));
    $form_state->setRedirect(
      'openid.update'
    );
  }
}

