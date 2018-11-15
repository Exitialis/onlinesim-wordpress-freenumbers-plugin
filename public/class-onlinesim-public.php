<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Exitialis
 * @since      1.0.0
 *
 * @package    Onlinesim
 * @subpackage Onlinesim/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Onlinesim
 * @subpackage Onlinesim/public
 * @author     Exitialis <admin@onlinesim.ru>
 */
class Onlinesim_Public
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of the plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {
    global $post;
    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Onlinesim_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Onlinesim_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    if (has_shortcode($post->post_content, $this->plugin_name)) {
      wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/onlinesim-public.css', array(), $this->version, 'all');
    }


  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {
    global $post;
    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Onlinesim_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Onlinesim_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    if (has_shortcode($post->post_content, $this->plugin_name)) {
      wp_enqueue_script('vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js', [], '2.5.16');
      wp_enqueue_script('axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js', [], '0.18.0');
      wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/onlinesim-public.js', array('jquery'), $this->version, false);
    }

  }

  public function shortcode()
  {
    return '<div id="on"><div class="panel panel-flat">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="margin-bottom: 10px">
                        <div class="col-md-4 col-sm-4 col-xs-12 no-border" v-for="item in countries">
                            <button class="btn btn-block btn-country" :class="{select: item.country === country}" @click="country = item.country">
                                <img :src="\'https://onlinesim.ru/assets/images/flags/\'+item.country+\'.png\'" class="flag">
                                <span v-text="item.country_text"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>


        <div class="panel panel-flat" >
            <div class="panel-body">
                <div class="row">

                    <div class="col-md-3">
                        <div class="panel panel-success" id="refreshPhoneList" style="position: static; zoom: 1;">
                            <div class="col-md-12">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Number</h3>
                                    <div style="position: absolute;top: 16px;right: 16px;">
                                        <div class="form-group" style="padding-top: 10px;">
                                            <ul class="icons-list">
                                                <li>
                                                    <a @click="loadPhoneList()">
                                                        <i class="glyphicon glyphicon-refresh"
                                                           style="font-weight: bold"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="sidebar-resive" id="menu-list">

                                    <ul class="navigation navigation-alt navigation-accordion no-padding-top no-padding-bottom list-unstyled">
                                        <li v-for="date in numbersList">
                                            <a @click="selectNumbers = date"
                                               :class="date.number == selectNumbers.number ? \'active\' : \'\'">
                                              
                                                <span class="copyclipboard">
                                                         <strong v-text="phonemask(date.number, date.country)">+7 (965) 384-7678</strong>
                                                           <span class="icon-clipboard5" title="Copy" onclick="copyToClipboard(event)"></span>
                                                    </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <span style="text-align: center; display: block" v-if="numbersList.length === 0"> NO NUMBERS</span>

                                </div>
                            </div>

                   
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="panel panel-default" id="refreshMessageList"
                             style="position: static; zoom: 1;">
                            <div class="panel-heading">
                                <h3 class="panel-title">All messages</h3>
                                <div class="time-zamena">
                                    <div class="form-group" style="padding-top: 10px;">
                                        <ul class="icons-list">
                                            <li>Number replace: <b><span v-text="selectNumbers.data_humans"></span></b></li>
                                            <li>
                                                <a @click="loadMessageList()">
                                                    <i class="glyphicon glyphicon-refresh" style="font-weight: bold"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul class="media-list media-list-bordered">
                                    <li class="media" v-for="date in messageList"
                                        :class="(date.in_number == \'notify\') ? \'no-active\' : \'\'">
                                        <div class="media-left">
                                                    <span class="mt-3 cursor-default btn border-success text-success btn-flat btn-icon btn-rounded ">
                                                        <img src="https://onlinesim.ru/assets/images/new/message.png" alt="message" v-if="date.in_number !== \'notify\'">
                                                        <img src="https://onlinesim.ru/assets/images/new/no-message.png" alt="message" v-else>
                                                    </span>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading">
                                                <span v-text="date.in_number"></span>
                                                <span class="media-annotation dotted"
                                                      v-text="date.data_humans"></span>
                                            </h6>

                                            <highlight :text="date.text" v-if="date.in_number !== \'notify\'"></highlight>
                                            <span v-else>nad<a href="#regulations">pad</a> </span>
                                        </div>
                                    </li>

                                    <span style="text-align: center; display: block"
                                        v-if="messageList.length == 0"> NO MESSAGES </span>


                                </ul>
                            </div>
                            <pg-paginate v-if="messageList.length > 0" v-model="selectpage"
                                         :total_page="totalpages" :offset="4"></pg-paginate>

                        </div>
                    </div>
                </div>

                <div style="text-align: center">
                    <hr>
                    <a v-if="!infoblock" @click="loadIgnoreList" name="regulations">RULES</a>
                    <span class="hide-info" v-else v-html="infoblock"></span>
                </div>
            </div>
        </div></div>';
  }

}
