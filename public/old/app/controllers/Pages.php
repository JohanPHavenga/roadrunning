<?php
class Pages extends Frontend_Controller {    
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('event_model');
    
        // carousel count on home page
        $this->cc=5;
    }

    // check if method exists, if not calls "view" method
    public function _remap($method, $params = array())
    {
        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }
        else
        {
            $this->view($method, $params = array());
        }
    }
    
    public function no_new_site($uri_string_encoded) {
        set_cookie("no_new_site", true, 604800);
        redirect(base_url(my_decrypt($uri_string_encoded)));
    }

    public function view($page = 'home')
    {
        if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        {
                // Whoops, we don't have a page for that!
                show_404();
        }

        if ($page=="home") {
//            $this->output->cache(60);
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->data_to_header['section']="home";

            // get races for the next 3 months
            $race_summary=$this->event_model->get_event_list_summary($from="date_range",["date_from"=>date("Y-m-d"),"date_to"=>date("Y-m-d",strtotime("+3 months")),"only_active"=>true]);
            $this->data_to_view['race_list_html']=$this->render_races_accordian_html($race_summary, "Next 3 Months");

            $this->data_to_header['css_to_load']=array(
                "plugins/revo-slider/css/settings.css",
                "plugins/revo-slider/css/layers.css",
                "plugins/revo-slider/css/navigation.css",
                "plugins/cubeportfolio/css/cubeportfolio.min.css",
                );

            $this->data_to_footer['js_to_load']=array(
                "plugins/revo-slider/js/jquery.themepunch.tools.min.js",
                "plugins/revo-slider/js/jquery.themepunch.revolution.min.js",
                "plugins/revo-slider/js/extensions/revolution.extension.slideanims.min.js",
                "plugins/revo-slider/js/extensions/revolution.extension.layeranimation.min.js",
                "plugins/revo-slider/js/extensions/revolution.extension.navigation.min.js",
                "plugins/cubeportfolio/js/jquery.cubeportfolio.min.js",
                );

            $this->data_to_footer['scripts_to_load']=array(
                "scripts/revo-slider/slider-4.js",
                "scripts/events.js",
                );
            
            
            $this->data_to_view['carousel_html']=$this->get_carousel_html($this->cc);            
            

        } else {
            $this->data_to_header['title'] = ucfirst($page); // Capitalize the first letter
        }

        // wts($this->data_to_footer);
        // exit();

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('pages/'.$page, $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
    
    private function get_carousel_html($carousel_count) 
    {        
        $carousel_data=$this->get_carousel_data($carousel_count);
              
        $c_arr[]="<ul>";
        for ($i = 1; $i <= $carousel_count; $i++) {  
            $img_url="img/carousel/".$carousel_data[$i]['img'];
            $quote=wordwrap($carousel_data[$i]['quote'], 30, "<br />\n");
            $c_arr[]="<li data-transition='fade' data-slotamount='1' data-masterspeed='1000'>";
                $c_arr[]="<img alt='' src='".base_url($img_url)."' data-bgposition='center center' data-bgfit='cover' data-bgrepeat='no-repeat'>";
                $c_arr[]="<div class='tp-caption customin customout' data-x='center' data-y='center' data-hoffset='' data-voffset='-50' data-speed='500' data-start='1000' data-transform_idle='o:1;' data-transform_in='rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;' data-transform_out='rX:0.5;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;' data-splitin='none' data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1' data-endspeed='600'>";
                $c_arr[]="<h3 class='c-main-title-circle c-font-48 c-font-bold c-font-center c-font-uppercase c-font-white c-block'>$quote</h3>";
                $c_arr[]="</div>";
                $c_arr[]="<div class='tp-caption lft' data-x='center' data-y='center' data-voffset='110' data-speed='900' data-start='2000' data-transform_idle='o:1;' data-transform_in='x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;' data-transform_out='x:100;y:100;rX:120;scaleX:0.75;scaleY:0.75;o:0;s:500;e:Back.easeInOut;'></div>";
            $c_arr[]="</li>";
        }
        $c_arr[]="</ul>";
        
        $carousel_html= implode("", $c_arr);
        return($carousel_html);
    }
    
    private function get_carousel_data($carousel_count) 
    {        
        $this->load->model('quote_model');
        $this->load->helper('file');        
        
        // CAROUSEL stuff
        // get random quotes
        $full_quote_arr=$this->quote_model->get_quote_list();
        foreach ($full_quote_arr as $quote) {
            $quote_arr[]=$quote['quote_quote'];
        }
        $quote_count=sizeof($quote_arr);        

        // get random bg image
        $img_arr = get_filenames("img/carousel");
        $img_count=sizeof($img_arr);
        
        // empty arrays for dup checks
        $rand_quote_num_arr=[];
        $rand_img_num_arr=[];
        
        for ($i = 1; $i <= $carousel_count; $i++) {
            // get random quote
            do {
                $rand_quote_num=rand(0, $quote_count-1);
            } while(in_array($rand_quote_num, $rand_quote_num_arr));
            // add number to array to stop duplication
            $rand_quote_num_arr[]=$rand_quote_num;
            // add quote to return array
            $return_arr[$i]['quote']=$quote_arr[$rand_quote_num];
            
            // get random image
            do {
                $rand_img_num=rand(0, $img_count-1);
            } while(in_array($rand_img_num, $rand_img_num_arr));
            // add number to array to stop duplication
            $rand_img_num_arr[]=$rand_img_num;
            // add img to return array
            $return_arr[$i]['img']=$img_arr[$rand_img_num];            
        }
        
        return ($return_arr);        
                        
    }

    
    public function search() {
        
        $this->load->model('file_model');
        $this->load->helper('form');
        
        if ($this->input->get('query')) {
            $params=["ss"=>$this->input->get('query'),"inc_all"=>$this->input->get('inc'),"inc_non_active"=>false];
            $this->data_to_view['search_results']=$this->event_model->get_event_list_summary($from="search",$params);       
            $this->data_to_view['msg']="We could <b>not find</b> any race matching your search.<br>Please try again.";
        } else {
            $this->data_to_view['msg']="Please use the <b>search box</b> above to seach for a race.";
        }
        
        // kry die logo files gekoppel aan die editions
        $this->data_to_view['edition_logo_list']=[];
        if (is_array($this->data_to_view['search_results'])) {
        foreach ($this->data_to_view['search_results'] as $year => $year_list) {
            foreach ($year_list as $month => $month_list) {
                foreach ($month_list as $day => $edition_list) {
                    foreach ($edition_list as $id => $event) {
                        $file_info=$this->file_model->check_filetype_exists("edition",$event['edition_id'],1);
                        if (@$file_info['file_id']>0) {
                            $this->data_to_view['edition_logo_list'][$event['edition_id']]=$file_info['file_name'];
                        }
                    }
                }
            }
        }
        }
                    
        $this->data_to_header['section']="home";
        $this->data_to_header['title']="Race Search";
        $this->data_to_header['meta_description']="Search for a running event or race";
        $this->data_to_header['keywords']="Running, Search, Results, Race";

        $this->data_to_header['css_to_load']=array(
            );

        $this->data_to_footer['js_to_load']=array(
            );

        $this->data_to_footer['scripts_to_load']=array(
            );

        // set crumbs
        $crumbs=[
            "Search Results"=>"",
            "Home"=>"/",
        ];
        // set title bar
        $this->data_to_header["title_bar"]=$this->render_topbar_html(
            [
                "title"=>$this->data_to_header['title'],
                "crumbs"=>$crumbs,
            ]);

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('pages/search', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }
    

    public function about() {

        $this->data_to_header['section']="home";
        $this->data_to_header['title']="About Us";

        $this->data_to_header['css_to_load']=array(
            "plugins/cubeportfolio/css/cubeportfolio.min.css",
            );

        $this->data_to_footer['js_to_load']=array(
            "plugins/cubeportfolio/js/jquery.cubeportfolio.min.js",
            );

        $this->data_to_footer['scripts_to_load']=array(
            "scripts/events.js",
            );

        // set crumbs
        $crumbs=[
            "About Us"=>"",
            "Home"=>"/",
        ];
        // set title bar
        $this->data_to_header["title_bar"]=$this->render_topbar_html(
            [
                "title"=>$this->data_to_header['title'],
                "crumbs"=>$crumbs,
            ]);

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('pages/about', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function my_404($page = 'home')
    {
        $this->output->set_status_header('404');
        $this->data_to_header['title']="Page not found";

        $crumbs=[
            "404"=>"",
            "Home"=>"/",
        ];

        // set title bar
        $this->data_to_view["title_bar"]=$this->render_topbar_html(
            [
                "title"=>"Page not found",
                "crumbs"=>$crumbs,
            ]);
        $this->data_to_footer['no_notice']=true;
        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('pages/404', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }


    public function mailer()
    {

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->data_to_header['title'] = "Mailer"; // Capitalize the first letter
        $this->data_to_view['page'] = "mailer";

         $this->data_to_header['css_to_load']=array(
                "plugins/revo-slider/css/settings.css",
                "plugins/revo-slider/css/layers.css",
                "plugins/revo-slider/css/navigation.css",
                "plugins/cubeportfolio/css/cubeportfolio.min.css",
                );

            $this->data_to_footer['js_to_load']=array(
                "plugins/revo-slider/js/jquery.themepunch.tools.min.js",
                "plugins/revo-slider/js/jquery.themepunch.revolution.min.js",
                "plugins/revo-slider/js/extensions/revolution.extension.slideanims.min.js",
                "plugins/revo-slider/js/extensions/revolution.extension.layeranimation.min.js",
                "plugins/revo-slider/js/extensions/revolution.extension.navigation.min.js",
                "plugins/cubeportfolio/js/jquery.cubeportfolio.min.js",
                );

            $this->data_to_footer['scripts_to_load']=array(
                "scripts/revo-slider/slider-4.js",
                "scripts/events.js",
                );
            
            
            $this->data_to_view['carousel_html']=$this->get_carousel_html($this->cc); 

        // set validation rules
        $this->form_validation->set_rules('dname', 'Name', 'required');
        $this->form_validation->set_rules('demail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('dphone', 'Phone', 'alpha_numeric_spaces');
        $this->form_validation->set_rules('dmsg', 'Comment', 'required');

        // get races for the next 3 months
        $race_summary=$this->event_model->get_event_list_summary($from="date_range",["date_from"=>date("Y-m-d"),"date_to"=>date("Y-m-d",strtotime("+3 months"))]);
        $this->data_to_view['race_list_html']=$this->render_races_accordian_html($race_summary, "Next 3 Months");

        // load correct view
        if ($this->form_validation->run() === FALSE)
        {
                $this->data_to_view['form_data']=$_POST;
                $this->data_to_view['email_send']=false;

                $this->load->view($this->header_url, $this->data_to_header);
                $this->load->view('pages/home', $this->data_to_view);
                $this->load->view($this->footer_url, $this->data_to_footer);
        }
        else
        {
            $this->load->library('email');
            $config['mailtype'] = 'html';
            $config['smtp_host'] = 'dandelion.aserv.co.za';
            $config['smtp_port'] = '465';
            $this->email->initialize($config);

            $this->email->from($this->input->post('demail'), $this->input->post('dname'));
            $this->email->to('info@roadrunning.co.za');
            // $this->email->cc('monicahav@gmail.com');
            $this->email->bcc($this->input->post('demail'));

            $this->email->subject('RR enquiry: '.$this->input->post('devent'));

            $msg_arr[]="Name: ".$this->input->post('dname');
            $msg_arr[]="Email: ".$this->input->post('demail');
            // $msg_arr[]="Phone: ".$this->input->post('dphone');
            $msg_arr[]="Event: ".$this->input->post('devent');
            $msg_arr[]="Comment: ".$this->input->post('dmsg');
            $msg=implode("<br>",$msg_arr);

            $this->email->message($msg);

            $this->email->send();

            $this->data_to_view['email_send']=true;
            $this->load->view($this->header_url, $this->data_to_header);
            $this->load->view('pages/home', $this->data_to_view);
            $this->load->view($this->footer_url, $this->data_to_footer);
        }
    }
    
    
    // test for the new race page
    // to be deleted
    public function race() {

        $this->data_to_header['section']="home";
        $this->data_to_header['title']="Tygerberg 30km";

        // set crumbs
        $crumbs=[
            "Tygerberg 30km"=>"",
            "Events"=>"/event/calendar",
            "Home"=>"/",
        ];
        // set title bar
        $this->data_to_header["title_bar"]=$this->render_topbar_html(
            [
                "crumbs"=>$crumbs,
            ]);
        
        $this->data_to_header['css_to_load']=array(
            "plugins/cubeportfolio/css/cubeportfolio.min.css",
            "plugins/owl-carousel/assets/owl.carousel.css",
            "plugins/fancybox/jquery.fancybox.css",
            "plugins/slider-for-bootstrap/css/slider.css",
            );

        $this->data_to_footer['js_to_load']=array(
            "plugins/cubeportfolio/js/jquery.cubeportfolio.min.js",
            "plugins/owl-carousel/owl.carousel.min.js",
            "plugins/fancybox/jquery.fancybox.pack.js",
            "plugins/smooth-scroll/jquery.smooth-scroll.js",
            "plugins/slider-for-bootstrap/js/bootstrap-slider.js",
            );

        $this->data_to_footer['scripts_to_load']=array(
            "http://maps.google.com/maps/api/js?sensor=true",
            "plugins/gmaps/gmaps.js",
//            "scripts/contact.js"
            );
        
        // script to add gmaps to the page
        $this->data_to_footer['scripts_to_display'][]="            
            var PageContact = function() {
            
                var _init = function() {
                    var mapbg = new GMaps({
                            div: '#gmapbg',
                            lat: -33.799011,
                            lng: 18.623957,
                            scrollwheel: false
                    });

                    mapbg.addMarker({
                            lat: -33.799011,
                            lng: 18.623957,
                            title: 'Your Location',
                            infoWindow: {
                                    content: '<h3>Tyger Run/Walk 2017</h3><p>25, Lorem Lis Street, Orange C, California, US</p>'
                            }
                    });
                }

                return {
                    init: function() {
                        _init();
                    }

                };
            }();

            $(document).ready(function() {
                PageContact.init();
            });";
        
        $this->data_to_footer['lat']="3.118823";

        $this->load->view($this->header_url, $this->data_to_header);
        $this->load->view('pages/race', $this->data_to_view);
        $this->load->view($this->footer_url, $this->data_to_footer);
    }

}
