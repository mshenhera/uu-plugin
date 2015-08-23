<?php
class XooUserUltraCommon
{
    var $wp_all_categories =  array();


    function get_all_sytem_pages()
    {
        if($this->wp_all_pages === false)
        {
            $this->wp_all_pages[0] = "Select Page";
            foreach(get_pages() as $key=>$value)
            {
                $this->wp_all_pages[$value->ID] = $value->post_title;
            }
        }

        return $this->wp_all_pages;
    }

    function get_all_sytem_cagegories()
    {

        require_once(ABSPATH . 'wp-includes/category-template.php');

        $args = array(
            'type'                     => 'post',
            'child_of'                 => 0,
            'parent'                   => '',
            'orderby'                  => 'name',
            'order'                    => 'ASC',
            'hide_empty'               => 0,
            'hierarchical'             => 1,
            'exclude'                  => '',
            'include'                  => '',
            'number'                   => '',
            'taxonomy'                 => 'category',
            'pad_counts'               => false

        );


        $categories = get_categories($args);



            $this->wp_all_categories[0] = "Select Category";
            foreach($categories as $category)
            {
                $this->wp_all_categories[$category->cat_ID] = $category->cat_name;
            }


        return $this->wp_all_categories;
    }

    // get value in admin option
    function get_value($option_id)
    {

        if (isset($this->options[$option_id]) && $this->options[$option_id] != '' )
        {

            if(is_string($this->options[$option_id])){

                 return stripslashes($this->options[$option_id]);

            }else{

                 return $this->options[$option_id];
            }


        } elseif (isset($this->userultra_default_options[$option_id]) && $this->userultra_default_options[$option_id] != '' ) {
            return stripslashes($this->userultra_default_options[$option_id]);
        } else {
            return null;
        }
    }

        // add setting field
    function create_plugin_setting($type, $id, $label, $pairs, $help, $inline_help = '', $extra=null) {

        $field_holder_id= $id.'_holder';
        echo  "<tr valign=\"top\" id=\"$field_holder_id\">
        <th scope=\"row\"><label for=\"$id\">$label</label></th>
        <td>";
        $input_html = '';

        $value = '';
        $value = $this->get_value($id);

        switch ($type) {

            case 'textarea':
                print "<textarea name=\"$id\" type=\"text\" id=\"$id\" class=\"large-text code text-area uultra-setting-options-texarea\" rows=\"3\">$value</textarea>";
                break;

            case 'input':
                print "<input name=\"$id\" type=\"text\" id=\"$id\" value=\"$value\" class=\"regular-text\" />";
                break;

            case 'select':
                print "<select name=\"$id\" id=\"$id\">";
                foreach($pairs as $k => $v) {

                    if (is_array($v)) {
                        $v = $v['name'];
                    }

                    echo '<option value="'.$k.'"';
                    if (isset($this->options[$id]) && $k == $this->options[$id]) {
                        echo ' selected="selected"';
                    }
                    echo '>';
                    echo $v;
                    echo '</option>';

                }
                print "</select>";
                break;

            case 'checkbox':
                $checked='';
                if('1' == $value)
                {
                    $checked='checked';
                }
                print "<input name=\"$id\" type=\"checkbox\" id=\"$id\" value=\"1\" ".$checked." />";
                break;
            case 'color':
                $default_color = $this->defaults[$id];
                print "<input name=\"$id\" type=\"text\" id=\"$id\" value=\"$value\" class=\"my-color-field\" data-default-color=\"$default_color\" />";
                break;

            case 'checkbox_list':
                $selected_roles = $value;
                $default_role = get_option("default_role");


                foreach ($pairs as $role_key => $role) {
                    if($default_role == $role_key){
                        echo $this->check_box(array('name' => $id.'[]', 'id' => $id, 'value' => $role_key,'checked'=>'checked','disabled'=>'disabled')).$role.'<br/>';
                    }else{
                        $checked_value = '';
                        if(is_array($selected_roles) && in_array($role_key,$selected_roles)){
                            $checked_value = $role_key;
                        }
                        echo $this->check_box(array('name' => $id.'[]', 'id' => $id, 'value' => $role_key),$checked_value).$role.'<br/>';
                    }

                }
                break;

        }

        $qtip_classes = 'qtip-light ';

        if($inline_help!='')
        {
            echo '<a class="'.$qtip_classes.' uultra-tooltip" title="' . $inline_help . '" '.$qtip_style.'><i class="fa fa-info-circle reg_tooltip"></i></a>';
        }


        if ($help)
            echo "<p class=\"description\">$help</p>";

        if (is_array($extra)) {
            echo "<div class=\"helper-wrap\">";
            foreach ($extra as $a) {
                echo $a;
            }
            echo "</div>";
        }

        echo "</td></tr>";

    }

