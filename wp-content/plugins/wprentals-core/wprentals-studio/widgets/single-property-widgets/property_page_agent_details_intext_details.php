<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use function apply_filters;
use function esc_attr;
use function esc_html;
use function esc_html__;
use function esc_url;
use function get_post;
use function get_post_field;
use function get_post_meta;
use function get_post_thumbnail_id;
use function get_post_type;
use function get_stylesheet_directory_uri;
use function get_the_author_meta;
use function get_the_ID;
use function get_the_title;
use function is_email;
use function is_preview;
use function is_singular;
use function wp_get_attachment_image_url;
use function wp_kses_post;
use function wprentals_get_option;
use function wpestate_last_property_id;
use function wpsestate_get_author;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Agent_Details_Intext extends Widget_Base {

    /**
     * Retrieve the widget name.
     */
    public function get_name() {
        return 'property_page_agent_details_intext_details';
    }

    /**
     * Retrieve the widget title.
     */
    public function get_title() {
        return esc_html__('Owner Detail', 'wprentals-core');
    }

    /**
     * Retrieve the widget icon.
     */
    public function get_icon() {
        return 'wprentals-note eicon-kit-details';
    }

    /**
     * Retrieve widget categories.
     */
    public function get_categories() {
        return ['wprentals_single_property'];
    }

    /**
     * Retrieve script dependencies.
     */
    public function get_script_depends() {
        return [];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'detail',
            [
                'label' => esc_html__('Select owner detail', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'name',
                'options' => $this->get_owner_detail_options(),
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => esc_html__('Element Label', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Owner Detail', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'label_inline',
            [
                'label' => esc_html__('Display Label Inline', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_on' => esc_html__('Yes', 'wprentals-core'),
                'label_off' => esc_html__('No', 'wprentals-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label_style',
            [
                'label' => esc_html__('Label', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_custom_detail_label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .property_custom_detail_label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_value_style',
            [
                'label' => esc_html__('Value', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_custom_detail_value' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .property_custom_detail_value a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .property_custom_detail_value',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $detail       = $settings['detail'] ?? 'name';
        $label        = $settings['label'] ?? '';
        $label_inline = ($settings['label_inline'] ?? '') === 'yes';

        $property_id = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $property_id = wpestate_last_property_id();
        }

        if (!$property_id) {
            $property_id = wpestate_last_property_id();
        }

        if ($property_id && 'estate_property' !== get_post_type($property_id)) {
            $last_property_id = wpestate_last_property_id();
            if ($last_property_id) {
                $property_id = $last_property_id;
            }
        }

        if (!$property_id) {
            return;
        }

        $switched = false;

        if (
            method_exists(Plugin::instance()->db, 'switch_to_post') &&
            method_exists(Plugin::instance()->db, 'restore_current_post')
        ) {
            Plugin::instance()->db->switch_to_post($property_id);
            $switched = true;
        }

        $owner_details = $this->collect_owner_details($property_id);
        $resolved      = $this->resolve_owner_detail_value($detail, $owner_details);

        if ($switched) {
            Plugin::instance()->db->restore_current_post();
        }

        if (empty($resolved['value'])) {
            return;
        }

        if ($label_inline && empty($resolved['allow_inline'])) {
            $label_inline = false;
        }

        $value_output = $resolved['value'];

        if ($label_inline && function_exists('\\wprentals_estate_property_simple_detail_inline_value')) {
            $value_output = \wprentals_estate_property_simple_detail_inline_value($value_output);
        }

        if ('' === trim((string) $value_output)) {
            return;
        }

        $wrapper_classes = ['property_custom_detail_wrapper', 'property_custom_detail_wrapper--owner'];

        if ($label_inline) {
            $wrapper_classes[] = 'property_custom_detail_wrapper--inline';
        }

        $wrapper_class_attr = esc_attr(implode(' ', array_unique(array_filter($wrapper_classes))));
        $value_tag          = $label_inline ? 'span' : 'div';

        $label_output = '';
        if ('' !== trim((string) $label)) {
            $label_output = esc_html($label) . ' ';
        }

        printf(
            '<div class="%1$s"><span class="property_custom_detail_label">%2$s</span><%3$s class="property_custom_detail_value">%4$s</%3$s></div>',
            $wrapper_class_attr,
            $label_output,
            $value_tag,
            $value_output
        );
    }

    /**
     * Retrieve the list of selectable owner details.
     *
     * @return array
     */
    protected function get_owner_detail_options() {
        return [
            'name'          => esc_html__('Display Name', 'wprentals-core'),
            'bio'           => esc_html__('Biography', 'wprentals-core'),
            'profile_photo' => esc_html__('Profile Photo', 'wprentals-core'),
          
            'live_in'       => esc_html__('Location', 'wprentals-core'),
            'i_speak'       => esc_html__('Spoken Languages', 'wprentals-core'),
            'phone'         => esc_html__('Phone Number', 'wprentals-core'),
            'mobile'        => esc_html__('Mobile Number', 'wprentals-core'),
            'email'         => esc_html__('Email Address', 'wprentals-core'),
            'skype'         => esc_html__('Skype Handle', 'wprentals-core'),
            'website'       => esc_html__('Website', 'wprentals-core'),
            'facebook'      => esc_html__('Facebook', 'wprentals-core'),
            'twitter'       => esc_html__('Twitter', 'wprentals-core'),
            'linkedin'      => esc_html__('LinkedIn', 'wprentals-core'),
            'pinterest'     => esc_html__('Pinterest', 'wprentals-core'),
            'instagram'     => esc_html__('Instagram', 'wprentals-core'),
            'youtube'       => esc_html__('YouTube', 'wprentals-core'),
            'payment_info'  => esc_html__('Payment Instructions', 'wprentals-core'),
        ];
    }

    /**
     * Collect owner details for the given property.
     *
     * @param int $property_id Property ID.
     * @return array
     */
    protected function collect_owner_details($property_id) {
        $details = [
            'name'          => '',
            'bio'           => '',
            'profile_photo' => '',
            'position'      => '',
            'pitch'         => '',
            'live_in'       => '',
            'i_speak'       => '',
            'phone'         => '',
            'mobile'        => '',
            'email'         => '',
            'skype'         => '',
            'website'       => '',
            'facebook'      => '',
            'twitter'       => '',
            'linkedin'      => '',
            'pinterest'     => '',
            'instagram'     => '',
            'youtube'       => '',
            'payment_info'  => '',
        ];

        if (!$property_id) {
            return $details;
        }

        $agent_post_id = intval(get_post_meta($property_id, 'property_agent', true));
        $user_id       = 0;

        if ($agent_post_id > 0) {
            $agent_post = get_post($agent_post_id);
            if ($agent_post && $agent_post->post_type === 'estate_agent') {
                $user_id = intval(get_post_meta($agent_post_id, 'user_agent_id', true));
                if (!$user_id && isset($agent_post->post_author)) {
                    $user_id = (int) $agent_post->post_author;
                }

                $details['name'] = get_the_title($agent_post_id);
                $details['bio']  = apply_filters('the_content', get_post_field('post_content', $agent_post_id));

                $details['profile_photo'] = $this->resolve_agent_image($agent_post_id, $user_id);

                $details['position'] = $this->maybe_translate_meta($agent_post_id, 'agent_position');
                $details['pitch']    = get_post_meta($agent_post_id, 'agent_pitch', true);
                $details['live_in']  = get_post_meta($agent_post_id, 'live_in', true);
                $details['i_speak']  = get_post_meta($agent_post_id, 'i_speak', true);
                $details['phone']    = get_post_meta($agent_post_id, 'agent_phone', true);
                $details['mobile']   = get_post_meta($agent_post_id, 'agent_mobile', true);

                $email = get_post_meta($agent_post_id, 'agent_email', true);
                if (empty($email) && $user_id) {
                    $email = get_the_author_meta('user_email', $user_id);
                }
                $details['email'] = $email;

                $details['skype']       = get_post_meta($agent_post_id, 'agent_skype', true);
                $details['website']     = get_post_meta($agent_post_id, 'agent_website', true);
                $details['facebook']    = get_post_meta($agent_post_id, 'agent_facebook', true);
                $details['twitter']     = get_post_meta($agent_post_id, 'agent_twitter', true);
                $details['linkedin']    = get_post_meta($agent_post_id, 'agent_linkedin', true);
                $details['pinterest']   = get_post_meta($agent_post_id, 'agent_pinterest', true);
                $details['instagram']   = get_post_meta($agent_post_id, 'agent_instagram', true);
                $details['youtube']     = get_post_meta($agent_post_id, 'agent_youtube', true);
                $details['payment_info'] = get_post_meta($agent_post_id, 'payment_info', true);

                return $details;
            }
        }

        $user_id = wpsestate_get_author($property_id);
        if ($user_id) {
            $first_name = get_the_author_meta('first_name', $user_id);
            $last_name  = get_the_author_meta('last_name', $user_id);
            $details['name'] = trim($first_name . ' ' . $last_name);
            if ('' === $details['name']) {
                $details['name'] = get_the_author_meta('display_name', $user_id);
            }

            $details['bio']           = apply_filters('the_content', get_the_author_meta('description', $user_id));
            $details['profile_photo'] = $this->resolve_user_image($user_id);
            $details['live_in']       = get_the_author_meta('live_in', $user_id);
            $details['i_speak']       = get_the_author_meta('i_speak', $user_id);
            $details['phone']         = get_the_author_meta('phone', $user_id);
            $details['mobile']        = get_the_author_meta('mobile', $user_id);
            $details['email']         = get_the_author_meta('user_email', $user_id);
            $details['skype']         = get_the_author_meta('skype', $user_id);
            $details['website']       = get_the_author_meta('user_url', $user_id);
            $details['facebook']      = get_the_author_meta('facebook', $user_id);
            $details['twitter']       = get_the_author_meta('twitter', $user_id);
            $details['linkedin']      = get_the_author_meta('linkedin', $user_id);
            $details['pinterest']     = get_the_author_meta('pinterest', $user_id);
            $details['instagram']     = get_the_author_meta('instagram', $user_id);
            $details['youtube']       = get_the_author_meta('youtube', $user_id);
            $details['payment_info']  = get_the_author_meta('payment_info', $user_id);
        }

        return $details;
    }

    /**
     * Resolve an owner detail into display value and configuration.
     *
     * @param string $detail_key Detail key.
     * @param array  $details    Owner details map.
     *
     * @return array
     */
    protected function resolve_owner_detail_value($detail_key, $details) {
        $value        = '';
        $allow_inline = true;

        switch ($detail_key) {
            case 'name':
            case 'position':
            case 'pitch':
            case 'live_in':
            case 'i_speak':
            case 'skype':
                $value = esc_html(trim((string) ($details[$detail_key] ?? '')));
                break;
            case 'phone':
            case 'mobile':
                $raw_number = trim((string) ($details[$detail_key] ?? ''));
                if ('' !== $raw_number) {
                    $href = $this->build_phone_href($raw_number);
                    if ($href) {
                        $value = sprintf(
                            '<a href="%1$s">%2$s</a>',
                            esc_url($href),
                            esc_html($raw_number)
                        );
                    } else {
                        $value = esc_html($raw_number);
                    }
                }
                break;
            case 'email':
                $email = trim((string) ($details['email'] ?? ''));
                if ($email && is_email($email)) {
                    $value = sprintf(
                        '<a href="mailto:%1$s">%2$s</a>',
                        esc_attr($email),
                        esc_html($email)
                    );
                } elseif ($email) {
                    $value = esc_html($email);
                }
                break;
            case 'website':
                $url = trim((string) ($details['website'] ?? ''));
                if ($url) {
                    $value = sprintf(
                        '<a href="%1$s" rel="nofollow noopener" target="_blank">%2$s</a>',
                        esc_url($url),
                        esc_html($this->trim_url_label($url))
                    );
                }
                break;
            case 'facebook':
            case 'twitter':
            case 'linkedin':
            case 'pinterest':
            case 'instagram':
            case 'youtube':
                $url = trim((string) ($details[$detail_key] ?? ''));
                if ($url) {
                    $label = $this->get_social_label($detail_key, $url);
                    $value = sprintf(
                        '<a href="%1$s" rel="nofollow noopener" target="_blank">%2$s</a>',
                        esc_url($url),
                        esc_html($label)
                    );
                }
                break;
            case 'profile_photo':
                $image_url = trim((string) ($details['profile_photo'] ?? ''));
                if ($image_url) {
                    $alt_text = esc_attr($details['name'] ?: esc_html__('Owner photo', 'wprentals-core'));
                    $value    = sprintf('<img class="property_owner_detail_image" src="%1$s" alt="%2$s" />', esc_url($image_url), $alt_text);
                }
                $allow_inline = false;
                break;
            case 'bio':
                $value        = wp_kses_post($details['bio']);
                $allow_inline = false;
                break;
            case 'payment_info':
                $value        = wp_kses_post($details['payment_info']);
                $allow_inline = false;
                break;
            default:
                $raw_value = $details[$detail_key] ?? '';
                if (is_string($raw_value)) {
                    $value = esc_html(trim($raw_value));
                }
                break;
        }

        if ('' === trim((string) $value)) {
            $value = '';
        }

        return [
            'value'        => $value,
            'allow_inline' => $allow_inline,
        ];
    }

    /**
     * Resolve featured image for agent posts.
     *
     * @param int $agent_post_id Agent post ID.
     * @param int $user_id       Related user ID.
     *
     * @return string
     */
    protected function resolve_agent_image($agent_post_id, $user_id) {
        $image = '';

        $thumbnail_id = get_post_thumbnail_id($agent_post_id);
        if ($thumbnail_id) {
            $image = wp_get_attachment_image_url($thumbnail_id, 'medium');
        }

        if (!$image && $user_id) {
            $custom_picture = get_the_author_meta('custom_picture', $user_id);
            if ($custom_picture) {
                $image = $custom_picture;
            }
        }

        if (!$image) {
            $image = wprentals_get_option('wp_estate_default_user_image', 'url');
        }

        if (!$image) {
            $image = get_stylesheet_directory_uri() . '/img/default-user.png';
        }

        return $image;
    }

    /**
     * Resolve fallback image for users without agent posts.
     *
     * @param int $user_id User ID.
     *
     * @return string
     */
    protected function resolve_user_image($user_id) {
        $image = get_the_author_meta('custom_picture', $user_id);

        if (!$image) {
            $image = wprentals_get_option('wp_estate_default_user_image', 'url');
        }

        if (!$image) {
            $image = get_stylesheet_directory_uri() . '/img/default-user.png';
        }

        return $image;
    }

    /**
     * Maybe translate agent meta values via WPML.
     *
     * @param int    $agent_post_id Agent post ID.
     * @param string $meta_key      Meta key.
     *
     * @return string
     */
    protected function maybe_translate_meta($agent_post_id, $meta_key) {
        $value = get_post_meta($agent_post_id, $meta_key, true);

        if ('' === trim((string) $value)) {
            return '';
        }

        if (function_exists('icl_translate')) {
            $value = icl_translate('wprentals', $meta_key, $value);
        }

        return $value;
    }

    /**
     * Build a sanitized phone href.
     *
     * @param string $number Raw phone number.
     *
     * @return string
     */
    protected function build_phone_href($number) {
        $filtered = preg_replace('/[^0-9+*#;,]/', '', $number);

        if ('' === $filtered) {
            return '';
        }

        return 'tel:' . $filtered;
    }

    /**
     * Create readable label for social profiles.
     *
     * @param string $key Social key.
     * @param string $url Profile URL.
     *
     * @return string
     */
    protected function get_social_label($key, $url) {
        switch ($key) {
            case 'facebook':
                return esc_html__('Facebook', 'wprentals-core');
            case 'twitter':
                return esc_html__('Twitter', 'wprentals-core');
            case 'linkedin':
                return esc_html__('LinkedIn', 'wprentals-core');
            case 'pinterest':
                return esc_html__('Pinterest', 'wprentals-core');
            case 'instagram':
                return esc_html__('Instagram', 'wprentals-core');
            case 'youtube':
                return esc_html__('YouTube', 'wprentals-core');
            default:
                return $this->trim_url_label($url);
        }
    }

    /**
     * Trim URL to a readable label.
     *
     * @param string $url URL.
     *
     * @return string
     */
    protected function trim_url_label($url) {
        $label = preg_replace('#^https?://#i', '', $url);
        $label = preg_replace('#/$#', '', $label);

        return $label;
    }
}
