<?php
function guaven_woos_insert_to_results_cache($guaven_woos_tempval){
  global $_wp_using_ext_object_cache;
  $_wp_using_ext_object_cache_previous = $_wp_using_ext_object_cache;
  $_wp_using_ext_object_cache = false;
  global $wpdb,$guaven_woo_search_backend,$guaven_woos_finalresult,$guaven_woos_finalkeys;
  $sanitize=$guaven_woos_tempval;
  $wpdb->query(
    $wpdb->prepare(
    "update ".$wpdb->prefix."woos_search_cache set status=1,result_data=%s,result_ids=%s where query=%s",$guaven_woos_finalresult,$guaven_woos_finalkeys,$sanitize)
  );
  set_transient('gws_' .$sanitize , $guaven_woos_finalkeys, 12 * 3600);
  $_wp_using_ext_object_cache = $_wp_using_ext_object_cache_previous; 
}

function guaven_woos_searchProduct($guaven_woos_tempval) {
    global $minkeycount, $rescount, $maxtypocount,$guaven_woos_finalresult,$guaven_woos_finalkeys;
    $rescount = 0;
    $finalResult = "";
    $guaven_woos_finalresult='';
    $guaven_woos_finalkeys=[];
    
    if (strlen(trim($guaven_woos_tempval)) >= ($minkeycount - 1)) {
        $guaven_woos_result_loop_0=guaven_woos_result_loop($guaven_woos_tempval, 0);
        if ($rescount <= $maxtypocount) {
            $finalpercent = 0;
            guaven_woos_result_loop($guaven_woos_tempval, 1,$guaven_woos_result_loop_0[2]);
        }
    }
    $guaven_woos_finalkeys=implode(",",$guaven_woos_finalkeys);
    if (empty($guaven_woos_finalkeys)) $guaven_woos_finalkeys='0,0';
    guaven_woos_insert_to_results_cache($guaven_woos_tempval);
    return array($guaven_woos_finalresult,$guaven_woos_finalkeys);
}


function guaven_woos_keyformat($numm) {
    $numstr = "";
    if ($numm < 10) {
        $numstr = '000' . $numm;
    } elseif ($numm < 100) {
        $numstr = '00' . $numm;
    } elseif ($numm < 1000) {
        $numstr = '0' . $numm;
    }
    return $numstr;
}

function guaven_woos_format($str, $ttl='') {
  $guaven_woos_pure_home = explode("?", home_url());
  $wpupdir               = wp_upload_dir();
  $fetch_pid_1 = explode("prli_",$str);
  $pid='';
  if (count($fetch_pid_1)>1){
	  for ($i=1;$i<count($fetch_pid_1);$i++){
      $fetch_pid_2 = explode('"',$fetch_pid_1[$i]);
      $pid=$fetch_pid_2[0];
      if (!empty($fetch_pid_2)){
        $str=str_replace("{gwsvid}",$fetch_pid_2[0],$str);
      }
    }
  }

  $str = str_replace(
    array(
      '{{t}}',
      '{{s}}',
      '{{h}}',
      '{{l}}',
      '{{d}}',
      '{{i}}',
      '{{e}}',
      '{{p}}',
      '{{m}}',
      '{{a}}',
      '{{g}}',
      '{{v}}',
      '{{k}}',
      '{{n}}',
      '{{j}}',
      '{{w}}',
      '{{c}}',
      '{{u}}',
      '"gwp='
  ),
    array(
      $ttl,
      '</span> <span class=\"guaven_woos_hidden guaven_woos_hidden_tags\">',
      '<span class=\"guaven_woos_hidden\">',
      '<li class=\"guaven_woos_suggestion_list\" tabindex=\"',
      '\"><div class=\"guaven_woos_div\"><img class=\"guaven_woos_img\" src=\"',
      '\"></div><div class=\"guaven_woos_titlediv\">',
      '</div></a> </li>',
      '</span>',
      '<small>',
      '</small>',
      '</span> <span class=\"gwshd\">',
      '</span> <span class=\"woos_sku woos_sku_variations\">',
      '<span class=\"woos_sku\">',
      '<span class=\"gwstrn\">',
      '<span class=\"gwshd\">',
      '</span><span class=\"gwstrn\">',
      '<span class=\"woocommerce-Price-amount amount\"><span class=\"woocommerce-Price-currencySymbol\">',
      $wpupdir['baseurl'],
      '"'.$guaven_woos_pure_home[0].'?p=',
    ),
    $str);
  return array(stripslashes($str),$pid);
}

