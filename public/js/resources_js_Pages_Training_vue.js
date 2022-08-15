"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_Training_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Training.vue?vue&type=script&setup=true&lang=js":
/*!********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Training.vue?vue&type=script&setup=true&lang=js ***!
  \********************************************************************************************************************************************************************************************************/
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
  __name: 'Training',
  setup: function setup(__props, _ref) {
    var expose = _ref.expose;
    expose();
    var videoPlayer = (0,_Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_0__.useVideoPlayerStore)();
    var chat = (0,_Stores_ChatStore_js__WEBPACK_IMPORTED_MODULE_1__.useChatStore)();
    videoPlayer["class"] = "videoTopRight";
    videoPlayer.videoContainerClass = "videoContainerTopRight";
    videoPlayer.fullPage = false;
    chat["class"] = "chatSmall";
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Training.vue?vue&type=template&id=4acbd283":
/*!*************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Training.vue?vue&type=template&id=4acbd283 ***!
  \*************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "sticky top-0 w-full nav-mask"
};

var _hoisted_2 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "place-self-center flex flex-col gap-y-3 pageWidth"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
  "class": "bg-white text-black p-5 mb-10"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("h1", {
  "class": "text-3xl pb-3 font-semibold"
}, "Training"), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("p", null, " Travis will create some training videos to help people create content in a higher broadcast quality. "), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("ul", {
  "class": "list-disc list-inside pt-4"
}, [/*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, " Field production "), /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("li", null, " Studio production ")])])], -1
/* HOISTED */
);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  var _component_Head = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Head");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Head, {
    title: "Training"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["ResponsiveNavigationMenu"]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["NavigationMenu"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("        <template #header>"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("            <h2 class=\"font-semibold text-xl text-gray-800 leading-tight\">"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("                Dashboard"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("            </h2>"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("        </template>"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("        <div class=\"py-12\">"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("            <div class=\"max-w-7xl mx-auto sm:px-6 lg:px-8\">"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("                <div class=\"bg-white overflow-hidden shadow-xl sm:rounded-lg\">"), _hoisted_2, (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("                </div>"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("            </div>"), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createCommentVNode)("        </div>")], 64
  /* STABLE_FRAGMENT */
  );
}

/***/ }),

/***/ "./resources/js/Pages/Training.vue":
/*!*****************************************!*\
  !*** ./resources/js/Pages/Training.vue ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Training_vue_vue_type_template_id_4acbd283__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Training.vue?vue&type=template&id=4acbd283 */ "./resources/js/Pages/Training.vue?vue&type=template&id=4acbd283");
/* harmony import */ var _Training_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Training.vue?vue&type=script&setup=true&lang=js */ "./resources/js/Pages/Training.vue?vue&type=script&setup=true&lang=js");
/* harmony import */ var _var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_Training_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_Training_vue_vue_type_template_id_4acbd283__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/Pages/Training.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/Pages/Training.vue?vue&type=script&setup=true&lang=js":
/*!****************************************************************************!*\
  !*** ./resources/js/Pages/Training.vue?vue&type=script&setup=true&lang=js ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Training_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Training_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Training.vue?vue&type=script&setup=true&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Training.vue?vue&type=script&setup=true&lang=js");
 

/***/ }),

/***/ "./resources/js/Pages/Training.vue?vue&type=template&id=4acbd283":
/*!***********************************************************************!*\
  !*** ./resources/js/Pages/Training.vue?vue&type=template&id=4acbd283 ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Training_vue_vue_type_template_id_4acbd283__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_Training_vue_vue_type_template_id_4acbd283__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./Training.vue?vue&type=template&id=4acbd283 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/Training.vue?vue&type=template&id=4acbd283");


/***/ })

}]);