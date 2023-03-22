"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7980],{5425:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(821),o=(0,n.createElementVNode)("div",null,null,-1);const l={},a=(0,r(3744).Z)(l,[["render",function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[o,(0,n.renderSlot)(e.$slots,"default")],64)}]])},7980:(e,t,r)=>{r.r(t),r.d(t,{default:()=>j});var n=r(821),o=r(9038),l=r(3206),a=r(5501),c=r(7041),u=r(1959),s=r(5300),i=r(7088),m=r(9470),d=r(5425);function f(e){return f="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},f(e)}function p(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function b(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?p(Object(r),!0).forEach((function(t){y(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):p(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function y(e,t,r){return(t=function(e){var t=function(e,t){if("object"!==f(e)||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!==f(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"===f(t)?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var w={key:0,class:"mb-4 font-medium text-sm text-green-600"},v=["onSubmit"],g={class:"mt-4"},V={class:"block mt-4"},N={class:"flex items-center"},k=(0,n.createElementVNode)("span",{class:"ml-2 text-sm text-gray-600"},"Remember me",-1),h={class:"flex items-center justify-end mt-4"},O={layout:d.Z};const j=Object.assign(O,{__name:"Login",props:{canResetPassword:Boolean,status:String,userType:Number},setup:function(e){var t=(0,o.cI)({email:"",password:"",remember:!1}),r=function(){t.transform((function(e){return b(b({},e),{},{remember:t.remember?"on":""})})).post(route("login"),{onFinish:function(){return t.reset("password")}})};return function(o,d){var f=(0,n.resolveComponent)("Head"),p=(0,n.resolveComponent)("Link");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(f,{title:"Log in"}),(0,n.createVNode)(l.Z,null,{logo:(0,n.withCtx)((function(){return[(0,n.createVNode)(a.Z)]})),default:(0,n.withCtx)((function(){return[(0,n.createVNode)(m.Z,{class:"mb-4"}),e.status?((0,n.openBlock)(),(0,n.createElementBlock)("div",w,(0,n.toDisplayString)(e.status),1)):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("form",{onSubmit:(0,n.withModifiers)(r,["prevent"])},[(0,n.createElementVNode)("div",null,[(0,n.createVNode)(i.Z,{for:"email",value:"Email"}),(0,n.createVNode)(u.Z,{id:"email",modelValue:(0,n.unref)(t).email,"onUpdate:modelValue":d[0]||(d[0]=function(e){return(0,n.unref)(t).email=e}),type:"email",class:"mt-1 block w-full",required:"",autofocus:""},null,8,["modelValue"])]),(0,n.createElementVNode)("div",g,[(0,n.createVNode)(i.Z,{for:"password",value:"Password"}),(0,n.createVNode)(u.Z,{id:"password",modelValue:(0,n.unref)(t).password,"onUpdate:modelValue":d[1]||(d[1]=function(e){return(0,n.unref)(t).password=e}),type:"password",class:"mt-1 block w-full",required:"",autocomplete:"current-password"},null,8,["modelValue"])]),(0,n.createElementVNode)("div",V,[(0,n.createElementVNode)("label",N,[(0,n.createVNode)(s.Z,{checked:(0,n.unref)(t).remember,"onUpdate:checked":d[2]||(d[2]=function(e){return(0,n.unref)(t).remember=e}),name:"remember"},null,8,["checked"]),k])]),(0,n.createElementVNode)("div",h,[e.canResetPassword?((0,n.openBlock)(),(0,n.createBlock)(p,{key:0,href:o.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Forgot your password? ")]})),_:1},8,["href"])):(0,n.createCommentVNode)("",!0),(0,n.createVNode)(c.Z,{class:(0,n.normalizeClass)(["ml-4",{"opacity-25":(0,n.unref)(t).processing}]),disabled:(0,n.unref)(t).processing},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Log in ")]})),_:1},8,["class","disabled"])])],40,v)]})),_:1})],64)}}})}}]);
//# sourceMappingURL=7980.js.map