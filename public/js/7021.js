"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7021],{3605:(e,t,o)=>{o.d(t,{Z:()=>a});var n=o(821),r={class:"text-sm text-gray-600"};const a={__name:"ActionMessage",props:{on:Boolean},setup:function(e){return function(t,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createVNode)(n.Transition,{"leave-active-class":"transition ease-in duration-1000","leave-from-class":"opacity-100","leave-to-class":"opacity-0"},{default:(0,n.withCtx)((function(){return[(0,n.withDirectives)((0,n.createElementVNode)("div",r,[(0,n.renderSlot)(t.$slots,"default")],512),[[n.vShow,e.on]])]})),_:3})])}}}},8277:(e,t,o)=>{o.d(t,{Z:()=>a});var n=o(821),r=["type"];const a={__name:"Button",props:{type:{type:String,default:"submit"}},setup:function(e){return function(t,o){return(0,n.openBlock)(),(0,n.createElementBlock)("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"},[(0,n.renderSlot)(t.$slots,"default")],8,r)}}}},1333:(e,t,o)=>{o.d(t,{Z:()=>i});var n=o(821),r=o(7012),a={class:"md:grid md:grid-cols-3 md:gap-6"},l={class:"mt-5 md:mt-0 md:col-span-2"},s={class:"grid grid-cols-6 gap-6"},c={key:0,class:"flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md"};const i={__name:"FormSection",emits:["submitted"],setup:function(e){var t=(0,n.computed)((function(){return!!(0,n.useSlots)().actions}));return function(e,o){return(0,n.openBlock)(),(0,n.createElementBlock)("div",a,[(0,n.createVNode)(r.Z,null,{title:(0,n.withCtx)((function(){return[(0,n.renderSlot)(e.$slots,"title")]})),description:(0,n.withCtx)((function(){return[(0,n.renderSlot)(e.$slots,"description")]})),_:3}),(0,n.createElementVNode)("div",l,[(0,n.createElementVNode)("form",{onSubmit:o[0]||(o[0]=(0,n.withModifiers)((function(t){return e.$emit("submitted")}),["prevent"]))},[(0,n.createElementVNode)("div",{class:(0,n.normalizeClass)(["px-4 py-5 bg-white sm:p-6 shadow",t.value?"sm:rounded-tl-md sm:rounded-tr-md":"sm:rounded-md"])},[(0,n.createElementVNode)("div",s,[(0,n.renderSlot)(e.$slots,"form")])],2),t.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",c,[(0,n.renderSlot)(e.$slots,"actions")])):(0,n.createCommentVNode)("",!0)],32)])])}}}},6710:(e,t,o)=>{o.d(t,{Z:()=>a});var n=o(821),r=["value"];const a={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var o=t.expose,a=(0,n.ref)(null);return(0,n.onMounted)((function(){a.value.hasAttribute("autofocus")&&a.value.focus()})),o({focus:function(){return a.value.focus()}}),function(t,o){return(0,n.openBlock)(),(0,n.createElementBlock)("input",{ref_key:"input",ref:a,class:"border-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:e.modelValue,onInput:o[0]||(o[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,r)}}}},9883:(e,t,o)=>{o.d(t,{Z:()=>a});var n=o(821),r={class:"text-sm text-red-600"};const a={__name:"InputError",props:{message:String},setup:function(e){return function(t,o){return(0,n.withDirectives)(((0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("p",r,(0,n.toDisplayString)(e.message),1)],512)),[[n.vShow,e.message]])}}}},5957:(e,t,o)=>{o.d(t,{Z:()=>s});var n=o(821),r={class:"block font-medium text-sm text-gray-700"},a={key:0},l={key:1};const s={__name:"Label",props:{value:String},setup:function(e){return function(t,o){return(0,n.openBlock)(),(0,n.createElementBlock)("label",r,[e.value?((0,n.openBlock)(),(0,n.createElementBlock)("span",a,(0,n.toDisplayString)(e.value),1)):((0,n.openBlock)(),(0,n.createElementBlock)("span",l,[(0,n.renderSlot)(t.$slots,"default")]))])}}}},6580:(e,t,o)=>{o.d(t,{Z:()=>a});var n=o(821),r=["type"];const a={__name:"SecondaryButton",props:{type:{type:String,default:"button"}},setup:function(e){return function(t,o){return(0,n.openBlock)(),(0,n.createElementBlock)("button",{type:e.type,class:"inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition"},[(0,n.renderSlot)(t.$slots,"default")],8,r)}}}},7012:(e,t,o)=>{o.d(t,{Z:()=>u});var n=o(821),r={class:"md:col-span-1 flex justify-between"},a={class:"px-4 sm:px-0"},l={class:"text-lg font-medium text-gray-100"},s={class:"mt-1 text-sm text-gray-300"},c={class:"px-4 sm:px-0"};const i={},u=(0,o(3744).Z)(i,[["render",function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",r,[(0,n.createElementVNode)("div",a,[(0,n.createElementVNode)("h3",l,[(0,n.renderSlot)(e.$slots,"title")]),(0,n.createElementVNode)("p",s,[(0,n.renderSlot)(e.$slots,"description")])]),(0,n.createElementVNode)("div",c,[(0,n.renderSlot)(e.$slots,"aside")])])}]])},7021:(e,t,o)=>{o.r(t),o.d(t,{default:()=>b});var n=o(821),r=o(9680),a=o(9038),l=o(8277),s=o(1333),c=o(6710),i=o(9883),u=o(5957),d=o(3605),m=o(6580),p={key:0,class:"col-span-6 sm:col-span-4"},f={class:"mt-2"},v=["src","alt"],g={class:"mt-2"},h={class:"col-span-6 sm:col-span-4"},y={class:"col-span-6 sm:col-span-4"},x={key:0},V={class:"text-sm mt-2"},k={class:"mt-2 font-medium text-sm text-green-600"};const b={__name:"UpdateProfileInformationForm",props:{user:Object},setup:function(e){var t=e,o=(0,a.cI)({_method:"PUT",name:t.user.name,email:t.user.email,photo:null}),b=(0,n.ref)(null),N=(0,n.ref)(null),w=(0,n.ref)(null),S=function(){w.value&&(o.photo=w.value.files[0]),o.post(route("user-profile-information.update"),{errorBag:"updateProfileInformation",preserveScroll:!0,onSuccess:function(){return Z()}})},E=function(){b.value=!0},B=function(){w.value.click()},_=function(){var e=w.value.files[0];if(e){var t=new FileReader;t.onload=function(e){N.value=e.target.result},t.readAsDataURL(e)}},C=function(){r.Inertia.delete(route("current-user-photo.destroy"),{preserveScroll:!0,onSuccess:function(){N.value=null,Z()}})},Z=function(){var e;null!==(e=w.value)&&void 0!==e&&e.value&&(w.value.value=null)};return function(t,r){return(0,n.openBlock)(),(0,n.createBlock)(s.Z,{onSubmitted:S},{title:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Profile Information ")]})),description:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Update your account's profile information and email address. ")]})),form:(0,n.withCtx)((function(){return[t.$page.props.jetstream.managesProfilePhotos?((0,n.openBlock)(),(0,n.createElementBlock)("div",p,[(0,n.createElementVNode)("input",{ref_key:"photoInput",ref:w,type:"file",class:"hidden",onChange:_},null,544),(0,n.createVNode)(u.Z,{for:"photo",value:"Photo"}),(0,n.withDirectives)((0,n.createElementVNode)("div",f,[(0,n.createElementVNode)("img",{src:e.user.profile_photo_url,alt:e.user.name,class:"rounded-full h-20 w-20 object-cover"},null,8,v)],512),[[n.vShow,!N.value]]),(0,n.withDirectives)((0,n.createElementVNode)("div",g,[(0,n.createElementVNode)("span",{class:"block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center",style:(0,n.normalizeStyle)("background-image: url('"+N.value+"');")},null,4)],512),[[n.vShow,N.value]]),(0,n.createVNode)(m.Z,{class:"mt-2 mr-2",type:"button",onClick:(0,n.withModifiers)(B,["prevent"])},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Select A New Photo ")]})),_:1}),e.user.profile_photo_path?((0,n.openBlock)(),(0,n.createBlock)(m.Z,{key:0,type:"button",class:"mt-2",onClick:(0,n.withModifiers)(C,["prevent"])},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Remove Photo ")]})),_:1})):(0,n.createCommentVNode)("",!0),(0,n.createVNode)(i.Z,{message:(0,n.unref)(o).errors.photo,class:"mt-2"},null,8,["message"])])):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",h,[(0,n.createVNode)(u.Z,{for:"name",value:"Name"}),(0,n.createVNode)(c.Z,{id:"name",modelValue:(0,n.unref)(o).name,"onUpdate:modelValue":r[0]||(r[0]=function(e){return(0,n.unref)(o).name=e}),type:"text",class:"mt-1 block w-full",autocomplete:"name"},null,8,["modelValue"]),(0,n.createVNode)(i.Z,{message:(0,n.unref)(o).errors.name,class:"mt-2"},null,8,["message"])]),(0,n.createElementVNode)("div",y,[(0,n.createVNode)(u.Z,{for:"email",value:"Email"}),(0,n.createVNode)(c.Z,{id:"email",modelValue:(0,n.unref)(o).email,"onUpdate:modelValue":r[1]||(r[1]=function(e){return(0,n.unref)(o).email=e}),type:"email",class:"mt-1 block w-full"},null,8,["modelValue"]),(0,n.createVNode)(i.Z,{message:(0,n.unref)(o).errors.email,class:"mt-2"},null,8,["message"]),t.$page.props.jetstream.hasEmailVerification&&null===e.user.email_verified_at?((0,n.openBlock)(),(0,n.createElementBlock)("div",x,[(0,n.createElementVNode)("p",V,[(0,n.createTextVNode)(" Your email address is unverified. "),(0,n.createVNode)((0,n.unref)(a.rU),{href:t.route("verification.send"),method:"post",as:"button",class:"underline text-gray-600 hover:text-gray-900",onClick:(0,n.withModifiers)(E,["prevent"])},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Click here to re-send the verification email. ")]})),_:1},8,["href"])]),(0,n.withDirectives)((0,n.createElementVNode)("div",k," A new verification link has been sent to your email address. ",512),[[n.vShow,b.value]])])):(0,n.createCommentVNode)("",!0)])]})),actions:(0,n.withCtx)((function(){return[(0,n.createVNode)(d.Z,{on:(0,n.unref)(o).recentlySuccessful,class:"mr-3"},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Saved. ")]})),_:1},8,["on"]),(0,n.createVNode)(l.Z,{class:(0,n.normalizeClass)({"opacity-25":(0,n.unref)(o).processing}),disabled:(0,n.unref)(o).processing},{default:(0,n.withCtx)((function(){return[(0,n.createTextVNode)(" Save ")]})),_:1},8,["class","disabled"])]})),_:1})}}}}}]);
//# sourceMappingURL=7021.js.map