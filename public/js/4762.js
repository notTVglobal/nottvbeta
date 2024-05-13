"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4762,2724],{73605:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r={class:"text-sm text-gray-600"};const l={__name:"ActionMessage",props:{on:Boolean},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createVNode)(o.Transition,{"leave-active-class":"transition ease-in duration-1000","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:(0,o.withCtx)((function(){return[(0,o.withDirectives)((0,o.createElementVNode)("div",r,[(0,o.renderSlot)(t.$slots,"default")],512),[[o.vShow,e.on]])]})),_:3})])}}}},47639:(e,t,n)=>{n.d(t,{Z:()=>c});var o=n(70821),r=n(97012),l={class:"md:grid md:grid-cols-3 md:gap-6"},s={class:"mt-5 md:mt-0 md:col-span-2"},a={class:"px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg"};const c={__name:"ActionSection",setup:function(e){return function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",l,[(0,o.createVNode)(r.Z,null,{title:(0,o.withCtx)((function(){return[(0,o.renderSlot)(e.$slots,"title")]})),description:(0,o.withCtx)((function(){return[(0,o.renderSlot)(e.$slots,"description")]})),_:3}),(0,o.createElementVNode)("div",s,[(0,o.createElementVNode)("div",a,[(0,o.renderSlot)(e.$slots,"content")])])])}}}},43497:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r=["value"];const l={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{type:String,default:null}},emits:["update:checked"],setup:function(e,t){var n=t.emit,l=e,s=(0,o.computed)({get:function(){return l.checked},set:function(e){n("update:checked",e)}});return function(t,n){return(0,o.withDirectives)(((0,o.openBlock)(),(0,o.createElementBlock)("input",{"onUpdate:modelValue":n[0]||(n[0]=function(e){return s.value=e}),type:"checkbox",value:e.value,class:"rounded border-2 border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"},null,8,r)),[[o.vModelCheckbox,s.value]])}}}},82208:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r=["type"];const l={__name:"DangerButton",props:{type:{type:String,default:"button"}},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("button",{type:e.type,class:"inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition"},[(0,o.renderSlot)(t.$slots,"default")],8,r)}}}},33261:(e,t,n)=>{n.d(t,{Z:()=>i});var o=n(70821),r=n(70543),l={class:"px-6 py-4"},s={class:"text-lg"},a={class:"mt-4"},c={class:"flex flex-row justify-end px-6 py-4 bg-gray-100 text-right"};const i={__name:"DialogModal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup:function(e,t){var n=t.emit,i=function(){n("close")};return function(t,n){return(0,o.openBlock)(),(0,o.createBlock)(r.Z,{show:e.show,"max-width":e.maxWidth,closeable:e.closeable,onClose:i},{default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",l,[(0,o.createElementVNode)("div",s,[(0,o.renderSlot)(t.$slots,"title")]),(0,o.createElementVNode)("div",a,[(0,o.renderSlot)(t.$slots,"content")])]),(0,o.createElementVNode)("div",c,[(0,o.renderSlot)(t.$slots,"footer")])]})),_:3},8,["show","max-width","closeable"])}}}},71333:(e,t,n)=>{n.d(t,{Z:()=>i});var o=n(70821),r=n(97012),l={class:"md:grid md:grid-cols-3 md:gap-6"},s={class:"mt-5 md:mt-0 md:col-span-2"},a={class:"grid grid-cols-6 gap-6"},c={key:0,class:"flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md"};const i={__name:"FormSection",emits:["submitted"],setup:function(e){var t=(0,o.computed)((function(){return!!(0,o.useSlots)().actions}));return function(e,n){return(0,o.openBlock)(),(0,o.createElementBlock)("div",l,[(0,o.createVNode)(r.Z,null,{title:(0,o.withCtx)((function(){return[(0,o.renderSlot)(e.$slots,"title")]})),description:(0,o.withCtx)((function(){return[(0,o.renderSlot)(e.$slots,"description")]})),_:3}),(0,o.createElementVNode)("div",s,[(0,o.createElementVNode)("form",{onSubmit:n[0]||(n[0]=(0,o.withModifiers)((function(t){return e.$emit("submitted")}),["prevent"]))},[(0,o.createElementVNode)("div",{class:(0,o.normalizeClass)(["px-4 py-5 bg-white sm:p-6 shadow",t.value?"sm:rounded-tl-md sm:rounded-tr-md":"sm:rounded-md"])},[(0,o.createElementVNode)("div",a,[(0,o.renderSlot)(e.$slots,"form")])],2),t.value?((0,o.openBlock)(),(0,o.createElementBlock)("div",c,[(0,o.renderSlot)(e.$slots,"actions")])):(0,o.createCommentVNode)("",!0)],32)])])}}}},6710:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r=["value"];const l={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var n=t.expose,l=(0,o.ref)(null);return(0,o.onMounted)((function(){l.value.hasAttribute("autofocus")&&l.value.focus()})),n({focus:function(){return l.value.focus()}}),function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("input",{ref_key:"input",ref:l,class:"border-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm text-black bg-white dark:bg-gray-800 dark:text-white",value:e.modelValue,onInput:n[0]||(n[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,r)}}}},49883:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r={class:"text-sm text-red-600"};const l={__name:"InputError",props:{message:String},setup:function(e){return function(t,n){return(0,o.withDirectives)(((0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createElementVNode)("p",r,(0,o.toDisplayString)(e.message),1)],512)),[[o.vShow,e.message]])}}}},15957:(e,t,n)=>{n.d(t,{Z:()=>a});var o=n(70821),r={class:"block font-medium text-sm text-gray-700"},l={key:0},s={key:1};const a={__name:"Label",props:{value:String},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("label",r,[e.value?((0,o.openBlock)(),(0,o.createElementBlock)("span",l,(0,o.toDisplayString)(e.value),1)):((0,o.openBlock)(),(0,o.createElementBlock)("span",s,[(0,o.renderSlot)(t.$slots,"default")]))])}}}},70543:(e,t,n)=>{n.d(t,{Z:()=>s});var o=n(70821),r={class:"fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50","scroll-region":""},l=[(0,o.createElementVNode)("div",{class:"absolute inset-0 bg-gray-500 opacity-75"},null,-1)];const s={__name:"Modal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup:function(e,t){var n=t.emit,s=e,a=n;(0,o.watch)((function(){return s.show}),(function(){s.show?document.body.style.overflow="hidden":document.body.style.overflow=null}));var c=function(){s.closeable&&a("close")},i=function(e){"Escape"===e.key&&s.show&&c()};(0,o.onMounted)((function(){return document.addEventListener("keydown",i)})),(0,o.onUnmounted)((function(){document.removeEventListener("keydown",i),document.body.style.overflow=null}));var u=(0,o.computed)((function(){return{sm:"sm:max-w-sm",md:"sm:max-w-md",lg:"sm:max-w-lg",xl:"sm:max-w-xl","2xl":"sm:max-w-2xl"}[s.maxWidth]}));return function(t,n){return(0,o.openBlock)(),(0,o.createBlock)(o.Teleport,{to:"body"},[(0,o.createVNode)(o.Transition,{"leave-active-class":"duration-200"},{default:(0,o.withCtx)((function(){return[(0,o.withDirectives)((0,o.createElementVNode)("div",r,[(0,o.createVNode)(o.Transition,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0","enter-to-class":"opacity-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:(0,o.withCtx)((function(){return[(0,o.withDirectives)((0,o.createElementVNode)("div",{class:"fixed inset-0 transform transition-all",onClick:c},l,512),[[o.vShow,e.show]])]})),_:1}),(0,o.createVNode)(o.Transition,{"enter-active-class":"ease-out duration-300","enter-from-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95","enter-to-class":"opacity-100 translate-y-0 sm:scale-100","leave-active-class":"ease-in duration-200","leave-from-class":"opacity-100 translate-y-0 sm:scale-100","leave-to-class":"opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"},{default:(0,o.withCtx)((function(){return[(0,o.withDirectives)((0,o.createElementVNode)("div",{class:(0,o.normalizeClass)(["mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto",u.value])},[e.show?(0,o.renderSlot)(t.$slots,"default",{key:0}):(0,o.createCommentVNode)("",!0)],2),[[o.vShow,e.show]])]})),_:3})],512),[[o.vShow,e.show]])]})),_:3})])}}}},26580:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r=["type"];const l={__name:"SecondaryButton",props:{type:{type:String,default:"button"}},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition"},[(0,o.renderSlot)(t.$slots,"default")],8,r)}}}},85538:(e,t,n)=>{n.d(t,{Z:()=>a});var o=n(70821),r={class:"hidden sm:block"},l=[(0,o.createElementVNode)("div",{class:"py-8"},[(0,o.createElementVNode)("div",{class:"border-t border-gray-200"})],-1)];const s={},a=(0,n(83744).Z)(s,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,l)}]])},97012:(e,t,n)=>{n.d(t,{Z:()=>u});var o=n(70821),r={class:"md:col-span-1 flex justify-between"},l={class:"px-4 sm:px-0"},s={class:"text-lg font-medium text-gray-100"},a={class:"mt-1 text-sm text-gray-300"},c={class:"px-4 sm:px-0"};const i={},u=(0,n(83744).Z)(i,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,[(0,o.createElementVNode)("div",l,[(0,o.createElementVNode)("h3",s,[(0,o.renderSlot)(e.$slots,"title")]),(0,o.createElementVNode)("p",a,[(0,o.renderSlot)(e.$slots,"description")])]),(0,o.createElementVNode)("div",c,[(0,o.renderSlot)(e.$slots,"aside")])])}]])},44762:(e,t,n)=>{n.r(t),n.d(t,{default:()=>l});var o=n(70821),r=(n(32724),n(66504),{class:"font-semibold text-xl text-gray-800 leading-tight"});const l={__name:"Index",props:{tokens:Array,availablePermissions:Array,defaultPermissions:Array},setup:function(e){return function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("h2",r," API Tokens ")}}}},32724:(e,t,n)=>{n.r(t),n.d(t,{default:()=>U});var o=n(70821),r=n(39038),l=n(73605),s=n(47639),a=n(28277),c=n(70543),i={class:"bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4"},u={class:"sm:flex sm:items-start"},d=(0,o.createElementVNode)("div",{class:"mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"},[(0,o.createElementVNode)("svg",{class:"h-6 w-6 text-red-600",stroke:"currentColor",fill:"none",viewBox:"0 0 24 24"},[(0,o.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"})])],-1),m={class:"mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"},f={class:"text-lg"},p={class:"mt-2"},v={class:"flex flex-row justify-end px-6 py-4 bg-gray-100 text-right"};const x={__name:"ConfirmationModal",props:{show:{type:Boolean,default:!1},maxWidth:{type:String,default:"2xl"},closeable:{type:Boolean,default:!0}},emits:["close"],setup:function(e,t){var n=t.emit,r=function(){n("close")};return function(t,n){return(0,o.openBlock)(),(0,o.createBlock)(c.Z,{show:e.show,"max-width":e.maxWidth,closeable:e.closeable,onClose:r},{default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",i,[(0,o.createElementVNode)("div",u,[d,(0,o.createElementVNode)("div",m,[(0,o.createElementVNode)("h3",f,[(0,o.renderSlot)(t.$slots,"title")]),(0,o.createElementVNode)("div",p,[(0,o.renderSlot)(t.$slots,"content")])])])]),(0,o.createElementVNode)("div",v,[(0,o.renderSlot)(t.$slots,"footer")])]})),_:3},8,["show","max-width","closeable"])}}};var y=n(82208),h=n(33261),k=n(71333),g=n(6710),w=n(43497),b=n(49883),V=n(15957),N=n(26580),E=n(85538),C={class:"col-span-6 sm:col-span-4"},B={key:0,class:"col-span-6"},S={class:"mt-2 grid grid-cols-1 md:grid-cols-2 gap-4"},_={class:"flex items-center"},Z={class:"ml-2 text-sm text-gray-600"},T={key:0},$={class:"mt-10 sm:mt-0"},P={class:"space-y-6"},A={class:"flex items-center"},D={key:0,class:"text-sm text-gray-400"},I=["onClick"],M=["onClick"],j=(0,o.createElementVNode)("div",null," Please copy your new API token. For your security, it won't be shown again. ",-1),L={key:0,class:"mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500"},z={class:"grid grid-cols-1 md:grid-cols-2 gap-4"},W={class:"flex items-center"},F={class:"ml-2 text-sm text-gray-600"};const U={__name:"ApiTokenManager",props:{tokens:Array,availablePermissions:Array,defaultPermissions:Array},setup:function(e){var t=e,n=(0,r.cI)({name:"",permissions:t.defaultPermissions}),c=(0,r.cI)({permissions:[]}),i=(0,r.cI)(),u=(0,o.ref)(!1),d=(0,o.ref)(null),m=(0,o.ref)(null),f=function(){n.post(route("api-tokens.store"),{preserveScroll:!0,onSuccess:function(){u.value=!0,n.reset()}})},p=function(){c.put(route("api-tokens.update",d.value),{preserveScroll:!0,preserveState:!0,onSuccess:function(){return d.value=null}})},v=function(){i.delete(route("api-tokens.destroy",m.value),{preserveScroll:!0,preserveState:!0,onSuccess:function(){return m.value=null}})};return function(t,r){return(0,o.openBlock)(),(0,o.createElementBlock)("div",null,[(0,o.createVNode)(k.Z,{onSubmitted:f},{title:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Create API Token ")]})),description:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" API tokens allow third-party services to authenticate with our application on your behalf. ")]})),form:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",C,[(0,o.createVNode)(V.Z,{for:"name",value:"Name"}),(0,o.createVNode)(g.Z,{id:"name",modelValue:(0,o.unref)(n).name,"onUpdate:modelValue":r[0]||(r[0]=function(e){return(0,o.unref)(n).name=e}),type:"text",class:"mt-1 block w-full",autofocus:""},null,8,["modelValue"]),(0,o.createVNode)(b.Z,{message:(0,o.unref)(n).errors.name,class:"mt-2"},null,8,["message"])]),e.availablePermissions.length>0?((0,o.openBlock)(),(0,o.createElementBlock)("div",B,[(0,o.createVNode)(V.Z,{for:"permissions",value:"Permissions"}),(0,o.createElementVNode)("div",S,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(e.availablePermissions,(function(e){return(0,o.openBlock)(),(0,o.createElementBlock)("div",{key:e},[(0,o.createElementVNode)("label",_,[(0,o.createVNode)(w.Z,{checked:(0,o.unref)(n).permissions,"onUpdate:checked":r[1]||(r[1]=function(e){return(0,o.unref)(n).permissions=e}),value:e},null,8,["checked","value"]),(0,o.createElementVNode)("span",Z,(0,o.toDisplayString)(e),1)])])})),128))])])):(0,o.createCommentVNode)("",!0)]})),actions:(0,o.withCtx)((function(){return[(0,o.createVNode)(l.Z,{on:(0,o.unref)(n).recentlySuccessful,class:"mr-3"},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Created. ")]})),_:1},8,["on"]),(0,o.createVNode)(a.Z,{class:(0,o.normalizeClass)({"opacity-25":(0,o.unref)(n).processing}),disabled:(0,o.unref)(n).processing},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Create ")]})),_:1},8,["class","disabled"])]})),_:1}),e.tokens.length>0?((0,o.openBlock)(),(0,o.createElementBlock)("div",T,[(0,o.createVNode)(E.Z),(0,o.createElementVNode)("div",$,[(0,o.createVNode)(s.Z,null,{title:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Manage API Tokens ")]})),description:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" You may delete any of your existing tokens if they are no longer needed. ")]})),content:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",P,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(e.tokens,(function(t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",{key:t.id,class:"flex items-center justify-between"},[(0,o.createElementVNode)("div",null,(0,o.toDisplayString)(t.name),1),(0,o.createElementVNode)("div",A,[t.last_used_ago?((0,o.openBlock)(),(0,o.createElementBlock)("div",D," Last used "+(0,o.toDisplayString)(t.last_used_ago),1)):(0,o.createCommentVNode)("",!0),e.availablePermissions.length>0?((0,o.openBlock)(),(0,o.createElementBlock)("button",{key:1,class:"cursor-pointer ml-6 text-sm text-gray-400 underline",onClick:function(e){return function(e){c.permissions=e.abilities,d.value=e}(t)}}," Permissions ",8,I)):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("button",{class:"cursor-pointer ml-6 text-sm text-red-500",onClick:function(e){return function(e){m.value=e}(t)}}," Delete ",8,M)])])})),128))])]})),_:1})])])):(0,o.createCommentVNode)("",!0),(0,o.createVNode)(h.Z,{show:u.value,onClose:r[3]||(r[3]=function(e){return u.value=!1})},{title:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" API Token ")]})),content:(0,o.withCtx)((function(){return[j,t.$page.props.jetstream.flash.token?((0,o.openBlock)(),(0,o.createElementBlock)("div",L,(0,o.toDisplayString)(t.$page.props.jetstream.flash.token),1)):(0,o.createCommentVNode)("",!0)]})),footer:(0,o.withCtx)((function(){return[(0,o.createVNode)(N.Z,{onClick:r[2]||(r[2]=function(e){return u.value=!1})},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Close ")]})),_:1})]})),_:1},8,["show"]),(0,o.createVNode)(h.Z,{show:null!=d.value,onClose:r[6]||(r[6]=function(e){return d.value=null})},{title:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" API Token Permissions ")]})),content:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("div",z,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(e.availablePermissions,(function(e){return(0,o.openBlock)(),(0,o.createElementBlock)("div",{key:e},[(0,o.createElementVNode)("label",W,[(0,o.createVNode)(w.Z,{checked:(0,o.unref)(c).permissions,"onUpdate:checked":r[4]||(r[4]=function(e){return(0,o.unref)(c).permissions=e}),value:e},null,8,["checked","value"]),(0,o.createElementVNode)("span",F,(0,o.toDisplayString)(e),1)])])})),128))])]})),footer:(0,o.withCtx)((function(){return[(0,o.createVNode)(N.Z,{onClick:r[5]||(r[5]=function(e){return d.value=null})},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Cancel ")]})),_:1}),(0,o.createVNode)(a.Z,{class:(0,o.normalizeClass)(["ml-3",{"opacity-25":(0,o.unref)(c).processing}]),disabled:(0,o.unref)(c).processing,onClick:p},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Save ")]})),_:1},8,["class","disabled"])]})),_:1},8,["show"]),(0,o.createVNode)(x,{show:null!=m.value,onClose:r[8]||(r[8]=function(e){return m.value=null})},{title:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Delete API Token ")]})),content:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Are you sure you would like to delete this API token? ")]})),footer:(0,o.withCtx)((function(){return[(0,o.createVNode)(N.Z,{onClick:r[7]||(r[7]=function(e){return m.value=null})},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Cancel ")]})),_:1}),(0,o.createVNode)(y.Z,{class:(0,o.normalizeClass)(["ml-3",{"opacity-25":(0,o.unref)(i).processing}]),disabled:(0,o.unref)(i).processing,onClick:v},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Delete ")]})),_:1},8,["class","disabled"])]})),_:1},8,["show"])])}}}}}]);
//# sourceMappingURL=4762.js.map