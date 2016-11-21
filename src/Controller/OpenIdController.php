<?php
/**
 * @file
 * Contains \Drupal\OpenId\Controller\OpeinIdController
 */

namespace Drupal\openid\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OpenIdController extends ControllerBase {

  public function debug() {

//	$_SESSION['openid']['rand'] = rand();
	$rows = db_query("SELECT id,uid,openid FROM openid_mapping");
	$content = "Old Sessoin content: $oldsession<br/>New Session content: $newsession<br/> <ul>";
	foreach ($rows as $row){
		$content.='<li>';
		$id  = $row->id;
		$uid = $row->uid;
		$oid = $row->openid;
		$content.="$id<ul><li>user = $uid</li><li>openid = $oid</li></ul>";
		$content.='</li>';		
	}
	$content.='</ul>';



    return array(
        '#type' => 'markup',
        '#markup' => $content,
    );
  }


	public function authenticate(){
		//$result = openid_complete(); // Drupal 7
		$result = \Drupal::moduleHandler()->invoke('openid','complete');  // invoke openid_complete in openid.module
		$status = $result['status'];


  		switch ($status) {
			case 'success':
				return openid_authentication($result);
			case 'failed':
				drupal_set_message(t('OpenID login failed.'), 'error');
				break;
			case 'cancel':
				drupal_set_message(t('OpenID login cancelled.'));
				break;
			default:
				drupal_set_message(t('OpenId login failed with status ='.$status), 'error');
		}
		// drupal_goto(); // Drupal 7
		return $this->redirect('openid.login');
	}
}
