webpackJsonp([0],[
/* 0 */
/***/ function(module, exports, __webpack_require__) {

var __vue_exports__, __vue_options__

/* script */
__vue_exports__ = __webpack_require__(2)

/* template */
var __vue_template__ = __webpack_require__(3)
__vue_options__ = __vue_exports__ = __vue_exports__ || {}
if (
  typeof __vue_exports__.default === "object" ||
  typeof __vue_exports__.default === "function"
) {
__vue_options__ = __vue_exports__ = __vue_exports__.default
}
if (typeof __vue_options__ === "function") {
  __vue_options__ = __vue_options__.options
}

__vue_options__.render = __vue_template__.render
__vue_options__.staticRenderFns = __vue_template__.staticRenderFns

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
            console.log('Hello');
        }
    }
};

/***/ },
/* 3 */
/***/ function(module, exports) {

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
  }, ["\n    Reorder Categories\n"])
}},staticRenderFns: []}

/***/ },
/* 4 */
/***/ function(module, exports, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__treesort__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__treesort___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__treesort__);



$(() => {
    new __WEBPACK_IMPORTED_MODULE_0_vue___default.a({
        el: '[data-bedard-shop="categories-treesort"]',
        render: h => h(__WEBPACK_IMPORTED_MODULE_1__treesort___default.a)
    });
});

/***/ }
],[4]);
//# sourceMappingURL=treesort.js.map