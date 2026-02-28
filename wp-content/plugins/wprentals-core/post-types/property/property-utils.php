<?php 
        
if( !function_exists('wpestate_country_list_only_array') ):
/**
 * Returns an array of countries for the Wprenta;s theme.
 *
 * This function returns an associative array of country names and their translations, which can be used
 * in dropdowns or selection fields throughout the Wprentaks theme.
 *
 * @return array List of countries.
 * @since 1.0.0
 */
function wpestate_country_list_only_array() {
        $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals-core'),
                            'Albania'               => esc_html__('Albania','wprentals-core'),
                            'Algeria'               => esc_html__('Algeria','wprentals-core'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals-core'),
                            'Andorra'               => esc_html__('Andorra','wprentals-core'),
                            'Angola'                => esc_html__('Angola','wprentals-core'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals-core'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals-core'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals-core'),
                            'Argentina'             => esc_html__('Argentina','wprentals-core'),
                            'Armenia'               => esc_html__('Armenia','wprentals-core'),
                            'Aruba'                 => esc_html__('Aruba','wprentals-core'),
                            'Australia'             => esc_html__('Australia','wprentals-core'),
                            'Austria'               => esc_html__('Austria','wprentals-core'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals-core'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals-core'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals-core'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals-core'),
                            'Barbados'              => esc_html__('Barbados','wprentals-core'),
                            'Belarus'               => esc_html__('Belarus','wprentals-core'),
                            'Belgium'               => esc_html__('Belgium','wprentals-core'),
                            'Belize'                => esc_html__('Belize','wprentals-core'),
                            'Benin'                 => esc_html__('Benin','wprentals-core'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals-core'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals-core'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals-core'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals-core'),
                            'Botswana'              => esc_html__('Botswana','wprentals-core'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals-core'),
                            'Brazil'                => esc_html__('Brazil','wprentals-core'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals-core'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals-core'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals-core'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals-core'),
                            'Burundi'               => esc_html__('Burundi','wprentals-core'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals-core'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals-core'),
                            'Canada'                => esc_html__('Canada','wprentals-core'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals-core'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals-core'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals-core'),
                            'Chad'                  => esc_html__('Chad','wprentals-core'),
                            'Chile'                 => esc_html__('Chile','wprentals-core'),
                            'China'                 => esc_html__('China','wprentals-core'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals-core'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals-core'),
                            'Colombia'              => esc_html__('Colombia','wprentals-core'),
                            'Comoros'               => esc_html__('Comoros','wprentals-core'),
                            'Congo'                 => esc_html__('Congo','wprentals-core'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals-core'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals-core'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals-core'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals-core'),
                            'Croatia'               => esc_html__('Croatia','wprentals-core'),
                            'Cuba'                  => esc_html__('Cuba','wprentals-core'),
                            'Curacao'               => esc_html__('Curacao','wprentals-core'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals-core'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals-core'),
                            'Denmark'               => esc_html__('Denmark','wprentals-core'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals-core'),
                            'Dominica'              => esc_html__('Dominica','wprentals-core'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals-core'),
                            'East Timor'            => esc_html__('East Timor','wprentals-core'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals-core'),
                            'Egypt'                 => esc_html__('Egypt','wprentals-core'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals-core'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals-core'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals-core'),
                            'Estonia'               => esc_html__('Estonia','wprentals-core'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals-core'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals-core'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals-core'),
                            'Fiji'                  => esc_html__('Fiji','wprentals-core'),
                            'Finland'               => esc_html__('Finland','wprentals-core'),
                            'France'                => esc_html__('France','wprentals-core'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals-core'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals-core'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals-core'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals-core'),
                            'Gabon'                 => esc_html__('Gabon','wprentals-core'),
                            'Gambia'                => esc_html__('Gambia','wprentals-core'),
                            'Georgia'               => esc_html__('Georgia','wprentals-core'),
                            'Germany'               => esc_html__('Germany','wprentals-core'),
                            'Ghana'                 => esc_html__('Ghana','wprentals-core'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals-core'),
                            'Greece'                => esc_html__('Greece','wprentals-core'),
                            'Greenland'             => esc_html__('Greenland','wprentals-core'),
                            'Grenada'               => esc_html__('Grenada','wprentals-core'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals-core'),
                            'Guam'                  => esc_html__('Guam','wprentals-core'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals-core'),
                            'Guinea'                => esc_html__('Guinea','wprentals-core'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals-core'),
                            'Guyana'                => esc_html__('Guyana','wprentals-core'),
                            'Haiti'                 => esc_html__('Haiti','wprentals-core'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals-core'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals-core'),
                            'Honduras'              => esc_html__('Honduras','wprentals-core'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals-core'),
                            'Hungary'               => esc_html__('Hungary','wprentals-core'),
                            'Iceland'               => esc_html__('Iceland','wprentals-core'),
                            'India'                 => esc_html__('India','wprentals-core'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals-core'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals-core'),
                            'Iraq'                  => esc_html__('Iraq','wprentals-core'),
                            'Ireland'               => esc_html__('Ireland','wprentals-core'),
                            'Israel'                => esc_html__('Israel','wprentals-core'),
                            'Italy'                 => esc_html__('Italy','wprentals-core'),
                            'Island of Saba'        => esc_html__('Island of Saba','wprentals-core'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals-core'),
                            'Japan'                 => esc_html__('Japan','wprentals-core'),
                            'Jordan'                => esc_html__('Jordan','wprentals-core'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals-core'),
                            'Kenya'                 => esc_html__('Kenya','wprentals-core'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals-core'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals-core'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals-core'),
                            'Kosovo'                => esc_html__('Kosovo', 'wprentals-core'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals-core'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals-core'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals-core'),
                            'Latvia'                => esc_html__('Latvia','wprentals-core'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals-core'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals-core'),
                            'Liberia'               => esc_html__('Liberia','wprentals-core'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals-core'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals-core'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals-core'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals-core'),
                            'Macau'                 => esc_html__('Macau','wprentals-core'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals-core'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals-core'),
                            'Malawi'                => esc_html__('Malawi','wprentals-core'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals-core'),
                            'Maldives'              => esc_html__('Maldives','wprentals-core'),
                            'Mali'                  => esc_html__('Mali','wprentals-core'),
                            'Malta'                 => esc_html__('Malta','wprentals-core'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals-core'),
                            'Martinique'            => esc_html__('Martinique','wprentals-core'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals-core'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals-core'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals-core'),
                            'Mexico'                => esc_html__('Mexico','wprentals-core'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals-core'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals-core'),
                            'Monaco'                => esc_html__('Monaco','wprentals-core'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals-core'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals-core'),
                            'Morocco'               => esc_html__('Morocco','wprentals-core'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals-core'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals-core'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals-core'),
                            'Namibia'               => esc_html__('Namibia','wprentals-core'),
                            'Nauru'                 => esc_html__('Nauru','wprentals-core'),
                            'Nepal'                 => esc_html__('Nepal','wprentals-core'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals-core'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals-core'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals-core'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals-core'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals-core'),
                            'Niger'                 => esc_html__('Niger','wprentals-core'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals-core'),
                            'Niue'                  => esc_html__('Niue','wprentals-core'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals-core'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals-core'),
                            'Norway'                => esc_html__('Norway','wprentals-core'),
                            'Oman'                  => esc_html__('Oman','wprentals-core'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals-core'),
                            'Palau'                 => esc_html__('Palau','wprentals-core'),
                            'Panama'                => esc_html__('Panama','wprentals-core'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals-core'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals-core'),
                            'Peru'                  => esc_html__('Peru','wprentals-core'),
                            'Philippines'           => esc_html__('Philippines','wprentals-core'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals-core'),
                            'Poland'                => esc_html__('Poland','wprentals-core'),
                            'Portugal'              => esc_html__('Portugal','wprentals-core'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals-core'),
                            'Qatar'                 => esc_html__('Qatar','wprentals-core'),
                            'Reunion'               => esc_html__('Reunion','wprentals-core'),
                            'Romania'               => esc_html__('Romania','wprentals-core'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals-core'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals-core'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals-core'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals-core'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals-core'),
                            'Saint Barthélemy'      => esc_html__('Saint Barthélemy','wprentals-core'),
                            'Saint Martin'          => esc_html__('Saint Martin','wprentals-core'),
                            'Sint Maarten'          => esc_html__('Sint Maarten','wprentals-core'),
                            'Samoa'                 => esc_html__('Samoa','wprentals-core'),
                            'San Marino'            => esc_html__('San Marino','wprentals-core'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals-core'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals-core'),
                            'Serbia'                => esc_html__('Serbia','wprentals-core'),
                            'Senegal'               => esc_html__('Senegal','wprentals-core'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals-core'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals-core'),
                            'Singapore'             => esc_html__('Singapore','wprentals-core'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals-core'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals-core'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals-core'),
                            'Somalia'               => esc_html__('Somalia','wprentals-core'),
                            'South Africa'          => esc_html__('South Africa','wprentals-core'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals-core'),
                            'Spain'                 => esc_html__('Spain','wprentals-core'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals-core'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals-core'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals-core'),
                            'Sudan'                 => esc_html__('Sudan','wprentals-core'),
                            'Suriname'              => esc_html__('Suriname','wprentals-core'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals-core'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals-core'),
                            'Sweden'                => esc_html__('Sweden','wprentals-core'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals-core'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals-core'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals-core'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals-core'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals-core'),
                            'Thailand'              => esc_html__('Thailand','wprentals-core'),
                            'Togo'                  => esc_html__('Togo','wprentals-core'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals-core'),
                            'Tonga'                 => esc_html__('Tonga','wprentals-core'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals-core'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals-core'),
                            'Turkey'                => esc_html__('Turkey','wprentals-core'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals-core'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals-core'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals-core'),
                            'Uganda'                => esc_html__('Uganda','wprentals-core'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals-core'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals-core'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals-core'),
                            'United States'         => esc_html__('United States','wprentals-core'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals-core'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals-core'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals-core'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals-core'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals-core'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals-core'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals-core'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals-core'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals-core'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals-core'),
                            'Yemen'                 => esc_html__('Yemen','wprentals-core'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals-core'),
                            'Zambia'                => esc_html__('Zambia','wprentals-core'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals-core')
        );

        return $countries;
}
endif;

if( !function_exists('wpestate_return_country_list_translated') ):
    /**
 * Returns an array of translatable countries for the Wprenta;s theme.
 *
 * This function returns an associative array of country names and their translations, which can be used
 * in dropdowns or selection fields throughout the Wprentaks theme.
 *
 * @return array List of countries.
 * @since 1.0.0
 */
function wpestate_return_country_list_translated($selected='') {
    $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals-core'),
                            'Albania'               => esc_html__('Albania','wprentals-core'),
                            'Algeria'               => esc_html__('Algeria','wprentals-core'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals-core'),
                            'Andorra'               => esc_html__('Andorra','wprentals-core'),
                            'Angola'                => esc_html__('Angola','wprentals-core'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals-core'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals-core'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals-core'),
                            'Argentina'             => esc_html__('Argentina','wprentals-core'),
                            'Armenia'               => esc_html__('Armenia','wprentals-core'),
                            'Aruba'                 => esc_html__('Aruba','wprentals-core'),
                            'Australia'             => esc_html__('Australia','wprentals-core'),
                            'Austria'               => esc_html__('Austria','wprentals-core'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals-core'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals-core'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals-core'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals-core'),
                            'Barbados'              => esc_html__('Barbados','wprentals-core'),
                            'Belarus'               => esc_html__('Belarus','wprentals-core'),
                            'Belgium'               => esc_html__('Belgium','wprentals-core'),
                            'Belize'                => esc_html__('Belize','wprentals-core'),
                            'Benin'                 => esc_html__('Benin','wprentals-core'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals-core'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals-core'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals-core'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals-core'),
                            'Botswana'              => esc_html__('Botswana','wprentals-core'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals-core'),
                            'Brazil'                => esc_html__('Brazil','wprentals-core'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals-core'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals-core'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals-core'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals-core'),
                            'Burundi'               => esc_html__('Burundi','wprentals-core'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals-core'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals-core'),
                            'Canada'                => esc_html__('Canada','wprentals-core'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals-core'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals-core'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals-core'),
                            'Chad'                  => esc_html__('Chad','wprentals-core'),
                            'Chile'                 => esc_html__('Chile','wprentals-core'),
                            'China'                 => esc_html__('China','wprentals-core'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals-core'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals-core'),
                            'Colombia'              => esc_html__('Colombia','wprentals-core'),
                            'Comoros'               => esc_html__('Comoros','wprentals-core'),
                            'Congo'                 => esc_html__('Congo','wprentals-core'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals-core'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals-core'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals-core'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals-core'),
                            'Croatia (Hrvatska)'    => esc_html__('Croatia (Hrvatska)','wprentals-core'),
                            'Cuba'                  => esc_html__('Cuba','wprentals-core'),
                            'Curacao'               => esc_html__('Curacao','wprentals-core'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals-core'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals-core'),
                            'Denmark'               => esc_html__('Denmark','wprentals-core'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals-core'),
                            'Dominica'              => esc_html__('Dominica','wprentals-core'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals-core'),
                            'East Timor'            => esc_html__('East Timor','wprentals-core'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals-core'),
                            'Egypt'                 => esc_html__('Egypt','wprentals-core'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals-core'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals-core'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals-core'),
                            'Estonia'               => esc_html__('Estonia','wprentals-core'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals-core'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals-core'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals-core'),
                            'Fiji'                  => esc_html__('Fiji','wprentals-core'),
                            'Finland'               => esc_html__('Finland','wprentals-core'),
                            'France'                => esc_html__('France','wprentals-core'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals-core'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals-core'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals-core'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals-core'),
                            'Gabon'                 => esc_html__('Gabon','wprentals-core'),
                            'Gambia'                => esc_html__('Gambia','wprentals-core'),
                            'Georgia'               => esc_html__('Georgia','wprentals-core'),
                            'Germany'               => esc_html__('Germany','wprentals-core'),
                            'Ghana'                 => esc_html__('Ghana','wprentals-core'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals-core'),
                            'Greece'                => esc_html__('Greece','wprentals-core'),
                            'Greenland'             => esc_html__('Greenland','wprentals-core'),
                            'Grenada'               => esc_html__('Grenada','wprentals-core'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals-core'),
                            'Guam'                  => esc_html__('Guam','wprentals-core'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals-core'),
                            'Guinea'                => esc_html__('Guinea','wprentals-core'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals-core'),
                            'Guyana'                => esc_html__('Guyana','wprentals-core'),
                            'Haiti'                 => esc_html__('Haiti','wprentals-core'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals-core'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals-core'),
                            'Honduras'              => esc_html__('Honduras','wprentals-core'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals-core'),
                            'Hungary'               => esc_html__('Hungary','wprentals-core'),
                            'Iceland'               => esc_html__('Iceland','wprentals-core'),
                            'India'                 => esc_html__('India','wprentals-core'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals-core'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals-core'),
                            'Iraq'                  => esc_html__('Iraq','wprentals-core'),
                            'Ireland'               => esc_html__('Ireland','wprentals-core'),
                            'Israel'                => esc_html__('Israel','wprentals-core'),
                            'Italy'                 => esc_html__('Italy','wprentals-core'),
                            'Island of Saba'        => esc_html__('Island of Saba','wprentals-core'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals-core'),
                            'Japan'                 => esc_html__('Japan','wprentals-core'),
                            'Jordan'                => esc_html__('Jordan','wprentals-core'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals-core'),
                            'Kenya'                 => esc_html__('Kenya','wprentals-core'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals-core'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals-core'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals-core'),
                            'Kosovo'                => esc_html__('Kosovo', 'wprentals-core'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals-core'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals-core'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals-core'),
                            'Latvia'                => esc_html__('Latvia','wprentals-core'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals-core'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals-core'),
                            'Liberia'               => esc_html__('Liberia','wprentals-core'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals-core'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals-core'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals-core'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals-core'),
                            'Macau'                 => esc_html__('Macau','wprentals-core'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals-core'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals-core'),
                            'Malawi'                => esc_html__('Malawi','wprentals-core'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals-core'),
                            'Maldives'              => esc_html__('Maldives','wprentals-core'),
                            'Mali'                  => esc_html__('Mali','wprentals-core'),
                            'Malta'                 => esc_html__('Malta','wprentals-core'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals-core'),
                            'Martinique'            => esc_html__('Martinique','wprentals-core'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals-core'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals-core'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals-core'),
                            'Mexico'                => esc_html__('Mexico','wprentals-core'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals-core'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals-core'),
                            'Monaco'                => esc_html__('Monaco','wprentals-core'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals-core'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals-core'),
                            'Morocco'               => esc_html__('Morocco','wprentals-core'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals-core'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals-core'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals-core'),
                            'Namibia'               => esc_html__('Namibia','wprentals-core'),
                            'Nauru'                 => esc_html__('Nauru','wprentals-core'),
                            'Nepal'                 => esc_html__('Nepal','wprentals-core'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals-core'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals-core'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals-core'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals-core'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals-core'),
                            'Niger'                 => esc_html__('Niger','wprentals-core'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals-core'),
                            'Niue'                  => esc_html__('Niue','wprentals-core'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals-core'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals-core'),
                            'Norway'                => esc_html__('Norway','wprentals-core'),
                            'Oman'                  => esc_html__('Oman','wprentals-core'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals-core'),
                            'Palau'                 => esc_html__('Palau','wprentals-core'),
                            'Panama'                => esc_html__('Panama','wprentals-core'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals-core'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals-core'),
                            'Peru'                  => esc_html__('Peru','wprentals-core'),
                            'Philippines'           => esc_html__('Philippines','wprentals-core'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals-core'),
                            'Poland'                => esc_html__('Poland','wprentals-core'),
                            'Portugal'              => esc_html__('Portugal','wprentals-core'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals-core'),
                            'Qatar'                 => esc_html__('Qatar','wprentals-core'),
                            'Reunion'               => esc_html__('Reunion','wprentals-core'),
                            'Romania'               => esc_html__('Romania','wprentals-core'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals-core'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals-core'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals-core'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals-core'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals-core'),
                            'Samoa'                 => esc_html__('Samoa','wprentals-core'),
                            'San Marino'            => esc_html__('San Marino','wprentals-core'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals-core'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals-core'),
                            'Serbia'                => esc_html__('Serbia','wprentals-core'),
                            'Senegal'               => esc_html__('Senegal','wprentals-core'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals-core'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals-core'),
                            'Singapore'             => esc_html__('Singapore','wprentals-core'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals-core'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals-core'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals-core'),
                            'Somalia'               => esc_html__('Somalia','wprentals-core'),
                            'South Africa'          => esc_html__('South Africa','wprentals-core'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals-core'),
                            'Spain'                 => esc_html__('Spain','wprentals-core'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals-core'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals-core'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals-core'),
                            'Sudan'                 => esc_html__('Sudan','wprentals-core'),
                            'Suriname'              => esc_html__('Suriname','wprentals-core'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals-core'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals-core'),
                            'Sweden'                => esc_html__('Sweden','wprentals-core'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals-core'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals-core'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals-core'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals-core'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals-core'),
                            'Thailand'              => esc_html__('Thailand','wprentals-core'),
                            'Togo'                  => esc_html__('Togo','wprentals-core'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals-core'),
                            'Tonga'                 => esc_html__('Tonga','wprentals-core'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals-core'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals-core'),
                            'Turkey'                => esc_html__('Turkey','wprentals-core'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals-core'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals-core'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals-core'),
                            'Uganda'                => esc_html__('Uganda','wprentals-core'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals-core'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals-core'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals-core'),
                            'United States'         => esc_html__('United States','wprentals-core'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals-core'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals-core'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals-core'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals-core'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals-core'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals-core'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals-core'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals-core'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals-core'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals-core'),
                            'Yemen'                 => esc_html__('Yemen','wprentals-core'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals-core'),
                            'Zambia'                => esc_html__('Zambia','wprentals-core'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals-core')
        );
    if($selected!=''){
        $countries= array_change_key_case($countries, CASE_LOWER);
        if ( isset( $countries[$selected]) ) {
            return $countries[$selected];
        }
    }else{
        return $countries;
    }
}
endif;




/**
 * Function to display custom fields for properties in the Wprentaks theme.
 *
 * This file contains a function to display custom fields used in the Wprentaks theme.
 * The function can show various types of fields, such as text, numeric, dropdown, etc., with appropriate labels.
 *
 * Package: Wprentaks Theme
 * Dependencies: WordPress, Wprentaks-Core Plugin
 * Usage: This function is used for displaying property custom fields in the admin interface.
 */

 if ( ! function_exists( 'wpestate_show_custom_field' ) ) :

    /**
     * Displays a custom field for a property.
     *
     * @param bool   $show             Whether to print or return the template.
     * @param string $slug             The slug of the field.
     * @param string $name             The name of the field.
     * @param string $label            The label for the field.
     * @param string $type             The type of field ('long text', 'short text', 'numeric', 'date', 'dropdown').
     * @param int    $order            The order of the field (unused here but retained for potential future use).
     * @param string $dropdown_values  Values for dropdown options (comma-separated).
     * @param int    $post_id          The post ID.
     * @param string $value            The value of the field (optional).
     *
     * @since 1.0.0
     */
    function wpestate_show_custom_field( $show, $slug, $name, $label, $type, $order, $dropdown_values, $post_id, $value = '' ) {

        // Get value if not provided
        if ( $value == '' ) {
            $value = get_post_meta( $post_id, $slug, true );
            if ( $type == 'numeric' ) {
                $value = get_post_meta( $post_id, $slug, true );
                if ( $value !== '' ) {
                    $value = floatval( $value );
                }
            } else {
                $value = esc_html( get_post_meta( $post_id, $slug, true ) );
            }
        }

        // Template for the field
        $template = '';
        if ( $type == 'long text' ) {
            $template .= '<label for="' . esc_attr( $slug ) . '">' . esc_html( $label ) . ' ' . esc_html__( '(*text)', 'wprentals-core' ) . ' </label>';
            $template .= '<textarea type="text" class="form-control" id="' . esc_attr( $slug ) . '" name="' . esc_attr( $slug ) . '" rows="3" cols="42">' . esc_textarea( $value ) . '</textarea>';
        } elseif ( $type == 'short text' ) {
            $template .= '<label for="' . esc_attr( $slug ) . '">' . esc_html( $label ) . ' ' . esc_html__( '(*text)', 'wprentals-core' ) . ' </label>';
            $template .= '<input type="text" class="form-control" id="' . esc_attr( $slug ) . '" name="' . esc_attr( $slug ) . '" value="' . esc_attr( $value ) . '">';
        } elseif ( $type == 'numeric' ) {
            $template .= '<label for="' . esc_attr( $slug ) . '">' . esc_html( $label ) . ' ' . esc_html__( '(*numeric)', 'wprentals-core' ) . ' </label>';
            $template .= '<input type="text" class="form-control" id="' . esc_attr( $slug ) . '" name="' . esc_attr( $slug ) . '" value="' . floatval( $value ) . '">';
        } elseif ( $type == 'date' ) {
            $template .= '<label for="' . esc_attr( $slug ) . '">' . esc_html( $label ) . ' ' . esc_html__( '(*date)', 'wprentals-core' ) . ' </label>';
            $template .= '<input type="text" class="form-control" id="' . esc_attr( $slug ) . '" name="' . esc_attr( $slug ) . '" value="' . esc_attr( $value ) . '">';
            $template .= wpestate_date_picker_translation_return( esc_attr( $slug ) );
        } elseif ( $type == 'dropdown' ) {
            $dropdown_values_array = explode( ',', $dropdown_values );

            $template .= '<label for="' . esc_attr( $slug ) . '">' . esc_html( $label ) . ' </label>';
            $template .= '<select id="' . esc_attr( $slug ) . '" name="' . esc_attr( $slug ) . '">';
            $template .= '<option value="">' . esc_html__( 'Not Available', 'wprentals-core' ) . '</option>';
            foreach ( $dropdown_values_array as $key => $value_drop ) {
                $value_drop = stripslashes( $value_drop );
                $template .= '<option value="' . esc_attr( trim( $value_drop ) ) . '"';
                if ( trim( html_entity_decode( $value, ENT_QUOTES ) ) == trim( html_entity_decode( $value_drop, ENT_QUOTES ) ) ) {
                    $template .= ' selected';
                }
                if ( function_exists( 'icl_translate' ) ) {
                    $value_drop = apply_filters( 'wpml_translate_single_string', $value_drop, 'custom field value', 'custom_field_value' . $value_drop );
                }
                $template .= '>' . esc_html( trim( $value_drop ) ) . '</option>';
            }
            $template .= '</select>';
        }

        // Output or return the template
        if ( $show == 1 ) {
            echo $template; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            return $template;
        }
    }
endif;
                