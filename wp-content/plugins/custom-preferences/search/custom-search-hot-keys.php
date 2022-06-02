<?php
    add_action( 'admin_init', 'custom_preferences_search_init' );

    function custom_preferences_search_init() {
        // config global
        register_setting( 'custom_preferences_search_options', 'custom_preferences_search_options' );
        add_settings_section( 'custom_preferences_search', 'Configuration Search keywords', 'configuration_section_search', 'custom_preferences_search' );
        add_settings_field( 'config_search_keys', 'List keys search', 'config_search_section', 'custom_preferences_search', 'custom_preferences_search' );
    }

    function config_search_section() {
        $config_search_keys = get_option( 'custom_preferences_search_options' )['config_search_keys'];
        $index = 0;
        echo '<style>'
                .'#searchkeys tr {border-bottom: 1px solid silver;}'
                .'#searchkeys input {width: 100%}'
            .'</style>';
        echo '<button type="button" id="add-new-search">Add new</button>';
        echo '<table style="width: 100%"><thead><tr><th style="width: 200px;">Key</th><th style="width: calc(100% - 300px);">Url</th><th style="width: 100px;">Action</th></tr></thead>';
        echo '<tbody id="searchkeys">';
        
        if (isset($config_search_keys)) {
            $jsonData = json_decode($config_search_keys);
            if ($jsonData !== null && json_last_error() === JSON_ERROR_NONE) {
                foreach( $jsonData as $key => $value) : $index++; ?>
                    <tr>
                        <td>
                            <input type="text" id="search-key-<?php echo $index; ?>" value="<?php echo $value->key; ?>"/>
                        </td>
                        <td>
                            <input type="text" id="search-url-<?php echo $index; ?>" value="<?php echo $value->url; ?>"/>
                        </td>
                        <td>
                            <button onclick="removeRow(<?php echo $index; ?>)" type="button">Xóa</button>
                        </td>
                    </tr>

        <?php 
                endforeach;
            }
        }
        echo '</tbody>';
        echo '</table>';

        echo "<input type='hidden' id='config_search_keys' name='custom_preferences_search_options[config_search_keys]' size='40' value='{$config_search_keys}' />";
        
        renderScriptSearch($index);
    }

    function configuration_section_search() {
        echo '<p>These configuration is used in search of storefront.</p>';
    }

    function renderScriptSearch($index) {?>
        <script>
            var indexRow = <?php echo $index; ?>;
            jQuery('#add-new-search').on('click', function() {
                if (indexRow > 20) {
                    alert('Chúng tôi chỉ hỗ trợ tối đa 20 từ khóa');
                    return;
                } else  {
                    indexRow++;
                    var html = `<tr>
                                    <td>
                                        <input type="text" placeholder="key search" id="search-key-${indexRow}" value=""/>
                                    </td>
                                    <td>
                                        <input type="text" placeholder="url search" id="search-url-${indexRow}" value=""/>
                                    </td>
                                    <td>
                                        <button onclick="removeRow(${indexRow})" type="button">Xóa</button>
                                    </td>
                                </tr>`;

                    jQuery('#searchkeys').append(html);
                }
            });

            jQuery('#form-searchkey').on('submit', function(e) {
                e.preventDefault();
                var rows = jQuery('#searchkeys').find('tr');
                var jsonData = [];
                if  (rows.length >  0) {
                    for(var i = 0; i < rows.length; i++) {
                        var rowTr = jQuery(rows[i]);
                        var _key = jQuery(rowTr.find('input')[0]).val();
                        var _url = jQuery(rowTr.find('input')[1]).val();
                        if (!_key || _key.trim() === "" || !_url || _url.trim() === "" ) continue;
                        else {
                            jsonData.push({
                                "key" : _key,
                                "url" : _url
                            });
                        }
                    }
                }
                jQuery('#config_search_keys').val(JSON.stringify(jsonData));
                this.submit();
            });

            function removeRow(index) {
                var rows = jQuery('#searchkeys').find('tr');
                var _rows = [...rows];
                _rows.splice(index-1, 1);
                jQuery('#searchkeys').html(_rows);
            }
        </script>

    <?php }

    