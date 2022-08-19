"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_TermsOfService_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/TermsOfService.vue?vue&type=script&setup=true&lang=js":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/TermsOfService.vue?vue&type=script&setup=true&lang=js ***!
  \**************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Layouts_NoLayout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/Layouts/NoLayout */ "./resources/js/Layouts/NoLayout.vue");
/* harmony import */ var _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @inertiajs/inertia-vue3 */ "./node_modules/@inertiajs/inertia-vue3/dist/index.js");
/* harmony import */ var _Jetstream_AuthenticationCardLogo_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @/Jetstream/AuthenticationCardLogo.vue */ "./resources/js/Jetstream/AuthenticationCardLogo.vue");
/* harmony import */ var _Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @/Stores/VideoPlayerStore.js */ "./resources/js/Stores/VideoPlayerStore.js");

var __default__ = {
  layout: _Layouts_NoLayout__WEBPACK_IMPORTED_MODULE_0__["default"]
};



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/Object.assign(__default__, {
  __name: 'TermsOfService',
  props: {
    terms: String
  },
  setup: function setup(__props, _ref) {
    var expose = _ref.expose;
    expose();
    var videoPlayer = (0,_Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_3__.useVideoPlayerStore)();
    videoPlayer["class"] = "videoTopRight";
    videoPlayer.fullPage = false;
    var __returned__ = {
      videoPlayer: videoPlayer,
      NoLayout: _Layouts_NoLayout__WEBPACK_IMPORTED_MODULE_0__["default"],
      Head: _inertiajs_inertia_vue3__WEBPACK_IMPORTED_MODULE_1__.Head,
      JetAuthenticationCardLogo: _Jetstream_AuthenticationCardLogo_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
      useVideoPlayerStore: _Stores_VideoPlayerStore_js__WEBPACK_IMPORTED_MODULE_3__.useVideoPlayerStore
    };
    Object.defineProperty(__returned__, '__isScriptSetup', {
      enumerable: false,
      value: true
    });
    return __returned__;
  }
}));

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Layouts/NoLayout.vue?vue&type=template&id=7869ad4f":
/*!***************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Layouts/NoLayout.vue?vue&type=template&id=7869ad4f ***!
  \***************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

function render(_ctx, _cache) {
  var _component_Head = (0,vue__WEBPACK_IMPORTED_MODULE_0__.resolveComponent)("Head");

  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)(_component_Head, {
    title: _ctx.title
  }, null, 8
  /* PROPS */
  , ["title"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.renderSlot)(_ctx.$slots, "default")], 64
  /* STABLE_FRAGMENT */
  );
}

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/TermsOfService.vue?vue&type=template&id=63d45180":
/*!*******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/TermsOfService.vue?vue&type=template&id=63d45180 ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm-bundler.js");

var _hoisted_1 = {
  "class": "font-sans text-gray-900 antialiased"
};
var _hoisted_2 = {
  "class": "pt-4 bg-gray-100"
};
var _hoisted_3 = {
  "class": "min-h-screen flex flex-col items-center pt-6 sm:pt-0"
};
var _hoisted_4 = ["innerHTML"];

var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createStaticVNode)("<p><strong>notTV<u><br>WEBSITE TERMS AND CONDITIONS OF USE</u></strong></p><p>THESE WEBSITE TERMS AND CONDITIONS OF USE CONTAIN LEGAL OBLIGATIONS.  PLEASE READ THESE WEBSITE TERMS AND CONDITIONS OF USE BEFORE USING THIS WEBSITE.</p><p>Last Update: <strong>February 12, 2018</strong></p><h2>1. Introduction and Agreement</h2><p><strong>NOTTV LTD.</strong> (“<strong>notTV</strong>”) provides this website (the “<strong>Website</strong>”) as a venue to allow customers (the “<strong>Buyers</strong>”) who comply with these terms and conditions of use (these “<strong>Terms of Use</strong>”) to buy certain rights to articles, opinion pieces and other written materials, graphics, data, images, photographs, art, illustrations, animations and other content (the “<strong>Content</strong>”) within a set price format (the “<strong>Services</strong>”).</p><p>Use of the Website and the Services is subject to Buyers’ (“<strong>Your</strong>”) acceptance of and agreement to these Terms of Use.  By accessing and browsing information (including Content) on the Website, or by otherwise using the Website, You agree to be bound by these Terms of Use without limitation or qualification.  If You do not agree to be bound by these Terms of Use, You must not access or use the Website.</p><h2>2. Changes to the Website</h2><p>notTV MAY, AT ANY TIME WITHOUT NOTICE OR LIABILITY, AND FOR ANY REASON WHATSOEVER, CHANGE, SUSPEND OR TERMINATE ANY ASPECT OF THE WEBSITE AND THE SERVICES.  notTV RESERVES THE RIGHT, IN ITS SOLE DISCRETION, TO CORRECT ANY ERRORS OR OMISSIONS IN ANY PORTION OF THE WEBSITE AT ANY TIME WITHOUT NOTICE OR LIABILITY, BUT DOES NOT HAVE A DUTY TO DO SO.  IT IS YOUR RESPONSIBILITY TO CHECK THIS SITE PERIODICALLY FOR CHANGES.  IF YOU DO NOT AGREE WITH ANY CHANGES, YOU MAY TERMINATE THESE TERMS OF USE AS SET OUT BELOW.  YOUR CONTINUED USE OF THIS WEBSITE FOLLOWING THE POSTING OF CHANGES TO THESE TERMS OF USE WILL MEAN THAT YOU ACCEPT AND AGREE TO THESE CHANGES.</p><h2>3. Registration, Account and Password</h2><p>To access the Services on the Website, including purchasing rights to Content, You must:</p><p><strong>3.1 create an account with the Website;</strong></p><p><strong>3.2 make purchases using a major credit card or through a PayPal account and adhere to the terms of the applicable <a href=\"https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&amp;content_ID=ua/UserAgreement_full&amp;locale.x=en_US\">PayPal User Agreement</a></strong><strong> and <a href=\"https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&amp;content_ID=ua/AcceptableUse_full&amp;locale.x=en_US\">PayPal Acceptable Use Policy</a>; and</strong></p><p><strong>3.3 keep Your account profile information up-to-date and accurate at all times, including but not limited to, a valid email address.</strong></p><p>You agree to be responsible for:</p><p><strong>3.4 maintaining the confidentiality of any passwords or other account identifiers which You choose or are assigned as a result of any registration or subscription process; and</strong></p><p><strong>3.5 all activities that occur under Your account or password.</strong></p><p>You further agree to:</p><p><strong>3.6 notify notTV immediately of any unauthorized use of Your password or account; and</strong></p><p><strong>3.7 ensure that You log out of Your account and close Your browser window upon completing Your use of the Website.</strong></p><h2>4. Use and Restrictions</h2><p>You may access and use the Website and Services only in accordance with all applicable laws and regulations and with these Terms of Use.</p><p>You must not to modify, rent, lease, loan, sell, distribute or create derivative works or businesses based on the Website, in whole or in part.  The license to view and use granted in these Terms of Use does not include permission to copy the content, design elements, “look and feel” or the layout of the Website.</p><p>You must not, directly or indirectly, print, copy, reproduce, save onto Your own computer, modify, translate, merge with other data, frame in another website, post on another website, or otherwise use the Content for any purpose whatsoever, unless You have agreed to and complied with the terms and conditions of the agreement respecting the purchase of Content (the “<strong>Purchase Terms &amp; Conditions</strong>”).</p><h2>5. Intellectual Property Rights</h2><p>The Website (including the organization and layout of the Website and all software used in connection with the Website) is, and at all times remains, the property of notTV.  You agree to abide by all intellectual property notices, information and restrictions on, or displayed with, the Website.</p><p>Certain names, graphics, logos, icons, designs, words, titles and phrases on the Website constitute trade-marks (whether registered or unregistered), trade names or other intellectual property of notTV (the “<strong>notTV IP</strong>”) or third parties (the “<strong>Third Party IP</strong>”).  notTV at all times remains the sole owner of the notTV IP.</p><p>The display of the Content, the notTV IP and the Third Party IP on the Website does not convey or create any licence or other rights in any of the Content, the notTV IP or the Third Party IP, including:</p><p><strong>5.1 any ownership rights in any of the Content, the notTV IP or the Third Party IP;</strong></p><p><strong>5.2 any right to use any of the Content, the notTV IP or the Third Party IP for commercial purposes including sale, resale, licence or sublicense; or</strong></p><p><strong>5.3 any right to reproduce, distribute, display, post, disseminate, publish, broadcast, or transfer any of the Content, the notTV IP or the Third Party IP.</strong></p><p>Use of any of the notTV IP or the Third Party IP without the prior written consent of the notTV or third parties, as the case may be, is strictly prohibited.</p><p>Use of the Content without complying with the Purchasing Terms &amp; Conditions is strictly prohibited.</p><h2>6. Third Party Websites and Content Linked to the Website</h2><p>The Website may contain links to third party websites or content posted on third party websites (the “<strong>Links</strong>”).  notTV provides the Links only as a convenience to help You identify and locate other Internet resources that may be of interest.</p><p>You acknowledge that in providing the Links, notTV:</p><p><strong>6.1 does not endorse any third party website or content accessible via the Links;</strong></p><p><strong>6.2 does not act as an editor, publisher or disseminator of any content accessible via the Links and does not control or monitor any third party website;</strong></p><p><strong>6.3 does not make any representation or warranty of any kind regarding the Links;</strong></p><p><strong>6.4 is not responsible, directly or indirectly, in any way for the accuracy, relevancy, completeness, timeliness or legality of any content accessible via the Links; and</strong></p><p><strong>6.5 is not responsible, directly or indirectly, in any way for any loss or damage of any kind incurred as a result of, or in connection with, Your use of, or reliance on, any of the Links.</strong></p><p>You agree that You are solely responsible for:</p><p><strong>6.6 taking all protective measures to guard against viruses and other destructive elements that may be contained in the Links;</strong></p><p><strong>6.7 abiding by the terms and conditions of use and the privacy policies posted at the third party websites accessed through the Links; and</strong></p><p><strong>6.8 evaluating the content accessible via the Links and bearing all risks associated with Your use of, and reliance on, any such content.</strong></p><h2>7. Links to the Website on Third Party Websites</h2><p>If You want to include a link to the Website on Your website (the “Third Party Link”), You must:</p><p><strong>7.1 provide notTV with prior written notice describing the nature and features of Your website and the proposed appearance and location of the Third Party Link; and</strong></p><p><strong>7.2 obtain the prior written consent of notTV to establish the Third Party Link and abide by any terms and conditions required by notTV as part of its consent.</strong></p><p>notTV may at any time, in its sole and absolute discretion, revoke its consent to establish a Third Party Link, with or without cause, upon reasonable notice to You.  Upon exercise of this right by notTV, You must immediately remove all of Your Third Party Links to the Website and must cease all use of the notTV IP.  If You refuse or neglect to remove all of Your links to the Website or to cease using the notTV IP, You acknowledge that such refusal or neglect will result in immediate and irreparable damage to notTV and that notTV shall be entitled to relief in the way of temporary and permanent injunctions and such other and further relief as a court may deem just and proper.</p><p>notTV is not responsible or liable, directly or indirectly, in any way for any content available on a third party website that has established a link to the Website.</p><h2>8. Disclaimers</h2><p>You expressly understand and agree that:</p><p><strong>8.1 the Website, the Services and the Links are provided for informational purposes only;</strong></p><p><strong>8.2 the Website, the Services and the Links are provided on an “as is” and “as available” basis;</strong></p><p><strong>8.3 any use of, or reliance on, the Website, the Services, the Links and the Content is at Your sole risk;</strong></p><p><strong>8.4 notTV AND ITS LICENSORS, LICENSEES, AFFILIATES OR SUBSIDIARIES AND EACH OF THEIR RESPECTIVE DIRECTORS, OFFICERS, EMPLOYEES AND AGENTS DO NOT MAKE ANY, AND TO THE MAXIMUM EXTENT PERMITTED BY LAW EXPRESSLY DISCLAIM ALL, REPRESENTATIONS, WARRANTIES, COVENANTS AND CONDITIONS, EXPRESS OR IMPLIED, BY OPERATION OF LAW OR OTHERWISE, WITH RESPECT TO THE WEBSITE, THE SERVICES, THE CONTENT AND/OR THE LINKS INCLUDING, WITHOUT LIMITATION:</strong></p><p><strong>(a) WARRANTIES OR CONDITIONS OF TITLE AND NON-INFRINGEMENT;</strong></p><p><strong>(b) IMPLIED WARRANTIES OR CONDITIONS OF MERCHANTABILITY, MERCHANTABLE QUALITY, FITNESS FOR A PARTICULAR PURPOSE;</strong></p><p><strong>(c) WARRANTIES OR CONDITIONS THAT THE WEBSITE, THE SERVICES AND THE CONTENT WILL BE USEFUL OR MEET YOUR REQUIREMENTS OR EXPECTATIONS, THAT THE WEBSITE AND THE SERVICES WILL BE AVAILABLE, UNINTERRUPTED, TIMELY, SECURE, ERROR-FREE, OR FREE OF DEFECTS, COMPUTER VIRUSES OR OTHER HARMFUL COMPONENTS OR THAT THE RESULTS THAT MAY BE OBTAINED THROUGH USE OF THE WEBSITE AND THE SERVICES WILL BE COMPLETE, ACCURATE OR RELIABLE;</strong></p><p><strong>(d) WARRANTIES OR CONDITIONS AS TO SECURITY; AND</strong></p><p><strong>(e) WARRANTIES OR CONDITIONS ARISING FROM ANY COURSE OF DEALING, COURSE OF PERFORMANCE OR USAGE OF TRADE, WITH RESPECT TO THE WEBSITE, THE SERVICES OR THE LINKS.</strong></p><p><strong>8.5 No information, whether oral or written, obtained by You from notTV, its licensees or employees or from the Website, shall create any warranty not expressly stated in these Terms of Use.</strong></p><h2>9. Limitation of Liability</h2><p>YOU EXPRESSLY UNDERSTAND AND AGREE THAT IN NO EVENT WILL <strong>notTV</strong>, ANY OF ITS LICENSORS, LICENSEES, AFFILIATES OR SUBSIDIARIES AND EACH OF THEIR RESPECTIVE DIRECTORS, OFFICERS, EMPLOYEES AND AGENTS HAVE ANY RESPONSIBILITY OR LIABILITY IN CONNECTION WITH THE WEBSITE, THE SERVICES, THE LINKS OR THE CONTENT FOR ANY LOSS OR DAMAGES WHATSOEVER, WHETHER BASED ON CONTRACT, NEGLIGENCE OR OTHER LEGAL BASIS, INCLUDING WITHOUT LIMITATION, DIRECT, INDIRECT, SPECIAL, PUNITIVE, EXEMPLARY OR CONSEQUENTIAL DAMAGES OR OTHER DAMAGES ARISING FROM OR IN CONNECTION WITH THE USE OF OR ACCESS TO THE WEBSITE, THE SERVICES, THE CONTENT, ANY LINKS OR FAILURE OF THE WEBSITE OR ANY LINKS (INCLUDING, WITHOUT LIMITATION, ANY DAMAGES SUFFERED AS A RESULT OF OMISSIONS OR INACCURACIES IN THE WEBSITE, ANY CONTENT, ANY LINKS OR THE TRANSMISSION OF CONFIDENTIAL OR SENSITIVE INFORMATION TO OR FROM THE WEBSITE OR ANY LINKS OR YOUR SALE OR PURCHASE OF ANY CONTENT THROUGH THE WEBSITE).  THESE LIMITATIONS APPLY EVEN IF MCI HAD BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGE OR LOSS OR IF SUCH LOSS OR DAMAGE WAS FORESEEABLE.</p><p>You expressly acknowledge that notTV is making the Website, Services, Links and Content available to You in reliance upon the limitations and exclusions of liability and the disclaimers set forth herein and that the same form an essential basis of the contract between You and notTV.  You expressly agree that the limitations and exclusions of liability and the disclaimers set forth herein will survive, and continue to apply in the case of, a fundamental breach or breaches, the failure of essential purpose of contract, the failure of any exclusive remedy or the termination or suspension by <strong>notTV </strong>of Your use of, or access to, the Website.</p><h2>10. Your Representations and Warranties</h2><p>You represent and warrant that:</p><p><strong>10.1 if You are an individual, You are over the age of majority in the jurisdiction in which You are resident;</strong></p><p><strong>10.2 You are using the Website in a jurisdiction where access to, or use of, the Website (or any part thereof) is not prohibited or illegal; and</strong></p><p><strong>10.3 You are fully able and competent and authorized to enter into, and abide by, these Terms of Use.</strong></p><h2>11. Indemnity</h2><p>You agree to defend, indemnify and hold harmless each of notTV, its licensors, licensees, affiliates, subsidiaries and each of their respective directors, officers, employees, and agents from and against any and all claims, actions or demands, including, without limitation, reasonable legal and accounting fees, resulting from or related to, or alleged to result from or relate to:</p><p><strong>11.1 Your breach of any of these Terms of Use;</strong></p><p><strong>11.2 Your access to, use of or reliance on the Website, the Services, the Links and/or the Content;</strong></p><p><strong>11.3 Your communications or dealings with any third parties mentioned on the Website and the Links; and</strong></p><p><strong>11.4 Your purchase of rights to Content through the Website.</strong></p><h2>12. Termination</h2><p>You acknowledge and agree that notTV may, at any time without notice, in its sole and absolute discretion and without cause, suspend or terminate Your access to, the Website, the Services and/or any of the Links.</p><p>You further agree that notTV will not be liable to You or any other person as a result of such suspension or termination.</p><p>Sections 6, 8, 9, 10, 11, 13, 14, 17, 18, and 19 of these Terms of Use will survive such termination.</p><h2>13. No Agency</h2><p>You and notTV are independent contractors and no agency, partnership, joint venture, employee-employer or franchiser-franchisee relationship is intended or created by these Terms of Use.</p><h2>14. Choice of Law</h2><p>You agree that all matters relating to these Terms of Use, including the use of the Website the Services, the Content and/or any of the Links, shall be governed by the laws of the Province of British Columbia and the federal laws of Canada applicable therein, without reference to conflict of law principles.  notTV and You each agree to submit to the exclusive jurisdiction of the courts of the Province of British Columbia and to waive any objections based upon venue.  Any legal action taken in regards to this Agreement must be filed in the Kelowna Court Registry.</p><h2>15. Privacy</h2><p>Any of Your personal information which <strong>notTV </strong>collects via the Website is subject to <strong>notTV</strong>’s Website Privacy Policy which is available at <strong><a href=\"http://www.not.tv/privacy\">www.not.tv/privacy</a></strong>and is incorporated by reference into these Terms of Use.</p><h2>16. Language</h2><p>The parties agree that English shall be the sole language of this Agreement and that all disputes arising from it shall be resolved in English.</p><h2>17. Force Majeure</h2><p>Neither party is liable for any delay, interruption or failure in the performance of its obligations if caused by acts of God, war (declared or undeclared), fire, flood, storm, slide, earthquake, power failure, inability to obtain equipment, supplies or other facilities not caused by a failure to pay, labour disputes, or other similar event beyond the control of the party affected which may prevent or delay such performance.  If any such act or event occurs or is likely to occur, the party affected shall promptly notify the other, giving particulars of the event.  The party so affected shall use reasonable efforts to eliminate or remedy the event.</p><h2>18. General</h2><p>These Terms of Use (including the Website Privacy Policy, the Purchasing Terms &amp; Conditions) constitute the entire agreement between notTV and You with respect to Your use of the Website including, without limitation, the Services and/or any of the Content and/or any of the Links.</p><p>These Terms of Use supersede all prior communications, representations or agreements, either oral or written, between notTV and its licensors, licensees, affiliates or subsidiaries and each of their respective directors, officers, employees and agents and You with respect to the Website and the Services.</p><p>If any section (or part thereof) of these Terms of Use is determined to be void, invalid or otherwise unenforceable by a court of competent jurisdiction, such determination shall not affect the remaining sections (or parts thereof) of these Terms of Use, which shall remain in full force and effect.  notTV’s failure to insist upon or enforce strict performance of any section of these Terms of Use or any right shall not be construed as a waiver of any such section or right.</p><p>These Terms of Use will enure to the benefit of and be binding upon the parties and their respective successors and assigns.  notTV may assign its rights and duties under these Terms and Conditions at any time without notice to You.  You may not assign Your rights and duties under these Terms of Use to any individual or entity at any time.</p><h2>19. Notice</h2><p>Any communications to notTV in regards to these Terms of Use and/or the Website, may be made to:</p><p>notTV</p><p>101-1865 Dilworth Dr, Ste353<br>Kelowna, British Columbia<br>Canada V1Y 9T1</p><p>1-778-353-1411</p><p>contact@not.tv</p>", 101);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["Head"], {
    title: "Terms of Service"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["JetAuthenticationCardLogo"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose",
    innerHTML: $props.terms
  }, null, 8
  /* PROPS */
  , _hoisted_4), _hoisted_5])])])], 64
  /* STABLE_FRAGMENT */
  );
}

