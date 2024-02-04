"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[1032],{67877:(e,t,n)=>{n.d(t,{Z:()=>s});var o=n(94015),a=n.n(o),r=n(23645),l=n.n(r)()(a());l.push([e.id,".privacy[data-v-623cea80]{height:80vh}","",{version:3,sources:["webpack://./resources/js/Pages/PrivacyPolicy.vue"],names:[],mappings:"AAsCA,0BACI,WACJ",sourcesContent:['<template>\n    <Head title="Privacy Policy"/>\n\n    <div class="font-sans text-gray-900 antialiased ">\n        <div class="pt-4 bg-gray-100 rounded">\n            <div class="flex flex-col items-center pt-6 sm:pt-0">\n                <div>\n                    <JetAuthenticationCardLogo/>\n                </div>\n\n                <div class="privacy mb-5 mt-10 overflow-scroll">\n                    <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md sm:rounded-lg prose"\n                         v-html="policy"/>\n                </div>\n\n            </div>\n        </div>\n    </div>\n\n</template>\n\n<script setup>\nimport {Head} from \'@inertiajs/inertia-vue3\';\nimport JetAuthenticationCardLogo from \'@/Jetstream/AuthenticationCardLogo.vue\';\n\ndefineProps({\n    policy: String,\n});\n<\/script>\n<script>\nimport NoLayout from \'@/Layouts/NoLayout\';\n\nexport default {\n    layout: NoLayout,\n}\n<\/script>\n<style scoped>\n\n.privacy {\n    height: 80vh;\n}\n</style>\n'],sourceRoot:""}]);const s=l},5556:(e,t,n)=>{n.d(t,{Z:()=>i});var o=n(70821),a=n(58358),r=n(39038),l=n(9680),s=["src"];const i={__name:"AuthenticationCardLogo",setup:function(e){var t=(0,a.useAppSettingStore)();t.pageReload=!1;var n=function(){t.noLayout?(t.pageReload=!0,l.Inertia.visit("/")):l.Inertia.visit("/")};return function(e,t){return(0,o.openBlock)(),(0,o.createBlock)((0,o.unref)(r.rU),{onClick:n},{default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,s)]})),_:1})}}}},96898:(e,t,n)=>{n.d(t,{Z:()=>s});var o=n(70821),a=(0,o.createElementVNode)("div",null,null,-1),r={class:"overflow-scroll"};const l={},s=(0,n(83744).Z)(l,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[a,(0,o.createElementVNode)("div",r,[(0,o.renderSlot)(e.$slots,"default")])],64)}]])},21032:(e,t,n)=>{n.r(t),n.d(t,{default:()=>h});var o=n(70821),a=n(39038),r=n(5556),l=n(96898),s={class:"font-sans text-gray-900 antialiased"},i={class:"pt-4 bg-gray-100 rounded"},c={class:"flex flex-col items-center pt-6 sm:pt-0"},d={class:"privacy mb-5 mt-10 overflow-scroll"},u=["innerHTML"],p={layout:l.Z};const m=Object.assign(p,{__name:"PrivacyPolicy",props:{policy:String},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[(0,o.createVNode)((0,o.unref)(a.Fb),{title:"Privacy Policy"}),(0,o.createElementVNode)("div",s,[(0,o.createElementVNode)("div",i,[(0,o.createElementVNode)("div",c,[(0,o.createElementVNode)("div",null,[(0,o.createVNode)(r.Z)]),(0,o.createElementVNode)("div",d,[(0,o.createElementVNode)("div",{class:"w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md sm:rounded-lg prose",innerHTML:e.policy},null,8,u)])])])])],64)}}});var v=n(93379),g=n.n(v),f=n(67877),y={insert:"head",singleton:!1};g()(f.Z,y);f.Z.locals;const h=(0,n(83744).Z)(m,[["__scopeId","data-v-623cea80"]])}}]);
//# sourceMappingURL=1032.js.map