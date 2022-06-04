<?php 
  // rewrite rule
  function add_rewrite_rules_inc( $wp_rewrite ) 
  {
      $new_rules = array
      (
          'tragopsanpham/(.*?)/?$' => 'index.php?pagename=tragopsanpham'.
          '&product_id='.$wp_rewrite->preg_index(1),
      );
      // Always add your rules to the top, to make sure your rules have priority
      $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
  }

  function query_vars_inc($public_query_vars)
  {
      $public_query_vars[] = "product_id";

      return $public_query_vars;
  }

  function flush_rewrite_rules_inc()
  {
      global $wp_rewrite;

      $wp_rewrite->flush_rules();
  }