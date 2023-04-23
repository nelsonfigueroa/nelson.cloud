"use strict";

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
    this.switchThemeEventSet = new Set();
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
    key: "initSwitchTheme",
    value: function initSwitchTheme() {
      var _this2 = this;

      this.util.forEach(document.getElementsByClassName('theme-switch'), function ($themeSwitch) {
        $themeSwitch.addEventListener('click', function () {
          if (document.body.getAttribute('theme') === 'dark') document.body.setAttribute('theme', 'light');else document.body.setAttribute('theme', 'dark');
          _this2.isDark = !_this2.isDark;
          window.localStorage && localStorage.setItem('theme', _this2.isDark ? 'dark' : 'light');

          var _iterator = _createForOfIteratorHelper(_this2.switchThemeEventSet),
              _step;

          try {
            for (_iterator.s(); !(_step = _iterator.n()).done;) {
              var event = _step.value;
              event();
            }
          } catch (err) {
            _iterator.e(err);
          } finally {
            _iterator.f();
          }
        }, false);
      });
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
          $title.insertAdjacentHTML('afterbegin', '<i class="arrow fas fa-chevron-right fa-fw" aria-hidden="true"></i>');
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
          var $copy = document.createElement('span');
          $copy.insertAdjacentHTML('afterbegin', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M224 0c-35.3 0-64 28.7-64 64V288c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H224zM64 160c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V384H304v64c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V224c0-8.8 7.2-16 16-16h64V160H64z"/></svg>');
          $copy.classList.add('copy');
          var code = $code.innerText;
          if (_this5.config.code.maxShownLines < 0 || code.split('\n').length < _this5.config.code.maxShownLines + 2) $chroma.classList.add('open');

          if (_this5.config.code.copyTitle) {
            $copy.setAttribute('data-clipboard-text', code);
            $copy.title = _this5.config.code.copyTitle;
            var clipboard = new ClipboardJS($copy);
            $header.appendChild($copy);
          }

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
          $header.insertAdjacentHTML('afterbegin', "<a href=\"#".concat($header.id, "\" class=\"header-mark\"></a>"));
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
    key: "initMath",
    value: function initMath() {
      if (this.config.math) renderMathInElement(document.body, this.config.math);
    }
  }, {
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

        _this12.util.forEach($headers, function ($header) {
          if (scroll > ACCURACY) {
            $header.classList.remove('animate__fadeInDown');

            _this12.util.animateCSS($header, ['animate__fadeOutUp', 'animate__faster'], true);
          } else if (scroll < -ACCURACY) {
            $header.classList.remove('animate__fadeOutUp');

            _this12.util.animateCSS($header, ['animate__fadeInDown', 'animate__faster'], true);
          }
        });

        if (_this12.newScrollTop > MINIMUM) {
          if (isMobile && scroll > ACCURACY) {
            $fixedButtons.classList.remove('animate__fadeIn');

            _this12.util.animateCSS($fixedButtons, ['animate__fadeOut', 'animate__faster'], true);
          } else if (!isMobile || scroll < -ACCURACY) {
            $fixedButtons.style.display = 'block';
            $fixedButtons.classList.remove('animate__fadeOut');

            _this12.util.animateCSS($fixedButtons, ['animate__fadeIn', 'animate__faster'], true);
          }
        } else {
          if (!isMobile) {
            $fixedButtons.classList.remove('animate__fadeIn');

            _this12.util.animateCSS($fixedButtons, ['animate__fadeOut', 'animate__faster'], true);
          }

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
        this.initSwitchTheme();
        this.initDetails();
        this.initHighlight();
        this.initTable();
        this.initHeaderLink();
        this.initMath();
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
