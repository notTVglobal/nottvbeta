"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5600,1729],{3596:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(94015),o=r.n(n),a=r(23645),l=r.n(a)()(o());l.push([e.id,".isFullPageCss{background:#000c}","",{version:3,sources:["webpack://./resources/js/Components/Global/Navigation/PublicNavigationMenu.vue"],names:[],mappings:"AAoHA,eACE,gBAEF",sourcesContent:['<template>\n  <div class="fixed top-0 w-full nav-mask">\n    <nav class="sticky top-0 bg-black border-b border-gray-100 z-50">\n      \x3c!-- Primary Navigation Menu --\x3e\n      <div class="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50">\n        <div class="flex justify-between h-16">\n          <div class="flex">\n            \x3c!-- Logo --\x3e\n            <div class="shrink-0 flex items-center">\n              <Link @click="returnToWelcomePage">\n                <JetApplicationMark class="block h-9 w-auto"/>\n              </Link>\n            </div>\n            <div class="w-full flex flex-row justify-between">\n              <div class="space-x-4 py-6 pt-6 ml-8 text-gray-200">\n                <h3 class="inline-flex items-center relative">\n                  <JetNavLink\n                      @click="returnToWelcomePage"\n                  >Watch Now</JetNavLink>\n                </h3>\n                <h3 class="inline-flex items-center relative">\n                  <JetNavLink\n                      :href="`/public/news`"\n                      :active="appSettingStore.currentPage === \'public.news.index\'">\n                    News</JetNavLink>\n                </h3>\n              </div>\n            </div>\n          </div>\n          <div class="space-x-4 py-6 pt-6 mx-8 text-gray-200">\n            <h3 class="inline-flex items-center relative">\n              <JetNavLink\n                  :href="`/public/login`"\n                  :active="appSettingStore.currentPage === \'public.login\'">\n                Login</JetNavLink>\n            </h3>\n            <h3 class="inline-flex items-center relative">\n              <JetNavLink\n                  :href="`/public/register`"\n                  :active="appSettingStore.currentPage === \'public.register\'">\n                Register</JetNavLink>\n            </h3>\n\n          </div>\n        </div>\n\n      </div>\n    </nav>\n  </div>\n</template>\n\n<script setup>\nimport { Inertia } from "@inertiajs/inertia"\nimport { onMounted } from "vue"\nimport { Link } from \'@inertiajs/inertia-vue3\'\nimport JetNavLink from \'@/Jetstream/NavLink\'\nimport JetApplicationMark from \'@/Jetstream/ApplicationMark\'\nimport JetDropdownLink from \'@/Jetstream/DropdownLink\'\nimport JetDropdown from \'@/Jetstream/Dropdown\'\nimport { useVideoPlayerStore } from "@/Stores/VideoPlayerStore"\nimport { useAppSettingStore } from "@/Stores/AppSettingStore"\n\nimport { useChatStore } from "@/Stores/ChatStore"\nimport { useStreamStore } from "@/Stores/StreamStore"\n// import { useUserStore } from "@/Stores/UserStore"\nimport { useWelcomeStore } from "@/Stores/WelcomeStore"\n\nconst appSettingStore = useAppSettingStore()\nconst chat = useChatStore()\nconst videoPlayerStore = useVideoPlayerStore()\nconst streamStore = useStreamStore()\n// const userStore = useUserStore()\nconst welcomeStore = useWelcomeStore()\n\n// streamStore.isLive(true)\n\nlet props = defineProps({\n  user: Object,\n})\n\nappSettingStore.pageReload = false\n\nconst returnToWelcomePage = () => {\n  appSettingStore.pageReload = true\n  Inertia.visit(\'/\')\n}\n// let isStreamPage = false\n//\n// function setPage() {\n//     if (appSettingStore.currentPage = "stream") {\n//         videoPlayerStore.currentPageIsStream = true;\n//     } else\n//         videoPlayerStore.currentPageIsStream = false;\n// }\n\n// setPage()\n\n// onMounted(() => {\n//   getUser()\n// })\n//\n// function getUser() {\n//   if (props.user) {\n//     userStore.id = props.user.id\n//     userStore.roleId = props.user.role_id\n//     userStore.userIsAdmin = props.user.isAdmin\n//   }\n//   userStore.isSubscriber()\n//   userStore.isCreator()\n//   userStore.isVip()\n//   userStore.isAdmin()\n// }\n\n<\/script>\n<style>\n\n.isFullPageCss {\n  background: rgba(0, 0, 0, 0.8);\n  /*background: yellow;*/\n}\n\n</style>\n'],sourceRoot:""}]);const i=l},25513:(e,t,r)=>{r.d(t,{Z:()=>g});var n=r(70821),o={class:"footer justify-center space-x-1 md:space-x-24 lg:space-x-32 bg-gray-900 text-neutral-content pt-12 pb-16"},a=(0,n.createElementVNode)("nav",null,[(0,n.createElementVNode)("header",{class:"footer-title"},"Membership"),(0,n.createElementVNode)("span",{class:"italic"},"Links coming soon.")],-1),l=(0,n.createElementVNode)("header",{class:"footer-title"},"Newsroom",-1),i=(0,n.createElementVNode)("header",{class:"footer-title"},"Company",-1),s=(0,n.createElementVNode)("header",{class:"footer-title"},"Legal",-1),c={class:"bg-gray-900 text-neutral-content border-t border-base-300 space-y-4 px-10 2xl:px-96 py-10"},u={class:"container mx-auto flex flex-col md:flex-row justify-between items-center"},d={class:"flex flex-col items-center md:items-start text-center md:text-left"},m=(0,n.createElementVNode)("img",{src:"/storage/images/logo_white_512.png",alt:"Logo",class:"w-1/6 mb-2"},null,-1),p=(0,n.createElementVNode)("span",{class:"text-xs -ml-1 -mr-1"}," ® ",-1),f=(0,n.createStaticVNode)('<nav class="flex justify-center space-x-4 pt-4"><a href="https://twitter.com/nottv" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg></a><a href="https://www.youtube.com/@notTV-WeAreNotTV" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></a><a href="https://www.facebook.com/wearenottv" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a><a href="https://t.me/+2yVGxCKH5gY3MWEx" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current hover:fill-accent hover:cursor-pointer"><path d="M2,21 L23,12 L2,3 L2,10 L17,12 L2,14 Z"></path></svg></a></nav>',1);const v={},g=(0,r(83744).Z)(v,[["render",function(e,t){var r=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createElementVNode)("footer",o,[a,(0,n.createElementVNode)("nav",null,[l,(0,n.createVNode)(r,{href:"/public/news",class:"hover:text-blue-500"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("Headlines")]})),_:1}),(0,n.createVNode)(r,{href:"/public/news/reporters",class:"hover:text-blue-500"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("Reporters")]})),_:1})]),(0,n.createElementVNode)("nav",null,[i,(0,n.createVNode)(r,{href:"/whitepaper",class:"hover:text-blue-500"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("Whitepaper")]})),_:1})]),(0,n.createElementVNode)("nav",null,[s,(0,n.createVNode)(r,{href:"/terms-of-service",class:"hover:text-blue-500"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("Terms of use")]})),_:1}),(0,n.createVNode)(r,{href:"/privacy-policy",class:"hover:text-blue-500"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("Privacy policy")]})),_:1})])]),(0,n.createElementVNode)("footer",c,[(0,n.createElementVNode)("div",u,[(0,n.createElementVNode)("div",d,[m,(0,n.createElementVNode)("p",null,[(0,n.createTextVNode)("not "),p,(0,n.createTextVNode)(" TV © 2009-"+(0,n.toDisplayString)((new Date).getFullYear()),1)])]),f])])],64)}]])},36700:(e,t,r)=>{r.d(t,{Z:()=>Z});var n=r(70821),o=r(9680),a=r(39038),l=r(65309),i=r(15458),s=(r(88333),r(68464),r(40191)),c=r(58358),u=r(35164),d=r(53489),m=r(27569),p={class:"fixed top-0 w-full nav-mask"},f={class:"sticky top-0 bg-black border-b border-gray-100 z-50"},v={class:"max-w-7xl mx-auto px-4 lg:px-6 xl:px-8 z-50"},g={class:"flex justify-between h-16"},h={class:"flex"},x={class:"shrink-0 flex items-center"},b={class:"w-full flex flex-row justify-between"},w={class:"space-x-4 py-6 pt-6 ml-8 text-gray-200"},N={class:"inline-flex items-center relative"},V={class:"inline-flex items-center relative"},y={class:"space-x-4 py-6 pt-6 mx-8 text-gray-200"},k={class:"inline-flex items-center relative"},S={class:"inline-flex items-center relative"};const E={__name:"PublicNavigationMenu",props:{user:Object},setup:function(e){var t=(0,c.useAppSettingStore)();(0,u.useChatStore)(),(0,s.useVideoPlayerStore)(),(0,d.useStreamStore)(),(0,m.useWelcomeStore)();t.pageReload=!1;var r=function(){t.pageReload=!0,o.Inertia.visit("/")};return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",p,[(0,n.createElementVNode)("nav",f,[(0,n.createElementVNode)("div",v,[(0,n.createElementVNode)("div",g,[(0,n.createElementVNode)("div",h,[(0,n.createElementVNode)("div",x,[(0,n.createVNode)((0,n.unref)(a.rU),{onClick:r},{default:(0,n.withCtx)((function(){return[(0,n.createVNode)((0,n.unref)(i.Z),{class:"block h-9 w-auto"})]})),_:1})]),(0,n.createElementVNode)("div",b,[(0,n.createElementVNode)("div",w,[(0,n.createElementVNode)("h3",N,[(0,n.createVNode)((0,n.unref)(l.Z),{onClick:r},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)("Watch Now")]})),_:1})]),(0,n.createElementVNode)("h3",V,[(0,n.createVNode)((0,n.unref)(l.Z),{href:"/public/news",active:"public.news.index"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" News")]})),_:1},8,["active"])])])])]),(0,n.createElementVNode)("div",y,[(0,n.createElementVNode)("h3",k,[(0,n.createVNode)((0,n.unref)(l.Z),{href:"/public/login",active:"public.login"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Login")]})),_:1},8,["active"])]),(0,n.createElementVNode)("h3",S,[(0,n.createVNode)((0,n.unref)(l.Z),{href:"/public/register",active:"public.register"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Register")]})),_:1},8,["active"])])])])])])])}}};var C=r(93379),P=r.n(C),_=r(3596),B={insert:"head",singleton:!1};P()(_.Z,B);_.Z.locals;const Z=E},45109:(e,t,r)=>{r.d(t,{Z:()=>c});var n=r(70821),o=r(9680),a=r(58358),l=(r(28277),r(49341)),i={class:"flex mt-24 py-10 px-16 justify-center"},s={class:"grid grid-cols-2 md:grid-cols-5 gap-4 justify-center"};const c={__name:"PublicNewsNavigationButtons",props:{href:String,active:Boolean},setup:function(e){var t=(0,a.useAppSettingStore)(),r=e;(0,n.computed)((function(){return r.active?"flex justify-center items-center p-2 bg-blue-500 rounded leading-5 hover:bg-indigo-400 transition":"flex justify-center items-center p-2 bg-indigo-400 hover:bg-indigo-400 rounded leading-5 transition"}));return function(e,r){return(0,n.openBlock)(),(0,n.createElementBlock)("div",i,[(0,n.createElementVNode)("div",s,[(0,n.createVNode)(l.Z,{onClick:r[0]||(r[0]=function(){return(0,n.unref)(o.Inertia).visit("/public/news/reporters")}),active:"public.news.reporters"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Reporters ")]})),_:1},8,["active"]),(0,n.createVNode)(l.Z,{disabled:"",onClick:r[1]||(r[1]=function(){return(0,n.unref)(o.Inertia).visit("/public/news/stories")}),active:"public.news.stories"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Stories ")]})),_:1},8,["active"]),(0,n.createVNode)(l.Z,{disabled:"",onClick:r[2]||(r[2]=function(){return(0,n.unref)(o.Inertia).visit("/public/news/categories")}),active:"public.news.categories"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Categories ")]})),_:1},8,["active"]),(0,n.createVNode)(l.Z,{disabled:"",onClick:r[3]||(r[3]=function(){return(0,n.unref)(o.Inertia).visit("/public/news/cities")}),active:"public.news.cities"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Cities ")]})),_:1},8,["active"]),(0,n.createVNode)(l.Z,{disabled:"",onClick:r[4]||(r[4]=function(){return(0,n.unref)(o.Inertia).visit("/public/news/regions")}),active:"public.news.regions"===(0,n.unref)(t).currentPage},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Regions ")]})),_:1},8,["active"])])])}}}},32242:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(70821),o={class:"min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"},a={class:"w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"};const l={},i=(0,r(83744).Z)(l,[["render",function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",o,[(0,n.createElementVNode)("div",null,[(0,n.renderSlot)(e.$slots,"logo")]),(0,n.createElementVNode)("div",a,[(0,n.renderSlot)(e.$slots,"default")])])}]])},5556:(e,t,r)=>{r.d(t,{Z:()=>s});var n=r(70821),o=r(58358),a=r(39038),l=r(9680),i=["src"];const s={__name:"AuthenticationCardLogo",setup:function(e){var t=(0,o.useAppSettingStore)();t.pageReload=!1;var r=function(){t.noLayout?(t.pageReload=!0,l.Inertia.visit("/")):l.Inertia.visit("/")};return function(e,t){return(0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(a.rU),{onClick:r},{default:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,i)]})),_:1})}}}},43497:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(70821),o=["value"];const a={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{type:String,default:null}},emits:["update:checked"],setup:function(e,t){var r=t.emit,a=e,l=(0,n.computed)({get:function(){return a.checked},set:function(e){r("update:checked",e)}});return function(t,r){return(0,n.withDirectives)(((0,n.openBlock)(),(0,n.createElementBlock)("input",{"onUpdate:modelValue":r[0]||(r[0]=function(e){return l.value=e}),type:"checkbox",value:e.value,class:"rounded border-2 border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"},null,8,o)),[[n.vModelCheckbox,l.value]])}}}},6710:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(70821),o=["value"];const a={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var r=t.expose,a=(0,n.ref)(null);return(0,n.onMounted)((function(){a.value.hasAttribute("autofocus")&&a.value.focus()})),r({focus:function(){return a.value.focus()}}),function(t,r){return(0,n.openBlock)(),(0,n.createElementBlock)("input",{ref_key:"input",ref:a,class:"border-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:e.modelValue,onInput:r[0]||(r[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,o)}}}},15957:(e,t,r)=>{r.d(t,{Z:()=>i});var n=r(70821),o={class:"block font-medium text-sm text-gray-700"},a={key:0},l={key:1};const i={__name:"Label",props:{value:String},setup:function(e){return function(t,r){return(0,n.openBlock)(),(0,n.createElementBlock)("label",o,[e.value?((0,n.openBlock)(),(0,n.createElementBlock)("span",a,(0,n.toDisplayString)(e.value),1)):((0,n.openBlock)(),(0,n.createElementBlock)("span",l,[(0,n.renderSlot)(t.$slots,"default")]))])}}}},30131:(e,t,r)=>{r.d(t,{Z:()=>s});var n=r(70821),o=r(39038),a={key:0},l=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),i={class:"mt-3 list-disc list-inside text-sm text-red-600"};const s={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.qt)().props.value.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",a,[l,(0,n.createElementVNode)("ul",i,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},91729:(e,t,r)=>{r.r(t),r.d(t,{default:()=>S});var n=r(70821),o=(r(9680),r(39038)),a=r(32242),l=r(5556),i=r(28277),s=r(6710),c=r(43497),u=r(15957),d=r(30131),m=r(58358);function p(e){return p="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},p(e)}function f(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function v(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?f(Object(r),!0).forEach((function(t){g(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):f(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function g(e,t,r){var n;return n=function(e,t){if("object"!=p(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=p(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(t,"string"),(t="symbol"==p(n)?n:String(n))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var h={key:0,class:"mb-4 font-medium text-sm text-green-600"},x={class:"pb-6 flex flex-col"},b=(0,n.createElementVNode)("div",null,"Please log in to watch notTV and chat.",-1),w={class:"mt-4"},N={class:"block mt-4"},V={class:"flex items-center"},y=(0,n.createElementVNode)("span",{class:"ml-2 text-sm text-gray-600"},"Remember me",-1),k={class:"flex items-center justify-end mt-4"};const S={__name:"Login",props:{canResetPassword:Boolean,status:String,userType:Number},setup:function(e){var t=(0,m.useAppSettingStore)();t.noLayout=!0;var r=(0,o.cI)({email:"",password:"",remember:!1}),p=function(){r.transform((function(e){return v(v({},e),{},{remember:r.remember?"on":""})})).post(route("login"),{onFinish:function(){return r.reset("password")}}),t.pageReload=!0};return function(t,m){var f=(0,n.resolveComponent)("Head");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(f,{title:"Log in"}),(0,n.createVNode)(a.Z,null,{logo:(0,n.withCtx)((function(){return[(0,n.createVNode)(l.Z)]})),default:(0,n.withCtx)((function(){return[(0,n.createVNode)(d.Z,{class:"mb-4"}),e.status?((0,n.openBlock)(),(0,n.createElementBlock)("div",h,(0,n.toDisplayString)(e.status),1)):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("form",{onSubmit:(0,n.withModifiers)(p,["prevent"]),class:"pb-10"},[(0,n.createElementVNode)("div",x,[b,(0,n.createElementVNode)("div",null,[(0,n.createTextVNode)("Need to "),(0,n.createVNode)((0,n.unref)(o.rU),{href:"/register",class:"text-blue-800 hover:text-blue-500"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" register ")]})),_:1}),(0,n.createTextVNode)("for an account?")])]),(0,n.createElementVNode)("div",null,[(0,n.createVNode)(u.Z,{for:"email",value:"Email"}),(0,n.createVNode)(s.Z,{id:"email",modelValue:(0,n.unref)(r).email,"onUpdate:modelValue":m[0]||(m[0]=function(e){return(0,n.unref)(r).email=e}),type:"email",class:"mt-1 block w-full text-black",required:"",autofocus:""},null,8,["modelValue"])]),(0,n.createElementVNode)("div",w,[(0,n.createVNode)(u.Z,{for:"password",value:"Password"}),(0,n.createVNode)(s.Z,{id:"password",modelValue:(0,n.unref)(r).password,"onUpdate:modelValue":m[1]||(m[1]=function(e){return(0,n.unref)(r).password=e}),type:"password",class:"mt-1 block w-full text-black",required:"",autocomplete:"current-password"},null,8,["modelValue"])]),(0,n.createElementVNode)("div",N,[(0,n.createElementVNode)("label",V,[(0,n.createVNode)(c.Z,{checked:(0,n.unref)(r).remember,"onUpdate:checked":m[2]||(m[2]=function(e){return(0,n.unref)(r).remember=e}),name:"remember"},null,8,["checked"]),y])]),(0,n.createElementVNode)("div",k,[e.canResetPassword?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(o.rU),{key:0,href:t.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Forgot your password? ")]})),_:1},8,["href"])):(0,n.createCommentVNode)("",!0),(0,n.createVNode)(i.Z,{class:(0,n.normalizeClass)(["ml-4",{"opacity-25":(0,n.unref)(r).processing}]),disabled:(0,n.unref)(r).processing},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Log in ")]})),_:1},8,["class","disabled"])])],32)]})),_:1})],64)}}}},35600:(e,t,r)=>{r.r(t),r.d(t,{default:()=>y});var n=r(70821),o=(r(9680),r(39038)),a=r(58358),l=(r(5556),r(32242),r(30131)),i=(r(43497),r(28277)),s=r(6710),c=r(15957),u=(r(45109),r(36700)),d=r(25513),m=(r(91729),{class:"bg-gray-900 h-[calc(100vh)]"}),p={id:"topDiv",class:"bg-gray-900 text-white px-5 flex flex-col w-full hide-scrollbar"},f=(0,n.createElementVNode)("div",{class:"mt-36 text-gray-50 text-center text-3xl font-semibold tracking-widest"},"Forgot Password?",-1),v={class:"pb-8 hide-scrollbar"},g={class:"mx-auto px-4 border-b border-gray-800 hide-scrollbar"},h={class:"hide-scrollbar bg-gray-200 mt-6 mb-36 mx-auto p-5 w-3/4 max-w-96 text-gray-900 rounded"},x=(0,n.createElementVNode)("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),b=(0,n.createElementVNode)("div",{class:"mb-4 text-sm text-black bg-orange-500"},' tec21: 2024-01-19 The code for this is the same as "forgot-password.blade.php", it references \'PasswordResetLinkController\', the only reference I can find to this is in the Fortify vendor package. It should be sending a $page.prop.status back with a message that can get displayed to the user "Password request email sent." Or however it\'s worded. // TO DO: Make this component work here and receive the status response to display on the page. ',-1),w={key:0,class:"mb-4 font-medium text-sm text-green-600"},N={class:""},V={class:"flex items-center justify-end mt-4"};const y={__name:"ForgotPassword",props:{status:String},setup:function(e){var t=(0,a.useAppSettingStore)();t.noLayout=!0,t.currentPage="public.forgotPassword";var r=(0,o.cI)({email:""}),y=function(){r.post(route("password.email"))};return function(t,a){return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)((0,n.unref)(o.Fb),{title:"Forgot Password"}),(0,n.createElementVNode)("div",m,[(0,n.createElementVNode)("div",null,[(0,n.createVNode)((0,n.unref)(u.Z),{class:"fixed top-0 w-full h-16"})]),(0,n.createElementVNode)("div",p,[f,(0,n.createElementVNode)("main",v,[(0,n.createElementVNode)("div",g,[(0,n.createElementVNode)("div",h,[x,b,e.status?((0,n.openBlock)(),(0,n.createElementBlock)("div",w,(0,n.toDisplayString)(e.status),1)):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",N,[(0,n.createVNode)(l.Z,{class:"mb-4"}),(0,n.createElementVNode)("form",{onSubmit:(0,n.withModifiers)(y,["prevent"])},[(0,n.createElementVNode)("div",null,[(0,n.createVNode)(c.Z,{for:"email",value:"Email"}),(0,n.createVNode)(s.Z,{id:"email",modelValue:(0,n.unref)(r).email,"onUpdate:modelValue":a[0]||(a[0]=function(e){return(0,n.unref)(r).email=e}),type:"email",class:"mt-1 block w-full text-gray-800",required:"",autofocus:""},null,8,["modelValue"])]),(0,n.createElementVNode)("div",V,[(0,n.createVNode)(i.Z,{class:(0,n.normalizeClass)({"opacity-25":(0,n.unref)(r).processing}),disabled:(0,n.unref)(r).processing},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Email Password Reset Link ")]})),_:1},8,["class","disabled"])])],32)])])])]),(0,n.createVNode)(d.Z)])])],64)}}}}}]);
//# sourceMappingURL=5600.js.map