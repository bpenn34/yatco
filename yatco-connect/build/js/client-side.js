"use strict";

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _typeof3(obj) { "@babel/helpers - typeof"; return _typeof3 = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof3(obj); }

var _YCT = function () {
  //private properties:
  var _debug = false;
  var _properties = {
    apiEP: _debug ? 'http://localhost:53606/api/v1/tracking' : 'https://analytics.yatco.com/api/v1/tracking'
  };
  var _endPoints = {
    sid: '/sid',
    //site id
    pv: '/pv',
    //page view
    pve: '/pve',
    //page view end
    fs: '/fs',
    //form submit
    vi: '/vi',
    //vessel impressions
    vv: '/vv',
    //vessel view
    ci: '/ci',
    //charter impressions
    cv: '/cv',
    //charter view
    fgv: '/fgv',
    //forsale gallery view
    fvp: '/fvp' //forsale vessel phone

  }; //private methods:

  var _helpers = {
    url: function url(ep, qsObj) {
      qsObj = qsObj || {};
      var qs = [];

      for (var key in qsObj) {
        qs.push(key + '=' + encodeURIComponent(qsObj[key]));
      }

      return _properties.apiEP + ep + '?' + qs.join('&');
    },
    get: function get(uri, callbackFunction) {
      callbackFunction = callbackFunction || function () {};

      var img = new Image();
      img.addEventListener('load', callbackFunction);
      img.src = uri;
    },
    pvObj: function pvObj() {
      return {
        referrer: document.referrer,
        host: window.location.protocol + '//' + window.location.host,
        path: window.location.pathname,
        title: document.title,
        res: window.screen.width + 'x' + window.screen.height,
        lang: window.navigator ? window.navigator.language : "unavailable",
        ua: window.navigator ? window.navigator.userAgent : "unavailable"
      };
    },
    argtolist: function argtolist(l) {
      var argList = l;
      var idList = [];

      if (argList.length > 1) {
        for (var i = 0; i < argList.length; i++) {
          idList.push(argList[i]);
        }
      } else if (argList.length == 1) {
        idList = argList[0].split(',');
      } //console.log(idList);


      return idList;
    }
  }; //Request Site ID from _Init:

  function _getCookie() {
    var host = window.location.host;
    if (host.indexOf('www.') == 0) host = host.replace('www.', '');

    _helpers.get(_helpers.url(_endPoints.sid, {
      h: host
    }), function () {
      _log.pv();
    });
  } //log functions:


  var _log = {
    pv: function pv() {
      _helpers.get(_helpers.url(_endPoints.pv, _helpers.pvObj()));
    },
    pve: function pve() {
      _helpers.get(_helpers.url(_endPoints.pve));
    } //keep this, but log the time on page on the pageview table instead

  };

  function _Init() {
    _getCookie();

    window.addEventListener('beforeunload', _log.pve);
  }

  _Init();

  function fs(eml, extraData) {
    _helpers.get(_helpers.url(_endPoints.fs, {
      email: eml,
      extraData: extraData
    }));
  }

  function vi() {
    var idList = _helpers.argtolist(arguments);

    _helpers.get(_helpers.url(_endPoints.vi, {
      v: idList
    }));
  }

  function vv(vid) {
    _helpers.get(_helpers.url(_endPoints.vv, {
      v: vid
    }));
  }

  function ci() {
    var idList = _helpers.argtolist(arguments);

    _helpers.get(_helpers.url(_endPoints.ci, {
      v: idList
    }));
  }

  function cv(vid) {
    _helpers.get(_helpers.url(_endPoints.cv, {
      v: vid
    }));
  }

  function fgv(vid) {
    _helpers.get(_helpers.url(_endPoints.fgv, {
      v: vid
    }));
  }

  function fvp(vid) {
    _helpers.get(_helpers.url(_endPoints.fvp, {
      v: vid
    }));
  } //expose any functions publicly:


  return {
    formSubmit: fs,
    forsale: {
      impressions: vi,
      view: vv,
      gallery: fgv,
      phone: fvp
    },
    charter: {
      impressions: ci,
      view: cv
    }
  };
}();
/*
    A simple jQuery modal (http://github.com/kylefox/jquery-modal)
    Version 0.9.2
*/


(function (factory) {
  // Making your jQuery plugin work better with npm tools
  // http://blog.npmjs.org/post/112712169830/making-your-jquery-plugin-work-better-with-npm
  if ((typeof module === "undefined" ? "undefined" : _typeof3(module)) === "object" && _typeof3(module.exports) === "object") {
    factory(require("jquery"), window, document);
  } else {
    factory(jQuery, window, document);
  }
})(function ($, window, document, undefined) {
  var modals = [],
      getCurrent = function getCurrent() {
    return modals.length ? modals[modals.length - 1] : null;
  },
      selectCurrent = function selectCurrent() {
    var i,
        selected = false;

    for (i = modals.length - 1; i >= 0; i--) {
      if (modals[i].$blocker) {
        modals[i].$blocker.toggleClass('current', !selected).toggleClass('behind', selected);
        selected = true;
      }
    }
  };

  $.yatco_modal = function (el, options) {
    var remove, target;
    this.$body = $('body');
    this.options = $.extend({}, $.yatco_modal.defaults, options);
    this.options.doFade = !isNaN(parseInt(this.options.fadeDuration, 10));
    this.$blocker = null;
    if (this.options.closeExisting) while ($.yatco_modal.isActive()) {
      $.yatco_modal.close();
    } // Close any open modals.

    modals.push(this);

    if (el.is('a')) {
      target = el.attr('href');
      this.anchor = el; //Select element by id from href

      if (/^#/.test(target)) {
        this.$elm = $(target);
        if (this.$elm.length !== 1) return null;
        this.$body.append(this.$elm);
        this.open(); //AJAX
      } else {
        this.$elm = $('<div>');
        this.$body.append(this.$elm);

        remove = function remove(event, modal) {
          modal.elm.remove();
        };

        this.showSpinner();
        el.trigger($.yatco_modal.AJAX_SEND);
        $.get(target).done(function (html) {
          if (!$.yatco_modal.isActive()) return;
          el.trigger($.yatco_modal.AJAX_SUCCESS);
          var current = getCurrent();
          current.$elm.empty().append(html).on($.yatco_modal.CLOSE, remove);
          current.hideSpinner();
          current.open();
          el.trigger($.yatco_modal.AJAX_COMPLETE);
        }).fail(function () {
          el.trigger($.yatco_modal.AJAX_FAIL);
          var current = getCurrent();
          current.hideSpinner();
          modals.pop(); // remove expected modal from the list

          el.trigger($.yatco_modal.AJAX_COMPLETE);
        });
      }
    } else {
      this.$elm = el;
      this.anchor = el;
      this.$body.append(this.$elm);
      this.open();
    }
  };

  $.yatco_modal.prototype = {
    constructor: $.yatco_modal,
    open: function open() {
      var m = this;
      this.block();
      this.anchor.blur();

      if (this.options.doFade) {
        setTimeout(function () {
          m.show();
        }, this.options.fadeDuration * this.options.fadeDelay);
      } else {
        this.show();
      }

      $(document).off('keydown.modal').on('keydown.modal', function (event) {
        var current = getCurrent();
        if (event.which === 27 && current.options.escapeClose) current.close();
      });
      if (this.options.clickClose) this.$blocker.click(function (e) {
        if (e.target === this) $.yatco_modal.close();
      });
    },
    close: function close() {
      modals.pop();
      this.unblock();
      this.hide();
      if (!$.yatco_modal.isActive()) $(document).off('keydown.modal');
    },
    block: function block() {
      this.$elm.trigger($.yatco_modal.BEFORE_BLOCK, [this._ctx()]);
      this.$body.css('overflow', 'hidden');
      this.$blocker = $('<div class="' + this.options.blockerClass + ' blocker current"></div>').appendTo(this.$body);
      selectCurrent();

      if (this.options.doFade) {
        this.$blocker.css('opacity', 0).animate({
          opacity: 1
        }, this.options.fadeDuration);
      }

      this.$elm.trigger($.yatco_modal.BLOCK, [this._ctx()]);
    },
    unblock: function unblock(now) {
      if (!now && this.options.doFade) this.$blocker.fadeOut(this.options.fadeDuration, this.unblock.bind(this, true));else {
        this.$blocker.children().appendTo(this.$body);
        this.$blocker.remove();
        this.$blocker = null;
        selectCurrent();
        if (!$.yatco_modal.isActive()) this.$body.css('overflow', '');
      }
    },
    show: function show() {
      this.$elm.trigger($.yatco_modal.BEFORE_OPEN, [this._ctx()]);

      if (this.options.showClose) {
        this.closeButton = $('<a href="#close-modal" rel="modal:close" class="close-modal ' + this.options.closeClass + '">' + this.options.closeText + '</a>');
        this.$elm.append(this.closeButton);
      }

      this.$elm.addClass(this.options.modalClass).appendTo(this.$blocker);

      if (this.options.doFade) {
        this.$elm.css({
          opacity: 0,
          display: 'inline-block'
        }).animate({
          opacity: 1
        }, this.options.fadeDuration);
      } else {
        this.$elm.css('display', 'inline-block');
      }

      this.$elm.trigger($.yatco_modal.OPEN, [this._ctx()]);
    },
    hide: function hide() {
      this.$elm.trigger($.yatco_modal.BEFORE_CLOSE, [this._ctx()]);
      if (this.closeButton) this.closeButton.remove();

      var _this = this;

      if (this.options.doFade) {
        this.$elm.fadeOut(this.options.fadeDuration, function () {
          _this.$elm.trigger($.yatco_modal.AFTER_CLOSE, [_this._ctx()]);
        });
      } else {
        this.$elm.hide(0, function () {
          _this.$elm.trigger($.yatco_modal.AFTER_CLOSE, [_this._ctx()]);
        });
      }

      this.$elm.trigger($.yatco_modal.CLOSE, [this._ctx()]);
    },
    showSpinner: function showSpinner() {
      if (!this.options.showSpinner) return;
      this.spinner = this.spinner || $('<div class="' + this.options.modalClass + '-spinner"></div>').append(this.options.spinnerHtml);
      this.$body.append(this.spinner);
      this.spinner.show();
    },
    hideSpinner: function hideSpinner() {
      if (this.spinner) this.spinner.remove();
    },
    //Return context for custom events
    _ctx: function _ctx() {
      return {
        elm: this.$elm,
        $elm: this.$elm,
        $blocker: this.$blocker,
        options: this.options,
        $anchor: this.anchor
      };
    }
  };

  $.yatco_modal.close = function (event) {
    if (!$.yatco_modal.isActive()) return;
    if (event) event.preventDefault();
    var current = getCurrent();
    current.close();
    return current.$elm;
  }; // Returns if there currently is an active modal


  $.yatco_modal.isActive = function () {
    return modals.length > 0;
  };

  $.yatco_modal.getCurrent = getCurrent;
  $.yatco_modal.defaults = {
    closeExisting: true,
    escapeClose: true,
    clickClose: true,
    closeText: 'Close',
    closeClass: '',
    modalClass: "yt-modal",
    blockerClass: "jquery-modal",
    spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    showSpinner: true,
    showClose: true,
    fadeDuration: null,
    // Number of milliseconds the fade animation takes.
    fadeDelay: 1.0 // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)

  }; // Event constants

  $.yatco_modal.BEFORE_BLOCK = 'modal:before-block';
  $.yatco_modal.BLOCK = 'modal:block';
  $.yatco_modal.BEFORE_OPEN = 'modal:before-open';
  $.yatco_modal.OPEN = 'modal:open';
  $.yatco_modal.BEFORE_CLOSE = 'modal:before-close';
  $.yatco_modal.CLOSE = 'modal:close';
  $.yatco_modal.AFTER_CLOSE = 'modal:after-close';
  $.yatco_modal.AJAX_SEND = 'modal:ajax:send';
  $.yatco_modal.AJAX_SUCCESS = 'modal:ajax:success';
  $.yatco_modal.AJAX_FAIL = 'modal:ajax:fail';
  $.yatco_modal.AJAX_COMPLETE = 'modal:ajax:complete';

  $.fn.yatco_modal = function (options) {
    if (this.length === 1) {
      new $.yatco_modal(this, options);
    }

    return this;
  }; // Automatically bind links with rel="modal:close" to, well, close the modal.


  $(document).on('click.modal', 'a[rel~="modal:close"]', $.yatco_modal.close);
  $(document).on('click.modal', 'a[rel~="modal:open"]', function (event) {
    event.preventDefault();
    $(this).modal();
  });
});
/**
* simplePagination.js v1.6
* A simple jQuery pagination plugin.
* http://flaviusmatis.github.com/simplePagination.js/
*
* Copyright 2012, Flavius Matis
* Released under the MIT license.
* http://flaviusmatis.github.com/license.html
*/


(function ($) {
  var methods = {
    init: function init(options) {
      var o = $.extend({
        items: 1,
        itemsOnPage: 1,
        pages: 0,
        displayedPages: 5,
        edges: 2,
        currentPage: 0,
        useAnchors: true,
        hrefTextPrefix: '#page-',
        hrefTextSuffix: '',
        prevText: 'Prev',
        nextText: 'Next',
        ellipseText: '&hellip;',
        ellipsePageSet: true,
        cssStyle: 'light-theme',
        listStyle: '',
        labelMap: [],
        selectOnClick: true,
        nextAtFront: false,
        invertPageOrder: false,
        useStartEdge: true,
        useEndEdge: true,
        onPageClick: function onPageClick(pageNumber, event) {// Callback triggered when a page is clicked
          // Page number is given as an optional parameter
        },
        onInit: function onInit() {// Callback triggered immediately after initialization
        }
      }, options || {});
      var self = this;
      o.pages = o.pages ? o.pages : Math.ceil(o.items / o.itemsOnPage) ? Math.ceil(o.items / o.itemsOnPage) : 1;
      if (o.currentPage) o.currentPage = o.currentPage - 1;else o.currentPage = !o.invertPageOrder ? 0 : o.pages - 1;
      o.halfDisplayed = o.displayedPages / 2;
      this.each(function () {
        self.addClass(o.cssStyle + ' simple-pagination').data('pagination', o);

        methods._draw.call(self);
      });
      o.onInit();
      return this;
    },
    selectPage: function selectPage(page) {
      methods._selectPage.call(this, page - 1);

      return this;
    },
    prevPage: function prevPage() {
      var o = this.data('pagination');

      if (!o.invertPageOrder) {
        if (o.currentPage > 0) {
          methods._selectPage.call(this, o.currentPage - 1);
        }
      } else {
        if (o.currentPage < o.pages - 1) {
          methods._selectPage.call(this, o.currentPage + 1);
        }
      }

      return this;
    },
    nextPage: function nextPage() {
      var o = this.data('pagination');

      if (!o.invertPageOrder) {
        if (o.currentPage < o.pages - 1) {
          methods._selectPage.call(this, o.currentPage + 1);
        }
      } else {
        if (o.currentPage > 0) {
          methods._selectPage.call(this, o.currentPage - 1);
        }
      }

      return this;
    },
    getPagesCount: function getPagesCount() {
      return this.data('pagination').pages;
    },
    setPagesCount: function setPagesCount(count) {
      this.data('pagination').pages = count;
    },
    getCurrentPage: function getCurrentPage() {
      return this.data('pagination').currentPage + 1;
    },
    destroy: function destroy() {
      this.empty();
      return this;
    },
    drawPage: function drawPage(page) {
      var o = this.data('pagination');
      o.currentPage = page - 1;
      this.data('pagination', o);

      methods._draw.call(this);

      return this;
    },
    redraw: function redraw() {
      methods._draw.call(this);

      return this;
    },
    disable: function disable() {
      var o = this.data('pagination');
      o.disabled = true;
      this.data('pagination', o);

      methods._draw.call(this);

      return this;
    },
    enable: function enable() {
      var o = this.data('pagination');
      o.disabled = false;
      this.data('pagination', o);

      methods._draw.call(this);

      return this;
    },
    updateItems: function updateItems(newItems) {
      var o = this.data('pagination');
      o.items = newItems;
      o.pages = methods._getPages(o);
      this.data('pagination', o);

      methods._draw.call(this);
    },
    updateItemsOnPage: function updateItemsOnPage(itemsOnPage) {
      var o = this.data('pagination');
      o.itemsOnPage = itemsOnPage;
      o.pages = methods._getPages(o);
      this.data('pagination', o);

      methods._selectPage.call(this, 0);

      return this;
    },
    getItemsOnPage: function getItemsOnPage() {
      return this.data('pagination').itemsOnPage;
    },
    _draw: function _draw() {
      var o = this.data('pagination'),
          interval = methods._getInterval(o),
          i,
          tagName;

      methods.destroy.call(this);
      tagName = typeof this.prop === 'function' ? this.prop('tagName') : this.attr('tagName');
      var $panel = tagName === 'UL' ? this : $('<ul' + (o.listStyle ? ' class="' + o.listStyle + '"' : '') + '></ul>').appendTo(this); // Generate Prev link

      if (o.prevText) {
        methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage - 1 : o.currentPage + 1, {
          text: o.prevText,
          classes: 'prev'
        });
      } // Generate Next link (if option set for at front)


      if (o.nextText && o.nextAtFront) {
        methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage + 1 : o.currentPage - 1, {
          text: o.nextText,
          classes: 'next'
        });
      } // Generate start edges


      if (!o.invertPageOrder) {
        if (interval.start > 0 && o.edges > 0) {
          if (o.useStartEdge) {
            var end = Math.min(o.edges, interval.start);

            for (i = 0; i < end; i++) {
              methods._appendItem.call(this, i);
            }
          }

          if (o.edges < interval.start && interval.start - o.edges != 1) {
            $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
          } else if (interval.start - o.edges == 1) {
            methods._appendItem.call(this, o.edges);
          }
        }
      } else {
        if (interval.end < o.pages && o.edges > 0) {
          if (o.useStartEdge) {
            var begin = Math.max(o.pages - o.edges, interval.end);

            for (i = o.pages - 1; i >= begin; i--) {
              methods._appendItem.call(this, i);
            }
          }

          if (o.pages - o.edges > interval.end && o.pages - o.edges - interval.end != 1) {
            $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
          } else if (o.pages - o.edges - interval.end == 1) {
            methods._appendItem.call(this, interval.end);
          }
        }
      } // Generate interval links


      if (!o.invertPageOrder) {
        for (i = interval.start; i < interval.end; i++) {
          methods._appendItem.call(this, i);
        }
      } else {
        for (i = interval.end - 1; i >= interval.start; i--) {
          methods._appendItem.call(this, i);
        }
      } // Generate end edges


      if (!o.invertPageOrder) {
        if (interval.end < o.pages && o.edges > 0) {
          if (o.pages - o.edges > interval.end && o.pages - o.edges - interval.end != 1) {
            $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
          } else if (o.pages - o.edges - interval.end == 1) {
            methods._appendItem.call(this, interval.end);
          }

          if (o.useEndEdge) {
            var begin = Math.max(o.pages - o.edges, interval.end);

            for (i = begin; i < o.pages; i++) {
              methods._appendItem.call(this, i);
            }
          }
        }
      } else {
        if (interval.start > 0 && o.edges > 0) {
          if (o.edges < interval.start && interval.start - o.edges != 1) {
            $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
          } else if (interval.start - o.edges == 1) {
            methods._appendItem.call(this, o.edges);
          }

          if (o.useEndEdge) {
            var end = Math.min(o.edges, interval.start);

            for (i = end - 1; i >= 0; i--) {
              methods._appendItem.call(this, i);
            }
          }
        }
      } // Generate Next link (unless option is set for at front)


      if (o.nextText && !o.nextAtFront) {
        methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage + 1 : o.currentPage - 1, {
          text: o.nextText,
          classes: 'next'
        });
      }

      if (o.ellipsePageSet && !o.disabled) {
        methods._ellipseClick.call(this, $panel);
      }
    },
    _getPages: function _getPages(o) {
      var pages = Math.ceil(o.items / o.itemsOnPage);
      return pages || 1;
    },
    _getInterval: function _getInterval(o) {
      return {
        start: Math.ceil(o.currentPage > o.halfDisplayed ? Math.max(Math.min(o.currentPage - o.halfDisplayed, o.pages - o.displayedPages), 0) : 0),
        end: Math.ceil(o.currentPage > o.halfDisplayed ? Math.min(o.currentPage + o.halfDisplayed, o.pages) : Math.min(o.displayedPages, o.pages))
      };
    },
    _appendItem: function _appendItem(pageIndex, opts) {
      var self = this,
          options,
          $link,
          o = self.data('pagination'),
          $linkWrapper = $('<li></li>'),
          $ul = self.find('ul');
      pageIndex = pageIndex < 0 ? 0 : pageIndex < o.pages ? pageIndex : o.pages - 1;
      options = {
        text: pageIndex + 1,
        classes: ''
      };

      if (o.labelMap.length && o.labelMap[pageIndex]) {
        options.text = o.labelMap[pageIndex];
      }

      options = $.extend(options, opts || {});

      if (pageIndex == o.currentPage || o.disabled) {
        if (o.disabled || options.classes === 'prev' || options.classes === 'next') {
          $linkWrapper.addClass('disabled');
        } else {
          $linkWrapper.addClass('active');
        }

        $link = $('<span class="current">' + options.text + '</span>');
      } else {
        if (o.useAnchors) {
          $link = $('<a href="' + o.hrefTextPrefix + (pageIndex + 1) + o.hrefTextSuffix + '" class="page-link">' + options.text + '</a>');
        } else {
          $link = $('<span >' + options.text + '</span>');
        }

        $link.click(function (event) {
          return methods._selectPage.call(self, pageIndex, event);
        });
      }

      if (options.classes) {
        $link.addClass(options.classes);
      }

      $linkWrapper.append($link);

      if ($ul.length) {
        $ul.append($linkWrapper);
      } else {
        self.append($linkWrapper);
      }
    },
    _selectPage: function _selectPage(pageIndex, event) {
      var o = this.data('pagination');
      o.currentPage = pageIndex;

      if (o.selectOnClick) {
        methods._draw.call(this);
      }

      return o.onPageClick(pageIndex + 1, event);
    },
    _ellipseClick: function _ellipseClick($panel) {
      var self = this,
          o = this.data('pagination'),
          $ellip = $panel.find('.ellipse');
      $ellip.addClass('clickable').parent().removeClass('disabled');
      $ellip.click(function (event) {
        if (!o.disable) {
          var $this = $(this),
              val = (parseInt($this.parent().prev().text(), 10) || 0) + 1;
          $this.html('<input type="number" min="1" max="' + o.pages + '" step="1" value="' + val + '">').find('input').focus().click(function (event) {
            // prevent input number arrows from bubbling a click event on $ellip
            event.stopPropagation();
          }).keyup(function (event) {
            var val = $(this).val();

            if (event.which === 13 && val !== '') {
              // enter to accept
              if (val > 0 && val <= o.pages) methods._selectPage.call(self, val - 1);
            } else if (event.which === 27) {
              // escape to cancel
              $ellip.empty().html(o.ellipseText);
            }
          }).bind('blur', function (event) {
            var val = $(this).val();

            if (val !== '') {
              methods._selectPage.call(self, val - 1);
            }

            $ellip.empty().html(o.ellipseText);
            return false;
          });
        }

        return false;
      });
    }
  };

  $.fn.pagination = function (method) {
    // Method calling logic
    if (methods[method] && method.charAt(0) != '_') {
      return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (_typeof3(method) === 'object' || !method) {
      return methods.init.apply(this, arguments);
    } else {
      $.error('Method ' + method + ' does not exist on jQuery.pagination');
    }
  };
})(jQuery);
/*!
 *  @preserve
 * 
 * Readmore.js plugin
 * Author: @jed_foster
 * Project home: jedfoster.com/Readmore.js
 * Version: 3.0.0-beta-1
 * Licensed under the MIT license
 * 
 * Debounce function from davidwalsh.name/javascript-debounce-function
 */


(function webpackUniversalModuleDefinition(root, factory) {
  if ((typeof exports === "undefined" ? "undefined" : _typeof3(exports)) === 'object' && (typeof module === "undefined" ? "undefined" : _typeof3(module)) === 'object') module.exports = factory();else if (typeof define === 'function' && define.amd) define("Readmore", [], factory);else if ((typeof exports === "undefined" ? "undefined" : _typeof3(exports)) === 'object') exports["Readmore"] = factory();else root["Readmore"] = factory();
})(window, function () {
  return (
    /******/
    function (modules) {
      // webpackBootstrap

      /******/
      // The module cache

      /******/
      var installedModules = {};
      /******/

      /******/
      // The require function

      /******/

      function __webpack_require__(moduleId) {
        /******/

        /******/
        // Check if module is in cache

        /******/
        if (installedModules[moduleId]) {
          /******/
          return installedModules[moduleId].exports;
          /******/
        }
        /******/
        // Create a new module (and put it into the cache)

        /******/


        var module = installedModules[moduleId] = {
          /******/
          i: moduleId,

          /******/
          l: false,

          /******/
          exports: {}
          /******/

        };
        /******/

        /******/
        // Execute the module function

        /******/

        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/

        /******/
        // Flag the module as loaded

        /******/

        module.l = true;
        /******/

        /******/
        // Return the exports of the module

        /******/

        return module.exports;
        /******/
      }
      /******/

      /******/

      /******/
      // expose the modules object (__webpack_modules__)

      /******/


      __webpack_require__.m = modules;
      /******/

      /******/
      // expose the module cache

      /******/

      __webpack_require__.c = installedModules;
      /******/

      /******/
      // define getter function for harmony exports

      /******/

      __webpack_require__.d = function (exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
          /******/
          Object.defineProperty(exports, name, {
            enumerable: true,
            get: getter
          });
          /******/
        }
        /******/

      };
      /******/

      /******/
      // define __esModule on exports

      /******/


      __webpack_require__.r = function (exports) {
        /******/
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
          /******/
          Object.defineProperty(exports, Symbol.toStringTag, {
            value: 'Module'
          });
          /******/
        }
        /******/


        Object.defineProperty(exports, '__esModule', {
          value: true
        });
        /******/
      };
      /******/

      /******/
      // create a fake namespace object

      /******/
      // mode & 1: value is a module id, require it

      /******/
      // mode & 2: merge all properties of value into the ns

      /******/
      // mode & 4: return value when already ns object

      /******/
      // mode & 8|1: behave like require

      /******/


      __webpack_require__.t = function (value, mode) {
        /******/
        if (mode & 1) value = __webpack_require__(value);
        /******/

        if (mode & 8) return value;
        /******/

        if (mode & 4 && _typeof3(value) === 'object' && value && value.__esModule) return value;
        /******/

        var ns = Object.create(null);
        /******/

        __webpack_require__.r(ns);
        /******/


        Object.defineProperty(ns, 'default', {
          enumerable: true,
          value: value
        });
        /******/

        if (mode & 2 && typeof value != 'string') for (var key in value) {
          __webpack_require__.d(ns, key, function (key) {
            return value[key];
          }.bind(null, key));
        }
        /******/

        return ns;
        /******/
      };
      /******/

      /******/
      // getDefaultExport function for compatibility with non-harmony modules

      /******/


      __webpack_require__.n = function (module) {
        /******/
        var getter = module && module.__esModule ?
        /******/
        function getDefault() {
          return module['default'];
        } :
        /******/
        function getModuleExports() {
          return module;
        };
        /******/

        __webpack_require__.d(getter, 'a', getter);
        /******/


        return getter;
        /******/
      };
      /******/

      /******/
      // Object.prototype.hasOwnProperty.call

      /******/


      __webpack_require__.o = function (object, property) {
        return Object.prototype.hasOwnProperty.call(object, property);
      };
      /******/

      /******/
      // __webpack_public_path__

      /******/


      __webpack_require__.p = "";
      /******/

      /******/

      /******/
      // Load entry module and return exports

      /******/

      return __webpack_require__(__webpack_require__.s = 0);
      /******/
    }
    /************************************************************************/

    /******/
    ({
      /***/
      "./node_modules/@babel/runtime/helpers/classCallCheck.js":
      /*!***************************************************************!*\
        !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
        \***************************************************************/

      /*! no static exports found */

      /***/
      function node_modulesBabelRuntimeHelpersClassCallCheckJs(module, exports) {
        function _classCallCheck(instance, Constructor) {
          if (!(instance instanceof Constructor)) {
            throw new TypeError("Cannot call a class as a function");
          }
        }

        module.exports = _classCallCheck;
        /***/
      },

      /***/
      "./node_modules/@babel/runtime/helpers/createClass.js":
      /*!************************************************************!*\
        !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
        \************************************************************/

      /*! no static exports found */

      /***/
      function node_modulesBabelRuntimeHelpersCreateClassJs(module, exports) {
        function _defineProperties(target, props) {
          for (var i = 0; i < props.length; i++) {
            var descriptor = props[i];
            descriptor.enumerable = descriptor.enumerable || false;
            descriptor.configurable = true;
            if ("value" in descriptor) descriptor.writable = true;
            Object.defineProperty(target, descriptor.key, descriptor);
          }
        }

        function _createClass(Constructor, protoProps, staticProps) {
          if (protoProps) _defineProperties(Constructor.prototype, protoProps);
          if (staticProps) _defineProperties(Constructor, staticProps);
          return Constructor;
        }

        module.exports = _createClass;
        /***/
      },

      /***/
      "./node_modules/@babel/runtime/helpers/typeof.js":
      /*!*******************************************************!*\
        !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
        \*******************************************************/

      /*! no static exports found */

      /***/
      function node_modulesBabelRuntimeHelpersTypeofJs(module, exports) {
        function _typeof2(obj) {
          if (typeof Symbol === "function" && _typeof3(Symbol.iterator) === "symbol") {
            _typeof2 = function _typeof2(obj) {
              return _typeof3(obj);
            };
          } else {
            _typeof2 = function _typeof2(obj) {
              return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : _typeof3(obj);
            };
          }

          return _typeof2(obj);
        }

        function _typeof(obj) {
          if (typeof Symbol === "function" && _typeof2(Symbol.iterator) === "symbol") {
            module.exports = _typeof = function _typeof(obj) {
              return _typeof2(obj);
            };
          } else {
            module.exports = _typeof = function _typeof(obj) {
              return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : _typeof2(obj);
            };
          }

          return _typeof(obj);
        }

        module.exports = _typeof;
        /***/
      },

      /***/
      "./src/readmore.js":
      /*!*************************!*\
        !*** ./src/readmore.js ***!
        \*************************/

      /*! exports provided: default */

      /***/
      function srcReadmoreJs(module, __webpack_exports__, __webpack_require__) {
        "use strict";

        __webpack_require__.r(__webpack_exports__);
        /* harmony import */


        var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(
        /*! @babel/runtime/helpers/classCallCheck */
        "./node_modules/@babel/runtime/helpers/classCallCheck.js");
        /* harmony import */


        var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__);
        /* harmony import */


        var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(
        /*! @babel/runtime/helpers/createClass */
        "./node_modules/@babel/runtime/helpers/createClass.js");
        /* harmony import */


        var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__);
        /* harmony import */


        var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(
        /*! @babel/runtime/helpers/typeof */
        "./node_modules/@babel/runtime/helpers/typeof.js");
        /* harmony import */


        var _babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2__);

        var uniqueIdCounter = 0;
        var isCssEmbeddedFor = []; // from:https://github.com/jserz/js_piece/blob/master/DOM/ChildNode/remove()/remove().md

        (function removePolyfill(arr) {
          arr.forEach(function (item) {
            if (Object.prototype.hasOwnProperty.call(item, 'remove')) {
              return;
            }

            Object.defineProperty(item, 'remove', {
              configurable: true,
              enumerable: true,
              writable: true,
              value: function remove() {
                if (this.parentNode !== null) {
                  this.parentNode.removeChild(this);
                }
              }
            });
          });
        })([Element.prototype, CharacterData.prototype, DocumentType.prototype]);

        function forEach(arr, callback, scope) {
          for (var i = 0; i < arr.length; i += 1) {
            callback.call(scope, arr[i], i);
          }
        }

        function extend() {
          for (var _len = arguments.length, objects = new Array(_len), _key = 0; _key < _len; _key++) {
            objects[_key] = arguments[_key];
          }

          var hasProp = {}.hasOwnProperty;
          var child = objects[0];
          var parent = objects[1];

          if (objects.length > 2) {
            var args = [];
            Object.keys(objects).forEach(function (key) {
              args.push(objects[key]);
            });

            while (args.length > 2) {
              var c1 = args.shift();
              var p1 = args.shift();
              args.unshift(extend(c1, p1));
            }

            child = args.shift();
            parent = args.shift();
          }

          if (parent) {
            Object.keys(parent).forEach(function (key) {
              if (hasProp.call(parent, key)) {
                if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2___default()(parent[key]) === 'object') {
                  child[key] = child[key] || {};
                  child[key] = extend(child[key], parent[key]);
                } else {
                  child[key] = parent[key];
                }
              }
            });
          }

          return child;
        }

        function debounce(func, wait, immediate) {
          var timeout;
          return function debouncedFunc() {
            var _this = this;

            for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
              args[_key2] = arguments[_key2];
            }

            var callNow = immediate && !timeout;

            var later = function later() {
              timeout = null;
              if (!immediate) func.apply(_this, args);
            };

            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(this, args);
          };
        }

        function uniqueId() {
          uniqueIdCounter += 1;
          return "rmjs-".concat(uniqueIdCounter);
        }

        function setBoxHeights(element) {
          element.style.height = 'auto';
          var expandedHeight = parseInt(element.getBoundingClientRect().height, 10);
          var cssMaxHeight = parseInt(window.getComputedStyle(element).maxHeight, 10);
          var defaultHeight = parseInt(element.readmore.defaultHeight, 10); // Store our measurements.

          element.readmore.expandedHeight = expandedHeight;
          element.readmore.maxHeight = cssMaxHeight;
          element.readmore.collapsedHeight = cssMaxHeight || element.readmore.collapsedHeight || defaultHeight;
          element.style.maxHeight = 'none';
        }

        function createElementFromString(htmlString) {
          var div = document.createElement('div');
          div.innerHTML = htmlString;
          return div.firstChild;
        }

        function embedCSS(selector, options) {
          if (!isCssEmbeddedFor[selector]) {
            var styles = '';

            if (options.embedCSS && options.blockCSS !== '') {
              styles += "".concat(selector, " + [data-readmore-toggle], ").concat(selector, "[data-readmore] {\n        ").concat(options.blockCSS, "\n      }");
            } // Include the transition CSS even if embedCSS is false


            styles += "".concat(selector, "[data-readmore] {\n      transition: height ").concat(options.speed, "ms;\n      overflow: hidden;\n    }");

            (function (d, u) {
              var css = d.createElement('style');
              css.type = 'text/css';

              if (css.styleSheet) {
                css.styleSheet.cssText = u;
              } else {
                css.appendChild(d.createTextNode(u));
              }

              d.getElementsByTagName('head')[0].appendChild(css);
            })(document, styles);

            isCssEmbeddedFor[selector] = true;
          }
        }

        function buildToggle(link, element, scope) {
          function clickHandler(event) {
            this.toggle(element, event);
          }

          var text = link;

          if (typeof link === 'function') {
            text = link(element);
          }

          var toggleLink = createElementFromString(text);
          toggleLink.setAttribute('data-readmore-toggle', element.id);
          toggleLink.setAttribute('aria-controls', element.id);
          toggleLink.addEventListener('click', clickHandler.bind(scope));
          return toggleLink;
        }

        function isEnvironmentSupported() {
          return typeof window !== 'undefined' && typeof document !== 'undefined' && !!document.querySelectorAll && !!window.addEventListener;
        }

        var resizeBoxes = debounce(function () {
          var elements = document.querySelectorAll('[data-readmore]');
          forEach(elements, function (element) {
            var expanded = element.getAttribute('aria-expanded') === 'true';
            setBoxHeights(element);
            element.style.height = "".concat(expanded ? element.readmore.expandedHeight : element.readmore.collapsedHeight, "px");
          });
        }, 100);
        var defaults = {
          speed: 100,
          collapsedHeight: 200,
          heightMargin: 16,
          moreLink: '<a href="#">Read More</a>',
          lessLink: '<a href="#">Close</a>',
          embedCSS: true,
          blockCSS: 'display: block; width: 100%;',
          startOpen: false,
          sourceOrder: 'after',
          // callbacks
          blockProcessed: function blockProcessed() {},
          beforeToggle: function beforeToggle() {},
          afterToggle: function afterToggle() {}
        };

        var Readmore = /*#__PURE__*/function () {
          function Readmore() {
            var _this2 = this;

            _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0___default()(this, Readmore);

            if (!isEnvironmentSupported()) return;

            for (var _len3 = arguments.length, args = new Array(_len3), _key3 = 0; _key3 < _len3; _key3++) {
              args[_key3] = arguments[_key3];
            }

            var selector = args[0],
                options = args[1];
            var elements;

            if (typeof selector === 'string') {
              elements = document.querySelectorAll(selector);
            } else if (selector.nodeName) {
              elements = [selector]; // emulate a NodeList by casting a single Node as an array
            } else {
              elements = selector;
            } // After all that, if we _still_ don't have iteratable NodeList, bail out.


            if (!elements.length) return;
            this.options = extend({}, defaults, options);

            if (typeof selector === 'string') {
              embedCSS(selector, this.options);
            } else {
              // Instances need distinct selectors so they don't stomp on each other.
              this.instanceSelector = ".".concat(uniqueId());
              embedCSS(this.instanceSelector, this.options);
            } // Need to resize boxes when the page has fully loaded.


            window.addEventListener('load', resizeBoxes);
            window.addEventListener('resize', resizeBoxes);
            this.elements = [];
            forEach(elements, function (element) {
              if (_this2.instanceSelector) {
                element.classList.add(_this2.instanceSelector.substr(1));
              }

              var expanded = _this2.options.startOpen;
              element.readmore = {
                defaultHeight: _this2.options.collapsedHeight,
                heightMargin: _this2.options.heightMargin
              };
              setBoxHeights(element);
              var heightMargin = element.readmore.heightMargin;

              if (element.getBoundingClientRect().height <= element.readmore.collapsedHeight + heightMargin) {
                if (typeof _this2.options.blockProcessed === 'function') {
                  _this2.options.blockProcessed(element, false);
                }

                return;
              }

              element.setAttribute('data-readmore', '');
              element.setAttribute('aria-expanded', expanded);
              element.id = element.id || uniqueId();
              var toggleLink = expanded ? _this2.options.lessLink : _this2.options.moreLink;
              var toggleElement = buildToggle(toggleLink, element, _this2);
              element.parentNode.insertBefore(toggleElement, _this2.options.sourceOrder === 'before' ? element : element.nextSibling);
              element.style.height = "".concat(expanded ? element.readmore.expandedHeight : element.readmore.collapsedHeight, "px");

              if (typeof _this2.options.blockProcessed === 'function') {
                _this2.options.blockProcessed(element, true);
              }

              _this2.elements.push(element);
            });
          } // Signature when called internally by the toggleLink click handler:
          //   toggle(element, event)
          //
          // When called externally by an instance,
          // e.g. readmoreDemo.toggle(document.querySelector('article:nth-of-type(1)')):
          //   toggle(elementOrQuerySelector)


          _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1___default()(Readmore, [{
            key: "toggle",
            value: function toggle() {
              var _this3 = this;

              var el = arguments.length <= 0 ? undefined : arguments[0];

              var toggleElement = function toggleElement(element) {
                var trigger = document.querySelector("[aria-controls=\"".concat(element.id, "\"]"));
                var expanded = element.getBoundingClientRect().height <= element.readmore.collapsedHeight;
                var newHeight = expanded ? element.readmore.expandedHeight : element.readmore.collapsedHeight; // Fire beforeToggle callback
                // Since we determined the new "expanded" state above we're now out of sync
                // with our true current state, so we need to flip the value of `expanded`

                if (typeof _this3.options.beforeToggle === 'function') {
                  var shouldContinueToggle = _this3.options.beforeToggle(trigger, element, !expanded); // if the beforeToggle callback returns false, stop toggling


                  if (shouldContinueToggle === false) {
                    return;
                  }
                }

                element.style.height = "".concat(newHeight, "px");

                var transitionendHandler = function transitionendHandler(transitionEvent) {
                  // Fire afterToggle callback
                  if (typeof _this3.options.afterToggle === 'function') {
                    _this3.options.afterToggle(trigger, element, expanded);
                  }

                  transitionEvent.stopPropagation();
                  element.setAttribute('aria-expanded', expanded);
                  element.removeEventListener('transitionend', transitionendHandler, false);
                };

                element.addEventListener('transitionend', transitionendHandler, false);

                if (_this3.options.speed < 1) {
                  transitionendHandler.call(_this3, {
                    target: element
                  });
                }

                var toggleLink = expanded ? _this3.options.lessLink : _this3.options.moreLink;

                if (!toggleLink) {
                  trigger.remove();
                } else if (trigger && trigger.parentNode) {
                  trigger.parentNode.replaceChild(buildToggle(toggleLink, element, _this3), trigger);
                }
              };

              if (typeof el === 'string') {
                el = document.querySelectorAll(el);
              }

              if (!el) {
                throw new Error('Element MUST be either an HTML node or querySelector string');
              }

              var event = arguments.length <= 1 ? undefined : arguments[1];

              if (event) {
                event.preventDefault();
                event.stopPropagation();
              }

              if (_babel_runtime_helpers_typeof__WEBPACK_IMPORTED_MODULE_2___default()(el) === 'object' && !el.nodeName) {
                // element is likely a NodeList
                forEach(el, toggleElement);
              } else {
                toggleElement(el);
              }
            }
          }, {
            key: "destroy",
            value: function destroy(selector) {
              var _this4 = this;

              var elements;

              if (!selector) {
                elements = this.elements; // eslint-disable-line
              } else if (typeof selector === 'string') {
                elements = document.querySelectorAll(selector);
              } else if (selector.nodeName) {
                elements = [selector]; // emulate a NodeList by casting a single Node as an array
              } else {
                elements = selector;
              }

              forEach(elements, function (element) {
                if (_this4.elements.indexOf(element) === -1) {
                  return;
                }

                _this4.elements = _this4.elements.filter(function (el) {
                  return el !== element;
                });

                if (_this4.instanceSelector) {
                  element.classList.remove(_this4.instanceSelector.substr(1));
                }

                delete element.readmore;
                element.style.height = 'initial';
                element.style.maxHeight = 'initial';
                element.removeAttribute('data-readmore');
                element.removeAttribute('aria-expanded');
                var trigger = document.querySelector("[aria-controls=\"".concat(element.id, "\"]"));

                if (trigger) {
                  trigger.remove();
                }

                if (element.id.indexOf('rmjs-') !== -1) {
                  element.removeAttribute('id');
                }
              });
              delete this;
            }
          }]);

          return Readmore;
        }();

        Readmore.VERSION = "3.0.0-beta-1";
        /* harmony default export */

        __webpack_exports__["default"] = Readmore;
        /***/
      },

      /***/
      0:
      /*!*******************************!*\
        !*** multi ./src/readmore.js ***!
        \*******************************/

      /*! no static exports found */

      /***/
      function _(module, exports, __webpack_require__) {
        module.exports = __webpack_require__(
        /*! ./src/readmore.js */
        "./src/readmore.js");
        /***/
      }
      /******/

    })["default"]
  );
});

var _YBA = {};
_YBA.url = '';
_YBA.YACHTS = {};
_YBA.FORM_SELECT_OPTIONS = {};

_YBA.call_api = function (method, path, passing_data) {
  var xhttp = new XMLHttpRequest();
  return new Promise(function (resolve, reject) {
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var responseData = JSON.parse(this.responseText);
        resolve(responseData);
      }
    };

    switch (method) {
      case 'GET':
        var searchParams = new URLSearchParams();

        for (var property in passing_data) {
          searchParams.set(property, passing_data[property]);
        }

        xhttp.open("GET", _yatco_wp_url._yatco_wp_rest_url + "yatco/" + path + "?" + searchParams.toString(), true);
        xhttp.send();
        break;

      case 'POST':
        xhttp.open("POST", _yatco_wp_url._yatco_wp_rest_url + "yatco/" + path, true);
        xhttp.setRequestHeader('Content-Type', 'application/json');
        xhttp.send(JSON.stringify(passing_data));
        break;
    }
    /*
    	xhttp.open("POST", "http://data-wordpress.yatcoboss.com:80/API/V1/ForSale/Vessel/Search", true);
    		xhttp.setRequestHeader('Authorization', "Basic /lQhJqv6kgCkA8Gxv/tNSU1mU6CuzLMXWo92FEKcLPg=");
    	xhttp.setRequestHeader('Accept', 'application/json');
    	xhttp.setRequestHeader('Content-Type', 'application/json');
    */

  });
};

_YBA.label_options = function (label) {
  var passing_data;

  if (_typeof3(label) == 'object' && !Array.isArray(label)) {
    passing_data = label;
  } else if (label === '' || Array.isArray(label) && label.length == 0) {
    return {};
  } else if (typeof label == 'string' || Array.isArray(label)) {
    passing_data = {};
    passing_data.label = label;
  }

  return _YBA.call_api('GET', 'form-data-common', passing_data);
};

_YBA.yacht_search = function (params) {
  if (typeof params == 'undefined') {
    params = {};
  }

  return _YBA.call_api('POST', 'yachts', params);
};

_YBA.yacht_details = function (id) {
  return _YBA.call_api('POST', 'yacht-detail', {
    vessel_id: id
  });
};

_YBA.charter_search = function (params) {
  if (typeof params == 'undefined') {
    params = {};
  }

  return _YBA.call_api('POST', 'charters', params);
};

_YBA.broker_search = function (params) {
  if (typeof params == 'undefined') {
    params = {};
  }

  return _YBA.call_api('POST', 'brokers', params);
};

_YBA.brokerage_search = function (params) {
  if (typeof params == 'undefined') {
    params = {};
  }

  return _YBA.call_api('POST', 'brokerages', params);
};

_YBA.send_lead = function (params) {
  return _YBA.call_api('POST', 'send-lead', params);
}; //helper for fixing large list issue for mobile


function setupFilteredAutocomplete(inputSelector, datalistId, dataSource, getValueFn) {
  var input = jQuery(inputSelector);
  var datalist = jQuery('#' + datalistId);
  var typingTimer;
  var isFiltering = false;
  input.on('input', function () {
    clearTimeout(typingTimer);
    var searchTerm = this.value.toLowerCase().trim(); // Clear if less than 2 characters

    if (searchTerm.length < 2) {
      if (!isFiltering) {
        datalist.empty();
      }

      return;
    }

    typingTimer = setTimeout(function () {
      isFiltering = true;
      datalist.empty(); // Filter matches

      var matches = [];

      for (var i = 0; i < dataSource.length && matches.length < 50; i++) {
        var value = getValueFn(dataSource[i]);

        if (value.toLowerCase().indexOf(searchTerm) !== -1) {
          matches.push(value);
        }
      } // Use fragment for single DOM update


      if (matches.length > 0) {
        var fragment = document.createDocumentFragment();

        for (var i = 0; i < matches.length; i++) {
          var opt = document.createElement('option');
          opt.value = matches[i];
          fragment.appendChild(opt);
        }

        datalist[0].appendChild(fragment);
      }

      isFiltering = false;
    }, 250);
  }); // Clear on blur to save memory

  input.on('blur', function () {
    setTimeout(function () {
      datalist.empty();
    }, 200);
  });
} // Fill Select Inputs With Options


function yt_fill_options(ele, list) {
  for (var i = 0; i < list.length; i++) {
    if (list[i].id != null && list[i].text != '') {
      var new_option = document.createElement('option');
      new_option.text = list[i].text;

      if (ele.getAttribute('data-yatco-fil-just-text-val') == 'true') {
        new_option.value = list[i].text;
        new_option.setAttribute('data-id', list[i].id);
      } else {
        new_option.value = list[i].id;
      }

      ele.options.add(new_option);
    }
  }
}

function yt_init_super_selector(ele) {
  var attr_label = ele.data('yatco-fill-options');
  var cur_input = ele;
  console.log(attr_label);

  _YBA.label_options(attr_label).then(function (opts) {
    yt_data_fills[attr_label] = opts[yt_data_fills];
    yt_fill_super_options(cur_input[0], opts[attr_label]);
    cur_input.attr('filled', 'filled');
    yt_init_super_selector_search_and_uncheck(cur_input, opts[attr_label]);
    YT_SelectAfterLoading(cur_input);
  });
}

function yt_init_super_selector_search_and_uncheck(cur_input, list) {
  console.log(cur_input);
  console.log(list);

  if (cur_input.hasClass('inited')) {
    jQuery('.search', cur_input).unbind("input");
    jQuery('.search', cur_input).on('input', function () {
      var filtered_results = list.filter(function (opt) {
        var lower_val = opt.text.toLowerCase();
        var input_val_lower = jQuery('.search', cur_input).val().toLowerCase();
        return lower_val.indexOf(input_val_lower) != -1;
      });
      yt_fill_super_options_hide(cur_input[0], filtered_results);
    });
    /*jQuery('.uncheck-all', cur_input).click(function() {
    		jQuery('.yt-super-option', cur_input).each(function() {
    			jQuery('input', jQuery(this)).prop('checked', false);
    		});
    	});*/
  } else {
    cur_input.addClass('inited');
    cur_input.prepend('<input type="text" class="search" placeholder="Search..">');
    jQuery('.search', cur_input).on('input', function () {
      var filtered_results = list.filter(function (opt) {
        var lower_val = opt.text.toLowerCase();
        var input_val_lower = jQuery('.search', cur_input).val().toLowerCase();
        return lower_val.indexOf(input_val_lower) != -1;
      });
      yt_fill_super_options_hide(cur_input[0], filtered_results);
    });
    jQuery('.uncheck-all', cur_input).click(function () {
      jQuery('.yt-super-option', cur_input).each(function () {
        jQuery('input', jQuery(this)).prop('checked', false);
      });
    });
  }
}

function yt_fill_super_options(ele, list) {
  jQuery('.yt-super-options', ele).html('');
  var input_name = jQuery(ele).attr('name');

  for (var i = 0; i < list.length; i++) {
    if (list[i].id != null && list[i].text != '') {
      //console.log(list[i].text);
      var new_option = document.createElement('div');
      new_option.className = 'yt-super-option';
      new_option.innerHTML = "<label id=\"option-".concat(list[i].id, "\" data-boss-id=\"").concat(list[i].id, "\"><input type=\"radio\" name=\"").concat(input_name, "\" value=\"").concat(list[i].id, "\">").concat(list[i].text, "</label>");
      jQuery('.yt-super-options', ele).append(new_option);
    }
  }

  jQuery('.yt-super-option', ele).each(function () {
    jQuery('label', jQuery(this)).click(function () {
      if (jQuery('input', jQuery(this)).prop('checked')) {
        jQuery(this).addClass('checked');
      } else {
        jQuery(this).removeClass('checked');
      }
    });
  });
}

function yt_fill_super_options_with_promise(ele, list) {
  return new Promise(function (resolve, reject) {
    jQuery('.yt-super-options', ele).html('');
    var input_name = jQuery(ele).attr('name');

    for (var i = 0; i < list.length; i++) {
      if (list[i].id != null && list[i].text != '') {
        //console.log(list[i].text);
        var new_option = document.createElement('div');
        new_option.className = 'yt-super-option';
        new_option.innerHTML = "<label id=\"option-".concat(list[i].id, "\" data-boss-id=\"").concat(list[i].id, "\"><input type=\"radio\" name=\"").concat(input_name, "\" value=\"").concat(list[i].id, "\">").concat(list[i].text, "</label>");
        jQuery('.yt-super-options', ele).append(new_option);
      }
    }

    jQuery('.yt-super-option', ele).each(function () {
      jQuery('label', jQuery(this)).click(function () {
        if (jQuery('input', jQuery(this)).prop('checked')) {
          jQuery(this).addClass('checked');
        } else {
          jQuery(this).removeClass('checked');
        }
      });
    });
    resolve();
  });
}

function yt_fill_super_options_hide(ele, list) {
  var list_just_ids = list.map(function (opt) {
    return opt.id;
  });
  jQuery('.yt-super-options label', ele).each(function () {
    var id = jQuery(this).data('boss-id');

    if (list_just_ids.indexOf(id) == -1) {
      jQuery(this).addClass('opt-hide').removeClass('opt-show');
    } else {
      jQuery(this).addClass('opt-show').removeClass('opt-hide');
    }
  });
} // Select After Loading


function YT_SelectAfterLoading(ele) {
  if (jQuery(ele).data('value') !== "") {
    if (jQuery(ele).hasClass('yt-super-select')) {
      var input_name = jQuery(ele).attr('name');
      var input_val = ele.data('value');
      jQuery('input[name="' + input_name + '"][value="' + input_val + '"]', jQuery(ele)).prop('checked', true);
      jQuery('input[name="' + input_name + '"][value="' + input_val + '"]', jQuery(ele)).parent().addClass('checked');
    } else {
      jQuery(ele).val(jQuery(ele).data('value'));
    }
  }
} // Countries


function GetCountryList(value) {
  value = value || '';

  if (jQuery('.fill-country').length > 0 && value != '') {
    _YBA.label_options({
      label: 'GeoListActiveVesselRegionCountries',
      RegionId: value
    }).then(function (lst) {
      GetCountryList_Success(lst.GeoListActiveVesselRegionCountries);
    });
  }
}

function GetCountryList_Success(lst) {
  var dd = jQuery('.fill-country');
  dd.empty();

  if (lst.length == 0) {
    dd.append('<option value="">None</option>');
  } else {
    dd.append('<option value="">Any Country</option>');

    for (var i = 0; i < lst.length; i++) {
      if (dd.attr('data-yatco-fil-just-text-val') == 'true') {
        dd.append('<option data-id="' + lst[i].id + '" value="' + lst[i].text + '">' + lst[i].Country + '</option>');
      } else {
        dd.append('<option value="' + lst[i].id + '">' + lst[i].text + '</option>');
      }
    }
  }

  YT_SelectAfterLoading(dd);
} // STATES


function GetStateList(value) {
  value = value || '';

  if (jQuery('.fill-state').length > 0 && value != '') {
    _YBA.label_options({
      label: 'States',
      countrySEO: value
    }).then(function (lst) {
      GetStateList_Success(lst.States);
    });
  }
}

function GetGeoStateList(value) {
  value = value || '';

  if (jQuery('.fill-state').length > 0 && value != '') {
    _YBA.label_options({
      label: 'GeoListActiveVesselStates',
      CountryId: value
    }).then(function (lst) {
      GetStateList_Success(lst.GeoListActiveVesselStates);
    });
  }
}

function GetStateList_Success(lst) {
  var dd = jQuery('.fill-state');
  dd.empty();

  if (lst.length == 0) {
    dd.append('<option value="">None</option>');
  } else {
    dd.append('<option value="">Any State</option>');

    for (var i = 0; i < lst.length; i++) {
      if (dd.attr('data-yatco-fil-just-text-val') == 'true') {
        dd.append('<option data-id="' + lst[i].id + '" value="' + lst[i].text + '">' + lst[i].text + '</option>');
      } else {
        dd.append('<option value="' + lst[i].id + '">' + lst[i].text + '</option>');
      }
    }
  }

  YT_SelectAfterLoading(dd);
} // CITIES


function GetGeoCityList(value) {
  value = value || '';

  if (jQuery('.fill-state').length > 0 && value != '') {
    _YBA.label_options({
      label: 'GeoListActiveCities',
      StateId: value
    }).then(function (lst) {
      GetCityList_Success(lst.GeoListActiveCities);
    });
  }
}

function GetGeoCountryCityList(value) {
  value = value || '';

  if (jQuery('.fill-state').length > 0 && value != '') {
    _YBA.label_options({
      label: 'GeoListActiveVesselCountryCities',
      CountryId: value
    }).then(function (lst) {
      GetCityList_Success(lst.GeoListActiveVesselCountryCities);
    });
  }
}

function GetCityList_Success(lst) {
  var dd = jQuery('.fill-city');
  dd.empty();

  if (lst.length == 0) {
    dd.append('<option value="">None</option>');
  } else {
    dd.append('<option value="">Any City</option>');

    for (var i = 0; i < lst.length; i++) {
      dd.append('<option value="' + lst[i].id + '">' + lst[i].text + '</option>');
    }
  }

  YT_SelectAfterLoading(dd);
} // Filling Association Regions


function GetAssociationRegions(lst) {
  jQuery('.fill-association-region').each(function () {
    var ddd = jQuery(this);

    _YBA.label_options({
      label: 'AssociationRegions',
      'id': jQuery(this).data('association-id')
    }).then(function (lst) {
      var dd = ddd;
      dd.empty();
      dd.append('<option value="">All</option>');
      yt_fill_options(dd[0], lst.AssociationRegions);
      YT_SelectAfterLoading(dd);
    });
  });
} /// data-yatco-fill-options


var yt_data_fills = {};

function yatco_data_fill_options(root_ele) {
  console.log(yt_data_fills);
  var list_of_fills = [];
  jQuery('select[data-yatco-fill-options]:not([filled])', root_ele).each(function () {
    var attr_label = jQuery(this).data('yatco-fill-options');
    var cur_input = jQuery(this);

    if (list_of_fills.indexOf(attr_label) == -1) {
      list_of_fills.push(attr_label);
    }
  });

  for (var property in yt_data_fills) {
    console.log(property);

    if (list_of_fills.indexOf(property) != -1) {
      var index = list_of_fills.indexOf(property);
      console.log('remoe');
      delete list_of_fills[index];
    }
  }

  if (list_of_fills.length >= 1) {
    _YBA.label_options(list_of_fills).then(function (opts) {
      for (var _property in opts) {
        var options = opts[_property];
        yt_data_fills[_property] = options;
        jQuery('select[data-yatco-fill-options="' + _property + '"]').each(function () {
          var attr_label = jQuery(this).data('yatco-fill-options');
          var cur_input = jQuery(this);

          if (cur_input.data('yatco-empty-after-fill') == true) {
            cur_input.empty();
          }

          yt_fill_options(cur_input[0], options);
          YT_SelectAfterLoading(cur_input);
        });
      }
    });
  }

  jQuery('.yt-super-select[data-yatco-fill-options]:not([filled])', root_ele).each(function () {
    yt_init_super_selector(jQuery(this));
    console.log(jQuery(this)[0]);
  });
}

jQuery(document).ready(function () {
  // Resoting Form Data
  jQuery('form[data-input-values]').each(function () {
    var values = jQuery(this).data('input-values');
    run_through_and_sync_form(jQuery(this), values);
  });

  if (jQuery('.yatco-js-shortcode-yacht-results, .yatco-js-shortcode-yachts-search-form').length > 0 || jQuery('.yatco-js-shortcode-charter-results, .yatco-js-shortcode-charter-search-form').length > 0) {
    var root_ele = jQuery('.yatco-js-shortcode-yachts-search-form');
    jQuery('.yt-super-select[data-yatco-fill-options]:not([filled])', root_ele).each(function () {
      yt_init_super_selector(jQuery(this));
    });
  } else {
    // data-yatco-fill-options
    yatco_data_fill_options(jQuery('.small-form, .big-form, .yatco-shortcode-yacht-results, .yatco-shortcode-search-form'));
    jQuery('.small-form, .big-form').on('change', function (e) {
      var params = get_form_data(jQuery(this)[0]);
      run_through_and_sync_form(jQuery('.small-form, .big-form'), params);
    });
  }

  GetAssociationRegions(); // Filling Country

  jQuery('select[data-yatco-fill-options="GeoListRegionsWithActiveCountries"]').each(function () {
    var regionID = jQuery(this).data('value');

    if (regionID == 1 || regionID == 12) {
      jQuery('.yt-state-col-field').removeClass('disabled');
      jQuery('.yt-state-col-field select').removeAttr('disabled');
    } else {
      jQuery('.yt-state-col-field').addClass('disabled');
      jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
    }

    GetCountryList(regionID);
  }).on('change', function () {
    var regionID = jQuery(this).val();

    if (regionID == 1 || regionID == 12) {
      jQuery('.yt-state-col-field').removeClass('disabled');
      jQuery('.yt-state-col-field select').removeAttr('disabled');
    } else {
      jQuery('.yt-state-col-field').addClass('disabled');
      jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
    }

    jQuery('select[name="LocationCountryID"]').html('<option value="">Pick Region First...</option>');
    jQuery('select[name="LocationStateID"]').html('<option value="">Pick Country First...</option>');
    jQuery('select[name="LocationCityID"]').html('<option value="">Pick State First...</option>');
    GetCountryList(regionID);
  }); // Filling States

  /*jQuery('select[data-yatco-fill-options="Countries"]').each(function() {
  	
  	GetStateList(jQuery(this).data('value'));
  
  }).on('change', function () {
  
  	var CountryID = jQuery(this).val();
  		GetStateList(CountryID);
  
  });
  */

  jQuery('select[name="LocationCountryID"]').each(function () {
    var CountryID = jQuery(this).data('value');

    if (CountryID == 233 || CountryID == 39 || CountryID == 14 || CountryID == 158) {
      jQuery('.yt-state-col-field').removeClass('disabled');
      jQuery('.yt-state-col-field select').removeAttr('disabled');
      jQuery('select[name="LocationStateID"]').html('<option value="">Pick Country First...</option>');
      jQuery('select[name="LocationCityID"]').html('<option value="">Pick State First...</option>');
      GetGeoStateList(CountryID);
    } else {
      jQuery('.yt-state-col-field').addClass('disabled');
      jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
      jQuery('select[name="LocationCityID"]').html('<option value="">Pick Country First...</option>');
      GetGeoCountryCityList(CountryID);
    }
  }).on('change', function () {
    // USA (location ID 233), Canada (location ID 39) 
    // Australia (location ID 14), and New Zealand (location ID 158).
    if (jQuery(this).attr('data-yatco-fil-just-text-val') == 'true') {
      GetGeoStateList(jQuery(this).data('id'));

      if (CountryID == 233 || CountryID == 39 || CountryID == 14 || CountryID == 158) {
        jQuery('.yt-state-col-field').removeClass('disabled');
        jQuery('.yt-state-col-field select').removeAttr('disabled');
      } else {
        jQuery('.yt-state-col-field').addClass('disabled');
        jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
      }
    } else {
      var CountryID = jQuery(this).val();

      if (CountryID == 233 || CountryID == 39 || CountryID == 14 || CountryID == 158) {
        jQuery('.yt-state-col-field').removeClass('disabled');
        jQuery('.yt-state-col-field select').removeAttr('disabled');
        GetGeoStateList(CountryID);
      } else {
        jQuery('.yt-state-col-field').addClass('disabled');
        jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
        GetGeoCountryCityList(CountryID);
      }
    }
  }); // Filling Cities

  jQuery('select[name="LocationStateID"]').each(function () {
    GetGeoCityList(jQuery(this).data('value'));
  }).on('change', function () {
    GetGeoCityList(jQuery(this).val());
  }); // fill #builders_list

  if (jQuery('#builders_list').length > 0 && jQuery('#vessel_names_list').length > 0) {
    _YBA.call_api("POST", 'form-data-common', {
      label: 'ActiveBuilders,VesselNames',
      //label: 'PopularBuilders', 
      filter: ''
    }).then(function (data) {
      yt_data_fills['ActiveBuilders'] = data.ActiveBuilders;
      yt_data_fills['VesselNames'] = data.VesselNames;
      setupFilteredAutocomplete('input[name="VesselName"][list]', 'vessel_names_list', yt_data_fills.VesselNames, function (item) {
        return item;
      }); // Setup filtered autocomplete for Builders

      setupFilteredAutocomplete('input[name="Builder"][list]', 'builders_list', yt_data_fills.ActiveBuilders, function (item) {
        return item.text;
      });
    });
  } else {
    if (jQuery('#builders_list').length > 0) {
      _YBA.call_api("POST", 'form-data-common', {
        label: 'ActiveBuilders',
        //label: 'PopularBuilders', 
        filter: ''
      }).then(function (data) {
        yt_data_fills['ActiveBuilders'] = data.ActiveBuilders;
        setupFilteredAutocomplete('input[name="Builder"][list]', 'builders_list', yt_data_fills.ActiveBuilders, function (item) {
          return item.text;
        });
      });
    }

    if (jQuery('#vessel_names_list').length > 0) {
      _YBA.call_api("POST", 'form-data-common', {
        label: 'VesselNames',
        filter: ''
      }).then(function (data) {
        yt_data_fills['VesselNames'] = data.VesselNames;
        setupFilteredAutocomplete('input[name="VesselName"][list]', 'vessel_names_list', yt_data_fills.VesselNames, function (item) {
          return item;
        });
      });
    }
  }

  if (jQuery('#destinations_list').length > 0) {
    _YBA.call_api("POST", 'form-data-common', {
      label: 'DDCharterDestinations',
      filter: ''
    }).then(function (data) {
      jQuery('#destinations_list').html('');
      var list = [];

      for (var b = 0; b < data.DDCharterDestinations.length; b++) {
        var item = data.DDCharterDestinations[b];
        list.push(item.text.replace('-- ', ''));
      }

      list.sort();

      for (var b = 0; b < list.length; b++) {
        jQuery('#destinations_list').append('<option value="' + list[b] + '"></option>');
      }
    });
  }
});

function yt_category_based_off_of_type(ele) {
  if (ele.val() == '1' || ele.val() === 1) {
    _YBA.label_options({
      label: 'SailCategories'
    }).then(function (lst) {
      jQuery('select[name=MainCategoryID]').empty();
      jQuery('select[name=MainCategoryID]').prepend('<option value="">All</option>');

      if (jQuery('.yt-super-select[name=MainCategoryID]').length) {
        var cur_input = jQuery('.yt-super-select[name=MainCategoryID]');
        yt_fill_super_options_with_promise(cur_input[0], lst.Categories).then(function () {
          yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=MainCategoryID]'), lst.Categories);
          YT_SelectAfterLoading(jQuery('.yt-super-select[name=MainCategoryID]'));
          jQuery('.yt-sub-category-col-field').addClass('disabled');
          jQuery('.yt-sub-category-col-field select').attr('disabled', 'disabled');
          jQuery('.yt-super-select input[name=MainCategoryID]').each(function () {
            jQuery(this).change(function () {
              if (jQuery(this).val() != '') {
                jQuery('.yt-sub-category-col-field').removeClass('disabled');
                jQuery('.yt-sub-category-col-field select').removeAttr('disabled');

                _YBA.label_options({
                  label: 'SubCategories',
                  mainID: jQuery(this).val()
                }).then(function (lst) {
                  yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=SubCategoryID]')[0], lst.SubCategories).then(function () {
                    yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=SubCategoryID]'), lst.SubCategories);
                    YT_SelectAfterLoading(jQuery('.yt-super-select[name=SubCategoryID]'));
                  });
                });
              }
            });
          });
        });
      } else {
        yt_fill_options(jQuery('select[name=MainCategoryID]')[0], lst.SailCategories);
      }

      YT_SelectAfterLoading(jQuery('select[name=MainCategoryID]'));
    });
  } else {
    _YBA.label_options({
      label: 'Categories'
    }).then(function (lst) {
      jQuery('select[name=MainCategoryID]').empty();
      jQuery('select[name=MainCategoryID]').prepend('<option value="">All</option>');

      if (jQuery('.yt-super-select[name=MainCategoryID]').length) {
        var cur_input = jQuery('.yt-super-select[name=MainCategoryID]');
        yt_fill_super_options_with_promise(cur_input[0], lst.Categories).then(function () {
          yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=MainCategoryID]'), lst.Categories);
          YT_SelectAfterLoading(jQuery('.yt-super-select[name=MainCategoryID]'));
          jQuery('.yt-sub-category-col-field').addClass('disabled');
          jQuery('.yt-sub-category-col-field select').attr('disabled', 'disabled');
          jQuery('.yt-super-select input[name=MainCategoryID]').each(function () {
            jQuery(this).change(function () {
              if (jQuery(this).val() != '') {
                jQuery('.yt-sub-category-col-field').removeClass('disabled');
                jQuery('.yt-sub-category-col-field select').removeAttr('disabled', 'disabled');

                _YBA.label_options({
                  label: 'SubCategories',
                  mainID: jQuery(this).val()
                }).then(function (lst) {
                  yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=SubCategoryID]')[0], lst.SubCategories).then(function () {
                    yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=SubCategoryID]'), lst.SubCategories);
                    YT_SelectAfterLoading(jQuery('.yt-super-select[name=SubCategoryID]'));
                  });
                });
              }
            });
          });
        });
      } else {
        yt_fill_options(jQuery('select[name=MainCategoryID]')[0], lst.Categories);
      }

      YT_SelectAfterLoading(jQuery('select[name=MainCategoryID]'));
    });
  }
}

jQuery(document).ready(function () {
  // Filling Sail/Motor Categories
  jQuery('select[name=VesselType], select[name=vesseltype], input[name=VesselType], input[name=vesseltype]').each(function () {
    if ((jQuery(this).attr('type') == 'radio' || jQuery(this).attr('type') == 'checkbox') && !jQuery(this).prop('checked')) {
      return false;
    }

    yt_category_based_off_of_type(jQuery(this));
  }).change(function () {
    jQuery('select[name=SubCategoryID]').empty();
    jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');
    yt_category_based_off_of_type(jQuery(this));
  }); // Filling Sub-Category

  jQuery('select[name=MainCategoryID], .yt-super-select[name=MainCategoryID]').each(function () {
    if (jQuery(this).data('value') != '') {
      _YBA.label_options({
        label: 'SubCategories',
        mainID: jQuery(this).data('value')
      }).then(function (lst) {
        console.log(lst.SubCategories);
        jQuery('select[name=SubCategoryID]').empty();
        jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');

        if (jQuery('.yt-super-select[name=SubCategoryID]').length) {
          jQuery('.yt-sub-category-col-field').removeClass('disabled');
          jQuery('.yt-sub-category-col-field select').removeAttr('disabled', 'disabled');
          yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=SubCategoryID]')[0], lst.SubCategories).then(function () {
            YT_SelectAfterLoading(jQuery('.yt-super-select[name=SubCategoryID]'));
          });
        } else {
          yt_fill_options(jQuery('select[name=SubCategoryID]')[0], lst.SubCategories);
          YT_SelectAfterLoading(jQuery('select[name=SubCategoryID]'));
        }
      });
    } else {
      jQuery('select[name=SubCategoryID]').empty();
      jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');
    }
  }).on('change', function () {
    if (jQuery(this).val() != '') {
      _YBA.label_options({
        label: 'SubCategories',
        mainID: jQuery(this).val()
      }).then(function (lst) {
        jQuery('select[name=SubCategoryID]').empty();
        jQuery('select[name=SubCategoryID]').prepend('<option value="">-- ALL --</option>');
        yt_fill_options(jQuery('select[name=SubCategoryID]')[0], lst.SubCategories);
      });
    } else {
      jQuery('select[name=SubCategoryID]').empty();
      jQuery('select[name=SubCategoryID]').prepend('<option value="">-- Pick A Category, First --</option>');
    }
  });
});

function yt_super_selector_location_search(ele) {}

jQuery(document).ready(function () {
  console.log('hello world'); // Filling Country

  jQuery('.yt-super-select[name=LocationRegionID][data-value]').each(function () {
    if (jQuery(this).data('value') != '') {
      _YBA.label_options({
        label: 'GeoListRegionCountries',
        RegionId: jQuery(this).data('value')
      }).then(function (lst) {
        yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCountryID]')[0], lst.GeoListRegionCountries).then(function () {
          yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCountryID]'), lst.GeoListRegionCountries);
          YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCountryID]'));
          jQuery('.yt-country-col-field').removeClass('disabled'); // Filling States

          _YBA.label_options({
            label: 'GeoListStates',
            CountryId: jQuery('.yt-super-select[name=LocationCountryID]').data('value')
          }).then(function (lst) {
            yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], lst.GeoListStates).then(function () {
              yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationStateID]'), lst.GeoListStates);
              YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationStateID]'));

              if (jQuery('.yt-super-select[name=LocationStateID]').data('value') != '') {
                jQuery('.yt-state-col-field').removeClass('disabled');
              } // Filling States


              _YBA.label_options({
                label: 'GeoListCities',
                StateId: jQuery('.yt-super-select[name=LocationStateID]').data('value')
              }).then(function (lst) {
                yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], lst.GeoListCities).then(function () {
                  yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCityID]'), lst.GeoListCities);
                  YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCityID]'));

                  if (jQuery('.yt-super-select[name=LocationCityID]').data('value') != '') {
                    jQuery('.yt-city-col-field').removeClass('disabled');
                  }
                });
              });
            });
          });
        });
      });
    }
  });

  if (jQuery('.yt-super-select[name=LocationRegionID]').length) {
    _YBA.label_options({
      label: 'GeoListRegionsWithActiveCountries'
    }).then(function (lst) {
      if (jQuery('.yt-super-select[name=LocationRegionID]').length) {
        var cur_input = jQuery('.yt-super-select[name=LocationRegionID]');
        yt_fill_super_options_with_promise(cur_input[0], lst.GeoListRegionsWithActiveCountries).then(function () {
          yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationRegionID]'), lst.GeoListRegionsWithActiveCountries);
          YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationRegionID]'));

          if (jQuery('.yt-super-select[name=LocationRegionID]').data('value') == '') {
            jQuery('.yt-country-col-field').addClass('disabled');
            jQuery('.yt-state-col-field').addClass('disabled');
            jQuery('.yt-city-col-field').addClass('disabled');
          }

          if (jQuery('.yt-super-select[name=LocationRegionID]').data('value') != '') {
            jQuery('.yt-country-col-field').removeClass('disabled');
          }

          jQuery('.yt-super-select input[name=LocationRegionID]').each(function () {
            jQuery(this).change(function () {
              if (jQuery(this).val() != '') {
                jQuery('.yt-country-col-field').removeClass('disabled');
                var regionID = jQuery(this).val();

                if (regionID == 1 || regionID == 12) {
                  jQuery('.yt-state-col-field').removeClass('disabled');
                  jQuery('.yt-state-col-field select').removeAttr('disabled');
                } else {
                  jQuery('.yt-state-col-field').addClass('disabled');
                  jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
                }

                yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], []);
                yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], []);

                _YBA.label_options({
                  label: 'GeoListRegionCountries',
                  RegionId: jQuery(this).val()
                }).then(function (lst) {
                  console.log(lst);
                  console.log(jQuery('.yt-super-select[name=LocationCountryID]')[0]);
                  yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCountryID]')[0], lst.GeoListRegionCountries).then(function () {
                    yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCountryID]'), lst.GeoListRegionCountries);
                    YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCountryID]'));
                    jQuery('.yt-super-select input[name=LocationCountryID]').each(function () {
                      jQuery(this).change(function () {
                        var CountryID = jQuery(this).val();
                        jQuery('.yt-state-col-field').removeClass('disabled');
                        jQuery('.yt-city-col-field').removeClass('disabled'); //seet classes

                        if (CountryID == 233 || CountryID == 39 || CountryID == 14 || CountryID == 158) {
                          jQuery('.yt-state-col-field').removeClass('disabled');
                          jQuery('.yt-state-col-field select').removeAttr('disabled');
                        } else {
                          jQuery('.yt-state-col-field').addClass('disabled');
                          jQuery('.yt-state-col-field select').attr('disabled', 'disabled');
                        }

                        yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], []);
                        yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], []); // set options

                        if (CountryID == 233 || CountryID == 39 || CountryID == 14 || CountryID == 158) {
                          _YBA.label_options({
                            label: 'GeoListStates',
                            CountryId: jQuery(this).val()
                          }).then(function (lst) {
                            yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationStateID]')[0], lst.GeoListStates).then(function () {
                              yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationStateID]'), lst.GeoListStates);
                              YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationStateID]'));
                              jQuery('.yt-super-select input[name=LocationStateID]').each(function () {
                                jQuery(this).change(function () {
                                  _YBA.label_options({
                                    label: 'GeoListCities',
                                    StateId: jQuery(this).val()
                                  }).then(function (lst) {
                                    yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], lst.GeoListCities).then(function () {
                                      yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCityID]'), lst.GeoListCities);
                                      YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCityID]'));
                                    });
                                  });
                                });
                              });
                            });
                          });
                        } else {
                          // country -> cities
                          _YBA.label_options({
                            label: 'GeoListCountryCities',
                            CountryId: jQuery(this).val()
                          }).then(function (lst) {
                            yt_fill_super_options_with_promise(jQuery('.yt-super-select[name=LocationCityID]')[0], lst.GeoListCountryCities).then(function () {
                              yt_init_super_selector_search_and_uncheck(jQuery('.yt-super-select[name=LocationCityID]'), lst.GeoListCountryCities);
                              YT_SelectAfterLoading(jQuery('.yt-super-select[name=LocationCityID]'));
                            });
                          });
                        }
                      });
                    });
                  });
                });
              } else {
                jQuery('.yt-country-col-field').addClass('disabled');
                jQuery('.yt-state-col-field').addClass('disabled');
                jQuery('.yt-city-col-field').addClass('disabled');
              }
            });
          });
        });
      }
    });
  }
});

function yt_sync_input(ele_input, values) {
  if (!ele_input.attr('dont-touch')) {
    var name = ele_input.attr('name');
    var _val = null; // name = name.replace('_', '');
    //console.log(name);

    if (typeof values[name] != 'undefined' && values[name] != null) {
      _val = values[name];
    } else if (typeof values[name.toLowerCase()] != 'undefined' && values[name.toLowerCase()] != null) {
      _val = values[name.toLowerCase()];
    } else {
      _val = null;
    } //console.log(_val);


    if (typeof _val != 'undefined' && _val != null) {
      if (ele_input.attr('type') == 'checkbox' || ele_input.attr('type') == 'radio') {
        if (ele_input.attr('value') == _val) {
          ele_input.attr('checked', 'checked');
        }
      } else {
        ele_input.val(_val);
      }
    }
  }
}

function yt_input_number_formatter() {
  var val = jQuery(this).val();
  val = decodeURIComponent(val);
  console.log(val);
  var number_str = val.match(/(\d*)/g);

  if (Array.isArray(number_str)) {
    number_str = number_str.join('');
  }

  var formatted = new Intl.NumberFormat('en').format(number_str);

  if (formatted != NaN && formatted != 'NaN' && formatted !== "0" && formatted !== 0) {
    jQuery(this).val(formatted);
  }
}

function yt_ele_input_number_formatter(j_ele) {
  var val = j_ele.val();
  val = decodeURIComponent(val);
  console.log(val);
  var number_str = val.match(/(\d*)/g);

  if (Array.isArray(number_str)) {
    number_str = number_str.join('');
  }

  var formatted = new Intl.NumberFormat('en').format(number_str);

  if (formatted != NaN && formatted != 'NaN' && formatted !== "0" && formatted !== 0) {
    j_ele.val(formatted);
  }
}

function run_through_and_sync_form(jquery_ele, values) {
  var form_name = jquery_ele.attr('id');
  jQuery('input[name], select[name], textarea[name]', jquery_ele).each(function () {
    yt_sync_input(jQuery(this), values);
  });
  jQuery('input[name][form="' + form_name + '"], select[name][form="' + form_name + '"], textarea[name][form="' + form_name + '"]').each(function () {
    yt_sync_input(jQuery(this), values);
  });
  jQuery('input[name="pricerange_from"], input[name="pricerange_to"], input[name="loa_from"], input[name="loa_to"]', jquery_ele).each(function () {
    var $input = jQuery(this); // attach events

    $input.on('input', yt_input_number_formatter);
    $input.on('change', yt_input_number_formatter); // delay initial format so value isn’t lost

    setTimeout(function () {
      yt_ele_input_number_formatter($input);
    }, 50);
  });
}

function isElementInViewport(el) {
  // Special bonus for those using jQuery
  if (typeof jQuery === "function" && el instanceof jQuery) {
    el = el[0];
  }

  var rect = el.getBoundingClientRect();
  return rect.top >= 0 && rect.left >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
  /* or $(window).height() */
  rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  /* or $(window).width() */
  ;
} // Reset Search Form


function resetSearchForm(e) {
  e.preventDefault(); //document.getElementById("desktop-quick-search").reset();
  //document.getElementById("desktop-expanded-search").reset();

  jQuery('form[data-shortcode-attributes]').each(function () {
    jQuery(this)[0].reset();
    var values = jQuery(this).data('shortcode-attributes');

    if (typeof values['load-more-button'] != 'undefined' && values['load-more-button'] == 1 || values['load-more-button'] == "1") {
      values.page_size = 12;
      values.page_index = 1;
    }

    run_through_and_sync_form(jQuery(this), values);
  });
}

function yt_js_resetSearchFormAndResult(e) {
  e.preventDefault(); //document.getElementById("desktop-quick-search").reset();
  //document.getElementById("desktop-expanded-search").reset();

  jQuery('form[data-shortcode-attributes]').each(function () {
    jQuery(this)[0].reset();
    var values = jQuery(this).data('shortcode-attributes');

    if (typeof values['load-more-button'] != 'undefined' && (values['load-more-button'] == 1 || values['load-more-button'] == "1")) {
      values.page_size = 12;
      values.page_index = 1;
    }

    console.log(values);
    run_through_and_sync_form(jQuery(this), values);
    get_vessels_and_render(values);
  });
} // Contact Modal


function openContactModal(MLSID, CompanyID, CompanyName, BrokerName) {
  jQuery('#yatco-contact-modal input[name=VesselID]').val(MLSID);
  jQuery('#yatco-contact-modal input[name=CompanyID]').val(CompanyID);
  jQuery('#yatco-contact-modal .broker-name').text(BrokerName);
  jQuery('#yatco-contact-modal .brokerage').text(CompanyName);
  jQuery("#yatco-contact-modal").yatco_modal({
    closeText: 'X'
  });
  yt_ga_event('Modal', 'Open', 'Modal Opened: Vessel Lead', ''); // Restting Form 

  jQuery('#yatco-contact-modal textarea[name="Message"]').val('');
  jQuery('#yatco-contact-modal .hide-after-submit').show();
  jQuery('#yatco-contact-modal .form-success-message').hide();
}

function openVdContactModal(MLSID, CompanyID, CompanyName, BrokerName) {
  jQuery('#yatco-vd-contact-modal input[name=VesselID]').val(MLSID);
  jQuery('#yatco-vd-contact-modal input[name=CompanyID]').val(CompanyID);
  jQuery("#yatco-vd-contact-modal").yatco_modal({
    closeText: 'X'
  });
  yt_ga_event('Modal', 'Open', 'Modal Opened: Vessel Directory Lead', ''); // Restting Form 

  jQuery('#yatco-vd-contact-modal textarea[name="Message"]').val('');
  jQuery('#yatco-vd-contact-modal .hide-after-submit').show();
  jQuery('#yatco-vd-contact-modal .form-success-message').hide();
}

function openBrokerContactModal(BrokerID, CompanyName, BrokerName) {
  jQuery('#single-company-modal-email-broker input[name=BrokerID]').val(BrokerID);
  jQuery('#single-company-modal-email-broker .broker-name').text(BrokerName);
  jQuery('#single-company-modal-email-broker .brokerage').text(CompanyName);
  jQuery("#single-company-modal-email-broker").yatco_modal({
    closeText: 'X'
  });
  yt_ga_event('Modal', 'Open', 'Modal Opened: Broker Lead', ''); // Restting Form 

  jQuery('#single-company-modal-email-broker textarea[name="Message"]').val('');
  jQuery('#single-company-modal-email-broker .hide-after-submit').show();
  jQuery('#single-company-modal-email-broker .form-success-message').hide();
} // openCharterLeadModal


function openCharterLeadModal(MLSID, CompanyID) {
  jQuery('#yatco-charter-lead-modal input[name=VesselID]').val(MLSID);
  jQuery('#yatco-charter-lead-modal input[name=CompanyID]').val(CompanyID); // document.querySelector('#yatco-contact-modal .brokerage').innerHTML=CompanyName;

  jQuery("#yatco-charter-lead-modal").yatco_modal({
    closeText: 'X'
  });

  if (typeof ga != 'undefined') {
    ga('send', 'event', 'Modal', 'Open', 'Charter Lead Modal From Results Page');
    ga('send', 'event', MLSID, 'Lead', 'Open');
  } // Restting Form 


  jQuery('#yatco-charter-lead-modal textarea[name="Message"]').val('');
  jQuery('#yatco-charter-lead-modal .hide-after-submit').show();
  jQuery('#yatco-charter-lead-modal .form-success-message').hide();
}

jQuery(document).ready(function () {
  var searchParams = new URLSearchParams(window.location.search);
  var shortcode_form_attr_data = jQuery('.big-form, .yatco-shortcode-search-form form').data('shortcode-attributes');

  if (jQuery('.yatco-shortcode-search-form form').length == 0 && jQuery('.yatco-shortcode-yacht-results').length > 0) {
    var shortcode_attr_data = jQuery('.yatco-shortcode-yacht-results').data('shortcode-attributes');
    shortcode_attr_data.override_with_attrs = '1';
    jQuery('body').append("\n\t\t\t<div class=\"yatco-shortcode-yachts-search-form\">\n\t\t\t\t<form id=\"desktop-expanded-search\" data-shortcode-attributes>\n\t\t\t\t\t<input type=\"hidden\" name=\"fromForm\" value=\"1\">\n\t\t\t\t</form>\n\t\t\t</div>\n\t\t");
    jQuery('#desktop-expanded-search').data('shortcode-attributes', shortcode_attr_data);
  }

  jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr('form', jQuery('.yatco-shortcode-search-form form').attr('id')); // Expand Search

  jQuery('.small-form .expand-search, .big-form .close-advandenced-search').click(function (event) {
    jQuery('.small-form').toggle();
    jQuery('.big-form').toggle();
    var expand_search_is_open = jQuery('.big-form').css('display') == 'block';
    jQuery('.yatco-shortcode-yachts-search-form').data('is-advanced-search-open', expand_search_is_open ? 1 : 0);
    jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr('form', expand_search_is_open ? jQuery('.big-form').attr('id') : jQuery('.small-form').attr('id'));
  });

  if (searchParams.get("openSearchForm") == 'yes' || _typeof3(shortcode_form_attr_data) == 'object' && typeof shortcode_form_attr_data.opensearchform != 'undefined' && shortcode_form_attr_data.opensearchform == 'yes') {
    jQuery('.yatco-shortcode-yachts-search-form').data('is-advanced-search-open', 1);
    jQuery('.small-form').hide();
    jQuery('.big-form').show();
    jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr('form', jQuery('.big-form').attr('id'));
  } else {
    if (jQuery('.yatco-shortcode-search-form form').length == 1) {
      jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').attr('form', jQuery('.small-form').attr('id'));
    }
  } // Scroll To Results


  if (searchParams.has('page_index') || searchParams.get('openSearchForm') || searchParams.get('scroll-to-form')) {
    //document.getElementById('yatco-scroll-to-once-searched').scrollIntoView({behavior: "smooth", block: "start", inline: "nearest"});
    if (jQuery('#yatco-scroll-to-once-searched').length) {
      jQuery([document.documentElement, document.body]).animate({
        scrollTop: jQuery("#yatco-scroll-to-once-searched").offset().top - 75
      }, 1000);
    }
  } // View All


  var more_link_html = '<div class="yt-text-center view-all-button"><a href="#" class="yt-btn read-more">VIEW ALL</a></div>';
  var less_link_html = '<div class="yt-text-center view-all-button"><a href="#" class="yt-btn read-less">VIEW LESS</a></div>';
  new Readmore('.yatco-shortcode-yacht-results.view-all-button', {
    speed: 75,
    collapsedHeight: jQuery('.yatco-shortcode-yacht-results .col-yacht').height() * 3,
    moreLink: more_link_html,
    lessLink: less_link_html
  });
  new Readmore('.card-container.view-all-button', {
    speed: 75,
    collapsedHeight: jQuery('.card-container .col-yacht').height() * 3,
    moreLink: more_link_html,
    lessLink: less_link_html
  }); // View Style

  jQuery('.yatco-shortcode-yacht-results input[name="viewtype"], .yatco-shortcode-yacht-results select[name=sortId]').each(function () {
    jQuery(this).change(function () {
      var form_id = jQuery(this).attr('form');
      jQuery('#' + form_id).submit();
    });
  });
  jQuery('.yatco-shortcode-yacht-results select[name=sortField], .yatco-shortcode-yacht-results select[name=sortDirection]').each(function () {
    jQuery(this).change(function () {
      jQuery('.big-form').submit();
    });
  }); // Reset Search Form

  jQuery('.big-form [type=reset]').click(function (e) {
    e.preventDefault();
    jQuery('form[data-shortcode-attributes]').each(function () {
      jQuery(this)[0].reset();
      var values = jQuery(this).data('shortcode-attributes');
      run_through_and_sync_form(jQuery(this), values);
    });
  });
  jQuery('input[name="pricerange_from"], input[name="pricerange_to"], input[name="loa_from"], input[name="loa_to"]').each(function () {
    jQuery(this).attr('type', 'text'); //.autoNumeric('init'); 

    jQuery(this).on('input', yt_input_number_formatter);
    jQuery(this).on('change', yt_input_number_formatter);
    yt_ele_input_number_formatter(jQuery(this));
  });
  /*
  
  	jQuery('input[name="pricerange_to"]').attr('type', 'text').autoNumeric('init'); 
  	jQuery('input[name="loa_from"]').attr('type', 'text').autoNumeric('init'); 
  	jQuery('input[name="loa_to"]').attr('type', 'text').autoNumeric('init'); 	*/

  jQuery('form[data-shortcode-attributes]').each(function () {
    //var values={"SetMinMax": {"loa_to": {"min": 0, "max": "51"}}, "FirstArgs": {'loa_to': 51}};
    var values = jQuery(this).data('shortcode-attributes'); // var input_names=Object.keys(values['SetMinMax']);

    var jquery_ele = jQuery(this);

    if (typeof values.SetMinMax != 'undefined') {
      jQuery('input[name], select[name], textarea[name]', jquery_ele).each(function () {
        if (!jQuery(this).attr('dont-touch')) {
          var name = jQuery(this).attr('name');
          var _val = null; //console.log(values['SetMinMax'][name]);

          if (typeof values.SetMinMax[name] != 'undefined') {
            var min_and_max = values.SetMinMax[name];
            jQuery(this).attr(min_and_max);

            if (name == 'pricerange_from' || name == 'pricerange_to' || name == 'loa_to' || name == 'loa_from') {
              jQuery(this).attr('type', 'text').autoNumeric('update', {
                'vMin': min_and_max.min,
                'vMax': min_and_max.max
              });
            }
          }
        }
      });
    }
  }); // Search Accordions

  var acc = document.getElementsByClassName("yt-search-accordion");
  var i;

  for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
      /* Toggle between adding and removing the "active" class,
      to highlight the button that controls the panel */
      this.classList.toggle("active");
      /* Toggle between hiding and showing the active panel */

      var panel = this.nextElementSibling;

      if (panel.style.display === "block") {
        panel.style.display = "none";
      } else {
        panel.style.display = "block";
      }
    });
  }
});

function get_form_data(form_ele) {
  var formData = new FormData(form_ele);
  var fd = Array.from(formData.entries()).reduce(function (memo, pair) {
    return _objectSpread(_objectSpread({}, memo), {}, _defineProperty({}, pair[0], pair[1]));
  }, {});
  return fd;
}
/* ------ Start Of Cookies ------ */


var cookieTime = 360;

function setCookie(c_name, value, exdays, host) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var c_value = escape(value) + (exdays == null ? "" : "; expires=" + exdate.toUTCString());
  document.cookie = c_name + "=" + c_value + (host == null ? "" : ";domain=" + host + "" + ";path=/;");
}

function getCookie(c_name) {
  var i,
      x,
      y,
      ARRcookies = document.cookie.split(";");

  for (i = 0; i < ARRcookies.length; i++) {
    x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
    y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
    x = x.replace(/^\s+|\s+$/g, "");

    if (x == c_name) {
      return unescape(y);
    }
  }
}

function deleteCookie(name, path, domain) {
  if (getCookie(name)) {
    document.cookie = name + "=" + (path ? "; path=" + path : "") + (domain ? "; domain=" + domain : "") + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}
/* ------ End Of Cookies ------ */
// Google Analytics 


function yt_ga_event(category, action, label, value) {
  console.log('ga event');

  if (typeof gtag !== 'undefined') {
    gtag('event', action, {
      'event_category': category,
      'event_label': label,
      'event_value': value,
      'event_callback': function event_callback() {
        console.log('ga event callback (' + action + ' - ' + category + ' - ' + label + ')');
      }
    });
  } else if (typeof ga != 'undefined') {
    ga('send', 'event', category, action, label);
  } else {
    console.warn("Google Analytics Doesnt Seem To Be Installed.");
  }

  if (typeof _paq != 'undefined') {
    _paq.push(['trackEvent', category, action, label, value]);
  }
} // Render Google ReCaptcha


function renderYATCOrecaptcha() {
  /*jQuery('.yatco-g-recaptcha').each(function() {
     grecaptcha.render(jQuery(this)[0], {});
   });*/

  /*jQuery('*:not(.yt-modal) .yatco-g-recaptcha').each(function() {
     grecaptcha.render(jQuery(this)[0], {});
   });*/
} // re-initing modal jquery


jQuery(document).ready(function () {
  jQuery('[data-modal]').click(function () {
    var data_modal = jQuery(this).data('modal');
    yt_ga_event('Modal', 'Open', 'Modal Opened: ' + data_modal, data_modal);
    jQuery(data_modal).yatco_modal({
      closeText: 'X',
      modalClass: 'yt-modal-open',
      closeClass: 'yt-model-close'
    });
  });
}); // Ninja Forms Submit Event To Google

/*jQuery( document ).ready( function( $ ) {
  if (typeof Marionette != 'undefined') {
    // Create a new object for custom validation of a custom field.
    var ninjaFormsSubmittion = Marionette.Object.extend({
      initialize: function() {
        this.listenTo( Backbone.Radio.channel( 'forms' ), 'submit:response', this.actionSubmit );
      },

      actionSubmit: function( response ) {
        console.log( response );
        console.log( response.data.settings.title );

        // Do stuff here.
        if (typeof ga != 'undefined') {
          yt_ga_event('Ninja Form Submittion', response.data.settings.title, 'Submitted');
        }
      }
    });

    // Instantiate our custom field's controller, defined above.
    new ninjaFormsSubmittion();
  }
  else {
    console.log('Marionette is undefined. Just means ninja is not running on page.');
  }
});*/

jQuery(document).ready(function () {
  jQuery('img[src^="https://cloud.yatco.com"]').bind('contextmenu', function (e) {
    return false;
  });
}); // Submitting Newsletter 

function YTPLUGINSubmittingNewsletter(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Newsletter', 'Submitted', 'Form Submit Clicked', ''); // Add New Tracking For Owner VS industry

  _YBA.send_lead({
    'label': 'ClientNewsletter',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Newsletter', 'Success', 'Successful Newsletter Signup', '');
      setCookie('has_newsletter_signup_vONE', 1, 365);
    } else {
      yt_ga_event('Newsletter', 'Unknow Issue', 'Boss Return Issue', '');
    }
  });
}

jQuery(document).ready(function ($) {
  jQuery('.yatco-client-newsletter-signup').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YTPLUGINSubmittingNewsletter);
  });
}); // NEW FUNCTIONS

var yt_lead_common_inputs = ['FirstName', 'LastName', 'Email', 'Phone', 'owner_or_industry'];

function yt_restore_lead_common_inputs(form) {
  yt_lead_common_inputs.forEach(function (input_name) {
    var cookie_val = getCookie('yatco-contact-form-' + input_name);

    if (typeof cookie_val != 'undefined' && cookie_val != 'undefined') {
      jQuery('input[name=' + input_name + ']', form).val(cookie_val);
    }
  });
}

function yt_set_lead_common_inputs(data) {
  yt_lead_common_inputs.forEach(function (key) {
    setCookie('yatco-contact-form-' + key, data[key], 360);
  });
}

function yt_owner_or(ele_form) {
  if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
    yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
  } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
    yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
  }
}

function YT_SubmitVesselLead(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendVesselLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead', ele_form.attr('id') + "_" + formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
}

function YT_SubmitVesselLeadAboutFullSpec(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendVesselLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Successfull Full Spec Vessel Lead', ele_form.attr('id') + "_" + formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
}

function YT_SubmitVesselBrochureLead(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendVesselLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Brochure Lead', ele_form.attr('id') + "_" + formData.VesselID);
      window.open(formData.BrochureUrl, '_blank'); //jQuery('#download_pdf_brochure')[0].src=formData.BrochureUrl;

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
}

function YT_SubmitVesselLeadShowing(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendVesselLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Scheduled A Showing', ele_form.attr('id') + "_" + formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
}

function YT_SubmitVesselLeadQulifiedBroker(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Vessel Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendVesselLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      //yt_ga_event('Vessel Lead', 'Success', 'Successfull Vessel Lead',  ele_form.attr('id')+"_"+formData.VesselID); 
      yt_ga_event('Vessel Lead', 'Success', 'Qulified Broker', ele_form.attr('id') + "_" + formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Vessel Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Vessel Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
}

function YT_SubmitBrokerContactForm(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Broker', 'Submitted', 'Form Submit Clicked', 'broker-' + formData.BrokerID);

  _YBA.send_lead({
    'label': 'BrokerContact',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Broker', 'Success', 'Successfull Broker Message', 'broker-' + formData.BrokerID);
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', 'broker-' + formData.BrokerID);
    } else {
      yt_ga_event('Broker', 'Unknow Issue', 'Boss Return Issue', 'broker-' + formData.BrokerID);
    }
  });
}

function YT_SubmitSoldContactForm(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Vessel Sold Lead', 'Submitted', 'Form Submit Clicked', 'broker-' + formData.BrokerID);

  _YBA.send_lead({
    'label': 'BrokerContact',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Vessel Sold Lead', 'Success', 'Successfull Sold Message', 'broker-' + formData.BrokerID);
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', 'broker-' + formData.BrokerID);
    } else {
      yt_ga_event('Vessel Sold Lead', 'Unknow Issue', 'Boss Return Issue', 'broker-' + formData.BrokerID);
    }
  });
}

function YT_SubmitServiceMlsContentForm(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Services MLS', 'Submitted', 'Form Submit Clicked', '');

  _YBA.send_lead({
    'label': 'ServicesMlsContact',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Services MLS', 'Success', 'Successfull Sold Message', '');
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Services MLS', 'AKI SPAM', 'Aki Spam Return Issue', '');
    } else {
      yt_ga_event('Services MLS', 'Unknow Issue', 'Boss Return Issue', '');
    }
  });
}

function YGC_SubmittingContactForm(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');

  _YBA.send_lead({
    'label': 'SendGeneralContact',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).removeAttr('disabled');
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));
  });
}

function SubmittingContactForm_Success(r, ele_form) {
  jQuery('[type=submit]', ele_form).removeAttr('disabled');

  if (typeof r.error != 'undefined') {
    alert(r.error);
  } else if (r.ResponseCode == 0 || r.Response == 'SPAM') {
    if (typeof _YCT != 'undefined') {
      _YCT.formSubmit(jQuery('input[type=email]', ele_form).val(), r.ExtraData);
    }

    jQuery('.hide-after-submit', ele_form).hide();
    jQuery('.form-success-message', ele_form).show();
  }
}

function GA_TrackTele(VesselID, kind) {
  yt_ga_event('Telephone', 'Click', kind, ''); //yt_ga_event('send', 'event', VesselID, 'Telephone', kind);

  if (typeof _YCT != 'undefined') {
    _YCT.forsale.phone(VesselID);
  }
}

function YT_SubmitCharterLead(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Charter Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendCharterLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).removeAttr('disabled');
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Charter Lead', 'Success', 'Successfull Charter Lead', ele_form.attr('id') + "_" + formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Charter Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
}

function YT_SubmitCharterFullSpecLead(e) {
  e.preventDefault();
  var ele_form = jQuery(this);
  var formData = get_form_data(ele_form[0]);
  yt_set_lead_common_inputs(formData);
  jQuery('[type=submit]', ele_form).attr('disabled', 'disabled');
  jQuery('[type=submit]', ele_form).data('og-val', jQuery('[type=submit]', ele_form).val());
  jQuery('[type=submit]', ele_form).val('Processing...');
  yt_ga_event('Charter Lead', 'Submitted', 'Form Submit Clicked', ele_form.attr('id') + "_" + formData.VesselID);

  _YBA.send_lead({
    'label': 'SendCharterLead',
    'form_data': formData
  }).then(function (r_data) {
    SubmittingContactForm_Success(r_data, ele_form);
    jQuery('[type=submit]', ele_form).removeAttr('disabled');
    jQuery('[type=submit]', ele_form).val(jQuery('[type=submit]', ele_form).data('og-val'));

    if (r_data.ResponseCode == 0) {
      yt_ga_event('Charter Lead', 'Success', 'Successfull Charter Lead', ele_form.attr('id') + "_" + formData.VesselID);

      if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'owner') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Owner', '');
      } else if (jQuery('input[name=owner_or_industry]:checked', ele_form).val() == 'industry') {
        yt_ga_event('Charter Lead', 'Owner OR Industry', 'Industry', '');
      }
    } else if (typeof r_data.spam_aki != 'undefined') {
      yt_ga_event('Vessel Lead', 'AKI SPAM', 'Aki Spam Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    } else {
      yt_ga_event('Charter Lead', 'Unknow Issue', 'Boss Return Issue', ele_form.attr('id') + "_" + formData.VesselID);
    }
  });
} // NEW ATTACH


jQuery(document).ready(function () {
  // VESSEL LEAD FORM
  jQuery('.contact-broker-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitVesselLead);
  }); // FULL SPEC VESSEL LEAD FORM

  jQuery('.contact-broker-about-full-spec-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitVesselLeadAboutFullSpec);
  }); // BROCHURE VESSEL LEAD FORM

  jQuery('.contact-wanted-forsale-brochure').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitVesselBrochureLead);
  }); // VESSEL LEAD - SHOWING - FORM

  jQuery('.contact-broker-about-showing').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitVesselLeadShowing);
  }); // VESSEL LEAD - Qulified - FORM

  jQuery('.contact-qulified-broker').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitVesselLeadQulifiedBroker);
  }); // BROKER CONTACT

  jQuery('.contact-broker-for-real-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitBrokerContactForm);
  }); // Broker sold lead

  jQuery('.sold-contact-broker-for-real-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitSoldContactForm);
  }); // CHARTER LEAD FORM

  jQuery('.charter-lead-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitCharterLead);
  }); // CHARTER FULL SPEC LEAD FORM

  jQuery('.charter-full-spec-lead-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitCharterLead);
  }); // GENERAL CONTACT FORM

  jQuery('.yatco-general-contact-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YGC_SubmittingContactForm);
  }); // SERVICES MLS CONTACT FORM

  jQuery('.yatco-services-mls-contact-form').each(function () {
    yt_restore_lead_common_inputs(jQuery(this));
    jQuery(this).on('submit', YT_SubmitServiceMlsContentForm);
  });
});
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIllDVC5qcyIsIm1vZGFsLmpzIiwic2ltcGxlLXBhZ2luYXRpb24uanMiLCJyZWFkbW9yZS5qcyIsInlhdGNvLWJvc3MtYXBpLWNsaWVudC5qcyIsInlhY2h0cy1maWxsaW5nLWluLWlucHV0cy5qcyIsInlhY2h0cy1maWxsaW5nLWluLWNhdGVnb3JpZXMuanMiLCJ5YWNodHMtZmlsbGluZy1pbi1sb2NhdGlvbnMuanMiLCJ5YWNodHMuanMiLCJ3aGF0ZXZlci5qcyIsImxlYWRzLmpzIl0sIm5hbWVzIjpbIl9ZQ1QiLCJfZGVidWciLCJfcHJvcGVydGllcyIsImFwaUVQIiwiX2VuZFBvaW50cyIsInNpZCIsInB2IiwicHZlIiwiZnMiLCJ2aSIsInZ2IiwiY2kiLCJjdiIsImZndiIsImZ2cCIsIl9oZWxwZXJzIiwidXJsIiwiZXAiLCJxc09iaiIsInFzIiwia2V5IiwicHVzaCIsImVuY29kZVVSSUNvbXBvbmVudCIsImpvaW4iLCJnZXQiLCJ1cmkiLCJjYWxsYmFja0Z1bmN0aW9uIiwiaW1nIiwiSW1hZ2UiLCJhZGRFdmVudExpc3RlbmVyIiwic3JjIiwicHZPYmoiLCJyZWZlcnJlciIsImRvY3VtZW50IiwiaG9zdCIsIndpbmRvdyIsImxvY2F0aW9uIiwicHJvdG9jb2wiLCJwYXRoIiwicGF0aG5hbWUiLCJ0aXRsZSIsInJlcyIsInNjcmVlbiIsIndpZHRoIiwiaGVpZ2h0IiwibGFuZyIsIm5hdmlnYXRvciIsImxhbmd1YWdlIiwidWEiLCJ1c2VyQWdlbnQiLCJhcmd0b2xpc3QiLCJsIiwiYXJnTGlzdCIsImlkTGlzdCIsImxlbmd0aCIsImkiLCJzcGxpdCIsIl9nZXRDb29raWUiLCJpbmRleE9mIiwicmVwbGFjZSIsImgiLCJfbG9nIiwiX0luaXQiLCJlbWwiLCJleHRyYURhdGEiLCJlbWFpbCIsImFyZ3VtZW50cyIsInYiLCJ2aWQiLCJmb3JtU3VibWl0IiwiZm9yc2FsZSIsImltcHJlc3Npb25zIiwidmlldyIsImdhbGxlcnkiLCJwaG9uZSIsImNoYXJ0ZXIiLCJmYWN0b3J5IiwibW9kdWxlIiwiZXhwb3J0cyIsInJlcXVpcmUiLCJqUXVlcnkiLCIkIiwidW5kZWZpbmVkIiwibW9kYWxzIiwiZ2V0Q3VycmVudCIsInNlbGVjdEN1cnJlbnQiLCJzZWxlY3RlZCIsIiRibG9ja2VyIiwidG9nZ2xlQ2xhc3MiLCJ5YXRjb19tb2RhbCIsImVsIiwib3B0aW9ucyIsInJlbW92ZSIsInRhcmdldCIsIiRib2R5IiwiZXh0ZW5kIiwiZGVmYXVsdHMiLCJkb0ZhZGUiLCJpc05hTiIsInBhcnNlSW50IiwiZmFkZUR1cmF0aW9uIiwiY2xvc2VFeGlzdGluZyIsImlzQWN0aXZlIiwiY2xvc2UiLCJpcyIsImF0dHIiLCJhbmNob3IiLCJ0ZXN0IiwiJGVsbSIsImFwcGVuZCIsIm9wZW4iLCJldmVudCIsIm1vZGFsIiwiZWxtIiwic2hvd1NwaW5uZXIiLCJ0cmlnZ2VyIiwiQUpBWF9TRU5EIiwiZG9uZSIsImh0bWwiLCJBSkFYX1NVQ0NFU1MiLCJjdXJyZW50IiwiZW1wdHkiLCJvbiIsIkNMT1NFIiwiaGlkZVNwaW5uZXIiLCJBSkFYX0NPTVBMRVRFIiwiZmFpbCIsIkFKQVhfRkFJTCIsInBvcCIsInByb3RvdHlwZSIsImNvbnN0cnVjdG9yIiwibSIsImJsb2NrIiwiYmx1ciIsInNldFRpbWVvdXQiLCJzaG93IiwiZmFkZURlbGF5Iiwib2ZmIiwid2hpY2giLCJlc2NhcGVDbG9zZSIsImNsaWNrQ2xvc2UiLCJjbGljayIsImUiLCJ1bmJsb2NrIiwiaGlkZSIsIkJFRk9SRV9CTE9DSyIsIl9jdHgiLCJjc3MiLCJibG9ja2VyQ2xhc3MiLCJhcHBlbmRUbyIsImFuaW1hdGUiLCJvcGFjaXR5IiwiQkxPQ0siLCJub3ciLCJmYWRlT3V0IiwiYmluZCIsImNoaWxkcmVuIiwiQkVGT1JFX09QRU4iLCJzaG93Q2xvc2UiLCJjbG9zZUJ1dHRvbiIsImNsb3NlQ2xhc3MiLCJjbG9zZVRleHQiLCJhZGRDbGFzcyIsIm1vZGFsQ2xhc3MiLCJkaXNwbGF5IiwiT1BFTiIsIkJFRk9SRV9DTE9TRSIsIl90aGlzIiwiQUZURVJfQ0xPU0UiLCJzcGlubmVyIiwic3Bpbm5lckh0bWwiLCIkYW5jaG9yIiwicHJldmVudERlZmF1bHQiLCJmbiIsIm1ldGhvZHMiLCJpbml0IiwibyIsIml0ZW1zIiwiaXRlbXNPblBhZ2UiLCJwYWdlcyIsImRpc3BsYXllZFBhZ2VzIiwiZWRnZXMiLCJjdXJyZW50UGFnZSIsInVzZUFuY2hvcnMiLCJocmVmVGV4dFByZWZpeCIsImhyZWZUZXh0U3VmZml4IiwicHJldlRleHQiLCJuZXh0VGV4dCIsImVsbGlwc2VUZXh0IiwiZWxsaXBzZVBhZ2VTZXQiLCJjc3NTdHlsZSIsImxpc3RTdHlsZSIsImxhYmVsTWFwIiwic2VsZWN0T25DbGljayIsIm5leHRBdEZyb250IiwiaW52ZXJ0UGFnZU9yZGVyIiwidXNlU3RhcnRFZGdlIiwidXNlRW5kRWRnZSIsIm9uUGFnZUNsaWNrIiwicGFnZU51bWJlciIsIm9uSW5pdCIsInNlbGYiLCJNYXRoIiwiY2VpbCIsImhhbGZEaXNwbGF5ZWQiLCJlYWNoIiwiZGF0YSIsIl9kcmF3IiwiY2FsbCIsInNlbGVjdFBhZ2UiLCJwYWdlIiwiX3NlbGVjdFBhZ2UiLCJwcmV2UGFnZSIsIm5leHRQYWdlIiwiZ2V0UGFnZXNDb3VudCIsInNldFBhZ2VzQ291bnQiLCJjb3VudCIsImdldEN1cnJlbnRQYWdlIiwiZGVzdHJveSIsImRyYXdQYWdlIiwicmVkcmF3IiwiZGlzYWJsZSIsImRpc2FibGVkIiwiZW5hYmxlIiwidXBkYXRlSXRlbXMiLCJuZXdJdGVtcyIsIl9nZXRQYWdlcyIsInVwZGF0ZUl0ZW1zT25QYWdlIiwiZ2V0SXRlbXNPblBhZ2UiLCJpbnRlcnZhbCIsIl9nZXRJbnRlcnZhbCIsInRhZ05hbWUiLCJwcm9wIiwiJHBhbmVsIiwiX2FwcGVuZEl0ZW0iLCJ0ZXh0IiwiY2xhc3NlcyIsInN0YXJ0IiwiZW5kIiwibWluIiwiYmVnaW4iLCJtYXgiLCJfZWxsaXBzZUNsaWNrIiwicGFnZUluZGV4Iiwib3B0cyIsIiRsaW5rIiwiJGxpbmtXcmFwcGVyIiwiJHVsIiwiZmluZCIsIiRlbGxpcCIsInBhcmVudCIsInJlbW92ZUNsYXNzIiwiJHRoaXMiLCJ2YWwiLCJwcmV2IiwiZm9jdXMiLCJzdG9wUHJvcGFnYXRpb24iLCJrZXl1cCIsInBhZ2luYXRpb24iLCJtZXRob2QiLCJjaGFyQXQiLCJhcHBseSIsIkFycmF5Iiwic2xpY2UiLCJlcnJvciIsIndlYnBhY2tVbml2ZXJzYWxNb2R1bGVEZWZpbml0aW9uIiwicm9vdCIsImRlZmluZSIsImFtZCIsIm1vZHVsZXMiLCJpbnN0YWxsZWRNb2R1bGVzIiwiX193ZWJwYWNrX3JlcXVpcmVfXyIsIm1vZHVsZUlkIiwiYyIsImQiLCJuYW1lIiwiZ2V0dGVyIiwiT2JqZWN0IiwiZGVmaW5lUHJvcGVydHkiLCJlbnVtZXJhYmxlIiwiciIsIlN5bWJvbCIsInRvU3RyaW5nVGFnIiwidmFsdWUiLCJ0IiwibW9kZSIsIl9fZXNNb2R1bGUiLCJucyIsImNyZWF0ZSIsIm4iLCJnZXREZWZhdWx0IiwiZ2V0TW9kdWxlRXhwb3J0cyIsIm9iamVjdCIsInByb3BlcnR5IiwiaGFzT3duUHJvcGVydHkiLCJwIiwicyIsIl9jbGFzc0NhbGxDaGVjayIsImluc3RhbmNlIiwiQ29uc3RydWN0b3IiLCJUeXBlRXJyb3IiLCJfZGVmaW5lUHJvcGVydGllcyIsInByb3BzIiwiZGVzY3JpcHRvciIsImNvbmZpZ3VyYWJsZSIsIndyaXRhYmxlIiwiX2NyZWF0ZUNsYXNzIiwicHJvdG9Qcm9wcyIsInN0YXRpY1Byb3BzIiwiX3R5cGVvZjIiLCJvYmoiLCJpdGVyYXRvciIsIl90eXBlb2YiLCJfX3dlYnBhY2tfZXhwb3J0c19fIiwiX2JhYmVsX3J1bnRpbWVfaGVscGVyc19jbGFzc0NhbGxDaGVja19fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMF9fIiwiX2JhYmVsX3J1bnRpbWVfaGVscGVyc19jbGFzc0NhbGxDaGVja19fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMF9fX2RlZmF1bHQiLCJfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NyZWF0ZUNsYXNzX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8xX18iLCJfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NyZWF0ZUNsYXNzX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8xX19fZGVmYXVsdCIsIl9iYWJlbF9ydW50aW1lX2hlbHBlcnNfdHlwZW9mX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8yX18iLCJfYmFiZWxfcnVudGltZV9oZWxwZXJzX3R5cGVvZl9fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMl9fX2RlZmF1bHQiLCJ1bmlxdWVJZENvdW50ZXIiLCJpc0Nzc0VtYmVkZGVkRm9yIiwicmVtb3ZlUG9seWZpbGwiLCJhcnIiLCJmb3JFYWNoIiwiaXRlbSIsInBhcmVudE5vZGUiLCJyZW1vdmVDaGlsZCIsIkVsZW1lbnQiLCJDaGFyYWN0ZXJEYXRhIiwiRG9jdW1lbnRUeXBlIiwiY2FsbGJhY2siLCJzY29wZSIsIl9sZW4iLCJvYmplY3RzIiwiX2tleSIsImhhc1Byb3AiLCJjaGlsZCIsImFyZ3MiLCJrZXlzIiwiYzEiLCJzaGlmdCIsInAxIiwidW5zaGlmdCIsImRlYm91bmNlIiwiZnVuYyIsIndhaXQiLCJpbW1lZGlhdGUiLCJ0aW1lb3V0IiwiZGVib3VuY2VkRnVuYyIsIl9sZW4yIiwiX2tleTIiLCJjYWxsTm93IiwibGF0ZXIiLCJjbGVhclRpbWVvdXQiLCJ1bmlxdWVJZCIsImNvbmNhdCIsInNldEJveEhlaWdodHMiLCJlbGVtZW50Iiwic3R5bGUiLCJleHBhbmRlZEhlaWdodCIsImdldEJvdW5kaW5nQ2xpZW50UmVjdCIsImNzc01heEhlaWdodCIsImdldENvbXB1dGVkU3R5bGUiLCJtYXhIZWlnaHQiLCJkZWZhdWx0SGVpZ2h0IiwicmVhZG1vcmUiLCJjb2xsYXBzZWRIZWlnaHQiLCJjcmVhdGVFbGVtZW50RnJvbVN0cmluZyIsImh0bWxTdHJpbmciLCJkaXYiLCJjcmVhdGVFbGVtZW50IiwiaW5uZXJIVE1MIiwiZmlyc3RDaGlsZCIsImVtYmVkQ1NTIiwic2VsZWN0b3IiLCJzdHlsZXMiLCJibG9ja0NTUyIsInNwZWVkIiwidSIsInR5cGUiLCJzdHlsZVNoZWV0IiwiY3NzVGV4dCIsImFwcGVuZENoaWxkIiwiY3JlYXRlVGV4dE5vZGUiLCJnZXRFbGVtZW50c0J5VGFnTmFtZSIsImJ1aWxkVG9nZ2xlIiwibGluayIsImNsaWNrSGFuZGxlciIsInRvZ2dsZSIsInRvZ2dsZUxpbmsiLCJzZXRBdHRyaWJ1dGUiLCJpZCIsImlzRW52aXJvbm1lbnRTdXBwb3J0ZWQiLCJxdWVyeVNlbGVjdG9yQWxsIiwicmVzaXplQm94ZXMiLCJlbGVtZW50cyIsImV4cGFuZGVkIiwiZ2V0QXR0cmlidXRlIiwiaGVpZ2h0TWFyZ2luIiwibW9yZUxpbmsiLCJsZXNzTGluayIsInN0YXJ0T3BlbiIsInNvdXJjZU9yZGVyIiwiYmxvY2tQcm9jZXNzZWQiLCJiZWZvcmVUb2dnbGUiLCJhZnRlclRvZ2dsZSIsIlJlYWRtb3JlIiwiX3RoaXMyIiwiX2xlbjMiLCJfa2V5MyIsIm5vZGVOYW1lIiwiaW5zdGFuY2VTZWxlY3RvciIsImNsYXNzTGlzdCIsImFkZCIsInN1YnN0ciIsInRvZ2dsZUVsZW1lbnQiLCJpbnNlcnRCZWZvcmUiLCJuZXh0U2libGluZyIsIl90aGlzMyIsInF1ZXJ5U2VsZWN0b3IiLCJuZXdIZWlnaHQiLCJzaG91bGRDb250aW51ZVRvZ2dsZSIsInRyYW5zaXRpb25lbmRIYW5kbGVyIiwidHJhbnNpdGlvbkV2ZW50IiwicmVtb3ZlRXZlbnRMaXN0ZW5lciIsInJlcGxhY2VDaGlsZCIsIkVycm9yIiwiX3RoaXM0IiwiZmlsdGVyIiwicmVtb3ZlQXR0cmlidXRlIiwiVkVSU0lPTiIsIl9ZQkEiLCJZQUNIVFMiLCJGT1JNX1NFTEVDVF9PUFRJT05TIiwiY2FsbF9hcGkiLCJwYXNzaW5nX2RhdGEiLCJ4aHR0cCIsIlhNTEh0dHBSZXF1ZXN0IiwiUHJvbWlzZSIsInJlc29sdmUiLCJyZWplY3QiLCJvbnJlYWR5c3RhdGVjaGFuZ2UiLCJyZWFkeVN0YXRlIiwic3RhdHVzIiwicmVzcG9uc2VEYXRhIiwiSlNPTiIsInBhcnNlIiwicmVzcG9uc2VUZXh0Iiwic2VhcmNoUGFyYW1zIiwiVVJMU2VhcmNoUGFyYW1zIiwic2V0IiwiX3lhdGNvX3dwX3VybCIsIl95YXRjb193cF9yZXN0X3VybCIsInRvU3RyaW5nIiwic2VuZCIsInNldFJlcXVlc3RIZWFkZXIiLCJzdHJpbmdpZnkiLCJsYWJlbF9vcHRpb25zIiwibGFiZWwiLCJpc0FycmF5IiwieWFjaHRfc2VhcmNoIiwicGFyYW1zIiwieWFjaHRfZGV0YWlscyIsInZlc3NlbF9pZCIsImNoYXJ0ZXJfc2VhcmNoIiwiYnJva2VyX3NlYXJjaCIsImJyb2tlcmFnZV9zZWFyY2giLCJzZW5kX2xlYWQiLCJzZXR1cEZpbHRlcmVkQXV0b2NvbXBsZXRlIiwiaW5wdXRTZWxlY3RvciIsImRhdGFsaXN0SWQiLCJkYXRhU291cmNlIiwiZ2V0VmFsdWVGbiIsImlucHV0IiwiZGF0YWxpc3QiLCJ0eXBpbmdUaW1lciIsImlzRmlsdGVyaW5nIiwic2VhcmNoVGVybSIsInRvTG93ZXJDYXNlIiwidHJpbSIsIm1hdGNoZXMiLCJmcmFnbWVudCIsImNyZWF0ZURvY3VtZW50RnJhZ21lbnQiLCJvcHQiLCJ5dF9maWxsX29wdGlvbnMiLCJlbGUiLCJsaXN0IiwibmV3X29wdGlvbiIsInl0X2luaXRfc3VwZXJfc2VsZWN0b3IiLCJhdHRyX2xhYmVsIiwiY3VyX2lucHV0IiwiY29uc29sZSIsImxvZyIsInRoZW4iLCJ5dF9kYXRhX2ZpbGxzIiwieXRfZmlsbF9zdXBlcl9vcHRpb25zIiwieXRfaW5pdF9zdXBlcl9zZWxlY3Rvcl9zZWFyY2hfYW5kX3VuY2hlY2siLCJZVF9TZWxlY3RBZnRlckxvYWRpbmciLCJoYXNDbGFzcyIsInVuYmluZCIsImZpbHRlcmVkX3Jlc3VsdHMiLCJsb3dlcl92YWwiLCJpbnB1dF92YWxfbG93ZXIiLCJ5dF9maWxsX3N1cGVyX29wdGlvbnNfaGlkZSIsInByZXBlbmQiLCJpbnB1dF9uYW1lIiwiY2xhc3NOYW1lIiwieXRfZmlsbF9zdXBlcl9vcHRpb25zX3dpdGhfcHJvbWlzZSIsImxpc3RfanVzdF9pZHMiLCJtYXAiLCJpbnB1dF92YWwiLCJHZXRDb3VudHJ5TGlzdCIsIlJlZ2lvbklkIiwibHN0IiwiR2V0Q291bnRyeUxpc3RfU3VjY2VzcyIsIkdlb0xpc3RBY3RpdmVWZXNzZWxSZWdpb25Db3VudHJpZXMiLCJkZCIsIkNvdW50cnkiLCJHZXRTdGF0ZUxpc3QiLCJjb3VudHJ5U0VPIiwiR2V0U3RhdGVMaXN0X1N1Y2Nlc3MiLCJTdGF0ZXMiLCJHZXRHZW9TdGF0ZUxpc3QiLCJDb3VudHJ5SWQiLCJHZW9MaXN0QWN0aXZlVmVzc2VsU3RhdGVzIiwiR2V0R2VvQ2l0eUxpc3QiLCJTdGF0ZUlkIiwiR2V0Q2l0eUxpc3RfU3VjY2VzcyIsIkdlb0xpc3RBY3RpdmVDaXRpZXMiLCJHZXRHZW9Db3VudHJ5Q2l0eUxpc3QiLCJHZW9MaXN0QWN0aXZlVmVzc2VsQ291bnRyeUNpdGllcyIsIkdldEFzc29jaWF0aW9uUmVnaW9ucyIsImRkZCIsIkFzc29jaWF0aW9uUmVnaW9ucyIsInlhdGNvX2RhdGFfZmlsbF9vcHRpb25zIiwicm9vdF9lbGUiLCJsaXN0X29mX2ZpbGxzIiwiaW5kZXgiLCJyZWFkeSIsInZhbHVlcyIsInJ1bl90aHJvdWdoX2FuZF9zeW5jX2Zvcm0iLCJnZXRfZm9ybV9kYXRhIiwicmVnaW9uSUQiLCJyZW1vdmVBdHRyIiwiQ291bnRyeUlEIiwiQWN0aXZlQnVpbGRlcnMiLCJWZXNzZWxOYW1lcyIsImIiLCJERENoYXJ0ZXJEZXN0aW5hdGlvbnMiLCJzb3J0IiwieXRfY2F0ZWdvcnlfYmFzZWRfb2ZmX29mX3R5cGUiLCJDYXRlZ29yaWVzIiwiY2hhbmdlIiwibWFpbklEIiwiU3ViQ2F0ZWdvcmllcyIsIlNhaWxDYXRlZ29yaWVzIiwieXRfc3VwZXJfc2VsZWN0b3JfbG9jYXRpb25fc2VhcmNoIiwiR2VvTGlzdFJlZ2lvbkNvdW50cmllcyIsIkdlb0xpc3RTdGF0ZXMiLCJHZW9MaXN0Q2l0aWVzIiwiR2VvTGlzdFJlZ2lvbnNXaXRoQWN0aXZlQ291bnRyaWVzIiwiR2VvTGlzdENvdW50cnlDaXRpZXMiLCJ5dF9zeW5jX2lucHV0IiwiZWxlX2lucHV0IiwiX3ZhbCIsInl0X2lucHV0X251bWJlcl9mb3JtYXR0ZXIiLCJkZWNvZGVVUklDb21wb25lbnQiLCJudW1iZXJfc3RyIiwibWF0Y2giLCJmb3JtYXR0ZWQiLCJJbnRsIiwiTnVtYmVyRm9ybWF0IiwiZm9ybWF0IiwiTmFOIiwieXRfZWxlX2lucHV0X251bWJlcl9mb3JtYXR0ZXIiLCJqX2VsZSIsImpxdWVyeV9lbGUiLCJmb3JtX25hbWUiLCIkaW5wdXQiLCJpc0VsZW1lbnRJblZpZXdwb3J0IiwicmVjdCIsInRvcCIsImxlZnQiLCJib3R0b20iLCJpbm5lckhlaWdodCIsImRvY3VtZW50RWxlbWVudCIsImNsaWVudEhlaWdodCIsInJpZ2h0IiwiaW5uZXJXaWR0aCIsImNsaWVudFdpZHRoIiwicmVzZXRTZWFyY2hGb3JtIiwicmVzZXQiLCJwYWdlX3NpemUiLCJwYWdlX2luZGV4IiwieXRfanNfcmVzZXRTZWFyY2hGb3JtQW5kUmVzdWx0IiwiZ2V0X3Zlc3NlbHNfYW5kX3JlbmRlciIsIm9wZW5Db250YWN0TW9kYWwiLCJNTFNJRCIsIkNvbXBhbnlJRCIsIkNvbXBhbnlOYW1lIiwiQnJva2VyTmFtZSIsInl0X2dhX2V2ZW50Iiwib3BlblZkQ29udGFjdE1vZGFsIiwib3BlbkJyb2tlckNvbnRhY3RNb2RhbCIsIkJyb2tlcklEIiwib3BlbkNoYXJ0ZXJMZWFkTW9kYWwiLCJnYSIsInNlYXJjaCIsInNob3J0Y29kZV9mb3JtX2F0dHJfZGF0YSIsInNob3J0Y29kZV9hdHRyX2RhdGEiLCJvdmVycmlkZV93aXRoX2F0dHJzIiwiZXhwYW5kX3NlYXJjaF9pc19vcGVuIiwib3BlbnNlYXJjaGZvcm0iLCJoYXMiLCJib2R5Iiwic2Nyb2xsVG9wIiwib2Zmc2V0IiwibW9yZV9saW5rX2h0bWwiLCJsZXNzX2xpbmtfaHRtbCIsImZvcm1faWQiLCJzdWJtaXQiLCJTZXRNaW5NYXgiLCJtaW5fYW5kX21heCIsImF1dG9OdW1lcmljIiwiYWNjIiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsInBhbmVsIiwibmV4dEVsZW1lbnRTaWJsaW5nIiwiZm9ybV9lbGUiLCJmb3JtRGF0YSIsIkZvcm1EYXRhIiwiZmQiLCJmcm9tIiwiZW50cmllcyIsInJlZHVjZSIsIm1lbW8iLCJwYWlyIiwiY29va2llVGltZSIsInNldENvb2tpZSIsImNfbmFtZSIsImV4ZGF5cyIsImV4ZGF0ZSIsIkRhdGUiLCJzZXREYXRlIiwiZ2V0RGF0ZSIsImNfdmFsdWUiLCJlc2NhcGUiLCJ0b1VUQ1N0cmluZyIsImNvb2tpZSIsImdldENvb2tpZSIsIngiLCJ5IiwiQVJSY29va2llcyIsInVuZXNjYXBlIiwiZGVsZXRlQ29va2llIiwiZG9tYWluIiwiY2F0ZWdvcnkiLCJhY3Rpb24iLCJndGFnIiwid2FybiIsIl9wYXEiLCJyZW5kZXJZQVRDT3JlY2FwdGNoYSIsImRhdGFfbW9kYWwiLCJZVFBMVUdJTlN1Ym1pdHRpbmdOZXdzbGV0dGVyIiwiZWxlX2Zvcm0iLCJ5dF9zZXRfbGVhZF9jb21tb25faW5wdXRzIiwicl9kYXRhIiwiU3VibWl0dGluZ0NvbnRhY3RGb3JtX1N1Y2Nlc3MiLCJSZXNwb25zZUNvZGUiLCJ5dF9yZXN0b3JlX2xlYWRfY29tbW9uX2lucHV0cyIsInl0X2xlYWRfY29tbW9uX2lucHV0cyIsImZvcm0iLCJjb29raWVfdmFsIiwieXRfb3duZXJfb3IiLCJZVF9TdWJtaXRWZXNzZWxMZWFkIiwiVmVzc2VsSUQiLCJzcGFtX2FraSIsIllUX1N1Ym1pdFZlc3NlbExlYWRBYm91dEZ1bGxTcGVjIiwiWVRfU3VibWl0VmVzc2VsQnJvY2h1cmVMZWFkIiwiQnJvY2h1cmVVcmwiLCJZVF9TdWJtaXRWZXNzZWxMZWFkU2hvd2luZyIsIllUX1N1Ym1pdFZlc3NlbExlYWRRdWxpZmllZEJyb2tlciIsIllUX1N1Ym1pdEJyb2tlckNvbnRhY3RGb3JtIiwiWVRfU3VibWl0U29sZENvbnRhY3RGb3JtIiwiWVRfU3VibWl0U2VydmljZU1sc0NvbnRlbnRGb3JtIiwiWUdDX1N1Ym1pdHRpbmdDb250YWN0Rm9ybSIsImFsZXJ0IiwiUmVzcG9uc2UiLCJFeHRyYURhdGEiLCJHQV9UcmFja1RlbGUiLCJraW5kIiwiWVRfU3VibWl0Q2hhcnRlckxlYWQiLCJZVF9TdWJtaXRDaGFydGVyRnVsbFNwZWNMZWFkIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7O0FBQUEsSUFBQUEsSUFBQSxHQUFBLFlBQUE7RUFFQTtFQUNBLElBQUFDLE1BQUEsR0FBQSxLQUFBO0VBQ0EsSUFBQUMsV0FBQSxHQUFBO0lBQ0FDLEtBQUEsRUFBQUYsTUFBQSxHQUFBLHdDQUFBLEdBQUE7RUFEQSxDQUFBO0VBR0EsSUFBQUcsVUFBQSxHQUFBO0lBQ0FDLEdBQUEsRUFBQSxNQURBO0lBQ0E7SUFDQUMsRUFBQSxFQUFBLEtBRkE7SUFFQTtJQUNBQyxHQUFBLEVBQUEsTUFIQTtJQUdBO0lBQ0FDLEVBQUEsRUFBQSxLQUpBO0lBSUE7SUFDQUMsRUFBQSxFQUFBLEtBTEE7SUFLQTtJQUNBQyxFQUFBLEVBQUEsS0FOQTtJQU1BO0lBQ0FDLEVBQUEsRUFBQSxLQVBBO0lBT0E7SUFDQUMsRUFBQSxFQUFBLEtBUkE7SUFRQTtJQUNBQyxHQUFBLEVBQUEsTUFUQTtJQVNBO0lBQ0FDLEdBQUEsRUFBQSxNQVZBLENBVUE7O0VBVkEsQ0FBQSxDQVBBLENBb0JBOztFQUNBLElBQUFDLFFBQUEsR0FBQTtJQUNBQyxHQUFBLEVBQUEsYUFBQUMsRUFBQSxFQUFBQyxLQUFBLEVBQUE7TUFDQUEsS0FBQSxHQUFBQSxLQUFBLElBQUEsRUFBQTtNQUNBLElBQUFDLEVBQUEsR0FBQSxFQUFBOztNQUNBLEtBQUEsSUFBQUMsR0FBQSxJQUFBRixLQUFBLEVBQUE7UUFBQUMsRUFBQSxDQUFBRSxJQUFBLENBQUFELEdBQUEsR0FBQSxHQUFBLEdBQUFFLGtCQUFBLENBQUFKLEtBQUEsQ0FBQUUsR0FBQSxDQUFBLENBQUE7TUFBQTs7TUFDQSxPQUFBbEIsV0FBQSxDQUFBQyxLQUFBLEdBQUFjLEVBQUEsR0FBQSxH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QUEsNkJBQUEsQ0FBQSxDQUFBNkgsSUFBQSxDQUFBLFlBQUE7SUFFQWlkLDZCQUFBLENBQUE5a0IsTUFBQSxDQUFBLElBQUEsQ0FBQSxDQUFBO0lBRUFBLE1BQUEsQ0FBQSxJQUFBLENBQUEsQ0FBQTBDLEVBQUEsQ0FBQSxRQUFBLEVBQUFvakIseUJBQUE7RUFFQSxDQU5BLEVBbkZBLENBMkZBOztFQUNBOWxCLE1BQUEsQ0FBQSxrQ0FBQSxDQUFBLENBQUE2SCxJQUFBLENBQUEsWUFBQTtJQUVBaWQsNkJBQUEsQ0FBQTlrQixNQUFBLENBQUEsSUFBQSxDQUFBLENBQUE7SUFFQUEsTUFBQSxDQUFBLElBQUEsQ0FBQSxDQUFBMEMsRUFBQSxDQUFBLFFBQUEsRUFBQW1qQiw4QkFBQTtFQUVBLENBTkE7QUFRQSxDQXBHQSIsImZpbGUiOiJjbGllbnQtc2lkZS5qcyIsInNvdXJjZXNDb250ZW50IjpbInZhciBfWUNUID0gKGZ1bmN0aW9uICgpIHtcclxuXHJcbiAgICAvL3ByaXZhdGUgcHJvcGVydGllczpcclxuICAgIHZhciBfZGVidWcgPSBmYWxzZTtcclxuICAgIHZhciBfcHJvcGVydGllcyA9IHtcclxuICAgICAgICBhcGlFUDogKF9kZWJ1ZyA/ICdodHRwOi8vbG9jYWxob3N0OjUzNjA2L2FwaS92MS90cmFja2luZycgOiAnaHR0cHM6Ly9hbmFseXRpY3MueWF0Y28uY29tL2FwaS92MS90cmFja2luZycpXHJcbiAgICB9O1xyXG4gICAgdmFyIF9lbmRQb2ludHMgPSB7XHJcbiAgICAgICAgc2lkOiAnL3NpZCcsIC8vc2l0ZSBpZFxyXG4gICAgICAgIHB2OiAnL3B2JywgLy9wYWdlIHZpZXdcclxuICAgICAgICBwdmU6ICcvcHZlJywgLy9wYWdlIHZpZXcgZW5kXHJcbiAgICAgICAgZnM6ICcvZnMnLCAvL2Zvcm0gc3VibWl0XHJcbiAgICAgICAgdmk6ICcvdmknLCAvL3Zlc3NlbCBpbXByZXNzaW9uc1xyXG4gICAgICAgIHZ2OiAnL3Z2JywgLy92ZXNzZWwgdmlld1xyXG4gICAgICAgIGNpOiAnL2NpJywgLy9jaGFydGVyIGltcHJlc3Npb25zXHJcbiAgICAgICAgY3Y6ICcvY3YnLCAvL2NoYXJ0ZXIgdmlld1xyXG4gICAgICAgIGZndjogJy9mZ3YnLCAvL2ZvcnNhbGUgZ2FsbGVyeSB2aWV3XHJcbiAgICAgICAgZnZwOiAnL2Z2cCcsIC8vZm9yc2FsZSB2ZXNzZWwgcGhvbmVcclxuICAgIH07XHJcblxyXG4gICAgLy9wcml2YXRlIG1ldGhvZHM6XHJcbiAgICB2YXIgX2hlbHBlcnMgPSB7XHJcbiAgICAgICAgdXJsOiBmdW5jdGlvbiAoZXAsIHFzT2JqKSB7XHJcbiAgICAgICAgICAgIHFzT2JqID0gcXNPYmogfHwge307XHJcbiAgICAgICAgICAgIHZhciBxcyA9IFtdO1xyXG4gICAgICAgICAgICBmb3IgKHZhciBrZXkgaW4gcXNPYmopIHsgcXMucHVzaChrZXkgKyAnPScgKyBlbmNvZGVVUklDb21wb25lbnQocXNPYmpba2V5XSkpOyB9XHJcbiAgICAgICAgICAgIHJldHVybiBfcHJvcGVydGllcy5hcGlFUCArIGVwICsgJz8nICsgcXMuam9pbignJicpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZ2V0OiBmdW5jdGlvbiAodXJpLCBjYWxsYmFja0Z1bmN0aW9uKSB7XHJcbiAgICAgICAgICAgIGNhbGxiYWNrRnVuY3Rpb24gPSBjYWxsYmFja0Z1bmN0aW9uIHx8IGZ1bmN0aW9uICgpIHsgfTtcclxuICAgICAgICAgICAgdmFyIGltZyA9IG5ldyBJbWFnZSgpO1xyXG4gICAgICAgICAgICBpbWcuYWRkRXZlbnRMaXN0ZW5lcignbG9hZCcsIGNhbGxiYWNrRnVuY3Rpb24pO1xyXG4gICAgICAgICAgICBpbWcuc3JjID0gdXJpO1xyXG4gICAgICAgIH0sXHJcbiAgICAgICAgcHZPYmo6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgcmV0dXJuIHtcclxuICAgICAgICAgICAgICAgIHJlZmVycmVyOiBkb2N1bWVudC5yZWZlcnJlcixcclxuICAgICAgICAgICAgICAgIGhvc3Q6IHdpbmRvdy5sb2NhdGlvbi5wcm90b2NvbCArICcvLycgKyB3aW5kb3cubG9jYXRpb24uaG9zdCxcclxuICAgICAgICAgICAgICAgIHBhdGg6IHdpbmRvdy5sb2NhdGlvbi5wYXRobmFtZSxcclxuICAgICAgICAgICAgICAgIHRpdGxlOiBkb2N1bWVudC50aXRsZSxcclxuICAgICAgICAgICAgICAgIHJlczogd2luZG93LnNjcmVlbi53aWR0aCArICd4JyArIHdpbmRvdy5zY3JlZW4uaGVpZ2h0LFxyXG4gICAgICAgICAgICAgICAgbGFuZzogd2luZG93Lm5hdmlnYXRvciA/IHdpbmRvdy5uYXZpZ2F0b3IubGFuZ3VhZ2UgOiBcInVuYXZhaWxhYmxlXCIsXHJcbiAgICAgICAgICAgICAgICB1YTogd2luZG93Lm5hdmlnYXRvciA/IHdpbmRvdy5uYXZpZ2F0b3IudXNlckFnZW50IDogXCJ1bmF2YWlsYWJsZVwiXHJcbiAgICAgICAgICAgIH07XHJcbiAgICAgICAgfSxcclxuICAgICAgICBhcmd0b2xpc3Q6IGZ1bmN0aW9uIChsKSB7XHJcbiAgICAgICAgICAgIHZhciBhcmdMaXN0ID0gbDtcclxuICAgICAgICAgICAgdmFyIGlkTGlzdCA9IFtdO1xyXG4gICAgICAgICAgICBpZiAoYXJnTGlzdC5sZW5ndGggPiAxKSB7XHJcbiAgICAgICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IGFyZ0xpc3QubGVuZ3RoOyBpKyspIGlkTGlzdC5wdXNoKGFyZ0xpc3RbaV0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2UgaWYgKGFyZ0xpc3QubGVuZ3RoID09IDEpIHtcclxuICAgICAgICAgICAgICAgIGlkTGlzdCA9IGFyZ0xpc3RbMF0uc3BsaXQoJywnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvL2NvbnNvbGUubG9nKGlkTGlzdCk7XHJcbiAgICAgICAgICAgIHJldHVybiBpZExpc3Q7XHJcbiAgICAgICAgfVxyXG4gICAgfTtcclxuXHJcbiAgICAvL1JlcXVlc3QgU2l0ZSBJRCBmcm9tIF9Jbml0OlxyXG4gICAgZnVuY3Rpb24gX2dldENvb2tpZSgpIHtcclxuICAgICAgICB2YXIgaG9zdCA9IHdpbmRvdy5sb2NhdGlvbi5ob3N0O1xyXG4gICAgICAgIGlmIChob3N0LmluZGV4T2YoJ3d3dy4nKSA9PSAwKSBob3N0ID0gaG9zdC5yZXBsYWNlKCd3d3cuJywgJycpO1xyXG4gICAgICAgIF9oZWxwZXJzLmdldChfaGVscGVycy51cmwoX2VuZFBvaW50cy5zaWQsIHsgaDogaG9zdCB9KSwgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICBfbG9nLnB2KCk7XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcblxyXG4gICAgLy9sb2cgZnVuY3Rpb25zOlxyXG4gICAgdmFyIF9sb2cgPSB7XHJcbiAgICAgICAgcHY6IGZ1bmN0aW9uICgpIHsgX2hlbHBlcnMuZ2V0KF9oZWxwZXJzLnVybChfZW5kUG9pbnRzLnB2LCBfaGVscGVycy5wdk9iaigpKSk7IH0sXHJcbiAgICAgICAgcHZlOiBmdW5jdGlvbiAoKSB7IF9oZWxwZXJzLmdldChfaGVscGVycy51cmwoX2VuZFBvaW50cy5wdmUpKTsgfSAvL2tlZXAgdGhpcywgYnV0IGxvZyB0aGUgdGltZSBvbiBwYWdlIG9uIHRoZSBwYWdldmlldyB0YWJsZSBpbnN0ZWFkXHJcbiAgICB9O1xyXG5cclxuICAgIGZ1bmN0aW9uIF9Jbml0KCkge1xyXG4gICAgICAgIF9nZXRDb29raWUoKTtcclxuXHJcbiAgICAgICAgd2luZG93LmFkZEV2ZW50TGlzdGVuZXIoJ2JlZm9yZXVubG9hZCcsIF9sb2cucHZlKTtcclxuICAgIH1cclxuXHJcbiAgICBfSW5pdCgpO1xyXG4gICAgXHJcbiAgICBmdW5jdGlvbiBmcyhlbWwsIGV4dHJhRGF0YSkge1xyXG4gICAgICAgIF9oZWxwZXJzLmdldChfaGVscGVycy51cmwoX2VuZFBvaW50cy5mcywgeyBlbWFpbDogZW1sLCBleHRyYURhdGE6IGV4dHJhRGF0YSB9KSk7XHJcbiAgICB9XHJcbiAgICBcclxuICAgIGZ1bmN0aW9uIHZpKCkge1xyXG4gICAgICAgIHZhciBpZExpc3QgPSBfaGVscGVycy5hcmd0b2xpc3QoYXJndW1lbnRzKTtcclxuICAgICAgICBfaGVscGVycy5nZXQoX2hlbHBlcnMudXJsKF9lbmRQb2ludHMudmksIHsgdjogaWRMaXN0IH0pKTtcclxuICAgIH1cclxuXHJcbiAgICBmdW5jdGlvbiB2dih2aWQpIHtcclxuICAgICAgICBfaGVscGVycy5nZXQoX2hlbHBlcnMudXJsKF9lbmRQb2ludHMudnYsIHsgdjogdmlkIH0pKTtcclxuICAgIH1cclxuXHJcbiAgICBmdW5jdGlvbiBjaSgpIHtcclxuICAgICAgICB2YXIgaWRMaXN0ID0gX2hlbHBlcnMuYXJndG9saXN0KGFyZ3VtZW50cyk7XHJcbiAgICAgICAgX2hlbHBlcnMuZ2V0KF9oZWxwZXJzLnVybChfZW5kUG9pbnRzLmNpLCB7IHY6IGlkTGlzdCB9KSk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gY3YodmlkKSB7XHJcbiAgICAgICAgX2hlbHBlcnMuZ2V0KF9oZWxwZXJzLnVybChfZW5kUG9pbnRzLmN2LCB7IHY6IHZpZCB9KSk7XHJcbiAgICB9XHJcblxyXG4gICAgZnVuY3Rpb24gZmd2KHZpZCkgeyBfaGVscGVycy5nZXQoX2hlbHBlcnMudXJsKF9lbmRQb2ludHMuZmd2LCB7IHY6IHZpZCB9KSk7IH1cclxuXHJcbiAgICBmdW5jdGlvbiBmdnAodmlkKSB7IF9oZWxwZXJzLmdldChfaGVscGVycy51cmwoX2VuZFBvaW50cy5mdnAsIHsgdjogdmlkIH0pKTsgfVxyXG5cclxuICAgIC8vZXhwb3NlIGFueSBmdW5jdGlvbnMgcHVibGljbHk6XHJcbiAgICByZXR1cm4ge1xyXG4gICAgICAgIGZvcm1TdWJtaXQ6IGZzLFxyXG4gICAgICAgIGZvcnNhbGU6IHtcclxuICAgICAgICAgICAgaW1wcmVzc2lvbnM6IHZpLFxyXG4gICAgICAgICAgICB2aWV3OiB2dixcclxuICAgICAgICAgICAgZ2FsbGVyeTogZmd2LFxyXG4gICAgICAgICAgICBwaG9uZTogZnZwXHJcbiAgICAgICAgfSxcclxuICAgICAgICBjaGFydGVyOiB7XHJcbiAgICAgICAgICAgIGltcHJlc3Npb25zOiBjaSxcclxuICAgICAgICAgICAgdmlldzogY3ZcclxuICAgICAgICB9XHJcbiAgICB9O1xyXG5cclxufSkoKTsiLCIvKlxuICAgIEEgc2ltcGxlIGpRdWVyeSBtb2RhbCAoaHR0cDovL2dpdGh1Yi5jb20va3lsZWZveC9qcXVlcnktbW9kYWwpXG4gICAgVmVyc2lvbiAwLjkuMlxuKi9cblxuKGZ1bmN0aW9uIChmYWN0b3J5KSB7XG4gIC8vIE1ha2luZyB5b3VyIGpRdWVyeSBwbHVnaW4gd29yayBiZXR0ZXIgd2l0aCBucG0gdG9vbHNcbiAgLy8gaHR0cDovL2Jsb2cubnBtanMub3JnL3Bvc3QvMTEyNzEyMTY5ODMwL21ha2luZy15b3VyLWpxdWVyeS1wbHVnaW4td29yay1iZXR0ZXItd2l0aC1ucG1cbiAgaWYodHlwZW9mIG1vZHVsZSA9PT0gXCJvYmplY3RcIiAmJiB0eXBlb2YgbW9kdWxlLmV4cG9ydHMgPT09IFwib2JqZWN0XCIpIHtcbiAgICBmYWN0b3J5KHJlcXVpcmUoXCJqcXVlcnlcIiksIHdpbmRvdywgZG9jdW1lbnQpO1xuICB9XG4gIGVsc2Uge1xuICAgIGZhY3RvcnkoalF1ZXJ5LCB3aW5kb3csIGRvY3VtZW50KTtcbiAgfVxufShmdW5jdGlvbigkLCB3aW5kb3csIGRvY3VtZW50LCB1bmRlZmluZWQpIHtcblxuICB2YXIgbW9kYWxzID0gW10sXG4gICAgICBnZXRDdXJyZW50ID0gZnVuY3Rpb24oKSB7XG4gICAgICAgIHJldHVybiBtb2RhbHMubGVuZ3RoID8gbW9kYWxzW21vZGFscy5sZW5ndGggLSAxXSA6IG51bGw7XG4gICAgICB9LFxuICAgICAgc2VsZWN0Q3VycmVudCA9IGZ1bmN0aW9uKCkge1xuICAgICAgICB2YXIgaSxcbiAgICAgICAgICAgIHNlbGVjdGVkID0gZmFsc2U7XG4gICAgICAgIGZvciAoaT1tb2RhbHMubGVuZ3RoLTE7IGk+PTA7IGktLSkge1xuICAgICAgICAgIGlmIChtb2RhbHNbaV0uJGJsb2NrZXIpIHtcbiAgICAgICAgICAgIG1vZGFsc1tpXS4kYmxvY2tlci50b2dnbGVDbGFzcygnY3VycmVudCcsIXNlbGVjdGVkKS50b2dnbGVDbGFzcygnYmVoaW5kJyxzZWxlY3RlZCk7XG4gICAgICAgICAgICBzZWxlY3RlZCA9IHRydWU7XG4gICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICB9O1xuXG4gICQueWF0Y29fbW9kYWwgPSBmdW5jdGlvbihlbCwgb3B0aW9ucykge1xuICAgIHZhciByZW1vdmUsIHRhcmdldDtcbiAgICB0aGlzLiRib2R5ID0gJCgnYm9keScpO1xuICAgIHRoaXMub3B0aW9ucyA9ICQuZXh0ZW5kKHt9LCAkLnlhdGNvX21vZGFsLmRlZmF1bHRzLCBvcHRpb25zKTtcbiAgICB0aGlzLm9wdGlvbnMuZG9GYWRlID0gIWlzTmFOKHBhcnNlSW50KHRoaXMub3B0aW9ucy5mYWRlRHVyYXRpb24sIDEwKSk7XG4gICAgdGhpcy4kYmxvY2tlciA9IG51bGw7XG4gICAgaWYgKHRoaXMub3B0aW9ucy5jbG9zZUV4aXN0aW5nKVxuICAgICAgd2hpbGUgKCQueWF0Y29fbW9kYWwuaXNBY3RpdmUoKSlcbiAgICAgICAgJC55YXRjb19tb2RhbC5jbG9zZSgpOyAvLyBDbG9zZSBhbnkgb3BlbiBtb2RhbHMuXG4gICAgbW9kYWxzLnB1c2godGhpcyk7XG4gICAgaWYgKGVsLmlzKCdhJykpIHtcbiAgICAgIHRhcmdldCA9IGVsLmF0dHIoJ2hyZWYnKTtcbiAgICAgIHRoaXMuYW5jaG9yID0gZWw7XG4gICAgICAvL1NlbGVjdCBlbGVtZW50IGJ5IGlkIGZyb20gaHJlZlxuICAgICAgaWYgKC9eIy8udGVzdCh0YXJnZXQpKSB7XG4gICAgICAgIHRoaXMuJGVsbSA9ICQodGFyZ2V0KTtcbiAgICAgICAgaWYgKHRoaXMuJGVsbS5sZW5ndGggIT09IDEpIHJldHVybiBudWxsO1xuICAgICAgICB0aGlzLiRib2R5LmFwcGVuZCh0aGlzLiRlbG0pO1xuICAgICAgICB0aGlzLm9wZW4oKTtcbiAgICAgIC8vQUpBWFxuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdGhpcy4kZWxtID0gJCgnPGRpdj4nKTtcbiAgICAgICAgdGhpcy4kYm9keS5hcHBlbmQodGhpcy4kZWxtKTtcbiAgICAgICAgcmVtb3ZlID0gZnVuY3Rpb24oZXZlbnQsIG1vZGFsKSB7IG1vZGFsLmVsbS5yZW1vdmUoKTsgfTtcbiAgICAgICAgdGhpcy5zaG93U3Bpbm5lcigpO1xuICAgICAgICBlbC50cmlnZ2VyKCQueWF0Y29fbW9kYWwuQUpBWF9TRU5EKTtcbiAgICAgICAgJC5nZXQodGFyZ2V0KS5kb25lKGZ1bmN0aW9uKGh0bWwpIHtcbiAgICAgICAgICBpZiAoISQueWF0Y29fbW9kYWwuaXNBY3RpdmUoKSkgcmV0dXJuO1xuICAgICAgICAgIGVsLnRyaWdnZXIoJC55YXRjb19tb2RhbC5BSkFYX1NVQ0NFU1MpO1xuICAgICAgICAgIHZhciBjdXJyZW50ID0gZ2V0Q3VycmVudCgpO1xuICAgICAgICAgIGN1cnJlbnQuJGVsbS5lbXB0eSgpLmFwcGVuZChodG1sKS5vbigkLnlhdGNvX21vZGFsLkNMT1NFLCByZW1vdmUpO1xuICAgICAgICAgIGN1cnJlbnQuaGlkZVNwaW5uZXIoKTtcbiAgICAgICAgICBjdXJyZW50Lm9wZW4oKTtcbiAgICAgICAgICBlbC50cmlnZ2VyKCQueWF0Y29fbW9kYWwuQUpBWF9DT01QTEVURSk7XG4gICAgICAgIH0pLmZhaWwoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgZWwudHJpZ2dlcigkLnlhdGNvX21vZGFsLkFKQVhfRkFJTCk7XG4gICAgICAgICAgdmFyIGN1cnJlbnQgPSBnZXRDdXJyZW50KCk7XG4gICAgICAgICAgY3VycmVudC5oaWRlU3Bpbm5lcigpO1xuICAgICAgICAgIG1vZGFscy5wb3AoKTsgLy8gcmVtb3ZlIGV4cGVjdGVkIG1vZGFsIGZyb20gdGhlIGxpc3RcbiAgICAgICAgICBlbC50cmlnZ2VyKCQueWF0Y29fbW9kYWwuQUpBWF9DT01QTEVURSk7XG4gICAgICAgIH0pO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICB0aGlzLiRlbG0gPSBlbDtcbiAgICAgIHRoaXMuYW5jaG9yID0gZWw7XG4gICAgICB0aGlzLiRib2R5LmFwcGVuZCh0aGlzLiRlbG0pO1xuICAgICAgdGhpcy5vcGVuKCk7XG4gICAgfVxuICB9O1xuXG4gICQueWF0Y29fbW9kYWwucHJvdG90eXBlID0ge1xuICAgIGNvbnN0cnVjdG9yOiAkLnlhdGNvX21vZGFsLFxuXG4gICAgb3BlbjogZnVuY3Rpb24oKSB7XG4gICAgICB2YXIgbSA9IHRoaXM7XG4gICAgICB0aGlzLmJsb2NrKCk7XG4gICAgICB0aGlzLmFuY2hvci5ibHVyKCk7XG4gICAgICBpZih0aGlzLm9wdGlvbnMuZG9GYWRlKSB7XG4gICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgbS5zaG93KCk7XG4gICAgICAgIH0sIHRoaXMub3B0aW9ucy5mYWRlRHVyYXRpb24gKiB0aGlzLm9wdGlvbnMuZmFkZURlbGF5KTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHRoaXMuc2hvdygpO1xuICAgICAgfVxuICAgICAgJChkb2N1bWVudCkub2ZmKCdrZXlkb3duLm1vZGFsJykub24oJ2tleWRvd24ubW9kYWwnLCBmdW5jdGlvbihldmVudCkge1xuICAgICAgICB2YXIgY3VycmVudCA9IGdldEN1cnJlbnQoKTtcbiAgICAgICAgaWYgKGV2ZW50LndoaWNoID09PSAyNyAmJiBjdXJyZW50Lm9wdGlvbnMuZXNjYXBlQ2xvc2UpIGN1cnJlbnQuY2xvc2UoKTtcbiAgICAgIH0pO1xuICAgICAgaWYgKHRoaXMub3B0aW9ucy5jbGlja0Nsb3NlKVxuICAgICAgICB0aGlzLiRibG9ja2VyLmNsaWNrKGZ1bmN0aW9uKGUpIHtcbiAgICAgICAgICBpZiAoZS50YXJnZXQgPT09IHRoaXMpXG4gICAgICAgICAgICAkLnlhdGNvX21vZGFsLmNsb3NlKCk7XG4gICAgICAgIH0pO1xuICAgIH0sXG5cbiAgICBjbG9zZTogZnVuY3Rpb24oKSB7XG4gICAgICBtb2RhbHMucG9wKCk7XG4gICAgICB0aGlzLnVuYmxvY2soKTtcbiAgICAgIHRoaXMuaGlkZSgpO1xuICAgICAgaWYgKCEkLnlhdGNvX21vZGFsLmlzQWN0aXZlKCkpXG4gICAgICAgICQoZG9jdW1lbnQpLm9mZigna2V5ZG93bi5tb2RhbCcpO1xuICAgIH0sXG5cbiAgICBibG9jazogZnVuY3Rpb24oKSB7XG4gICAgICB0aGlzLiRlbG0udHJpZ2dlcigkLnlhdGNvX21vZGFsLkJFRk9SRV9CTE9DSywgW3RoaXMuX2N0eCgpXSk7XG4gICAgICB0aGlzLiRib2R5LmNzcygnb3ZlcmZsb3cnLCdoaWRkZW4nKTtcbiAgICAgIHRoaXMuJGJsb2NrZXIgPSAkKCc8ZGl2IGNsYXNzPVwiJyArIHRoaXMub3B0aW9ucy5ibG9ja2VyQ2xhc3MgKyAnIGJsb2NrZXIgY3VycmVudFwiPjwvZGl2PicpLmFwcGVuZFRvKHRoaXMuJGJvZHkpO1xuICAgICAgc2VsZWN0Q3VycmVudCgpO1xuICAgICAgaWYodGhpcy5vcHRpb25zLmRvRmFkZSkge1xuICAgICAgICB0aGlzLiRibG9ja2VyLmNzcygnb3BhY2l0eScsMCkuYW5pbWF0ZSh7b3BhY2l0eTogMX0sIHRoaXMub3B0aW9ucy5mYWRlRHVyYXRpb24pO1xuICAgICAgfVxuICAgICAgdGhpcy4kZWxtLnRyaWdnZXIoJC55YXRjb19tb2RhbC5CTE9DSywgW3RoaXMuX2N0eCgpXSk7XG4gICAgfSxcblxuICAgIHVuYmxvY2s6IGZ1bmN0aW9uKG5vdykge1xuICAgICAgaWYgKCFub3cgJiYgdGhpcy5vcHRpb25zLmRvRmFkZSlcbiAgICAgICAgdGhpcy4kYmxvY2tlci5mYWRlT3V0KHRoaXMub3B0aW9ucy5mYWRlRHVyYXRpb24sIHRoaXMudW5ibG9jay5iaW5kKHRoaXMsdHJ1ZSkpO1xuICAgICAgZWxzZSB7XG4gICAgICAgIHRoaXMuJGJsb2NrZXIuY2hpbGRyZW4oKS5hcHBlbmRUbyh0aGlzLiRib2R5KTtcbiAgICAgICAgdGhpcy4kYmxvY2tlci5yZW1vdmUoKTtcbiAgICAgICAgdGhpcy4kYmxvY2tlciA9IG51bGw7XG4gICAgICAgIHNlbGVjdEN1cnJlbnQoKTtcbiAgICAgICAgaWYgKCEkLnlhdGNvX21vZGFsLmlzQWN0aXZlKCkpXG4gICAgICAgICAgdGhpcy4kYm9keS5jc3MoJ292ZXJmbG93JywnJyk7XG4gICAgICB9XG4gICAgfSxcblxuICAgIHNob3c6IGZ1bmN0aW9uKCkge1xuICAgICAgdGhpcy4kZWxtLnRyaWdnZXIoJC55YXRjb19tb2RhbC5CRUZPUkVfT1BFTiwgW3RoaXMuX2N0eCgpXSk7XG4gICAgICBpZiAodGhpcy5vcHRpb25zLnNob3dDbG9zZSkge1xuICAgICAgICB0aGlzLmNsb3NlQnV0dG9uID0gJCgnPGEgaHJlZj1cIiNjbG9zZS1tb2RhbFwiIHJlbD1cIm1vZGFsOmNsb3NlXCIgY2xhc3M9XCJjbG9zZS1tb2RhbCAnICsgdGhpcy5vcHRpb25zLmNsb3NlQ2xhc3MgKyAnXCI+JyArIHRoaXMub3B0aW9ucy5jbG9zZVRleHQgKyAnPC9hPicpO1xuICAgICAgICB0aGlzLiRlbG0uYXBwZW5kKHRoaXMuY2xvc2VCdXR0b24pO1xuICAgICAgfVxuICAgICAgdGhpcy4kZWxtLmFkZENsYXNzKHRoaXMub3B0aW9ucy5tb2RhbENsYXNzKS5hcHBlbmRUbyh0aGlzLiRibG9ja2VyKTtcbiAgICAgIGlmKHRoaXMub3B0aW9ucy5kb0ZhZGUpIHtcbiAgICAgICAgdGhpcy4kZWxtLmNzcyh7b3BhY2l0eTogMCwgZGlzcGxheTogJ2lubGluZS1ibG9jayd9KS5hbmltYXRlKHtvcGFjaXR5OiAxfSwgdGhpcy5vcHRpb25zLmZhZGVEdXJhdGlvbik7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICB0aGlzLiRlbG0uY3NzKCdkaXNwbGF5JywgJ2lubGluZS1ibG9jaycpO1xuICAgICAgfVxuICAgICAgdGhpcy4kZWxtLnRyaWdnZXIoJC55YXRjb19tb2RhbC5PUEVOLCBbdGhpcy5fY3R4KCldKTtcbiAgICB9LFxuXG4gICAgaGlkZTogZnVuY3Rpb24oKSB7XG4gICAgICB0aGlzLiRlbG0udHJpZ2dlcigkLnlhdGNvX21vZGFsLkJFRk9SRV9DTE9TRSwgW3RoaXMuX2N0eCgpXSk7XG4gICAgICBpZiAodGhpcy5jbG9zZUJ1dHRvbikgdGhpcy5jbG9zZUJ1dHRvbi5yZW1vdmUoKTtcbiAgICAgIHZhciBfdGhpcyA9IHRoaXM7XG4gICAgICBpZih0aGlzLm9wdGlvbnMuZG9GYWRlKSB7XG4gICAgICAgIHRoaXMuJGVsbS5mYWRlT3V0KHRoaXMub3B0aW9ucy5mYWRlRHVyYXRpb24sIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBfdGhpcy4kZWxtLnRyaWdnZXIoJC55YXRjb19tb2RhbC5BRlRFUl9DTE9TRSwgW190aGlzLl9jdHgoKV0pO1xuICAgICAgICB9KTtcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIHRoaXMuJGVsbS5oaWRlKDAsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICBfdGhpcy4kZWxtLnRyaWdnZXIoJC55YXRjb19tb2RhbC5BRlRFUl9DTE9TRSwgW190aGlzLl9jdHgoKV0pO1xuICAgICAgICB9KTtcbiAgICAgIH1cbiAgICAgIHRoaXMuJGVsbS50cmlnZ2VyKCQueWF0Y29fbW9kYWwuQ0xPU0UsIFt0aGlzLl9jdHgoKV0pO1xuICAgIH0sXG5cbiAgICBzaG93U3Bpbm5lcjogZnVuY3Rpb24oKSB7XG4gICAgICBpZiAoIXRoaXMub3B0aW9ucy5zaG93U3Bpbm5lcikgcmV0dXJuO1xuICAgICAgdGhpcy5zcGlubmVyID0gdGhpcy5zcGlubmVyIHx8ICQoJzxkaXYgY2xhc3M9XCInICsgdGhpcy5vcHRpb25zLm1vZGFsQ2xhc3MgKyAnLXNwaW5uZXJcIj48L2Rpdj4nKVxuICAgICAgICAuYXBwZW5kKHRoaXMub3B0aW9ucy5zcGlubmVySHRtbCk7XG4gICAgICB0aGlzLiRib2R5LmFwcGVuZCh0aGlzLnNwaW5uZXIpO1xuICAgICAgdGhpcy5zcGlubmVyLnNob3coKTtcbiAgICB9LFxuXG4gICAgaGlkZVNwaW5uZXI6IGZ1bmN0aW9uKCkge1xuICAgICAgaWYgKHRoaXMuc3Bpbm5lcikgdGhpcy5zcGlubmVyLnJlbW92ZSgpO1xuICAgIH0sXG5cbiAgICAvL1JldHVybiBjb250ZXh0IGZvciBjdXN0b20gZXZlbnRzXG4gICAgX2N0eDogZnVuY3Rpb24oKSB7XG4gICAgICByZXR1cm4geyBlbG06IHRoaXMuJGVsbSwgJGVsbTogdGhpcy4kZWxtLCAkYmxvY2tlcjogdGhpcy4kYmxvY2tlciwgb3B0aW9uczogdGhpcy5vcHRpb25zLCAkYW5jaG9yOiB0aGlzLmFuY2hvciB9O1xuICAgIH1cbiAgfTtcblxuICAkLnlhdGNvX21vZGFsLmNsb3NlID0gZnVuY3Rpb24oZXZlbnQpIHtcbiAgICBpZiAoISQueWF0Y29fbW9kYWwuaXNBY3RpdmUoKSkgcmV0dXJuO1xuICAgIGlmIChldmVudCkgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICB2YXIgY3VycmVudCA9IGdldEN1cnJlbnQoKTtcbiAgICBjdXJyZW50LmNsb3NlKCk7XG4gICAgcmV0dXJuIGN1cnJlbnQuJGVsbTtcbiAgfTtcblxuICAvLyBSZXR1cm5zIGlmIHRoZXJlIGN1cnJlbnRseSBpcyBhbiBhY3RpdmUgbW9kYWxcbiAgJC55YXRjb19tb2RhbC5pc0FjdGl2ZSA9IGZ1bmN0aW9uICgpIHtcbiAgICByZXR1cm4gbW9kYWxzLmxlbmd0aCA+IDA7XG4gIH07XG5cbiAgJC55YXRjb19tb2RhbC5nZXRDdXJyZW50ID0gZ2V0Q3VycmVudDtcblxuICAkLnlhdGNvX21vZGFsLmRlZmF1bHRzID0ge1xuICAgIGNsb3NlRXhpc3Rpbmc6IHRydWUsXG4gICAgZXNjYXBlQ2xvc2U6IHRydWUsXG4gICAgY2xpY2tDbG9zZTogdHJ1ZSxcbiAgICBjbG9zZVRleHQ6ICdDbG9zZScsXG4gICAgY2xvc2VDbGFzczogJycsXG4gICAgbW9kYWxDbGFzczogXCJ5dC1tb2RhbFwiLFxuICAgIGJsb2NrZXJDbGFzczogXCJqcXVlcnktbW9kYWxcIixcbiAgICBzcGlubmVySHRtbDogJzxkaXYgY2xhc3M9XCJyZWN0MVwiPjwvZGl2PjxkaXYgY2xhc3M9XCJyZWN0MlwiPjwvZGl2PjxkaXYgY2xhc3M9XCJyZWN0M1wiPjwvZGl2PjxkaXYgY2xhc3M9XCJyZWN0NFwiPjwvZGl2PicsXG4gICAgc2hvd1NwaW5uZXI6IHRydWUsXG4gICAgc2hvd0Nsb3NlOiB0cnVlLFxuICAgIGZhZGVEdXJhdGlvbjogbnVsbCwgICAvLyBOdW1iZXIgb2YgbWlsbGlzZWNvbmRzIHRoZSBmYWRlIGFuaW1hdGlvbiB0YWtlcy5cbiAgICBmYWRlRGVsYXk6IDEuMCAgICAgICAgLy8gUG9pbnQgZHVyaW5nIHRoZSBvdmVybGF5J3MgZmFkZS1pbiB0aGF0IHRoZSBtb2RhbCBiZWdpbnMgdG8gZmFkZSBpbiAoLjUgPSA1MCUsIDEuNSA9IDE1MCUsIGV0Yy4pXG4gIH07XG5cbiAgLy8gRXZlbnQgY29uc3RhbnRzXG4gICQueWF0Y29fbW9kYWwuQkVGT1JFX0JMT0NLID0gJ21vZGFsOmJlZm9yZS1ibG9jayc7XG4gICQueWF0Y29fbW9kYWwuQkxPQ0sgPSAnbW9kYWw6YmxvY2snO1xuICAkLnlhdGNvX21vZGFsLkJFRk9SRV9PUEVOID0gJ21vZGFsOmJlZm9yZS1vcGVuJztcbiAgJC55YXRjb19tb2RhbC5PUEVOID0gJ21vZGFsOm9wZW4nO1xuICAkLnlhdGNvX21vZGFsLkJFRk9SRV9DTE9TRSA9ICdtb2RhbDpiZWZvcmUtY2xvc2UnO1xuICAkLnlhdGNvX21vZGFsLkNMT1NFID0gJ21vZGFsOmNsb3NlJztcbiAgJC55YXRjb19tb2RhbC5BRlRFUl9DTE9TRSA9ICdtb2RhbDphZnRlci1jbG9zZSc7XG4gICQueWF0Y29fbW9kYWwuQUpBWF9TRU5EID0gJ21vZGFsOmFqYXg6c2VuZCc7XG4gICQueWF0Y29fbW9kYWwuQUpBWF9TVUNDRVNTID0gJ21vZGFsOmFqYXg6c3VjY2Vzcyc7XG4gICQueWF0Y29fbW9kYWwuQUpBWF9GQUlMID0gJ21vZGFsOmFqYXg6ZmFpbCc7XG4gICQueWF0Y29fbW9kYWwuQUpBWF9DT01QTEVURSA9ICdtb2RhbDphamF4OmNvbXBsZXRlJztcblxuICAkLmZuLnlhdGNvX21vZGFsID0gZnVuY3Rpb24ob3B0aW9ucyl7XG4gICAgaWYgKHRoaXMubGVuZ3RoID09PSAxKSB7XG4gICAgICBuZXcgJC55YXRjb19tb2RhbCh0aGlzLCBvcHRpb25zKTtcbiAgICB9XG4gICAgcmV0dXJuIHRoaXM7XG4gIH07XG5cbiAgLy8gQXV0b21hdGljYWxseSBiaW5kIGxpbmtzIHdpdGggcmVsPVwibW9kYWw6Y2xvc2VcIiB0bywgd2VsbCwgY2xvc2UgdGhlIG1vZGFsLlxuICAkKGRvY3VtZW50KS5vbignY2xpY2subW9kYWwnLCAnYVtyZWx+PVwibW9kYWw6Y2xvc2VcIl0nLCAkLnlhdGNvX21vZGFsLmNsb3NlKTtcbiAgJChkb2N1bWVudCkub24oJ2NsaWNrLm1vZGFsJywgJ2FbcmVsfj1cIm1vZGFsOm9wZW5cIl0nLCBmdW5jdGlvbihldmVudCkge1xuICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgJCh0aGlzKS5tb2RhbCgpO1xuICB9KTtcbn0pKTsiLCIvKipcbiogc2ltcGxlUGFnaW5hdGlvbi5qcyB2MS42XG4qIEEgc2ltcGxlIGpRdWVyeSBwYWdpbmF0aW9uIHBsdWdpbi5cbiogaHR0cDovL2ZsYXZpdXNtYXRpcy5naXRodWIuY29tL3NpbXBsZVBhZ2luYXRpb24uanMvXG4qXG4qIENvcHlyaWdodCAyMDEyLCBGbGF2aXVzIE1hdGlzXG4qIFJlbGVhc2VkIHVuZGVyIHRoZSBNSVQgbGljZW5zZS5cbiogaHR0cDovL2ZsYXZpdXNtYXRpcy5naXRodWIuY29tL2xpY2Vuc2UuaHRtbFxuKi9cblxuKGZ1bmN0aW9uKCQpe1xuXG5cdHZhciBtZXRob2RzID0ge1xuXHRcdGluaXQ6IGZ1bmN0aW9uKG9wdGlvbnMpIHtcblx0XHRcdHZhciBvID0gJC5leHRlbmQoe1xuXHRcdFx0XHRpdGVtczogMSxcblx0XHRcdFx0aXRlbXNPblBhZ2U6IDEsXG5cdFx0XHRcdHBhZ2VzOiAwLFxuXHRcdFx0XHRkaXNwbGF5ZWRQYWdlczogNSxcblx0XHRcdFx0ZWRnZXM6IDIsXG5cdFx0XHRcdGN1cnJlbnRQYWdlOiAwLFxuXHRcdFx0XHR1c2VBbmNob3JzOiB0cnVlLFxuXHRcdFx0XHRocmVmVGV4dFByZWZpeDogJyNwYWdlLScsXG5cdFx0XHRcdGhyZWZUZXh0U3VmZml4OiAnJyxcblx0XHRcdFx0cHJldlRleHQ6ICdQcmV2Jyxcblx0XHRcdFx0bmV4dFRleHQ6ICdOZXh0Jyxcblx0XHRcdFx0ZWxsaXBzZVRleHQ6ICcmaGVsbGlwOycsXG5cdFx0XHRcdGVsbGlwc2VQYWdlU2V0OiB0cnVlLFxuXHRcdFx0XHRjc3NTdHlsZTogJ2xpZ2h0LXRoZW1lJyxcblx0XHRcdFx0bGlzdFN0eWxlOiAnJyxcblx0XHRcdFx0bGFiZWxNYXA6IFtdLFxuXHRcdFx0XHRzZWxlY3RPbkNsaWNrOiB0cnVlLFxuXHRcdFx0XHRuZXh0QXRGcm9udDogZmFsc2UsXG5cdFx0XHRcdGludmVydFBhZ2VPcmRlcjogZmFsc2UsXG5cdFx0XHRcdHVzZVN0YXJ0RWRnZSA6IHRydWUsXG5cdFx0XHRcdHVzZUVuZEVkZ2UgOiB0cnVlLFxuXHRcdFx0XHRvblBhZ2VDbGljazogZnVuY3Rpb24ocGFnZU51bWJlciwgZXZlbnQpIHtcblx0XHRcdFx0XHQvLyBDYWxsYmFjayB0cmlnZ2VyZWQgd2hlbiBhIHBhZ2UgaXMgY2xpY2tlZFxuXHRcdFx0XHRcdC8vIFBhZ2UgbnVtYmVyIGlzIGdpdmVuIGFzIGFuIG9wdGlvbmFsIHBhcmFtZXRlclxuXHRcdFx0XHR9LFxuXHRcdFx0XHRvbkluaXQ6IGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdC8vIENhbGxiYWNrIHRyaWdnZXJlZCBpbW1lZGlhdGVseSBhZnRlciBpbml0aWFsaXphdGlvblxuXHRcdFx0XHR9XG5cdFx0XHR9LCBvcHRpb25zIHx8IHt9KTtcblxuXHRcdFx0dmFyIHNlbGYgPSB0aGlzO1xuXG5cdFx0XHRvLnBhZ2VzID0gby5wYWdlcyA/IG8ucGFnZXMgOiBNYXRoLmNlaWwoby5pdGVtcyAvIG8uaXRlbXNPblBhZ2UpID8gTWF0aC5jZWlsKG8uaXRlbXMgLyBvLml0ZW1zT25QYWdlKSA6IDE7XG5cdFx0XHRpZiAoby5jdXJyZW50UGFnZSlcblx0XHRcdFx0by5jdXJyZW50UGFnZSA9IG8uY3VycmVudFBhZ2UgLSAxO1xuXHRcdFx0ZWxzZVxuXHRcdFx0XHRvLmN1cnJlbnRQYWdlID0gIW8uaW52ZXJ0UGFnZU9yZGVyID8gMCA6IG8ucGFnZXMgLSAxO1xuXHRcdFx0by5oYWxmRGlzcGxheWVkID0gby5kaXNwbGF5ZWRQYWdlcyAvIDI7XG5cblx0XHRcdHRoaXMuZWFjaChmdW5jdGlvbigpIHtcblx0XHRcdFx0c2VsZi5hZGRDbGFzcyhvLmNzc1N0eWxlICsgJyBzaW1wbGUtcGFnaW5hdGlvbicpLmRhdGEoJ3BhZ2luYXRpb24nLCBvKTtcblx0XHRcdFx0bWV0aG9kcy5fZHJhdy5jYWxsKHNlbGYpO1xuXHRcdFx0fSk7XG5cblx0XHRcdG8ub25Jbml0KCk7XG5cblx0XHRcdHJldHVybiB0aGlzO1xuXHRcdH0sXG5cblx0XHRzZWxlY3RQYWdlOiBmdW5jdGlvbihwYWdlKSB7XG5cdFx0XHRtZXRob2RzLl9zZWxlY3RQYWdlLmNhbGwodGhpcywgcGFnZSAtIDEpO1xuXHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0fSxcblxuXHRcdHByZXZQYWdlOiBmdW5jdGlvbigpIHtcblx0XHRcdHZhciBvID0gdGhpcy5kYXRhKCdwYWdpbmF0aW9uJyk7XG5cdFx0XHRpZiAoIW8uaW52ZXJ0UGFnZU9yZGVyKSB7XG5cdFx0XHRcdGlmIChvLmN1cnJlbnRQYWdlID4gMCkge1xuXHRcdFx0XHRcdG1ldGhvZHMuX3NlbGVjdFBhZ2UuY2FsbCh0aGlzLCBvLmN1cnJlbnRQYWdlIC0gMSk7XG5cdFx0XHRcdH1cblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdGlmIChvLmN1cnJlbnRQYWdlIDwgby5wYWdlcyAtIDEpIHtcblx0XHRcdFx0XHRtZXRob2RzLl9zZWxlY3RQYWdlLmNhbGwodGhpcywgby5jdXJyZW50UGFnZSArIDEpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0XHRyZXR1cm4gdGhpcztcblx0XHR9LFxuXG5cdFx0bmV4dFBhZ2U6IGZ1bmN0aW9uKCkge1xuXHRcdFx0dmFyIG8gPSB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKTtcblx0XHRcdGlmICghby5pbnZlcnRQYWdlT3JkZXIpIHtcblx0XHRcdFx0aWYgKG8uY3VycmVudFBhZ2UgPCBvLnBhZ2VzIC0gMSkge1xuXHRcdFx0XHRcdG1ldGhvZHMuX3NlbGVjdFBhZ2UuY2FsbCh0aGlzLCBvLmN1cnJlbnRQYWdlICsgMSk7XG5cdFx0XHRcdH1cblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdGlmIChvLmN1cnJlbnRQYWdlID4gMCkge1xuXHRcdFx0XHRcdG1ldGhvZHMuX3NlbGVjdFBhZ2UuY2FsbCh0aGlzLCBvLmN1cnJlbnRQYWdlIC0gMSk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblx0XHRcdHJldHVybiB0aGlzO1xuXHRcdH0sXG5cblx0XHRnZXRQYWdlc0NvdW50OiBmdW5jdGlvbigpIHtcblx0XHRcdHJldHVybiB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKS5wYWdlcztcblx0XHR9LFxuXG5cdFx0c2V0UGFnZXNDb3VudDogZnVuY3Rpb24oY291bnQpIHtcblx0XHRcdHRoaXMuZGF0YSgncGFnaW5hdGlvbicpLnBhZ2VzID0gY291bnQ7XG5cdFx0fSxcblxuXHRcdGdldEN1cnJlbnRQYWdlOiBmdW5jdGlvbiAoKSB7XG5cdFx0XHRyZXR1cm4gdGhpcy5kYXRhKCdwYWdpbmF0aW9uJykuY3VycmVudFBhZ2UgKyAxO1xuXHRcdH0sXG5cblx0XHRkZXN0cm95OiBmdW5jdGlvbigpe1xuXHRcdFx0dGhpcy5lbXB0eSgpO1xuXHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0fSxcblxuXHRcdGRyYXdQYWdlOiBmdW5jdGlvbiAocGFnZSkge1xuXHRcdFx0dmFyIG8gPSB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKTtcblx0XHRcdG8uY3VycmVudFBhZ2UgPSBwYWdlIC0gMTtcblx0XHRcdHRoaXMuZGF0YSgncGFnaW5hdGlvbicsIG8pO1xuXHRcdFx0bWV0aG9kcy5fZHJhdy5jYWxsKHRoaXMpO1xuXHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0fSxcblxuXHRcdHJlZHJhdzogZnVuY3Rpb24oKXtcblx0XHRcdG1ldGhvZHMuX2RyYXcuY2FsbCh0aGlzKTtcblx0XHRcdHJldHVybiB0aGlzO1xuXHRcdH0sXG5cblx0XHRkaXNhYmxlOiBmdW5jdGlvbigpe1xuXHRcdFx0dmFyIG8gPSB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKTtcblx0XHRcdG8uZGlzYWJsZWQgPSB0cnVlO1xuXHRcdFx0dGhpcy5kYXRhKCdwYWdpbmF0aW9uJywgbyk7XG5cdFx0XHRtZXRob2RzLl9kcmF3LmNhbGwodGhpcyk7XG5cdFx0XHRyZXR1cm4gdGhpcztcblx0XHR9LFxuXG5cdFx0ZW5hYmxlOiBmdW5jdGlvbigpe1xuXHRcdFx0dmFyIG8gPSB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKTtcblx0XHRcdG8uZGlzYWJsZWQgPSBmYWxzZTtcblx0XHRcdHRoaXMuZGF0YSgncGFnaW5hdGlvbicsIG8pO1xuXHRcdFx0bWV0aG9kcy5fZHJhdy5jYWxsKHRoaXMpO1xuXHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0fSxcblxuXHRcdHVwZGF0ZUl0ZW1zOiBmdW5jdGlvbiAobmV3SXRlbXMpIHtcblx0XHRcdHZhciBvID0gdGhpcy5kYXRhKCdwYWdpbmF0aW9uJyk7XG5cdFx0XHRvLml0ZW1zID0gbmV3SXRlbXM7XG5cdFx0XHRvLnBhZ2VzID0gbWV0aG9kcy5fZ2V0UGFnZXMobyk7XG5cdFx0XHR0aGlzLmRhdGEoJ3BhZ2luYXRpb24nLCBvKTtcblx0XHRcdG1ldGhvZHMuX2RyYXcuY2FsbCh0aGlzKTtcblx0XHR9LFxuXG5cdFx0dXBkYXRlSXRlbXNPblBhZ2U6IGZ1bmN0aW9uIChpdGVtc09uUGFnZSkge1xuXHRcdFx0dmFyIG8gPSB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKTtcblx0XHRcdG8uaXRlbXNPblBhZ2UgPSBpdGVtc09uUGFnZTtcblx0XHRcdG8ucGFnZXMgPSBtZXRob2RzLl9nZXRQYWdlcyhvKTtcblx0XHRcdHRoaXMuZGF0YSgncGFnaW5hdGlvbicsIG8pO1xuXHRcdFx0bWV0aG9kcy5fc2VsZWN0UGFnZS5jYWxsKHRoaXMsIDApO1xuXHRcdFx0cmV0dXJuIHRoaXM7XG5cdFx0fSxcblxuXHRcdGdldEl0ZW1zT25QYWdlOiBmdW5jdGlvbigpIHtcblx0XHRcdHJldHVybiB0aGlzLmRhdGEoJ3BhZ2luYXRpb24nKS5pdGVtc09uUGFnZTtcblx0XHR9LFxuXG5cdFx0X2RyYXc6IGZ1bmN0aW9uKCkge1xuXHRcdFx0dmFyXHRvID0gdGhpcy5kYXRhKCdwYWdpbmF0aW9uJyksXG5cdFx0XHRcdGludGVydmFsID0gbWV0aG9kcy5fZ2V0SW50ZXJ2YWwobyksXG5cdFx0XHRcdGksXG5cdFx0XHRcdHRhZ05hbWU7XG5cblx0XHRcdG1ldGhvZHMuZGVzdHJveS5jYWxsKHRoaXMpO1xuXG5cdFx0XHR0YWdOYW1lID0gKHR5cGVvZiB0aGlzLnByb3AgPT09ICdmdW5jdGlvbicpID8gdGhpcy5wcm9wKCd0YWdOYW1lJykgOiB0aGlzLmF0dHIoJ3RhZ05hbWUnKTtcblxuXHRcdFx0dmFyICRwYW5lbCA9IHRhZ05hbWUgPT09ICdVTCcgPyB0aGlzIDogJCgnPHVsJyArIChvLmxpc3RTdHlsZSA/ICcgY2xhc3M9XCInICsgby5saXN0U3R5bGUgKyAnXCInIDogJycpICsgJz48L3VsPicpLmFwcGVuZFRvKHRoaXMpO1xuXG5cdFx0XHQvLyBHZW5lcmF0ZSBQcmV2IGxpbmtcblx0XHRcdGlmIChvLnByZXZUZXh0KSB7XG5cdFx0XHRcdG1ldGhvZHMuX2FwcGVuZEl0ZW0uY2FsbCh0aGlzLCAhby5pbnZlcnRQYWdlT3JkZXIgPyBvLmN1cnJlbnRQYWdlIC0gMSA6IG8uY3VycmVudFBhZ2UgKyAxLCB7dGV4dDogby5wcmV2VGV4dCwgY2xhc3NlczogJ3ByZXYnfSk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIEdlbmVyYXRlIE5leHQgbGluayAoaWYgb3B0aW9uIHNldCBmb3IgYXQgZnJvbnQpXG5cdFx0XHRpZiAoby5uZXh0VGV4dCAmJiBvLm5leHRBdEZyb250KSB7XG5cdFx0XHRcdG1ldGhvZHMuX2FwcGVuZEl0ZW0uY2FsbCh0aGlzLCAhby5pbnZlcnRQYWdlT3JkZXIgPyBvLmN1cnJlbnRQYWdlICsgMSA6IG8uY3VycmVudFBhZ2UgLSAxLCB7dGV4dDogby5uZXh0VGV4dCwgY2xhc3NlczogJ25leHQnfSk7XG5cdFx0XHR9XG5cblx0XHRcdC8vIEdlbmVyYXRlIHN0YXJ0IGVkZ2VzXG5cdFx0XHRpZiAoIW8uaW52ZXJ0UGFnZU9yZGVyKSB7XG5cdFx0XHRcdGlmIChpbnRlcnZhbC5zdGFydCA+IDAgJiYgby5lZGdlcyA+IDApIHtcblx0XHRcdFx0XHRpZihvLnVzZVN0YXJ0RWRnZSkge1xuXHRcdFx0XHRcdFx0dmFyIGVuZCA9IE1hdGgubWluKG8uZWRnZXMsIGludGVydmFsLnN0YXJ0KTtcblx0XHRcdFx0XHRcdGZvciAoaSA9IDA7IGkgPCBlbmQ7IGkrKykge1xuXHRcdFx0XHRcdFx0XHRtZXRob2RzLl9hcHBlbmRJdGVtLmNhbGwodGhpcywgaSk7XG5cdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdGlmIChvLmVkZ2VzIDwgaW50ZXJ2YWwuc3RhcnQgJiYgKGludGVydmFsLnN0YXJ0IC0gby5lZGdlcyAhPSAxKSkge1xuXHRcdFx0XHRcdFx0JHBhbmVsLmFwcGVuZCgnPGxpIGNsYXNzPVwiZGlzYWJsZWRcIj48c3BhbiBjbGFzcz1cImVsbGlwc2VcIj4nICsgby5lbGxpcHNlVGV4dCArICc8L3NwYW4+PC9saT4nKTtcblx0XHRcdFx0XHR9IGVsc2UgaWYgKGludGVydmFsLnN0YXJ0IC0gby5lZGdlcyA9PSAxKSB7XG5cdFx0XHRcdFx0XHRtZXRob2RzLl9hcHBlbmRJdGVtLmNhbGwodGhpcywgby5lZGdlcyk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRpZiAoaW50ZXJ2YWwuZW5kIDwgby5wYWdlcyAmJiBvLmVkZ2VzID4gMCkge1xuXHRcdFx0XHRcdGlmKG8udXNlU3RhcnRFZGdlKSB7XG5cdFx0XHRcdFx0XHR2YXIgYmVnaW4gPSBNYXRoLm1heChvLnBhZ2VzIC0gby5lZGdlcywgaW50ZXJ2YWwuZW5kKTtcblx0XHRcdFx0XHRcdGZvciAoaSA9IG8ucGFnZXMgLSAxOyBpID49IGJlZ2luOyBpLS0pIHtcblx0XHRcdFx0XHRcdFx0bWV0aG9kcy5fYXBwZW5kSXRlbS5jYWxsKHRoaXMsIGkpO1xuXHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGlmIChvLnBhZ2VzIC0gby5lZGdlcyA+IGludGVydmFsLmVuZCAmJiAoby5wYWdlcyAtIG8uZWRnZXMgLSBpbnRlcnZhbC5lbmQgIT0gMSkpIHtcblx0XHRcdFx0XHRcdCRwYW5lbC5hcHBlbmQoJzxsaSBjbGFzcz1cImRpc2FibGVkXCI+PHNwYW4gY2xhc3M9XCJlbGxpcHNlXCI+JyArIG8uZWxsaXBzZVRleHQgKyAnPC9zcGFuPjwvbGk+Jyk7XG5cdFx0XHRcdFx0fSBlbHNlIGlmIChvLnBhZ2VzIC0gby5lZGdlcyAtIGludGVydmFsLmVuZCA9PSAxKSB7XG5cdFx0XHRcdFx0XHRtZXRob2RzLl9hcHBlbmRJdGVtLmNhbGwodGhpcywgaW50ZXJ2YWwuZW5kKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0Ly8gR2VuZXJhdGUgaW50ZXJ2YWwgbGlua3Ncblx0XHRcdGlmICghby5pbnZlcnRQYWdlT3JkZXIpIHtcblx0XHRcdFx0Zm9yIChpID0gaW50ZXJ2YWwuc3RhcnQ7IGkgPCBpbnRlcnZhbC5lbmQ7IGkrKykge1xuXHRcdFx0XHRcdG1ldGhvZHMuX2FwcGVuZEl0ZW0uY2FsbCh0aGlzLCBpKTtcblx0XHRcdFx0fVxuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0Zm9yIChpID0gaW50ZXJ2YWwuZW5kIC0gMTsgaSA+PSBpbnRlcnZhbC5zdGFydDsgaS0tKSB7XG5cdFx0XHRcdFx0bWV0aG9kcy5fYXBwZW5kSXRlbS5jYWxsKHRoaXMsIGkpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cblx0XHRcdC8vIEdlbmVyYXRlIGVuZCBlZGdlc1xuXHRcdFx0aWYgKCFvLmludmVydFBhZ2VPcmRlcikge1xuXHRcdFx0XHRpZiAoaW50ZXJ2YWwuZW5kIDwgby5wYWdlcyAmJiBvLmVkZ2VzID4gMCkge1xuXHRcdFx0XHRcdGlmIChvLnBhZ2VzIC0gby5lZGdlcyA+IGludGVydmFsLmVuZCAmJiAoby5wYWdlcyAtIG8uZWRnZXMgLSBpbnRlcnZhbC5lbmQgIT0gMSkpIHtcblx0XHRcdFx0XHRcdCRwYW5lbC5hcHBlbmQoJzxsaSBjbGFzcz1cImRpc2FibGVkXCI+PHNwYW4gY2xhc3M9XCJlbGxpcHNlXCI+JyArIG8uZWxsaXBzZVRleHQgKyAnPC9zcGFuPjwvbGk+Jyk7XG5cdFx0XHRcdFx0fSBlbHNlIGlmIChvLnBhZ2VzIC0gby5lZGdlcyAtIGludGVydmFsLmVuZCA9PSAxKSB7XG5cdFx0XHRcdFx0XHRtZXRob2RzLl9hcHBlbmRJdGVtLmNhbGwodGhpcywgaW50ZXJ2YWwuZW5kKTtcblx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0aWYoby51c2VFbmRFZGdlKSB7XG5cdFx0XHRcdFx0XHR2YXIgYmVnaW4gPSBNYXRoLm1heChvLnBhZ2VzIC0gby5lZGdlcywgaW50ZXJ2YWwuZW5kKTtcblx0XHRcdFx0XHRcdGZvciAoaSA9IGJlZ2luOyBpIDwgby5wYWdlczsgaSsrKSB7XG5cdFx0XHRcdFx0XHRcdG1ldGhvZHMuX2FwcGVuZEl0ZW0uY2FsbCh0aGlzLCBpKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdGlmIChpbnRlcnZhbC5zdGFydCA+IDAgJiYgby5lZGdlcyA+IDApIHtcblx0XHRcdFx0XHRpZiAoby5lZGdlcyA8IGludGVydmFsLnN0YXJ0ICYmIChpbnRlcnZhbC5zdGFydCAtIG8uZWRnZXMgIT0gMSkpIHtcblx0XHRcdFx0XHRcdCRwYW5lbC5hcHBlbmQoJzxsaSBjbGFzcz1cImRpc2FibGVkXCI+PHNwYW4gY2xhc3M9XCJlbGxpcHNlXCI+JyArIG8uZWxsaXBzZVRleHQgKyAnPC9zcGFuPjwvbGk+Jyk7XG5cdFx0XHRcdFx0fSBlbHNlIGlmIChpbnRlcnZhbC5zdGFydCAtIG8uZWRnZXMgPT0gMSkge1xuXHRcdFx0XHRcdFx0bWV0aG9kcy5fYXBwZW5kSXRlbS5jYWxsKHRoaXMsIG8uZWRnZXMpO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdGlmKG8udXNlRW5kRWRnZSkge1xuXHRcdFx0XHRcdFx0dmFyIGVuZCA9IE1hdGgubWluKG8uZWRnZXMsIGludGVydmFsLnN0YXJ0KTtcblx0XHRcdFx0XHRcdGZvciAoaSA9IGVuZCAtIDE7IGkgPj0gMDsgaS0tKSB7XG5cdFx0XHRcdFx0XHRcdG1ldGhvZHMuX2FwcGVuZEl0ZW0uY2FsbCh0aGlzLCBpKTtcblx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHR9XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0Ly8gR2VuZXJhdGUgTmV4dCBsaW5rICh1bmxlc3Mgb3B0aW9uIGlzIHNldCBmb3IgYXQgZnJvbnQpXG5cdFx0XHRpZiAoby5uZXh0VGV4dCAmJiAhby5uZXh0QXRGcm9udCkge1xuXHRcdFx0XHRtZXRob2RzLl9hcHBlbmRJdGVtLmNhbGwodGhpcywgIW8uaW52ZXJ0UGFnZU9yZGVyID8gby5jdXJyZW50UGFnZSArIDEgOiBvLmN1cnJlbnRQYWdlIC0gMSwge3RleHQ6IG8ubmV4dFRleHQsIGNsYXNzZXM6ICduZXh0J30pO1xuXHRcdFx0fVxuXG5cdFx0XHRpZiAoby5lbGxpcHNlUGFnZVNldCAmJiAhby5kaXNhYmxlZCkge1xuXHRcdFx0XHRtZXRob2RzLl9lbGxpcHNlQ2xpY2suY2FsbCh0aGlzLCAkcGFuZWwpO1xuXHRcdFx0fVxuXG5cdFx0fSxcblxuXHRcdF9nZXRQYWdlczogZnVuY3Rpb24obykge1xuXHRcdFx0dmFyIHBhZ2VzID0gTWF0aC5jZWlsKG8uaXRlbXMgLyBvLml0ZW1zT25QYWdlKTtcblx0XHRcdHJldHVybiBwYWdlcyB8fCAxO1xuXHRcdH0sXG5cblx0XHRfZ2V0SW50ZXJ2YWw6IGZ1bmN0aW9uKG8pIHtcblx0XHRcdHJldHVybiB7XG5cdFx0XHRcdHN0YXJ0OiBNYXRoLmNlaWwoby5jdXJyZW50UGFnZSA+IG8uaGFsZkRpc3BsYXllZCA/IE1hdGgubWF4KE1hdGgubWluKG8uY3VycmVudFBhZ2UgLSBvLmhhbGZEaXNwbGF5ZWQsIChvLnBhZ2VzIC0gby5kaXNwbGF5ZWRQYWdlcykpLCAwKSA6IDApLFxuXHRcdFx0XHRlbmQ6IE1hdGguY2VpbChvLmN1cnJlbnRQYWdlID4gby5oYWxmRGlzcGxheWVkID8gTWF0aC5taW4oby5jdXJyZW50UGFnZSArIG8uaGFsZkRpc3BsYXllZCwgby5wYWdlcykgOiBNYXRoLm1pbihvLmRpc3BsYXllZFBhZ2VzLCBvLnBhZ2VzKSlcblx0XHRcdH07XG5cdFx0fSxcblxuXHRcdF9hcHBlbmRJdGVtOiBmdW5jdGlvbihwYWdlSW5kZXgsIG9wdHMpIHtcblx0XHRcdHZhciBzZWxmID0gdGhpcywgb3B0aW9ucywgJGxpbmssIG8gPSBzZWxmLmRhdGEoJ3BhZ2luYXRpb24nKSwgJGxpbmtXcmFwcGVyID0gJCgnPGxpPjwvbGk+JyksICR1bCA9IHNlbGYuZmluZCgndWwnKTtcblxuXHRcdFx0cGFnZUluZGV4ID0gcGFnZUluZGV4IDwgMCA/IDAgOiAocGFnZUluZGV4IDwgby5wYWdlcyA/IHBhZ2VJbmRleCA6IG8ucGFnZXMgLSAxKTtcblxuXHRcdFx0b3B0aW9ucyA9IHtcblx0XHRcdFx0dGV4dDogcGFnZUluZGV4ICsgMSxcblx0XHRcdFx0Y2xhc3NlczogJydcblx0XHRcdH07XG5cblx0XHRcdGlmIChvLmxhYmVsTWFwLmxlbmd0aCAmJiBvLmxhYmVsTWFwW3BhZ2VJbmRleF0pIHtcblx0XHRcdFx0b3B0aW9ucy50ZXh0ID0gby5sYWJlbE1hcFtwYWdlSW5kZXhdO1xuXHRcdFx0fVxuXG5cdFx0XHRvcHRpb25zID0gJC5leHRlbmQob3B0aW9ucywgb3B0cyB8fCB7fSk7XG5cblx0XHRcdGlmIChwYWdlSW5kZXggPT0gby5jdXJyZW50UGFnZSB8fCBvLmRpc2FibGVkKSB7XG5cdFx0XHRcdGlmIChvLmRpc2FibGVkIHx8IG9wdGlvbnMuY2xhc3NlcyA9PT0gJ3ByZXYnIHx8IG9wdGlvbnMuY2xhc3NlcyA9PT0gJ25leHQnKSB7XG5cdFx0XHRcdFx0JGxpbmtXcmFwcGVyLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHR9IGVsc2Uge1xuXHRcdFx0XHRcdCRsaW5rV3JhcHBlci5hZGRDbGFzcygnYWN0aXZlJyk7XG5cdFx0XHRcdH1cblx0XHRcdFx0JGxpbmsgPSAkKCc8c3BhbiBjbGFzcz1cImN1cnJlbnRcIj4nICsgKG9wdGlvbnMudGV4dCkgKyAnPC9zcGFuPicpO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0aWYgKG8udXNlQW5jaG9ycykge1xuXHRcdFx0XHRcdCRsaW5rID0gJCgnPGEgaHJlZj1cIicgKyBvLmhyZWZUZXh0UHJlZml4ICsgKHBhZ2VJbmRleCArIDEpICsgby5ocmVmVGV4dFN1ZmZpeCArICdcIiBjbGFzcz1cInBhZ2UtbGlua1wiPicgKyAob3B0aW9ucy50ZXh0KSArICc8L2E+Jyk7XG5cdFx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdFx0JGxpbmsgPSAkKCc8c3BhbiA+JyArIChvcHRpb25zLnRleHQpICsgJzwvc3Bhbj4nKTtcblx0XHRcdFx0fVxuXHRcdFx0XHQkbGluay5jbGljayhmdW5jdGlvbihldmVudCl7XG5cdFx0XHRcdFx0cmV0dXJuIG1ldGhvZHMuX3NlbGVjdFBhZ2UuY2FsbChzZWxmLCBwYWdlSW5kZXgsIGV2ZW50KTtcblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cblx0XHRcdGlmIChvcHRpb25zLmNsYXNzZXMpIHtcblx0XHRcdFx0JGxpbmsuYWRkQ2xhc3Mob3B0aW9ucy5jbGFzc2VzKTtcblx0XHRcdH1cblxuXHRcdFx0JGxpbmtXcmFwcGVyLmFwcGVuZCgkbGluayk7XG5cblx0XHRcdGlmICgkdWwubGVuZ3RoKSB7XG5cdFx0XHRcdCR1bC5hcHBlbmQoJGxpbmtXcmFwcGVyKTtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdHNlbGYuYXBwZW5kKCRsaW5rV3JhcHBlcik7XG5cdFx0XHR9XG5cdFx0fSxcblxuXHRcdF9zZWxlY3RQYWdlOiBmdW5jdGlvbihwYWdlSW5kZXgsIGV2ZW50KSB7XG5cdFx0XHR2YXIgbyA9IHRoaXMuZGF0YSgncGFnaW5hdGlvbicpO1xuXHRcdFx0by5jdXJyZW50UGFnZSA9IHBhZ2VJbmRleDtcblx0XHRcdGlmIChvLnNlbGVjdE9uQ2xpY2spIHtcblx0XHRcdFx0bWV0aG9kcy5fZHJhdy5jYWxsKHRoaXMpO1xuXHRcdFx0fVxuXHRcdFx0cmV0dXJuIG8ub25QYWdlQ2xpY2socGFnZUluZGV4ICsgMSwgZXZlbnQpO1xuXHRcdH0sXG5cblxuXHRcdF9lbGxpcHNlQ2xpY2s6IGZ1bmN0aW9uKCRwYW5lbCkge1xuXHRcdFx0dmFyIHNlbGYgPSB0aGlzLFxuXHRcdFx0XHRvID0gdGhpcy5kYXRhKCdwYWdpbmF0aW9uJyksXG5cdFx0XHRcdCRlbGxpcCA9ICRwYW5lbC5maW5kKCcuZWxsaXBzZScpO1xuXHRcdFx0JGVsbGlwLmFkZENsYXNzKCdjbGlja2FibGUnKS5wYXJlbnQoKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdCRlbGxpcC5jbGljayhmdW5jdGlvbihldmVudCkge1xuXHRcdFx0XHRpZiAoIW8uZGlzYWJsZSkge1xuXHRcdFx0XHRcdHZhciAkdGhpcyA9ICQodGhpcyksXG5cdFx0XHRcdFx0XHR2YWwgPSAocGFyc2VJbnQoJHRoaXMucGFyZW50KCkucHJldigpLnRleHQoKSwgMTApIHx8IDApICsgMTtcblx0XHRcdFx0XHQkdGhpc1xuXHRcdFx0XHRcdFx0Lmh0bWwoJzxpbnB1dCB0eXBlPVwibnVtYmVyXCIgbWluPVwiMVwiIG1heD1cIicgKyBvLnBhZ2VzICsgJ1wiIHN0ZXA9XCIxXCIgdmFsdWU9XCInICsgdmFsICsgJ1wiPicpXG5cdFx0XHRcdFx0XHQuZmluZCgnaW5wdXQnKVxuXHRcdFx0XHRcdFx0LmZvY3VzKClcblx0XHRcdFx0XHRcdC5jbGljayhmdW5jdGlvbihldmVudCkge1xuXHRcdFx0XHRcdFx0XHQvLyBwcmV2ZW50IGlucHV0IG51bWJlciBhcnJvd3MgZnJvbSBidWJibGluZyBhIGNsaWNrIGV2ZW50IG9uICRlbGxpcFxuXHRcdFx0XHRcdFx0XHRldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcblx0XHRcdFx0XHRcdH0pXG5cdFx0XHRcdFx0XHQua2V5dXAoZnVuY3Rpb24oZXZlbnQpIHtcblx0XHRcdFx0XHRcdFx0dmFyIHZhbCA9ICQodGhpcykudmFsKCk7XG5cdFx0XHRcdFx0XHRcdGlmIChldmVudC53aGljaCA9PT0gMTMgJiYgdmFsICE9PSAnJykge1xuXHRcdFx0XHRcdFx0XHRcdC8vIGVudGVyIHRvIGFjY2VwdFxuXHRcdFx0XHRcdFx0XHRcdGlmICgodmFsPjApJiYodmFsPD1vLnBhZ2VzKSlcblx0XHRcdFx0XHRcdFx0XHRtZXRob2RzLl9zZWxlY3RQYWdlLmNhbGwoc2VsZiwgdmFsIC0gMSk7XG5cdFx0XHRcdFx0XHRcdH0gZWxzZSBpZiAoZXZlbnQud2hpY2ggPT09IDI3KSB7XG5cdFx0XHRcdFx0XHRcdFx0Ly8gZXNjYXBlIHRvIGNhbmNlbFxuXHRcdFx0XHRcdFx0XHRcdCRlbGxpcC5lbXB0eSgpLmh0bWwoby5lbGxpcHNlVGV4dCk7XG5cdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdH0pXG5cdFx0XHRcdFx0XHQuYmluZCgnYmx1cicsIGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0XHRcdFx0XHRcdHZhciB2YWwgPSAkKHRoaXMpLnZhbCgpO1xuXHRcdFx0XHRcdFx0XHRpZiAodmFsICE9PSAnJykge1xuXHRcdFx0XHRcdFx0XHRcdG1ldGhvZHMuX3NlbGVjdFBhZ2UuY2FsbChzZWxmLCB2YWwgLSAxKTtcblx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHQkZWxsaXAuZW1wdHkoKS5odG1sKG8uZWxsaXBzZVRleHQpO1xuXHRcdFx0XHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHRcdFx0XHR9KTtcblx0XHRcdFx0fVxuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XG5cdFx0XHR9KTtcblx0XHR9XG5cblx0fTtcblxuXHQkLmZuLnBhZ2luYXRpb24gPSBmdW5jdGlvbihtZXRob2QpIHtcblxuXHRcdC8vIE1ldGhvZCBjYWxsaW5nIGxvZ2ljXG5cdFx0aWYgKG1ldGhvZHNbbWV0aG9kXSAmJiBtZXRob2QuY2hhckF0KDApICE9ICdfJykge1xuXHRcdFx0cmV0dXJuIG1ldGhvZHNbbWV0aG9kXS5hcHBseSh0aGlzLCBBcnJheS5wcm90b3R5cGUuc2xpY2UuY2FsbChhcmd1bWVudHMsIDEpKTtcblx0XHR9IGVsc2UgaWYgKHR5cGVvZiBtZXRob2QgPT09ICdvYmplY3QnIHx8ICFtZXRob2QpIHtcblx0XHRcdHJldHVybiBtZXRob2RzLmluaXQuYXBwbHkodGhpcywgYXJndW1lbnRzKTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0JC5lcnJvcignTWV0aG9kICcgKyAgbWV0aG9kICsgJyBkb2VzIG5vdCBleGlzdCBvbiBqUXVlcnkucGFnaW5hdGlvbicpO1xuXHRcdH1cblxuXHR9O1xuXG59KShqUXVlcnkpOyIsIi8qIVxuICogIEBwcmVzZXJ2ZVxuICogXG4gKiBSZWFkbW9yZS5qcyBwbHVnaW5cbiAqIEF1dGhvcjogQGplZF9mb3N0ZXJcbiAqIFByb2plY3QgaG9tZTogamVkZm9zdGVyLmNvbS9SZWFkbW9yZS5qc1xuICogVmVyc2lvbjogMy4wLjAtYmV0YS0xXG4gKiBMaWNlbnNlZCB1bmRlciB0aGUgTUlUIGxpY2Vuc2VcbiAqIFxuICogRGVib3VuY2UgZnVuY3Rpb24gZnJvbSBkYXZpZHdhbHNoLm5hbWUvamF2YXNjcmlwdC1kZWJvdW5jZS1mdW5jdGlvblxuICovXG4oZnVuY3Rpb24gd2VicGFja1VuaXZlcnNhbE1vZHVsZURlZmluaXRpb24ocm9vdCwgZmFjdG9yeSkge1xuICBpZih0eXBlb2YgZXhwb3J0cyA9PT0gJ29iamVjdCcgJiYgdHlwZW9mIG1vZHVsZSA9PT0gJ29iamVjdCcpXG4gICAgbW9kdWxlLmV4cG9ydHMgPSBmYWN0b3J5KCk7XG4gIGVsc2UgaWYodHlwZW9mIGRlZmluZSA9PT0gJ2Z1bmN0aW9uJyAmJiBkZWZpbmUuYW1kKVxuICAgIGRlZmluZShcIlJlYWRtb3JlXCIsIFtdLCBmYWN0b3J5KTtcbiAgZWxzZSBpZih0eXBlb2YgZXhwb3J0cyA9PT0gJ29iamVjdCcpXG4gICAgZXhwb3J0c1tcIlJlYWRtb3JlXCJdID0gZmFjdG9yeSgpO1xuICBlbHNlXG4gICAgcm9vdFtcIlJlYWRtb3JlXCJdID0gZmFjdG9yeSgpO1xufSkod2luZG93LCBmdW5jdGlvbigpIHtcbnJldHVybiAvKioqKioqLyAoZnVuY3Rpb24obW9kdWxlcykgeyAvLyB3ZWJwYWNrQm9vdHN0cmFwXG4vKioqKioqLyAgLy8gVGhlIG1vZHVsZSBjYWNoZVxuLyoqKioqKi8gIHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG4vKioqKioqL1xuLyoqKioqKi8gIC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4vKioqKioqLyAgZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuLyoqKioqKi9cbi8qKioqKiovICAgIC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuLyoqKioqKi8gICAgaWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbi8qKioqKiovICAgICAgcmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4vKioqKioqLyAgICB9XG4vKioqKioqLyAgICAvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuLyoqKioqKi8gICAgdmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuLyoqKioqKi8gICAgICBpOiBtb2R1bGVJZCxcbi8qKioqKiovICAgICAgbDogZmFsc2UsXG4vKioqKioqLyAgICAgIGV4cG9ydHM6IHt9XG4vKioqKioqLyAgICB9O1xuLyoqKioqKi9cbi8qKioqKiovICAgIC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuLyoqKioqKi8gICAgbW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG4vKioqKioqL1xuLyoqKioqKi8gICAgLy8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuLyoqKioqKi8gICAgbW9kdWxlLmwgPSB0cnVlO1xuLyoqKioqKi9cbi8qKioqKiovICAgIC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4vKioqKioqLyAgICByZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4vKioqKioqLyAgfVxuLyoqKioqKi9cbi8qKioqKiovXG4vKioqKioqLyAgLy8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbi8qKioqKiovICBfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuLyoqKioqKi9cbi8qKioqKiovICAvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuLyoqKioqKi8gIF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG4vKioqKioqL1xuLyoqKioqKi8gIC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuLyoqKioqKi8gIF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuLyoqKioqKi8gICAgaWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuLyoqKioqKi8gICAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGdldHRlciB9KTtcbi8qKioqKiovICAgIH1cbi8qKioqKiovICB9O1xuLyoqKioqKi9cbi8qKioqKiovICAvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4vKioqKioqLyAgX193ZWJwYWNrX3JlcXVpcmVfXy5yID0gZnVuY3Rpb24oZXhwb3J0cykge1xuLyoqKioqKi8gICAgaWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4vKioqKioqLyAgICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuLyoqKioqKi8gICAgfVxuLyoqKioqKi8gICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbi8qKioqKiovICB9O1xuLyoqKioqKi9cbi8qKioqKiovICAvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3Rcbi8qKioqKiovICAvLyBtb2RlICYgMTogdmFsdWUgaXMgYSBtb2R1bGUgaWQsIHJlcXVpcmUgaXRcbi8qKioqKiovICAvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbi8qKioqKiovICAvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3Rcbi8qKioqKiovICAvLyBtb2RlICYgOHwxOiBiZWhhdmUgbGlrZSByZXF1aXJlXG4vKioqKioqLyAgX193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbi8qKioqKiovICAgIGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuLyoqKioqKi8gICAgaWYobW9kZSAmIDgpIHJldHVybiB2YWx1ZTtcbi8qKioqKiovICAgIGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4vKioqKioqLyAgICB2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuLyoqKioqKi8gICAgX193ZWJwYWNrX3JlcXVpcmVfXy5yKG5zKTtcbi8qKioqKiovICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbi8qKioqKiovICAgIGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbi8qKioqKiovICAgIHJldHVybiBucztcbi8qKioqKiovICB9O1xuLyoqKioqKi9cbi8qKioqKiovICAvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuLyoqKioqKi8gIF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuLyoqKioqKi8gICAgdmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4vKioqKioqLyAgICAgIGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4vKioqKioqLyAgICAgIGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4vKioqKioqLyAgICBfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4vKioqKioqLyAgICByZXR1cm4gZ2V0dGVyO1xuLyoqKioqKi8gIH07XG4vKioqKioqL1xuLyoqKioqKi8gIC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuLyoqKioqKi8gIF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcbi8qKioqKiovXG4vKioqKioqLyAgLy8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbi8qKioqKiovICBfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuLyoqKioqKi9cbi8qKioqKiovXG4vKioqKioqLyAgLy8gTG9hZCBlbnRyeSBtb2R1bGUgYW5kIHJldHVybiBleHBvcnRzXG4vKioqKioqLyAgcmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG4vKioqKioqLyB9KVxuLyoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cbi8qKioqKiovICh7XG5cbi8qKiovIFwiLi9ub2RlX21vZHVsZXMvQGJhYmVsL3J1bnRpbWUvaGVscGVycy9jbGFzc0NhbGxDaGVjay5qc1wiOlxuLyohKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqISpcXFxuICAhKioqIC4vbm9kZV9tb2R1bGVzL0BiYWJlbC9ydW50aW1lL2hlbHBlcnMvY2xhc3NDYWxsQ2hlY2suanMgKioqIVxuICBcXCoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cbi8qISBubyBzdGF0aWMgZXhwb3J0cyBmb3VuZCAqL1xuLyoqKi8gKGZ1bmN0aW9uKG1vZHVsZSwgZXhwb3J0cykge1xuXG5mdW5jdGlvbiBfY2xhc3NDYWxsQ2hlY2soaW5zdGFuY2UsIENvbnN0cnVjdG9yKSB7XG4gIGlmICghKGluc3RhbmNlIGluc3RhbmNlb2YgQ29uc3RydWN0b3IpKSB7XG4gICAgdGhyb3cgbmV3IFR5cGVFcnJvcihcIkNhbm5vdCBjYWxsIGEgY2xhc3MgYXMgYSBmdW5jdGlvblwiKTtcbiAgfVxufVxuXG5tb2R1bGUuZXhwb3J0cyA9IF9jbGFzc0NhbGxDaGVjaztcblxuLyoqKi8gfSksXG5cbi8qKiovIFwiLi9ub2RlX21vZHVsZXMvQGJhYmVsL3J1bnRpbWUvaGVscGVycy9jcmVhdGVDbGFzcy5qc1wiOlxuLyohKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqISpcXFxuICAhKioqIC4vbm9kZV9tb2R1bGVzL0BiYWJlbC9ydW50aW1lL2hlbHBlcnMvY3JlYXRlQ2xhc3MuanMgKioqIVxuICBcXCoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cbi8qISBubyBzdGF0aWMgZXhwb3J0cyBmb3VuZCAqL1xuLyoqKi8gKGZ1bmN0aW9uKG1vZHVsZSwgZXhwb3J0cykge1xuXG5mdW5jdGlvbiBfZGVmaW5lUHJvcGVydGllcyh0YXJnZXQsIHByb3BzKSB7XG4gIGZvciAodmFyIGkgPSAwOyBpIDwgcHJvcHMubGVuZ3RoOyBpKyspIHtcbiAgICB2YXIgZGVzY3JpcHRvciA9IHByb3BzW2ldO1xuICAgIGRlc2NyaXB0b3IuZW51bWVyYWJsZSA9IGRlc2NyaXB0b3IuZW51bWVyYWJsZSB8fCBmYWxzZTtcbiAgICBkZXNjcmlwdG9yLmNvbmZpZ3VyYWJsZSA9IHRydWU7XG4gICAgaWYgKFwidmFsdWVcIiBpbiBkZXNjcmlwdG9yKSBkZXNjcmlwdG9yLndyaXRhYmxlID0gdHJ1ZTtcbiAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkodGFyZ2V0LCBkZXNjcmlwdG9yLmtleSwgZGVzY3JpcHRvcik7XG4gIH1cbn1cblxuZnVuY3Rpb24gX2NyZWF0ZUNsYXNzKENvbnN0cnVjdG9yLCBwcm90b1Byb3BzLCBzdGF0aWNQcm9wcykge1xuICBpZiAocHJvdG9Qcm9wcykgX2RlZmluZVByb3BlcnRpZXMoQ29uc3RydWN0b3IucHJvdG90eXBlLCBwcm90b1Byb3BzKTtcbiAgaWYgKHN0YXRpY1Byb3BzKSBfZGVmaW5lUHJvcGVydGllcyhDb25zdHJ1Y3Rvciwgc3RhdGljUHJvcHMpO1xuICByZXR1cm4gQ29uc3RydWN0b3I7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gX2NyZWF0ZUNsYXNzO1xuXG4vKioqLyB9KSxcblxuLyoqKi8gXCIuL25vZGVfbW9kdWxlcy9AYmFiZWwvcnVudGltZS9oZWxwZXJzL3R5cGVvZi5qc1wiOlxuLyohKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiEqXFxcbiAgISoqKiAuL25vZGVfbW9kdWxlcy9AYmFiZWwvcnVudGltZS9oZWxwZXJzL3R5cGVvZi5qcyAqKiohXG4gIFxcKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKi9cbi8qISBubyBzdGF0aWMgZXhwb3J0cyBmb3VuZCAqL1xuLyoqKi8gKGZ1bmN0aW9uKG1vZHVsZSwgZXhwb3J0cykge1xuXG5mdW5jdGlvbiBfdHlwZW9mMihvYmopIHsgaWYgKHR5cGVvZiBTeW1ib2wgPT09IFwiZnVuY3Rpb25cIiAmJiB0eXBlb2YgU3ltYm9sLml0ZXJhdG9yID09PSBcInN5bWJvbFwiKSB7IF90eXBlb2YyID0gZnVuY3Rpb24gX3R5cGVvZjIob2JqKSB7IHJldHVybiB0eXBlb2Ygb2JqOyB9OyB9IGVsc2UgeyBfdHlwZW9mMiA9IGZ1bmN0aW9uIF90eXBlb2YyKG9iaikgeyByZXR1cm4gb2JqICYmIHR5cGVvZiBTeW1ib2wgPT09IFwiZnVuY3Rpb25cIiAmJiBvYmouY29uc3RydWN0b3IgPT09IFN5bWJvbCAmJiBvYmogIT09IFN5bWJvbC5wcm90b3R5cGUgPyBcInN5bWJvbFwiIDogdHlwZW9mIG9iajsgfTsgfSByZXR1cm4gX3R5cGVvZjIob2JqKTsgfVxuXG5mdW5jdGlvbiBfdHlwZW9mKG9iaikge1xuICBpZiAodHlwZW9mIFN5bWJvbCA9PT0gXCJmdW5jdGlvblwiICYmIF90eXBlb2YyKFN5bWJvbC5pdGVyYXRvcikgPT09IFwic3ltYm9sXCIpIHtcbiAgICBtb2R1bGUuZXhwb3J0cyA9IF90eXBlb2YgPSBmdW5jdGlvbiBfdHlwZW9mKG9iaikge1xuICAgICAgcmV0dXJuIF90eXBlb2YyKG9iaik7XG4gICAgfTtcbiAgfSBlbHNlIHtcbiAgICBtb2R1bGUuZXhwb3J0cyA9IF90eXBlb2YgPSBmdW5jdGlvbiBfdHlwZW9mKG9iaikge1xuICAgICAgcmV0dXJuIG9iaiAmJiB0eXBlb2YgU3ltYm9sID09PSBcImZ1bmN0aW9uXCIgJiYgb2JqLmNvbnN0cnVjdG9yID09PSBTeW1ib2wgJiYgb2JqICE9PSBTeW1ib2wucHJvdG90eXBlID8gXCJzeW1ib2xcIiA6IF90eXBlb2YyKG9iaik7XG4gICAgfTtcbiAgfVxuXG4gIHJldHVybiBfdHlwZW9mKG9iaik7XG59XG5cbm1vZHVsZS5leHBvcnRzID0gX3R5cGVvZjtcblxuLyoqKi8gfSksXG5cbi8qKiovIFwiLi9zcmMvcmVhZG1vcmUuanNcIjpcbi8qISoqKioqKioqKioqKioqKioqKioqKioqKiohKlxcXG4gICEqKiogLi9zcmMvcmVhZG1vcmUuanMgKioqIVxuICBcXCoqKioqKioqKioqKioqKioqKioqKioqKiovXG4vKiEgZXhwb3J0cyBwcm92aWRlZDogZGVmYXVsdCAqL1xuLyoqKi8gKGZ1bmN0aW9uKG1vZHVsZSwgX193ZWJwYWNrX2V4cG9ydHNfXywgX193ZWJwYWNrX3JlcXVpcmVfXykge1xuXG5cInVzZSBzdHJpY3RcIjtcbl9fd2VicGFja19yZXF1aXJlX18ucihfX3dlYnBhY2tfZXhwb3J0c19fKTtcbi8qIGhhcm1vbnkgaW1wb3J0ICovIHZhciBfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NsYXNzQ2FsbENoZWNrX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8wX18gPSBfX3dlYnBhY2tfcmVxdWlyZV9fKC8qISBAYmFiZWwvcnVudGltZS9oZWxwZXJzL2NsYXNzQ2FsbENoZWNrICovIFwiLi9ub2RlX21vZHVsZXMvQGJhYmVsL3J1bnRpbWUvaGVscGVycy9jbGFzc0NhbGxDaGVjay5qc1wiKTtcbi8qIGhhcm1vbnkgaW1wb3J0ICovIHZhciBfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NsYXNzQ2FsbENoZWNrX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8wX19fZGVmYXVsdCA9IC8qI19fUFVSRV9fKi9fX3dlYnBhY2tfcmVxdWlyZV9fLm4oX2JhYmVsX3J1bnRpbWVfaGVscGVyc19jbGFzc0NhbGxDaGVja19fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMF9fKTtcbi8qIGhhcm1vbnkgaW1wb3J0ICovIHZhciBfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NyZWF0ZUNsYXNzX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8xX18gPSBfX3dlYnBhY2tfcmVxdWlyZV9fKC8qISBAYmFiZWwvcnVudGltZS9oZWxwZXJzL2NyZWF0ZUNsYXNzICovIFwiLi9ub2RlX21vZHVsZXMvQGJhYmVsL3J1bnRpbWUvaGVscGVycy9jcmVhdGVDbGFzcy5qc1wiKTtcbi8qIGhhcm1vbnkgaW1wb3J0ICovIHZhciBfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NyZWF0ZUNsYXNzX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8xX19fZGVmYXVsdCA9IC8qI19fUFVSRV9fKi9fX3dlYnBhY2tfcmVxdWlyZV9fLm4oX2JhYmVsX3J1bnRpbWVfaGVscGVyc19jcmVhdGVDbGFzc19fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMV9fKTtcbi8qIGhhcm1vbnkgaW1wb3J0ICovIHZhciBfYmFiZWxfcnVudGltZV9oZWxwZXJzX3R5cGVvZl9fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMl9fID0gX193ZWJwYWNrX3JlcXVpcmVfXygvKiEgQGJhYmVsL3J1bnRpbWUvaGVscGVycy90eXBlb2YgKi8gXCIuL25vZGVfbW9kdWxlcy9AYmFiZWwvcnVudGltZS9oZWxwZXJzL3R5cGVvZi5qc1wiKTtcbi8qIGhhcm1vbnkgaW1wb3J0ICovIHZhciBfYmFiZWxfcnVudGltZV9oZWxwZXJzX3R5cGVvZl9fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMl9fX2RlZmF1bHQgPSAvKiNfX1BVUkVfXyovX193ZWJwYWNrX3JlcXVpcmVfXy5uKF9iYWJlbF9ydW50aW1lX2hlbHBlcnNfdHlwZW9mX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8yX18pO1xuXG5cblxudmFyIHVuaXF1ZUlkQ291bnRlciA9IDA7XG52YXIgaXNDc3NFbWJlZGRlZEZvciA9IFtdOyAvLyBmcm9tOmh0dHBzOi8vZ2l0aHViLmNvbS9qc2Vyei9qc19waWVjZS9ibG9iL21hc3Rlci9ET00vQ2hpbGROb2RlL3JlbW92ZSgpL3JlbW92ZSgpLm1kXG5cbihmdW5jdGlvbiByZW1vdmVQb2x5ZmlsbChhcnIpIHtcbiAgYXJyLmZvckVhY2goZnVuY3Rpb24gKGl0ZW0pIHtcbiAgICBpZiAoT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKGl0ZW0sICdyZW1vdmUnKSkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIE9iamVjdC5kZWZpbmVQcm9wZXJ0eShpdGVtLCAncmVtb3ZlJywge1xuICAgICAgY29uZmlndXJhYmxlOiB0cnVlLFxuICAgICAgZW51bWVyYWJsZTogdHJ1ZSxcbiAgICAgIHdyaXRhYmxlOiB0cnVlLFxuICAgICAgdmFsdWU6IGZ1bmN0aW9uIHJlbW92ZSgpIHtcbiAgICAgICAgaWYgKHRoaXMucGFyZW50Tm9kZSAhPT0gbnVsbCkge1xuICAgICAgICAgIHRoaXMucGFyZW50Tm9kZS5yZW1vdmVDaGlsZCh0aGlzKTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0pO1xuICB9KTtcbn0pKFtFbGVtZW50LnByb3RvdHlwZSwgQ2hhcmFjdGVyRGF0YS5wcm90b3R5cGUsIERvY3VtZW50VHlwZS5wcm90b3R5cGVdKTtcblxuZnVuY3Rpb24gZm9yRWFjaChhcnIsIGNhbGxiYWNrLCBzY29wZSkge1xuICBmb3IgKHZhciBpID0gMDsgaSA8IGFyci5sZW5ndGg7IGkgKz0gMSkge1xuICAgIGNhbGxiYWNrLmNhbGwoc2NvcGUsIGFycltpXSwgaSk7XG4gIH1cbn1cblxuZnVuY3Rpb24gZXh0ZW5kKCkge1xuICBmb3IgKHZhciBfbGVuID0gYXJndW1lbnRzLmxlbmd0aCwgb2JqZWN0cyA9IG5ldyBBcnJheShfbGVuKSwgX2tleSA9IDA7IF9rZXkgPCBfbGVuOyBfa2V5KyspIHtcbiAgICBvYmplY3RzW19rZXldID0gYXJndW1lbnRzW19rZXldO1xuICB9XG5cbiAgdmFyIGhhc1Byb3AgPSB7fS5oYXNPd25Qcm9wZXJ0eTtcbiAgdmFyIGNoaWxkID0gb2JqZWN0c1swXTtcbiAgdmFyIHBhcmVudCA9IG9iamVjdHNbMV07XG5cbiAgaWYgKG9iamVjdHMubGVuZ3RoID4gMikge1xuICAgIHZhciBhcmdzID0gW107XG4gICAgT2JqZWN0LmtleXMob2JqZWN0cykuZm9yRWFjaChmdW5jdGlvbiAoa2V5KSB7XG4gICAgICBhcmdzLnB1c2gob2JqZWN0c1trZXldKTtcbiAgICB9KTtcblxuICAgIHdoaWxlIChhcmdzLmxlbmd0aCA+IDIpIHtcbiAgICAgIHZhciBjMSA9IGFyZ3Muc2hpZnQoKTtcbiAgICAgIHZhciBwMSA9IGFyZ3Muc2hpZnQoKTtcbiAgICAgIGFyZ3MudW5zaGlmdChleHRlbmQoYzEsIHAxKSk7XG4gICAgfVxuXG4gICAgY2hpbGQgPSBhcmdzLnNoaWZ0KCk7XG4gICAgcGFyZW50ID0gYXJncy5zaGlmdCgpO1xuICB9XG5cbiAgaWYgKHBhcmVudCkge1xuICAgIE9iamVjdC5rZXlzKHBhcmVudCkuZm9yRWFjaChmdW5jdGlvbiAoa2V5KSB7XG4gICAgICBpZiAoaGFzUHJvcC5jYWxsKHBhcmVudCwga2V5KSkge1xuICAgICAgICBpZiAoX2JhYmVsX3J1bnRpbWVfaGVscGVyc190eXBlb2ZfX1dFQlBBQ0tfSU1QT1JURURfTU9EVUxFXzJfX19kZWZhdWx0KCkocGFyZW50W2tleV0pID09PSAnb2JqZWN0Jykge1xuICAgICAgICAgIGNoaWxkW2tleV0gPSBjaGlsZFtrZXldIHx8IHt9O1xuICAgICAgICAgIGNoaWxkW2tleV0gPSBleHRlbmQoY2hpbGRba2V5XSwgcGFyZW50W2tleV0pO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGNoaWxkW2tleV0gPSBwYXJlbnRba2V5XTtcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH0pO1xuICB9XG5cbiAgcmV0dXJuIGNoaWxkO1xufVxuXG5mdW5jdGlvbiBkZWJvdW5jZShmdW5jLCB3YWl0LCBpbW1lZGlhdGUpIHtcbiAgdmFyIHRpbWVvdXQ7XG4gIHJldHVybiBmdW5jdGlvbiBkZWJvdW5jZWRGdW5jKCkge1xuICAgIHZhciBfdGhpcyA9IHRoaXM7XG5cbiAgICBmb3IgKHZhciBfbGVuMiA9IGFyZ3VtZW50cy5sZW5ndGgsIGFyZ3MgPSBuZXcgQXJyYXkoX2xlbjIpLCBfa2V5MiA9IDA7IF9rZXkyIDwgX2xlbjI7IF9rZXkyKyspIHtcbiAgICAgIGFyZ3NbX2tleTJdID0gYXJndW1lbnRzW19rZXkyXTtcbiAgICB9XG5cbiAgICB2YXIgY2FsbE5vdyA9IGltbWVkaWF0ZSAmJiAhdGltZW91dDtcblxuICAgIHZhciBsYXRlciA9IGZ1bmN0aW9uIGxhdGVyKCkge1xuICAgICAgdGltZW91dCA9IG51bGw7XG4gICAgICBpZiAoIWltbWVkaWF0ZSkgZnVuYy5hcHBseShfdGhpcywgYXJncyk7XG4gICAgfTtcblxuICAgIGNsZWFyVGltZW91dCh0aW1lb3V0KTtcbiAgICB0aW1lb3V0ID0gc2V0VGltZW91dChsYXRlciwgd2FpdCk7XG4gICAgaWYgKGNhbGxOb3cpIGZ1bmMuYXBwbHkodGhpcywgYXJncyk7XG4gIH07XG59XG5cbmZ1bmN0aW9uIHVuaXF1ZUlkKCkge1xuICB1bmlxdWVJZENvdW50ZXIgKz0gMTtcbiAgcmV0dXJuIFwicm1qcy1cIi5jb25jYXQodW5pcXVlSWRDb3VudGVyKTtcbn1cblxuZnVuY3Rpb24gc2V0Qm94SGVpZ2h0cyhlbGVtZW50KSB7XG4gIGVsZW1lbnQuc3R5bGUuaGVpZ2h0ID0gJ2F1dG8nO1xuICB2YXIgZXhwYW5kZWRIZWlnaHQgPSBwYXJzZUludChlbGVtZW50LmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpLmhlaWdodCwgMTApO1xuICB2YXIgY3NzTWF4SGVpZ2h0ID0gcGFyc2VJbnQod2luZG93LmdldENvbXB1dGVkU3R5bGUoZWxlbWVudCkubWF4SGVpZ2h0LCAxMCk7XG4gIHZhciBkZWZhdWx0SGVpZ2h0ID0gcGFyc2VJbnQoZWxlbWVudC5yZWFkbW9yZS5kZWZhdWx0SGVpZ2h0LCAxMCk7IC8vIFN0b3JlIG91ciBtZWFzdXJlbWVudHMuXG5cbiAgZWxlbWVudC5yZWFkbW9yZS5leHBhbmRlZEhlaWdodCA9IGV4cGFuZGVkSGVpZ2h0O1xuICBlbGVtZW50LnJlYWRtb3JlLm1heEhlaWdodCA9IGNzc01heEhlaWdodDtcbiAgZWxlbWVudC5yZWFkbW9yZS5jb2xsYXBzZWRIZWlnaHQgPSBjc3NNYXhIZWlnaHQgfHwgZWxlbWVudC5yZWFkbW9yZS5jb2xsYXBzZWRIZWlnaHQgfHwgZGVmYXVsdEhlaWdodDtcbiAgZWxlbWVudC5zdHlsZS5tYXhIZWlnaHQgPSAnbm9uZSc7XG59XG5cbmZ1bmN0aW9uIGNyZWF0ZUVsZW1lbnRGcm9tU3RyaW5nKGh0bWxTdHJpbmcpIHtcbiAgdmFyIGRpdiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuICBkaXYuaW5uZXJIVE1MID0gaHRtbFN0cmluZztcbiAgcmV0dXJuIGRpdi5maXJzdENoaWxkO1xufVxuXG5mdW5jdGlvbiBlbWJlZENTUyhzZWxlY3Rvciwgb3B0aW9ucykge1xuICBpZiAoIWlzQ3NzRW1iZWRkZWRGb3Jbc2VsZWN0b3JdKSB7XG4gICAgdmFyIHN0eWxlcyA9ICcnO1xuXG4gICAgaWYgKG9wdGlvbnMuZW1iZWRDU1MgJiYgb3B0aW9ucy5ibG9ja0NTUyAhPT0gJycpIHtcbiAgICAgIHN0eWxlcyArPSBcIlwiLmNvbmNhdChzZWxlY3RvciwgXCIgKyBbZGF0YS1yZWFkbW9yZS10b2dnbGVdLCBcIikuY29uY2F0KHNlbGVjdG9yLCBcIltkYXRhLXJlYWRtb3JlXSB7XFxuICAgICAgICBcIikuY29uY2F0KG9wdGlvbnMuYmxvY2tDU1MsIFwiXFxuICAgICAgfVwiKTtcbiAgICB9IC8vIEluY2x1ZGUgdGhlIHRyYW5zaXRpb24gQ1NTIGV2ZW4gaWYgZW1iZWRDU1MgaXMgZmFsc2VcblxuXG4gICAgc3R5bGVzICs9IFwiXCIuY29uY2F0KHNlbGVjdG9yLCBcIltkYXRhLXJlYWRtb3JlXSB7XFxuICAgICAgdHJhbnNpdGlvbjogaGVpZ2h0IFwiKS5jb25jYXQob3B0aW9ucy5zcGVlZCwgXCJtcztcXG4gICAgICBvdmVyZmxvdzogaGlkZGVuO1xcbiAgICB9XCIpO1xuXG4gICAgKGZ1bmN0aW9uIChkLCB1KSB7XG4gICAgICB2YXIgY3NzID0gZC5jcmVhdGVFbGVtZW50KCdzdHlsZScpO1xuICAgICAgY3NzLnR5cGUgPSAndGV4dC9jc3MnO1xuXG4gICAgICBpZiAoY3NzLnN0eWxlU2hlZXQpIHtcbiAgICAgICAgY3NzLnN0eWxlU2hlZXQuY3NzVGV4dCA9IHU7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBjc3MuYXBwZW5kQ2hpbGQoZC5jcmVhdGVUZXh0Tm9kZSh1KSk7XG4gICAgICB9XG5cbiAgICAgIGQuZ2V0RWxlbWVudHNCeVRhZ05hbWUoJ2hlYWQnKVswXS5hcHBlbmRDaGlsZChjc3MpO1xuICAgIH0pKGRvY3VtZW50LCBzdHlsZXMpO1xuXG4gICAgaXNDc3NFbWJlZGRlZEZvcltzZWxlY3Rvcl0gPSB0cnVlO1xuICB9XG59XG5cbmZ1bmN0aW9uIGJ1aWxkVG9nZ2xlKGxpbmssIGVsZW1lbnQsIHNjb3BlKSB7XG4gIGZ1bmN0aW9uIGNsaWNrSGFuZGxlcihldmVudCkge1xuICAgIHRoaXMudG9nZ2xlKGVsZW1lbnQsIGV2ZW50KTtcbiAgfVxuXG4gIHZhciB0ZXh0ID0gbGluaztcblxuICBpZiAodHlwZW9mIGxpbmsgPT09ICdmdW5jdGlvbicpIHtcbiAgICB0ZXh0ID0gbGluayhlbGVtZW50KTtcbiAgfVxuXG4gIHZhciB0b2dnbGVMaW5rID0gY3JlYXRlRWxlbWVudEZyb21TdHJpbmcodGV4dCk7XG4gIHRvZ2dsZUxpbmsuc2V0QXR0cmlidXRlKCdkYXRhLXJlYWRtb3JlLXRvZ2dsZScsIGVsZW1lbnQuaWQpO1xuICB0b2dnbGVMaW5rLnNldEF0dHJpYnV0ZSgnYXJpYS1jb250cm9scycsIGVsZW1lbnQuaWQpO1xuICB0b2dnbGVMaW5rLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xpY2tIYW5kbGVyLmJpbmQoc2NvcGUpKTtcbiAgcmV0dXJuIHRvZ2dsZUxpbms7XG59XG5cbmZ1bmN0aW9uIGlzRW52aXJvbm1lbnRTdXBwb3J0ZWQoKSB7XG4gIHJldHVybiB0eXBlb2Ygd2luZG93ICE9PSAndW5kZWZpbmVkJyAmJiB0eXBlb2YgZG9jdW1lbnQgIT09ICd1bmRlZmluZWQnICYmICEhZG9jdW1lbnQucXVlcnlTZWxlY3RvckFsbCAmJiAhIXdpbmRvdy5hZGRFdmVudExpc3RlbmVyO1xufVxuXG52YXIgcmVzaXplQm94ZXMgPSBkZWJvdW5jZShmdW5jdGlvbiAoKSB7XG4gIHZhciBlbGVtZW50cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJ1tkYXRhLXJlYWRtb3JlXScpO1xuICBmb3JFYWNoKGVsZW1lbnRzLCBmdW5jdGlvbiAoZWxlbWVudCkge1xuICAgIHZhciBleHBhbmRlZCA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKCdhcmlhLWV4cGFuZGVkJykgPT09ICd0cnVlJztcbiAgICBzZXRCb3hIZWlnaHRzKGVsZW1lbnQpO1xuICAgIGVsZW1lbnQuc3R5bGUuaGVpZ2h0ID0gXCJcIi5jb25jYXQoZXhwYW5kZWQgPyBlbGVtZW50LnJlYWRtb3JlLmV4cGFuZGVkSGVpZ2h0IDogZWxlbWVudC5yZWFkbW9yZS5jb2xsYXBzZWRIZWlnaHQsIFwicHhcIik7XG4gIH0pO1xufSwgMTAwKTtcbnZhciBkZWZhdWx0cyA9IHtcbiAgc3BlZWQ6IDEwMCxcbiAgY29sbGFwc2VkSGVpZ2h0OiAyMDAsXG4gIGhlaWdodE1hcmdpbjogMTYsXG4gIG1vcmVMaW5rOiAnPGEgaHJlZj1cIiNcIj5SZWFkIE1vcmU8L2E+JyxcbiAgbGVzc0xpbms6ICc8YSBocmVmPVwiI1wiPkNsb3NlPC9hPicsXG4gIGVtYmVkQ1NTOiB0cnVlLFxuICBibG9ja0NTUzogJ2Rpc3BsYXk6IGJsb2NrOyB3aWR0aDogMTAwJTsnLFxuICBzdGFydE9wZW46IGZhbHNlLFxuICBzb3VyY2VPcmRlcjogJ2FmdGVyJyxcbiAgLy8gY2FsbGJhY2tzXG4gIGJsb2NrUHJvY2Vzc2VkOiBmdW5jdGlvbiBibG9ja1Byb2Nlc3NlZCgpIHt9LFxuICBiZWZvcmVUb2dnbGU6IGZ1bmN0aW9uIGJlZm9yZVRvZ2dsZSgpIHt9LFxuICBhZnRlclRvZ2dsZTogZnVuY3Rpb24gYWZ0ZXJUb2dnbGUoKSB7fVxufTtcblxudmFyIFJlYWRtb3JlID1cbi8qI19fUFVSRV9fKi9cbmZ1bmN0aW9uICgpIHtcbiAgZnVuY3Rpb24gUmVhZG1vcmUoKSB7XG4gICAgdmFyIF90aGlzMiA9IHRoaXM7XG5cbiAgICBfYmFiZWxfcnVudGltZV9oZWxwZXJzX2NsYXNzQ2FsbENoZWNrX19XRUJQQUNLX0lNUE9SVEVEX01PRFVMRV8wX19fZGVmYXVsdCgpKHRoaXMsIFJlYWRtb3JlKTtcblxuICAgIGlmICghaXNFbnZpcm9ubWVudFN1cHBvcnRlZCgpKSByZXR1cm47XG5cbiAgICBmb3IgKHZhciBfbGVuMyA9IGFyZ3VtZW50cy5sZW5ndGgsIGFyZ3MgPSBuZXcgQXJyYXkoX2xlbjMpLCBfa2V5MyA9IDA7IF9rZXkzIDwgX2xlbjM7IF9rZXkzKyspIHtcbiAgICAgIGFyZ3NbX2tleTNdID0gYXJndW1lbnRzW19rZXkzXTtcbiAgICB9XG5cbiAgICB2YXIgc2VsZWN0b3IgPSBhcmdzWzBdLFxuICAgICAgICBvcHRpb25zID0gYXJnc1sxXTtcbiAgICB2YXIgZWxlbWVudHM7XG5cbiAgICBpZiAodHlwZW9mIHNlbGVjdG9yID09PSAnc3RyaW5nJykge1xuICAgICAgZWxlbWVudHMgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yQWxsKHNlbGVjdG9yKTtcbiAgICB9IGVsc2UgaWYgKHNlbGVjdG9yLm5vZGVOYW1lKSB7XG4gICAgICBlbGVtZW50cyA9IFtzZWxlY3Rvcl07IC8vIGVtdWxhdGUgYSBOb2RlTGlzdCBieSBjYXN0aW5nIGEgc2luZ2xlIE5vZGUgYXMgYW4gYXJyYXlcbiAgICB9IGVsc2Uge1xuICAgICAgZWxlbWVudHMgPSBzZWxlY3RvcjtcbiAgICB9IC8vIEFmdGVyIGFsbCB0aGF0LCBpZiB3ZSBfc3RpbGxfIGRvbid0IGhhdmUgaXRlcmF0YWJsZSBOb2RlTGlzdCwgYmFpbCBvdXQuXG5cblxuICAgIGlmICghZWxlbWVudHMubGVuZ3RoKSByZXR1cm47XG4gICAgdGhpcy5vcHRpb25zID0gZXh0ZW5kKHt9LCBkZWZhdWx0cywgb3B0aW9ucyk7XG5cbiAgICBpZiAodHlwZW9mIHNlbGVjdG9yID09PSAnc3RyaW5nJykge1xuICAgICAgZW1iZWRDU1Moc2VsZWN0b3IsIHRoaXMub3B0aW9ucyk7XG4gICAgfSBlbHNlIHtcbiAgICAgIC8vIEluc3RhbmNlcyBuZWVkIGRpc3RpbmN0IHNlbGVjdG9ycyBzbyB0aGV5IGRvbid0IHN0b21wIG9uIGVhY2ggb3RoZXIuXG4gICAgICB0aGlzLmluc3RhbmNlU2VsZWN0b3IgPSBcIi5cIi5jb25jYXQodW5pcXVlSWQoKSk7XG4gICAgICBlbWJlZENTUyh0aGlzLmluc3RhbmNlU2VsZWN0b3IsIHRoaXMub3B0aW9ucyk7XG4gICAgfSAvLyBOZWVkIHRvIHJlc2l6ZSBib3hlcyB3aGVuIHRoZSBwYWdlIGhhcyBmdWxseSBsb2FkZWQuXG5cblxuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdsb2FkJywgcmVzaXplQm94ZXMpO1xuICAgIHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdyZXNpemUnLCByZXNpemVCb3hlcyk7XG4gICAgdGhpcy5lbGVtZW50cyA9IFtdO1xuICAgIGZvckVhY2goZWxlbWVudHMsIGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gICAgICBpZiAoX3RoaXMyLmluc3RhbmNlU2VsZWN0b3IpIHtcbiAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QuYWRkKF90aGlzMi5pbnN0YW5jZVNlbGVjdG9yLnN1YnN0cigxKSk7XG4gICAgICB9XG5cbiAgICAgIHZhciBleHBhbmRlZCA9IF90aGlzMi5vcHRpb25zLnN0YXJ0T3BlbjtcbiAgICAgIGVsZW1lbnQucmVhZG1vcmUgPSB7XG4gICAgICAgIGRlZmF1bHRIZWlnaHQ6IF90aGlzMi5vcHRpb25zLmNvbGxhcHNlZEhlaWdodCxcbiAgICAgICAgaGVpZ2h0TWFyZ2luOiBfdGhpczIub3B0aW9ucy5oZWlnaHRNYXJnaW5cbiAgICAgIH07XG4gICAgICBzZXRCb3hIZWlnaHRzKGVsZW1lbnQpO1xuICAgICAgdmFyIGhlaWdodE1hcmdpbiA9IGVsZW1lbnQucmVhZG1vcmUuaGVpZ2h0TWFyZ2luO1xuXG4gICAgICBpZiAoZWxlbWVudC5nZXRCb3VuZGluZ0NsaWVudFJlY3QoKS5oZWlnaHQgPD0gZWxlbWVudC5yZWFkbW9yZS5jb2xsYXBzZWRIZWlnaHQgKyBoZWlnaHRNYXJnaW4pIHtcbiAgICAgICAgaWYgKHR5cGVvZiBfdGhpczIub3B0aW9ucy5ibG9ja1Byb2Nlc3NlZCA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgIF90aGlzMi5vcHRpb25zLmJsb2NrUHJvY2Vzc2VkKGVsZW1lbnQsIGZhbHNlKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgZWxlbWVudC5zZXRBdHRyaWJ1dGUoJ2RhdGEtcmVhZG1vcmUnLCAnJyk7XG4gICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZSgnYXJpYS1leHBhbmRlZCcsIGV4cGFuZGVkKTtcbiAgICAgIGVsZW1lbnQuaWQgPSBlbGVtZW50LmlkIHx8IHVuaXF1ZUlkKCk7XG4gICAgICB2YXIgdG9nZ2xlTGluayA9IGV4cGFuZGVkID8gX3RoaXMyLm9wdGlvbnMubGVzc0xpbmsgOiBfdGhpczIub3B0aW9ucy5tb3JlTGluaztcbiAgICAgIHZhciB0b2dnbGVFbGVtZW50ID0gYnVpbGRUb2dnbGUodG9nZ2xlTGluaywgZWxlbWVudCwgX3RoaXMyKTtcbiAgICAgIGVsZW1lbnQucGFyZW50Tm9kZS5pbnNlcnRCZWZvcmUodG9nZ2xlRWxlbWVudCwgX3RoaXMyLm9wdGlvbnMuc291cmNlT3JkZXIgPT09ICdiZWZvcmUnID8gZWxlbWVudCA6IGVsZW1lbnQubmV4dFNpYmxpbmcpO1xuICAgICAgZWxlbWVudC5zdHlsZS5oZWlnaHQgPSBcIlwiLmNvbmNhdChleHBhbmRlZCA/IGVsZW1lbnQucmVhZG1vcmUuZXhwYW5kZWRIZWlnaHQgOiBlbGVtZW50LnJlYWRtb3JlLmNvbGxhcHNlZEhlaWdodCwgXCJweFwiKTtcblxuICAgICAgaWYgKHR5cGVvZiBfdGhpczIub3B0aW9ucy5ibG9ja1Byb2Nlc3NlZCA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICBfdGhpczIub3B0aW9ucy5ibG9ja1Byb2Nlc3NlZChlbGVtZW50LCB0cnVlKTtcbiAgICAgIH1cblxuICAgICAgX3RoaXMyLmVsZW1lbnRzLnB1c2goZWxlbWVudCk7XG4gICAgfSk7XG4gIH0gLy8gU2lnbmF0dXJlIHdoZW4gY2FsbGVkIGludGVybmFsbHkgYnkgdGhlIHRvZ2dsZUxpbmsgY2xpY2sgaGFuZGxlcjpcbiAgLy8gICB0b2dnbGUoZWxlbWVudCwgZXZlbnQpXG4gIC8vXG4gIC8vIFdoZW4gY2FsbGVkIGV4dGVybmFsbHkgYnkgYW4gaW5zdGFuY2UsXG4gIC8vIGUuZy4gcmVhZG1vcmVEZW1vLnRvZ2dsZShkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCdhcnRpY2xlOm50aC1vZi10eXBlKDEpJykpOlxuICAvLyAgIHRvZ2dsZShlbGVtZW50T3JRdWVyeVNlbGVjdG9yKVxuXG5cbiAgX2JhYmVsX3J1bnRpbWVfaGVscGVyc19jcmVhdGVDbGFzc19fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMV9fX2RlZmF1bHQoKShSZWFkbW9yZSwgW3tcbiAgICBrZXk6IFwidG9nZ2xlXCIsXG4gICAgdmFsdWU6IGZ1bmN0aW9uIHRvZ2dsZSgpIHtcbiAgICAgIHZhciBfdGhpczMgPSB0aGlzO1xuXG4gICAgICB2YXIgZWwgPSBhcmd1bWVudHMubGVuZ3RoIDw9IDAgPyB1bmRlZmluZWQgOiBhcmd1bWVudHNbMF07XG5cbiAgICAgIHZhciB0b2dnbGVFbGVtZW50ID0gZnVuY3Rpb24gdG9nZ2xlRWxlbWVudChlbGVtZW50KSB7XG4gICAgICAgIHZhciB0cmlnZ2VyID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihcIlthcmlhLWNvbnRyb2xzPVxcXCJcIi5jb25jYXQoZWxlbWVudC5pZCwgXCJcXFwiXVwiKSk7XG4gICAgICAgIHZhciBleHBhbmRlZCA9IGVsZW1lbnQuZ2V0Qm91bmRpbmdDbGllbnRSZWN0KCkuaGVpZ2h0IDw9IGVsZW1lbnQucmVhZG1vcmUuY29sbGFwc2VkSGVpZ2h0O1xuICAgICAgICB2YXIgbmV3SGVpZ2h0ID0gZXhwYW5kZWQgPyBlbGVtZW50LnJlYWRtb3JlLmV4cGFuZGVkSGVpZ2h0IDogZWxlbWVudC5yZWFkbW9yZS5jb2xsYXBzZWRIZWlnaHQ7IC8vIEZpcmUgYmVmb3JlVG9nZ2xlIGNhbGxiYWNrXG4gICAgICAgIC8vIFNpbmNlIHdlIGRldGVybWluZWQgdGhlIG5ldyBcImV4cGFuZGVkXCIgc3RhdGUgYWJvdmUgd2UncmUgbm93IG91dCBvZiBzeW5jXG4gICAgICAgIC8vIHdpdGggb3VyIHRydWUgY3VycmVudCBzdGF0ZSwgc28gd2UgbmVlZCB0byBmbGlwIHRoZSB2YWx1ZSBvZiBgZXhwYW5kZWRgXG5cbiAgICAgICAgaWYgKHR5cGVvZiBfdGhpczMub3B0aW9ucy5iZWZvcmVUb2dnbGUgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICB2YXIgc2hvdWxkQ29udGludWVUb2dnbGUgPSBfdGhpczMub3B0aW9ucy5iZWZvcmVUb2dnbGUodHJpZ2dlciwgZWxlbWVudCwgIWV4cGFuZGVkKTsgLy8gaWYgdGhlIGJlZm9yZVRvZ2dsZSBjYWxsYmFjayByZXR1cm5zIGZhbHNlLCBzdG9wIHRvZ2dsaW5nXG5cblxuICAgICAgICAgIGlmIChzaG91bGRDb250aW51ZVRvZ2dsZSA9PT0gZmFsc2UpIHtcbiAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBlbGVtZW50LnN0eWxlLmhlaWdodCA9IFwiXCIuY29uY2F0KG5ld0hlaWdodCwgXCJweFwiKTtcblxuICAgICAgICB2YXIgdHJhbnNpdGlvbmVuZEhhbmRsZXIgPSBmdW5jdGlvbiB0cmFuc2l0aW9uZW5kSGFuZGxlcih0cmFuc2l0aW9uRXZlbnQpIHtcbiAgICAgICAgICAvLyBGaXJlIGFmdGVyVG9nZ2xlIGNhbGxiYWNrXG4gICAgICAgICAgaWYgKHR5cGVvZiBfdGhpczMub3B0aW9ucy5hZnRlclRvZ2dsZSA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgX3RoaXMzLm9wdGlvbnMuYWZ0ZXJUb2dnbGUodHJpZ2dlciwgZWxlbWVudCwgZXhwYW5kZWQpO1xuICAgICAgICAgIH1cblxuICAgICAgICAgIHRyYW5zaXRpb25FdmVudC5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgICBlbGVtZW50LnNldEF0dHJpYnV0ZSgnYXJpYS1leHBhbmRlZCcsIGV4cGFuZGVkKTtcbiAgICAgICAgICBlbGVtZW50LnJlbW92ZUV2ZW50TGlzdGVuZXIoJ3RyYW5zaXRpb25lbmQnLCB0cmFuc2l0aW9uZW5kSGFuZGxlciwgZmFsc2UpO1xuICAgICAgICB9O1xuXG4gICAgICAgIGVsZW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigndHJhbnNpdGlvbmVuZCcsIHRyYW5zaXRpb25lbmRIYW5kbGVyLCBmYWxzZSk7XG5cbiAgICAgICAgaWYgKF90aGlzMy5vcHRpb25zLnNwZWVkIDwgMSkge1xuICAgICAgICAgIHRyYW5zaXRpb25lbmRIYW5kbGVyLmNhbGwoX3RoaXMzLCB7XG4gICAgICAgICAgICB0YXJnZXQ6IGVsZW1lbnRcbiAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIHZhciB0b2dnbGVMaW5rID0gZXhwYW5kZWQgPyBfdGhpczMub3B0aW9ucy5sZXNzTGluayA6IF90aGlzMy5vcHRpb25zLm1vcmVMaW5rO1xuXG4gICAgICAgIGlmICghdG9nZ2xlTGluaykge1xuICAgICAgICAgIHRyaWdnZXIucmVtb3ZlKCk7XG4gICAgICAgIH0gZWxzZSBpZiAodHJpZ2dlciAmJiB0cmlnZ2VyLnBhcmVudE5vZGUpIHtcbiAgICAgICAgICB0cmlnZ2VyLnBhcmVudE5vZGUucmVwbGFjZUNoaWxkKGJ1aWxkVG9nZ2xlKHRvZ2dsZUxpbmssIGVsZW1lbnQsIF90aGlzMyksIHRyaWdnZXIpO1xuICAgICAgICB9XG4gICAgICB9O1xuXG4gICAgICBpZiAodHlwZW9mIGVsID09PSAnc3RyaW5nJykge1xuICAgICAgICBlbCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoZWwpO1xuICAgICAgfVxuXG4gICAgICBpZiAoIWVsKSB7XG4gICAgICAgIHRocm93IG5ldyBFcnJvcignRWxlbWVudCBNVVNUIGJlIGVpdGhlciBhbiBIVE1MIG5vZGUgb3IgcXVlcnlTZWxlY3RvciBzdHJpbmcnKTtcbiAgICAgIH1cblxuICAgICAgdmFyIGV2ZW50ID0gYXJndW1lbnRzLmxlbmd0aCA8PSAxID8gdW5kZWZpbmVkIDogYXJndW1lbnRzWzFdO1xuXG4gICAgICBpZiAoZXZlbnQpIHtcbiAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICB9XG5cbiAgICAgIGlmIChfYmFiZWxfcnVudGltZV9oZWxwZXJzX3R5cGVvZl9fV0VCUEFDS19JTVBPUlRFRF9NT0RVTEVfMl9fX2RlZmF1bHQoKShlbCkgPT09ICdvYmplY3QnICYmICFlbC5ub2RlTmFtZSkge1xuICAgICAgICAvLyBlbGVtZW50IGlzIGxpa2VseSBhIE5vZGVMaXN0XG4gICAgICAgIGZvckVhY2goZWwsIHRvZ2dsZUVsZW1lbnQpO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgdG9nZ2xlRWxlbWVudChlbCk7XG4gICAgICB9XG4gICAgfVxuICB9LCB7XG4gICAga2V5OiBcImRlc3Ryb3lcIixcbiAgICB2YWx1ZTogZnVuY3Rpb24gZGVzdHJveShzZWxlY3Rvcikge1xuICAgICAgdmFyIF90aGlzNCA9IHRoaXM7XG5cbiAgICAgIHZhciBlbGVtZW50cztcblxuICAgICAgaWYgKCFzZWxlY3Rvcikge1xuICAgICAgICBlbGVtZW50cyA9IHRoaXMuZWxlbWVudHM7IC8vIGVzbGludC1kaXNhYmxlLWxpbmVcbiAgICAgIH0gZWxzZSBpZiAodHlwZW9mIHNlbGVjdG9yID09PSAnc3RyaW5nJykge1xuICAgICAgICBlbGVtZW50cyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoc2VsZWN0b3IpO1xuICAgICAgfSBlbHNlIGlmIChzZWxlY3Rvci5ub2RlTmFtZSkge1xuICAgICAgICBlbGVtZW50cyA9IFtzZWxlY3Rvcl07IC8vIGVtdWxhdGUgYSBOb2RlTGlzdCBieSBjYXN0aW5nIGEgc2luZ2xlIE5vZGUgYXMgYW4gYXJyYXlcbiAgICAgIH0gZWxzZSB7XG4gICAgICAgIGVsZW1lbnRzID0gc2VsZWN0b3I7XG4gICAgICB9XG5cbiAgICAgIGZvckVhY2goZWxlbWVudHMsIGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gICAgICAgIGlmIChfdGhpczQuZWxlbWVudHMuaW5kZXhPZihlbGVtZW50KSA9PT0gLTEpIHtcbiAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuICAgICAgICBfdGhpczQuZWxlbWVudHMgPSBfdGhpczQuZWxlbWVudHMuZmlsdGVyKGZ1bmN0aW9uIChlbCkge1xuICAgICAgICAgIHJldHVybiBlbCAhPT0gZWxlbWVudDtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgaWYgKF90aGlzNC5pbnN0YW5jZVNlbGVjdG9yKSB7XG4gICAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKF90aGlzNC5pbnN0YW5jZVNlbGVjdG9yLnN1YnN0cigxKSk7XG4gICAgICAgIH1cblxuICAgICAgICBkZWxldGUgZWxlbWVudC5yZWFkbW9yZTtcbiAgICAgICAgZWxlbWVudC5zdHlsZS5oZWlnaHQgPSAnaW5pdGlhbCc7XG4gICAgICAgIGVsZW1lbnQuc3R5bGUubWF4SGVpZ2h0ID0gJ2luaXRpYWwnO1xuICAgICAgICBlbGVtZW50LnJlbW92ZUF0dHJpYnV0ZSgnZGF0YS1yZWFkbW9yZScpO1xuICAgICAgICBlbGVtZW50LnJlbW92ZUF0dHJpYnV0ZSgnYXJpYS1leHBhbmRlZCcpO1xuICAgICAgICB2YXIgdHJpZ2dlciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoXCJbYXJpYS1jb250cm9scz1cXFwiXCIuY29uY2F0KGVsZW1lbnQuaWQsIFwiXFxcIl1cIikpO1xuXG4gICAgICAgIGlmICh0cmlnZ2VyKSB7XG4gICAgICAgICAgdHJpZ2dlci5yZW1vdmUoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChlbGVtZW50LmlkLmluZGV4T2YoJ3JtanMtJykgIT09IC0xKSB7XG4gICAgICAgICAgZWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoJ2lkJyk7XG4gICAgICAgIH1cbiAgICAgIH0pO1xuICAgICAgZGVsZXRlIHRoaXM7XG4gICAgfVxuICB9XSk7XG5cbiAgcmV0dXJuIFJlYWRtb3JlO1xufSgpO1xuXG5SZWFkbW9yZS5WRVJTSU9OID0gXCIzLjAuMC1iZXRhLTFcIjtcbi8qIGhhcm1vbnkgZGVmYXVsdCBleHBvcnQgKi8gX193ZWJwYWNrX2V4cG9ydHNfX1tcImRlZmF1bHRcIl0gPSAoUmVhZG1vcmUpO1xuXG4vKioqLyB9KSxcblxuLyoqKi8gMDpcbi8qISoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiohKlxcXG4gICEqKiogbXVsdGkgLi9zcmMvcmVhZG1vcmUuanMgKioqIVxuICBcXCoqKioqKioqKioqKioqKioqKioqKioqKioqKioqKiovXG4vKiEgbm8gc3RhdGljIGV4cG9ydHMgZm91bmQgKi9cbi8qKiovIChmdW5jdGlvbihtb2R1bGUsIGV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pIHtcblxubW9kdWxlLmV4cG9ydHMgPSBfX3dlYnBhY2tfcmVxdWlyZV9fKC8qISAuL3NyYy9yZWFkbW9yZS5qcyAqL1wiLi9zcmMvcmVhZG1vcmUuanNcIik7XG5cblxuLyoqKi8gfSlcblxuLyoqKioqKi8gfSlbXCJkZWZhdWx0XCJdO1xufSk7XG4vLyMgc291cmNlTWFwcGluZ1VSTD1yZWFkbW9yZS5qcy5tYXBcbiIsInZhciBfWUJBPXt9O1xuXG5cdF9ZQkEudXJsID0gJyc7XG5cblx0X1lCQS5ZQUNIVFM9e307XG5cblx0X1lCQS5GT1JNX1NFTEVDVF9PUFRJT05TPXt9O1xuXG5cdF9ZQkEuY2FsbF9hcGk9ZnVuY3Rpb24obWV0aG9kLCBwYXRoLCBwYXNzaW5nX2RhdGEpIHtcblx0XHR2YXIgeGh0dHAgPSBuZXcgWE1MSHR0cFJlcXVlc3QoKTtcblxuXHRcdHJldHVybiBuZXcgUHJvbWlzZShmdW5jdGlvbihyZXNvbHZlLCByZWplY3QpIHtcblx0XHRcdFxuXHRcdFx0eGh0dHAub25yZWFkeXN0YXRlY2hhbmdlID0gZnVuY3Rpb24oKSB7XG5cdFx0XHRcdGlmICh0aGlzLnJlYWR5U3RhdGUgPT0gNCAmJiB0aGlzLnN0YXR1cyA9PSAyMDApIHtcblxuXHRcdFx0XHRcdHZhciByZXNwb25zZURhdGEgPSBKU09OLnBhcnNlKCB0aGlzLnJlc3BvbnNlVGV4dCApO1xuXG5cdFx0XHRcdFx0cmVzb2x2ZShyZXNwb25zZURhdGEpO1xuXG5cdFx0XHRcdH1cblx0XHRcdH07XG5cblx0XHRcdHN3aXRjaCAobWV0aG9kKSB7XG5cdFx0XHRcdGNhc2UgJ0dFVCc6XG5cdFx0XHRcdFx0dmFyIHNlYXJjaFBhcmFtcyA9IG5ldyBVUkxTZWFyY2hQYXJhbXMoKTtcblxuXHRcdFx0XHRcdGZvciAoY29uc3QgcHJvcGVydHkgaW4gcGFzc2luZ19kYXRhKSB7XG5cdFx0XHRcdFx0XHRzZWFyY2hQYXJhbXMuc2V0KHByb3BlcnR5LCBwYXNzaW5nX2RhdGFbIHByb3BlcnR5IF0pO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdHhodHRwLm9wZW4oXCJHRVRcIiwgX3lhdGNvX3dwX3VybC5feWF0Y29fd3BfcmVzdF91cmwrXCJ5YXRjby9cIisgcGF0aCArXCI/XCIrc2VhcmNoUGFyYW1zLnRvU3RyaW5nKCksIHRydWUpO1xuXG5cdFx0XHRcdFx0eGh0dHAuc2VuZCgpO1xuXG5cdFx0XHRcdFx0YnJlYWs7XG5cblx0XHRcdFx0Y2FzZSAnUE9TVCc6XG5cblx0XHRcdFx0XHR4aHR0cC5vcGVuKFwiUE9TVFwiLCBfeWF0Y29fd3BfdXJsLl95YXRjb193cF9yZXN0X3VybCtcInlhdGNvL1wiKyBwYXRoLCB0cnVlKTtcblxuXHRcdFx0XHRcdHhodHRwLnNldFJlcXVlc3RIZWFkZXIoJ0NvbnRlbnQtVHlwZScsICdhcHBsaWNhdGlvbi9qc29uJyk7XG5cblx0XHRcdFx0XHR4aHR0cC5zZW5kKEpTT04uc3RyaW5naWZ5KHBhc3NpbmdfZGF0YSkpO1xuXG5cdFx0XHRcdFx0YnJlYWs7XG5cdFx0XHR9XG5cdFx0XHRcblx0XHRcdC8qXG5cdFx0XHRcdHhodHRwLm9wZW4oXCJQT1NUXCIsIFwiaHR0cDovL2RhdGEtd29yZHByZXNzLnlhdGNvYm9zcy5jb206ODAvQVBJL1YxL0ZvclNhbGUvVmVzc2VsL1NlYXJjaFwiLCB0cnVlKTtcblxuXHRcdFx0XHR4aHR0cC5zZXRSZXF1ZXN0SGVhZGVyKCdBdXRob3JpemF0aW9uJywgXCJCYXNpYyAvbFFoSnF2NmtnQ2tBOEd4di90TlNVMW1VNkN1ekxNWFdvOTJGRUtjTFBnPVwiKTtcblx0XHRcdFx0eGh0dHAuc2V0UmVxdWVzdEhlYWRlcignQWNjZXB0JywgJ2FwcGxpY2F0aW9uL2pzb24nKTtcblx0XHRcdFx0eGh0dHAuc2V0UmVxdWVzdEhlYWRlcignQ29udGVudC1UeXBlJywgJ2FwcGxpY2F0aW9uL2pzb24nKTtcblx0XHRcdCovXG5cblx0XHR9KTtcblxuXHR9O1xuXG5cdF9ZQkEubGFiZWxfb3B0aW9ucz1mdW5jdGlvbihsYWJlbCkge1xuXHRcdHZhciBwYXNzaW5nX2RhdGE7XG5cblx0XHRpZiAodHlwZW9mIGxhYmVsID09ICdvYmplY3QnICYmICEgQXJyYXkuaXNBcnJheShsYWJlbCkpIHtcblx0XHRcdHBhc3NpbmdfZGF0YSA9IGxhYmVsO1xuXG5cdFx0fSBlbHNlIGlmIChsYWJlbCA9PT0gJycgfHwgKEFycmF5LmlzQXJyYXkobGFiZWwpICYmIGxhYmVsLmxlbmd0aCA9PSAwKSkge1xuXG5cdFx0XHRyZXR1cm4ge307XG5cblx0XHR9IGVsc2UgaWYgKHR5cGVvZiBsYWJlbCA9PSAnc3RyaW5nJyB8fCBBcnJheS5pc0FycmF5KGxhYmVsKSkge1xuXHRcdFx0cGFzc2luZ19kYXRhPXt9O1xuXG5cdFx0XHRwYXNzaW5nX2RhdGEubGFiZWwgPSBsYWJlbDtcblx0XHR9XG5cblx0XHRyZXR1cm4gX1lCQS5jYWxsX2FwaSgnR0VUJywgJ2Zvcm0tZGF0YS1jb21tb24nLCBwYXNzaW5nX2RhdGEpO1xuXHR9O1xuXG5cdF9ZQkEueWFjaHRfc2VhcmNoPWZ1bmN0aW9uKHBhcmFtcykge1xuXG5cdFx0aWYgKHR5cGVvZiBwYXJhbXMgPT0gJ3VuZGVmaW5lZCcpIHtcblx0XHRcdHBhcmFtcyA9IHt9O1xuXHRcdH1cbiBcblx0XHRyZXR1cm4gX1lCQS5jYWxsX2FwaSgnUE9TVCcsICd5YWNodHMnLCBwYXJhbXMpO1xuXG5cdH07XG5cblx0X1lCQS55YWNodF9kZXRhaWxzPWZ1bmN0aW9uKGlkKSB7XG5cdFx0XG5cdFx0cmV0dXJuIF9ZQkEuY2FsbF9hcGkoJ1BPU1QnLCAneWFjaHQtZGV0YWlsJywge3Zlc3NlbF9pZDogaWR9KTtcblxuXHR9O1xuXG5cdF9ZQkEuY2hhcnRlcl9zZWFyY2g9ZnVuY3Rpb24ocGFyYW1zKSB7XG5cblx0XHRpZiAodHlwZW9mIHBhcmFtcyA9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0cGFyYW1zID0ge307XG5cdFx0fVxuIFxuXHRcdHJldHVybiBfWUJBLmNhbGxfYXBpKCdQT1NUJywgJ2NoYXJ0ZXJzJywgcGFyYW1zKTtcblxuXHR9O1xuXG5cdF9ZQkEuYnJva2VyX3NlYXJjaD1mdW5jdGlvbihwYXJhbXMpIHtcblxuXHRcdGlmICh0eXBlb2YgcGFyYW1zID09ICd1bmRlZmluZWQnKSB7XG5cdFx0XHRwYXJhbXMgPSB7fTtcblx0XHR9XG4gXG5cdFx0cmV0dXJuIF9ZQkEuY2FsbF9hcGkoJ1BPU1QnLCAnYnJva2VycycsIHBhcmFtcyk7XG5cblx0fTtcblxuXHRfWUJBLmJyb2tlcmFnZV9zZWFyY2g9ZnVuY3Rpb24ocGFyYW1zKSB7XG5cblx0XHRpZiAodHlwZW9mIHBhcmFtcyA9PSAndW5kZWZpbmVkJykge1xuXHRcdFx0cGFyYW1zID0ge307XG5cdFx0fVxuIFxuXHRcdHJldHVybiBfWUJBLmNhbGxfYXBpKCdQT1NUJywgJ2Jyb2tlcmFnZXMnLCBwYXJhbXMpO1xuXG5cdH07XG5cblx0X1lCQS5zZW5kX2xlYWQ9ZnVuY3Rpb24ocGFyYW1zKSB7XG5cblx0XHRyZXR1cm4gX1lCQS5jYWxsX2FwaSgnUE9TVCcsICdzZW5kLWxlYWQnLCBwYXJhbXMpO1xuXG5cdH07XG4iLCIvL2hlbHBlciBmb3IgZml4aW5nIGxhcmdlIGxpc3QgaXNzdWUgZm9yIG1vYmlsZVxuZnVuY3Rpb24gc2V0dXBGaWx0ZXJlZEF1dG9jb21wbGV0ZShpbnB1dFNlbGVjdG9yLCBkYXRhbGlzdElkLCBkYXRhU291cmNlLCBnZXRWYWx1ZUZuKSB7XG5cdHZhciBpbnB1dCA9IGpRdWVyeShpbnB1dFNlbGVjdG9yKTtcblx0dmFyIGRhdGFsaXN0ID0galF1ZXJ5KCcjJyArIGRhdGFsaXN0SWQpO1xuXHR2YXIgdHlwaW5nVGltZXI7XG5cdHZhciBpc0ZpbHRlcmluZyA9IGZhbHNlO1xuXG5cdGlucHV0Lm9uKCdpbnB1dCcsIGZ1bmN0aW9uICgpIHtcblx0XHRjbGVhclRpbWVvdXQodHlwaW5nVGltZXIpO1xuXHRcdHZhciBzZWFyY2hUZXJtID0gdGhpcy52YWx1ZS50b0xvd2VyQ2FzZSgpLnRyaW0oKTtcblxuXHRcdC8vIENsZWFyIGlmIGxlc3MgdGhhbiAyIGNoYXJhY3RlcnNcblx0XHRpZiAoc2VhcmNoVGVybS5sZW5ndGggPCAyKSB7XG5cdFx0XHRpZiAoIWlzRmlsdGVyaW5nKSB7XG5cdFx0XHRcdGRhdGFsaXN0LmVtcHR5KCk7XG5cdFx0XHR9XG5cdFx0XHRyZXR1cm47XG5cdFx0fVxuXG5cdFx0dHlwaW5nVGltZXIgPSBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcblx0XHRcdGlzRmlsdGVyaW5nID0gdHJ1ZTtcblx0XHRcdGRhdGFsaXN0LmVtcHR5KCk7XG5cblx0XHRcdC8vIEZpbHRlciBtYXRjaGVzXG5cdFx0XHR2YXIgbWF0Y2hlcyA9IFtdO1xuXHRcdFx0Zm9yICh2YXIgaSA9IDA7IGkgPCBkYXRhU291cmNlLmxlbmd0aCAmJiBtYXRjaGVzLmxlbmd0aCA8IDUwOyBpKyspIHtcblx0XHRcdFx0dmFyIHZhbHVlID0gZ2V0VmFsdWVGbihkYXRhU291cmNlW2ldKTtcblx0XHRcdFx0aWYgKHZhbHVlLnRvTG93ZXJDYXNlKCkuaW5kZXhPZihzZWFyY2hUZXJtKSAhPT0gLTEpIHtcblx0XHRcdFx0XHRtYXRjaGVzLnB1c2godmFsdWUpO1xuXHRcdFx0XHR9XG5cdFx0XHR9XG5cblx0XHRcdC8vIFVzZSBmcmFnbWVudCBmb3Igc2luZ2xlIERPTSB1cGRhdGVcblx0XHRcdGlmIChtYXRjaGVzLmxlbmd0aCA+IDApIHtcblx0XHRcdFx0dmFyIGZyYWdtZW50ID0gZG9jdW1lbnQuY3JlYXRlRG9jdW1lbnRGcmFnbWVudCgpO1xuXHRcdFx0XHRmb3IgKHZhciBpID0gMDsgaSA8IG1hdGNoZXMubGVuZ3RoOyBpKyspIHtcblx0XHRcdFx0XHR2YXIgb3B0ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnb3B0aW9uJyk7XG5cdFx0XHRcdFx0b3B0LnZhbHVlID0gbWF0Y2hlc1tpXTtcblx0XHRcdFx0XHRmcmFnbWVudC5hcHBlbmRDaGlsZChvcHQpO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGRhdGFsaXN0WzBdLmFwcGVuZENoaWxkKGZyYWdtZW50KTtcblx0XHRcdH1cblxuXHRcdFx0aXNGaWx0ZXJpbmcgPSBmYWxzZTtcblx0XHR9LCAyNTApO1xuXHR9KTtcblxuXHQvLyBDbGVhciBvbiBibHVyIHRvIHNhdmUgbWVtb3J5XG5cdGlucHV0Lm9uKCdibHVyJywgZnVuY3Rpb24gKCkge1xuXHRcdHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuXHRcdFx0ZGF0YWxpc3QuZW1wdHkoKTtcblx0XHR9LCAyMDApO1xuXHR9KTtcbn1cblxuXG4vLyBGaWxsIFNlbGVjdCBJbnB1dHMgV2l0aCBPcHRpb25zXG5mdW5jdGlvbiB5dF9maWxsX29wdGlvbnMoZWxlLCBsaXN0KSB7XG5cdGZvciAodmFyIGkgPSAwOyBpIDwgbGlzdC5sZW5ndGg7IGkrKykge1xuXHRcdGlmIChsaXN0W2ldLmlkICE9IG51bGwgJiYgbGlzdFtpXS50ZXh0ICE9ICcnKSB7XG5cblx0XHRcdHZhciBuZXdfb3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnb3B0aW9uJyk7XG5cblx0XHRcdG5ld19vcHRpb24udGV4dCA9IGxpc3RbaV0udGV4dDtcblxuXHRcdFx0aWYgKGVsZS5nZXRBdHRyaWJ1dGUoJ2RhdGEteWF0Y28tZmlsLWp1c3QtdGV4dC12YWwnKSA9PSAndHJ1ZScpIHtcblx0XHRcdFx0bmV3X29wdGlvbi52YWx1ZSA9IGxpc3RbaV0udGV4dDtcblx0XHRcdFx0bmV3X29wdGlvbi5zZXRBdHRyaWJ1dGUoJ2RhdGEtaWQnLCBsaXN0W2ldLmlkKTtcblx0XHRcdH1cblx0XHRcdGVsc2Uge1xuXHRcdFx0XHRuZXdfb3B0aW9uLnZhbHVlID0gbGlzdFtpXS5pZDtcblx0XHRcdH1cblxuXG5cdFx0XHRlbGUub3B0aW9ucy5hZGQobmV3X29wdGlvbik7XG5cdFx0fVxuXHR9XG59XG5cbmZ1bmN0aW9uIHl0X2luaXRfc3VwZXJfc2VsZWN0b3IoZWxlKSB7XG5cdHZhciBhdHRyX2xhYmVsID0gZWxlLmRhdGEoJ3lhdGNvLWZpbGwtb3B0aW9ucycpO1xuXHR2YXIgY3VyX2lucHV0ID0gZWxlO1xuXG5cdGNvbnNvbGUubG9nKGF0dHJfbGFiZWwpO1xuXG5cdF9ZQkEubGFiZWxfb3B0aW9ucyhhdHRyX2xhYmVsKS50aGVuKGZ1bmN0aW9uIChvcHRzKSB7XG5cdFx0eXRfZGF0YV9maWxsc1thdHRyX2xhYmVsXSA9IG9wdHNbeXRfZGF0YV9maWxsc107XG5cblx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnMoY3VyX2lucHV0WzBdLCBvcHRzW2F0dHJfbGFiZWxdKTtcblxuXHRcdGN1cl9pbnB1dC5hdHRyKCdmaWxsZWQnLCAnZmlsbGVkJyk7XG5cblx0XHR5dF9pbml0X3N1cGVyX3NlbGVjdG9yX3NlYXJjaF9hbmRfdW5jaGVjayhjdXJfaW5wdXQsIG9wdHNbYXR0cl9sYWJlbF0pO1xuXG5cdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGN1cl9pbnB1dCk7XG5cblx0fSk7XG5cbn1cblxuZnVuY3Rpb24geXRfaW5pdF9zdXBlcl9zZWxlY3Rvcl9zZWFyY2hfYW5kX3VuY2hlY2soY3VyX2lucHV0LCBsaXN0KSB7XG5cdGNvbnNvbGUubG9nKGN1cl9pbnB1dCk7XG5cdGNvbnNvbGUubG9nKGxpc3QpO1xuXG5cdGlmIChjdXJfaW5wdXQuaGFzQ2xhc3MoJ2luaXRlZCcpKSB7XG5cblx0XHRqUXVlcnkoJy5zZWFyY2gnLCBjdXJfaW5wdXQpLnVuYmluZChcImlucHV0XCIpO1xuXG5cdFx0alF1ZXJ5KCcuc2VhcmNoJywgY3VyX2lucHV0KS5vbignaW5wdXQnLCBmdW5jdGlvbiAoKSB7XG5cblx0XHRcdHZhciBmaWx0ZXJlZF9yZXN1bHRzID0gbGlzdC5maWx0ZXIoZnVuY3Rpb24gKG9wdCkge1xuXHRcdFx0XHR2YXIgbG93ZXJfdmFsID0gb3B0LnRleHQudG9Mb3dlckNhc2UoKTtcblx0XHRcdFx0dmFyIGlucHV0X3ZhbF9sb3dlciA9IChqUXVlcnkoJy5zZWFyY2gnLCBjdXJfaW5wdXQpLnZhbCgpKS50b0xvd2VyQ2FzZSgpO1xuXG5cdFx0XHRcdHJldHVybiBsb3dlcl92YWwuaW5kZXhPZihpbnB1dF92YWxfbG93ZXIpICE9IC0xO1xuXHRcdFx0fSk7XG5cblx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc19oaWRlKGN1cl9pbnB1dFswXSwgZmlsdGVyZWRfcmVzdWx0cyk7XG5cdFx0fSk7XG5cblx0XHQvKmpRdWVyeSgnLnVuY2hlY2stYWxsJywgY3VyX2lucHV0KS5jbGljayhmdW5jdGlvbigpIHtcblxuXHRcdFx0alF1ZXJ5KCcueXQtc3VwZXItb3B0aW9uJywgY3VyX2lucHV0KS5lYWNoKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdGpRdWVyeSgnaW5wdXQnLCBqUXVlcnkodGhpcykpLnByb3AoJ2NoZWNrZWQnLCBmYWxzZSk7XG5cblx0XHRcdH0pO1xuXG5cdFx0fSk7Ki9cblxuXHR9XG5cdGVsc2Uge1xuXHRcdGN1cl9pbnB1dC5hZGRDbGFzcygnaW5pdGVkJyk7XG5cblx0XHRjdXJfaW5wdXQucHJlcGVuZCgnPGlucHV0IHR5cGU9XCJ0ZXh0XCIgY2xhc3M9XCJzZWFyY2hcIiBwbGFjZWhvbGRlcj1cIlNlYXJjaC4uXCI+Jyk7XG5cblx0XHRqUXVlcnkoJy5zZWFyY2gnLCBjdXJfaW5wdXQpLm9uKCdpbnB1dCcsIGZ1bmN0aW9uICgpIHtcblxuXHRcdFx0dmFyIGZpbHRlcmVkX3Jlc3VsdHMgPSBsaXN0LmZpbHRlcihmdW5jdGlvbiAob3B0KSB7XG5cdFx0XHRcdHZhciBsb3dlcl92YWwgPSBvcHQudGV4dC50b0xvd2VyQ2FzZSgpO1xuXHRcdFx0XHR2YXIgaW5wdXRfdmFsX2xvd2VyID0gKGpRdWVyeSgnLnNlYXJjaCcsIGN1cl9pbnB1dCkudmFsKCkpLnRvTG93ZXJDYXNlKCk7XG5cblx0XHRcdFx0cmV0dXJuIGxvd2VyX3ZhbC5pbmRleE9mKGlucHV0X3ZhbF9sb3dlcikgIT0gLTE7XG5cdFx0XHR9KTtcblxuXHRcdFx0eXRfZmlsbF9zdXBlcl9vcHRpb25zX2hpZGUoY3VyX2lucHV0WzBdLCBmaWx0ZXJlZF9yZXN1bHRzKTtcblx0XHR9KTtcblxuXHRcdGpRdWVyeSgnLnVuY2hlY2stYWxsJywgY3VyX2lucHV0KS5jbGljayhmdW5jdGlvbiAoKSB7XG5cblx0XHRcdGpRdWVyeSgnLnl0LXN1cGVyLW9wdGlvbicsIGN1cl9pbnB1dCkuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHRcdFx0alF1ZXJ5KCdpbnB1dCcsIGpRdWVyeSh0aGlzKSkucHJvcCgnY2hlY2tlZCcsIGZhbHNlKTtcblxuXHRcdFx0fSk7XG5cblx0XHR9KTtcblx0fVxuXG59XG5cbmZ1bmN0aW9uIHl0X2ZpbGxfc3VwZXJfb3B0aW9ucyhlbGUsIGxpc3QpIHtcblxuXHRqUXVlcnkoJy55dC1zdXBlci1vcHRpb25zJywgZWxlKS5odG1sKCcnKTtcblxuXHR2YXIgaW5wdXRfbmFtZSA9IGpRdWVyeShlbGUpLmF0dHIoJ25hbWUnKTtcblxuXHRmb3IgKHZhciBpID0gMDsgaSA8IGxpc3QubGVuZ3RoOyBpKyspIHtcblx0XHRpZiAobGlzdFtpXS5pZCAhPSBudWxsICYmIGxpc3RbaV0udGV4dCAhPSAnJykge1xuXHRcdFx0Ly9jb25zb2xlLmxvZyhsaXN0W2ldLnRleHQpO1xuXG5cdFx0XHR2YXIgbmV3X29wdGlvbiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xuXHRcdFx0bmV3X29wdGlvbi5jbGFzc05hbWUgPSAneXQtc3VwZXItb3B0aW9uJztcblxuXHRcdFx0bmV3X29wdGlvbi5pbm5lckhUTUwgPSBgPGxhYmVsIGlkPVwib3B0aW9uLSR7bGlzdFtpXS5pZH1cIiBkYXRhLWJvc3MtaWQ9XCIke2xpc3RbaV0uaWR9XCI+PGlucHV0IHR5cGU9XCJyYWRpb1wiIG5hbWU9XCIke2lucHV0X25hbWV9XCIgdmFsdWU9XCIke2xpc3RbaV0uaWR9XCI+JHtsaXN0W2ldLnRleHR9PC9sYWJlbD5gO1xuXG5cdFx0XHRqUXVlcnkoJy55dC1zdXBlci1vcHRpb25zJywgZWxlKS5hcHBlbmQobmV3X29wdGlvbik7XG5cdFx0fVxuXHR9XG5cblx0alF1ZXJ5KCcueXQtc3VwZXItb3B0aW9uJywgZWxlKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdGpRdWVyeSgnbGFiZWwnLCBqUXVlcnkodGhpcykpLmNsaWNrKGZ1bmN0aW9uICgpIHtcblxuXHRcdFx0aWYgKGpRdWVyeSgnaW5wdXQnLCBqUXVlcnkodGhpcykpLnByb3AoJ2NoZWNrZWQnKSkge1xuXHRcdFx0XHRqUXVlcnkodGhpcykuYWRkQ2xhc3MoJ2NoZWNrZWQnKTtcblx0XHRcdH1cblx0XHRcdGVsc2Uge1xuXHRcdFx0XHRqUXVlcnkodGhpcykucmVtb3ZlQ2xhc3MoJ2NoZWNrZWQnKTtcblx0XHRcdH1cblxuXHRcdH0pO1xuXHR9KTtcbn1cblxuZnVuY3Rpb24geXRfZmlsbF9zdXBlcl9vcHRpb25zX3dpdGhfcHJvbWlzZShlbGUsIGxpc3QpIHtcblxuXHRyZXR1cm4gbmV3IFByb21pc2UoZnVuY3Rpb24gKHJlc29sdmUsIHJlamVjdCkge1xuXG5cdFx0alF1ZXJ5KCcueXQtc3VwZXItb3B0aW9ucycsIGVsZSkuaHRtbCgnJyk7XG5cblx0XHR2YXIgaW5wdXRfbmFtZSA9IGpRdWVyeShlbGUpLmF0dHIoJ25hbWUnKTtcblxuXHRcdGZvciAodmFyIGkgPSAwOyBpIDwgbGlzdC5sZW5ndGg7IGkrKykge1xuXHRcdFx0aWYgKGxpc3RbaV0uaWQgIT0gbnVsbCAmJiBsaXN0W2ldLnRleHQgIT0gJycpIHtcblx0XHRcdFx0Ly9jb25zb2xlLmxvZyhsaXN0W2ldLnRleHQpO1xuXG5cdFx0XHRcdHZhciBuZXdfb3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7XG5cdFx0XHRcdG5ld19vcHRpb24uY2xhc3NOYW1lID0gJ3l0LXN1cGVyLW9wdGlvbic7XG5cblx0XHRcdFx0bmV3X29wdGlvbi5pbm5lckhUTUwgPSBgPGxhYmVsIGlkPVwib3B0aW9uLSR7bGlzdFtpXS5pZH1cIiBkYXRhLWJvc3MtaWQ9XCIke2xpc3RbaV0uaWR9XCI+PGlucHV0IHR5cGU9XCJyYWRpb1wiIG5hbWU9XCIke2lucHV0X25hbWV9XCIgdmFsdWU9XCIke2xpc3RbaV0uaWR9XCI+JHtsaXN0W2ldLnRleHR9PC9sYWJlbD5gO1xuXG5cdFx0XHRcdGpRdWVyeSgnLnl0LXN1cGVyLW9wdGlvbnMnLCBlbGUpLmFwcGVuZChuZXdfb3B0aW9uKTtcblx0XHRcdH1cblx0XHR9XG5cblx0XHRqUXVlcnkoJy55dC1zdXBlci1vcHRpb24nLCBlbGUpLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0XHRqUXVlcnkoJ2xhYmVsJywgalF1ZXJ5KHRoaXMpKS5jbGljayhmdW5jdGlvbiAoKSB7XG5cblx0XHRcdFx0aWYgKGpRdWVyeSgnaW5wdXQnLCBqUXVlcnkodGhpcykpLnByb3AoJ2NoZWNrZWQnKSkge1xuXHRcdFx0XHRcdGpRdWVyeSh0aGlzKS5hZGRDbGFzcygnY2hlY2tlZCcpO1xuXHRcdFx0XHR9XG5cdFx0XHRcdGVsc2Uge1xuXHRcdFx0XHRcdGpRdWVyeSh0aGlzKS5yZW1vdmVDbGFzcygnY2hlY2tlZCcpO1xuXHRcdFx0XHR9XG5cblx0XHRcdH0pO1xuXHRcdH0pO1xuXG5cdFx0cmVzb2x2ZSgpO1xuXG5cdH0pO1xuXG59XG5cbmZ1bmN0aW9uIHl0X2ZpbGxfc3VwZXJfb3B0aW9uc19oaWRlKGVsZSwgbGlzdCkge1xuXG5cdHZhciBsaXN0X2p1c3RfaWRzID0gbGlzdC5tYXAoZnVuY3Rpb24gKG9wdCkgeyByZXR1cm4gb3B0LmlkOyB9KTtcblxuXHRqUXVlcnkoJy55dC1zdXBlci1vcHRpb25zIGxhYmVsJywgZWxlKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdHZhciBpZCA9IGpRdWVyeSh0aGlzKS5kYXRhKCdib3NzLWlkJyk7XG5cblx0XHRpZiAobGlzdF9qdXN0X2lkcy5pbmRleE9mKGlkKSA9PSAtMSkge1xuXHRcdFx0alF1ZXJ5KHRoaXMpLmFkZENsYXNzKCdvcHQtaGlkZScpLnJlbW92ZUNsYXNzKCdvcHQtc2hvdycpO1xuXHRcdH1cblx0XHRlbHNlIHtcblx0XHRcdGpRdWVyeSh0aGlzKS5hZGRDbGFzcygnb3B0LXNob3cnKS5yZW1vdmVDbGFzcygnb3B0LWhpZGUnKTtcblx0XHR9XG5cdH0pO1xufVxuXG4vLyBTZWxlY3QgQWZ0ZXIgTG9hZGluZ1xuZnVuY3Rpb24gWVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGVsZSkge1xuXG5cdGlmIChqUXVlcnkoZWxlKS5kYXRhKCd2YWx1ZScpICE9PSBcIlwiKSB7XG5cblx0XHRpZiAoalF1ZXJ5KGVsZSkuaGFzQ2xhc3MoJ3l0LXN1cGVyLXNlbGVjdCcpKSB7XG5cdFx0XHR2YXIgaW5wdXRfbmFtZSA9IGpRdWVyeShlbGUpLmF0dHIoJ25hbWUnKTtcblx0XHRcdHZhciBpbnB1dF92YWwgPSBlbGUuZGF0YSgndmFsdWUnKTtcblxuXHRcdFx0alF1ZXJ5KCdpbnB1dFtuYW1lPVwiJyArIGlucHV0X25hbWUgKyAnXCJdW3ZhbHVlPVwiJyArIGlucHV0X3ZhbCArICdcIl0nLCBqUXVlcnkoZWxlKSkucHJvcCgnY2hlY2tlZCcsIHRydWUpO1xuXHRcdFx0alF1ZXJ5KCdpbnB1dFtuYW1lPVwiJyArIGlucHV0X25hbWUgKyAnXCJdW3ZhbHVlPVwiJyArIGlucHV0X3ZhbCArICdcIl0nLCBqUXVlcnkoZWxlKSkucGFyZW50KCkuYWRkQ2xhc3MoJ2NoZWNrZWQnKTtcblx0XHR9XG5cdFx0ZWxzZSB7XG5cblx0XHRcdGpRdWVyeShlbGUpLnZhbChqUXVlcnkoZWxlKS5kYXRhKCd2YWx1ZScpKTtcblxuXHRcdH1cblxuXHR9XG5cbn1cblxuLy8gQ291bnRyaWVzXG5mdW5jdGlvbiBHZXRDb3VudHJ5TGlzdCh2YWx1ZSkge1xuXHR2YWx1ZSA9IHZhbHVlIHx8ICcnO1xuXG5cdGlmIChqUXVlcnkoJy5maWxsLWNvdW50cnknKS5sZW5ndGggPiAwICYmIHZhbHVlICE9ICcnKSB7XG5cdFx0X1lCQS5sYWJlbF9vcHRpb25zKHsgbGFiZWw6ICdHZW9MaXN0QWN0aXZlVmVzc2VsUmVnaW9uQ291bnRyaWVzJywgUmVnaW9uSWQ6IHZhbHVlIH0pLnRoZW4oZnVuY3Rpb24gKGxzdCkge1xuXHRcdFx0R2V0Q291bnRyeUxpc3RfU3VjY2Vzcyhsc3QuR2VvTGlzdEFjdGl2ZVZlc3NlbFJlZ2lvbkNvdW50cmllcyk7XG5cdFx0fSk7XG5cdH1cblxufVxuXG5mdW5jdGlvbiBHZXRDb3VudHJ5TGlzdF9TdWNjZXNzKGxzdCkge1xuXHR2YXIgZGQgPSBqUXVlcnkoJy5maWxsLWNvdW50cnknKTtcblx0ZGQuZW1wdHkoKTtcblxuXHRpZiAobHN0Lmxlbmd0aCA9PSAwKSB7XG5cdFx0ZGQuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+Tm9uZTwvb3B0aW9uPicpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdGRkLmFwcGVuZCgnPG9wdGlvbiB2YWx1ZT1cIlwiPkFueSBDb3VudHJ5PC9vcHRpb24+Jyk7XG5cblx0XHRmb3IgKHZhciBpID0gMDsgaSA8IGxzdC5sZW5ndGg7IGkrKykge1xuXHRcdFx0aWYgKGRkLmF0dHIoJ2RhdGEteWF0Y28tZmlsLWp1c3QtdGV4dC12YWwnKSA9PSAndHJ1ZScpIHtcblx0XHRcdFx0ZGQuYXBwZW5kKCc8b3B0aW9uIGRhdGEtaWQ9XCInICsgbHN0W2ldLmlkICsgJ1wiIHZhbHVlPVwiJyArIGxzdFtpXS50ZXh0ICsgJ1wiPicgKyBsc3RbaV0uQ291bnRyeSArICc8L29wdGlvbj4nKTtcblx0XHRcdH1cblx0XHRcdGVsc2Uge1xuXHRcdFx0XHRkZC5hcHBlbmQoJzxvcHRpb24gdmFsdWU9XCInICsgbHN0W2ldLmlkICsgJ1wiPicgKyBsc3RbaV0udGV4dCArICc8L29wdGlvbj4nKTtcblx0XHRcdH1cblxuXG5cdFx0fVxuXHR9XG5cblx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGRkKTtcbn1cblxuXG5cbi8vIFNUQVRFU1xuZnVuY3Rpb24gR2V0U3RhdGVMaXN0KHZhbHVlKSB7XG5cdHZhbHVlID0gdmFsdWUgfHwgJyc7XG5cblx0aWYgKGpRdWVyeSgnLmZpbGwtc3RhdGUnKS5sZW5ndGggPiAwICYmIHZhbHVlICE9ICcnKSB7XG5cdFx0X1lCQS5sYWJlbF9vcHRpb25zKHsgbGFiZWw6ICdTdGF0ZXMnLCBjb3VudHJ5U0VPOiB2YWx1ZSB9KS50aGVuKGZ1bmN0aW9uIChsc3QpIHtcblx0XHRcdEdldFN0YXRlTGlzdF9TdWNjZXNzKGxzdC5TdGF0ZXMpO1xuXHRcdH0pO1xuXHR9XG59XG5cbmZ1bmN0aW9uIEdldEdlb1N0YXRlTGlzdCh2YWx1ZSkge1xuXHR2YWx1ZSA9IHZhbHVlIHx8ICcnO1xuXG5cdGlmIChqUXVlcnkoJy5maWxsLXN0YXRlJykubGVuZ3RoID4gMCAmJiB2YWx1ZSAhPSAnJykge1xuXHRcdF9ZQkEubGFiZWxfb3B0aW9ucyh7IGxhYmVsOiAnR2VvTGlzdEFjdGl2ZVZlc3NlbFN0YXRlcycsIENvdW50cnlJZDogdmFsdWUgfSkudGhlbihmdW5jdGlvbiAobHN0KSB7XG5cdFx0XHRHZXRTdGF0ZUxpc3RfU3VjY2Vzcyhsc3QuR2VvTGlzdEFjdGl2ZVZlc3NlbFN0YXRlcyk7XG5cdFx0fSk7XG5cdH1cbn1cblxuZnVuY3Rpb24gR2V0U3RhdGVMaXN0X1N1Y2Nlc3MobHN0KSB7XG5cdHZhciBkZCA9IGpRdWVyeSgnLmZpbGwtc3RhdGUnKTtcblx0ZGQuZW1wdHkoKTtcblxuXHRpZiAobHN0Lmxlbmd0aCA9PSAwKSB7XG5cdFx0ZGQuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+Tm9uZTwvb3B0aW9uPicpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdGRkLmFwcGVuZCgnPG9wdGlvbiB2YWx1ZT1cIlwiPkFueSBTdGF0ZTwvb3B0aW9uPicpO1xuXG5cdFx0Zm9yICh2YXIgaSA9IDA7IGkgPCBsc3QubGVuZ3RoOyBpKyspIHtcblx0XHRcdGlmIChkZC5hdHRyKCdkYXRhLXlhdGNvLWZpbC1qdXN0LXRleHQtdmFsJykgPT0gJ3RydWUnKSB7XG5cdFx0XHRcdGRkLmFwcGVuZCgnPG9wdGlvbiBkYXRhLWlkPVwiJyArIGxzdFtpXS5pZCArICdcIiB2YWx1ZT1cIicgKyBsc3RbaV0udGV4dCArICdcIj4nICsgbHN0W2ldLnRleHQgKyAnPC9vcHRpb24+Jyk7XG5cdFx0XHR9XG5cdFx0XHRlbHNlIHtcblx0XHRcdFx0ZGQuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiJyArIGxzdFtpXS5pZCArICdcIj4nICsgbHN0W2ldLnRleHQgKyAnPC9vcHRpb24+Jyk7XG5cdFx0XHR9XG5cblxuXHRcdH1cblx0fVxuXG5cdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhkZCk7XG59XG5cbi8vIENJVElFU1xuZnVuY3Rpb24gR2V0R2VvQ2l0eUxpc3QodmFsdWUpIHtcblx0dmFsdWUgPSB2YWx1ZSB8fCAnJztcblxuXHRpZiAoalF1ZXJ5KCcuZmlsbC1zdGF0ZScpLmxlbmd0aCA+IDAgJiYgdmFsdWUgIT0gJycpIHtcblx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ0dlb0xpc3RBY3RpdmVDaXRpZXMnLCBTdGF0ZUlkOiB2YWx1ZSB9KS50aGVuKGZ1bmN0aW9uIChsc3QpIHtcblx0XHRcdEdldENpdHlMaXN0X1N1Y2Nlc3MobHN0Lkdlb0xpc3RBY3RpdmVDaXRpZXMpO1xuXHRcdH0pO1xuXHR9XG59XG5cbmZ1bmN0aW9uIEdldEdlb0NvdW50cnlDaXR5TGlzdCh2YWx1ZSkge1xuXHR2YWx1ZSA9IHZhbHVlIHx8ICcnO1xuXG5cdGlmIChqUXVlcnkoJy5maWxsLXN0YXRlJykubGVuZ3RoID4gMCAmJiB2YWx1ZSAhPSAnJykge1xuXG5cdFx0X1lCQS5sYWJlbF9vcHRpb25zKHsgbGFiZWw6ICdHZW9MaXN0QWN0aXZlVmVzc2VsQ291bnRyeUNpdGllcycsIENvdW50cnlJZDogdmFsdWUgfSkudGhlbihmdW5jdGlvbiAobHN0KSB7XG5cdFx0XHRHZXRDaXR5TGlzdF9TdWNjZXNzKGxzdC5HZW9MaXN0QWN0aXZlVmVzc2VsQ291bnRyeUNpdGllcyk7XG5cdFx0fSk7XG5cblx0fVxufVxuXG5mdW5jdGlvbiBHZXRDaXR5TGlzdF9TdWNjZXNzKGxzdCkge1xuXHR2YXIgZGQgPSBqUXVlcnkoJy5maWxsLWNpdHknKTtcblx0ZGQuZW1wdHkoKTtcblxuXHRpZiAobHN0Lmxlbmd0aCA9PSAwKSB7XG5cdFx0ZGQuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+Tm9uZTwvb3B0aW9uPicpO1xuXHR9XG5cdGVsc2Uge1xuXHRcdGRkLmFwcGVuZCgnPG9wdGlvbiB2YWx1ZT1cIlwiPkFueSBDaXR5PC9vcHRpb24+Jyk7XG5cblx0XHRmb3IgKHZhciBpID0gMDsgaSA8IGxzdC5sZW5ndGg7IGkrKykge1xuXHRcdFx0ZGQuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiJyArIGxzdFtpXS5pZCArICdcIj4nICsgbHN0W2ldLnRleHQgKyAnPC9vcHRpb24+Jyk7XG5cdFx0fVxuXHR9XG5cblx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGRkKTtcbn1cblxuLy8gRmlsbGluZyBBc3NvY2lhdGlvbiBSZWdpb25zXG5mdW5jdGlvbiBHZXRBc3NvY2lhdGlvblJlZ2lvbnMobHN0KSB7XG5cdGpRdWVyeSgnLmZpbGwtYXNzb2NpYXRpb24tcmVnaW9uJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cdFx0dmFyIGRkZCA9IGpRdWVyeSh0aGlzKTtcblxuXHRcdF9ZQkEubGFiZWxfb3B0aW9ucyh7XG5cdFx0XHRsYWJlbDogJ0Fzc29jaWF0aW9uUmVnaW9ucycsXG5cdFx0XHQnaWQnOiBqUXVlcnkodGhpcykuZGF0YSgnYXNzb2NpYXRpb24taWQnKSxcblx0XHR9KS50aGVuKGZ1bmN0aW9uIChsc3QpIHtcblx0XHRcdHZhciBkZCA9IGRkZDtcblx0XHRcdGRkLmVtcHR5KCk7XG5cdFx0XHRkZC5hcHBlbmQoJzxvcHRpb24gdmFsdWU9XCJcIj5BbGw8L29wdGlvbj4nKTtcblxuXHRcdFx0eXRfZmlsbF9vcHRpb25zKGRkWzBdLCBsc3QuQXNzb2NpYXRpb25SZWdpb25zKTtcblxuXHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGRkKTtcblx0XHR9KTtcblx0fSk7XG59XG5cbi8vLyBkYXRhLXlhdGNvLWZpbGwtb3B0aW9uc1xudmFyIHl0X2RhdGFfZmlsbHMgPSB7fTtcblxuZnVuY3Rpb24geWF0Y29fZGF0YV9maWxsX29wdGlvbnMocm9vdF9lbGUpIHtcblx0Y29uc29sZS5sb2coeXRfZGF0YV9maWxscyk7XG5cblx0dmFyIGxpc3Rfb2ZfZmlsbHMgPSBbXTtcblxuXHRqUXVlcnkoJ3NlbGVjdFtkYXRhLXlhdGNvLWZpbGwtb3B0aW9uc106bm90KFtmaWxsZWRdKScsIHJvb3RfZWxlKS5lYWNoKGZ1bmN0aW9uICgpIHtcblx0XHR2YXIgYXR0cl9sYWJlbCA9IGpRdWVyeSh0aGlzKS5kYXRhKCd5YXRjby1maWxsLW9wdGlvbnMnKTtcblx0XHR2YXIgY3VyX2lucHV0ID0galF1ZXJ5KHRoaXMpO1xuXG5cdFx0aWYgKGxpc3Rfb2ZfZmlsbHMuaW5kZXhPZihhdHRyX2xhYmVsKSA9PSAtMSkge1xuXHRcdFx0bGlzdF9vZl9maWxscy5wdXNoKGF0dHJfbGFiZWwpO1xuXHRcdH1cblx0fSk7XG5cblx0Zm9yIChjb25zdCBwcm9wZXJ0eSBpbiB5dF9kYXRhX2ZpbGxzKSB7XG5cdFx0Y29uc29sZS5sb2cocHJvcGVydHkpO1xuXG5cdFx0aWYgKGxpc3Rfb2ZfZmlsbHMuaW5kZXhPZihwcm9wZXJ0eSkgIT0gLTEpIHtcblxuXHRcdFx0dmFyIGluZGV4ID0gbGlzdF9vZl9maWxscy5pbmRleE9mKHByb3BlcnR5KTtcblxuXHRcdFx0Y29uc29sZS5sb2coJ3JlbW9lJyk7XG5cblx0XHRcdGRlbGV0ZSBsaXN0X29mX2ZpbGxzW2luZGV4XTtcblxuXHRcdH1cblxuXHR9XG5cblx0aWYgKGxpc3Rfb2ZfZmlsbHMubGVuZ3RoID49IDEpIHtcblxuXHRcdF9ZQkEubGFiZWxfb3B0aW9ucyhsaXN0X29mX2ZpbGxzKS50aGVuKGZ1bmN0aW9uIChvcHRzKSB7XG5cdFx0XHRmb3IgKGNvbnN0IHByb3BlcnR5IGluIG9wdHMpIHtcblx0XHRcdFx0dmFyIG9wdGlvbnMgPSBvcHRzW3Byb3BlcnR5XTtcblxuXHRcdFx0XHR5dF9kYXRhX2ZpbGxzW3Byb3BlcnR5XSA9IG9wdGlvbnM7XG5cblx0XHRcdFx0alF1ZXJ5KCdzZWxlY3RbZGF0YS15YXRjby1maWxsLW9wdGlvbnM9XCInICsgcHJvcGVydHkgKyAnXCJdJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cdFx0XHRcdFx0dmFyIGF0dHJfbGFiZWwgPSBqUXVlcnkodGhpcykuZGF0YSgneWF0Y28tZmlsbC1vcHRpb25zJyk7XG5cdFx0XHRcdFx0dmFyIGN1cl9pbnB1dCA9IGpRdWVyeSh0aGlzKTtcblxuXHRcdFx0XHRcdGlmIChjdXJfaW5wdXQuZGF0YSgneWF0Y28tZW1wdHktYWZ0ZXItZmlsbCcpID09IHRydWUpIHtcblx0XHRcdFx0XHRcdGN1cl9pbnB1dC5lbXB0eSgpO1xuXHRcdFx0XHRcdH1cblxuXHRcdFx0XHRcdHl0X2ZpbGxfb3B0aW9ucyhjdXJfaW5wdXRbMF0sIG9wdGlvbnMpO1xuXG5cdFx0XHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGN1cl9pbnB1dCk7XG5cblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0fSk7XG5cblx0fVxuXG5cdGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtkYXRhLXlhdGNvLWZpbGwtb3B0aW9uc106bm90KFtmaWxsZWRdKScsIHJvb3RfZWxlKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdHl0X2luaXRfc3VwZXJfc2VsZWN0b3IoalF1ZXJ5KHRoaXMpKTtcblxuXHRcdGNvbnNvbGUubG9nKGpRdWVyeSh0aGlzKVswXSk7XG5cblx0fSk7XG5cbn1cblxualF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbiAoKSB7XG5cdC8vIFJlc290aW5nIEZvcm0gRGF0YVxuXHRqUXVlcnkoJ2Zvcm1bZGF0YS1pbnB1dC12YWx1ZXNdJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHR2YXIgdmFsdWVzID0galF1ZXJ5KHRoaXMpLmRhdGEoJ2lucHV0LXZhbHVlcycpO1xuXG5cdFx0cnVuX3Rocm91Z2hfYW5kX3N5bmNfZm9ybShqUXVlcnkodGhpcyksIHZhbHVlcyk7XG5cblx0fSk7XG5cblx0aWYgKFxuXHRcdChqUXVlcnkoJy55YXRjby1qcy1zaG9ydGNvZGUteWFjaHQtcmVzdWx0cywgLnlhdGNvLWpzLXNob3J0Y29kZS15YWNodHMtc2VhcmNoLWZvcm0nKS5sZW5ndGggPiAwKVxuXHRcdHx8XG5cdFx0KGpRdWVyeSgnLnlhdGNvLWpzLXNob3J0Y29kZS1jaGFydGVyLXJlc3VsdHMsIC55YXRjby1qcy1zaG9ydGNvZGUtY2hhcnRlci1zZWFyY2gtZm9ybScpLmxlbmd0aCA+IDApXG5cdCkge1xuXG5cdFx0dmFyIHJvb3RfZWxlID0galF1ZXJ5KCcueWF0Y28tanMtc2hvcnRjb2RlLXlhY2h0cy1zZWFyY2gtZm9ybScpO1xuXG5cdFx0alF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W2RhdGEteWF0Y28tZmlsbC1vcHRpb25zXTpub3QoW2ZpbGxlZF0pJywgcm9vdF9lbGUpLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0XHR5dF9pbml0X3N1cGVyX3NlbGVjdG9yKGpRdWVyeSh0aGlzKSk7XG5cblx0XHR9KTtcblxuXHR9XG5cdGVsc2Uge1xuXHRcdC8vIGRhdGEteWF0Y28tZmlsbC1vcHRpb25zXG5cdFx0eWF0Y29fZGF0YV9maWxsX29wdGlvbnMoalF1ZXJ5KCcuc21hbGwtZm9ybSwgLmJpZy1mb3JtLCAueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMsIC55YXRjby1zaG9ydGNvZGUtc2VhcmNoLWZvcm0nKSk7XG5cblx0XHRqUXVlcnkoJy5zbWFsbC1mb3JtLCAuYmlnLWZvcm0nKS5vbignY2hhbmdlJywgZnVuY3Rpb24gKGUpIHtcblxuXHRcdFx0dmFyIHBhcmFtcyA9IGdldF9mb3JtX2RhdGEoalF1ZXJ5KHRoaXMpWzBdKTtcblxuXHRcdFx0cnVuX3Rocm91Z2hfYW5kX3N5bmNfZm9ybShqUXVlcnkoJy5zbWFsbC1mb3JtLCAuYmlnLWZvcm0nKSwgcGFyYW1zKTtcblxuXHRcdH0pO1xuXHR9XG5cblx0R2V0QXNzb2NpYXRpb25SZWdpb25zKCk7XG5cblx0Ly8gRmlsbGluZyBDb3VudHJ5XG5cdGpRdWVyeSgnc2VsZWN0W2RhdGEteWF0Y28tZmlsbC1vcHRpb25zPVwiR2VvTGlzdFJlZ2lvbnNXaXRoQWN0aXZlQ291bnRyaWVzXCJdJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHR2YXIgcmVnaW9uSUQgPSBqUXVlcnkodGhpcykuZGF0YSgndmFsdWUnKTtcblxuXHRcdGlmIChyZWdpb25JRCA9PSAxIHx8IHJlZ2lvbklEID09IDEyKSB7XG5cdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCBzZWxlY3QnKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuXHRcdH1cblx0XHRlbHNlIHtcblx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkIHNlbGVjdCcpLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG5cdFx0fVxuXG5cdFx0R2V0Q291bnRyeUxpc3QocmVnaW9uSUQpO1xuXG5cdH0pLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XG5cblx0XHR2YXIgcmVnaW9uSUQgPSBqUXVlcnkodGhpcykudmFsKCk7XG5cblx0XHRpZiAocmVnaW9uSUQgPT0gMSB8fCByZWdpb25JRCA9PSAxMikge1xuXHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQgc2VsZWN0JykucmVtb3ZlQXR0cignZGlzYWJsZWQnKTtcblx0XHR9XG5cdFx0ZWxzZSB7XG5cdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCBzZWxlY3QnKS5hdHRyKCdkaXNhYmxlZCcsICdkaXNhYmxlZCcpO1xuXHRcdH1cblxuXHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9XCJMb2NhdGlvbkNvdW50cnlJRFwiXScpLmh0bWwoJzxvcHRpb24gdmFsdWU9XCJcIj5QaWNrIFJlZ2lvbiBGaXJzdC4uLjwvb3B0aW9uPicpO1xuXHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9XCJMb2NhdGlvblN0YXRlSURcIl0nKS5odG1sKCc8b3B0aW9uIHZhbHVlPVwiXCI+UGljayBDb3VudHJ5IEZpcnN0Li4uPC9vcHRpb24+Jyk7XG5cdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1cIkxvY2F0aW9uQ2l0eUlEXCJdJykuaHRtbCgnPG9wdGlvbiB2YWx1ZT1cIlwiPlBpY2sgU3RhdGUgRmlyc3QuLi48L29wdGlvbj4nKTtcblxuXHRcdEdldENvdW50cnlMaXN0KHJlZ2lvbklEKTtcblxuXHR9KTtcblxuXG5cdC8vIEZpbGxpbmcgU3RhdGVzXG5cdC8qalF1ZXJ5KCdzZWxlY3RbZGF0YS15YXRjby1maWxsLW9wdGlvbnM9XCJDb3VudHJpZXNcIl0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXHRcdFxuXHRcdEdldFN0YXRlTGlzdChqUXVlcnkodGhpcykuZGF0YSgndmFsdWUnKSk7XG5cblxuXHR9KS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuXG5cblx0XHR2YXIgQ291bnRyeUlEID0galF1ZXJ5KHRoaXMpLnZhbCgpO1xuXG5cdFx0R2V0U3RhdGVMaXN0KENvdW50cnlJRCk7XG5cblxuXHR9KTtcbiovXG5cdGpRdWVyeSgnc2VsZWN0W25hbWU9XCJMb2NhdGlvbkNvdW50cnlJRFwiXScpLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0dmFyIENvdW50cnlJRCA9IGpRdWVyeSh0aGlzKS5kYXRhKCd2YWx1ZScpO1xuXG5cdFx0aWYgKFxuXHRcdFx0Q291bnRyeUlEID09IDIzMyB8fCBDb3VudHJ5SUQgPT0gMzlcblx0XHRcdHx8XG5cdFx0XHRDb3VudHJ5SUQgPT0gMTQgfHwgQ291bnRyeUlEID09IDE1OFxuXHRcdCkge1xuXG5cdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCBzZWxlY3QnKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHRqUXVlcnkoJ3NlbGVjdFtuYW1lPVwiTG9jYXRpb25TdGF0ZUlEXCJdJykuaHRtbCgnPG9wdGlvbiB2YWx1ZT1cIlwiPlBpY2sgQ291bnRyeSBGaXJzdC4uLjwvb3B0aW9uPicpO1xuXHRcdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1cIkxvY2F0aW9uQ2l0eUlEXCJdJykuaHRtbCgnPG9wdGlvbiB2YWx1ZT1cIlwiPlBpY2sgU3RhdGUgRmlyc3QuLi48L29wdGlvbj4nKTtcblx0XHRcdEdldEdlb1N0YXRlTGlzdChDb3VudHJ5SUQpO1xuXG5cdFx0fVxuXHRcdGVsc2Uge1xuXHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQgc2VsZWN0JykuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcblxuXHRcdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1cIkxvY2F0aW9uQ2l0eUlEXCJdJykuaHRtbCgnPG9wdGlvbiB2YWx1ZT1cIlwiPlBpY2sgQ291bnRyeSBGaXJzdC4uLjwvb3B0aW9uPicpO1xuXG5cdFx0XHRHZXRHZW9Db3VudHJ5Q2l0eUxpc3QoQ291bnRyeUlEKTtcblx0XHR9XG5cblxuXHR9KS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuXG5cblx0XHQvLyBVU0EgKGxvY2F0aW9uIElEIDIzMyksIENhbmFkYSAobG9jYXRpb24gSUQgMzkpIFxuXHRcdC8vIEF1c3RyYWxpYSAobG9jYXRpb24gSUQgMTQpLCBhbmQgTmV3IFplYWxhbmQgKGxvY2F0aW9uIElEIDE1OCkuXG5cblx0XHRpZiAoalF1ZXJ5KHRoaXMpLmF0dHIoJ2RhdGEteWF0Y28tZmlsLWp1c3QtdGV4dC12YWwnKSA9PSAndHJ1ZScpIHtcblx0XHRcdEdldEdlb1N0YXRlTGlzdChqUXVlcnkodGhpcykuZGF0YSgnaWQnKSk7XG5cblx0XHRcdGlmIChcblx0XHRcdFx0Q291bnRyeUlEID09IDIzMyB8fCBDb3VudHJ5SUQgPT0gMzlcblx0XHRcdFx0fHxcblx0XHRcdFx0Q291bnRyeUlEID09IDE0IHx8IENvdW50cnlJRCA9PSAxNThcblx0XHRcdCkge1xuXG5cblx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCBzZWxlY3QnKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHR9XG5cdFx0XHRlbHNlIHtcblxuXHRcdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkIHNlbGVjdCcpLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG5cblx0XHRcdH1cblx0XHR9XG5cdFx0ZWxzZSB7XG5cblx0XHRcdHZhciBDb3VudHJ5SUQgPSBqUXVlcnkodGhpcykudmFsKCk7XG5cblx0XHRcdGlmIChcblx0XHRcdFx0Q291bnRyeUlEID09IDIzMyB8fCBDb3VudHJ5SUQgPT0gMzlcblx0XHRcdFx0fHxcblx0XHRcdFx0Q291bnRyeUlEID09IDE0IHx8IENvdW50cnlJRCA9PSAxNThcblx0XHRcdCkge1xuXG5cdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQgc2VsZWN0JykucmVtb3ZlQXR0cignZGlzYWJsZWQnKTtcblxuXHRcdFx0XHRHZXRHZW9TdGF0ZUxpc3QoQ291bnRyeUlEKTtcblxuXHRcdFx0fVxuXHRcdFx0ZWxzZSB7XG5cdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQgc2VsZWN0JykuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcblxuXHRcdFx0XHRHZXRHZW9Db3VudHJ5Q2l0eUxpc3QoQ291bnRyeUlEKTtcblx0XHRcdH1cblxuXHRcdH1cblxuXHR9KTtcblxuXHQvLyBGaWxsaW5nIENpdGllc1xuXG5cdGpRdWVyeSgnc2VsZWN0W25hbWU9XCJMb2NhdGlvblN0YXRlSURcIl0nKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdEdldEdlb0NpdHlMaXN0KGpRdWVyeSh0aGlzKS5kYXRhKCd2YWx1ZScpKTtcblxuXHR9KS5vbignY2hhbmdlJywgZnVuY3Rpb24gKCkge1xuXG5cdFx0R2V0R2VvQ2l0eUxpc3QoalF1ZXJ5KHRoaXMpLnZhbCgpKTtcblxuXHR9KTtcblxuXHQvLyBmaWxsICNidWlsZGVyc19saXN0XG5cdGlmIChqUXVlcnkoJyNidWlsZGVyc19saXN0JykubGVuZ3RoID4gMCAmJiBqUXVlcnkoJyN2ZXNzZWxfbmFtZXNfbGlzdCcpLmxlbmd0aCA+IDApIHtcblx0XHRfWUJBLmNhbGxfYXBpKFxuXHRcdFx0XCJQT1NUXCIsXG5cdFx0XHQnZm9ybS1kYXRhLWNvbW1vbicsXG5cdFx0XHR7XG5cdFx0XHRcdGxhYmVsOiAnQWN0aXZlQnVpbGRlcnMsVmVzc2VsTmFtZXMnLFxuXHRcdFx0XHQvL2xhYmVsOiAnUG9wdWxhckJ1aWxkZXJzJywgXG5cdFx0XHRcdGZpbHRlcjogJydcblx0XHRcdH1cblx0XHQpLnRoZW4oZnVuY3Rpb24gKGRhdGEpIHtcblx0XHRcdHl0X2RhdGFfZmlsbHNbJ0FjdGl2ZUJ1aWxkZXJzJ10gPSBkYXRhLkFjdGl2ZUJ1aWxkZXJzO1xuXG5cdFx0XHR5dF9kYXRhX2ZpbGxzWydWZXNzZWxOYW1lcyddID0gZGF0YS5WZXNzZWxOYW1lcztcblxuXHRcdFx0c2V0dXBGaWx0ZXJlZEF1dG9jb21wbGV0ZShcblx0XHRcdFx0J2lucHV0W25hbWU9XCJWZXNzZWxOYW1lXCJdW2xpc3RdJyxcblx0XHRcdFx0J3Zlc3NlbF9uYW1lc19saXN0Jyxcblx0XHRcdFx0eXRfZGF0YV9maWxscy5WZXNzZWxOYW1lcyxcblx0XHRcdFx0ZnVuY3Rpb24gKGl0ZW0pIHsgcmV0dXJuIGl0ZW07IH1cblx0XHRcdCk7XG5cblx0XHRcdC8vIFNldHVwIGZpbHRlcmVkIGF1dG9jb21wbGV0ZSBmb3IgQnVpbGRlcnNcblx0XHRcdHNldHVwRmlsdGVyZWRBdXRvY29tcGxldGUoXG5cdFx0XHRcdCdpbnB1dFtuYW1lPVwiQnVpbGRlclwiXVtsaXN0XScsXG5cdFx0XHRcdCdidWlsZGVyc19saXN0Jyxcblx0XHRcdFx0eXRfZGF0YV9maWxscy5BY3RpdmVCdWlsZGVycyxcblx0XHRcdFx0ZnVuY3Rpb24gKGl0ZW0pIHsgcmV0dXJuIGl0ZW0udGV4dDsgfVxuXHRcdFx0KTtcblxuXG5cblxuXHRcdH0pO1xuXG5cdH1cblx0ZWxzZSB7XG5cdFx0aWYgKGpRdWVyeSgnI2J1aWxkZXJzX2xpc3QnKS5sZW5ndGggPiAwKSB7XG5cdFx0XHRfWUJBLmNhbGxfYXBpKFxuXHRcdFx0XHRcIlBPU1RcIixcblx0XHRcdFx0J2Zvcm0tZGF0YS1jb21tb24nLFxuXHRcdFx0XHR7XG5cdFx0XHRcdFx0bGFiZWw6ICdBY3RpdmVCdWlsZGVycycsXG5cdFx0XHRcdFx0Ly9sYWJlbDogJ1BvcHVsYXJCdWlsZGVycycsIFxuXHRcdFx0XHRcdGZpbHRlcjogJydcblx0XHRcdFx0fVxuXHRcdFx0KS50aGVuKGZ1bmN0aW9uIChkYXRhKSB7XG5cdFx0XHRcdHl0X2RhdGFfZmlsbHNbJ0FjdGl2ZUJ1aWxkZXJzJ10gPSBkYXRhLkFjdGl2ZUJ1aWxkZXJzO1xuXG5cdFx0XHRcdHNldHVwRmlsdGVyZWRBdXRvY29tcGxldGUoXG5cdFx0XHRcdFx0J2lucHV0W25hbWU9XCJCdWlsZGVyXCJdW2xpc3RdJyxcblx0XHRcdFx0XHQnYnVpbGRlcnNfbGlzdCcsXG5cdFx0XHRcdFx0eXRfZGF0YV9maWxscy5BY3RpdmVCdWlsZGVycyxcblx0XHRcdFx0XHRmdW5jdGlvbiAoaXRlbSkgeyByZXR1cm4gaXRlbS50ZXh0OyB9XG5cdFx0XHRcdCk7XG5cdFx0XHR9KTtcblx0XHR9XG5cblx0XHRpZiAoalF1ZXJ5KCcjdmVzc2VsX25hbWVzX2xpc3QnKS5sZW5ndGggPiAwKSB7XG5cdFx0XHRfWUJBLmNhbGxfYXBpKFxuXHRcdFx0XHRcIlBPU1RcIixcblx0XHRcdFx0J2Zvcm0tZGF0YS1jb21tb24nLFxuXHRcdFx0XHR7IGxhYmVsOiAnVmVzc2VsTmFtZXMnLCBmaWx0ZXI6ICcnIH1cblx0XHRcdCkudGhlbihmdW5jdGlvbiAoZGF0YSkge1xuXG5cdFx0XHRcdHl0X2RhdGFfZmlsbHNbJ1Zlc3NlbE5hbWVzJ10gPSBkYXRhLlZlc3NlbE5hbWVzO1xuXG5cdFx0XHRcdHNldHVwRmlsdGVyZWRBdXRvY29tcGxldGUoXG5cdFx0XHRcdFx0J2lucHV0W25hbWU9XCJWZXNzZWxOYW1lXCJdW2xpc3RdJyxcblx0XHRcdFx0XHQndmVzc2VsX25hbWVzX2xpc3QnLFxuXHRcdFx0XHRcdHl0X2RhdGFfZmlsbHMuVmVzc2VsTmFtZXMsXG5cdFx0XHRcdFx0ZnVuY3Rpb24gKGl0ZW0pIHsgcmV0dXJuIGl0ZW07IH1cblx0XHRcdFx0KTtcblx0XHRcdH0pO1xuXHRcdH1cblx0fVxuXG5cdGlmIChqUXVlcnkoJyNkZXN0aW5hdGlvbnNfbGlzdCcpLmxlbmd0aCA+IDApIHtcblx0XHRfWUJBLmNhbGxfYXBpKFxuXHRcdFx0XCJQT1NUXCIsXG5cdFx0XHQnZm9ybS1kYXRhLWNvbW1vbicsXG5cdFx0XHR7IGxhYmVsOiAnRERDaGFydGVyRGVzdGluYXRpb25zJywgZmlsdGVyOiAnJyB9XG5cdFx0KS50aGVuKGZ1bmN0aW9uIChkYXRhKSB7XG5cblx0XHRcdGpRdWVyeSgnI2Rlc3RpbmF0aW9uc19saXN0JykuaHRtbCgnJyk7XG5cblx0XHRcdHZhciBsaXN0ID0gW107XG5cblx0XHRcdGZvciAodmFyIGIgPSAwOyBiIDwgZGF0YS5ERENoYXJ0ZXJEZXN0aW5hdGlvbnMubGVuZ3RoOyBiKyspIHtcblxuXHRcdFx0XHR2YXIgaXRlbSA9IGRhdGEuRERDaGFydGVyRGVzdGluYXRpb25zW2JdO1xuXG5cdFx0XHRcdGxpc3QucHVzaChpdGVtLnRleHQucmVwbGFjZSgnLS0gJywgJycpKTtcblxuXHRcdFx0fVxuXG5cdFx0XHRsaXN0LnNvcnQoKTtcblxuXHRcdFx0Zm9yICh2YXIgYiA9IDA7IGIgPCBsaXN0Lmxlbmd0aDsgYisrKSB7XG5cdFx0XHRcdGpRdWVyeSgnI2Rlc3RpbmF0aW9uc19saXN0JykuYXBwZW5kKCc8b3B0aW9uIHZhbHVlPVwiJyArIGxpc3RbYl0gKyAnXCI+PC9vcHRpb24+Jyk7XG5cdFx0XHR9XG5cblx0XHR9KTtcblx0fVxuXG5cbn0pOyIsImZ1bmN0aW9uIHl0X2NhdGVnb3J5X2Jhc2VkX29mZl9vZl90eXBlKGVsZSkge1xuXG5cdGlmIChlbGUudmFsKCkgPT0gJzEnIHx8IGVsZS52YWwoKSA9PT0gMSApIHtcblx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ1NhaWxDYXRlZ29yaWVzJyB9KS50aGVuKGZ1bmN0aW9uKGxzdCkge1xuXHRcdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKS5lbXB0eSgpO1xuXG5cdFx0XHRqUXVlcnkoJ3NlbGVjdFtuYW1lPU1haW5DYXRlZ29yeUlEXScpLnByZXBlbmQoJzxvcHRpb24gdmFsdWU9XCJcIj5BbGw8L29wdGlvbj4nKTtcblxuXHRcdFx0aWYgKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPU1haW5DYXRlZ29yeUlEXScpLmxlbmd0aCkge1xuXHRcdFx0XHR2YXIgY3VyX2lucHV0PWpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPU1haW5DYXRlZ29yeUlEXScpO1xuXG5cdFx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc193aXRoX3Byb21pc2UoY3VyX2lucHV0WzBdLCBsc3QuQ2F0ZWdvcmllcykudGhlbihmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdHl0X2luaXRfc3VwZXJfc2VsZWN0b3Jfc2VhcmNoX2FuZF91bmNoZWNrKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPU1haW5DYXRlZ29yeUlEXScpLCBsc3QuQ2F0ZWdvcmllcyk7XG5cblx0XHRcdFx0XHRZVF9TZWxlY3RBZnRlckxvYWRpbmcoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TWFpbkNhdGVnb3J5SURdJykpO1xuXG5cdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3ViLWNhdGVnb3J5LWNvbC1maWVsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN1Yi1jYXRlZ29yeS1jb2wtZmllbGQgc2VsZWN0JykuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcblxuXHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdCBpbnB1dFtuYW1lPU1haW5DYXRlZ29yeUlEXScpLmVhY2goZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHRcdGpRdWVyeSh0aGlzKS5jaGFuZ2UoZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHRcdFx0aWYgKGpRdWVyeSh0aGlzKS52YWwoKSAhPSAnJykge1xuXG5cdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3ViLWNhdGVnb3J5LWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN1Yi1jYXRlZ29yeS1jb2wtZmllbGQgc2VsZWN0JykucmVtb3ZlQXR0cignZGlzYWJsZWQnKTtcblxuXHRcdFx0XHRcdFx0XHRcdF9ZQkEubGFiZWxfb3B0aW9ucyh7IGxhYmVsOiAnU3ViQ2F0ZWdvcmllcycsIG1haW5JRDogalF1ZXJ5KHRoaXMpLnZhbCgpIH0pLnRoZW4oZnVuY3Rpb24obHN0KSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcblx0XHRcdFx0XHRcdFx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc193aXRoX3Byb21pc2UoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKVswXSwgbHN0LlN1YkNhdGVnb3JpZXMpLnRoZW4oZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0eXRfaW5pdF9zdXBlcl9zZWxlY3Rvcl9zZWFyY2hfYW5kX3VuY2hlY2soalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKSwgbHN0LlN1YkNhdGVnb3JpZXMpO1xuXG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJykpO1xuXG5cblx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHRcdFxuXHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRcdH0pO1xuXG5cdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFxuXG5cblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0XHRlbHNlIHtcblx0XHRcdFx0eXRfZmlsbF9vcHRpb25zKGpRdWVyeSgnc2VsZWN0W25hbWU9TWFpbkNhdGVnb3J5SURdJylbMF0sIGxzdC5TYWlsQ2F0ZWdvcmllcyk7XG5cdFx0XHR9XG5cblx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJ3NlbGVjdFtuYW1lPU1haW5DYXRlZ29yeUlEXScpKTtcblx0XHR9KTtcblxuXHR9XG5cdGVsc2Uge1xuXHRcdF9ZQkEubGFiZWxfb3B0aW9ucyh7IGxhYmVsOiAnQ2F0ZWdvcmllcycgfSkudGhlbihmdW5jdGlvbihsc3QpIHtcblx0XHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9TWFpbkNhdGVnb3J5SURdJykuZW1wdHkoKTtcblxuXHRcdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKS5wcmVwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+QWxsPC9vcHRpb24+Jyk7XG5cblx0XHRcdGlmIChqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKS5sZW5ndGgpIHtcblx0XHRcdFx0dmFyIGN1cl9pbnB1dD1qUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKTtcblxuXHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGN1cl9pbnB1dFswXSwgbHN0LkNhdGVnb3JpZXMpLnRoZW4oZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHR5dF9pbml0X3N1cGVyX3NlbGVjdG9yX3NlYXJjaF9hbmRfdW5jaGVjayhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKSwgbHN0LkNhdGVnb3JpZXMpO1xuXG5cdFx0XHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPU1haW5DYXRlZ29yeUlEXScpKTtcblxuXHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN1Yi1jYXRlZ29yeS1jb2wtZmllbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdWItY2F0ZWdvcnktY29sLWZpZWxkIHNlbGVjdCcpLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG5cblx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdXBlci1zZWxlY3QgaW5wdXRbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRqUXVlcnkodGhpcykuY2hhbmdlKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRcdGlmIChqUXVlcnkodGhpcykudmFsKCkgIT0gJycpIHtcblxuXHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN1Yi1jYXRlZ29yeS1jb2wtZmllbGQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdWItY2F0ZWdvcnktY29sLWZpZWxkIHNlbGVjdCcpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG5cblx0XHRcdFx0XHRcdFx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ1N1YkNhdGVnb3JpZXMnLCBtYWluSUQ6IGpRdWVyeSh0aGlzKS52YWwoKSB9KS50aGVuKGZ1bmN0aW9uKGxzdCkge1xuXHRcdFx0XHRcdFx0XHRcdFx0XG5cdFx0XHRcdFx0XHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJylbMF0sIGxzdC5TdWJDYXRlZ29yaWVzKS50aGVuKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHl0X2luaXRfc3VwZXJfc2VsZWN0b3Jfc2VhcmNoX2FuZF91bmNoZWNrKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJyksIGxzdC5TdWJDYXRlZ29yaWVzKTtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRZVF9TZWxlY3RBZnRlckxvYWRpbmcoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKSk7XG5cblxuXHRcdFx0XHRcdFx0XHRcdFx0fSk7XG5cblx0XHRcdFx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0XHR9KTtcblxuXHRcdFx0XHRcdH0pO1xuXG5cblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0XHRlbHNlIHtcblx0XHRcdFx0eXRfZmlsbF9vcHRpb25zKGpRdWVyeSgnc2VsZWN0W25hbWU9TWFpbkNhdGVnb3J5SURdJylbMF0sIGxzdC5DYXRlZ29yaWVzKTtcblx0XHRcdH1cblxuXHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGpRdWVyeSgnc2VsZWN0W25hbWU9TWFpbkNhdGVnb3J5SURdJykpO1xuXHRcdH0pO1xuXHR9XG59XG5cbmpRdWVyeShkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG5cdC8vIEZpbGxpbmcgU2FpbC9Nb3RvciBDYXRlZ29yaWVzXG5cdGpRdWVyeSgnc2VsZWN0W25hbWU9VmVzc2VsVHlwZV0sIHNlbGVjdFtuYW1lPXZlc3NlbHR5cGVdLCBpbnB1dFtuYW1lPVZlc3NlbFR5cGVdLCBpbnB1dFtuYW1lPXZlc3NlbHR5cGVdJykuZWFjaChmdW5jdGlvbigpIHtcdFx0XHRcdFxuXHRcdGlmIChcblx0XHRcdChqUXVlcnkodGhpcykuYXR0cigndHlwZScpID09ICdyYWRpbycgfHwgalF1ZXJ5KHRoaXMpLmF0dHIoJ3R5cGUnKSA9PSAnY2hlY2tib3gnKVxuXHRcdFx0JiZcblx0XHRcdCEgalF1ZXJ5KHRoaXMpLnByb3AoJ2NoZWNrZWQnKVxuXHRcdCkge1xuXHRcdFx0cmV0dXJuIGZhbHNlO1xuXHRcdH1cblxuXHRcdHl0X2NhdGVnb3J5X2Jhc2VkX29mZl9vZl90eXBlKGpRdWVyeSh0aGlzKSk7XG5cdFx0XG5cdH0pLmNoYW5nZShmdW5jdGlvbigpIHtcblxuXHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKS5lbXB0eSgpO1xuXHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKS5wcmVwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+LS0gUGljayBBIENhdGVnb3J5LCBGaXJzdCAtLTwvb3B0aW9uPicpO1xuXG5cdFx0eXRfY2F0ZWdvcnlfYmFzZWRfb2ZmX29mX3R5cGUoalF1ZXJ5KHRoaXMpKTtcblx0fSk7XG5cblx0Ly8gRmlsbGluZyBTdWItQ2F0ZWdvcnlcblx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0sIC55dC1zdXBlci1zZWxlY3RbbmFtZT1NYWluQ2F0ZWdvcnlJRF0nKS5lYWNoKGZ1bmN0aW9uKCkge1x0XHRcdFx0XG5cdFx0aWYgKGpRdWVyeSh0aGlzKS5kYXRhKCd2YWx1ZScpICE9ICcnKSB7XG5cdFx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ1N1YkNhdGVnb3JpZXMnLCBtYWluSUQ6IGpRdWVyeSh0aGlzKS5kYXRhKCd2YWx1ZScpIH0pLnRoZW4oZnVuY3Rpb24obHN0KSB7XG5cdFx0XHRcdGNvbnNvbGUubG9nKGxzdC5TdWJDYXRlZ29yaWVzKTtcblxuXHRcdFx0XHRqUXVlcnkoJ3NlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJykuZW1wdHkoKTtcblx0XHRcdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1TdWJDYXRlZ29yeUlEXScpLnByZXBlbmQoJzxvcHRpb24gdmFsdWU9XCJcIj4tLSBQaWNrIEEgQ2F0ZWdvcnksIEZpcnN0IC0tPC9vcHRpb24+Jyk7XG5cblx0XHRcdFx0aWYgKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJykubGVuZ3RoKSB7XG5cdFx0XHRcdFxuXHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN1Yi1jYXRlZ29yeS1jb2wtZmllbGQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdWItY2F0ZWdvcnktY29sLWZpZWxkIHNlbGVjdCcpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG5cblx0XHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJylbMF0sIGxzdC5TdWJDYXRlZ29yaWVzKS50aGVuKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRZVF9TZWxlY3RBZnRlckxvYWRpbmcoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKSk7XG5cblx0XHRcdFx0XHR9KTtcblx0XHRcdFx0fVxuXHRcdFx0XHRlbHNlIHtcblx0XHRcdFx0XHR5dF9maWxsX29wdGlvbnMoalF1ZXJ5KCdzZWxlY3RbbmFtZT1TdWJDYXRlZ29yeUlEXScpWzBdLCBsc3QuU3ViQ2F0ZWdvcmllcyk7XG5cdFx0XHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGpRdWVyeSgnc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKSk7XG5cdFx0XHRcdH1cblx0XHRcdFx0XG5cdFx0XHR9KTtcblx0XHR9XG5cdFx0ZWxzZSB7XG5cdFx0XHRqUXVlcnkoJ3NlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJykuZW1wdHkoKTtcblx0XHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKS5wcmVwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+LS0gUGljayBBIENhdGVnb3J5LCBGaXJzdCAtLTwvb3B0aW9uPicpO1xuXHRcdH1cblxuXG5cdH0pLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoKSB7XG5cblx0XHRpZiAoalF1ZXJ5KHRoaXMpLnZhbCgpICE9ICcnKSB7XG5cdFx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ1N1YkNhdGVnb3JpZXMnLCBtYWluSUQ6IGpRdWVyeSh0aGlzKS52YWwoKSB9KS50aGVuKGZ1bmN0aW9uKGxzdCkge1xuXHRcdFx0XHRqUXVlcnkoJ3NlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJykuZW1wdHkoKTtcblx0XHRcdFx0alF1ZXJ5KCdzZWxlY3RbbmFtZT1TdWJDYXRlZ29yeUlEXScpLnByZXBlbmQoJzxvcHRpb24gdmFsdWU9XCJcIj4tLSBBTEwgLS08L29wdGlvbj4nKTtcblxuXHRcdFx0XHR5dF9maWxsX29wdGlvbnMoalF1ZXJ5KCdzZWxlY3RbbmFtZT1TdWJDYXRlZ29yeUlEXScpWzBdLCBsc3QuU3ViQ2F0ZWdvcmllcyk7XG5cdFx0XHR9KTtcblx0XHR9XG5cdFx0ZWxzZSB7XG5cdFx0XHRqUXVlcnkoJ3NlbGVjdFtuYW1lPVN1YkNhdGVnb3J5SURdJykuZW1wdHkoKTtcblx0XHRcdGpRdWVyeSgnc2VsZWN0W25hbWU9U3ViQ2F0ZWdvcnlJRF0nKS5wcmVwZW5kKCc8b3B0aW9uIHZhbHVlPVwiXCI+LS0gUGljayBBIENhdGVnb3J5LCBGaXJzdCAtLTwvb3B0aW9uPicpO1xuXHRcdH1cblxuXHR9KTtcbn0pO1xuXG4iLCJmdW5jdGlvbiB5dF9zdXBlcl9zZWxlY3Rvcl9sb2NhdGlvbl9zZWFyY2goZWxlKSB7XG5cblxuXG5cblxufVxuXG5cbmpRdWVyeShkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XHRcblx0Y29uc29sZS5sb2coJ2hlbGxvIHdvcmxkJyk7XG5cblx0Ly8gRmlsbGluZyBDb3VudHJ5XG5cdGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uUmVnaW9uSURdW2RhdGEtdmFsdWVdJykuZWFjaChmdW5jdGlvbigpIHtcblxuXHRcdGlmIChqUXVlcnkodGhpcykuZGF0YSgndmFsdWUnKSAhPSAnJykge1xuXHRcdFx0X1lCQS5sYWJlbF9vcHRpb25zKHsgbGFiZWw6ICdHZW9MaXN0UmVnaW9uQ291bnRyaWVzJywgUmVnaW9uSWQ6IGpRdWVyeSh0aGlzKS5kYXRhKCd2YWx1ZScpIH0pLnRoZW4oZnVuY3Rpb24obHN0KSB7XG5cdFx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc193aXRoX3Byb21pc2UoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25Db3VudHJ5SURdJylbMF0sIGxzdC5HZW9MaXN0UmVnaW9uQ291bnRyaWVzKS50aGVuKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0eXRfaW5pdF9zdXBlcl9zZWxlY3Rvcl9zZWFyY2hfYW5kX3VuY2hlY2soalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25Db3VudHJ5SURdJyksIGxzdC5HZW9MaXN0UmVnaW9uQ291bnRyaWVzKTtcblxuXHRcdFx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNvdW50cnlJRF0nKSk7XG5cblx0XHRcdFx0XHRqUXVlcnkoJy55dC1jb3VudHJ5LWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHRcdFx0Ly8gRmlsbGluZyBTdGF0ZXNcblx0XHRcdFx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ0dlb0xpc3RTdGF0ZXMnLCBDb3VudHJ5SWQ6IGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uQ291bnRyeUlEXScpLmRhdGEoJ3ZhbHVlJykgfSkudGhlbihmdW5jdGlvbihsc3QpIHtcblxuXHRcdFx0XHRcdFx0eXRfZmlsbF9zdXBlcl9vcHRpb25zX3dpdGhfcHJvbWlzZShqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvblN0YXRlSURdJylbMF0sIGxzdC5HZW9MaXN0U3RhdGVzKS50aGVuKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRcdHl0X2luaXRfc3VwZXJfc2VsZWN0b3Jfc2VhcmNoX2FuZF91bmNoZWNrKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uU3RhdGVJRF0nKSwgbHN0Lkdlb0xpc3RTdGF0ZXMpO1xuXG5cdFx0XHRcdFx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvblN0YXRlSURdJykpO1xuXG5cdFx0XHRcdFx0XHRcdGlmIChqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvblN0YXRlSURdJykuZGF0YSgndmFsdWUnKSAhPSAnJykge1xuXHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdFxuXHRcdFx0XHRcdFx0XHQvLyBGaWxsaW5nIFN0YXRlc1xuXHRcdFx0XHRcdFx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ0dlb0xpc3RDaXRpZXMnLCBTdGF0ZUlkOiBqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvblN0YXRlSURdJykuZGF0YSgndmFsdWUnKSB9KS50aGVuKGZ1bmN0aW9uKGxzdCkge1xuXG5cdFx0XHRcdFx0XHRcdFx0eXRfZmlsbF9zdXBlcl9vcHRpb25zX3dpdGhfcHJvbWlzZShqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNpdHlJRF0nKVswXSwgbHN0Lkdlb0xpc3RDaXRpZXMpLnRoZW4oZnVuY3Rpb24oKSB7XG5cblx0XHRcdFx0XHRcdFx0XHRcdHl0X2luaXRfc3VwZXJfc2VsZWN0b3Jfc2VhcmNoX2FuZF91bmNoZWNrKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uQ2l0eUlEXScpLCBsc3QuR2VvTGlzdENpdGllcyk7XG5cblx0XHRcdFx0XHRcdFx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNpdHlJRF0nKSk7XG5cblx0XHRcdFx0XHRcdFx0XHRcdGlmIChqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNpdHlJRF0nKS5kYXRhKCd2YWx1ZScpICE9ICcnKSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LWNpdHktY29sLWZpZWxkJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHRcdFx0fSk7XG5cblx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdH0pO1xuXHRcdFx0XHR9KTtcblxuXG5cdFx0XHR9KTtcblx0XHR9XG5cblxuXHR9KTtcblxuXHRpZiAoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25SZWdpb25JRF0nKS5sZW5ndGgpIHtcblx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ0dlb0xpc3RSZWdpb25zV2l0aEFjdGl2ZUNvdW50cmllcycgfSkudGhlbihmdW5jdGlvbihsc3QpIHtcblxuXHRcdFx0aWYgKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uUmVnaW9uSURdJykubGVuZ3RoKSB7XG5cdFx0XHRcdHZhciBjdXJfaW5wdXQ9alF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25SZWdpb25JRF0nKTtcblxuXHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGN1cl9pbnB1dFswXSwgbHN0Lkdlb0xpc3RSZWdpb25zV2l0aEFjdGl2ZUNvdW50cmllcykudGhlbihmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdHl0X2luaXRfc3VwZXJfc2VsZWN0b3Jfc2VhcmNoX2FuZF91bmNoZWNrKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uUmVnaW9uSURdJyksIGxzdC5HZW9MaXN0UmVnaW9uc1dpdGhBY3RpdmVDb3VudHJpZXMpO1xuXG5cdFx0XHRcdFx0WVRfU2VsZWN0QWZ0ZXJMb2FkaW5nKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uUmVnaW9uSURdJykpO1xuXG5cdFx0XHRcdFx0aWYgKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uUmVnaW9uSURdJykuZGF0YSgndmFsdWUnKSA9PSAnJykge1xuXHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtY291bnRyeS1jb2wtZmllbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtY2l0eS1jb2wtZmllbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHR9XG5cblxuXHRcdFx0XHRcdGlmIChqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvblJlZ2lvbklEXScpLmRhdGEoJ3ZhbHVlJykgIT0gJycpIHtcblx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LWNvdW50cnktY29sLWZpZWxkJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFxuXG5cdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0IGlucHV0W25hbWU9TG9jYXRpb25SZWdpb25JRF0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRqUXVlcnkodGhpcykuY2hhbmdlKGZ1bmN0aW9uKCkge1xuXG5cdFx0XHRcdFx0XHRcdGlmIChqUXVlcnkodGhpcykudmFsKCkgIT0gJycpIHtcblx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1jb3VudHJ5LWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHRcdFx0XHRcdFx0dmFyIHJlZ2lvbklEID0galF1ZXJ5KHRoaXMpLnZhbCgpO1xuXG5cdFx0XHRcdFx0XHRcdFx0aWYgKHJlZ2lvbklEID09IDEgfHwgcmVnaW9uSUQgPT0gMTIpIHtcblx0XHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkIHNlbGVjdCcpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0fVxuXHRcdFx0XHRcdFx0XHRcdGVsc2Uge1xuXHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQgc2VsZWN0JykuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRcdFx0XHR9XG5cblx0XHRcdFx0XHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uU3RhdGVJRF0nKVswXSwgW10pO1xuXHRcdFx0XHRcdFx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc193aXRoX3Byb21pc2UoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25DaXR5SURdJylbMF0sIFtdKTtcblxuXHRcdFx0XHRcdFx0XHRcdF9ZQkEubGFiZWxfb3B0aW9ucyh7IGxhYmVsOiAnR2VvTGlzdFJlZ2lvbkNvdW50cmllcycsIFJlZ2lvbklkOiBqUXVlcnkodGhpcykudmFsKCkgfSkudGhlbihmdW5jdGlvbihsc3QpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0Y29uc29sZS5sb2cobHN0KTtcblx0XHRcdFx0XHRcdFx0XHRcdFxuXHRcdFx0XHRcdFx0XHRcdFx0Y29uc29sZS5sb2coalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25Db3VudHJ5SURdJylbMF0pO1xuXG5cdFx0XHRcdFx0XHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uQ291bnRyeUlEXScpWzBdLCBsc3QuR2VvTGlzdFJlZ2lvbkNvdW50cmllcykudGhlbihmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHR5dF9pbml0X3N1cGVyX3NlbGVjdG9yX3NlYXJjaF9hbmRfdW5jaGVjayhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNvdW50cnlJRF0nKSwgbHN0Lkdlb0xpc3RSZWdpb25Db3VudHJpZXMpO1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNvdW50cnlJRF0nKSk7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdXBlci1zZWxlY3QgaW5wdXRbbmFtZT1Mb2NhdGlvbkNvdW50cnlJRF0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KHRoaXMpLmNoYW5nZShmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0dmFyIENvdW50cnlJRCA9IGpRdWVyeSh0aGlzKS52YWwoKTtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1jaXR5LWNvbC1maWVsZCcpLnJlbW92ZUNsYXNzKCdkaXNhYmxlZCcpO1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL3NlZXQgY2xhc3Nlc1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0aWYgKFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRDb3VudHJ5SUQgPT0gMjMzIHx8IENvdW50cnlJRCA9PSAzOVxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR8fFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRDb3VudHJ5SUQgPT0gMTQgfHwgQ291bnRyeUlEID09IDE1OFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KSB7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQnKS5yZW1vdmVDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkIHNlbGVjdCcpLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGVsc2Uge1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LXN0YXRlLWNvbC1maWVsZCcpLmFkZENsYXNzKCdkaXNhYmxlZCcpO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdGF0ZS1jb2wtZmllbGQgc2VsZWN0JykuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uU3RhdGVJRF0nKVswXSwgW10pO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0eXRfZmlsbF9zdXBlcl9vcHRpb25zX3dpdGhfcHJvbWlzZShqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNpdHlJRF0nKVswXSwgW10pO1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBzZXQgb3B0aW9uc1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRpZiAoXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdENvdW50cnlJRCA9PSAyMzMgfHwgQ291bnRyeUlEID09IDM5XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHx8XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdENvdW50cnlJRCA9PSAxNCB8fCBDb3VudHJ5SUQgPT0gMTU4XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpIHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0X1lCQS5sYWJlbF9vcHRpb25zKHsgbGFiZWw6ICdHZW9MaXN0U3RhdGVzJywgQ291bnRyeUlkOiBqUXVlcnkodGhpcykudmFsKCkgfSkudGhlbihmdW5jdGlvbihsc3QpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc193aXRoX3Byb21pc2UoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25TdGF0ZUlEXScpWzBdLCBsc3QuR2VvTGlzdFN0YXRlcykudGhlbihmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0eXRfaW5pdF9zdXBlcl9zZWxlY3Rvcl9zZWFyY2hfYW5kX3VuY2hlY2soalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25TdGF0ZUlEXScpLCBsc3QuR2VvTGlzdFN0YXRlcyk7XG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvblN0YXRlSURdJykpO1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoJy55dC1zdXBlci1zZWxlY3QgaW5wdXRbbmFtZT1Mb2NhdGlvblN0YXRlSURdJykuZWFjaChmdW5jdGlvbigpIHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KHRoaXMpLmNoYW5nZShmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdF9ZQkEubGFiZWxfb3B0aW9ucyh7IGxhYmVsOiAnR2VvTGlzdENpdGllcycsIFN0YXRlSWQ6IGpRdWVyeSh0aGlzKS52YWwoKSB9KS50aGVuKGZ1bmN0aW9uKGxzdCkge1xuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR5dF9maWxsX3N1cGVyX29wdGlvbnNfd2l0aF9wcm9taXNlKGpRdWVyeSgnLnl0LXN1cGVyLXNlbGVjdFtuYW1lPUxvY2F0aW9uQ2l0eUlEXScpWzBdLCBsc3QuR2VvTGlzdENpdGllcykudGhlbihmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR5dF9pbml0X3N1cGVyX3NlbGVjdG9yX3NlYXJjaF9hbmRfdW5jaGVjayhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNpdHlJRF0nKSwgbHN0Lkdlb0xpc3RDaXRpZXMpO1xuXHRcdFx0XHRcdFxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRZVF9TZWxlY3RBZnRlckxvYWRpbmcoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25DaXR5SURdJykpO1xuXG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9KTtcblxuXG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRlbHNlIHtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gY291bnRyeSAtPiBjaXRpZXNcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRfWUJBLmxhYmVsX29wdGlvbnMoeyBsYWJlbDogJ0dlb0xpc3RDb3VudHJ5Q2l0aWVzJywgQ291bnRyeUlkOiBqUXVlcnkodGhpcykudmFsKCkgfSkudGhlbihmdW5jdGlvbihsc3QpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHl0X2ZpbGxfc3VwZXJfb3B0aW9uc193aXRoX3Byb21pc2UoalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25DaXR5SURdJylbMF0sIGxzdC5HZW9MaXN0Q291bnRyeUNpdGllcykudGhlbihmdW5jdGlvbigpIHtcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0eXRfaW5pdF9zdXBlcl9zZWxlY3Rvcl9zZWFyY2hfYW5kX3VuY2hlY2soalF1ZXJ5KCcueXQtc3VwZXItc2VsZWN0W25hbWU9TG9jYXRpb25DaXR5SURdJyksIGxzdC5HZW9MaXN0Q291bnRyeUNpdGllcyk7XG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFlUX1NlbGVjdEFmdGVyTG9hZGluZyhqUXVlcnkoJy55dC1zdXBlci1zZWxlY3RbbmFtZT1Mb2NhdGlvbkNpdHlJRF0nKSk7XG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcblxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9KTtcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cblxuXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSk7XG5cblx0XHRcdFx0XHRcdFx0XHRcdFx0fSk7XG5cdFx0XHRcdFx0XHRcdFx0XHR9KTtcblxuXHRcdFx0XHRcdFx0XHRcdH0pO1xuXHRcdFx0XHRcdFx0XHR9XG5cdFx0XHRcdFx0XHRcdGVsc2Uge1xuXHRcdFx0XHRcdFx0XHRcdGpRdWVyeSgnLnl0LWNvdW50cnktY29sLWZpZWxkJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtc3RhdGUtY29sLWZpZWxkJykuYWRkQ2xhc3MoJ2Rpc2FibGVkJyk7XG5cdFx0XHRcdFx0XHRcdFx0alF1ZXJ5KCcueXQtY2l0eS1jb2wtZmllbGQnKS5hZGRDbGFzcygnZGlzYWJsZWQnKTtcblx0XHRcdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0XHR9KTtcblxuXHRcdFx0XHRcdH0pO1xuXG5cblx0XHRcdFx0fSk7XG5cdFx0XHR9XG5cdFx0fSk7XG5cblx0fVxufSk7IiwiZnVuY3Rpb24geXRfc3luY19pbnB1dChlbGVfaW5wdXQsIHZhbHVlcykge1xuXG5cdGlmICghZWxlX2lucHV0LmF0dHIoJ2RvbnQtdG91Y2gnKSkge1xuXG5cdFx0dmFyIG5hbWUgPSBlbGVfaW5wdXQuYXR0cignbmFtZScpO1xuXHRcdHZhciBfdmFsID0gbnVsbDtcblxuXHRcdC8vIG5hbWUgPSBuYW1lLnJlcGxhY2UoJ18nLCAnJyk7XG5cdFx0Ly9jb25zb2xlLmxvZyhuYW1lKTtcblxuXHRcdGlmICh0eXBlb2YgdmFsdWVzW25hbWVdICE9ICd1bmRlZmluZWQnICYmIHZhbHVlc1tuYW1lXSAhPSBudWxsKSB7XG5cdFx0XHRfdmFsID0gdmFsdWVzW25hbWVdO1xuXHRcdH1cblx0XHRlbHNlIGlmICh0eXBlb2YgdmFsdWVzW25hbWUudG9Mb3dlckNhc2UoKV0gIT0gJ3VuZGVmaW5lZCcgJiYgdmFsdWVzW25hbWUudG9Mb3dlckNhc2UoKV0gIT0gbnVsbCkge1xuXHRcdFx0X3ZhbCA9IHZhbHVlc1tuYW1lLnRvTG93ZXJDYXNlKCldO1xuXHRcdH1cblx0XHRlbHNlIHtcblx0XHRcdF92YWwgPSBudWxsO1xuXHRcdH1cblxuXHRcdC8vY29uc29sZS5sb2coX3ZhbCk7XG5cblx0XHRpZiAodHlwZW9mIF92YWwgIT0gJ3VuZGVmaW5lZCcgJiYgX3ZhbCAhPSBudWxsKSB7XG5cblx0XHRcdGlmIChlbGVfaW5wdXQuYXR0cigndHlwZScpID09ICdjaGVja2JveCcgfHwgZWxlX2lucHV0LmF0dHIoJ3R5cGUnKSA9PSAncmFkaW8nKSB7XG5cblx0XHRcdFx0aWYgKGVsZV9pbnB1dC5hdHRyKCd2YWx1ZScpID09IF92YWwpIHtcblxuXHRcdFx0XHRcdGVsZV9pbnB1dC5hdHRyKCdjaGVja2VkJywgJ2NoZWNrZWQnKTtcblxuXHRcdFx0XHR9XG5cblx0XHRcdH1cblx0XHRcdGVsc2Uge1xuXHRcdFx0XHRlbGVfaW5wdXQudmFsKF92YWwpO1xuXHRcdFx0fVxuXG5cdFx0fVxuXG5cdH1cblxufVxuXG5mdW5jdGlvbiB5dF9pbnB1dF9udW1iZXJfZm9ybWF0dGVyKCkge1xuXHR2YXIgdmFsID0galF1ZXJ5KHRoaXMpLnZhbCgpO1xuXG5cdHZhbCA9IGRlY29kZVVSSUNvbXBvbmVudCh2YWwpO1xuXG5cdGNvbnNvbGUubG9nKHZhbCk7XG5cblx0dmFyIG51bWJlcl9zdHIgPSB2YWwubWF0Y2goLyhcXGQqKS9nKTtcblxuXHRpZiAoQXJyYXkuaXNBcnJheShudW1iZXJfc3RyKSkge1xuXHRcdG51bWJlcl9zdHIgPSBudW1iZXJfc3RyLmpvaW4oJycpO1xuXHR9XG5cblx0dmFyIGZvcm1hdHRlZCA9IG5ldyBJbnRsLk51bWJlckZvcm1hdCgnZW4nKS5mb3JtYXQobnVtYmVyX3N0cik7XG5cblx0aWYgKGZvcm1hdHRlZCAhPSBOYU4gJiYgZm9ybWF0dGVkICE9ICdOYU4nICYmIGZvcm1hdHRlZCAhPT0gXCIwXCIgJiYgZm9ybWF0dGVkICE9PSAwKSB7XG5cdFx0alF1ZXJ5KHRoaXMpLnZhbChmb3JtYXR0ZWQpO1xuXHR9XG59XG5cbmZ1bmN0aW9uIHl0X2VsZV9pbnB1dF9udW1iZXJfZm9ybWF0dGVyKGpfZWxlKSB7XG5cdHZhciB2YWwgPSBqX2VsZS52YWwoKTtcblxuXHR2YWwgPSBkZWNvZGVVUklDb21wb25lbnQodmFsKTtcblxuXHRjb25zb2xlLmxvZyh2YWwpO1xuXG5cdHZhciBudW1iZXJfc3RyID0gdmFsLm1hdGNoKC8oXFxkKikvZyk7XG5cblx0aWYgKEFycmF5LmlzQXJyYXkobnVtYmVyX3N0cikpIHtcblx0XHRudW1iZXJfc3RyID0gbnVtYmVyX3N0ci5qb2luKCcnKTtcblx0fVxuXG5cdHZhciBmb3JtYXR0ZWQgPSBuZXcgSW50bC5OdW1iZXJGb3JtYXQoJ2VuJykuZm9ybWF0KG51bWJlcl9zdHIpO1xuXG5cdGlmIChmb3JtYXR0ZWQgIT0gTmFOICYmIGZvcm1hdHRlZCAhPSAnTmFOJyAmJiBmb3JtYXR0ZWQgIT09IFwiMFwiICYmIGZvcm1hdHRlZCAhPT0gMCkge1xuXHRcdGpfZWxlLnZhbChmb3JtYXR0ZWQpO1xuXHR9XG59XG5cbmZ1bmN0aW9uIHJ1bl90aHJvdWdoX2FuZF9zeW5jX2Zvcm0oanF1ZXJ5X2VsZSwgdmFsdWVzKSB7XG5cblx0dmFyIGZvcm1fbmFtZSA9IGpxdWVyeV9lbGUuYXR0cignaWQnKTtcblxuXHRqUXVlcnkoJ2lucHV0W25hbWVdLCBzZWxlY3RbbmFtZV0sIHRleHRhcmVhW25hbWVdJywganF1ZXJ5X2VsZSkuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHR5dF9zeW5jX2lucHV0KGpRdWVyeSh0aGlzKSwgdmFsdWVzKTtcblxuXHR9KTtcblxuXHRqUXVlcnkoJ2lucHV0W25hbWVdW2Zvcm09XCInICsgZm9ybV9uYW1lICsgJ1wiXSwgc2VsZWN0W25hbWVdW2Zvcm09XCInICsgZm9ybV9uYW1lICsgJ1wiXSwgdGV4dGFyZWFbbmFtZV1bZm9ybT1cIicgKyBmb3JtX25hbWUgKyAnXCJdJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHR5dF9zeW5jX2lucHV0KGpRdWVyeSh0aGlzKSwgdmFsdWVzKTtcblxuXHR9KTtcblxuXG5cdGpRdWVyeSgnaW5wdXRbbmFtZT1cInByaWNlcmFuZ2VfZnJvbVwiXSwgaW5wdXRbbmFtZT1cInByaWNlcmFuZ2VfdG9cIl0sIGlucHV0W25hbWU9XCJsb2FfZnJvbVwiXSwgaW5wdXRbbmFtZT1cImxvYV90b1wiXScsIGpxdWVyeV9lbGUpLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdHZhciAkaW5wdXQgPSBqUXVlcnkodGhpcyk7XG5cdFx0Ly8gYXR0YWNoIGV2ZW50c1xuXHRcdCRpbnB1dC5vbignaW5wdXQnLCB5dF9pbnB1dF9udW1iZXJfZm9ybWF0dGVyKTtcblx0XHQkaW5wdXQub24oJ2NoYW5nZScsIHl0X2lucHV0X251bWJlcl9mb3JtYXR0ZXIpO1xuXG5cdFx0Ly8gZGVsYXkgaW5pdGlhbCBmb3JtYXQgc28gdmFsdWUgaXNu4oCZdCBsb3N0XG5cdFx0c2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG5cdFx0XHR5dF9lbGVfaW5wdXRfbnVtYmVyX2Zvcm1hdHRlcigkaW5wdXQpO1xuXHRcdH0sIDUwKTtcblx0fSk7XG59XG5cbmZ1bmN0aW9uIGlzRWxlbWVudEluVmlld3BvcnQoZWwpIHtcblxuXHQvLyBTcGVjaWFsIGJvbnVzIGZvciB0aG9zZSB1c2luZyBqUXVlcnlcblx0aWYgKHR5cGVvZiBqUXVlcnkgPT09IFwiZnVuY3Rpb25cIiAmJiBlbCBpbnN0YW5jZW9mIGpRdWVyeSkge1xuXHRcdGVsID0gZWxbMF07XG5cdH1cblxuXHR2YXIgcmVjdCA9IGVsLmdldEJvdW5kaW5nQ2xpZW50UmVjdCgpO1xuXG5cdHJldHVybiAoXG5cdFx0cmVjdC50b3AgPj0gMCAmJlxuXHRcdHJlY3QubGVmdCA+PSAwICYmXG5cdFx0cmVjdC5ib3R0b20gPD0gKHdpbmRvdy5pbm5lckhlaWdodCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xpZW50SGVpZ2h0KSAmJiAvKiBvciAkKHdpbmRvdykuaGVpZ2h0KCkgKi9cblx0XHRyZWN0LnJpZ2h0IDw9ICh3aW5kb3cuaW5uZXJXaWR0aCB8fCBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQuY2xpZW50V2lkdGgpIC8qIG9yICQod2luZG93KS53aWR0aCgpICovXG5cdCk7XG59XG5cbi8vIFJlc2V0IFNlYXJjaCBGb3JtXG5mdW5jdGlvbiByZXNldFNlYXJjaEZvcm0oZSkge1xuXHRlLnByZXZlbnREZWZhdWx0KCk7XG5cblx0Ly9kb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImRlc2t0b3AtcXVpY2stc2VhcmNoXCIpLnJlc2V0KCk7XG5cdC8vZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJkZXNrdG9wLWV4cGFuZGVkLXNlYXJjaFwiKS5yZXNldCgpO1xuXG5cdGpRdWVyeSgnZm9ybVtkYXRhLXNob3J0Y29kZS1hdHRyaWJ1dGVzXScpLmVhY2goZnVuY3Rpb24gKCkge1xuXG5cdFx0alF1ZXJ5KHRoaXMpWzBdLnJlc2V0KCk7XG5cblx0XHR2YXIgdmFsdWVzID0galF1ZXJ5KHRoaXMpLmRhdGEoJ3Nob3J0Y29kZS1hdHRyaWJ1dGVzJyk7XG5cblx0XHRpZiAodHlwZW9mIHZhbHVlc1snbG9hZC1tb3JlLWJ1dHRvbiddICE9ICd1bmRlZmluZWQnICYmIHZhbHVlc1snbG9hZC1tb3JlLWJ1dHRvbiddID09IDEgfHwgdmFsdWVzWydsb2FkLW1vcmUtYnV0dG9uJ10gPT0gXCIxXCIpIHtcblx0XHRcdHZhbHVlcy5wYWdlX3NpemUgPSAxMjtcblx0XHRcdHZhbHVlcy5wYWdlX2luZGV4ID0gMTtcblx0XHR9XG5cblx0XHRydW5fdGhyb3VnaF9hbmRfc3luY19mb3JtKGpRdWVyeSh0aGlzKSwgdmFsdWVzKTtcblx0fSk7XG5cbn1cblxuZnVuY3Rpb24geXRfanNfcmVzZXRTZWFyY2hGb3JtQW5kUmVzdWx0KGUpIHtcblx0ZS5wcmV2ZW50RGVmYXVsdCgpO1xuXG5cdC8vZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJkZXNrdG9wLXF1aWNrLXNlYXJjaFwiKS5yZXNldCgpO1xuXHQvL2RvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwiZGVza3RvcC1leHBhbmRlZC1zZWFyY2hcIikucmVzZXQoKTtcblxuXHRqUXVlcnkoJ2Zvcm1bZGF0YS1zaG9ydGNvZGUtYXR0cmlidXRlc10nKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdGpRdWVyeSh0aGlzKVswXS5yZXNldCgpO1xuXG5cdFx0dmFyIHZhbHVlcyA9IGpRdWVyeSh0aGlzKS5kYXRhKCdzaG9ydGNvZGUtYXR0cmlidXRlcycpO1xuXG5cdFx0aWYgKHR5cGVvZiB2YWx1ZXNbJ2xvYWQtbW9yZS1idXR0b24nXSAhPSAndW5kZWZpbmVkJyAmJiAodmFsdWVzWydsb2FkLW1vcmUtYnV0dG9uJ10gPT0gMSB8fCB2YWx1ZXNbJ2xvYWQtbW9yZS1idXR0b24nXSA9PSBcIjFcIikpIHtcblx0XHRcdHZhbHVlcy5wYWdlX3NpemUgPSAxMjtcblx0XHRcdHZhbHVlcy5wYWdlX2luZGV4ID0gMTtcblx0XHR9XG5cblx0XHRjb25zb2xlLmxvZyh2YWx1ZXMpO1xuXG5cdFx0cnVuX3Rocm91Z2hfYW5kX3N5bmNfZm9ybShqUXVlcnkodGhpcyksIHZhbHVlcyk7XG5cblx0XHRnZXRfdmVzc2Vsc19hbmRfcmVuZGVyKHZhbHVlcyk7XG5cblx0fSk7XG5cbn1cblxuLy8gQ29udGFjdCBNb2RhbFxuZnVuY3Rpb24gb3BlbkNvbnRhY3RNb2RhbChNTFNJRCwgQ29tcGFueUlELCBDb21wYW55TmFtZSwgQnJva2VyTmFtZSkge1xuXG5cdGpRdWVyeSgnI3lhdGNvLWNvbnRhY3QtbW9kYWwgaW5wdXRbbmFtZT1WZXNzZWxJRF0nKS52YWwoTUxTSUQpO1xuXHRqUXVlcnkoJyN5YXRjby1jb250YWN0LW1vZGFsIGlucHV0W25hbWU9Q29tcGFueUlEXScpLnZhbChDb21wYW55SUQpO1xuXG5cdGpRdWVyeSgnI3lhdGNvLWNvbnRhY3QtbW9kYWwgLmJyb2tlci1uYW1lJykudGV4dChCcm9rZXJOYW1lKTtcblx0alF1ZXJ5KCcjeWF0Y28tY29udGFjdC1tb2RhbCAuYnJva2VyYWdlJykudGV4dChDb21wYW55TmFtZSk7XG5cblx0alF1ZXJ5KFwiI3lhdGNvLWNvbnRhY3QtbW9kYWxcIikueWF0Y29fbW9kYWwoe1xuXHRcdGNsb3NlVGV4dDogJ1gnXG5cdH0pO1xuXG5cdHl0X2dhX2V2ZW50KCdNb2RhbCcsICdPcGVuJywgJ01vZGFsIE9wZW5lZDogVmVzc2VsIExlYWQnLCAnJyk7XG5cblx0Ly8gUmVzdHRpbmcgRm9ybSBcblx0alF1ZXJ5KCcjeWF0Y28tY29udGFjdC1tb2RhbCB0ZXh0YXJlYVtuYW1lPVwiTWVzc2FnZVwiXScpLnZhbCgnJyk7XG5cblx0alF1ZXJ5KCcjeWF0Y28tY29udGFjdC1tb2RhbCAuaGlkZS1hZnRlci1zdWJtaXQnKS5zaG93KCk7XG5cblx0alF1ZXJ5KCcjeWF0Y28tY29udGFjdC1tb2RhbCAuZm9ybS1zdWNjZXNzLW1lc3NhZ2UnKS5oaWRlKCk7XG5cbn1cblxuZnVuY3Rpb24gb3BlblZkQ29udGFjdE1vZGFsKE1MU0lELCBDb21wYW55SUQsIENvbXBhbnlOYW1lLCBCcm9rZXJOYW1lKSB7XG5cblx0alF1ZXJ5KCcjeWF0Y28tdmQtY29udGFjdC1tb2RhbCBpbnB1dFtuYW1lPVZlc3NlbElEXScpLnZhbChNTFNJRCk7XG5cdGpRdWVyeSgnI3lhdGNvLXZkLWNvbnRhY3QtbW9kYWwgaW5wdXRbbmFtZT1Db21wYW55SURdJykudmFsKENvbXBhbnlJRCk7XG5cblx0alF1ZXJ5KFwiI3lhdGNvLXZkLWNvbnRhY3QtbW9kYWxcIikueWF0Y29fbW9kYWwoe1xuXHRcdGNsb3NlVGV4dDogJ1gnXG5cdH0pO1xuXG5cdHl0X2dhX2V2ZW50KCdNb2RhbCcsICdPcGVuJywgJ01vZGFsIE9wZW5lZDogVmVzc2VsIERpcmVjdG9yeSBMZWFkJywgJycpO1xuXG5cdC8vIFJlc3R0aW5nIEZvcm0gXG5cdGpRdWVyeSgnI3lhdGNvLXZkLWNvbnRhY3QtbW9kYWwgdGV4dGFyZWFbbmFtZT1cIk1lc3NhZ2VcIl0nKS52YWwoJycpO1xuXG5cdGpRdWVyeSgnI3lhdGNvLXZkLWNvbnRhY3QtbW9kYWwgLmhpZGUtYWZ0ZXItc3VibWl0Jykuc2hvdygpO1xuXG5cdGpRdWVyeSgnI3lhdGNvLXZkLWNvbnRhY3QtbW9kYWwgLmZvcm0tc3VjY2Vzcy1tZXNzYWdlJykuaGlkZSgpO1xuXG59XG5cbmZ1bmN0aW9uIG9wZW5Ccm9rZXJDb250YWN0TW9kYWwoQnJva2VySUQsIENvbXBhbnlOYW1lLCBCcm9rZXJOYW1lKSB7XG5cblx0alF1ZXJ5KCcjc2luZ2xlLWNvbXBhbnktbW9kYWwtZW1haWwtYnJva2VyIGlucHV0W25hbWU9QnJva2VySURdJykudmFsKEJyb2tlcklEKTtcblxuXHRqUXVlcnkoJyNzaW5nbGUtY29tcGFueS1tb2RhbC1lbWFpbC1icm9rZXIgLmJyb2tlci1uYW1lJykudGV4dChCcm9rZXJOYW1lKTtcblx0alF1ZXJ5KCcjc2luZ2xlLWNvbXBhbnktbW9kYWwtZW1haWwtYnJva2VyIC5icm9rZXJhZ2UnKS50ZXh0KENvbXBhbnlOYW1lKTtcblxuXHRqUXVlcnkoXCIjc2luZ2xlLWNvbXBhbnktbW9kYWwtZW1haWwtYnJva2VyXCIpLnlhdGNvX21vZGFsKHtcblx0XHRjbG9zZVRleHQ6ICdYJ1xuXHR9KTtcblxuXHR5dF9nYV9ldmVudCgnTW9kYWwnLCAnT3BlbicsICdNb2RhbCBPcGVuZWQ6IEJyb2tlciBMZWFkJywgJycpO1xuXG5cdC8vIFJlc3R0aW5nIEZvcm0gXG5cdGpRdWVyeSgnI3NpbmdsZS1jb21wYW55LW1vZGFsLWVtYWlsLWJyb2tlciB0ZXh0YXJlYVtuYW1lPVwiTWVzc2FnZVwiXScpLnZhbCgnJyk7XG5cblx0alF1ZXJ5KCcjc2luZ2xlLWNvbXBhbnktbW9kYWwtZW1haWwtYnJva2VyIC5oaWRlLWFmdGVyLXN1Ym1pdCcpLnNob3coKTtcblxuXHRqUXVlcnkoJyNzaW5nbGUtY29tcGFueS1tb2RhbC1lbWFpbC1icm9rZXIgLmZvcm0tc3VjY2Vzcy1tZXNzYWdlJykuaGlkZSgpO1xuXG59XG5cbi8vIG9wZW5DaGFydGVyTGVhZE1vZGFsXG5cbmZ1bmN0aW9uIG9wZW5DaGFydGVyTGVhZE1vZGFsKE1MU0lELCBDb21wYW55SUQpIHtcblx0alF1ZXJ5KCcjeWF0Y28tY2hhcnRlci1sZWFkLW1vZGFsIGlucHV0W25hbWU9VmVzc2VsSURdJykudmFsKE1MU0lEKTtcblx0alF1ZXJ5KCcjeWF0Y28tY2hhcnRlci1sZWFkLW1vZGFsIGlucHV0W25hbWU9Q29tcGFueUlEXScpLnZhbChDb21wYW55SUQpO1xuXG5cdC8vIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJyN5YXRjby1jb250YWN0LW1vZGFsIC5icm9rZXJhZ2UnKS5pbm5lckhUTUw9Q29tcGFueU5hbWU7XG5cblx0alF1ZXJ5KFwiI3lhdGNvLWNoYXJ0ZXItbGVhZC1tb2RhbFwiKS55YXRjb19tb2RhbCh7XG5cdFx0Y2xvc2VUZXh0OiAnWCdcblx0fSk7XG5cblx0aWYgKHR5cGVvZiBnYSAhPSAndW5kZWZpbmVkJykge1xuXHRcdGdhKCdzZW5kJywgJ2V2ZW50JywgJ01vZGFsJywgJ09wZW4nLCAnQ2hhcnRlciBMZWFkIE1vZGFsIEZyb20gUmVzdWx0cyBQYWdlJyk7XG5cdFx0Z2EoJ3NlbmQnLCAnZXZlbnQnLCBNTFNJRCwgJ0xlYWQnLCAnT3BlbicpO1xuXHR9XG5cblx0Ly8gUmVzdHRpbmcgRm9ybSBcblx0alF1ZXJ5KCcjeWF0Y28tY2hhcnRlci1sZWFkLW1vZGFsIHRleHRhcmVhW25hbWU9XCJNZXNzYWdlXCJdJykudmFsKCcnKTtcblxuXHRqUXVlcnkoJyN5YXRjby1jaGFydGVyLWxlYWQtbW9kYWwgLmhpZGUtYWZ0ZXItc3VibWl0Jykuc2hvdygpO1xuXG5cdGpRdWVyeSgnI3lhdGNvLWNoYXJ0ZXItbGVhZC1tb2RhbCAuZm9ybS1zdWNjZXNzLW1lc3NhZ2UnKS5oaWRlKCk7XG59XG5cbmpRdWVyeShkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24gKCkge1xuXHR2YXIgc2VhcmNoUGFyYW1zID0gbmV3IFVSTFNlYXJjaFBhcmFtcyh3aW5kb3cubG9jYXRpb24uc2VhcmNoKTtcblxuXHR2YXIgc2hvcnRjb2RlX2Zvcm1fYXR0cl9kYXRhID0galF1ZXJ5KCcuYmlnLWZvcm0sIC55YXRjby1zaG9ydGNvZGUtc2VhcmNoLWZvcm0gZm9ybScpLmRhdGEoJ3Nob3J0Y29kZS1hdHRyaWJ1dGVzJyk7XG5cblx0aWYgKGpRdWVyeSgnLnlhdGNvLXNob3J0Y29kZS1zZWFyY2gtZm9ybSBmb3JtJykubGVuZ3RoID09IDAgJiYgalF1ZXJ5KCcueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMnKS5sZW5ndGggPiAwKSB7XG5cdFx0dmFyIHNob3J0Y29kZV9hdHRyX2RhdGEgPSBqUXVlcnkoJy55YXRjby1zaG9ydGNvZGUteWFjaHQtcmVzdWx0cycpLmRhdGEoJ3Nob3J0Y29kZS1hdHRyaWJ1dGVzJyk7XG5cblx0XHRzaG9ydGNvZGVfYXR0cl9kYXRhLm92ZXJyaWRlX3dpdGhfYXR0cnMgPSAnMSc7XG5cblx0XHRqUXVlcnkoJ2JvZHknKS5hcHBlbmQoYFxuXHRcdFx0PGRpdiBjbGFzcz1cInlhdGNvLXNob3J0Y29kZS15YWNodHMtc2VhcmNoLWZvcm1cIj5cblx0XHRcdFx0PGZvcm0gaWQ9XCJkZXNrdG9wLWV4cGFuZGVkLXNlYXJjaFwiIGRhdGEtc2hvcnRjb2RlLWF0dHJpYnV0ZXM+XG5cdFx0XHRcdFx0PGlucHV0IHR5cGU9XCJoaWRkZW5cIiBuYW1lPVwiZnJvbUZvcm1cIiB2YWx1ZT1cIjFcIj5cblx0XHRcdFx0PC9mb3JtPlxuXHRcdFx0PC9kaXY+XG5cdFx0YCk7XG5cblxuXHRcdGpRdWVyeSgnI2Rlc2t0b3AtZXhwYW5kZWQtc2VhcmNoJykuZGF0YSgnc2hvcnRjb2RlLWF0dHJpYnV0ZXMnLCBzaG9ydGNvZGVfYXR0cl9kYXRhKTtcblx0fVxuXG5cdGpRdWVyeSgnLnlhdGNvLXNob3J0Y29kZS15YWNodC1yZXN1bHRzIGlucHV0W25hbWU9XCJ2aWV3dHlwZVwiXSwgLnlhdGNvLXNob3J0Y29kZS15YWNodC1yZXN1bHRzIHNlbGVjdFtuYW1lPXNvcnRJZF0nKS5hdHRyKFxuXHRcdCdmb3JtJyxcblx0XHRqUXVlcnkoJy55YXRjby1zaG9ydGNvZGUtc2VhcmNoLWZvcm0gZm9ybScpLmF0dHIoJ2lkJylcblx0KTtcblxuXHQvLyBFeHBhbmQgU2VhcmNoXG5cdGpRdWVyeSgnLnNtYWxsLWZvcm0gLmV4cGFuZC1zZWFyY2gsIC5iaWctZm9ybSAuY2xvc2UtYWR2YW5kZW5jZWQtc2VhcmNoJykuY2xpY2soZnVuY3Rpb24gKGV2ZW50KSB7XG5cblx0XHRqUXVlcnkoJy5zbWFsbC1mb3JtJykudG9nZ2xlKCk7XG5cdFx0alF1ZXJ5KCcuYmlnLWZvcm0nKS50b2dnbGUoKTtcblxuXHRcdHZhciBleHBhbmRfc2VhcmNoX2lzX29wZW4gPSAoalF1ZXJ5KCcuYmlnLWZvcm0nKS5jc3MoJ2Rpc3BsYXknKSA9PSAnYmxvY2snKTtcblxuXHRcdGpRdWVyeSgnLnlhdGNvLXNob3J0Y29kZS15YWNodHMtc2VhcmNoLWZvcm0nKS5kYXRhKFxuXHRcdFx0J2lzLWFkdmFuY2VkLXNlYXJjaC1vcGVuJyxcblx0XHRcdChleHBhbmRfc2VhcmNoX2lzX29wZW4pID8gMSA6IDBcblx0XHQpO1xuXG5cdFx0alF1ZXJ5KCcueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMgaW5wdXRbbmFtZT1cInZpZXd0eXBlXCJdLCAueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMgc2VsZWN0W25hbWU9c29ydElkXScpLmF0dHIoXG5cdFx0XHQnZm9ybScsXG5cdFx0XHQoZXhwYW5kX3NlYXJjaF9pc19vcGVuKSA/IGpRdWVyeSgnLmJpZy1mb3JtJykuYXR0cignaWQnKSA6IGpRdWVyeSgnLnNtYWxsLWZvcm0nKS5hdHRyKCdpZCcpXG5cdFx0KTtcblx0fSk7XG5cblx0aWYgKFxuXHRcdHNlYXJjaFBhcmFtcy5nZXQoXCJvcGVuU2VhcmNoRm9ybVwiKSA9PSAneWVzJ1xuXHRcdHx8XG5cdFx0KHR5cGVvZiBzaG9ydGNvZGVfZm9ybV9hdHRyX2RhdGEgPT0gJ29iamVjdCcgJiYgdHlwZW9mIHNob3J0Y29kZV9mb3JtX2F0dHJfZGF0YS5vcGVuc2VhcmNoZm9ybSAhPSAndW5kZWZpbmVkJyAmJiBzaG9ydGNvZGVfZm9ybV9hdHRyX2RhdGEub3BlbnNlYXJjaGZvcm0gPT0gJ3llcycpXG5cdCkge1xuXHRcdGpRdWVyeSgnLnlhdGNvLXNob3J0Y29kZS15YWNodHMtc2VhcmNoLWZvcm0nKS5kYXRhKCdpcy1hZHZhbmNlZC1zZWFyY2gtb3BlbicsIDEpO1xuXG5cdFx0alF1ZXJ5KCcuc21hbGwtZm9ybScpLmhpZGUoKTtcblx0XHRqUXVlcnkoJy5iaWctZm9ybScpLnNob3coKTtcblxuXHRcdGpRdWVyeSgnLnlhdGNvLXNob3J0Y29kZS15YWNodC1yZXN1bHRzIGlucHV0W25hbWU9XCJ2aWV3dHlwZVwiXSwgLnlhdGNvLXNob3J0Y29kZS15YWNodC1yZXN1bHRzIHNlbGVjdFtuYW1lPXNvcnRJZF0nKS5hdHRyKFxuXHRcdFx0J2Zvcm0nLFxuXHRcdFx0alF1ZXJ5KCcuYmlnLWZvcm0nKS5hdHRyKCdpZCcpXG5cdFx0KTtcblx0fVxuXHRlbHNlIHtcblx0XHRpZiAoalF1ZXJ5KCcueWF0Y28tc2hvcnRjb2RlLXNlYXJjaC1mb3JtIGZvcm0nKS5sZW5ndGggPT0gMSkge1xuXHRcdFx0alF1ZXJ5KCcueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMgaW5wdXRbbmFtZT1cInZpZXd0eXBlXCJdLCAueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMgc2VsZWN0W25hbWU9c29ydElkXScpLmF0dHIoXG5cdFx0XHRcdCdmb3JtJyxcblx0XHRcdFx0alF1ZXJ5KCcuc21hbGwtZm9ybScpLmF0dHIoJ2lkJylcblx0XHRcdCk7XG5cdFx0fVxuXG5cdH1cblxuXHQvLyBTY3JvbGwgVG8gUmVzdWx0c1xuXHRpZiAoc2VhcmNoUGFyYW1zLmhhcygncGFnZV9pbmRleCcpIHx8IHNlYXJjaFBhcmFtcy5nZXQoJ29wZW5TZWFyY2hGb3JtJykgfHwgc2VhcmNoUGFyYW1zLmdldCgnc2Nyb2xsLXRvLWZvcm0nKSkge1xuXHRcdC8vZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3lhdGNvLXNjcm9sbC10by1vbmNlLXNlYXJjaGVkJykuc2Nyb2xsSW50b1ZpZXcoe2JlaGF2aW9yOiBcInNtb290aFwiLCBibG9jazogXCJzdGFydFwiLCBpbmxpbmU6IFwibmVhcmVzdFwifSk7XG5cblx0XHRpZiAoalF1ZXJ5KCcjeWF0Y28tc2Nyb2xsLXRvLW9uY2Utc2VhcmNoZWQnKS5sZW5ndGgpIHtcblx0XHRcdGpRdWVyeShbZG9jdW1lbnQuZG9jdW1lbnRFbGVtZW50LCBkb2N1bWVudC5ib2R5XSkuYW5pbWF0ZSh7XG5cdFx0XHRcdHNjcm9sbFRvcDogKGpRdWVyeShcIiN5YXRjby1zY3JvbGwtdG8tb25jZS1zZWFyY2hlZFwiKS5vZmZzZXQoKS50b3AgLSA3NSlcblx0XHRcdH0sIDEwMDApO1xuXHRcdH1cblx0fVxuXG5cdC8vIFZpZXcgQWxsXG5cdHZhciBtb3JlX2xpbmtfaHRtbCA9ICc8ZGl2IGNsYXNzPVwieXQtdGV4dC1jZW50ZXIgdmlldy1hbGwtYnV0dG9uXCI+PGEgaHJlZj1cIiNcIiBjbGFzcz1cInl0LWJ0biByZWFkLW1vcmVcIj5WSUVXIEFMTDwvYT48L2Rpdj4nO1xuXHR2YXIgbGVzc19saW5rX2h0bWwgPSAnPGRpdiBjbGFzcz1cInl0LXRleHQtY2VudGVyIHZpZXctYWxsLWJ1dHRvblwiPjxhIGhyZWY9XCIjXCIgY2xhc3M9XCJ5dC1idG4gcmVhZC1sZXNzXCI+VklFVyBMRVNTPC9hPjwvZGl2Pic7XG5cblx0bmV3IFJlYWRtb3JlKCcueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMudmlldy1hbGwtYnV0dG9uJywge1xuXHRcdHNwZWVkOiA3NSxcblx0XHRjb2xsYXBzZWRIZWlnaHQ6IChqUXVlcnkoJy55YXRjby1zaG9ydGNvZGUteWFjaHQtcmVzdWx0cyAuY29sLXlhY2h0JykuaGVpZ2h0KCkgKiAzKSxcblx0XHRtb3JlTGluazogbW9yZV9saW5rX2h0bWwsXG5cdFx0bGVzc0xpbms6IGxlc3NfbGlua19odG1sXG5cdH0pO1xuXG5cdG5ldyBSZWFkbW9yZSgnLmNhcmQtY29udGFpbmVyLnZpZXctYWxsLWJ1dHRvbicsIHtcblx0XHRzcGVlZDogNzUsXG5cdFx0Y29sbGFwc2VkSGVpZ2h0OiAoalF1ZXJ5KCcuY2FyZC1jb250YWluZXIgLmNvbC15YWNodCcpLmhlaWdodCgpICogMyksXG5cdFx0bW9yZUxpbms6IG1vcmVfbGlua19odG1sLFxuXHRcdGxlc3NMaW5rOiBsZXNzX2xpbmtfaHRtbFxuXHR9KTtcblxuXHQvLyBWaWV3IFN0eWxlXG5cdGpRdWVyeSgnLnlhdGNvLXNob3J0Y29kZS15YWNodC1yZXN1bHRzIGlucHV0W25hbWU9XCJ2aWV3dHlwZVwiXSwgLnlhdGNvLXNob3J0Y29kZS15YWNodC1yZXN1bHRzIHNlbGVjdFtuYW1lPXNvcnRJZF0nKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdGpRdWVyeSh0aGlzKS5jaGFuZ2UoZnVuY3Rpb24gKCkge1xuXG5cdFx0XHR2YXIgZm9ybV9pZCA9IGpRdWVyeSh0aGlzKS5hdHRyKCdmb3JtJyk7XG5cblx0XHRcdGpRdWVyeSgnIycgKyBmb3JtX2lkKS5zdWJtaXQoKTtcblxuXHRcdH0pO1xuXHR9KTtcblxuXHRqUXVlcnkoJy55YXRjby1zaG9ydGNvZGUteWFjaHQtcmVzdWx0cyBzZWxlY3RbbmFtZT1zb3J0RmllbGRdLCAueWF0Y28tc2hvcnRjb2RlLXlhY2h0LXJlc3VsdHMgc2VsZWN0W25hbWU9c29ydERpcmVjdGlvbl0nKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdGpRdWVyeSh0aGlzKS5jaGFuZ2UoZnVuY3Rpb24gKCkge1xuXHRcdFx0alF1ZXJ5KCcuYmlnLWZvcm0nKS5zdWJtaXQoKTtcblxuXHRcdH0pO1xuXHR9KTtcblxuXHQvLyBSZXNldCBTZWFyY2ggRm9ybVxuXHRqUXVlcnkoJy5iaWctZm9ybSBbdHlwZT1yZXNldF0nKS5jbGljayhmdW5jdGlvbiAoZSkge1xuXHRcdGUucHJldmVudERlZmF1bHQoKTtcblxuXHRcdGpRdWVyeSgnZm9ybVtkYXRhLXNob3J0Y29kZS1hdHRyaWJ1dGVzXScpLmVhY2goZnVuY3Rpb24gKCkge1xuXHRcdFx0alF1ZXJ5KHRoaXMpWzBdLnJlc2V0KCk7XG5cblx0XHRcdHZhciB2YWx1ZXMgPSBqUXVlcnkodGhpcykuZGF0YSgnc2hvcnRjb2RlLWF0dHJpYnV0ZXMnKTtcblxuXHRcdFx0cnVuX3Rocm91Z2hfYW5kX3N5bmNfZm9ybShqUXVlcnkodGhpcyksIHZhbHVlcyk7XG5cdFx0fSk7XG5cblx0fSk7XG5cblx0alF1ZXJ5KCdpbnB1dFtuYW1lPVwicHJpY2VyYW5nZV9mcm9tXCJdLCBpbnB1dFtuYW1lPVwicHJpY2VyYW5nZV90b1wiXSwgaW5wdXRbbmFtZT1cImxvYV9mcm9tXCJdLCBpbnB1dFtuYW1lPVwibG9hX3RvXCJdJykuZWFjaChmdW5jdGlvbiAoKSB7XG5cblx0XHRqUXVlcnkodGhpcykuYXR0cigndHlwZScsICd0ZXh0Jyk7Ly8uYXV0b051bWVyaWMoJ2luaXQnKTsgXG5cblx0XHRqUXVlcnkodGhpcykub24oJ2lucHV0JywgeXRfaW5wdXRfbnVtYmVyX2Zvcm1hdHRlcik7XG5cdFx0alF1ZXJ5KHRoaXMpLm9uKCdjaGFuZ2UnLCB5dF9pbnB1dF9udW1iZXJfZm9ybWF0dGVyKTtcblxuXHRcdHl0X2VsZV9pbnB1dF9udW1iZXJfZm9ybWF0dGVyKGpRdWVyeSh0aGlzKSk7XG5cblx0fSk7XG5cdC8qXG5cdFxuXHRcdGpRdWVyeSgnaW5wdXRbbmFtZT1cInByaWNlcmFuZ2VfdG9cIl0nKS5hdHRyKCd0eXBlJywgJ3RleHQnKS5hdXRvTnVtZXJpYygnaW5pdCcpOyBcblx0XHRqUXVlcnkoJ2lucHV0W25hbWU9XCJsb2FfZnJvbVwiXScpLmF0dHIoJ3R5cGUnLCAndGV4dCcpLmF1dG9OdW1lcmljKCdpbml0Jyk7IFxuXHRcdGpRdWVyeSgnaW5wdXRbbmFtZT1cImxvYV90b1wiXScpLmF0dHIoJ3R5cGUnLCAndGV4dCcpLmF1dG9OdW1lcmljKCdpbml0Jyk7IFx0Ki9cblxuXHRqUXVlcnkoJ2Zvcm1bZGF0YS1zaG9ydGNvZGUtYXR0cmlidXRlc10nKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdC8vdmFyIHZhbHVlcz17XCJTZXRNaW5NYXhcIjoge1wibG9hX3RvXCI6IHtcIm1pblwiOiAwLCBcIm1heFwiOiBcIjUxXCJ9fSwgXCJGaXJzdEFyZ3NcIjogeydsb2FfdG8nOiA1MX19O1xuXHRcdHZhciB2YWx1ZXMgPSBqUXVlcnkodGhpcykuZGF0YSgnc2hvcnRjb2RlLWF0dHJpYnV0ZXMnKTtcblxuXHRcdC8vIHZhciBpbnB1dF9uYW1lcz1PYmplY3Qua2V5cyh2YWx1ZXNbJ1NldE1pbk1heCddKTtcblxuXHRcdHZhciBqcXVlcnlfZWxlID0galF1ZXJ5KHRoaXMpO1xuXG5cdFx0aWYgKHR5cGVvZiB2YWx1ZXMuU2V0TWluTWF4ICE9ICd1bmRlZmluZWQnKSB7XG5cblx0XHRcdGpRdWVyeSgnaW5wdXRbbmFtZV0sIHNlbGVjdFtuYW1lXSwgdGV4dGFyZWFbbmFtZV0nLCBqcXVlcnlfZWxlKS5lYWNoKGZ1bmN0aW9uICgpIHtcblxuXHRcdFx0XHRpZiAoIWpRdWVyeSh0aGlzKS5hdHRyKCdkb250LXRvdWNoJykpIHtcblxuXHRcdFx0XHRcdHZhciBuYW1lID0galF1ZXJ5KHRoaXMpLmF0dHIoJ25hbWUnKTtcblx0XHRcdFx0XHR2YXIgX3ZhbCA9IG51bGw7XG5cblx0XHRcdFx0XHQvL2NvbnNvbGUubG9nKHZhbHVlc1snU2V0TWluTWF4J11bbmFtZV0pO1xuXG5cdFx0XHRcdFx0aWYgKHR5cGVvZiB2YWx1ZXMuU2V0TWluTWF4W25hbWVdICE9ICd1bmRlZmluZWQnKSB7XG5cdFx0XHRcdFx0XHR2YXIgbWluX2FuZF9tYXggPSB2YWx1ZXMuU2V0TWluTWF4W25hbWVdO1xuXG5cdFx0XHRcdFx0XHRqUXVlcnkodGhpcykuYXR0cihtaW5fYW5kX21heCk7XG5cblx0XHRcdFx0XHRcdGlmIChcblx0XHRcdFx0XHRcdFx0bmFtZSA9PSAncHJpY2VyYW5nZV9mcm9tJyB8fCBuYW1lID09ICdwcmljZXJhbmdlX3RvJ1xuXHRcdFx0XHRcdFx0XHR8fFxuXHRcdFx0XHRcdFx0XHRuYW1lID09ICdsb2FfdG8nIHx8IG5hbWUgPT0gJ2xvYV9mcm9tJ1xuXHRcdFx0XHRcdFx0KSB7XG5cblx0XHRcdFx0XHRcdFx0alF1ZXJ5KHRoaXMpLmF0dHIoJ3R5cGUnLCAndGV4dCcpLmF1dG9OdW1lcmljKCd1cGRhdGUnLCB7XG5cblx0XHRcdFx0XHRcdFx0XHQndk1pbic6IG1pbl9hbmRfbWF4Lm1pbixcblx0XHRcdFx0XHRcdFx0XHQndk1heCc6IG1pbl9hbmRfbWF4Lm1heCxcblxuXHRcdFx0XHRcdFx0XHR9KTtcblxuXHRcdFx0XHRcdFx0fVxuXG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cblx0XHRcdH0pO1xuXG5cdFx0fVxuXHR9KTtcblxuXHQvLyBTZWFyY2ggQWNjb3JkaW9uc1xuXHR2YXIgYWNjID0gZG9jdW1lbnQuZ2V0RWxlbWVudHNCeUNsYXNzTmFtZShcInl0LXNlYXJjaC1hY2NvcmRpb25cIik7XG5cdHZhciBpO1xuXG5cdGZvciAoaSA9IDA7IGkgPCBhY2MubGVuZ3RoOyBpKyspIHtcblx0XHRhY2NbaV0uYWRkRXZlbnRMaXN0ZW5lcihcImNsaWNrXCIsIGZ1bmN0aW9uICgpIHtcblx0XHRcdC8qIFRvZ2dsZSBiZXR3ZWVuIGFkZGluZyBhbmQgcmVtb3ZpbmcgdGhlIFwiYWN0aXZlXCIgY2xhc3MsXG5cdFx0XHR0byBoaWdobGlnaHQgdGhlIGJ1dHRvbiB0aGF0IGNvbnRyb2xzIHRoZSBwYW5lbCAqL1xuXHRcdFx0dGhpcy5jbGFzc0xpc3QudG9nZ2xlKFwiYWN0aXZlXCIpO1xuXG5cdFx0XHQvKiBUb2dnbGUgYmV0d2VlbiBoaWRpbmcgYW5kIHNob3dpbmcgdGhlIGFjdGl2ZSBwYW5lbCAqL1xuXHRcdFx0dmFyIHBhbmVsID0gdGhpcy5uZXh0RWxlbWVudFNpYmxpbmc7XG5cblx0XHRcdGlmIChwYW5lbC5zdHlsZS5kaXNwbGF5ID09PSBcImJsb2NrXCIpIHtcblx0XHRcdFx0cGFuZWwuc3R5bGUuZGlzcGxheSA9IFwibm9uZVwiO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0cGFuZWwuc3R5bGUuZGlzcGxheSA9IFwiYmxvY2tcIjtcblx0XHRcdH1cblx0XHR9KTtcblx0fVxuXG5cbn0pOyIsImZ1bmN0aW9uIGdldF9mb3JtX2RhdGEoZm9ybV9lbGUpIHtcbiAgdmFyIGZvcm1EYXRhID0gbmV3IEZvcm1EYXRhKCBmb3JtX2VsZSApO1xuICAgICAgXG4gIHZhciBmZCA9IEFycmF5LmZyb20oZm9ybURhdGEuZW50cmllcygpKS5yZWR1Y2UoKG1lbW8sIHBhaXIpID0+ICh7XG4gICAgLi4ubWVtbyxcbiAgICBbcGFpclswXV06IHBhaXJbMV0sXG4gIH0pLCB7fSk7XG5cbiAgcmV0dXJuIGZkO1xufVxuXG4vKiAtLS0tLS0gU3RhcnQgT2YgQ29va2llcyAtLS0tLS0gKi9cbnZhciBjb29raWVUaW1lPTM2MDtcblxuZnVuY3Rpb24gc2V0Q29va2llKGNfbmFtZSwgdmFsdWUsIGV4ZGF5cywgaG9zdCkge3ZhciBleGRhdGU9bmV3IERhdGUoKTsgZXhkYXRlLnNldERhdGUoZXhkYXRlLmdldERhdGUoKSArIGV4ZGF5cyk7IHZhciBjX3ZhbHVlPWVzY2FwZSh2YWx1ZSkgKyAoKGV4ZGF5cz09bnVsbCkgPyBcIlwiIDogXCI7IGV4cGlyZXM9XCIrZXhkYXRlLnRvVVRDU3RyaW5nKCkpOyBkb2N1bWVudC5jb29raWU9Y19uYW1lICsgXCI9XCIgKyBjX3ZhbHVlKyAoKGhvc3Q9PW51bGwpP1wiXCI6XCI7ZG9tYWluPVwiKyBob3N0ICtcIlwiK1wiO3BhdGg9LztcIik7fVxuXG5mdW5jdGlvbiBnZXRDb29raWUoY19uYW1lKSB7dmFyIGkseCx5LEFSUmNvb2tpZXM9ZG9jdW1lbnQuY29va2llLnNwbGl0KFwiO1wiKTsgZm9yIChpPTA7aTxBUlJjb29raWVzLmxlbmd0aDtpKyspIHt4PUFSUmNvb2tpZXNbaV0uc3Vic3RyKDAsQVJSY29va2llc1tpXS5pbmRleE9mKFwiPVwiKSk7IHk9QVJSY29va2llc1tpXS5zdWJzdHIoQVJSY29va2llc1tpXS5pbmRleE9mKFwiPVwiKSsxKTsgeD14LnJlcGxhY2UoL15cXHMrfFxccyskL2csXCJcIik7IGlmICh4PT1jX25hbWUpIHtyZXR1cm4gdW5lc2NhcGUoeSk7fX19XG5cbmZ1bmN0aW9uIGRlbGV0ZUNvb2tpZShuYW1lLCBwYXRoLCBkb21haW4pIHtpZiAoZ2V0Q29va2llKG5hbWUpKSB7ZG9jdW1lbnQuY29va2llID0gbmFtZSArIFwiPVwiICsgKChwYXRoKSA/IFwiOyBwYXRoPVwiICsgcGF0aCA6IFwiXCIpICsgKChkb21haW4pID8gXCI7IGRvbWFpbj1cIiArIGRvbWFpbiA6IFwiXCIpICsgXCI7IGV4cGlyZXM9VGh1LCAwMS1KYW4tNzAgMDA6MDA6MDEgR01UXCI7fX1cdFxuLyogLS0tLS0tIEVuZCBPZiBDb29raWVzIC0tLS0tLSAqL1xuXG4vLyBHb29nbGUgQW5hbHl0aWNzIFxuZnVuY3Rpb24geXRfZ2FfZXZlbnQoY2F0ZWdvcnksIGFjdGlvbiwgbGFiZWwsIHZhbHVlKSB7XG4gIGNvbnNvbGUubG9nKCdnYSBldmVudCcpO1xuICBcbiAgaWYgKHR5cGVvZiBndGFnICE9PSAndW5kZWZpbmVkJykge1xuICAgIGd0YWcoJ2V2ZW50JywgYWN0aW9uLCB7XG4gICAgICAnZXZlbnRfY2F0ZWdvcnknOiBjYXRlZ29yeSwgXG4gICAgICAnZXZlbnRfbGFiZWwnOiBsYWJlbCwgXG4gICAgICAnZXZlbnRfdmFsdWUnOiB2YWx1ZSxcbiAgICAgICdldmVudF9jYWxsYmFjayc6IGZ1bmN0aW9uKCkge1xuXG4gICAgICAgIGNvbnNvbGUubG9nKCdnYSBldmVudCBjYWxsYmFjayAoJysgYWN0aW9uICsnIC0gJysgY2F0ZWdvcnkgKycgLSAnKyBsYWJlbCArJyknKTtcblxuICAgICAgfSBcbiAgICB9KTtcbiAgfVxuICBlbHNlIGlmICh0eXBlb2YgZ2EgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICBnYSgnc2VuZCcsICdldmVudCcsIGNhdGVnb3J5LCBhY3Rpb24sIGxhYmVsKTtcbiAgfVxuICBlbHNlIHtcbiAgICBjb25zb2xlLndhcm4oXCJHb29nbGUgQW5hbHl0aWNzIERvZXNudCBTZWVtIFRvIEJlIEluc3RhbGxlZC5cIik7XG4gIH1cblxuICBpZiAodHlwZW9mIF9wYXEgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICBfcGFxLnB1c2goWyd0cmFja0V2ZW50JywgY2F0ZWdvcnksIGFjdGlvbiwgbGFiZWwsIHZhbHVlXSk7XG4gIH1cbn1cblxuLy8gUmVuZGVyIEdvb2dsZSBSZUNhcHRjaGFcbmZ1bmN0aW9uIHJlbmRlcllBVENPcmVjYXB0Y2hhKCkge1xuXG4gIC8qalF1ZXJ5KCcueWF0Y28tZy1yZWNhcHRjaGEnKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG4gICAgZ3JlY2FwdGNoYS5yZW5kZXIoalF1ZXJ5KHRoaXMpWzBdLCB7fSk7XG5cbiAgfSk7Ki9cblxuICAvKmpRdWVyeSgnKjpub3QoLnl0LW1vZGFsKSAueWF0Y28tZy1yZWNhcHRjaGEnKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG4gICAgZ3JlY2FwdGNoYS5yZW5kZXIoalF1ZXJ5KHRoaXMpWzBdLCB7fSk7XG5cbiAgfSk7Ki9cblxufVxuXG4vLyByZS1pbml0aW5nIG1vZGFsIGpxdWVyeVxualF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgalF1ZXJ5KCdbZGF0YS1tb2RhbF0nKS5jbGljayhmdW5jdGlvbigpIHtcblxuICAgIHZhciBkYXRhX21vZGFsID0galF1ZXJ5KHRoaXMpLmRhdGEoJ21vZGFsJyk7XG5cbiAgICB5dF9nYV9ldmVudCgnTW9kYWwnLCAnT3BlbicsICdNb2RhbCBPcGVuZWQ6ICcrIGRhdGFfbW9kYWwsIGRhdGFfbW9kYWwpO1xuXG4gICAgalF1ZXJ5KCBkYXRhX21vZGFsICkueWF0Y29fbW9kYWwoe1xuICAgIFx0Y2xvc2VUZXh0OiAnWCcsXG4gICAgICBtb2RhbENsYXNzOiAneXQtbW9kYWwtb3BlbicsXG4gICAgICBjbG9zZUNsYXNzOiAneXQtbW9kZWwtY2xvc2UnXG4gICAgfSk7XG4gIH0pO1xufSk7XG5cblxuLy8gTmluamEgRm9ybXMgU3VibWl0IEV2ZW50IFRvIEdvb2dsZVxuLypqUXVlcnkoIGRvY3VtZW50ICkucmVhZHkoIGZ1bmN0aW9uKCAkICkge1xuICBpZiAodHlwZW9mIE1hcmlvbmV0dGUgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAvLyBDcmVhdGUgYSBuZXcgb2JqZWN0IGZvciBjdXN0b20gdmFsaWRhdGlvbiBvZiBhIGN1c3RvbSBmaWVsZC5cbiAgICB2YXIgbmluamFGb3Jtc1N1Ym1pdHRpb24gPSBNYXJpb25ldHRlLk9iamVjdC5leHRlbmQoe1xuICAgICAgaW5pdGlhbGl6ZTogZnVuY3Rpb24oKSB7XG4gICAgICAgIHRoaXMubGlzdGVuVG8oIEJhY2tib25lLlJhZGlvLmNoYW5uZWwoICdmb3JtcycgKSwgJ3N1Ym1pdDpyZXNwb25zZScsIHRoaXMuYWN0aW9uU3VibWl0ICk7XG4gICAgICB9LFxuXG4gICAgICBhY3Rpb25TdWJtaXQ6IGZ1bmN0aW9uKCByZXNwb25zZSApIHtcbiAgICAgICAgY29uc29sZS5sb2coIHJlc3BvbnNlICk7XG4gICAgICAgIGNvbnNvbGUubG9nKCByZXNwb25zZS5kYXRhLnNldHRpbmdzLnRpdGxlICk7XG5cbiAgICAgICAgLy8gRG8gc3R1ZmYgaGVyZS5cbiAgICAgICAgaWYgKHR5cGVvZiBnYSAhPSAndW5kZWZpbmVkJykge1xuICAgICAgICAgIHl0X2dhX2V2ZW50KCdOaW5qYSBGb3JtIFN1Ym1pdHRpb24nLCByZXNwb25zZS5kYXRhLnNldHRpbmdzLnRpdGxlLCAnU3VibWl0dGVkJyk7XG4gICAgICAgIH1cbiAgICAgIH1cbiAgICB9KTtcblxuICAgIC8vIEluc3RhbnRpYXRlIG91ciBjdXN0b20gZmllbGQncyBjb250cm9sbGVyLCBkZWZpbmVkIGFib3ZlLlxuICAgIG5ldyBuaW5qYUZvcm1zU3VibWl0dGlvbigpO1xuICB9XG4gIGVsc2Uge1xuICAgIGNvbnNvbGUubG9nKCdNYXJpb25ldHRlIGlzIHVuZGVmaW5lZC4gSnVzdCBtZWFucyBuaW5qYSBpcyBub3QgcnVubmluZyBvbiBwYWdlLicpO1xuICB9XG59KTsqL1xuXG5qUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICBqUXVlcnkoJ2ltZ1tzcmNePVwiaHR0cHM6Ly9jbG91ZC55YXRjby5jb21cIl0nKS5iaW5kKCdjb250ZXh0bWVudScsIGZ1bmN0aW9uKGUpIHtcbiAgICAgIHJldHVybiBmYWxzZTtcbiAgfSk7IFxufSk7XG5cbi8vIFN1Ym1pdHRpbmcgTmV3c2xldHRlciBcbmZ1bmN0aW9uIFlUUExVR0lOU3VibWl0dGluZ05ld3NsZXR0ZXIoZSkge1xuXG4gIGUucHJldmVudERlZmF1bHQoKTtcbiAgXG4gIHZhciBlbGVfZm9ybSA9IGpRdWVyeSh0aGlzKTtcbiAgdmFyIGZvcm1EYXRhID0gZ2V0X2Zvcm1fZGF0YShlbGVfZm9ybVswXSk7XG5cbiAgeXRfc2V0X2xlYWRfY29tbW9uX2lucHV0cyhmb3JtRGF0YSk7XG5cbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnLCBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCkpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCdQcm9jZXNzaW5nLi4uJyk7XG5cbiAgeXRfZ2FfZXZlbnQoJ05ld3NsZXR0ZXInLCAnU3VibWl0dGVkJywgJ0Zvcm0gU3VibWl0IENsaWNrZWQnLCAnJyk7XG5cbiAgLy8gQWRkIE5ldyBUcmFja2luZyBGb3IgT3duZXIgVlMgaW5kdXN0cnlcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ0NsaWVudE5ld3NsZXR0ZXInLCAnZm9ybV9kYXRhJzogZm9ybURhdGF9KS50aGVuKGZ1bmN0aW9uKHJfZGF0YSkge1xuXG4gICAgU3VibWl0dGluZ0NvbnRhY3RGb3JtX1N1Y2Nlc3Mocl9kYXRhLCBlbGVfZm9ybSk7XG5cbiAgICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnKSk7XG5cbiAgICBpZiAocl9kYXRhLlJlc3BvbnNlQ29kZSA9PSAwKSB7XG4gICAgICB5dF9nYV9ldmVudCgnTmV3c2xldHRlcicsICdTdWNjZXNzJywgJ1N1Y2Nlc3NmdWwgTmV3c2xldHRlciBTaWdudXAnLCAnJyk7XG4gICAgICBzZXRDb29raWUoJ2hhc19uZXdzbGV0dGVyX3NpZ251cF92T05FJywgMSwgMzY1KTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICB5dF9nYV9ldmVudCgnTmV3c2xldHRlcicsICdVbmtub3cgSXNzdWUnLCAnQm9zcyBSZXR1cm4gSXNzdWUnLCAnJyk7XG4gICAgfVxuICB9KTtcblxufVxuXG5qUXVlcnkoIGRvY3VtZW50ICkucmVhZHkoIGZ1bmN0aW9uKCAkICkge1xuXG4gIGpRdWVyeSgnLnlhdGNvLWNsaWVudC1uZXdzbGV0dGVyLXNpZ251cCcpLmVhY2goZnVuY3Rpb24oKSB7XG5cbiAgICB5dF9yZXN0b3JlX2xlYWRfY29tbW9uX2lucHV0cyhqUXVlcnkodGhpcykpO1xuXG4gICAgalF1ZXJ5KHRoaXMpLm9uKCdzdWJtaXQnLCBZVFBMVUdJTlN1Ym1pdHRpbmdOZXdzbGV0dGVyKTtcblxuICB9KTtcblxufSApO1xuXG4iLCIvLyBORVcgRlVOQ1RJT05TXG52YXIgeXRfbGVhZF9jb21tb25faW5wdXRzPVsnRmlyc3ROYW1lJywgJ0xhc3ROYW1lJywgJ0VtYWlsJywgJ1Bob25lJywgJ293bmVyX29yX2luZHVzdHJ5J107XG5cbmZ1bmN0aW9uIHl0X3Jlc3RvcmVfbGVhZF9jb21tb25faW5wdXRzKGZvcm0pIHtcbiAgIHl0X2xlYWRfY29tbW9uX2lucHV0cy5mb3JFYWNoKGZ1bmN0aW9uKGlucHV0X25hbWUpIHtcbiAgICAgIFxuICAgIHZhciBjb29raWVfdmFsID0gZ2V0Q29va2llKCd5YXRjby1jb250YWN0LWZvcm0tJytpbnB1dF9uYW1lKTtcblxuICAgIGlmICh0eXBlb2YgY29va2llX3ZhbCAhPSAndW5kZWZpbmVkJyAmJiBjb29raWVfdmFsICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICBqUXVlcnkoJ2lucHV0W25hbWU9JysgIGlucHV0X25hbWUgKyddJywgZm9ybSkudmFsKCBjb29raWVfdmFsICk7XG4gICAgfVxuICB9KTtcbn1cblxuZnVuY3Rpb24geXRfc2V0X2xlYWRfY29tbW9uX2lucHV0cyhkYXRhKSB7XG4gIHl0X2xlYWRfY29tbW9uX2lucHV0cy5mb3JFYWNoKGZ1bmN0aW9uKGtleSkge1xuIFxuICAgIHNldENvb2tpZSgneWF0Y28tY29udGFjdC1mb3JtLScra2V5LCBkYXRhWyBrZXkgXSwgMzYwKTtcbiBcbiAgfSk7XG59XG5cbmZ1bmN0aW9uIHl0X293bmVyX29yKGVsZV9mb3JtKSB7XG4gIGlmIChqUXVlcnkoJ2lucHV0W25hbWU9b3duZXJfb3JfaW5kdXN0cnldOmNoZWNrZWQnLCBlbGVfZm9ybSkudmFsKCkgPT0gJ293bmVyJykge1xuICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdPd25lcicsICcnKTtcbiAgfVxuICBlbHNlIGlmIChqUXVlcnkoJ2lucHV0W25hbWU9b3duZXJfb3JfaW5kdXN0cnldOmNoZWNrZWQnLCBlbGVfZm9ybSkudmFsKCkgPT0gJ2luZHVzdHJ5Jykge1xuICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdJbmR1c3RyeScsICcnKTtcbiAgfVxufVxuXG5mdW5jdGlvbiBZVF9TdWJtaXRWZXNzZWxMZWFkKGUpIHtcbiAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICBcbiAgdmFyIGVsZV9mb3JtID0galF1ZXJ5KHRoaXMpO1xuICB2YXIgZm9ybURhdGEgPSBnZXRfZm9ybV9kYXRhKGVsZV9mb3JtWzBdKTtcblxuICB5dF9zZXRfbGVhZF9jb21tb25faW5wdXRzKGZvcm1EYXRhKTtcblxuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcsIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoKSk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoJ1Byb2Nlc3NpbmcuLi4nKTtcblxuICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnU3VibWl0dGVkJywgJ0Zvcm0gU3VibWl0IENsaWNrZWQnLCBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ1NlbmRWZXNzZWxMZWFkJywgJ2Zvcm1fZGF0YSc6IGZvcm1EYXRhfSkudGhlbihmdW5jdGlvbihyX2RhdGEpIHtcbiAgICBTdWJtaXR0aW5nQ29udGFjdEZvcm1fU3VjY2VzcyhyX2RhdGEsIGVsZV9mb3JtKTtcblxuICAgIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcpKTtcblxuICAgIGlmIChyX2RhdGEuUmVzcG9uc2VDb2RlID09IDApIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdTdWNjZXNzJywgJ1N1Y2Nlc3NmdWxsIFZlc3NlbCBMZWFkJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpOyBcblxuICAgICAgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnb3duZXInKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdPd25lcicsICcnKTtcbiAgICAgIH1cbiAgICAgIGVsc2UgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnaW5kdXN0cnknKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdJbmR1c3RyeScsICcnKTtcbiAgICAgIH1cbiAgICB9XG4gICAgZWxzZSBpZiAodHlwZW9mIHJfZGF0YS5zcGFtX2FraSAhPSAndW5kZWZpbmVkJykge1xuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ0FLSSBTUEFNJywgJ0FraSBTcGFtIFJldHVybiBJc3N1ZScsICBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnVW5rbm93IElzc3VlJywgJ0Jvc3MgUmV0dXJuIElzc3VlJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuICAgIH1cbiAgfSk7XG59XG5cbmZ1bmN0aW9uIFlUX1N1Ym1pdFZlc3NlbExlYWRBYm91dEZ1bGxTcGVjKGUpIHtcbiAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICBcbiAgdmFyIGVsZV9mb3JtID0galF1ZXJ5KHRoaXMpO1xuICB2YXIgZm9ybURhdGEgPSBnZXRfZm9ybV9kYXRhKGVsZV9mb3JtWzBdKTtcblxuICB5dF9zZXRfbGVhZF9jb21tb25faW5wdXRzKGZvcm1EYXRhKTtcblxuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcsIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoKSk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoJ1Byb2Nlc3NpbmcuLi4nKTtcblxuICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnU3VibWl0dGVkJywgJ0Zvcm0gU3VibWl0IENsaWNrZWQnLCBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ1NlbmRWZXNzZWxMZWFkJywgJ2Zvcm1fZGF0YSc6IGZvcm1EYXRhfSkudGhlbihmdW5jdGlvbihyX2RhdGEpIHtcbiAgICBTdWJtaXR0aW5nQ29udGFjdEZvcm1fU3VjY2VzcyhyX2RhdGEsIGVsZV9mb3JtKTtcblxuICAgIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcpKTtcblxuICAgIGlmIChyX2RhdGEuUmVzcG9uc2VDb2RlID09IDApIHtcbiAgICAgIC8veXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1N1Y2Nlc3MnLCAnU3VjY2Vzc2Z1bGwgVmVzc2VsIExlYWQnLCAgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7IFxuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1N1Y2Nlc3MnLCAnU3VjY2Vzc2Z1bGwgRnVsbCBTcGVjIFZlc3NlbCBMZWFkJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpOyBcblxuICAgICAgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnb3duZXInKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdPd25lcicsICcnKTtcbiAgICAgIH1cbiAgICAgIGVsc2UgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnaW5kdXN0cnknKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdJbmR1c3RyeScsICcnKTtcbiAgICAgIH1cbiAgICB9XG4gICAgZWxzZSBpZiAodHlwZW9mIHJfZGF0YS5zcGFtX2FraSAhPSAndW5kZWZpbmVkJykge1xuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ0FLSSBTUEFNJywgJ0FraSBTcGFtIFJldHVybiBJc3N1ZScsICBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnVW5rbm93IElzc3VlJywgJ0Jvc3MgUmV0dXJuIElzc3VlJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuICAgIH1cbiAgfSk7XG59XG5cbmZ1bmN0aW9uIFlUX1N1Ym1pdFZlc3NlbEJyb2NodXJlTGVhZChlKSB7XG4gIGUucHJldmVudERlZmF1bHQoKTtcbiAgXG4gIHZhciBlbGVfZm9ybSA9IGpRdWVyeSh0aGlzKTtcbiAgdmFyIGZvcm1EYXRhID0gZ2V0X2Zvcm1fZGF0YShlbGVfZm9ybVswXSk7XG5cbiAgeXRfc2V0X2xlYWRfY29tbW9uX2lucHV0cyhmb3JtRGF0YSk7XG5cbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnLCBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCkpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCdQcm9jZXNzaW5nLi4uJyk7XG5cbiAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1N1Ym1pdHRlZCcsICdGb3JtIFN1Ym1pdCBDbGlja2VkJywgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7XG5cbiAgX1lCQS5zZW5kX2xlYWQoeydsYWJlbCc6ICdTZW5kVmVzc2VsTGVhZCcsICdmb3JtX2RhdGEnOiBmb3JtRGF0YX0pLnRoZW4oZnVuY3Rpb24ocl9kYXRhKSB7XG4gICAgU3VibWl0dGluZ0NvbnRhY3RGb3JtX1N1Y2Nlc3Mocl9kYXRhLCBlbGVfZm9ybSk7XG5cbiAgICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnKSk7XG5cbiAgICBpZiAocl9kYXRhLlJlc3BvbnNlQ29kZSA9PSAwKSB7XG4gICAgICAvL3l0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdTdWNjZXNzJywgJ1N1Y2Nlc3NmdWxsIFZlc3NlbCBMZWFkJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpOyBcbiAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdTdWNjZXNzJywgJ1N1Y2Nlc3NmdWxsIFZlc3NlbCBCcm9jaHVyZSBMZWFkJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpOyBcblxuICAgICAgd2luZG93Lm9wZW4oZm9ybURhdGEuQnJvY2h1cmVVcmwsICdfYmxhbmsnKTtcblxuICAgICAgLy9qUXVlcnkoJyNkb3dubG9hZF9wZGZfYnJvY2h1cmUnKVswXS5zcmM9Zm9ybURhdGEuQnJvY2h1cmVVcmw7XG5cbiAgICAgIGlmIChqUXVlcnkoJ2lucHV0W25hbWU9b3duZXJfb3JfaW5kdXN0cnldOmNoZWNrZWQnLCBlbGVfZm9ybSkudmFsKCkgPT0gJ293bmVyJykge1xuICAgICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnT3duZXIgT1IgSW5kdXN0cnknLCAnT3duZXInLCAnJyk7XG4gICAgICB9XG4gICAgICBlbHNlIGlmIChqUXVlcnkoJ2lucHV0W25hbWU9b3duZXJfb3JfaW5kdXN0cnldOmNoZWNrZWQnLCBlbGVfZm9ybSkudmFsKCkgPT0gJ2luZHVzdHJ5Jykge1xuICAgICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnT3duZXIgT1IgSW5kdXN0cnknLCAnSW5kdXN0cnknLCAnJyk7XG4gICAgICB9XG4gICAgfVxuICAgIGVsc2UgaWYgKHR5cGVvZiByX2RhdGEuc3BhbV9ha2kgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdBS0kgU1BBTScsICdBa2kgU3BhbSBSZXR1cm4gSXNzdWUnLCAgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1Vua25vdyBJc3N1ZScsICdCb3NzIFJldHVybiBJc3N1ZScsICBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcbiAgICB9XG4gIH0pO1xufVxuXG5mdW5jdGlvbiBZVF9TdWJtaXRWZXNzZWxMZWFkU2hvd2luZyhlKSB7XG4gIGUucHJldmVudERlZmF1bHQoKTtcbiAgXG4gIHZhciBlbGVfZm9ybSA9IGpRdWVyeSh0aGlzKTtcbiAgdmFyIGZvcm1EYXRhID0gZ2V0X2Zvcm1fZGF0YShlbGVfZm9ybVswXSk7XG5cbiAgeXRfc2V0X2xlYWRfY29tbW9uX2lucHV0cyhmb3JtRGF0YSk7XG5cbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnLCBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCkpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCdQcm9jZXNzaW5nLi4uJyk7XG5cbiAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1N1Ym1pdHRlZCcsICdGb3JtIFN1Ym1pdCBDbGlja2VkJywgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7XG5cbiAgX1lCQS5zZW5kX2xlYWQoeydsYWJlbCc6ICdTZW5kVmVzc2VsTGVhZCcsICdmb3JtX2RhdGEnOiBmb3JtRGF0YX0pLnRoZW4oZnVuY3Rpb24ocl9kYXRhKSB7XG4gICAgU3VibWl0dGluZ0NvbnRhY3RGb3JtX1N1Y2Nlc3Mocl9kYXRhLCBlbGVfZm9ybSk7XG5cbiAgICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnKSk7XG5cbiAgICBpZiAocl9kYXRhLlJlc3BvbnNlQ29kZSA9PSAwKSB7XG4gICAgICAvL3l0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdTdWNjZXNzJywgJ1N1Y2Nlc3NmdWxsIFZlc3NlbCBMZWFkJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpOyBcbiAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdTdWNjZXNzJywgJ1NjaGVkdWxlZCBBIFNob3dpbmcnLCAgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7IFxuXG4gICAgICBpZiAoalF1ZXJ5KCdpbnB1dFtuYW1lPW93bmVyX29yX2luZHVzdHJ5XTpjaGVja2VkJywgZWxlX2Zvcm0pLnZhbCgpID09ICdvd25lcicpIHtcbiAgICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ093bmVyIE9SIEluZHVzdHJ5JywgJ093bmVyJywgJycpO1xuICAgICAgfVxuICAgICAgZWxzZSBpZiAoalF1ZXJ5KCdpbnB1dFtuYW1lPW93bmVyX29yX2luZHVzdHJ5XTpjaGVja2VkJywgZWxlX2Zvcm0pLnZhbCgpID09ICdpbmR1c3RyeScpIHtcbiAgICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ093bmVyIE9SIEluZHVzdHJ5JywgJ0luZHVzdHJ5JywgJycpO1xuICAgICAgfVxuICAgIH1cbiAgICBlbHNlIGlmICh0eXBlb2Ygcl9kYXRhLnNwYW1fYWtpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnQUtJIFNQQU0nLCAnQWtpIFNwYW0gUmV0dXJuIElzc3VlJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdVbmtub3cgSXNzdWUnLCAnQm9zcyBSZXR1cm4gSXNzdWUnLCAgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7XG4gICAgfVxuICB9KTtcbn1cblxuZnVuY3Rpb24gWVRfU3VibWl0VmVzc2VsTGVhZFF1bGlmaWVkQnJva2VyKGUpIHtcbiAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICBcbiAgdmFyIGVsZV9mb3JtID0galF1ZXJ5KHRoaXMpO1xuICB2YXIgZm9ybURhdGEgPSBnZXRfZm9ybV9kYXRhKGVsZV9mb3JtWzBdKTtcblxuICB5dF9zZXRfbGVhZF9jb21tb25faW5wdXRzKGZvcm1EYXRhKTtcblxuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcsIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoKSk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoJ1Byb2Nlc3NpbmcuLi4nKTtcblxuICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnU3VibWl0dGVkJywgJ0Zvcm0gU3VibWl0IENsaWNrZWQnLCBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ1NlbmRWZXNzZWxMZWFkJywgJ2Zvcm1fZGF0YSc6IGZvcm1EYXRhfSkudGhlbihmdW5jdGlvbihyX2RhdGEpIHtcbiAgICBTdWJtaXR0aW5nQ29udGFjdEZvcm1fU3VjY2VzcyhyX2RhdGEsIGVsZV9mb3JtKTtcblxuICAgIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcpKTtcblxuICAgIGlmIChyX2RhdGEuUmVzcG9uc2VDb2RlID09IDApIHtcbiAgICAgIC8veXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1N1Y2Nlc3MnLCAnU3VjY2Vzc2Z1bGwgVmVzc2VsIExlYWQnLCAgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7IFxuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ1N1Y2Nlc3MnLCAnUXVsaWZpZWQgQnJva2VyJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpOyBcblxuICAgICAgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnb3duZXInKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdPd25lcicsICcnKTtcbiAgICAgIH1cbiAgICAgIGVsc2UgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnaW5kdXN0cnknKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdJbmR1c3RyeScsICcnKTtcbiAgICAgIH1cbiAgICB9XG4gICAgZWxzZSBpZiAodHlwZW9mIHJfZGF0YS5zcGFtX2FraSAhPSAndW5kZWZpbmVkJykge1xuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBMZWFkJywgJ0FLSSBTUEFNJywgJ0FraSBTcGFtIFJldHVybiBJc3N1ZScsICBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnVW5rbm93IElzc3VlJywgJ0Jvc3MgUmV0dXJuIElzc3VlJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuICAgIH1cbiAgfSk7XG59XG5cbmZ1bmN0aW9uIFlUX1N1Ym1pdEJyb2tlckNvbnRhY3RGb3JtKGUpIHtcbiAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICBcbiAgdmFyIGVsZV9mb3JtID0galF1ZXJ5KHRoaXMpO1xuICB2YXIgZm9ybURhdGEgPSBnZXRfZm9ybV9kYXRhKGVsZV9mb3JtWzBdKTtcblxuICB5dF9zZXRfbGVhZF9jb21tb25faW5wdXRzKGZvcm1EYXRhKTtcblxuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcsIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoKSk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoJ1Byb2Nlc3NpbmcuLi4nKTtcblxuICB5dF9nYV9ldmVudCgnQnJva2VyJywgJ1N1Ym1pdHRlZCcsICdGb3JtIFN1Ym1pdCBDbGlja2VkJywgJ2Jyb2tlci0nK2Zvcm1EYXRhLkJyb2tlcklEKTtcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ0Jyb2tlckNvbnRhY3QnLCAnZm9ybV9kYXRhJzogZm9ybURhdGF9KS50aGVuKGZ1bmN0aW9uKHJfZGF0YSkge1xuXG4gICAgU3VibWl0dGluZ0NvbnRhY3RGb3JtX1N1Y2Nlc3Mocl9kYXRhLCBlbGVfZm9ybSk7XG5cbiAgICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnKSk7XG5cbiAgICBpZiAocl9kYXRhLlJlc3BvbnNlQ29kZSA9PSAwKSB7XG4gICAgICB5dF9nYV9ldmVudCgnQnJva2VyJywgJ1N1Y2Nlc3MnLCAnU3VjY2Vzc2Z1bGwgQnJva2VyIE1lc3NhZ2UnLCAnYnJva2VyLScrZm9ybURhdGEuQnJva2VySUQpO1xuICAgIH1cbiAgICBlbHNlIGlmICh0eXBlb2Ygcl9kYXRhLnNwYW1fYWtpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnQUtJIFNQQU0nLCAnQWtpIFNwYW0gUmV0dXJuIElzc3VlJywgJ2Jyb2tlci0nK2Zvcm1EYXRhLkJyb2tlcklEKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICB5dF9nYV9ldmVudCgnQnJva2VyJywgJ1Vua25vdyBJc3N1ZScsICdCb3NzIFJldHVybiBJc3N1ZScsICdicm9rZXItJytmb3JtRGF0YS5Ccm9rZXJJRCk7XG4gICAgfVxuICB9KTtcbn1cblxuZnVuY3Rpb24gWVRfU3VibWl0U29sZENvbnRhY3RGb3JtKGUpIHtcbiAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICBcbiAgdmFyIGVsZV9mb3JtID0galF1ZXJ5KHRoaXMpO1xuICB2YXIgZm9ybURhdGEgPSBnZXRfZm9ybV9kYXRhKGVsZV9mb3JtWzBdKTtcblxuICB5dF9zZXRfbGVhZF9jb21tb25faW5wdXRzKGZvcm1EYXRhKTtcblxuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuYXR0cignZGlzYWJsZWQnLCAnZGlzYWJsZWQnKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcsIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoKSk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoJ1Byb2Nlc3NpbmcuLi4nKTtcblxuICB5dF9nYV9ldmVudCgnVmVzc2VsIFNvbGQgTGVhZCcsICdTdWJtaXR0ZWQnLCAnRm9ybSBTdWJtaXQgQ2xpY2tlZCcsICdicm9rZXItJytmb3JtRGF0YS5Ccm9rZXJJRCk7XG5cbiAgX1lCQS5zZW5kX2xlYWQoeydsYWJlbCc6ICdCcm9rZXJDb250YWN0JywgJ2Zvcm1fZGF0YSc6IGZvcm1EYXRhfSkudGhlbihmdW5jdGlvbihyX2RhdGEpIHtcblxuICAgIFN1Ym1pdHRpbmdDb250YWN0Rm9ybV9TdWNjZXNzKHJfZGF0YSwgZWxlX2Zvcm0pO1xuXG4gICAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbChqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuZGF0YSgnb2ctdmFsJykpO1xuXG4gICAgaWYgKHJfZGF0YS5SZXNwb25zZUNvZGUgPT0gMCkge1xuICAgICAgeXRfZ2FfZXZlbnQoJ1Zlc3NlbCBTb2xkIExlYWQnLCAnU3VjY2VzcycsICdTdWNjZXNzZnVsbCBTb2xkIE1lc3NhZ2UnLCAnYnJva2VyLScrZm9ybURhdGEuQnJva2VySUQpO1xuICAgIH1cbiAgICBlbHNlIGlmICh0eXBlb2Ygcl9kYXRhLnNwYW1fYWtpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnQUtJIFNQQU0nLCAnQWtpIFNwYW0gUmV0dXJuIElzc3VlJywgJ2Jyb2tlci0nK2Zvcm1EYXRhLkJyb2tlcklEKTtcbiAgICB9XG4gICAgZWxzZSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIFNvbGQgTGVhZCcsICdVbmtub3cgSXNzdWUnLCAnQm9zcyBSZXR1cm4gSXNzdWUnLCAnYnJva2VyLScrZm9ybURhdGEuQnJva2VySUQpO1xuICAgIH1cbiAgfSk7XG59XG5cbmZ1bmN0aW9uIFlUX1N1Ym1pdFNlcnZpY2VNbHNDb250ZW50Rm9ybShlKSB7XG4gIGUucHJldmVudERlZmF1bHQoKTtcbiAgXG4gIHZhciBlbGVfZm9ybSA9IGpRdWVyeSh0aGlzKTtcbiAgdmFyIGZvcm1EYXRhID0gZ2V0X2Zvcm1fZGF0YShlbGVfZm9ybVswXSk7XG5cbiAgeXRfc2V0X2xlYWRfY29tbW9uX2lucHV0cyhmb3JtRGF0YSk7XG5cbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnLCBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCkpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCdQcm9jZXNzaW5nLi4uJyk7XG5cbiAgeXRfZ2FfZXZlbnQoJ1NlcnZpY2VzIE1MUycsICdTdWJtaXR0ZWQnLCAnRm9ybSBTdWJtaXQgQ2xpY2tlZCcsICcnKTtcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ1NlcnZpY2VzTWxzQ29udGFjdCcsICdmb3JtX2RhdGEnOiBmb3JtRGF0YX0pLnRoZW4oZnVuY3Rpb24ocl9kYXRhKSB7XG5cbiAgICBTdWJtaXR0aW5nQ29udGFjdEZvcm1fU3VjY2VzcyhyX2RhdGEsIGVsZV9mb3JtKTtcblxuICAgIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcpKTtcblxuICAgIGlmIChyX2RhdGEuUmVzcG9uc2VDb2RlID09IDApIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdTZXJ2aWNlcyBNTFMnLCAnU3VjY2VzcycsICdTdWNjZXNzZnVsbCBTb2xkIE1lc3NhZ2UnLCAnJyk7XG4gICAgfVxuICAgIGVsc2UgaWYgKHR5cGVvZiByX2RhdGEuc3BhbV9ha2kgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdTZXJ2aWNlcyBNTFMnLCAnQUtJIFNQQU0nLCAnQWtpIFNwYW0gUmV0dXJuIElzc3VlJywgJycpO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdTZXJ2aWNlcyBNTFMnLCAnVW5rbm93IElzc3VlJywgJ0Jvc3MgUmV0dXJuIElzc3VlJywgJycpO1xuICAgIH1cbiAgfSk7XG59XG5cbmZ1bmN0aW9uIFlHQ19TdWJtaXR0aW5nQ29udGFjdEZvcm0oZSkge1xuICBlLnByZXZlbnREZWZhdWx0KCk7XG5cbiAgdmFyIGVsZV9mb3JtID0galF1ZXJ5KHRoaXMpO1xuICB2YXIgZm9ybURhdGEgPSBnZXRfZm9ybV9kYXRhKGVsZV9mb3JtWzBdKTtcbiAgXG4gIHl0X3NldF9sZWFkX2NvbW1vbl9pbnB1dHMoZm9ybURhdGEpO1xuXG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5hdHRyKCdkaXNhYmxlZCcsICdkaXNhYmxlZCcpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuZGF0YSgnb2ctdmFsJywgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbCgpKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbCgnUHJvY2Vzc2luZy4uLicpO1xuXG4gIF9ZQkEuc2VuZF9sZWFkKHsnbGFiZWwnOiAnU2VuZEdlbmVyYWxDb250YWN0JywgJ2Zvcm1fZGF0YSc6IGZvcm1EYXRhfSkudGhlbihmdW5jdGlvbihyX2RhdGEpIHtcblxuICAgIFN1Ym1pdHRpbmdDb250YWN0Rm9ybV9TdWNjZXNzKHJfZGF0YSwgZWxlX2Zvcm0pO1xuXG4gICAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG4gICAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbChqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuZGF0YSgnb2ctdmFsJykpO1xuXG4gIH0pO1xufVxuXG5mdW5jdGlvbiBTdWJtaXR0aW5nQ29udGFjdEZvcm1fU3VjY2VzcyhyLCBlbGVfZm9ybSkge1xuXG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuXG4gIGlmICh0eXBlb2Ygci5lcnJvciAhPSAndW5kZWZpbmVkJykge1xuICAgIGFsZXJ0KHIuZXJyb3IpO1xuICB9XG4gIGVsc2UgaWYgKHIuUmVzcG9uc2VDb2RlID09IDAgfHwgci5SZXNwb25zZSA9PSAnU1BBTScpIHtcbiAgICBpZiAodHlwZW9mIF9ZQ1QgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgIF9ZQ1QuZm9ybVN1Ym1pdCggalF1ZXJ5KCdpbnB1dFt0eXBlPWVtYWlsXScsIGVsZV9mb3JtKS52YWwoKSwgci5FeHRyYURhdGEgKTtcbiAgICB9XG5cbiAgICBqUXVlcnkoJy5oaWRlLWFmdGVyLXN1Ym1pdCcsIGVsZV9mb3JtKS5oaWRlKCk7XG4gICAgXG4gICAgalF1ZXJ5KCcuZm9ybS1zdWNjZXNzLW1lc3NhZ2UnLCBlbGVfZm9ybSkuc2hvdygpO1xuXG4gIH1cblxufVxuXG5mdW5jdGlvbiBHQV9UcmFja1RlbGUoVmVzc2VsSUQsIGtpbmQpIHtcbiAgeXRfZ2FfZXZlbnQoJ1RlbGVwaG9uZScsICdDbGljaycsIGtpbmQsICcnKTsgXG4gIC8veXRfZ2FfZXZlbnQoJ3NlbmQnLCAnZXZlbnQnLCBWZXNzZWxJRCwgJ1RlbGVwaG9uZScsIGtpbmQpO1xuICBcbiAgaWYgKHR5cGVvZiBfWUNUICE9ICd1bmRlZmluZWQnKSB7XG4gICAgX1lDVC5mb3JzYWxlLnBob25lKFZlc3NlbElEKTtcbiAgfVxufVxuXG5mdW5jdGlvbiBZVF9TdWJtaXRDaGFydGVyTGVhZChlKSB7XG4gIGUucHJldmVudERlZmF1bHQoKTtcbiAgXG4gIHZhciBlbGVfZm9ybSA9IGpRdWVyeSh0aGlzKTtcbiAgdmFyIGZvcm1EYXRhID0gZ2V0X2Zvcm1fZGF0YShlbGVfZm9ybVswXSk7XG5cbiAgeXRfc2V0X2xlYWRfY29tbW9uX2lucHV0cyhmb3JtRGF0YSk7XG5cbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmF0dHIoJ2Rpc2FibGVkJywgJ2Rpc2FibGVkJyk7XG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5kYXRhKCdvZy12YWwnLCBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCkpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkudmFsKCdQcm9jZXNzaW5nLi4uJyk7XG5cbiAgeXRfZ2FfZXZlbnQoJ0NoYXJ0ZXIgTGVhZCcsICdTdWJtaXR0ZWQnLCAnRm9ybSBTdWJtaXQgQ2xpY2tlZCcsIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuXG4gIF9ZQkEuc2VuZF9sZWFkKHsnbGFiZWwnOiAnU2VuZENoYXJ0ZXJMZWFkJywgJ2Zvcm1fZGF0YSc6IGZvcm1EYXRhfSkudGhlbihmdW5jdGlvbihyX2RhdGEpIHtcblxuICAgIFN1Ym1pdHRpbmdDb250YWN0Rm9ybV9TdWNjZXNzKHJfZGF0YSwgZWxlX2Zvcm0pO1xuXG4gICAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnJlbW92ZUF0dHIoJ2Rpc2FibGVkJyk7XG4gICAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbChqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuZGF0YSgnb2ctdmFsJykpO1xuXG4gICAgaWYgKHJfZGF0YS5SZXNwb25zZUNvZGUgPT0gMCkge1xuICAgICAgeXRfZ2FfZXZlbnQoJ0NoYXJ0ZXIgTGVhZCcsICdTdWNjZXNzJywgJ1N1Y2Nlc3NmdWxsIENoYXJ0ZXIgTGVhZCcsIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuXG4gICAgICBpZiAoalF1ZXJ5KCdpbnB1dFtuYW1lPW93bmVyX29yX2luZHVzdHJ5XTpjaGVja2VkJywgZWxlX2Zvcm0pLnZhbCgpID09ICdvd25lcicpIHtcbiAgICAgICAgeXRfZ2FfZXZlbnQoJ0NoYXJ0ZXIgTGVhZCcsICdPd25lciBPUiBJbmR1c3RyeScsICdPd25lcicsICcnKTtcbiAgICAgIH1cbiAgICAgIGVsc2UgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnaW5kdXN0cnknKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdDaGFydGVyIExlYWQnLCAnT3duZXIgT1IgSW5kdXN0cnknLCAnSW5kdXN0cnknLCAnJyk7XG4gICAgICB9XG4gICAgfVxuICAgIGVsc2UgaWYgKHR5cGVvZiByX2RhdGEuc3BhbV9ha2kgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdWZXNzZWwgTGVhZCcsICdBS0kgU1BBTScsICdBa2kgU3BhbSBSZXR1cm4gSXNzdWUnLCAgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7XG4gICAgfVxuICAgIGVsc2Uge1xuICAgICAgeXRfZ2FfZXZlbnQoJ0NoYXJ0ZXIgTGVhZCcsICdVbmtub3cgSXNzdWUnLCAnQm9zcyBSZXR1cm4gSXNzdWUnLCBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcbiAgICB9XG5cbiAgfSk7XG59XG5cbmZ1bmN0aW9uIFlUX1N1Ym1pdENoYXJ0ZXJGdWxsU3BlY0xlYWQoZSkge1xuICBlLnByZXZlbnREZWZhdWx0KCk7XG4gIFxuICB2YXIgZWxlX2Zvcm0gPSBqUXVlcnkodGhpcyk7XG4gIHZhciBmb3JtRGF0YSA9IGdldF9mb3JtX2RhdGEoZWxlX2Zvcm1bMF0pO1xuXG4gIHl0X3NldF9sZWFkX2NvbW1vbl9pbnB1dHMoZm9ybURhdGEpO1xuXG4gIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5hdHRyKCdkaXNhYmxlZCcsICdkaXNhYmxlZCcpO1xuICBqUXVlcnkoJ1t0eXBlPXN1Ym1pdF0nLCBlbGVfZm9ybSkuZGF0YSgnb2ctdmFsJywgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbCgpKTtcbiAgalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLnZhbCgnUHJvY2Vzc2luZy4uLicpO1xuXG4gIHl0X2dhX2V2ZW50KCdDaGFydGVyIExlYWQnLCAnU3VibWl0dGVkJywgJ0Zvcm0gU3VibWl0IENsaWNrZWQnLCBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcblxuICBfWUJBLnNlbmRfbGVhZCh7J2xhYmVsJzogJ1NlbmRDaGFydGVyTGVhZCcsICdmb3JtX2RhdGEnOiBmb3JtRGF0YX0pLnRoZW4oZnVuY3Rpb24ocl9kYXRhKSB7XG5cbiAgICBTdWJtaXR0aW5nQ29udGFjdEZvcm1fU3VjY2VzcyhyX2RhdGEsIGVsZV9mb3JtKTtcblxuICAgIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS5yZW1vdmVBdHRyKCdkaXNhYmxlZCcpO1xuICAgIGpRdWVyeSgnW3R5cGU9c3VibWl0XScsIGVsZV9mb3JtKS52YWwoalF1ZXJ5KCdbdHlwZT1zdWJtaXRdJywgZWxlX2Zvcm0pLmRhdGEoJ29nLXZhbCcpKTtcblxuICAgIGlmIChyX2RhdGEuUmVzcG9uc2VDb2RlID09IDApIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdDaGFydGVyIExlYWQnLCAnU3VjY2VzcycsICdTdWNjZXNzZnVsbCBDaGFydGVyIExlYWQnLCBlbGVfZm9ybS5hdHRyKCdpZCcpK1wiX1wiK2Zvcm1EYXRhLlZlc3NlbElEKTtcblxuICAgICAgaWYgKGpRdWVyeSgnaW5wdXRbbmFtZT1vd25lcl9vcl9pbmR1c3RyeV06Y2hlY2tlZCcsIGVsZV9mb3JtKS52YWwoKSA9PSAnb3duZXInKSB7XG4gICAgICAgIHl0X2dhX2V2ZW50KCdDaGFydGVyIExlYWQnLCAnT3duZXIgT1IgSW5kdXN0cnknLCAnT3duZXInLCAnJyk7XG4gICAgICB9XG4gICAgICBlbHNlIGlmIChqUXVlcnkoJ2lucHV0W25hbWU9b3duZXJfb3JfaW5kdXN0cnldOmNoZWNrZWQnLCBlbGVfZm9ybSkudmFsKCkgPT0gJ2luZHVzdHJ5Jykge1xuICAgICAgICB5dF9nYV9ldmVudCgnQ2hhcnRlciBMZWFkJywgJ093bmVyIE9SIEluZHVzdHJ5JywgJ0luZHVzdHJ5JywgJycpO1xuICAgICAgfVxuICAgIH1cbiAgICBlbHNlIGlmICh0eXBlb2Ygcl9kYXRhLnNwYW1fYWtpICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICB5dF9nYV9ldmVudCgnVmVzc2VsIExlYWQnLCAnQUtJIFNQQU0nLCAnQWtpIFNwYW0gUmV0dXJuIElzc3VlJywgIGVsZV9mb3JtLmF0dHIoJ2lkJykrXCJfXCIrZm9ybURhdGEuVmVzc2VsSUQpO1xuICAgIH1cbiAgICBlbHNlIHtcbiAgICAgIHl0X2dhX2V2ZW50KCdDaGFydGVyIExlYWQnLCAnVW5rbm93IElzc3VlJywgJ0Jvc3MgUmV0dXJuIElzc3VlJywgZWxlX2Zvcm0uYXR0cignaWQnKStcIl9cIitmb3JtRGF0YS5WZXNzZWxJRCk7XG4gICAgfVxuXG4gIH0pO1xufVxuXG4vLyBORVcgQVRUQUNIXG5qUXVlcnkoZG9jdW1lbnQpLnJlYWR5KGZ1bmN0aW9uKCkge1xuICAvLyBWRVNTRUwgTEVBRCBGT1JNXG4gIGpRdWVyeSgnLmNvbnRhY3QtYnJva2VyLWZvcm0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG4gICAgeXRfcmVzdG9yZV9sZWFkX2NvbW1vbl9pbnB1dHMoalF1ZXJ5KHRoaXMpKTtcblxuICAgIGpRdWVyeSh0aGlzKS5vbignc3VibWl0JywgWVRfU3VibWl0VmVzc2VsTGVhZCk7XG5cbiAgfSk7XG5cbiAgLy8gRlVMTCBTUEVDIFZFU1NFTCBMRUFEIEZPUk1cbiAgalF1ZXJ5KCcuY29udGFjdC1icm9rZXItYWJvdXQtZnVsbC1zcGVjLWZvcm0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG4gICAgeXRfcmVzdG9yZV9sZWFkX2NvbW1vbl9pbnB1dHMoalF1ZXJ5KHRoaXMpKTtcblxuICAgIGpRdWVyeSh0aGlzKS5vbignc3VibWl0JywgWVRfU3VibWl0VmVzc2VsTGVhZEFib3V0RnVsbFNwZWMpO1xuXG4gIH0pO1xuXG4gIC8vIEJST0NIVVJFIFZFU1NFTCBMRUFEIEZPUk1cbiAgalF1ZXJ5KCcuY29udGFjdC13YW50ZWQtZm9yc2FsZS1icm9jaHVyZScpLmVhY2goZnVuY3Rpb24oKSB7XG5cbiAgICB5dF9yZXN0b3JlX2xlYWRfY29tbW9uX2lucHV0cyhqUXVlcnkodGhpcykpO1xuXG4gICAgalF1ZXJ5KHRoaXMpLm9uKCdzdWJtaXQnLCBZVF9TdWJtaXRWZXNzZWxCcm9jaHVyZUxlYWQpO1xuXG4gIH0pO1xuXG4gIC8vIFZFU1NFTCBMRUFEIC0gU0hPV0lORyAtIEZPUk1cbiAgalF1ZXJ5KCcuY29udGFjdC1icm9rZXItYWJvdXQtc2hvd2luZycpLmVhY2goZnVuY3Rpb24oKSB7XG5cbiAgICB5dF9yZXN0b3JlX2xlYWRfY29tbW9uX2lucHV0cyhqUXVlcnkodGhpcykpO1xuXG4gICAgalF1ZXJ5KHRoaXMpLm9uKCdzdWJtaXQnLCBZVF9TdWJtaXRWZXNzZWxMZWFkU2hvd2luZyk7XG5cbiAgfSk7XG5cbiAgLy8gVkVTU0VMIExFQUQgLSBRdWxpZmllZCAtIEZPUk1cbiAgalF1ZXJ5KCcuY29udGFjdC1xdWxpZmllZC1icm9rZXInKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG4gICAgeXRfcmVzdG9yZV9sZWFkX2NvbW1vbl9pbnB1dHMoalF1ZXJ5KHRoaXMpKTtcblxuICAgIGpRdWVyeSh0aGlzKS5vbignc3VibWl0JywgWVRfU3VibWl0VmVzc2VsTGVhZFF1bGlmaWVkQnJva2VyKTtcblxuICB9KTtcblxuICAvLyBCUk9LRVIgQ09OVEFDVFxuICBqUXVlcnkoJy5jb250YWN0LWJyb2tlci1mb3ItcmVhbC1mb3JtJykuZWFjaChmdW5jdGlvbigpIHtcblxuICAgIHl0X3Jlc3RvcmVfbGVhZF9jb21tb25faW5wdXRzKGpRdWVyeSh0aGlzKSk7XG5cbiAgICBqUXVlcnkodGhpcykub24oJ3N1Ym1pdCcsIFlUX1N1Ym1pdEJyb2tlckNvbnRhY3RGb3JtKTtcblxuICB9KTtcblxuICAvLyBCcm9rZXIgc29sZCBsZWFkXG4gIGpRdWVyeSgnLnNvbGQtY29udGFjdC1icm9rZXItZm9yLXJlYWwtZm9ybScpLmVhY2goZnVuY3Rpb24oKSB7XG5cbiAgICB5dF9yZXN0b3JlX2xlYWRfY29tbW9uX2lucHV0cyhqUXVlcnkodGhpcykpO1xuXG4gICAgalF1ZXJ5KHRoaXMpLm9uKCdzdWJtaXQnLCBZVF9TdWJtaXRTb2xkQ29udGFjdEZvcm0pO1xuXG4gIH0pO1xuXG4gIC8vIENIQVJURVIgTEVBRCBGT1JNXG4gIGpRdWVyeSgnLmNoYXJ0ZXItbGVhZC1mb3JtJykuZWFjaChmdW5jdGlvbigpIHtcblxuICAgIHl0X3Jlc3RvcmVfbGVhZF9jb21tb25faW5wdXRzKGpRdWVyeSh0aGlzKSk7XG5cbiAgICBqUXVlcnkodGhpcykub24oJ3N1Ym1pdCcsIFlUX1N1Ym1pdENoYXJ0ZXJMZWFkKTtcblxuICB9KTtcblxuICAvLyBDSEFSVEVSIEZVTEwgU1BFQyBMRUFEIEZPUk1cbiAgalF1ZXJ5KCcuY2hhcnRlci1mdWxsLXNwZWMtbGVhZC1mb3JtJykuZWFjaChmdW5jdGlvbigpIHtcblxuICAgIHl0X3Jlc3RvcmVfbGVhZF9jb21tb25faW5wdXRzKGpRdWVyeSh0aGlzKSk7XG5cbiAgICBqUXVlcnkodGhpcykub24oJ3N1Ym1pdCcsIFlUX1N1Ym1pdENoYXJ0ZXJMZWFkKTtcblxuICB9KTtcblxuICAvLyBHRU5FUkFMIENPTlRBQ1QgRk9STVxuICBqUXVlcnkoJy55YXRjby1nZW5lcmFsLWNvbnRhY3QtZm9ybScpLmVhY2goZnVuY3Rpb24oKSB7XG5cbiAgICB5dF9yZXN0b3JlX2xlYWRfY29tbW9uX2lucHV0cyhqUXVlcnkodGhpcykpO1xuXG4gICAgalF1ZXJ5KHRoaXMpLm9uKCdzdWJtaXQnLCBZR0NfU3VibWl0dGluZ0NvbnRhY3RGb3JtKTtcblxuICB9KTtcblxuICAvLyBTRVJWSUNFUyBNTFMgQ09OVEFDVCBGT1JNXG4gIGpRdWVyeSgnLnlhdGNvLXNlcnZpY2VzLW1scy1jb250YWN0LWZvcm0nKS5lYWNoKGZ1bmN0aW9uKCkge1xuXG4gICAgeXRfcmVzdG9yZV9sZWFkX2NvbW1vbl9pbnB1dHMoalF1ZXJ5KHRoaXMpKTtcblxuICAgIGpRdWVyeSh0aGlzKS5vbignc3VibWl0JywgWVRfU3VibWl0U2VydmljZU1sc0NvbnRlbnRGb3JtKTtcblxuICB9KTtcblxufSk7XG4iXX0=
