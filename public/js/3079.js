"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[3079],{64897:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(94015),r=n.n(o),s=n(23645),a=n.n(s)()(r());a.push([e.id,".terms[data-v-eeb87bd2]{height:80vh}","",{version:3,sources:["webpack://./resources/js/Pages/TermsOfService.vue"],names:[],mappings:"AAuCA,wBACI,WACJ",sourcesContent:['<template>\n    <Head title="Terms of Service"/>\n\n    <div class="font-sans text-gray-900 antialiased ">\n        <div class="pt-4 bg-gray-100 rounded">\n            <div class="flex flex-col items-center pt-6 sm:pt-0">\n                <div>\n                    <JetAuthenticationCardLogo/>\n                </div>\n\n                <div class="terms mb-5 mt-10 overflow-scroll">\n                    <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose"\n                        v-html="terms"/>\n                </div>\n\n\n\n            </div>\n        </div>\n    </div>\n</template>\n\n<script setup>\nimport {Head} from \'@inertiajs/inertia-vue3\';\nimport JetAuthenticationCardLogo from \'@/Jetstream/AuthenticationCardLogo.vue\';\n\ndefineProps({\n    terms: String,\n});\n<\/script>\n<script>\nimport NoLayout from \'@/Layouts/NoLayout\';\n\nexport default {\n    layout: NoLayout,\n}\n<\/script>\n<style scoped>\n\n.terms {\n    height: 80vh;\n}\n</style>\n'],sourceRoot:""}]);const l=a},5556:(e,t,n)=>{n.d(t,{Z:()=>i});var o=n(70821),r=n(58358),s=n(39038),a=n(9680),l=["src"];const i={__name:"AuthenticationCardLogo",setup:function(e){var t=(0,r.useAppSettingStore)();t.pageReload=!1;var n=function(){t.noLayout?(t.pageReload=!0,a.Inertia.visit("/")):a.Inertia.visit("/")};return function(e,t){return(0,o.openBlock)(),(0,o.createBlock)((0,o.unref)(s.rU),{onClick:n},{default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,l)]})),_:1})}}}},92587:(e,t,n)=>{n.d(t,{Z:()=>c});var o=n(70821),r=n(58358),s=n(54277),a=n(29150),l=(0,o.createElementVNode)("div",null,null,-1),i={class:"overflow-scroll hide-scrollbar scrollbar-hid"};const c={__name:"NoLayout",setup:function(e){var t=(0,r.useAppSettingStore)();return(0,o.onBeforeMount)((function(){t.checkScreenSize()})),function(e,n){return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[l,(0,o.createElementVNode)("div",i,[(0,o.renderSlot)(e.$slots,"default")]),(0,o.unref)(t).showImageLightboxModal?((0,o.openBlock)(),(0,o.createBlock)(a.Z,{key:0})):(0,o.createCommentVNode)("",!0),(0,o.createVNode)(s.Z)],64)}}}},43079:(e,t,n)=>{n.r(t),n.d(t,{default:()=>b});var o=n(70821),r=n(39038),s=n(5556),a=n(92587),l={class:"font-sans text-gray-900 antialiased"},i={class:"pt-4 bg-gray-100 rounded"},c={class:"flex flex-col items-center pt-6 sm:pt-0"},d={class:"terms mb-5 mt-10 overflow-scroll"},u=["innerHTML"],m={layout:a.Z};const p=Object.assign(m,{__name:"TermsOfService",props:{terms:String},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[(0,o.createVNode)((0,o.unref)(r.Fb),{title:"Terms of Service"}),(0,o.createElementVNode)("div",l,[(0,o.createElementVNode)("div",i,[(0,o.createElementVNode)("div",c,[(0,o.createElementVNode)("div",null,[(0,o.createVNode)(s.Z)]),(0,o.createElementVNode)("div",d,[(0,o.createElementVNode)("div",{class:"w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose",innerHTML:e.terms},null,8,u)])])])])],64)}}});var v=n(93379),f=n.n(v),g=n(64897),h={insert:"head",singleton:!1};f()(g.Z,h);g.Z.locals;const b=(0,n(83744).Z)(p,[["__scopeId","data-v-eeb87bd2"]])}}]);
//# sourceMappingURL=3079.js.map