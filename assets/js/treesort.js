webpackJsonp([0,2],[
/* 0 */
/***/ function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__

/* script */
__vue_exports__ = __webpack_require__(2)

/* template */
var __vue_template__ = __webpack_require__(4)
__vue_options__ = __vue_exports__ = __vue_exports__ || {}
if (
  typeof __vue_exports__.default === "object" ||
  typeof __vue_exports__.default === "function"
) {
if (Object.keys(__vue_exports__).some(function (key) { return key !== "default" && key !== "__esModule" })) {console.error("named exports are not supported in *.vue files.")}
__vue_options__ = __vue_exports__ = __vue_exports__.default
}
if (typeof __vue_options__ === "function") {
  __vue_options__ = __vue_options__.options
}
__vue_options__.__file = "c:\\wamp\\www\\beeasyboards\\src\\plugins\\bedard\\shop\\controllers\\categories\\treesort\\treesort.vue"
__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4970e86e", __vue_options__)
  } else {
    hotAPI.reload("data-v-4970e86e", __vue_options__)
  }
})()}
if (__vue_options__.functional) {console.error("[vue-loader] treesort.vue: functional components are not supported and should be defined in plain js files using render functions.")}

module.exports = __vue_exports__


/***/ },
/* 1 */,
/* 2 */
/***/ function(module, exports, __webpack_require__) {

"use strict";
//
//
//
//
//
//
//
//
//

/* harmony default export */ exports["default"] = {
    methods: {
        onClick: function () {
            console.log('sdfsdf');
        }
    }
};

/***/ },
/* 3 */,
/* 4 */
/***/ function(module, exports, __webpack_require__) {

module.exports={render:function (){with(this) {
  return _h('a', {
    staticClass: "btn btn-default oc-icon-sitemap",
    attrs: {
      "href": "#"
    },
    on: {
      "click": function($event) {
        $event.preventDefault();
        onClick($event)
      }
    }
  }, ["\n    things!\n"])
}},staticRenderFns: []}
if (false) {
  module.hot.accept()
  if (module.hot.data) {
     require("vue-hot-reload-api").rerender("data-v-4970e86e", module.exports)
  }
}

/***/ },
/* 5 */
/***/ function(module, exports, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__treesort_vue__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__treesort_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__treesort_vue__);



$(function () {
    new __WEBPACK_IMPORTED_MODULE_0_vue___default.a({
        el: '[data-bedard-shop="categories-treesort"]',
        render: h => h(__WEBPACK_IMPORTED_MODULE_1__treesort_vue___default.a)
    });
});

/***/ }
],[5]);
//# sourceMappingURL=treesort.js.map