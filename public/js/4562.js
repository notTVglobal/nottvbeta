"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4562],{1720:(e,t,o)=>{o.d(t,{Z:()=>r});var l=o(821),n=o(9680),a=o(771);const r={__name:"CancelButton",setup:function(e){var t=(0,a.L)();function o(){t.prevUrl&&n.Inertia.visit(t.prevUrl)}return function(e,t){return(0,l.openBlock)(),(0,l.createElementBlock)("div",null,[(0,l.createElementVNode)("button",{onClick:o,class:"ml-2 px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])}}}},4562:(e,t,o)=>{o.r(t),o.d(t,{default:()=>v});var l=o(821),n=o(191),a=o(771),r=o(664),s=(o(9038),o(9680),o(1720)),i={class:"place-self-center flex flex-col gap-y-3"},c={id:"topDiv",class:"bg-white text-black p-5 mb-10"},d={class:"flex justify-between"},u=(0,l.createElementVNode)("div",{class:"grid grid-cols-1 grid-rows-2"},[(0,l.createElementVNode)("h1",{class:"text-3xl font-semibold"},"Go Live")],-1),m=(0,l.createElementVNode)("p",null," Here we will place instructions on how to go live.. ",-1),p=(0,l.createElementVNode)("ul",{class:"list-disc list-inside pt-4 space-y-1"},[(0,l.createElementVNode)("li",null," Add a dropdown of your shows to select which show you want to go live on. "),(0,l.createElementVNode)("li",null," Display go live instructions here. "),(0,l.createElementVNode)("li",null," If you want to stream from a mobile device we will include a video here to show you how to download and setup the Larix Broadcaster app. "),(0,l.createElementVNode)("li",null," If you want to stream from software like Open Broadcaster or vMix we will include a video to show you how here. ")],-1);const v={__name:"GoLive",setup:function(e){var t=(0,n.q)(),o=(0,a.L)();return o.currentPage="goLive",o.showFlashMessage=!0,(0,l.onMounted)((function(){t.makeVideoTopRight(),o.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),function(e,t){var n=(0,l.resolveComponent)("Head");return(0,l.openBlock)(),(0,l.createElementBlock)(l.Fragment,null,[(0,l.createVNode)(n,{title:"Go Live"}),(0,l.createElementVNode)("div",i,[(0,l.createElementVNode)("div",c,[(0,l.unref)(o).showFlashMessage?((0,l.openBlock)(),(0,l.createBlock)((0,l.unref)(r.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("div",d,[u,(0,l.createElementVNode)("div",null,[(0,l.createVNode)(s.Z)])]),m,p])])],64)}}}}}]);
//# sourceMappingURL=4562.js.map