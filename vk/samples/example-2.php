<!doctype html>
    <meta charset="utf-8" />
    <style>
    html, body { font-family: monospace; }
    </style>

<?php

/**
 * Example 2.
 * Get access token via OAuth and usage VK API.
 * @link http://vk.com/developers.php VK API
 */

error_reporting(E_ALL);
require_once('../src/VK/VK.php');
require_once('../src/VK/VKException.php');
include '../../0/simple_html_dom.php';

echo '<pre>';

$vk_config = array(
    'app_id'        => '4546974',
    'api_secret'    => 'j4zHg25k41pJRVOOQqzV',
    'callback_url'  => 'http://motokofr.com/vk/samples/example-2.php',
    'api_settings'  => 'friends,video,offline' // In this example use 'friends'.
    // If you need infinite token use key 'offline'.
);

try {
    $vk = new VK\VK($vk_config['app_id'], $vk_config['api_secret']);
    
    if (!isset($_REQUEST['code'])) {
        /**
         * If you need switch the application in test mode,
         * add another parameter "true". Default value "false".
         * Ex. $vk->getAuthorizeURL($api_settings, $callback_url, true);
         */
        $authorize_url = $vk->getAuthorizeURL(
            $vk_config['api_settings'], $vk_config['callback_url']);
            
        echo '<a href="' . $authorize_url . '">Sign in with VK</a>';
        
$html = new simple_html_dom();
$html->load($authorize_url);  

print_r($_REQUEST);
        
        
    } else {
        $access_token = $vk->getAccessToken($_REQUEST['code'], $vk_config['callback_url']);
        
        echo 'access token: ' . $access_token['access_token']
            . '<br />expires: ' . $access_token['expires_in'] . ' sec.'
            . '<br />user id: ' . $access_token['user_id'] . '<br /><br />';
            
        $users = $vk->api('users.search', array(
            'q'       	=> 'Глюкъ',
            'fields'	=> 'photo_50'
        ));
        
echo '<pre>';
print_r($users);die;
        
        foreach ($users['response'] as $key => $value) {
            echo $value['first_name'] . ' ' . $value['last_name'] . ' ('
                . $value['uid'] . ')<br />';
        }
    }
} catch (VK\VKException $error) {
    echo $error->getMessage();
}