     public static function check_box($property=array(),$selected='0')
    {
        $chek_box='<input type="checkbox"';

        $checked='';

        if(is_array($property))
        {
            foreach($property as $key=>$value)
            {
                if($key == 'value' && trim($value) == trim($selected))
                    $checked=' checked="checked"';

                $chek_box.=' '.$key.'="'.$value.'"';
            }
        }

        $chek_box.=$checked.' />';
        return $chek_box;
    }

    function get_option($option)
    {
        $settings = get_option('userultra_options');
        if (isset($settings[$option]))
        {
            return $settings[$option];

        }else{

            return '';
        }

    }

    public function fetch_result($results)
    {
        if ( empty( $results ) )
        {


        }else{


            foreach ( $results as $result )
            {
                return $result;

            }

        }

    }

    function getPages($total_pages, $page, $targetpage, $limit )
    {


        // How many adjacent pages should be shown on each side?
        $adjacents = 3;

        $get_var = "ini";

        //echo $target_page;

        /*
           First get total number of rows in data table.
           If you have a WHERE clause in your query, make sure you mirror it here.
        */



        /* Setup vars for query. */
        //$targetpage = "filename.php";     //your file name  (the name of this file)
        //$limit = 2;                               //how many items to show per page
        //$page = $_GET['page'];
        if($page)
            $start = ($page - 1) * $limit;          //first item to display on this page
        else
            $start = 0;                             //if no page var is given, set start to 0


        /* Setup page vars for display. */
        if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
        $prev = $page - 1;                          //previous page is page - 1
        $next = $page + 1;                          //next page is page + 1
        $lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                      //last page minus 1

        /*
            Now we apply our rules and draw the pagination object.
            We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $sep_get = "&";

        $pagination = "";
        if($lastpage > 1)
        {
            $pagination .= "<div class=\"pagination\">";
            //previous button
            if ($page > 1)
                $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$prev\">Previous</a>";
            else
                $pagination.= "<span class=\"disabled\">Previous</span>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$counter\">$counter</a>";
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2))
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$counter\">$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$lpm1\">$lpm1</a>";
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$lastpage\">$lastpage</a>";
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=1\">1</a>";
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=2\">2</a>";
                    $pagination.= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$counter\">$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$lpm1\">$lpm1</a>";
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$lastpage\">$lastpage</a>";
                }
                //close to end; only hide early pages
                else
                {
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=1\">1</a>";
                    $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=2\">2</a>";
                    $pagination.= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage".$sep_get."".$get_var."=$counter\">$counter</a>";
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                $pagination.= "<a href=\"$targetpage".$sep_get."ini=$next\">Next</a>";
            else
                $pagination.= "<span class=\"disabled\">Next</span>";
            $pagination.= "</div>\n";
        }

        return $pagination;




    }

    function getPager2($TotalReg, $page, $targetpage, $limit )
    {


        // How many adjacent pages should be shown on each side?
        $adjacents = 3;

        echo $target_page;

        /*
           First get total number of rows in data table.
           If you have a WHERE clause in your query, make sure you mirror it here.
        */

        $total_pages = mysql_num_rows($TotalReg);

        /* Setup vars for query. */
        //$targetpage = "filename.php";     //your file name  (the name of this file)
        //$limit = 2;                               //how many items to show per page
        //$page = $_GET['page'];
        if($page)
            $start = ($page - 1) * $limit;          //first item to display on this page
        else
            $start = 0;                             //if no page var is given, set start to 0


        /* Setup page vars for display. */
        if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
        $prev = $page - 1;                          //previous page is page - 1
        $next = $page + 1;                          //next page is page + 1
        $lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                      //last page minus 1

        /*
            Now we apply our rules and draw the pagination object.
            We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $pagination = "";
        if($lastpage > 1)
        {
            $pagination .= "<div class=\"pagination\">";
            //previous button
            if ($page > 1)
                $pagination.= "<a href=\"$targetpage?page=$prev\">Previous</a>";
            else
                $pagination.= "<span class=\"disabled\">Previous</span>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
            {
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                }
            }
            elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if($page < 1 + ($adjacents * 2))
                {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                    $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                }
                //in middle; hide some front and some back
                elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                {
                    $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                    $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                    $pagination.= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                    }
                    $pagination.= "...";
                    $pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
                    $pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";
                }
                //close to end; only hide early pages
                else
                {
                    $pagination.= "<a href=\"$targetpage?page=1\">1</a>";
                    $pagination.= "<a href=\"$targetpage?page=2\">2</a>";
                    $pagination.= "...";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<span class=\"current\">$counter</span>";
                        else
                            $pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
                $pagination.= "<a href=\"$targetpage?page=$next\">Next</a>";
            else
                $pagination.= "<span class=\"disabled\">Next</span>";
            $pagination.= "</div>\n";
        }

        return $pagination;


    }

    /* Predefined arrays/listings */
    public function get_predifined($filter)
    {
        $array = array();

        switch($filter) {

            case 'countries':
                $array = array (
                  '0'  => '',
                    'AF' => 'Afghanistan',
                    'AX' => 'Aland Islands',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AS' => 'American Samoa',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AQ' => 'Antarctica',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BA' => 'Bosnia and Herzegovina',
                    'BW' => 'Botswana',
                    'BV' => 'Bouvet Island',
                    'BR' => 'Brazil',
                    'IO' => 'British Indian Ocean Territory',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island',
                    'CC' => 'Cocos (Keeling) Islands',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo',
                    'CD' => 'Congo Democratic',
                    'CK' => 'Cook Islands',
                    'CR' => 'Costa Rica',
                    'CI' => "Cote d'Ivoire",
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands (Malvinas)',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'TF' => 'French Southern Territories',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GU' => 'Guam',
                    'GT' => 'Guatemala',
                    'GG' => 'Guernsey',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HM' => 'Heard Island and McDonald Islands',
                    'VA' => 'Holy See (Vatican City State)',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IM' => 'Isle of Man',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JE' => 'Jersey',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KP' => "Korea Democratic",
                    'KR' => 'Korea Republic',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => "Lao People's Democratic Republic",
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MH' => 'Marshall Islands',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte',
                    'MX' => 'Mexico',
                    'FM' => 'Micronesia',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco',
                    'MN' => 'Mongolia',
                    'ME' => 'Montenegro',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'MM' => 'Myanmar',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'AN' => 'Netherlands Antilles',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'NU' => 'Niue',
                    'NF' => 'Norfolk Island',
                    'MP' => 'Northern Mariana Islands',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PW' => 'Palau',
                    'PS' => 'Palestine',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'PR' => 'Puerto Rico',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russian Federation',
                    'RW' => 'Rwanda',
                    'BL' => 'Saint Barthelemy',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts and Nevis',
                    'LC' => 'Saint Lucia',
                    'MF' => 'Saint Martin (French part)',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'WS' => 'Samoa',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'RS' => 'Serbia',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovakia',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia and the South Sandwich Islands',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SJ' => 'Svalbard and Jan Mayen',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TL' => 'Timor-Leste',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'GB' => 'United Kingdom',
                    'US' => 'United States',
                    'UM' => 'United States Minor Outlying Islands',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VE' => 'Venezuela',
                    'VN' => 'Viet Nam',
                    'VG' => 'Virgin Islands, British',
                    'VI' => 'Virgin Islands, U.S.',
                    'WF' => 'Wallis and Futuna',
                    'EH' => 'Western Sahara',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe'
                );
                break;

                case 'age':

                $array = array (
                  '0'  => '',
                  '15'  => '15',
                  '16'  => '16',
                  '17'  => '17',
                  '18'  => '18',
                  '19'  => '19',
                  '20'  => '20',
                  '21'  => '21',
                  '22'  => '22',
                  '23'  => '23',
                  '24'  => '24',
                  '25'  => '25',
                  '26'  => '26',
                  '27'  => '27',
                  '28'  => '28',
                  '29'  => '29',
                  '30'  => '30',
                  '31'  => '31',
                  '32'  => '32',
                  '33'  => '33',
                  '34'  => '34',
                  '35'  => '35',
                  '36'  => '36',
                  '37'  => '37',
                  '38'  => '38',
                  '39'  => '39',
                  '40'  => '40',
                  '41'  => '41',
                  '42'  => '42',
                  '43'  => '43',
                  '44'  => '44',
                  '45'  => '45',
                  '46'  => '46',
                  '47'  => '47',
                  '48'  => '48',
                  '49'  => '49',
                  '50'  => '50',

                  '51'  => '51',
                  '52'  => '52',
                  '53'  => '53',
                  '54'  => '54',
                  '55'  => '55',
                  '56'  => '56',
                  '57'  => '57',
                  '58'  => '58',
                  '59'  => '59',
                  '60'  => '60',
                  '61'  => '61',
                  '62'  => '62',
                  '63'  => '63',
                  '64'  => '64',
                  '65'  => '65',
                  '66'  => '66',
                  '67'  => '67',
                  '68'  => '68',
                  '69'  => '69',
                  '70'  => '70',

                  '71'  => '71',
                  '72'  => '72',
                  '73'  => '73',
                  '74'  => '74',
                  '75'  => '75',
                  '76'  => '76',
                  '77'  => '77',
                  '78'  => '78',
                  '79'  => '79',
                  '80'  => '80',

                  '81'  => '81',
                  '82'  => '82',
                  '83'  => '83',
                  '84'  => '84',
                  '85'  => '85',
                  '86'  => '86',
                  '87'  => '87',
                  '88'  => '88',
                  '89'  => '89',
                  '90'  => '90',
                  '91'  => '91',
                  '92'  => '92',
                  '93'  => '93',
                  '94'  => '94',
                  '95'  => '95',
                  '96'  => '96',
                  '97'  => '97',
                  '98'  => '98',
                  '99'  => '99',
                  '100'  => '100',
                );
                break;

                case 'regions':
                    $array = array(
                        "0" => "",
                        "1" => "Вінницька",
                        "2" => "Волинська",
                        "3" => "Дніпропетровська",
                        "4" => "Донецька",
                        "5" => "Житомирська",
                        "6" => "Закарпатська",
                        "7" => "Запорізька",
                        "8" => "Івано-Франківська",
                        "9" => "Київська",
                        "10" => "Кіровоградська",
                        "11" => "Крим",
                        "12" => "Луганська",
                        "13" => "Львівська",
                        "14" => "Миколаївська",
                        "15" => "Одеська",
                        "16" => "Полтавська",
                        "17" => "Рівненська",
                        "18" => "Сумська",
                        "19" => "Тернопільська",
                        "20" => "Харківська",
                        "21" => "Херсонська",
                        "22" => "Хмельницька",
                        "23" => "Черкаська",
                        "24" => "Чернігівська",
                        "25" => "Чернівецька",
                    );
                break;

                case 'cities':
                    $array = array (
                        "0" => "",
                        "1" => "Вінниця|Бар|Бершадь|Браїлів|Брацлав|Вапнярка|Вендичани|Вороновиця|Гайсин|Гнівань|Дашів|Жмеринка|Іллінці|Козятин|Калинівка|Крижопіль|Липовець|Літин|Могилів-Подільський|Муровані Курилівці|Немирів|Оратів|Піщанка|Погребище|Теплик|Томашпіль|Тростянець|Тульчин|Тиврів|Хмільник|Чернівці|Чечельник|Шаргород|Ямпіль",
                        "2" => "Луцьк|Берестечко|Володимир-Волинський|Голоби|Головно|Горохів|Заболоття|Іваничі|Камінь-Каширський|Ківерці|Ковель|Локачі|Любешів|Любомль|Маневичі|Нововолинськ|Ратне|Рожище|Стара Вижівка|Турійськ|Шацьк",
                        "3" => "Дніпропетровськ|Апостолове|Аули|Брагинівка|Васильківка|Верхньодніпровськ|Верхівцеве|Вільногірськ|Гвардійське|Гірницьке|Губиниха|Демурино|Дніпродзержинськ|Жовті Води|Залізничне|Зелене|Іларіонове|Інгулець|Калініно|Карнаухівка|Кривий Ріг|Кринички|Магдалинівка|Марганець|Межова|Нікополь|Новомосковськ|Орджонікідзе|Павлоград|Першотравенськ|Петропавлівка|Покровське|П'ятихатки|Синельникове|Софіївка|Тернівка|Томаківка|Царичанка|Широке",
                        "4" => "Донецьк|Авдіївка|Олександрівка|Олексієво-Дружківка|Амвросіївка|Андріївка|Артемове|Артемівськ|Безіменне|Біленьке|Білицьке|Благодатне|Билбасівка|Велика Новосілка|Войковський|Волноваха|Володарське|Гольмівський|Горбачево-Михайлівка|Горлівка|Гродівка|Грузько-Зорянське|Дебальцеве|Дзержинськ|Димитров|Добропілля|Докучаєвськ|Донецька|Донське|Дробишево|Дружківка|Єнакієве|Жданов|Желанне|Зугрес|Зуївка|Іловайськ|Карло-Лібкнехтовск|Карло-Марксове|Кіровськ|Костянтинівка|Краматорськ|Красноармійськ|Червоний Лиман|Курахове|Макіївка|Маріуполь|Мар'їнка|Новоазовськ|Новогродівка|Новоекономічне|Першотравневе|Селидове|Слов'янськ|Сніжне|Старобешеве|Тельманове|Торез|Вугледар|Харцизьк|Шахтарськ|Ясинувата",
                        "5" => "Житомир|Андрушівка|Баранівка|Біла Криниця|Бердичів|Броницька Гута|Брусилов|Биківка|Великі Коровинці|Володарськ-Волинський|Городниця|Гришківці|Дзержинськ|Довбиш|Ємільчине|Іванопіль|Кам'яний Брід|Коростень|Коростишів|Лугини|Любар|Малин|Народичі|Новоград-Волинський|Овруч|Олевськ|Попельня|Радомишль|Ружин|Червоноармійськ|Черняхів|Чуднов",
                        "6" => "Ужгород|Берегово|Буштино|Великий Березний|Великий Бичків|Виноградів|Воловець|Іршава|Королево|Міжгір'я|Мукачево|Оноківці|Перечин|Рахів|Свалява|Тячів|Хуст|Чоп",
                        "7" => "Запоріжжя|Якимівка|Андріївка|Балабине|Бердянськ|Василівка|Веселе|Гуляйполе|Кам'янка-Дніпровська|Кам'яне|Комиш-Зоря|Куйбишеве|Мелітополь|Михайлівка|Новомиколаївка|Оріхів|Пологи|Приазовське|Приморськ|Токмак|Чернігівка|Енергодар",
                        "8" => "Івано-Франківськ|Богородчани|Болехів|Більшівці|Брошнів-Осада|Букачівці|Бурштин|Битків|Верховина|Войнилів|Ворохта|Вигода|Галич|Гвіздець|Городенка|Делятин|Жовтень|Заболотів|Калуж|Калуш|Коломия|Косів|Надвірна|Рогатин|Рожнятів|Снятин|Станіславів|Тлумач|Тлумач|Яремча",
                        "9" => "Київ|Баришівка|Біла Церква|Березань|Безп'ятне|Богуслав|Бориспіль|Борова|Бородянка||Cофіївська Борщагівка|Петропавлівська Борщагівка|Боярка|Бровари|Буча|Васильків|Вишневе|Володарка|Ворзель|Вишгород|Гора|Гостомель|Гребінки|Димер|Зазим'я|Згурівка|Іванків|Ірпінь|Кагарлик|Крюківщина|Калинівка|Кодра|Кожанка|Козин|Коцюбинське|Макаров|Обухів|Переяслав-Хмельницький|Петрівське|Поліське|Рокитне|Сквира|Славутич|Ставище|Тараща|Тетіїв|Узин|Українка|Фастов|Чорнобиль|Яготин",
                        "10" => "Кіровоград|Алексадровка|Олександрія|Олександрівка|Бобринець|Гайворон|Голованівськ|Добровеличківка|Долинська|Єлизаветградка|Завалля|Знам'янка|Знам'янка-Друга|Капітанівка|Компаніївка|Мала Виска|Новгородка|Новоархангельськ|Новомиргород|Новоукраїнка|Вільшанка|Онуфріївка|Петрове|Світловодськ|Ульяновка|Устинівка",
                        "11" => "Сімферополь|Азовське|Алупка|Алушта|Армянськ|Армянськ|Артек|Багерово|Балаклава|Бахчисарай|Білогірськ|Берегове|Ботанічний|Гаспра|Гвардійське|Гурзуф|Джанкой|Євпаторія|Зуя|Кастрополь|Кацивелі|Керч|Кіровське|Кореїз|Красногвардейск|Красногвардійське|Краснокам'янка|Красноперекопськ|Курпати|Леніно|Лівадія|Масандра|Місхор|Науковий|Нижньогірський|Олива|Оползневе|Паркове|Первомайське|Понизівка|Роздольне|Саки|Санаторне|Севастополь|Сімеїз|Радянський|Судак|Феодосія|Форос|Чорноморське|Щолкіно|Ялта",
                        "12" => "Луганськ|Алексадровск|Алчевськ|Антрацит|Артемівськ|Байрачки|Біловодськ|Білогорівка|Біле|Білокуракине|Білолуцьк|Бірюкове|Боково-Платово|Борівське|Брянка|Бугаївка|Вахрушеве|Великий Лог|Вергулівка|Володарськ|Вовчеярівка|Ворошиловград|Врубівка||Врубівський|Георгіївка|Гірське|Єсаулівка|Зимогір'я|Золоте|Зоринськ|Ізварине|Калінінський|Калинове|Комишуваха|Кіровськ|Комунарськ|Краснодон|Червоний Луч|Кремінна|Лисичанськ|Лутугине|Марківка|Мілове|Молодогвардійськ|Новоайдар|Новодружеськ|Новопсков|Первомайськ|Перевальськ|Петрівське|Попасна|Ровеньки|Рубіжне|Сватове|Свердловськ|Сєвєродонецьк|Слов'яносербськ|Станично-Луганське|Старобільськ|Стаханов|Троїцьке",
                        "13" => "Львів|Белз|Бібрка|Борислав|Броди|Буськ|Великі Мости|Верхнє Синьовидне|Винники|Глиняни|Гірник|Городок|Дашава|Добромиль|Добротвір|Дрогобич|Жидачів|Жолкіев|Золочів|Івано-Франкове|Кам'янка-Бузька|Мостиська|Нестеров|Миколаїв|Перемишляни|Пустомити|Рава Руська|Радехів|Самбір|Сколе|Сокаль|Старий Самбір|Стрий|Трускавець|Турка|Червоноград|Яворів",
                        "14" => "Миколаїв|Олександрівка|Арбузинка|Баштанка|Березнегувате|Братське|Велика Корениха|Веселинове|Вознесенськ|Доманівка|Єланець|Казанка|Криве Озеро|Нова Одеса|Новий Буг|Очаків|Первомайськ|Снігурівка|Южноукраїнськ",
                        "15" => "Одеса|Аккерман|Ананьїв|Арциз|Балта|Білгород-Дністровський|Біляївка|Березино|Березівка|Болград|Бородіно|Велика Михайлівка|Велікодолінінское|Вилкове|Затишшя|Ізмаїл|Іллічівськ|Кілія|Кодима|Комінтернівське|Котовськ|Красні Окни|Лиманське|Любашівка|Миколаївка|Овідіополь|Роздільна|Рені|Саврань|Сарата|Тарутине|Татарбунари|Теплодар|Фрунзівка|Ширяєве|Південне|Південний",
                        "16" => "Полтава|Білики|Велика Багачка|Гадяч|Глобине|Гоголеве|Градизьк|Гребінка|Диканька|Зіньків|Карлівка|Кобеляки|Козельщина|Комсомольськ|Котельва|Кременчук|Лохвиця|Лубни|Машівка|Миргород|Нові Санжари|Оржиця|Пирятин|Пирятин|Решетилівка|Семенівка|Хорол|Чорнухи|Чутове|Шишаки",
                        "17" => "Рівне|Володимирець|Гоща|Демидівка|Дубно|Дубровиця|Зарічне|Здолбунів|Клевань|Клесів|Корець|Костопіль|Кузнецовськ|Млинів|Острог|Рокитне|Сарни|Червоноармійськ",
                        "18" => "Суми|Охтирка|Білопілля|Буринь|Велика Писарівка|Ворожба|Воронеж|Глухов|Дружба|Зноб-Новгородське|Кириківка|Конотоп|Краснопілля|Кровелець|Лебедин|Липова Долина|Недригайлів|Овлаші|Путивль|Ромни|Свеса|Середина-Буда|Тростянець|Шостка|Шурупинське|Ямпіль",
                        "19" => "Тернопіль|Бережани|Борщів|Бучач|Великі Бірки|Вишнівець|Гримайлів|Гусятин|Заліщики|Заложці|Збараж|Зборів|Золотники|Золотий|Козлов|Козова|Кременець|Ланівці|Монастириська|Підволочиськ|Теребовля|Чортків|Шумське",
                        "20" => "Харків|Балаклея|Барвінкове|Близнюки|Богодухів|Борки|Борова|Буди|Валки|Великий Бурлук|Вовчанськ|Високий|Готвальд|Гути|Дергачі|Зачепилівка|Зідьки|Зміїв|Золочів|Ізюм|Козача Лопань|Кегичівка|Комсомольське|Красноград|Краснокутськ|Куп'янськ|Лозова|Люботин|Мерефа|Нова Водолага|Первомайський|Пісочин|Сахновщина|Солоницівка|Чугуїв|Шевченкове",
                        "21" => "Херсон|Асканія-Нова|Біла Криниця|Білозерка|Берислав|Велика Олександрівка|Велика Лепетиха|Верхній Рогачик|Високопілля|Генічеськ|Гола Пристань|Горностаївка|Дніпряни|Каланчак|Калінінське|Каховка|Нижні Сірогози|Нова Каховка|Нововоронцовка|Новотроїцьке|Скадовськ|Цюрупинськ|Чаплинка",
                        "22" => "Хмельницький|Антоніни|Базалія|Білогір'я|Віньківці|Волочиськ|Городок|Гриців|Деражня|Дунаївці|Ізяслав|Кам'янець-Подільський|Красилів|Летичів|Нетішин|Нова Ушиця|Полонне|Славута|Стара Синява|Старокостянтинів|Теофіполь|Чемерівці|Шепетівка|Ярмолинці",
                        "23" => "Черкаси|Ватутіне|Городище|Драбів|Єрки|Жашків|Зараевск|Звенигородка|Золотоноша|Ірдинь|Кам'янка|Канів|Катеринопіль|Корсунь-Шевченківський|Лисянка|Маньківка|Монастирище|Сміла|Тальне|Умань|Христинівка|Чорнобай|Чигирин|Шпола",
                        "24" => "Чернігів|Батурин|Бахмач|Березна|Бобровиця|Борзна|Варва|Вертіївка|Городня|Десна|Замглай|Ічня|Козелець|Короп|Корюківка|Куликівка|Мена|Ніжин|Новгород Сіверський|Носівка|Прилуки|Ріпки|Семенівка|Сосниця|Талалаївка|Щорс",
                        "25" => "Чернівці|Берегомет|Вашківці|Вижниця|Герца|Глибока|Заставна|Кельменці|Кіцмань|Мамаївці|Новодністровськ|Новоселиця|Путила|Сокиряни|Сторожинець|Хотин",
                    );
                break;

                case 'prof_categories':
                    $array = array (
                        "0" => "",
                        "1" => "1",
                        "2" => "2",
                        "3" => "3",
                        "4" => "4",
                        "5" => "5",
                        "6" => "6",
                    );
                break;
        }

        return $array;

    }

}
$key = "commmonmethods";
$this->{$key} = new XooUserUltraCommon();
?>
