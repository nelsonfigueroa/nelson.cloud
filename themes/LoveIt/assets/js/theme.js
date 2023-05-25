"use strict";

// GoatCounter: https://www.goatcounter.com
// This file (and *only* this file) is released under the ISC license:
// https://opensource.org/licenses/ISC
window.goatcounter = {endpoint: 'https://nelsonfigueroa.goatcounter.com/count'}
;(function() {
	'use strict';

	if (window.goatcounter && window.goatcounter.vars)  // Compatibility with very old version; do not use.
		window.goatcounter = window.goatcounter.vars
	else
		window.goatcounter = window.goatcounter || {}

	// Load settings from data-goatcounter-settings.
	var s = document.querySelector('script[data-goatcounter]')
	if (s && s.dataset.goatcounterSettings) {
		try         { var set = JSON.parse(s.dataset.goatcounterSettings) }
		catch (err) { console.error('invalid JSON in data-goatcounter-settings: ' + err) }
		for (var k in set)
			if (['no_onload', 'no_events', 'allow_local', 'allow_frame', 'path', 'title', 'referrer', 'event'].indexOf(k) > -1)
				window.goatcounter[k] = set[k]
	}

	var enc = encodeURIComponent

	// Get all data we're going to send off to the counter endpoint.
	var get_data = function(vars) {
		var data = {
			p: (vars.path     === undefined ? goatcounter.path     : vars.path),
			r: (vars.referrer === undefined ? goatcounter.referrer : vars.referrer),
			t: (vars.title    === undefined ? goatcounter.title    : vars.title),
			e: !!(vars.event || goatcounter.event),
			s: [window.screen.width, window.screen.height, (window.devicePixelRatio || 1)],
			b: is_bot(),
			q: location.search,
		}

		var rcb, pcb, tcb  // Save callbacks to apply later.
		if (typeof(data.r) === 'function') rcb = data.r
		if (typeof(data.t) === 'function') tcb = data.t
		if (typeof(data.p) === 'function') pcb = data.p

		if (is_empty(data.r)) data.r = document.referrer
		if (is_empty(data.t)) data.t = document.title
		if (is_empty(data.p)) data.p = get_path()

		if (rcb) data.r = rcb(data.r)
		if (tcb) data.t = tcb(data.t)
		if (pcb) data.p = pcb(data.p)
		return data
	}

	// Check if a value is "empty" for the purpose of get_data().
	var is_empty = function(v) { return v === null || v === undefined || typeof(v) === 'function' }

	// See if this looks like a bot; there is some additional filtering on the
	// backend, but these properties can't be fetched from there.
	var is_bot = function() {
		// Headless browsers are probably a bot.
		var w = window, d = document
		if (w.callPhantom || w._phantom || w.phantom)
			return 150
		if (w.__nightmare)
			return 151
		if (d.__selenium_unwrapped || d.__webdriver_evaluate || d.__driver_evaluate)
			return 152
		if (navigator.webdriver)
			return 153
		return 0
	}

	// Object to urlencoded string, starting with a ?.
	var urlencode = function(obj) {
		var p = []
		for (var k in obj)
			if (obj[k] !== '' && obj[k] !== null && obj[k] !== undefined && obj[k] !== false)
				p.push(enc(k) + '=' + enc(obj[k]))
		return '?' + p.join('&')
	}

	// Show a warning in the console.
	var warn = function(msg) {
		if (console && 'warn' in console)
			console.warn('goatcounter: ' + msg)
	}

	// Get the endpoint to send requests to.
	var get_endpoint = function() {
		var s = document.querySelector('script[data-goatcounter]')
		if (s && s.dataset.goatcounter)
			return s.dataset.goatcounter
		return (goatcounter.endpoint || window.counter)  // counter is for compat; don't use.
	}

	// Get current path.
	var get_path = function() {
		var loc = location,
			c = document.querySelector('link[rel="canonical"][href]')
		if (c) {  // May be relative or point to different domain.
			var a = document.createElement('a')
			a.href = c.href
			if (a.hostname.replace(/^www\./, '') === location.hostname.replace(/^www\./, ''))
				loc = a
		}
		return (loc.pathname + loc.search) || '/'
	}

	// Run function after DOM is loaded.
	var on_load = function(f) {
		if (document.body === null)
			document.addEventListener('DOMContentLoaded', function() { f() }, false)
		else
			f()
	}

	// Filter some requests that we (probably) don't want to count.
	goatcounter.filter = function() {
		if ('visibilityState' in document && document.visibilityState === 'prerender')
			return 'visibilityState'
		if (!goatcounter.allow_frame && location !== parent.location)
			return 'frame'
		if (!goatcounter.allow_local && location.hostname.match(/(localhost$|^127\.|^10\.|^172\.(1[6-9]|2[0-9]|3[0-1])\.|^192\.168\.|^0\.0\.0\.0$)/))
			return 'localhost'
		if (!goatcounter.allow_local && location.protocol === 'file:')
			return 'localfile'
		if (localStorage && localStorage.getItem('skipgc') === 't')
			return 'disabled with #toggle-goatcounter'
		return false
	}

	// Get URL to send to GoatCounter.
	window.goatcounter.url = function(vars) {
		var data = get_data(vars || {})
		if (data.p === null)  // null from user callback.
			return
		data.rnd = Math.random().toString(36).substr(2, 5)  // Browsers don't always listen to Cache-Control.

		var endpoint = get_endpoint()
		if (!endpoint)
			return warn('no endpoint found')

		return endpoint + urlencode(data)
	}

	// Count a hit.
	window.goatcounter.count = function(vars) {
		var f = goatcounter.filter()
		if (f)
			return warn('not counting because of: ' + f)

		var url = goatcounter.url(vars)
		if (!url)
			return warn('not counting because path callback returned null')

		if (navigator.sendBeacon)
			navigator.sendBeacon(url)
		else {  // Fallback for (very) old browsers.
			var img = document.createElement('img')
			img.src = url
			img.style.position = 'absolute'  // Affect layout less.
			img.style.bottom = '0px'
			img.style.width = '1px'
			img.style.height = '1px'
			img.loading = 'eager'
			img.setAttribute('alt', '')
			img.setAttribute('aria-hidden', 'true')

			var rm = function() { if (img && img.parentNode) img.parentNode.removeChild(img) }
			img.addEventListener('load', rm, false)
			document.body.appendChild(img)
		}
	}

	// Get a query parameter.
	window.goatcounter.get_query = function(name) {
		var s = location.search.substr(1).split('&')
		for (var i = 0; i < s.length; i++)
			if (s[i].toLowerCase().indexOf(name.toLowerCase() + '=') === 0)
				return s[i].substr(name.length + 1)
	}

	// Track click events.
	window.goatcounter.bind_events = function() {
		if (!document.querySelectorAll)  // Just in case someone uses an ancient browser.
			return

		var send = function(elem) {
			return function() {
				goatcounter.count({
					event:    true,
					path:     (elem.dataset.goatcounterClick || elem.name || elem.id || ''),
					title:    (elem.dataset.goatcounterTitle || elem.title || (elem.innerHTML || '').substr(0, 200) || ''),
					referrer: (elem.dataset.goatcounterReferrer || elem.dataset.goatcounterReferral || ''),
				})
			}
		}

		Array.prototype.slice.call(document.querySelectorAll("*[data-goatcounter-click]")).forEach(function(elem) {
			if (elem.dataset.goatcounterBound)
				return
			var f = send(elem)
			elem.addEventListener('click', f, false)
			elem.addEventListener('auxclick', f, false)  // Middle click.
			elem.dataset.goatcounterBound = 'true'
		})
	}

	// Add a "visitor counter" frame or image.
	window.goatcounter.visit_count = function(opt) {
		on_load(function() {
			opt        = opt        || {}
			opt.type   = opt.type   || 'html'
			opt.append = opt.append || 'body'
			opt.path   = opt.path   || get_path()
			opt.attr   = opt.attr   || {width: '200', height: (opt.no_branding ? '60' : '80')}

			opt.attr['src'] = get_endpoint() + 'er/' + enc(opt.path) + '.' + enc(opt.type) + '?'
			if (opt.no_branding) opt.attr['src'] += '&no_branding=1'
			if (opt.style)       opt.attr['src'] += '&style=' + enc(opt.style)
			if (opt.start)       opt.attr['src'] += '&start=' + enc(opt.start)
			if (opt.end)         opt.attr['src'] += '&end='   + enc(opt.end)

			var tag = {png: 'img', svg: 'img', html: 'iframe'}[opt.type]
			if (!tag)
				return warn('visit_count: unknown type: ' + opt.type)

			if (opt.type === 'html') {
				opt.attr['frameborder'] = '0'
				opt.attr['scrolling']   = 'no'
			}

			var d = document.createElement(tag)
			for (var k in opt.attr)
				d.setAttribute(k, opt.attr[k])

			var p = document.querySelector(opt.append)
			if (!p)
				return warn('visit_count: append not found: ' + opt.append)
			p.appendChild(d)
		})
	}

	// Make it easy to skip your own views.
	if (location.hash === '#toggle-goatcounter') {
		if (localStorage.getItem('skipgc') === 't') {
			localStorage.removeItem('skipgc', 't')
			alert('GoatCounter tracking is now ENABLED in this browser.')
		}
		else {
			localStorage.setItem('skipgc', 't')
			alert('GoatCounter tracking is now DISABLED in this browser until ' + location + ' is loaded again.')
		}
	}

	if (!goatcounter.no_onload)
		on_load(function() {
			// 1. Page is visible, count request.
			// 2. Page is not yet visible; wait until it switches to 'visible' and count.
			// See #487
			if (!('visibilityState' in document) || document.visibilityState === 'visible')
				goatcounter.count()
			else {
				var f = function(e) {
					if (document.visibilityState !== 'visible')
						return
					document.removeEventListener('visibilitychange', f)
					goatcounter.count()
				}
				document.addEventListener('visibilitychange', f)
			}

			if (!goatcounter.no_events)
				goatcounter.bind_events()
		})
})();
// end goatcounter

