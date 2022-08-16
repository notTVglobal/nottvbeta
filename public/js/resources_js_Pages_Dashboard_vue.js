"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Dashboard_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang=js":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang=js ***!
  \*********************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Stores/VideoPlayerStore.js */ "./resources/js/Stores/VideoPlayerStore.js");
/* harmony import */ var _Stores_ChatStore_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/Stores/ChatStore.js */ "./resources/js/Stores/ChatStore.js");
/* harmony import */ var _Components_ResponsiveNavigationMenu__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Components/ResponsiveNavigationMenu */ "./resources/js/Components/ResponsiveNavigationMenu.vue");
/* harmony import */ var _Components_NavigationMenu__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/Components/NavigationMenu */ "./resources/js/Components/NavigationMenu.vue");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  __name: 'Dashboard',
  setup: function setup(__props, _ref) {
    var expose = _ref.expose;
    expose();
    var videoPlayer = (0,_Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_0__.useVideoPlayerStore)();
    var chat = (0,_Stores_ChatStore_js__WEBPACK_IMPORTED_MODULE_1__.useChatStore)();
    videoPlayer["class"] = "videoTopRight";
    videoPlayer.videoContainerClass = "videoContainerTopRight";
    videoPlayer.fullPage = false;
    videoPlayer.loggedIn = true;
    chat["class"] = "chatSmall"; // onload(videoPlayer.class = "videoTopRight")

    var __returned__ = {
      videoPlayer: videoPlayer,
      chat: chat,
      useVideoPlayerStore: _Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_0__.useVideoPlayerStore,
      useChatStore: _Stores_ChatStore_js__WEBPACK_IMPORTED_MODULE_1__.useChatStore,
      ResponsiveNavigationMenu: _Components_ResponsiveNavigationMenu__WEBPACK_IMPORTED_MODULE_2__["default"],
      NavigationMenu: _Components_NavigationMenu__WEBPACK_IMPORTED_MODULE_3__["default"]
    };
    Object.defineProperty(__returned__, '__isScriptSetup', {
      enumerable: false,
      value: true
    });
    return __returned__;
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Dashboard.vue?vue&type=template&id=097ba13b":
/*!**************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Dashboard.vue?vue&type=template&id=097ba13b ***!
  \**************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "sticky top-0 w-full nav-mask"
};
var _hoisted_2 = {
  "class": "place-self-center flex flex-col gap-y-3 md:pageWidth"
};
var _hoisted_3 = {
  "class": "bg-white text-black p-5 mb-10"
};

var _hoisted_4 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-3xl font-semibold pb-3"
}, "Dashboard", -1
/* HOISTED */
);

var _hoisted_5 = {
  "class": "grid grid-cols-1 lg:grid-cols-3 gap-4 my-3 m-auto p-1 text-black"
};

var _hoisted_6 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "p-5 bg-gray-200 rounded"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", {
  "class": "font-semibold text-xl"
}, "My Assignments"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", {
  "class": "mt-1"
}, "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.")], -1
/* HOISTED */
);

var _hoisted_7 = {
  "class": "p-5 bg-gray-200 rounded"
};

var _hoisted_8 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", {
  "class": "font-semibold text-xl mb-1"
}, "My Shows", -1
/* HOISTED */
);

var _hoisted_9 = {
  "class": ""
};

var _hoisted_10 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" Down The Rabbit Hole ");

var _hoisted_11 = {
  "class": ""
};

var _hoisted_12 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" News & Talk ");

var _hoisted_13 = {
  "class": "p-5 bg-gray-200 rounded"
};

var _hoisted_14 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h2", {
  "class": "font-semibold text-xl mb-1"
}, "My Teams", -1
/* HOISTED */
);

var _hoisted_15 = {
  "class": ""
};

var _hoisted_16 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" notTV Founders ");

var _hoisted_17 = {
  "class": ""
};

var _hoisted_18 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" notTV Street Team ");

var _hoisted_19 = {
  "class": ""
};

var _hoisted_20 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createTextVNode)(" RJG Productions ");