function guaven_woos_concatsearch($arrdata, $str) {
    $hasil = 0;
    $respoint = 0;
    $arrdata_arr = explode(" ", $arrdata);
    for ($i = 0; $i < count($arrdata_arr); $i++) {
        if (substr_count($str, $arrdata_arr[$i])) {
            $respoint += strpos($str, $arrdata_arr[$i]);
        } else {
            $respoint += -1;
        }
        if (!substr_count($str, $arrdata_arr[$i])) {
            $hasil = -1;
        }
    }
    if ($hasil == -1) {
        $respoint = -1;
    }
    return $respoint;
}

function guaven_woos_stripQuotes($s) {
    return trim($s, '"');
}

function guaven_woos_result_push($pushdata,$key){
  global $gws_foundids;
  $gws_foundids[]=$key;
  return $pushdata;
}

function guaven_woos_result_loop($guaven_woos_tempval, $tries,$rescount_new=0) {
    global  $maxcount, $rescount,$guaven_woos_cache_data,$local_values;
    global $gws_foundids;
    $gws_foundids=[];
    //$guaven_woos_finalresult = "";
    $keyhelper = [];
    $keyhelper_relevant = [];

    if (empty($guaven_woos_cache_data)){
      $cache_dir_old=plugin_dir_path( __FILE__ ) . 'public/assets';
      $updir=wp_upload_dir();
      $cache_dir_def=$updir['basedir'].'/woos_search_engine_cache';
      if (file_exists($cache_dir_def) and is_writable($cache_dir_def)) $cache_dir=$cache_dir_def;
      else $cache_dir=$cache_dir_old;

      $kk=file_get_contents($cache_dir.'/guaven_woos_data'.GUAVEN_WOO_SEARCH_CACHE_ENDFIX.guaven_woos_get_current_language_code().'.js');
      $kk=str_replace(array(
      	"var guaven_woos_cache_html=",
      	"var guaven_woos_cache_keywords=",
      	"var guaven_woos_pinned_html="
      )
      ,"~gwssrvrsd~",$kk);
      $htmls=json_decode(substr(explode("~gwssrvrsd~",$kk)[1],0,-2),true);
      $keywords=json_decode( addcslashes(substr(explode("~gwssrvrsd~",$kk)[2],0,-2),"'"),true);
      $keywords_raw=array_map('gws_strtolower',$keywords);
      $guaven_woos_cache_data['htmls']=$htmls;
      $guaven_woos_cache_data['keywords']=$keywords;
      $guaven_woos_cache_data['keywords_raw']=$keywords_raw;
    }
    else {
      $htmls=$guaven_woos_cache_data['htmls'];
      $keywords=$guaven_woos_cache_data['keywords'];
      $keywords_raw=$guaven_woos_cache_data['keywords_raw'];
    }

    $guaven_woos_cache_html = $htmls;
    $guaven_woos_cache_keywords = $keywords;
    $guaven_woos_cache_keywords_raw = $keywords_raw;
    $guaven_woos_tempval = gws_strtolower($guaven_woos_tempval, 'UTF-8');

    if (strpos($guaven_woos_tempval, "guaven") > -1) {
        return;
    }
    $guaven_woos_findin_data = $guaven_woos_cache_keywords_raw;
    global $guaven_woo_search_admin;
    $guaven_woos_tempval_spec = $guaven_woo_search_admin->translitter($guaven_woos_tempval);

   // $guaven_woos_tempval = $guaven_woos_tempval_spec;
    $guaven_woos_tempval_space=strpos($guaven_woos_tempval," ");
    foreach ($guaven_woos_findin_data as $guaven_woos_key => $guaven_woos_value) {
        $guaven_woos_temptitle = $guaven_woos_cache_keywords[$guaven_woos_key];
        $guaven_woos_temptitle_raw = $guaven_woos_value;
        $guaven_woos_temphtml = $guaven_woos_cache_html[$guaven_woos_key];

        if ($local_values['local_values']['exactmatch'] == 1) {
          $guaven_woos_temptitle_exact_string = str_replace('/(<([^>]+)>)/ig', "",$guaven_woos_temptitle_raw); // needs to be checked
          $guaven_woos_temptitle_exact_string = str_replace(",", " ",$guaven_woos_temptitle_exact_string);
          $guaven_woos_temptitle_exact_string = guaven_woos_stripQuotes($guaven_woos_temptitle_exact_string);
          $guaven_woos_temptitle_exact = explode(" ",$guaven_woos_temptitle_exact_string);
          foreach ($guaven_woos_temptitle_exact as $exact_key=>$exvalue) {
            if ($exvalue == $guaven_woos_tempval) {
              $keyhelper[] = guaven_woos_result_push(guaven_woos_keyformat($guaven_woos_search_existense) . '~g~v~n~' . 
              $guaven_woos_temphtml.'~g~v~n~'.$guaven_woos_temptitle,$guaven_woos_key);
            }
          }
        }
        elseif ($tries == 0) {

          if ($local_values['local_values']['orderrelevancy']==1){
            $guaven_woos_search_existense_relevant = strpos($guaven_woos_temptitle_raw,$guaven_woos_tempval." ");
          }
          else {
            $guaven_woos_search_existense_relevant=false;
          }

          $guaven_woos_search_existense = strpos($guaven_woos_temptitle_raw, $guaven_woos_tempval);
          if ($guaven_woos_search_existense_relevant !== false) {
              $keyhelper_relevant[] = guaven_woos_result_push(guaven_woos_keyformat($guaven_woos_search_existense) . '~g~v~n~' . 
              $guaven_woos_temphtml.'~g~v~n~'.$guaven_woos_temptitle,$guaven_woos_key);
          } elseif ($guaven_woos_search_existense !== false) {
              $rescount++;
              $keyhelper[] = guaven_woos_result_push(guaven_woos_keyformat($guaven_woos_search_existense) . '~g~v~n~' . 
              $guaven_woos_temphtml.'~g~v~n~'.$guaven_woos_temptitle,$guaven_woos_key);
          }
          elseif ($guaven_woos_tempval_space!==false) {
            $concatsearch = guaven_woos_concatsearch($guaven_woos_tempval, $guaven_woos_temptitle_raw);
            if ($concatsearch > -1) {
                $rescount++;
                $keyhelper[] = guaven_woos_result_push(guaven_woos_keyformat($concatsearch + $maxcount) . '~g~v~n~' . 
                $guaven_woos_temphtml.'~g~v~n~'.$guaven_woos_temptitle,$guaven_woos_key);
            } 
          }
          
        } 
        elseif ($local_values['local_values']['correction_enabled'] == 1) {
                
              $guaven_woos_temptitle_temp=$guaven_woos_temptitle_raw;
              if ($local_values['local_values']['disable_meta_correction']==1){
                    if (strpos($guaven_woos_temptitle_raw,"woos_sku")!==false){
                    $guaven_woos_temptitle_temp=explode("woos_sku",$guaven_woos_temptitle_raw);
                    }
                    else{
                    $guaven_woos_temptitle_temp=explode("{{k}}",$guaven_woos_temptitle_raw);
                    }        
                    $guaven_woos_temptitle_temp=$guaven_woos_temptitle_temp[0];
                }
         
         
                if (in_array($guaven_woos_key,$gws_foundids)>-1) continue;

                $lev_a = $guaven_woos_tempval;
                $lev_b = substr($guaven_woos_temptitle_temp, 0, strlen($lev_a));

                $lev_a_spec = $lev_a;//$guaven_woos_tempval_spec;
                $lev_b_spec =$lev_b;// substr($guaven_woos_temptitle_spec, 0, strlen($lev_a));
                $corrected_push=0;
                $finalpercent = levenshtein($lev_a_spec, $lev_b_spec);
                $finalpercent_spec = $finalpercent;//levenshtein($lev_a_spec, $lev_b_spec);
                if ($finalpercent <= 3 && $finalpercent >= 1 && $finalpercent < (strlen($lev_a) - 4)) {
                  $corrected_push = 1;
                }
                else {
                  $lev_a = str_replace(" ", "",$guaven_woos_tempval);
                  $gwtsp_splitted = explode(" ",$guaven_woos_temptitle_temp);
                  for ($i=0;$i<min(count($gwtsp_splitted),4);$i++) {
                    $gwtspval=$gwtsp_splitted[$i];
                    if (strlen($gwtspval) < 3) continue;
                    $finalpercent = levenshtein($lev_a, $gwtspval);
                    if ($finalpercent >= 1 && $finalpercent <= 3 && $finalpercent < (strlen($lev_a) - 4)) {
                      $corrected_push = 1;
                    }
                  }
                }
                if ($corrected_push==1){
                  $rescount++;
                  $keyhelper[] = guaven_woos_result_push(guaven_woos_keyformat(100 + $maxcount) . '~g~v~n~' . 
                  $guaven_woos_temphtml.'~g~v~n~'.$guaven_woos_temptitle,$guaven_woos_key);
                }
            
        }
    }

    if($local_values['local_values']['orderrelevancy']==1){
      sort($keyhelper_relevant);
      sort($keyhelper);
     $finalArr = array_merge($keyhelper, $keyhelper_relevant);
    }  
    else  $finalArr =$keyhelper;

    //$rescount_new = 0;
    global $guaven_woos_finalresult,$guaven_woos_finalkeys;
    foreach ($finalArr as $keyh => $keyv) {
            $purevalue = explode("~g~v~n~", $keyv);
            if (strpos($guaven_woos_finalresult, $purevalue[1]) === false) {
              $purevalue_1 = explode("prli_",$purevalue[1]);
              $purevalue_2 = explode('"',$purevalue_1[1]);
              if ($rescount_new < $maxcount) {
                $rescount_new++;
                $formatted_data=guaven_woos_format($purevalue[1],$purevalue[2]);
                $guaven_woos_finalresult = $guaven_woos_finalresult .$formatted_data[0]  ;
              }
              $guaven_woos_finalkeys[]=$purevalue_2[0];
        }
    }

    return array($guaven_woos_finalresult,$guaven_woos_finalkeys,$rescount_new);
}