function _objectDestructuringEmpty(obj) { if (obj == null) throw new TypeError("Cannot destructure undefined"); }

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e2) { throw _e2; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e3) { didErr = true; err = _e3; }, f: function f() { try { if (!normalCompletion && it.return != null) it.return(); } finally { if (didErr) throw err; } } }; }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); Object.defineProperty(Constructor, "prototype", { writable: false }); return Constructor; }

var Util = /*#__PURE__*/function () {
  function Util() {
    _classCallCheck(this, Util);
  }

  _createClass(Util, [{
    key: "forEach",
    value: function forEach(elements, handler) {
      elements = elements || [];

      for (var i = 0; i < elements.length; i++) {
        handler(elements[i]);
      }
    }
  }, {
    key: "getScrollTop",
    value: function getScrollTop() {
      return document.documentElement && document.documentElement.scrollTop || document.body.scrollTop;
    }
  }, {
    key: "isMobile",
    value: function isMobile() {
      return window.matchMedia('only screen and (max-width: 680px)').matches;
    }
  }, {
    key: "isTocStatic",
    value: function isTocStatic() {
      return window.matchMedia('only screen and (max-width: 960px)').matches;
    }
  }, {
    key: "animateCSS",
    value: function animateCSS(element, animation, reserved, callback) {
      var _element$classList;

      if (!Array.isArray(animation)) animation = [animation];

      (_element$classList = element.classList).add.apply(_element$classList, ['idk'].concat(_toConsumableArray(animation)));

      var handler = function handler() {
        var _element$classList2;

        (_element$classList2 = element.classList).remove.apply(_element$classList2, ['idk'].concat(_toConsumableArray(animation)));

        element.removeEventListener('animationend', handler);
        if (typeof callback === 'function') callback();
      };

      if (!reserved) element.addEventListener('animationend', handler, false);
    }
  }]);

  return Util;
}();

