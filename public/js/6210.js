"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[6210],{1720:(e,t,r)=>{r.d(t,{Z:()=>a});var n=r(821),o=r(9680),l=r(771);const a={__name:"CancelButton",setup:function(e){var t=(0,l.L)();function r(){t.prevUrl&&o.Inertia.visit(t.prevUrl)}return function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",null,[(0,n.createElementVNode)("button",{onClick:r,class:"ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])}}}},131:(e,t,r)=>{r.d(t,{Z:()=>c});var n=r(821),o=r(9038),l={key:0},a=(0,n.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),s={class:"mt-3 list-disc list-inside text-sm text-red-600"};const c={__name:"ValidationErrors",setup:function(e){var t=(0,n.computed)((function(){return(0,o.qt)().props.value.errors})),r=(0,n.computed)((function(){return Object.keys(t.value).length>0}));return function(e,o){return r.value?((0,n.openBlock)(),(0,n.createElementBlock)("div",l,[a,(0,n.createElementVNode)("ul",s,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(t.value,(function(e,t){return(0,n.openBlock)(),(0,n.createElementBlock)("li",{key:t},(0,n.toDisplayString)(e),1)})),128))])])):(0,n.createCommentVNode)("",!0)}}}},6210:(e,t,r)=>{r.r(t),r.d(t,{default:()=>w});var n=r(821),o=r(9038),l=r(191),a=r(771),s=r(664),c=r(131),u=(r(9680),r(1720)),i={class:"place-self-center flex flex-col gap-y-3"},d={id:"topDiv",class:"bg-white text-black p-5 mb-10"},m={class:"flex justify-between mt-3 mb-6 pt-6"},p=(0,n.createElementVNode)("div",{class:"text-3xl"},"Create New Team",-1),f={class:"mb-6"},b=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700",for:"name"}," Team Name ",-1),x=["textContent"],g={class:"mb-6"},v=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700",for:"description"}," Description ",-1),k=["textContent"],V={class:"mb-6"},y=(0,n.createElementVNode)("label",{class:"block mb-2 uppercase font-bold text-xs text-gray-700",for:"totalSpots"}," Maximum # of Team Members ",-1),E=["textContent"],N={class:"flex justify-between mb-6"},h=["disabled"];const w={__name:"Create",props:{user:Object},setup:function(e){var t=(0,l.q)(),r=(0,a.L)(),w=e,C=(0,o.cI)({name:"",description:"",user_id:w.user.id,totalSpots:"1"}),B=function(){C.post("/teams")};function S(){C.reset()}return r.currentPage="teams",r.showFlashMessage=!0,(0,n.onMounted)((function(){t.makeVideoTopRight(),r.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),function(e,t){var o=(0,n.resolveComponent)("Head");return(0,n.openBlock)(),(0,n.createElementBlock)(n.Fragment,null,[(0,n.createVNode)(o,{title:"Create Team"}),(0,n.createElementVNode)("div",i,[(0,n.createElementVNode)("div",d,[(0,n.unref)(r).showFlashMessage?((0,n.openBlock)(),(0,n.createBlock)((0,n.unref)(s.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,n.createCommentVNode)("",!0),(0,n.createElementVNode)("div",m,[p,(0,n.createElementVNode)("div",null,[(0,n.createVNode)(u.Z)])]),(0,n.createElementVNode)("form",{onSubmit:t[4]||(t[4]=(0,n.withModifiers)((function(){return(0,n.unref)(B)&&(0,n.unref)(B).apply(void 0,arguments)}),["prevent"])),class:"max-w-md mx-auto mt-8"},[(0,n.createElementVNode)("div",f,[b,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[0]||(t[0]=function(e){return(0,n.unref)(C).name=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500",type:"text",name:"name",id:"name",required:""},null,512),[[n.vModelText,(0,n.unref)(C).name]]),(0,n.unref)(C).errors.name?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(C).errors.name),class:"text-xs text-red-600 mt-1"},null,8,x)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",g,[v,(0,n.withDirectives)((0,n.createElementVNode)("textarea",{"onUpdate:modelValue":t[1]||(t[1]=function(e){return(0,n.unref)(C).description=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",type:"text",name:"description",id:"description",required:""},null,512),[[n.vModelText,(0,n.unref)(C).description]]),(0,n.unref)(C).errors.description?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(C).errors.description),class:"text-xs text-red-600 mt-1"},null,8,k)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",V,[y,(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[2]||(t[2]=function(e){return(0,n.unref)(C).totalSpots=e}),class:"bg-gray-50 border border-gray-400 text-gray-900 text-sm p-2 w-full rounded-lg focus:ring-blue-500 focus:border-blue-500",type:"text",name:"totalSpots",id:"totalSpots"},null,512),[[n.vModelText,(0,n.unref)(C).totalSpots]]),(0,n.unref)(C).errors.totalSpots?((0,n.openBlock)(),(0,n.createElementBlock)("div",{key:0,textContent:(0,n.toDisplayString)((0,n.unref)(C).errors.totalSpots),class:"text-xs text-red-600 mt-1"},null,8,E)):(0,n.createCommentVNode)("",!0)]),(0,n.withDirectives)((0,n.createElementVNode)("input",{"onUpdate:modelValue":t[3]||(t[3]=function(e){return(0,n.unref)(C).user_id=e}),hidden:""},null,512),[[n.vModelText,(0,n.unref)(C).user_id]]),(0,n.createElementVNode)("div",N,[(0,n.createVNode)(c.Z,{class:"mr-4"}),(0,n.createElementVNode)("button",{type:"submit",class:"h-fit bg-blue-600 hover:bg-blue-500 text-white rounded py-2 px-4",disabled:(0,n.unref)(C).processing}," Submit ",8,h),(0,n.createElementVNode)("div",{onClick:S,class:"text-blue-600 text-sm cursor-pointer"},"Reset")])],32)])])],64)}}}}}]);
//# sourceMappingURL=6210.js.map