function guaven_woos_get_current_language_code(){
    $sapi_type = php_sapi_name();
    if (defined('ICL_LANGUAGE_CODE') and substr($sapi_type, 0, 3) != 'cli') return ICL_LANGUAGE_CODE;
    return '';
}

function guaven_woos_get_fresh_option($name){
  global $wpdb;
  $xx=rand(0,100000);
  $ret=$wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s and $xx=$xx LIMIT 1", $name ) );
  return (int)$ret;
}

function gws_strtolower($str){
  if (function_exists('mb_strtolower')) return mb_strtolower($str,'UTF-8');
  return strtolower($str);
}

function gws_find_wordpress_base_path() {
  $dir = dirname(__FILE__);
  do {
      //it is possible to check for other files here
      if( file_exists($dir."/wp-config.php") ) {
          return $dir;
      }
  } while( $dir = realpath("$dir/..") );
  return null;
}
#####################################################################################################################################################


set_time_limit(-1);

$_SERVER['REQUEST_METHOD'] ='GET';
if(isset($argv[4]) and isset($argv[5]) and isset($argv[6])){
  $_SERVER['HTTP_HOST']=$argv[4];//'demo.guaven.com';
  $_SERVER['REQUEST_URI']=$argv[5];
  $_SERVER['SERVER_PROTOCOL']=$argv[6];//'wsb-accelerated';
}


