<?php
/**
 * @file
 * Contains \Drupal\OpenId\Controller\OpeinIdController
 */

namespace Drupal\openid\Controller;

use Drupal\Core\Controller\ControllerBase;

class OpenIdController extends ControllerBase {

  public function debug() {

	$user = \Drupal::currentUser();
/*	$query = db_insert('authmap')
		->fields(array(
			'uid' => $user->id(),
			'authname' => 'test',
			'provider' => 'openid',))
		->execute(); */

	$rows = db_query("SELECT uid,authname,provider FROM {authmap} WHERE provider='openid' AND uid=:uid", array(':uid' => $user->id()));
	$content = '<ul>';
	foreach ($rows as $row){
		$content.='<li>';
		$authname = ($row->authname);
		$uid = $row->uid;
		$provider = ($row->provider);
		$content.="$uid<ul><li>$authname</li><li>$provider</li></ul>";
		$content.='</li>';		
	}
	$content.='</ul>';



    return array(
        '#type' => 'markup',
        '#markup' => $content,
    );
  }
}

