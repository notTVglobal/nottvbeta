"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_Pages_PrivacyPolicy_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/PrivacyPolicy.vue?vue&type=script&setup=true&lang=js":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/PrivacyPolicy.vue?vue&type=script&setup=true&lang=js ***!
  \*************************************************************************************************************************************************************************************************************/
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
  __name: 'PrivacyPolicy',
  props: {
    policy: String
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/PrivacyPolicy.vue?vue&type=template&id=41527301":
/*!******************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/PrivacyPolicy.vue?vue&type=template&id=41527301 ***!
  \******************************************************************************************************************************************************************************************************************************************************************************/
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

var _hoisted_5 = /*#__PURE__*/(0,vue__WEBPACK_IMPORTED_MODULE_0__.createStaticVNode)("<p class=\"updated\">Last Updated: June 24, 2017</p><div>notTV Ltd. respects the privacy of our users and has developed this Privacy Policy to demonstrate its commitment to protecting your privacy. These privacy policies (the &quot;Privacy Policy&quot;) are intended to describe for you, as an individual who is a user of <span style=\"color:#0000ff;\"><a href=\"https://not.tv\">not.tv</a></span> or any of our related sites, mobile and connected TV applications, or other online services, the information we collect, how that information may be used, with whom it may be shared, and your choices about such uses and disclosures.We encourage you to read this Privacy Policy carefully when using our website or services or transacting business with us. By using our websites or any of our applications, you are accepting the practices described in this Privacy Policy. If you have any questions about our privacy practices, please refer to the end of this Privacy Policy for information on how to contact us. </div><section class=\"block\"><a name=\"info_we_collect\"></a><h2>Information We Collect About You</h2><em>In General</em>. We may collect personal information that can identify you, such as your name and email address, and other information that does not identify you. When you provide personal information through our website, the information may be sent to servers located in the Canada and other countries around the world. <ul class=\"styled\"><li><strong>Information you provide.</strong> We may collect and store any personal information you enter on our website or provide to us in some other manner, including personal information that may be contained in any video, comment or other submission you upload or post to the website. This includes identifying information, such as your name, address, e-mail address, and telephone number; your likeness; and, if you transact business with us, financial information such as your payment method (valid credit card number, type, expiration date or other financial information). We also may request information about your interests and activities, your gender and age, and other demographic information. </li><li><strong>Information from other sources.</strong> We may also periodically obtain both personal and non-personal information about you from other notTV businesses, business partners, contractors and other third parties. Examples of information that we may receive include: updated delivery and address information, purchase history, and additional demographic information. </li><li><strong>Information about others.</strong> We may also collect and store personal information about other people that you provide to us. If you use our website to send others (friends, relatives, colleagues, etc.) a product as a gift, information that may interest them or messages (such as invitations) through our system, we may store your personal information, and the personal information of each such recipient. Similarly, if you use our website to upload, share and/or distribute content (including videos, comments or other submissions), and such content contains personal information about others, such information may be stored in order to allow for such uploading, sharing and/or distribution. </li></ul><em>Use of cookies and other technologies to collect information.</em> We use various technologies to collect information from your computer and about your activities on our site. For more information on Cookies, please see our <a href=\"https://not.tv/cookie-policy-ca/\">Cookie Policy</a>. <ul class=\"styled\"><li><strong>Information collected automatically.</strong> We automatically collect information from your browser when you visit our website. This information includes your IP address, your browser type and language, access times, the content of any undeleted cookies that your browser previously accepted from us (see &quot;<a href=\"https://vimeo.com/privacy#cookies\">Cookies</a>&quot; below), and the referring website address. </li><li><strong>Cookies.</strong> When you visit our website, we may assign your computer one or more <a href=\"https://vimeo.com/privacy#cookies\">cookies</a>, to facilitate access to our site and to personalize your online experience. Through the use of a cookie, we also may automatically collect information about your online activity on our site, such as the web pages you visit, the links you click, and the searches you conduct on our site. Most browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies. If you choose to decline <a href=\"https://vimeo.com/privacy#cookies\">cookies</a>, please note that you may not be able to sign in or use some of the interactive features offered on our website. </li><li><strong>Other Technologies.</strong> We may use standard Internet technology, such as web <a href=\"https://vimeo.com/privacy#beacons\">beacons</a> and other similar technologies, to track your use of our site. We also may include web beacons in promotional e-mail messages or newsletters to determine whether messages have been opened and acted upon. The information we obtain in this manner enables us to customize the services we offer our website visitors to deliver targeted advertisements and to measure the overall effectiveness of our online advertising, content, programming or other activities. </li><li><a name=\"info_others_collect\"></a><strong>Information collected by third-parties.</strong> We may allow third-parties, including our authorized service providers, notTV companies, advertising companies, and ad networks, to display advertisements on our site. These companies may use tracking technologies, such as <a href=\"https://vimeo.com/privacy#cookies\">cookies</a>, to collect information about users who view or interact with their advertisements. Unless expressly stated otherwise, our website does not provide any personal information to these third parties. This information allows them to deliver targeted advertisements and gauge their effectiveness. Some of these third-party advertising companies may be advertising networks that are members of the Network Advertising Initiative, which offers a single location to opt out of ad targeting from member companies (<a href=\"http://www.networkadvertising.org\">www.networkadvertising.org</a>). </li></ul></section><section class=\"block\"><a name=\"info_we_use\"></a><h2>How We Use the Information We Collect</h2><em>In General.</em> We may use information that we collect about you to: <ul class=\"styled\"><li>deliver the products and services that you have requested;</li><li>manage your account and provide you with customer support;</li><li>perform research and analysis about your use of, or interest in, our products, services, or content, or products, services or content offered by others; </li><li>communicate with you by e-mail, postal mail, telephone and/or mobile devices about products or services that may be of interest to you either from us, our sister notTV companies or other third parties; </li><li>develop and display content and advertising tailored to your interests on our site and other sites; </li><li>verify your eligibility and deliver prizes in connection with contests and sweepstakes;</li><li>enforce our terms and conditions;</li><li>manage our business and</li><li>perform functions as otherwise described to you at the time of collection.</li></ul><em>Financial information.</em> We may use financial information or payment method to process payment for any purchases, subscriptions or sales made on our website, to protect against or identify possible fraudulent transactions, and otherwise as needed to manage our business. Please review <a href=\"https://not.tv/privacy/\">&quot;Your Choices About Collection and Use of Your Information&quot;</a> below. </section><section class=\"block\"><a name=\"info_we_share\"></a><h2>With Whom We Share Your Information</h2> We want you to understand when and with whom we may share personal or other information we have collected about you or your activities on our website or while using our services. <em>Personal information.</em> We do not share your personal information with others except as indicated below or when we inform you and give you an opportunity to opt out of having your personal information shared. We may share personal information with: <ul class=\"styled\"><li><strong>Authorized service providers:</strong> We may share your personal information with our authorized service providers that perform certain services on our behalf. These services may include fulfilling orders, processing credit card payments, delivering packages, providing customer service and marketing assistance, performing business and sales analysis, supporting our website functionality, and supporting contests, sweepstakes, surveys and other features offered through our website. These service providers may have access to personal information needed to perform their functions but are not permitted to share or use such information for any other purposes. </li><li><strong>Business partners:</strong> When you make purchases, reservations or engage in promotions offered through our website or our services, we may share personal information with the businesses with which we partner to offer you those products, services, promotions, contests and/or sweepstakes. When you elect to engage in a particular merchant’s offer or program, you authorize us to provide your email address and other information to that merchant. </li><li><strong>Direct mail partners.</strong> From time to time we may share our postal mailing list with selected providers of goods and services that may be of interest to you. If you prefer not to receive mailings from these providers, you can notify us at any time by <a href=\"https://not.tv/contact\">clicking here</a>. </li><li><strong>Other Situations.</strong> We also may disclose your information: <ul class=\"styled\"><li>In response to a subpoena or similar investigative demand, a court order, or a request for cooperation from a law enforcement or other government agency; to establish or exercise our legal rights; to defend against legal claims; or as otherwise required by law. In such cases, we may raise or waive any legal objection or right available to us, in our sole discretion. </li><li>When we believe disclosure is appropriate in connection with efforts to investigate, prevent, report or take other action regarding illegal activity, suspected fraud or other wrongdoing; to protect and defend the rights, property or safety of our company, our users, our employees, or others; to comply with applicable law or cooperate with law enforcement; or to enforce our website terms and conditions or other agreements or policies. </li><li>In connection with a substantial corporate transaction, such as the sale of our business, a divestiture, merger, consolidation, or asset sale, or in the unlikely event of bankruptcy. </li></ul></li></ul> Any third parties to whom we may disclose personal information may have their own privacy policies which describe how they use and disclose personal information. Those policies will govern use, handling and disclosure of your personal information once we have shared it with those third parties as described in this Privacy Policy. If you want to learn more about their privacy practices, we encourage you to visit the websites of those third parties. These entities or their servers may be located either inside or outside the United States. <em>Aggregated and non-personal information.</em> We may share aggregated and non-personal information we collect under any of the above circumstances. We may also share it with third parties and our notTV sister companies to develop and deliver targeted advertising on our websites and on websites of third parties. We may combine non-personal information we collect with additional non-personal information collected from other sources. We also may share aggregated information with third parties, including advisors, advertisers and investors, for the purpose of conducting general business analysis. For example, we may tell our advertisers the number of visitors to our website and the most popular features or services accessed. This information does not contain any personal information and may be used to develop website content and services that we hope you and other users will find of interest and to target content and advertising. </section><section class=\"block\"><h2>Third-Party Websites</h2> There are a number of places on our website or through our services where you may click on a link to access other websites that do not operate under this Privacy Policy. For example, if you click on an advertisement or a search result on our website, you may be taken to a website that we do not control. These third-party websites may independently solicit and collect information, including personal information, from you and, in some instances, provide us with information about your activities on those websites. We recommend that you consult the privacy statements of all third-party websites you visit by clicking on the &quot;privacy&quot; link typically located at the bottom of the webpage you are visiting. </section><section class=\"block\"><h2>How You Can Access Your Information</h2> If you have an online account with us, you have the ability to review and update your personal information online by logging into your account. You can also review and update your personal information by contacting us. More information about how to contact us is provided below. If you have an account with us, you also may choose to close your account (and remove any of your content) at any time by contacting us <a href=\"https://not.tv/contact/\">here</a>. After you close your account, you will not be able to sign in to our website or access any of your personal information. However, you can open a new account at any time. If you close your account, we may still retain certain information associated with your account for analytical purposes and record keeping integrity, as well as to prevent fraud, collect any fees owed, enforce our terms and conditions, take actions we deem necessary to protect the integrity of our website or our users, or take other actions otherwise permitted by law. In addition, if certain information has already been provided to third parties as described in this <a href=\"https://not.tv/privacy/\">Privacy Policy</a>, retention of that information will be subject to those third parties’ policies. </section><section class=\"block\"><a name=\"your_choices\"></a><h2>Your Choices About Collection and Use of Your Information</h2><ul class=\"styled\"><li>You can choose not to provide us with certain information, but that may result in you being unable to use certain features of our website because such information may be required in order for you to register as a member; purchase products or services; participate in a contest, promotion, survey, or sweepstakes; ask a question; or initiate other transactions on our website. </li><li>When you register on our website you may be given a choice as to whether you want to receive email messages and/or newsletters about product updates, improvements, special offers, or containing special distributions of content by us. You will be given the opportunity, in any commercial e-mail that we send to you, to opt out of receiving such messages in the future. It may take up to 10 days for us to process an opt-out request. We may send you other types of transactional and relationship e-mail communications, such as service announcements, administrative notices, and surveys, without offering you the opportunity to opt out of receiving them. Please note that, changing information in your account, or otherwise opting out of receipt of promotional email communications will only affect future activities or communications from us. If we have already provided your information to a third party (such as a credit card processing partner or an event provider) before you have changed your preferences or updated your information, you may have to change you preferences directly with that third party. </li><li>You may tell us not to share your personal information with third parties or the notTV family of companies for direct marketing purposes by clicking on the following link and entering the email address associated with your account: <a href=\"https://not.tv/opt-out\">click here</a>. </li></ul></section><section class=\"block\"><a name=\"security\"></a><h2>How We Protect Your Personal Information</h2> We take appropriate security measures (including physical, electronic and procedural measures) to help safeguard your personal information from unauthorized access and disclosure. For example, only authorized employees are permitted to access personal information, and they may do so only for permitted business functions. In addition, we use encryption in the transmission of your sensitive personal information between your system and ours, and we use firewalls to help prevent unauthorized persons from gaining access to your personal information. We want you to feel confident using our website to transact business. However, no system can be completely secure. Therefore, although we take steps to secure your information, we do not promise, and you should not expect, that your personal information, searches, or other communications will always remain secure. Users should also take care with how they handle and disclose their personal information and should avoid sending personal information through insecure email. Please refer to the RCMP’s website at <a href=\"http://www.rcmp-grc.gc.ca/scams-fraudes/id-theft-vol-eng.htm\">http://www.rcmp-grc.gc.ca/scams-fraudes/id-theft-vol-eng.htm</a> for information about how to protect yourself against identity theft. </section><section class=\"block\"><a name=\"user_submissions\"></a><h2>User Submissions</h2> We may provide areas on our websites where you can post information about yourself and others and communicate with others; upload content (e.g. pictures, videos, audio files, etc.); and post comments or reviews of content found on the Website. Such postings are governed by our <a href=\"https://not.tv/terms\">Terms and Conditions of Use</a>. In addition, such postings may appear on other websites or when searches are executed on the subject of your posting. Also, whenever you voluntarily disclose personal information on publicly-viewable web pages, that information will be publicly available and can be collected and used by others. For example, if you post your email address or share it with another user, you may receive unsolicited messages. We cannot control who reads your postings or what other users may do with the information you post or otherwise share with other users, so we encourage you to exercise discretion and caution with respect to your personal information. Once you have posted information, you may not be able to edit or delete such information. </section><section class=\"block\"><h2>Children’s Privacy</h2> Our website is a general audience site, and we do not knowingly collect personal information from children under the age of 13. </section><section class=\"block\"><h2>Visiting Our Websites from Outside Canada</h2> This Privacy Policy is intended to cover collection of information on or via our website from residents of Canada. If you are visiting our website from outside Canada, please be aware that your information may be transferred to, stored, and processed in Canada where our servers are located and our central database is operated. The data protection and other laws of Canada and other countries might not be as comprehensive as those in your country. Please be assured that we seek to take reasonable steps to ensure that your privacy is protected. By using our services, you understand that your information may be transferred to our facilities and those third parties with whom we share it as described in this privacy policy. </section><section class=\"block\"><h2>No Rights of Third Parties</h2> This Privacy Policy does not create rights enforceable by third parties or require disclosure of any personal information relating to users of the website. </section><section class=\"block\"><h2>Changes to This Privacy Policy</h2> We will occasionally update this Privacy Policy to reflect changes in our practices and services. When we post changes to this Privacy Policy, we will revise the &quot;Last Updated&quot; date at the top of this Privacy Policy. If we make any material changes in the way we collect, use, and/or share your personal information, we will notify you by sending an email to the email address you most recently provided us in your account, profile or registration (unless we do not have such an email address), and/or by prominently posting notice of the changes on our website. We recommend that you check our website from time to time to inform yourself of any changes in this Privacy Policy or any of our other policies. </section><section class=\"block\"><h2>How to Contact Us</h2> If you have any questions about this Privacy Policy or our information-handling practices, or if you would like to request information about our disclosure of personal information to third parties for their direct marketing purposes, please contact us by <a href=\"https://not.tv/contact\">email</a> or postal mail as follows: <blockquote><strong>notTV Ltd.</strong> 101-1865 Dilworth Dr., Ste353 Kelowna, British Columbia V1Y 9T1 </blockquote></section><section class=\"block footnote\"><h4>Linked information:</h4><a name=\"cookies\"></a><h5>Cookies:</h5> A cookie is a small text file that is stored on a user’s computer for record keeping purposes. Cookies can be either session cookies or persistent cookies. A session cookie expires when you close your browser and is used to make it easier for you to navigate our website. A persistent cookie remains on your hard drive for an extended period of time. For example, when you sign in to our website, we will record your user or member ID and the name on your user or member account in the cookie file on your computer. We also may record your password in this cookie file, if you indicated that you would like your password saved for automatic sign-in. For security purposes, we will encrypt any usernames, passwords, and other user or member account-related data that we store in such cookies. In the case of sites and services that do not use a user or member ID, the cookie will contain a unique identifier. We may allow our authorized service providers to serve cookies from our website to allow them to assist us in various activities, such as doing analysis and research on the effectiveness of our site, content and advertising. You may delete or decline cookies by changing your browser settings. (Click &quot;Help&quot; in the toolbar of most browsers for instructions.) If you do so, some of the features and services of our website may not function properly. We may allow third-parties, including notTV companies, advertising companies, and ad networks, to display advertisements on our site. These companies may use tracking technologies, such as cookies, to collect information about users who view or interact with their advertisements. Our website does not provide any personal information to these third parties, but they may collect information about where you, or others who are using your computer, saw and/or clicked on the advertisements they deliver, and possibly associate this information with your subsequent visits to the advertised websites. They also may combine this information with personal information they collect from you. The collection and use of that information is subject to the third-party’s privacy policy. This information allows them to deliver targeted advertisements and gauge their effectiveness. Some of these third-party advertising companies may be advertising networks that are members of the Network Advertising Initiative, which offers a single location to opt out of ad targeting from member companies (<a href=\"http://www.networkadvertising.org\">www.networkadvertising.org</a>). <a name=\"beacons\"></a><h5>Web Beacons:</h5> Web beacons (also known as clear gifs, pixel tags or web bugs) are tiny graphics with a unique identifier, similar in function to cookies, and are used to track the online movements of web users or to access cookies. Unlike cookies which are stored on the user’s computer hard drive, web beacons are embedded invisibly on the web pages (or in email) and are about the size of the period at the end of this sentence. Web beacons may be used to deliver or communicate with cookies, to count users who have visited certain pages and to understand usage patterns. We also may receive an anonymous identification number if you come to our site from an online advertisement displayed on a third-party website. </section>", 16);

function render(_ctx, _cache, $props, $setup, $data, $options) {
  return (0,vue__WEBPACK_IMPORTED_MODULE_0__.openBlock)(), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementBlock)(vue__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["Head"], {
    title: "Privacy Policy"
  }), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_1, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_2, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", _hoisted_3, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", null, [(0,vue__WEBPACK_IMPORTED_MODULE_0__.createVNode)($setup["JetAuthenticationCardLogo"])]), (0,vue__WEBPACK_IMPORTED_MODULE_0__.createElementVNode)("div", {
    "class": "w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose",
    innerHTML: $props.policy
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

/***/ "./resources/js/Pages/PrivacyPolicy.vue":
/*!**********************************************!*\
  !*** ./resources/js/Pages/PrivacyPolicy.vue ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PrivacyPolicy_vue_vue_type_template_id_41527301__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PrivacyPolicy.vue?vue&type=template&id=41527301 */ "./resources/js/Pages/PrivacyPolicy.vue?vue&type=template&id=41527301");
/* harmony import */ var _PrivacyPolicy_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PrivacyPolicy.vue?vue&type=script&setup=true&lang=js */ "./resources/js/Pages/PrivacyPolicy.vue?vue&type=script&setup=true&lang=js");
/* harmony import */ var _var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./node_modules/vue-loader/dist/exportHelper.js */ "./node_modules/vue-loader/dist/exportHelper.js");