define( 'BASE_PATH', gws_find_wordpress_base_path()."/" );
define( 'WP_USE_THEMES', false );
/** Loads the WordPress Environment and Template */
require( BASE_PATH. 'wp-blog-header.php' );


//$guaven_woo_search_admin->set_memory_limit();
if (!isset($argv[1]) or $argv[1]!='guaven_woos_live_as' or !isset($argv[2]) or $argv[2]!=get_option('guaven_woos_cronkey')) die(' not perm');

if (isset($argv[3]) and defined('WP_ALLOW_MULTISITE')) switch_to_blog($argv[3]);
  set_time_limit(-1);
  if (get_option( 'guaven_woos_live_server')==''){exit;}

  global $wpdb, $maxcount, $rescount;
 
  $local_values=$guaven_woo_search_front->local_values();
  $maxcount = $local_values['local_values']['maxcount'];//10;
  $minkeycount = $local_values['local_values']['minkeycount'];//3;
  $maxtypocount = $local_values['local_values']['maxtypocount'];//3;
  $rescount = 0;

  update_option('guaven_woos_live_server_pid',getmypid());
  $check_version_trigger=0;
  $current_process_pid=(int)get_option( 'guaven_woos_live_server_pid');
  $current_version=(int) get_option('guaven_woos_jscss_version');
  $session_id=uniqid();
  while(true){
    usleep(100000);
    $xx=rand(0,100000);
    $searchkeyword=$wpdb->get_var("select query from ".$wpdb->prefix."woos_search_cache where $xx=$xx and status=0 order by ID ASC limit 1");
    $check_version_trigger++;
    if ($check_version_trigger==300){
      $check_version_trigger=0;
      $check_process_pid= guaven_woos_get_fresh_option('guaven_woos_live_server_pid');
      $new_version= guaven_woos_get_fresh_option('guaven_woos_jscss_version');
      if ($current_version!=$new_version or $current_process_pid!=$check_process_pid or $check_process_pid==0) exit;
      update_option('guaven_woos_live_server_heartbeat',time());
      update_option('guaven_woos_live_server_pid',getmypid());
    }
    if (!empty($searchkeyword)){
      echo 'new search...';
      $result=guaven_woos_searchProduct($searchkeyword);
    }
  }
  exit;