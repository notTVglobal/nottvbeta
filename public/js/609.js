"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[609],{73605:(e,t,r)=>{r.d(t,{Z:()=>s});var o=r(70821),n={class:"text-sm text-gray-600"};const s={__name:"ActionMessage",props:{on:Boolean},setup:function(e){return function(t,r){return(0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createVNode)(o.Transition,{"leave-active-class":"transition ease-in duration-1000","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:(0,o.withCtx)((function(){return[(0,o.withDirectives)((0,o.createElementVNode)("div",n,[(0,o.renderSlot)(t.$slots,"default")],512),[[o.vShow,e.on]])]})),_:3})])}}}},28277:(e,t,r)=>{r.d(t,{Z:()=>s});var o=r(70821),n=["type"];const s={__name:"Button",props:{type:{type:String,default:"submit"}},setup:function(e){return function(t,r){return(0,o.openBlock)(),(0,o.createElementBlock)("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"},[(0,o.renderSlot)(t.$slots,"default")],8,n)}}}},71333:(e,t,r)=>{r.d(t,{Z:()=>u});var o=r(70821),n=r(97012),s={class:"md:grid md:grid-cols-3 md:gap-6"},a={class:"mt-5 md:mt-0 md:col-span-2"},l={class:"grid grid-cols-6 gap-6"},c={key:0,class:"flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md"};const u={__name:"FormSection",emits:["submitted"],setup:function(e){var t=(0,o.computed)((function(){return!!(0,o.useSlots)().actions}));return function(e,r){return(0,o.openBlock)(),(0,o.createElementBlock)("div",s,[(0,o.createVNode)(n.Z,null,{title:(0,o.withCtx)((function(){return[(0,o.renderSlot)(e.$slots,"title")]})),description:(0,o.withCtx)((function(){return[(0,o.renderSlot)(e.$slots,"description")]})),_:3}),(0,o.createElementVNode)("div",a,[(0,o.createElementVNode)("form",{onSubmit:r[0]||(r[0]=(0,o.withModifiers)((function(t){return e.$emit("submitted")}),["prevent"]))},[(0,o.createElementVNode)("div",{class:(0,o.normalizeClass)(["px-4 py-5 bg-white sm:p-6 shadow",t.value?"sm:rounded-tl-md sm:rounded-tr-md":"sm:rounded-md"])},[(0,o.createElementVNode)("div",l,[(0,o.renderSlot)(e.$slots,"form")])],2),t.value?((0,o.openBlock)(),(0,o.createElementBlock)("div",c,[(0,o.renderSlot)(e.$slots,"actions")])):(0,o.createCommentVNode)("",!0)],32)])])}}}},6710:(e,t,r)=>{r.d(t,{Z:()=>s});var o=r(70821),n=["value"];const s={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var r=t.expose,s=(0,o.ref)(null);return(0,o.onMounted)((function(){s.value.hasAttribute("autofocus")&&s.value.focus()})),r({focus:function(){return s.value.focus()}}),function(t,r){return(0,o.openBlock)(),(0,o.createElementBlock)("input",{ref_key:"input",ref:s,class:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:e.modelValue,onInput:r[0]||(r[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,n)}}}},49883:(e,t,r)=>{r.d(t,{Z:()=>s});var o=r(70821),n={class:"text-sm text-red-600"};const s={__name:"InputError",props:{message:String},setup:function(e){return function(t,r){return(0,o.withDirectives)(((0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createElementVNode)("p",n,(0,o.toDisplayString)(e.message),1)],512)),[[o.vShow,e.message]])}}}},15957:(e,t,r)=>{r.d(t,{Z:()=>l});var o=r(70821),n={class:"block font-medium text-sm text-gray-700"},s={key:0},a={key:1};const l={__name:"Label",props:{value:String},setup:function(e){return function(t,r){return(0,o.openBlock)(),(0,o.createElementBlock)("label",n,[e.value?((0,o.openBlock)(),(0,o.createElementBlock)("span",s,(0,o.toDisplayString)(e.value),1)):((0,o.openBlock)(),(0,o.createElementBlock)("span",a,[(0,o.renderSlot)(t.$slots,"default")]))])}}}},97012:(e,t,r)=>{r.d(t,{Z:()=>d});var o=r(70821),n={class:"md:col-span-1 flex justify-between"},s={class:"px-4 sm:px-0"},a={class:"text-lg font-medium text-gray-100"},l={class:"mt-1 text-sm text-gray-300"},c={class:"px-4 sm:px-0"};const u={},d=(0,r(83744).Z)(u,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",n,[(0,o.createElementVNode)("div",s,[(0,o.createElementVNode)("h3",a,[(0,o.renderSlot)(e.$slots,"title")]),(0,o.createElementVNode)("p",l,[(0,o.renderSlot)(e.$slots,"description")])]),(0,o.createElementVNode)("div",c,[(0,o.renderSlot)(e.$slots,"aside")])])}]])},20609:(e,t,r)=>{r.r(t),r.d(t,{default:()=>f});var o=r(70821),n=r(39038),s=r(73605),a=r(28277),l=r(71333),c=r(6710),u=r(49883),d=r(15957),i={class:"col-span-6 sm:col-span-4"},p={class:"col-span-6 sm:col-span-4"},m={class:"col-span-6 sm:col-span-4"};const f={__name:"UpdatePasswordForm",setup:function(e){var t=(0,o.ref)(null),r=(0,o.ref)(null),f=(0,n.cI)({current_password:"",password:"",password_confirmation:""}),w=function(){f.put(route("user-password.update"),{errorBag:"updatePassword",preserveScroll:!0,onSuccess:function(){return f.reset()},onError:function(){f.errors.password&&(f.reset("password","password_confirmation"),t.value.focus()),f.errors.current_password&&(f.reset("current_password"),r.value.focus())}})};return function(e,n){return(0,o.openBlock)(),(0,o.createBlock)(l.Z,{onSubmitted:w},{title:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Update Password ")]})),description:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Ensure your account is using a long, random password to stay secure. ")]})),form:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",i,[(0,o.createVNode)(d.Z,{for:"current_password",value:"Current Password"}),(0,o.createVNode)(c.Z,{id:"current_password",ref_key:"currentPasswordInput",ref:r,modelValue:(0,o.unref)(f).current_password,"onUpdate:modelValue":n[0]||(n[0]=function(e){return(0,o.unref)(f).current_password=e}),type:"password",class:"mt-1 block w-full",autocomplete:"current-password"},null,8,["modelValue"]),(0,o.createVNode)(u.Z,{message:(0,o.unref)(f).errors.current_password,class:"mt-2"},null,8,["message"])]),(0,o.createElementVNode)("div",p,[(0,o.createVNode)(d.Z,{for:"password",value:"New Password"}),(0,o.createVNode)(c.Z,{id:"password",ref_key:"passwordInput",ref:t,modelValue:(0,o.unref)(f).password,"onUpdate:modelValue":n[1]||(n[1]=function(e){return(0,o.unref)(f).password=e}),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),(0,o.createVNode)(u.Z,{message:(0,o.unref)(f).errors.password,class:"mt-2"},null,8,["message"])]),(0,o.createElementVNode)("div",m,[(0,o.createVNode)(d.Z,{for:"password_confirmation",value:"Confirm Password"}),(0,o.createVNode)(c.Z,{id:"password_confirmation",modelValue:(0,o.unref)(f).password_confirmation,"onUpdate:modelValue":n[2]||(n[2]=function(e){return(0,o.unref)(f).password_confirmation=e}),type:"password",class:"mt-1 block w-full",autocomplete:"new-password"},null,8,["modelValue"]),(0,o.createVNode)(u.Z,{message:(0,o.unref)(f).errors.password_confirmation,class:"mt-2"},null,8,["message"])])]})),actions:(0,o.withCtx)((function(){return[(0,o.createVNode)(s.Z,{on:(0,o.unref)(f).recentlySuccessful,class:"mr-3"},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Saved. ")]})),_:1},8,["on"]),(0,o.createVNode)(a.Z,{class:(0,o.normalizeClass)({"opacity-25":(0,o.unref)(f).processing}),disabled:(0,o.unref)(f).processing},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Save ")]})),_:1},8,["class","disabled"])]})),_:1})}}}}}]);
//# sourceMappingURL=609.js.map