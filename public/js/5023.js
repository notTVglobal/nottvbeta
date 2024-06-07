"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5023],{32242:(e,t,n)=>{n.d(t,{Z:()=>c});var r=n(70821),o={class:"min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"},l={class:"w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"};const a={},c=(0,n(83744).Z)(a,[["render",function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("div",o,[(0,r.createElementVNode)("div",null,[(0,r.renderSlot)(e.$slots,"logo")]),(0,r.createElementVNode)("div",l,[(0,r.renderSlot)(e.$slots,"default")])])}]])},5556:(e,t,n)=>{n.d(t,{Z:()=>c});var r=n(70821),o=n(58358),l=n(87879),a=["src"];const c={__name:"AuthenticationCardLogo",setup:function(e){var t=(0,o.useAppSettingStore)();t.pageReload=!1;var n=function(){t.noLayout?(t.pageReload=!0,l.Nd.visit("/")):l.Nd.visit("/")};return function(e,t){return(0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(l.rU),{onClick:n,href:"#"},{default:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,a)]})),_:1})}}}},92587:(e,t,n)=>{n.d(t,{Z:()=>d});var r=n(70821),o=n(58358),l=n(50329),a=n(54277),c=n(7841),i=n(18138),s=(0,r.createElementVNode)("div",null,null,-1),u={class:"overflow-scroll hide-scrollbar scrollbar-hid"};const d={__name:"NoLayout",setup:function(e){var t=(0,o.useAppSettingStore)(),n=(0,l.useSocialShareStore)();return(0,r.onBeforeMount)((function(){t.checkScreenSize()})),function(e,o){return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[s,(0,r.createElementVNode)("div",u,[(0,r.renderSlot)(e.$slots,"default")]),(0,r.unref)(t).showImageLightboxModal?((0,r.openBlock)(),(0,r.createBlock)(c.Z,{key:0})):(0,r.createCommentVNode)("",!0),(0,r.createVNode)(a.Z),(0,r.unref)(n).socialSharingModal?((0,r.openBlock)(),(0,r.createBlock)(i.Z,{key:1})):(0,r.createCommentVNode)("",!0)],64)}}}},25023:(e,t,n)=>{n.r(t),n.d(t,{default:()=>d});var r=n(70821),o=n(87879),l=n(32242),a=(n(5556),n(28277),n(92587)),c=["src"],i=(0,r.createElementVNode)("div",{class:"my-4 font-medium text-center text-sm text-green-600"}," A new verification link has been sent to your email! ",-1),s={class:"mt-4 flex items-center justify-center"},u={layout:a.Z};const d=Object.assign(u,{__name:"VerifySent",props:{status:String},setup:function(e){var t=e,n=(0,o.cI)(),a=function(){n.post(route("verification.send"))};(0,r.computed)((function(){return"verification-link-sent"===t.status}));return function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)((0,r.unref)(o.Fb),{title:"Email Verification"}),(0,r.createVNode)(l.Z,null,{logo:(0,r.withCtx)((function(){return[(0,r.createVNode)((0,r.unref)(o.rU),{href:"/",class:"underline text-sm text-gray-600 hover:text-gray-900 ml-2"},{default:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center max-w-[16rem]"},null,8,c)]})),_:1})]})),default:(0,r.withCtx)((function(){return[i,(0,r.createElementVNode)("form",{onSubmit:(0,r.withModifiers)(a,["prevent"])},[(0,r.createElementVNode)("div",s,[(0,r.createVNode)((0,r.unref)(o.rU),{href:e.route("logout"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 ml-2"},{default:(0,r.withCtx)((function(){return[(0,r.createTextVNode)(" Log Out ")]})),_:1},8,["href"])])],32)]})),_:1})],64)}}})}}]);
//# sourceMappingURL=5023.js.map