var _hoisted_21 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createStaticVNode)("<div class=\"mt-6 h-0.5 bg-gray-800\"></div><section class=\"grid grid-cols-1 mt-6 gap-2\"><div class=\"font-semibold text-2xl text-gray-800 px-2\"> Account Summary </div><div class=\"px-2\"> Account: 000000 </div><div class=\"grid grid-cols-2 border-2 pb-3\"><h2 class=\"bg-gray-800 text-white text-sm p-2 col-span-2\">Membership: 000000</h2><h2 class=\"bg-blue-400 font-semibold text-sm text-black px-2 mb-3 col-span-2\">Account Name</h2><p class=\"px-2\">Chequing</p><p class=\"px-2 justify-self-end\">0.00</p><p class=\"px-2\">Equity Shares</p><p class=\"px-2 justify-self-end\">10.00</p><p class=\"px-2\">Savings</p><p class=\"px-2 justify-self-end\">0.00</p><h2 class=\"bg-blue-400 font-semibold text-sm text-black my-3 px-2 col-span-2\">Team Accounts</h2><p class=\"px-2\">notTV Founders Shares</p><p class=\"px-2 justify-self-end\">0.00</p><p class=\"px-2\">notTV Street Team Shares</p><p class=\"px-2 justify-self-end\">0.00</p><p class=\"px-2\">RJG Production Shares</p><p class=\"px-2 justify-self-end\">0.00</p></div></section>", 2);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Head = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Head");

  var _component_Link = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Link");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Head, {
    title: "Dashboard"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["ResponsiveNavigationMenu"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["NavigationMenu"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [_hoisted_4, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("section", _hoisted_5, [_hoisted_6, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_7, [_hoisted_8, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_9, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    onClick: _cache[0] || (_cache[0] = function ($event) {
      return $setup.videoPlayer.makeVideoTopRight();
    }),
    href: "/teams/1",
    "class": "text-blue-800 hover:text-blue-400"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_10];
    }),
    _: 1
    /* STABLE */

  })]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_11, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    onClick: _cache[1] || (_cache[1] = function ($event) {
      return $setup.videoPlayer.makeVideoTopRight();
    }),
    href: "/teams/1",
    "class": "text-blue-800 hover:text-blue-400"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_12];
    }),
    _: 1
    /* STABLE */

  })])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_13, [_hoisted_14, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_15, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    onClick: _cache[2] || (_cache[2] = function ($event) {
      return $setup.videoPlayer.makeVideoTopRight();
    }),
    href: "/teams/1",
    "class": "text-blue-800 hover:text-blue-400"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_16];
    }),
    _: 1
    /* STABLE */

  })]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_17, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    onClick: _cache[3] || (_cache[3] = function ($event) {
      return $setup.videoPlayer.makeVideoTopRight();
    }),
    href: "/teams/1",
    "class": "text-blue-800 hover:text-blue-400"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_18];
    }),
    _: 1
    /* STABLE */

  })]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", _hoisted_19, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Link, {
    onClick: _cache[4] || (_cache[4] = function ($event) {
      return $setup.videoPlayer.makeVideoTopRight();
    }),
    href: "/teams/1",
    "class": "text-blue-800 hover:text-blue-400"
  }, {
    "default": (0,vue__WEBPACK_IMPORTED_MODULE_0__.withCtx)(function () {
      return [_hoisted_20];
    }),
    _: 1
    /* STABLE */

  })])])]), _hoisted_21])])], 64
  /* STABLE_FRAGMENT */
  );
}

/***/ }),

/***/ "./resources/js/Pages/Dashboard.vue":
/*!******************************************!*\
  !*** ./resources/js/Pages/Dashboard.vue ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Dashboard_vue_vue_type_template_id_097ba13b__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=template&id=097ba13b */ "./resources/js/Pages/Dashboard.vue?vue&type=template&id=097ba13b");
/* harmony import */ var _Dashboard_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=script&setup=true&lang=js */ "./resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang=js");
/* harmony import */ var _var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Dashboard_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Dashboard_vue_vue_type_template_id_097ba13b__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/Pages/Dashboard.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang=js":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang=js ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Dashboard.vue?vue&type=script&setup=true&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Dashboard.vue?vue&type=script&setup=true&lang=js");
 

/***/ }),

/***/ "./resources/js/Pages/Dashboard.vue?vue&type=template&id=097ba13b":
/*!************************************************************************!*\
  !*** ./resources/js/Pages/Dashboard.vue?vue&type=template&id=097ba13b ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_template_id_097ba13b__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Dashboard_vue_vue_type_template_id_097ba13b__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Dashboard.vue?vue&type=template&id=097ba13b */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Dashboard.vue?vue&type=template&id=097ba13b");


/***/ })

}]);