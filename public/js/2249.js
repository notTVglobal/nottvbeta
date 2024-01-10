"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2249],{1720:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(821),o=r(9680),l=r(771);const a={__name:"CancelButton",setup:function(e){var t=(0,l.L)();function r(){t.prevUrl&&o.Inertia.visit(t.prevUrl)}return function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("button",{onClick:r,class:"ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])}}}},131:(e,t,r)=>{r.d(t,{Z:()=>d});var n=r(821),o=r(9038),l={key:0},a=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),s={class:"mt-3 list-disc list-inside text-sm text-red-600"};const d={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.qt)().props.value.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",l,[a,(0,n.createElementVNode)("ul",s,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},2249:(e,t,r)=>{r.r(t),r.d(t,{default:()=>X});var n=r(821),o=r(9038),l=r(191),a=r(771),s=r(664),d=r(131),c=(r(9680),r(1720)),i={class:"place-self-center flex flex-col gap-y-3"},u={id:"topDiv",class:"bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10"},m={class:"flex justify-between mt-3 mb-6"},p=(0,n.createElementVNode)("div",{class:"text-3xl"},"Create New User",-1),x=(0,n.createElementVNode)("div",{class:"p-4 mb-4 text-sm text-orange700 bg-orange-100 rounded-lg dark:bg-orange-200 dark:text-orange-800",role:"alert"},[(0,n.createElementVNode)("span",{class:"font-medium"},' New users will need to "Reset Password" then "re-send the verification email". ')],-1),f={class:"mb-6"},b=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"name"}," User Role ",-1),y=[(0,n.createElementVNode)("option",{value:"1"},"Standard User",-1),(0,n.createElementVNode)("option",{value:"4"},"Creator",-1)],k=["textContent"],g={class:"mb-6"},v=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"name"}," Name ",-1),V=["textContent"],N={class:"mb-6"},E=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," Email ",-1),C=["textContent"],w={class:"mb-6 hidden"},h=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"password"}," Password ",-1),B=["textContent"],D={class:"mb-6"},S=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," Phone Number ",-1),U=["textContent"],_={class:"mb-6"},M=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," Address ",-1),T=["textContent"],P=["textContent"],q={class:"mb-6"},I=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," City ",-1),Z=["textContent"],F={class:"mb-6"},R=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," Province ",-1),j=["textContent"],L={class:"mb-6"},A=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," Country ",-1),H=["textContent"],O={class:"mb-6"},W=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"email"}," Postal Code ",-1),$=["textContent"],z={class:"mb-6"},G=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700 dark:text-gray-50",for:"text"}," Stripe ID ",-1),J=["textContent"],K={class:"flex justify-between mb-6"},Q=["disabled"];const X={__name:"Create",props:{message:String},setup:function(e){var t=(0,l.q)(),r=(0,a.L)();r.currentPage="users",r.showFlashMessage=!0,(0,n.onMounted)((function(){t.makeVideoTopRight(),r.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()}));var X=(0,o.cI)({name:"",email:"",password:" ",role_id:"",address1:null,address2:null,city:null,province:null,country:null,postalCode:null,phone:null,stripe_id:null});function Y(){X.reset()}var ee=function(){X.post(route("users.store"))};(0,n.ref)(!0);return function(e,t){var o=(0,n.resolveComponent)("Head");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(o,{title:"Create User"}),(0,n.createElementVNode)("div",i,[(0,n.createElementVNode)("div",u,[(0,n.unref)(r).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(s.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",m,[p,(0,n.createElementVNode)("div",null,[(0,n.createVNode)(c.Z)])]),x,(0,n.createElementVNode)("form",{onSubmit:(0,n.withModifiers)(ee,["prevent"]),class:"max-w-md mx-auto mt-8"},[(0,n.createElementVNode)("div",f,[b,(0,n.withDirectives)((0,n.createElementVNode)("select",{name:"role",id:"role",class:"border border-gray-400 p-2 w-full rounded-lg block mb-2 uppercase font-bold text-xs text-gray-700","onUpdate:modelValue":t[0]||(t[0]=function(e){return(0,n.unref)(X).role_id=e}),required:""},y,512),[[n.vModelSelect,(0,n.unref)(X).role_id]]),(0,n.unref)(X).errors.role_id?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.role_id),class:"text-xs text-red-600 mt-1"},null,8,k)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",g,[v,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[1]||(t[1]=function(e){return(0,n.unref)(X).name=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"name",id:"name",required:""},null,512),[[n.vModelText,(0,n.unref)(X).name]]),(0,n.unref)(X).errors.name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.name),class:"text-xs text-red-600 mt-1"},null,8,V)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",N,[E,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[2]||(t[2]=function(e){return(0,n.unref)(X).email=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"email",name:"email",id:"email",required:""},null,512),[[n.vModelText,(0,n.unref)(X).email]]),(0,n.unref)(X).errors.email?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.email),class:"text-xs text-red-600 mt-1"},null,8,C)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",w,[h,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[3]||(t[3]=function(e){return(0,n.unref)(X).password=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"password",name:"password",id:"password",hidden:""},null,512),[[n.vModelText,(0,n.unref)(X).password]]),(0,n.unref)(X).errors.password?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.password),class:"text-xs text-red-600 mt-1"},null,8,B)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",D,[S,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[4]||(t[4]=function(e){return(0,n.unref)(X).phone=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"phone",id:"phone"},null,512),[[n.vModelText,(0,n.unref)(X).phone]]),(0,n.unref)(X).errors.phone?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.phone),class:"text-xs text-red-600 mt-1"},null,8,U)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",_,[M,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[5]||(t[5]=function(e){return(0,n.unref)(X).address1=e}),class:"border border-gray-400 p-2 mb-2 w-full rounded-lg text-black",type:"text",name:"address1",id:"address1"},null,512),[[n.vModelText,(0,n.unref)(X).address1]]),(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[6]||(t[6]=function(e){return(0,n.unref)(X).address2=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"address2",id:"address2"},null,512),[[n.vModelText,(0,n.unref)(X).address2]]),(0,n.unref)(X).errors.address1?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.address1),class:"text-xs text-red-600 mt-1"},null,8,T)):(0,n.createCommentVNode)("",!0),(0,n.unref)(X).errors.address2?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:1,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.address2),class:"text-xs text-red-600 mt-1"},null,8,P)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",q,[I,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[7]||(t[7]=function(e){return(0,n.unref)(X).city=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"city",id:"city"},null,512),[[n.vModelText,(0,n.unref)(X).city]]),(0,n.unref)(X).errors.city?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.city),class:"text-xs text-red-600 mt-1"},null,8,Z)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",F,[R,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[8]||(t[8]=function(e){return(0,n.unref)(X).province=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"province",id:"province"},null,512),[[n.vModelText,(0,n.unref)(X).province]]),(0,n.unref)(X).errors.province?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.province),class:"text-xs text-red-600 mt-1"},null,8,j)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",L,[A,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[9]||(t[9]=function(e){return(0,n.unref)(X).country=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"country",id:"country"},null,512),[[n.vModelText,(0,n.unref)(X).country]]),(0,n.unref)(X).errors.country?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.country),class:"text-xs text-red-600 mt-1"},null,8,H)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",O,[W,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[10]||(t[10]=function(e){return(0,n.unref)(X).postalCode=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"postalCode",id:"postalCode"},null,512),[[n.vModelText,(0,n.unref)(X).postalCode]]),(0,n.unref)(X).errors.postalCode?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.postalCode),class:"text-xs text-red-600 mt-1"},null,8,$)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",z,[G,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[11]||(t[11]=function(e){return(0,n.unref)(X).stripe_id=e}),class:"border border-gray-400 p-2 w-full rounded-lg text-black",type:"text",name:"stripe_id",id:"stripe_id"},null,512),[[n.vModelText,(0,n.unref)(X).stripe_id]]),(0,n.unref)(X).errors.stripe_id?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(X).errors.stripe_id),class:"text-xs text-red-600 mt-1"},null,8,J)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",K,[(0,n.createVNode)(d.Z,{class:"mr-4"}),(0,n.createElementVNode)("button",{type:"submit",class:"h-fit bg-blue-600 text-white rounded py-2 px-4 hover:bg-blue-400",disabled:(0,n.unref)(X).processing}," Submit ",8,Q),(0,n.createElementVNode)("div",{onClick:Y,class:"text-blue-600 text-sm cursor-pointer"},"Reset")])],32)])])],64)}}}}}]);
//# sourceMappingURL=2249.js.map