/***/ }),

/***/ "./resources/js/Layouts/NoLayout.vue":
/*!*******************************************!*\
  !*** ./resources/js/Layouts/NoLayout.vue ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NoLayout_vue_vue_type_template_id_7869ad4f__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NoLayout.vue?vue&type=template&id=7869ad4f */ "./resources/js/Layouts/NoLayout.vue?vue&type=template&id=7869ad4f");
/* harmony import */ var _var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");

const script = {}

;
const __exports__ = /*#__PURE__*/(0,_var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_1__["default"])(script, [['render',_NoLayout_vue_vue_type_template_id_7869ad4f__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/Layouts/NoLayout.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/Pages/TermsOfService.vue":
/*!***********************************************!*\
  !*** ./resources/js/Pages/TermsOfService.vue ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TermsOfService_vue_vue_type_template_id_63d45180__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TermsOfService.vue?vue&type=template&id=63d45180 */ "./resources/js/Pages/TermsOfService.vue?vue&type=template&id=63d45180");
/* harmony import */ var _TermsOfService_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TermsOfService.vue?vue&type=script&setup=true&lang=js */ "./resources/js/Pages/TermsOfService.vue?vue&type=script&setup=true&lang=js");
/* harmony import */ var _var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_TermsOfService_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_TermsOfService_vue_vue_type_template_id_63d45180__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/Pages/TermsOfService.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/Pages/TermsOfService.vue?vue&type=script&setup=true&lang=js":
/*!**********************************************************************************!*\
  !*** ./resources/js/Pages/TermsOfService.vue?vue&type=script&setup=true&lang=js ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TermsOfService_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TermsOfService_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TermsOfService.vue?vue&type=script&setup=true&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/TermsOfService.vue?vue&type=script&setup=true&lang=js");
 

/***/ }),

/***/ "./resources/js/Layouts/NoLayout.vue?vue&type=template&id=7869ad4f":
/*!*************************************************************************!*\
  !*** ./resources/js/Layouts/NoLayout.vue?vue&type=template&id=7869ad4f ***!
  \*************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NoLayout_vue_vue_type_template_id_7869ad4f__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_NoLayout_vue_vue_type_template_id_7869ad4f__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./NoLayout.vue?vue&type=template&id=7869ad4f */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Layouts/NoLayout.vue?vue&type=template&id=7869ad4f");


/***/ }),

/***/ "./resources/js/Pages/TermsOfService.vue?vue&type=template&id=63d45180":
/*!*****************************************************************************!*\
  !*** ./resources/js/Pages/TermsOfService.vue?vue&type=template&id=63d45180 ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TermsOfService_vue_vue_type_template_id_63d45180__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_TermsOfService_vue_vue_type_template_id_63d45180__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./TermsOfService.vue?vue&type=template&id=63d45180 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/TermsOfService.vue?vue&type=template&id=63d45180");


/***/ })

}]);