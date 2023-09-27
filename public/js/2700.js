"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2700],{32242:(e,t,n)=>{n.d(t,{Z:()=>u});var o=n(70821),r={class:"min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"},l={class:"w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"};const a={},u=(0,n(83744).Z)(a,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,[(0,o.createElementVNode)("div",null,[(0,o.renderSlot)(e.$slots,"logo")]),(0,o.createElementVNode)("div",l,[(0,o.renderSlot)(e.$slots,"default")])])}]])},5556:(e,t,n)=>{n.d(t,{Z:()=>a});var o=n(70821),r=n(39038),l=["src"];const a={__name:"AuthenticationCardLogo",setup:function(e){return function(e,t){return(0,o.openBlock)(),(0,o.createBlock)((0,o.unref)(r.rU),{href:"/"},{default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,l)]})),_:1})}}}},28277:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r=["type"];const l={__name:"Button",props:{type:{type:String,default:"submit"}},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"},[(0,o.renderSlot)(t.$slots,"default")],8,r)}}}},6710:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r=["value"];const l={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var n=t.expose,l=(0,o.ref)(null);return(0,o.onMounted)((function(){l.value.hasAttribute("autofocus")&&l.value.focus()})),n({focus:function(){return l.value.focus()}}),function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("input",{ref_key:"input",ref:l,class:"border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:e.modelValue,onInput:n[0]||(n[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,r)}}}},15957:(e,t,n)=>{n.d(t,{Z:()=>u});var o=n(70821),r={class:"block font-medium text-sm text-gray-700"},l={key:0},a={key:1};const u={__name:"Label",props:{value:String},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("label",r,[e.value?((0,o.openBlock)(),(0,o.createElementBlock)("span",l,(0,o.toDisplayString)(e.value),1)):((0,o.openBlock)(),(0,o.createElementBlock)("span",a,[(0,o.renderSlot)(t.$slots,"default")]))])}}}},30131:(e,t,n)=>{n.d(t,{Z:()=>s});var o=n(70821),r=n(39038),l={key:0},a=(0,o.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),u={class:"mt-3 list-disc list-inside text-sm text-red-600"};const s={__name:"ValidationErrors",setup:function(e){var t=(0,o.computed)((function(){return(0,r.qt)().props.value.errors})),n=(0,o.computed)((function(){return Object.keys(t.value).length>0}));return function(e,r){return n.value?((0,o.openBlock)(),(0,o.createElementBlock)("div",l,[a,(0,o.createElementVNode)("ul",u,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(t.value,(function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("li",{key:t},(0,o.toDisplayString)(e),1)})),128))])])):(0,o.createCommentVNode)("",!0)}}}},34350:(e,t,n)=>{n.d(t,{Z:()=>u});var o=n(70821),r=(0,o.createElementVNode)("div",null,null,-1),l={class:"h-full w-full overflow-scroll"};const a={},u=(0,n(83744).Z)(a,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[r,(0,o.createElementVNode)("div",l,[(0,o.renderSlot)(e.$slots,"default")])],64)}]])},52700:(e,t,n)=>{n.r(t),n.d(t,{default:()=>v});var o=n(70821),r=n(39038),l=n(32242),a=n(5556),u=n(28277),s=n(6710),c=n(15957),i=n(30131),d=n(34350),m=(0,o.createElementVNode)("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),f={key:0,class:"mb-4 font-medium text-sm text-green-600"},p=["onSubmit"],g={class:"flex items-center justify-end mt-4"},k={layout:d.Z};const v=Object.assign(k,{__name:"ForgotPassword",props:{status:String},setup:function(e){var t=(0,r.cI)({email:""}),n=function(){t.post(route("password.email"))};return function(d,k){return(0,o.openBlock)(),(0,o.createElementBlock)(o.Fragment,null,[(0,o.createVNode)((0,o.unref)(r.Fb),{title:"Forgot Password"}),(0,o.createVNode)(l.Z,null,{logo:(0,o.withCtx)((function(){return[(0,o.createVNode)(a.Z)]})),default:(0,o.withCtx)((function(){return[m,e.status?((0,o.openBlock)(),(0,o.createElementBlock)("div",f,(0,o.toDisplayString)(e.status),1)):(0,o.createCommentVNode)("",!0),(0,o.createVNode)(i.Z,{class:"mb-4"}),(0,o.createElementVNode)("form",{onSubmit:(0,o.withModifiers)(n,["prevent"])},[(0,o.createElementVNode)("div",null,[(0,o.createVNode)(c.Z,{for:"email",value:"Email"}),(0,o.createVNode)(s.Z,{id:"email",modelValue:(0,o.unref)(t).email,"onUpdate:modelValue":k[0]||(k[0]=function(e){return(0,o.unref)(t).email=e}),type:"email",class:"mt-1 block w-full",required:"",autofocus:""},null,8,["modelValue"])]),(0,o.createElementVNode)("div",g,[(0,o.createVNode)(u.Z,{class:(0,o.normalizeClass)({"opacity-25":(0,o.unref)(t).processing}),disabled:(0,o.unref)(t).processing},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Email Password Reset Link ")]})),_:1},8,["class","disabled"])])],40,p)]})),_:1})],64)}}})}}]);
//# sourceMappingURL=2700.js.map