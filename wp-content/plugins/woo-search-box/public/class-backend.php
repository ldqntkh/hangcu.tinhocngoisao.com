<?php

if (!defined('ABSPATH')) {
    die;
}

class Guaven_woo_search_backend
{
    protected $replacement_occured;

    public function backend_search_filter($where = '')
    {
        //WOOF filter support
        if (isset($_GET["woof_text"]) and strpos($where,'LOWER(post_title) REGEXP')!==false and strpos($where,"post_type = 'product'")!==false){
            $this->replacement_occured=1;
        }
        
        if (empty($this->replacement_occured)) return $where;

        $search_query_local_raw=$this->search_query();
        $is_woo                = 1;
        $search_query_local=$this->character_remover($search_query_local_raw);
        $found_posts           = $this->find_posts($search_query_local);
        $checkkeyword          = $found_posts[0];
        $sanitize_cookie_final = $found_posts[1];
        $leftpart   = explode(" ", $checkkeyword);
        $gsquery    = esc_attr($search_query_local);
        $leftpart_2 = explode(" ", $gsquery);

        global $wpdb;
        if (empty($sanitize_cookie_final)) {
            $sanitize_cookie_final = 0;
        }
        $newwhere='';
        if (get_option('guaven_woos_variation_skus')==2){
          $newwhere_arr=array();
          $proparents=$wpdb->get_results("select post_parent from $wpdb->posts where $wpdb->posts.ID in (" . $sanitize_cookie_final . ")");
          foreach ($proparents as $pps){
            if ($pps->post_parent>0) $newwhere_arr[]=$pps->post_parent;
          }
          if (!empty($newwhere_arr)) {
            $newwhere= " or $wpdb->posts.ID in (" . implode(",",$newwhere_arr) . ")";
          }
        }
	      $where .= " AND ( $wpdb->posts.ID in (" . $sanitize_cookie_final . ") ".$newwhere."  )";
        $where=str_replace('?(.*)',' ',$where);
        $where = $this->query_cleaner(strtolower($where), $checkkeyword,$search_query_local_raw);
        $where = $this->query_cleaner(strtolower($where), $gsquery,$search_query_local_raw);
        $where=str_replace(strtolower($wpdb->prefix), $wpdb->prefix, $where);

        $ignored_products = get_option('guaven_woos_excluded_prods');
        if (!empty($ignored_products)) {
            $where .= " and ($wpdb->posts.ID not in (" . esc_sql($ignored_products) . "))";
        }
        do_action('guaven_woos_where_processing',$where);
        return $where;
    }

    function backend_search_replacer($search){
      if (is_admin() or empty($_GET["s"]) or empty($_GET["post_type"]) or $_GET["post_type"]!='product') {
        return $search;
      }
      $backend_enable        = get_option('guaven_woos_backend');
      $search_query_local_raw=$this->search_query();

      if (!in_array($backend_enable,array(1,3)) or is_admin() or empty($search_query_local_raw)) {return $search;}

      $search_query_local=explode(" ",strtolower($this->character_remover($search_query_local_raw)));
      if ( strpos($search,'post_title LIKE')!==false and
      ( strpos(strtolower($search),$search_query_local_raw)!==false or
      strpos(strtolower($search),$search_query_local[0])!==false) ) {
        $this->replacement_occured=1;
        return '';
      }
      $this->replacement_occured='';
      return $search;
    }

    function character_remover($str){
      $str=strtolower(str_replace(array(
         "'",
         "/",
         '"',
         //"_",
         "\\"
      ), "", stripslashes($str)));
      $str=str_replace("_","\\\\_",$str);
      $ignorearr = explode(",", get_option('guaven_woos_ignorelist'));
      if (!empty($ignorearr)) $str=str_replace($ignorearr,"",$str);
      return trim($str);
    }

    function slug_formatting($str){
      $transient_name=$this->character_remover($str);
      $transient_name=substr($transient_name, 0, 166);
      $guaven_woo_search_admin = new Guaven_woo_search_admin();
      $transient_name=$guaven_woo_search_admin->translitter($transient_name);
      $transient_name=str_replace(" ", "_", $transient_name);
      return $transient_name;
    }

    public function backend_search_orderby($orderby_statement)
    {
        if ( 
            (isset($_GET["orderby"]) and  strpos($_SERVER["REQUEST_URI"],'orderby')!==false ) 
            or 
            empty($this->replacement_occured)
            ) {return $orderby_statement;}

        $search_query_local=$this->search_query();
        $found_posts           = $this->find_posts($search_query_local);
        if (!empty($found_posts[1])) {
            global $wpdb;
            $orderby_statement = "FIELD( $wpdb->posts.ID, " . $found_posts[1] . ") ASC";
        }
        return $orderby_statement;
    }


