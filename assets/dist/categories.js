webpackJsonp([0],[function(t,e,n){var s,a;s=n(2);var o=n(3);a=s=s||{},"object"!=typeof s.default&&"function"!=typeof s.default||(a=s=s.default),"function"==typeof a&&(a=a.options),a.render=o.render,a.staticRenderFns=o.staticRenderFns,t.exports=s},,function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:["categories","lang"]}},function(module,exports){module.exports={render:function(){with(this)return _h("div",[_h("div",{staticClass:"modal-header"},[_m(0)," ",_h("h4",{staticClass:"modal-title"},[_s(lang.reorder_button)])])," ",_h("div",{staticClass:"modal-body"},[_h("ul",[_l(categories,function(t){return _h("li",[_s(t.name)])})])])," ",_m(1)])},staticRenderFns:[function(){with(this)return _h("button",{staticClass:"close",attrs:{type:"button","data-dismiss":"modal","aria-hidden":"true"}},["×"])},function(){with(this)return _h("div",{staticClass:"modal-footer"},[_h("button",{staticClass:"btn btn-default",attrs:{type:"button","data-dismiss":"modal"}},["Cancel"])," ",_h("button",{staticClass:"btn btn-primary",attrs:{type:"button","data-dismiss":"modal"}},["Save"])])}]}},,function(t,e,n){"use strict";function s(t){return t&&t.__esModule?t:{default:t}}var a=n(1),o=s(a),r=n(0),i=s(r);$.fn.mountReorderComponent=function(t){var e=t.categories,n=t.lang;new o.default({el:$(this)[0],components:{"v-reorder":i.default},render:function(t){return t("v-reorder",{attrs:{categories:e,lang:n}},[])}})}}],[5]);