var Theme = /*#__PURE__*/function () {
  function Theme() {
    _classCallCheck(this, Theme);

    this.config = window.config;
    this.data = this.config.data;
    this.isDark = document.body.getAttribute('theme') === 'dark';
    this.util = new Util();
    this.newScrollTop = this.util.getScrollTop();
    this.oldScrollTop = this.newScrollTop;
    this.scrollEventSet = new Set();
    this.resizeEventSet = new Set();
    this.clickMaskEventSet = new Set();
    if (window.objectFitImages) objectFitImages();
  }

  _createClass(Theme, [{
    key: "initRaw",
    value: function initRaw() {
      var _this = this;

      this.util.forEach(document.querySelectorAll('[data-raw]'), function ($raw) {
        $raw.innerHTML = _this.data[$raw.id];
      });
    }
  }, {
    key: "initSVGIcon",
    value: function initSVGIcon() {
      this.util.forEach(document.querySelectorAll('[data-svg-src]'), function ($icon) {
        fetch($icon.getAttribute('data-svg-src')).then(function (response) {
          return response.text();
        }).then(function (svg) {
          var $temp = document.createElement('div');
          $temp.insertAdjacentHTML('afterbegin', svg);
          var $svg = $temp.firstChild;
          $svg.setAttribute('data-svg-src', $icon.getAttribute('data-svg-src'));
          $svg.classList.add('icon');
          var $titleElements = $svg.getElementsByTagName('title');
          if ($titleElements.length) $svg.removeChild($titleElements[0]);
          $icon.parentElement.replaceChild($svg, $icon);
        }).catch(function (err) {
          console.error(err);
        });
      });
    }
  }, {
    key: "initMenuMobile",
    value: function initMenuMobile() {
      var $menuToggleMobile = document.getElementById('menu-toggle-mobile');
      var $menuMobile = document.getElementById('menu-mobile');
      $menuToggleMobile.addEventListener('click', function () {
        $menuToggleMobile.classList.toggle('active');
        $menuMobile.classList.toggle('active');
      }, false);

      this._menuMobileOnClickMask = this._menuMobileOnClickMask || function () {
        $menuToggleMobile.classList.remove('active');
        $menuMobile.classList.remove('active');
      };

      this.clickMaskEventSet.add(this._menuMobileOnClickMask);
    }
  }, {
    key: "initDetails",
    value: function initDetails() {
      this.util.forEach(document.getElementsByClassName('details'), function ($details) {
        var $summary = $details.getElementsByClassName('details-summary')[0];
        $summary.addEventListener('click', function () {
          $details.classList.toggle('open');
        }, false);
      });
    }
  }, {
    key: "initHighlight",
    value: function initHighlight() {
      var _this5 = this;

      this.util.forEach(document.querySelectorAll('.highlight > pre.chroma'), function ($preChroma) {
        var $chroma = document.createElement('div');
        $chroma.className = $preChroma.className;
        var $table = document.createElement('table');
        $chroma.appendChild($table);
        var $tbody = document.createElement('tbody');
        $table.appendChild($tbody);
        var $tr = document.createElement('tr');
        $tbody.appendChild($tr);
        var $td = document.createElement('td');
        $tr.appendChild($td);
        $preChroma.parentElement.replaceChild($chroma, $preChroma);
        $td.appendChild($preChroma);
      });
      this.util.forEach(document.querySelectorAll('.highlight > .chroma'), function ($chroma) {
        var $codeElements = $chroma.querySelectorAll('pre.chroma > code');

        if ($codeElements.length) {
          var $code = $codeElements[$codeElements.length - 1];
          var $header = document.createElement('div');
          $header.className = 'code-header ' + $code.className.toLowerCase();
          var $title = document.createElement('span');
          $title.classList.add('code-title');
          $title.addEventListener('click', function () {
            $chroma.classList.toggle('open');
          }, false);
          $header.appendChild($title);
          var $ellipses = document.createElement('span');
          $ellipses.insertAdjacentHTML('afterbegin', '<i class="fas fa-ellipsis-h fa-fw" aria-hidden="true"></i>');
          $ellipses.classList.add('ellipses');
          $ellipses.addEventListener('click', function () {
            $chroma.classList.add('open');
          }, false);
          $header.appendChild($ellipses);
          var code = $code.innerText;
          if (_this5.config.code.maxShownLines < 0 || code.split('\n').length < _this5.config.code.maxShownLines + 2) $chroma.classList.add('open');

          $chroma.insertBefore($header, $chroma.firstChild);
        }
      });
    }
  }, {
    key: "initTable",
    value: function initTable() {
      this.util.forEach(document.querySelectorAll('.content table'), function ($table) {
        var $wrapper = document.createElement('div');
        $wrapper.className = 'table-wrapper';
        $table.parentElement.replaceChild($wrapper, $table);
        $wrapper.appendChild($table);
      });
    }
  }, {
    key: "initHeaderLink",
    value: function initHeaderLink() {
      for (var num = 1; num <= 6; num++) {
        this.util.forEach(document.querySelectorAll('.single .content > h' + num), function ($header) {
          $header.classList.add('headerLink');
          $header.insertAdjacentHTML('afterbegin', "<a href=\"#".concat($header.id, "\" class=\"header-mark\" aria-label=",$header.id," ></a>"));
        });
      }
    }
  }, {
    key: "initToc",
    value: function initToc() {
      var _this6 = this;

      var $tocCore = document.getElementById('TableOfContents');
      if ($tocCore === null) return;

      if (document.getElementById('toc-static').getAttribute('data-kept') || this.util.isTocStatic()) {
        var $tocContentStatic = document.getElementById('toc-content-static');

        if ($tocCore.parentElement !== $tocContentStatic) {
          $tocCore.parentElement.removeChild($tocCore);
          $tocContentStatic.appendChild($tocCore);
        }

        if (this._tocOnScroll) this.scrollEventSet.delete(this._tocOnScroll);
      } else {
        var $tocContentAuto = document.getElementById('toc-content-auto');

        if ($tocCore.parentElement !== $tocContentAuto) {
          $tocCore.parentElement.removeChild($tocCore);
          $tocContentAuto.appendChild($tocCore);
        }

        var $toc = document.getElementById('toc-auto');
        var $page = document.getElementsByClassName('page')[0];
        var rect = $page.getBoundingClientRect();
        $toc.style.left = "".concat(rect.left + rect.width + 20, "px");
        $toc.style.maxWidth = "".concat($page.getBoundingClientRect().left - 20, "px");
        $toc.style.visibility = 'visible';
        var $tocLinkElements = $tocCore.querySelectorAll('a:first-child');
        var $tocLiElements = $tocCore.getElementsByTagName('li');
        var $headerLinkElements = document.getElementsByClassName('headerLink');
        var headerIsFixed = document.body.getAttribute('data-header-desktop') !== 'normal';
        var headerHeight = document.getElementById('header-desktop').offsetHeight;
        var TOP_SPACING = 20 + (headerIsFixed ? headerHeight : 0);
        var minTocTop = $toc.offsetTop;
        var minScrollTop = minTocTop - TOP_SPACING + (headerIsFixed ? 0 : headerHeight);

        this._tocOnScroll = this._tocOnScroll || function () {
          var footerTop = document.getElementById('post-footer').offsetTop;
          var maxTocTop = footerTop - $toc.getBoundingClientRect().height;
          var maxScrollTop = maxTocTop - TOP_SPACING + (headerIsFixed ? 0 : headerHeight);

          if (_this6.newScrollTop < minScrollTop) {
            $toc.style.position = 'absolute';
            $toc.style.top = "".concat(minTocTop, "px");
          } else if (_this6.newScrollTop > maxScrollTop) {
            $toc.style.position = 'absolute';
            $toc.style.top = "".concat(maxTocTop, "px");
          } else {
            $toc.style.position = 'fixed';
            $toc.style.top = "".concat(TOP_SPACING, "px");
          }

          _this6.util.forEach($tocLinkElements, function ($tocLink) {
            $tocLink.classList.remove('active');
          });

          _this6.util.forEach($tocLiElements, function ($tocLi) {
            $tocLi.classList.remove('has-active');
          });

          var INDEX_SPACING = 20 + (headerIsFixed ? headerHeight : 0);
          var activeTocIndex = $headerLinkElements.length - 1;

          for (var i = 0; i < $headerLinkElements.length - 1; i++) {
            var thisTop = $headerLinkElements[i].getBoundingClientRect().top;
            var nextTop = $headerLinkElements[i + 1].getBoundingClientRect().top;

            if (i == 0 && thisTop > INDEX_SPACING || thisTop <= INDEX_SPACING && nextTop > INDEX_SPACING) {
              activeTocIndex = i;
              break;
            }
          }

          if (activeTocIndex !== -1) {
            $tocLinkElements[activeTocIndex].classList.add('active');
            var $parent = $tocLinkElements[activeTocIndex].parentElement;

            while ($parent !== $tocCore) {
              $parent.classList.add('has-active');
              $parent = $parent.parentElement.parentElement;
            }
          }
        };

        this._tocOnScroll();

        this.scrollEventSet.add(this._tocOnScroll);
      }
    }
  }, {
    // this is used for table of contents
    key: "onScroll",
    value: function onScroll() {
      var _this12 = this;

      var $headers = [];
      if (document.body.getAttribute('data-header-desktop') === 'auto') $headers.push(document.getElementById('header-desktop'));
      if (document.body.getAttribute('data-header-mobile') === 'auto') $headers.push(document.getElementById('header-mobile'));

      var $fixedButtons = document.getElementById('fixed-buttons');
      var ACCURACY = 20,
          MINIMUM = 100;
      window.addEventListener('scroll', function () {
        _this12.newScrollTop = _this12.util.getScrollTop();
        var scroll = _this12.newScrollTop - _this12.oldScrollTop;

        var isMobile = _this12.util.isMobile();

        if (_this12.newScrollTop > MINIMUM) {
          if  (!isMobile || scroll < -ACCURACY) {
            $fixedButtons.style.display = 'block';
          }
        } else {
          $fixedButtons.style.display = 'none';
        }

        var _iterator2 = _createForOfIteratorHelper(_this12.scrollEventSet),
            _step2;

        try {
          for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
            var event = _step2.value;
            event();
          }
        } catch (err) {
          _iterator2.e(err);
        } finally {
          _iterator2.f();
        }

        _this12.oldScrollTop = _this12.newScrollTop;
      }, false);
    }
  }, {
    key: "onResize",
    value: function onResize() {
      var _this13 = this;

      window.addEventListener('resize', function () {
        if (!_this13._resizeTimeout) {
          _this13._resizeTimeout = window.setTimeout(function () {
            _this13._resizeTimeout = null;

            var _iterator3 = _createForOfIteratorHelper(_this13.resizeEventSet),
                _step3;

            try {
              for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
                var event = _step3.value;
                event();
              }
            } catch (err) {
              _iterator3.e(err);
            } finally {
              _iterator3.f();
            }

            _this13.initToc();

          }, 100);
        }
      }, false);
    }
  }, {
    key: "onClickMask",
    value: function onClickMask() {
      var _this14 = this;

      document.getElementById('mask').addEventListener('click', function () {
        var _iterator4 = _createForOfIteratorHelper(_this14.clickMaskEventSet),
            _step4;

        try {
          for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
            var event = _step4.value;
            event();
          }
        } catch (err) {
          _iterator4.e(err);
        } finally {
          _iterator4.f();
        }
      }, false);
    }
  }, {
    key: "init",
    value: function init() {
      var _this15 = this;

      try {
        this.initRaw();
        this.initSVGIcon();
        this.initMenuMobile();
        this.initDetails();
        this.initHighlight();
        this.initTable();
        this.initHeaderLink();
      } catch (err) {
        console.error(err);
      }

      window.setTimeout(function () {
        _this15.initToc();

        _this15.onScroll();

        _this15.onResize();

        _this15.onClickMask();
      }, 100);
    }
  }]);

  return Theme;
}();

var themeInit = function themeInit() {
  var theme = new Theme();
  theme.init();
};

if (document.readyState !== 'loading') {
  themeInit();
} else {
  document.addEventListener('DOMContentLoaded', themeInit, false);
}
