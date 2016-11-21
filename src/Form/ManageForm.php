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
class ManageForm extends FormBase {
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

		$form['openid'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Add an OpenId'),
			'#default_value' => $openid,
			'#description' => $this->t('Post your new openid here'),
		);
		
		$rows = db_query('SELECT openid FROM openid_mapping WHERE uid = :uid',array(':uid'=>$uid));		
		$options = array('none');
		foreach ($rows as $row){
			$options[$row->openid] = $row->openid;
		}

		$form['delete_openid'] = array(
			'#type' =>'select',
			'#title'=>$this->t('Delete openid?'),
			'#options'=>$options,
			'#descripion'=> $this->t('If you want to delete openids from your account, select one here'),
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

		$delete_openid = trim($form_state->getValue('delete_openid'));
		if (!empty($delete_openid)){
			db_query("DELETE FROM openid_mapping WHERE openid = :openid AND uid = :uid",array(':openid'=>$delete_openid,':uid'=>$uid));
		}

		$openid = trim($form_state->getValue('openid'));
		if (!empty($openid)){
			db_query("INSERT IGNORE INTO openid_mapping (openid, uid) VALUES(:openid, :uid)",array(':openid'=>$openid,':uid'=>$uid));
		}
	}
}

