<?php
if (!isset($_POST["action"]) OR "guaven_woos_new_request"!=$_POST["action"] or !isset($_POST["gws_search"]) or !isset($_POST["gws_lang"])) die();

//init part
require_once('../../../../wp-load.php');
header('Content-Type: text/html');
send_nosniff_header();
header('Cache-Control: no-cache');
header('Pragma: no-cache');
$action_name='guaven_woos_new_request';
define('DOING_AJAX', true);
check_ajax_referer( 'gws_live_validate_code', 'validate_code' );

//functions part
function guaven_woos_add_to_cache_queue($keyword,$language=''){
  global $wpdb;
  $sanitize=($keyword);
  $wpdb->query(
    $wpdb->prepare(
    "insert ignore into ".$wpdb->prefix."woos_search_cache (query,result_data,result_ids,language,status) values(%s,%s,%s,%s,%s)",$sanitize,'','',$language,'0')
  );
}
function guaven_woos_get_from_cache($keyword,$language=''){
  global $wpdb;
  $sanitize=($keyword);
  $xx=rand(0,100000);
  $res=$wpdb->get_row("select result_data,result_ids from ".$wpdb->prefix."woos_search_cache
  where language='".esc_sql($language)."' and query='".esc_sql($sanitize)."' and $xx=$xx
  order by ID desc limit 1");
  if (empty($res)) return array('',0);
  if (empty($res->result_ids)) return array('',1);
  return array($res->result_data.'~gws_plus_found_ids~'.$res->result_ids,1);
}
function guaven_woos_new_request_handler($attempt=0){
  $starttime=microtime(true);
  $searchkeyword=$_POST["gws_search"];
  $language=str_replace("woolan_","",$_POST["gws_lang"]);
  $result_and_count=guaven_woos_get_from_cache($searchkeyword,$language);
  $result=$result_and_count[0];
  $countitself=$result_and_count[1];
  if ($countitself==0) {
    guaven_woos_add_to_cache_queue($searchkeyword,$language);
    $search_progress=0;$tries=0;
    while($search_progress<1){
      usleep(400000);
      $tries++;
      $result_and_count=guaven_woos_get_from_cache($searchkeyword,$language);
      $result=$result_and_count[0];
      if (!empty($result) or $tries==12){
        $search_progress=1;
      }
    }

    if ($tries==20 and $attempt==0 and (int)get_option('guaven_woos_live_server_heartbeat')<(time()-35)){
      guaven_woos_start_server();
      guaven_woos_new_request_handler(1);
    }
  }
  $endtime=microtime(true);
  echo $result;
}
function guaven_woos_start_server(){
  $multisite_part=defined('WP_ALLOW_MULTISITE')?(get_current_blog_id().' '.$_SERVER['HTTP_HOST'].' '.$_SERVER['REQUEST_URI']).' '.$_SERVER['SERVER_PROTOCOL']:'';
  $command='php '.GUAVEN_WOO_SEARCH_PLUGIN_PATH.'server/searchserver-base.php guaven_woos_live_as '.get_option('guaven_woos_cronkey').' '.$multisite_part.' > /dev/null &';
  $pid=exec($command);
}

//procedure part
$action = esc_attr(trim($_POST['action']));
$allowed_actions = array('guaven_woos_new_request');

if (get_option('guaven_woos_live_server')=='') exit;
if(in_array($action, $allowed_actions)){
    if ($_POST["gws_connected"]==0 and (get_option('guaven_woos_live_server_pid')=='' or (int)get_option('guaven_woos_live_server_heartbeat')<(time()-35) ) ){
      guaven_woos_start_server();
    }
    guaven_woos_new_request_handler();
}
else{
    die('-1');
}
