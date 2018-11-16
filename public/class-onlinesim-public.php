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

  private function getCustomCss()
  {
    ob_start();

    $mutedColor = get_theme_mod('muted_color', '');
    if (!empty($mutedColor)) {
      ?>
      .free-numbers .muted {
        color: <?php echo $mutedColor; ?>;
      }
      <?php

    }

    $primary_color = get_theme_mod('primary_color', '');
    if (!empty($primary_color)) {
      ?>
      .free-numbers__countries-country .active {
        background-color: <?php echo $primary_color; ?>;
        border: 1px solid <?php echo $primary_color; ?>;
      }

      .free-numbers__list i {
        color: <?php echo $primary_color; ?>;
      }

      .free-numbers__list ul {
        border-top: 4px solid <?php echo $primary_color; ?>;
      }

      .free-numbers__list ul li > a.active {
        color: <?php echo $primary_color; ?>;
      }

      .free-numbers__pagination-block > li > a.active {
        background-color: <?php echo $primary_color; ?>;
      }
      <?php

    }

    $borderColor = get_theme_mod('border_color', '');
    if (!empty($borderColor)) {
      ?>
      .free-numbers__countries-country button {
        border-color: <?php echo $borderColor; ?>
      }
      .free-numbers__list ul li {
        border: 1px solid <?php echo $borderColor; ?>;
      }
      <?php

    }

    $hoverColor = get_theme_mod('hover_color', '');
    if (!empty($hoverColor)) {
      ?>
      .free-numbers__countries-country button:hover {
        background-color: <?php echo $hoverColor; ?>;
      }
      .free-numbers__list ul li > a.active {
        background-color: <?php echo $hoverColor; ?>;
      }
      <?php

    }



    $css = ob_get_clean();
    return $css;
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
      wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/onlinesim-public.css');
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
    wp_register_style('onlinesim-inline-style', false, array($this->plugin_name));
    wp_enqueue_style('onlinesim-inline-style');
    $custom_css = $this->getCustomCss();
    wp_add_inline_style('onlinesim-inline-style', $custom_css);


    return ' <div id="on"><div class="free-numbers">
    <div class="free-numbers__countries">
      <div class="free-numbers__countries-country" v-for="item in countries" :key="item.country">
        <button :class="{active: item.country === country}" @click="country = item.country">
          <img :src="\'https://onlinesim.ru/assets/images/flags/\' + item.country + \'.png\'" class="flag"> 
          <span v-text="item.country_text"></span>      
        </button>
      </div>
    </div>
    <div class="free-numbers__numbers-block">
      <div class="free-numbers__numbers-block__title">
        <h3 class="muted">Change number</h3>
        <p class="muted">
          <a href="#" @click.prevent="loadPhoneList()"><i class="icon-arrows-cw"></i></a>
        </p>
      </div>
      <div class="free-numbers__list">
        <ul v-if="numbersList.length > 0">
          <li v-for="(date, id) in numbersList" :key="id">
            <a href="" @click.prevent="selectNumbers = date" :class="{active: date.number === selectNumbers.number}">
              <i class="on-icon icon-phone"></i>
              <span v-text="phonemask(date.number, date.country)" onclick="copyToClipboard(event)"></span>
            </a>
          </li>
        </ul>
        <ul v-if="numbersList.length === 0">
          <li>
            <a href="">No numbers</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="free-numbers__messages-block">
      <div class="free-numbers__messages-block__title">
        <h3 class="muted">All Messages</h3>
        <p class="muted">
          Replace number - <span v-text="selectNumbers.data_humans"></span>
          <a href="" @click.prevent="loadMessageList()">
            <i class="icon-arrows-cw"></i>
          </a>
        </p>
      </div>
      <div class="free-numbers__list">
        <ul v-if="messageList.length > 0">
          <li v-for="(message, id) in messageList" :key="id">
            <div>
              <i class="icon-comment-alt" :class="{muted: message.in_number === \'notify\'}"></i>
            </div>
            <div>
              <h4>
                <span :class="{muted: message.in_number === \'notify\'}" v-text="message.in_number"></span>
                <span class="dotted" v-text="message.data_humans"></span>
              </h4>
              <highlight :text="message.text" v-if="message.in_number !== \'notify\'"></highlight>
              <span v-else class="muted">SMS with this service will not be accepted</span>
            </div>
          </li>
        </ul>
        <ul v-else>
          <li style="justify-content: center;">
            <a href="">No messages</a>
          </li>
        </ul>
      </div>
      <div class="free-numbers__pagination">
        <paginate v-if="messageList.length > 0" v-model="selectpage" :total_page="totalpages" :offset="4"></paginate>
      </div>
    </div>
  </div>
  </div>';
  }

}
