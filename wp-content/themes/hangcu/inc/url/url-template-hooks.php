<?php

add_action( 'init', 'flush_rewrite_rules_inc');
add_action('generate_rewrite_rules', 'add_rewrite_rules_inc');
add_filter('query_vars', 'query_vars_inc');