"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[9559],{79559:(e,t,r)=>{r.r(t),r.d(t,{default:()=>s});var n=r(70821);function o(e){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},o(e)}function c(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function a(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?c(Object(r),!0).forEach((function(t){i(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):c(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function i(e,t,r){var n;return n=function(e,t){if("object"!=o(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=o(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(t,"string"),(t="symbol"==o(n)?n:String(n))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var u={id:"messages",role:"alert"},l=["href"],p=(0,n.createElementVNode)("br",null,null,-1);const s={__name:"SrMessages",props:{messages:{type:Array,required:!0}},setup:function(e){var t=e,r=(0,n.toRef)(t,"messages"),o=(0,n.computed)((function(){return r.value.map((function(e){var t=/(pi_(\S*)\b)/,r=e.match(t);return a(a({},r&&{paymentIntent:r[0]}),{},{content:e.replace(t,"")||e})}))}));return function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",u,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(o.value,(function(e){return(0,n.openBlock)(),(0,n.createElementBlock)("span",{key:e},[(0,n.createTextVNode)(" > "+(0,n.toDisplayString)(e.content),1),e.paymentIntent?((0,n.openBlock)(),(0,n.createElementBlock)("a",{key:0,href:(t=e.paymentIntent,"https://dashboard.stripe.com/test/payments/".concat(t))},(0,n.toDisplayString)(e.paymentIntent),9,l)):(0,n.createCommentVNode)("",!0),p]);var t})),128))])}}}}}]);
//# sourceMappingURL=9559.js.map