(function ($) {
  'use strict';

  function copyTextToClipboard(text) {
    event.preventDefault();
    let textArea = document.createElement("textarea");
    textArea.style.position = "fixed";
    textArea.style.padding = textArea.style.left = textArea.style.top = '0';
    textArea.style.height = textArea.style.width = "2em";
    textArea.style.boxShadow = textArea.style.outline = textArea.style.border = "none";
    textArea.style.background = "transparent";

    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();

    try {
      var successful = document.execCommand('copy');
        // var msg = successful ? ' successful' : ' unsuccessful';
        // notify('info','Copy to clipboard: ' + $(parentTarget).text() + msg, 'info');
        /* target.classList.remove("icon-shareable") */;
      document.removeChild(textArea);
    } catch (err) { }
  }

  function copyToClipboard(event) {
    let target = event.currentTarget;
    try {
      copyTextToClipboard(event.currentTarget.parentNode.innerText);
      target.classList.remove("icon-copy");
    } catch (err) { }
  }

  window.copyToClipboard = copyToClipboard;

  Vue.component('highlight', {
    props: {
      text: ''
    },
    computed: {
      formatedText: function () {
        var code = this.text.match(/\b(?:\d*\-)?[\d\-]{4,8}\b/g);
        var text = this.text
        if (code) {
          for (var n = 0; n < code.length; n++) {
            text = text.replace(code[n], '<span class="copyclipboard"><span class="highlight" >' + code[n] + '</span><a id="icon-copy" class="on-icon icon-copy" title="Copy" onclick="window.copyToClipboard(event)"></a> </span> ');
          }
        }
        return text;
      }
    },
    template: '<span v-html="formatedText"></span>'
  });


  Vue.component('switch-button', {
    // camelCase in JavaScript
    props: {
      value: {
        type: Boolean,
        default: false,
        toWay: true
      },
      color_off: {
        type: String,
        default: '#ddd'
      },
      color_on: {
        type: String,
        default: '#39bbf9'
      },
      margin_top: {
        type: String,
        default: '3'
      },
      text: ''
    },
    computed: {
      color: function () {
        return this.value ? this.color_on : this.color_off;
      }
    },
    methods: {
      change: function () {
        this.$emit('input', !this.value)
      }
    },
    template: '<span id="switcher" ><label class="switch_label" > <span v-html="text"></span> </label> ' +
      '<div class="switch" :style=" { \'background-color\': color, \'margin-top\': margin_top+\'px\' } " :class="{open :value}" @click="change" >' +
      '<div class="switch__btn" ></div></div></span>'
  });

  Vue.component('paginate', {
    props: {
      total_page: {
        type: Number,
        default: 0
      },
      value: {
        type: Number,
        default: 1
      },
      offset: {
        type: Number,
        default: 1
      }
    },
    computed: {
      pages: function () {

        if (this.total_page == 0) {
          return [];
        }

        var from = this.value - this.offset;
        if (from < 1) {
          from = 1;
        }

        var to = from + (this.offset * 2);
        if (to >= this.total_page) {
          to = this.total_page;
        }

        var arr = [];
        while (from <= to) {
          arr.push(from);
          from++;
        }
        return arr;
      }
    },
    methods: {
      changePage: function (page, $event) {
        $event.preventDefault();
        this.$emit('input', page);
      }
    },
    template: '<ul class="free-numbers__pagination-block">' +
      '<li>' +
      '<a href="#" :disabled="value > 1" aria-label="Previous" @click="changePage(1, $event)">' +
      '<span aria-hidden="true">&laquo;</span>' +
      '</a>' +
      '</li>' +
      '<li v-for="num in pages" :key="num">' +
      '<a href="#" @click.prevent="changePage(num, $event)" :class="{active: num === value}">{{ num }}</a>' +
      '</li>' +

      '<li>' +
      '<a href="#" :disabled="value === total_page" aria-label="Next" @click="changePage(total_page, $event)"><span aria-hidden="true">»</span></a>' +
      '</li>' +
      '</ul>'
  });


  document.addEventListener('DOMContentLoaded', function () {
    new Vue({
      el: '#on',
      data: {
        numbersList: [],
        messageList: [],
        selectNumbers: [],
        selectpage: 1,
        totalpages: 0,
        hideInfo: false,
        instance: false,
        infoblock: false,
        country: null,
        countries: {}

      },
      computed: {
        lang() {
          return document.documentElement.lang.split('-')[0]
        },
        link() {
          let refEl = document.getElementById('qwertasdfgh');
          if (!refEl) {
            console.error('No ref!');
            return 'https://onlinesim.ru';
          }
          return 'https://onlinesim.ru?ref=' + document.getElementById('qwertasdfgh').innerText
        }
      },
      watch: {
        selectNumbers: function (val) {
          this.loadMessageList();
        },
        selectpage: function (val) {
          this.loadMessageList();
        },
        hideInfo: function (val) {
          localStorage.setItem('showInfoIndex', val ? 1 : 0);
        },
        country: function (val) {
          localStorage.setItem('countrySelect', val);
          this.loadPhoneList();
        },
      },
      methods: {
        phonemask: function (value, country) {
          value = value.toString();
          var re = new RegExp("^([0-9]{3})([0-9]{3})([0-9]{1,9})$");
          return "+" + country + " " + value.replace(re, '($1) $2-$3');
        },
        refresh: function () {
          if ($active === true) {
            //this.loadRepeatDeferrOperationSettings();
          }
          console.log('refresh: ' + $active);
          setTimeout(this.refresh, 20000);
        },
        loadCountryList: function () {
          var self = this;
          // getFreeCountryList
          axios.get('https://onlinesim.ru/api/getFreeCountryList?lang=' + this.lang).then(function (response) {
            if (response.data.response == 1) {
              self.countries = response.data.countries
            }
          }).catch(function (error) {
            console.log(error);
            // notify('error', response['ERROR_REFRESH'], 'danger');
          });
        },
        loadPhoneList: function () {
          this.selectpage = 1;
          var self = this;
          this.refreshImage('#refreshPhoneList', true);
          axios.get('https://onlinesim.ru/api/getFreePhoneList?country=' + self.country + '&lang=' + this.lang).then(function (response) {
            if (response.data.response == 1) {
              if (response.data.numbers.length > 0) {
                self.numbersList = response.data.numbers;
                self.selectNumbers = self.numbersList[0];
              } else {
                self.numbersList = [];
                self.messageList = [];
                self.selectNumbers = [];
              }
              //this.loadMessageList();
            }
            self.refreshImage('#refreshPhoneList', false);
          }).catch(function (error) {
            console.log(error);
            // notify('error', response['ERROR_REFRESH'], 'danger');
          });
        },
        loadIgnoreList: function () {
          var self = this;
          axios.get('https://onlinesim.ru/api/getIgnoreList?lang=' + this.lang).then(function (response) {
            self.infoblock = response.data.text;
          }).catch(function (error) {
            console.log(error);
            // notify('error', response['ERROR_REFRESH'], 'danger');
          });
        },
        loadMessageList: function () {
          var self = this;
          this.refreshImage('#refreshMessageList', true);
          let url = "https://onlinesim.ru/api/getFreeMessageList?page=" + this.selectpage + "&phone=" + this.selectNumbers.number + '&lang=' + this.lang;
          axios.get(url).then(function (response) {
            if (response.data.response == 1) {
              self.messageList = response.data.messages.data;
              self.totalpages = response.data.messages.last_page;
            }
            self.refreshImage('#refreshMessageList', false);
          }).catch(function (error) {
            console.log(error);
            // notify('error', response['ERROR_REFRESH'], 'danger');
          });
        },
        refreshImage: function ($id, $enable) {
          var elements = document.querySelectorAll($id);
          elements.forEach(function (element) {
            // var atts = JSON.parse( element.getAttribute('data-pk-atts') );
            // Do something with this element and its `atts`
          });

          // var block = $($id);
          // if ($enable) {
          //     $(block).block({
          //         message: '<i class="icon-spinner2 spinner"></i>',
          //         overlayCSS: {
          //             backgroundColor: '#fff',
          //             opacity: 0.8,
          //             cursor: 'wait',
          //             'box-shadow': '0 0 0 1px #ddd'
          //         },
          //         css: {
          //             border: 0,
          //             padding: 0,
          //             backgroundColor: 'none'
          //         }
          //     });
          // } else {
          //     $(block).unblock();
          // }

        }
      },
      mounted: function () {
        var showInfoIndex = parseInt(localStorage.getItem('showInfoIndex'));
        if (showInfoIndex) this.hideInfo = showInfoIndex === 1;

        this.loadCountryList();

        this.country = parseInt(localStorage.getItem('countrySelect') || 7);
        //this.refresh();

      }
    });

  });
})(jQuery);
