<?php
class Wpestate_Social_widget extends WP_Widget {

	//function Wpestate_Social_widget(){
        function __construct(){    
		$widget_ops = array('classname' => 'social_sidebar', 'description' => 'Social Links on sidebar.');
		$control_ops = array('id_base' => 'wpestate_social_widget');
                parent::__construct('wpestate_social_widget', 'Wp Estate: Social Links', $widget_ops, $control_ops);
	}
	
        function form($instance){
		$defaults = array(  'title'         => esc_html__('Social Links:','wprentals_core'),
                                    'facebook'      => esc_html__('Facebook Link:','wprentals_core'),
                                    'whatsup'       => esc_html__('WhatsApp Link:','wprentals_core'),
                                    'telegram'      => esc_html__('Telegram Link:','wprentals_core'),
                                    'tiktok'        => esc_html__('TikTok Link:','wprentals_core'),
                                    'rss'           => esc_html__('Rss Link:','wprentals_core'),
                                    'twitter'       => esc_html__('X- Twiter Link:','wprentals_core'),
                                    'dribbble'      => esc_html__('Dribble Link:','wprentals_core'),
                                    'google'        => esc_html__('Google+ Link:','wprentals_core'),
                                    'linkedIn'      => esc_html__('Linkdin Link:','wprentals_core'),
                                 
                                    'tumblr'        => esc_html__('Tumblr Link:','wprentals_core'),
                                    'pinterest'     => esc_html__('Pinterest Link:','wprentals_core'),
                                    'yahoo'         => esc_html__('Yahoo Link:','wprentals_core'),
                                
                                    'youtube'       => esc_html__('Youtube Link:','wprentals_core'),
                                    'vimeo'         => esc_html__('Vimeo Link:','wprentals_core'),
                                    'instagram'     => esc_html__('Instagram Link:','wprentals_core'),
                                    'foursquare'    => esc_html__('FourthSquare Link:','wprentals_core'),
                                    );
		//$instance = wp_parse_args((array) $instance, $defaults);
                
                $display='';
                foreach($defaults as $key=>$value):
                    $display.='<p><label for="'.$this->get_field_id($key).'">'.$value.'</label></p>
                        <p><input id="'.$this->get_field_id($key).'" name="'.$this->get_field_name($key).'" value="';
                        
                        if(isset($instance[$key])){
                            $display.=$instance[$key];
                        }
                
                    $display.='" /></p>';
                endforeach;
                
                
                
               
		print $display;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title']      = $new_instance['title'];
		$instance['rss']        = $new_instance['rss'];
		$instance['facebook']   = $new_instance['facebook'];
                $instance['whatsup']    = $new_instance['whatsup'];
                $instance['telegram']   = $new_instance['telegram'];
                $instance['tiktok']     = $new_instance['tiktok'];
		$instance['twitter']    = $new_instance['twitter'];
		$instance['email']      = $new_instance['email'];
		$instance['dribbble']   = $new_instance['dribbble'];
		$instance['google']     = $new_instance['google'];
		$instance['linkedIn']   = $new_instance['linkedIn'];
		$instance['phone_no']   = $new_instance['phone_no'];
		$instance['tumblr']     = $new_instance['tumblr'];
		$instance['pinterest']  = $new_instance['pinterest'];
		$instance['youtube']    = $new_instance['youtube'];
		$instance['vimeo']      = $new_instance['vimeo'];
                $instance['instagram']  = $new_instance['instagram'];
		$instance['foursquare'] = $new_instance['foursquare'];
                
		return $instance;
	}

	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
                
                $defaults = array( 
                    'facebook'      => '<i class="fab fa-facebook-f"></i>',
                    'whatsup'       => '<i class="fab fa-whatsapp"></i>',
                    'telegram'      => '<i class="fab fa-telegram-plane"></i>',
                    'tiktok'        => '<i class="fa-brands fa-tiktok"></i>',
                    'rss'           => '<i class="fas fa-rss fa-fw"></i>',
                    'twitter'       => '<i class="fa-brands fa-x-twitter"></i>',
                    'dribbble'      => '<i class="fab fa-dribbble  fa-fw"></i>',
                    'google'        => '<i class="fab fa-google-plus-g  fa-fw"></i>',
                    'linkedIn'      => '<i class="fab fa-linkedin-in"></i>',
                    'tumblr'        => '<i class="fab fa-tumblr  fa-fw"></i>',
                    'pinterest'     => '<i class="fab fa-pinterest-p  fa-fw"></i>',
                    'youtube'       => '<i class="fab fa-youtube  fa-fw"></i>',
                    'vimeo'         => '<i class="fab fa-vimeo-v  fa-fw"></i>',
                    'instagram'     => '<i class="fa-brands fa-instagram"></i>',
                    'foursquare'    => '<i class="fab  fa-foursquare  fa-fw"></i>',
                );
                
                
                $display='';
		print $before_widget;

		if($title) {
			print $before_title.$title.$after_title;
		}
		$display.='<div class="social_sidebar_internal">';
		
                foreach ($defaults as $key=>$value):
                    if(isset($instance[$key]) && $instance[$key]){
			$display.='<a href="'.$instance[$key].'" target="_blank">'.$value.'</a>';
                    }
                endforeach;

		$display.='</div>';
		print $display;
		print $after_widget;
	}
}
















?>