    public function find_posts($search_query_local)
    {
        $sanitize_cookie = '';
        $checkkeyword    = '';
        $guaven_woo_search_admin = new Guaven_woo_search_admin();
        $search_query_local_tr_name=$guaven_woo_search_admin->translitter($search_query_local);
        if (!empty($search_query_local)) {
            if (!empty($_POST["guaven_woos_ids"])){
                $sanitize_cookie = preg_replace("/[^0-9,.]/", "", $_POST["guaven_woos_ids"] );
                $clean_kw=$this->slug_formatting(urldecode($_POST["s"]));
                set_transient('gws_' .$clean_kw , $sanitize_cookie, 12 * 3600);
                header("location: ".home_url('?post_type=product&s='.urlencode(stripslashes($_POST["s"]) ) ) );
                exit;
            }
            if(empty($sanitize_cookie )){
                $sanitize_cookie = preg_replace("/[^0-9,.]/", "", get_transient('gws_' . substr(str_replace(" ", "_", $search_query_local_tr_name), 0, 166)));
            }
            $checkkeyword    = $guaven_woo_search_admin->lowercase(urldecode(substr($search_query_local, 0, 166)));
        }

        if ($sanitize_cookie != '') {
            if (substr($sanitize_cookie, -1) != ',') {
                $sanitize_cookie = $sanitize_cookie . ',';
            }
            $sanitize_cookie_final = esc_sql(substr($sanitize_cookie, 0, -1));
        } else {
            $sanitize_cookie_final = '';
        }

        return array(
            $checkkeyword,
            $sanitize_cookie_final
        );
    }

    public function query_cleaner($where, $keyword,$search_query_local)
    {

      $keyword_arr = explode(" ", esc_sql($search_query_local));
      foreach ($keyword_arr as $kwkey => $kwvalue) {
          $where = str_replace(array(
              ']' . strtolower($kwvalue),
              '%' . strtolower($kwvalue) . '%',
              strtolower($kwvalue) . '[',
              strtolower($kwvalue) . '{',
              '}' . strtolower($kwvalue)
          ), array(
              ']',
              '%%',
              '[',
              '{',
              '}'
          ), $where);
      }

        $keyword     = str_replace(array(
            '"'
        ), "", $keyword);
        $keyword_arr = explode(" ", $keyword);
        foreach ($keyword_arr as $kwkey => $kwvalue) {
            $where = str_replace(array(
                ']' . strtolower($kwvalue),
                '%' . $kwvalue . '%',
                strtolower($kwvalue) . '[',
                strtolower($kwvalue) . '{',
                '}' . strtolower($kwvalue)
            ), array(
                ']',
                '%%',
                '[',
                '{',
                '}'
            ), $where);
        }

        $keyword_reg=implode("?(.*)",$keyword_arr)."'";
        if (!empty($keyword_reg)){
          $where=str_replace("REGEXP '".$keyword_reg,"like '%%'",$where);
        }

        $where = str_replace("AND post_title REGEXP '[[:<:]][[:>:]]'", "", $where);
        $where = str_replace("regexp '".strtolower($kwvalue)."'", "like '%%'", $where); //WOOF search query cleaner
        $where = str_replace("regexp '".strtolower($keyword)."'", "like '%%'", $where); //WOOF search query cleaner
        $where = str_replace("regexp '".str_replace("-","\-",strtolower($keyword))."'", "like '%%'", $where); //WOOF search query cleaner
        return $where;
    }
    //cookie based search end

    public function standalone_search_resetter($query)
    {
        if ($query->is_main_query() and isset($_GET["guaven_woos_stdnln"]) and !empty($_GET["s"])) {
            $query->set('s', "");
            $query->set('post_type', "");
        }
    }

    public function guaven_woos_pass_to_backend()
    {
        $sanitized_ids = preg_replace("/[^0-9,.]/", "", $_REQUEST["ids"]);
        if (!empty($sanitized_ids)) {
          $clean_kw=$this->slug_formatting($_REQUEST["kw"]);
          set_transient('gws_' .$clean_kw , $sanitized_ids, 12 * 3600);
        } else set_transient('gws_' . $clean_kw,'0,0', 12 * 3600);
        echo 'ok';
        die();
    }

    public function force_search_reload()
    {
        $search_query_local=$this->search_query();
        if (!empty($search_query_local) and !empty($_GET["post_type"]) and $_GET["post_type"] == 'product') {
            if (get_option('guaven_woos_backend') != 3) {
                return;
            }
            $transient_name=$this->slug_formatting($search_query_local);
            $transient_name = 'gws_' . $transient_name;
            if (get_transient($transient_name) == '') {  ?>
     <style>body {display: none !important}</style>
      <script>
      jQuery(document).ready(function(){
      gws_custom_submission=setInterval(function(){
      if (typeof(guaven_woos)!="undefined" && typeof(guaven_woos_cache_keywords)!="undefined"){
          clearInterval(gws_custom_submission);
          guaven_woos_backend_preparer_direct('<?php echo ($this->character_remover(urldecode($search_query_local))); ?>');
          }
      },200);});
      </script>
      <?php
            }
        }
    }

    public function search_query(){
      if (isset($_GET["s"])) {return $_GET["s"];}
      if (isset($_GET["woof_text"])) {return $_GET["woof_text"];}
      return;
    }
}