;
const __exports__ = /*#__PURE__*/(0,_var_www_nottvbeta_node_modules_vue_loader_dist_exportHelper_js__WEBPACK_IMPORTED_MODULE_2__["default"])(_PrivacyPolicy_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_1__["default"], [['render',_PrivacyPolicy_vue_vue_type_template_id_41527301__WEBPACK_IMPORTED_MODULE_0__.render],['__file',"resources/js/Pages/PrivacyPolicy.vue"]])
/* hot reload */
if (false) {}


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (__exports__);

/***/ }),

/***/ "./resources/js/Pages/PrivacyPolicy.vue?vue&type=script&setup=true&lang=js":
/*!*********************************************************************************!*\
  !*** ./resources/js/Pages/PrivacyPolicy.vue?vue&type=script&setup=true&lang=js ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_PrivacyPolicy_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__["default"])
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_PrivacyPolicy_vue_vue_type_script_setup_true_lang_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./PrivacyPolicy.vue?vue&type=script&setup=true&lang=js */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/PrivacyPolicy.vue?vue&type=script&setup=true&lang=js");
 

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

/***/ "./resources/js/Pages/PrivacyPolicy.vue?vue&type=template&id=41527301":
/*!****************************************************************************!*\
  !*** ./resources/js/Pages/PrivacyPolicy.vue?vue&type=template&id=41527301 ***!
  \****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_PrivacyPolicy_vue_vue_type_template_id_41527301__WEBPACK_IMPORTED_MODULE_0__.render)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_dist_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_dist_index_js_ruleSet_0_use_0_PrivacyPolicy_vue_vue_type_template_id_41527301__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!../../../node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./PrivacyPolicy.vue?vue&type=template&id=41527301 */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/dist/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/dist/index.js??ruleSet[0].use[0]!./resources/js/Pages/PrivacyPolicy.vue?vue&type=template&id=41527301");


/***/ })

}]);