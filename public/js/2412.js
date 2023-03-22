"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2412],{6320:(e,t,r)=>{r.d(t,{Z:()=>o});var l=r(821);const o={__name:"Pagination",props:{links:Array,id:String},setup:function(e){return function(t,r){return(0,l.openBlock)(),(0,l.createElementBlock)("div",null,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.links,(function(t,o){return(0,l.openBlock)(),(0,l.createBlock)((0,l.resolveDynamicComponent)(t.url?"Link":"span"),{id:e.id,key:o,href:t.url,innerHTML:t.label,class:(0,l.normalizeClass)(["px-1 text-gray-800 dark:text-gray-50 dark:hover:text-blue-400 hover:text-blue-400",{"text-gray-300 dark:text-gray-700 dark:hover:text-gray-700 hover:text-gray-300":!t.url,"font-bold":t.active}]),onScroll:r[0]||(r[0]=(0,l.withModifiers)((function(){}),["prevent"]))},null,40,["id","href","innerHTML","class"])})),128))])}}}},2412:(e,t,r)=>{r.r(t),r.d(t,{default:()=>H});var l=r(821),o=r(6320),a=r(9680),n=r(3493),s=r.n(n),c=r(191),d=r(771),i=(0,l.createElementVNode)("div",{id:"topDiv"},null,-1),u={class:"place-self-center flex flex-col gap-y-3"},p={class:"bg-white dark:bg-gray-800 text-black dark:text-gray-50 p-5 mb-10"},m={key:0,class:"p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800",role:"alert"},g={class:"font-medium"},x={class:"flex justify-between pt-4"},f=(0,l.createElementVNode)("h1",{class:"text-3xl font-semibold pb-3"},"Users",-1),h=(0,l.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Dashboard",-1),b={class:"flex flex-row justify-between gap-x-4"},k=(0,l.createElementVNode)("button",{class:"bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded disabled:bg-gray-400"},"Add User",-1),y={class:"flex flex-col"},N={class:"-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},V={class:"py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"},w={class:"shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"},v={class:"overflow-hidden bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg"},E={class:"p-6 bg-white dark:bg-gray-800 border-b border-gray-200"},B={class:"relative overflow-x-auto shadow-md sm:rounded-lg"},C={class:"w-full text-sm text-left text-gray-500 dark:text-gray-400"},_=(0,l.createElementVNode)("thead",{class:"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"},[(0,l.createElementVNode)("tr",null,[(0,l.createElementVNode)("th",{scope:"col",class:"min-w-[8rem] px-6 py-3"}," Avatar "),(0,l.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Name "),(0,l.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Email "),(0,l.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Role "),(0,l.createElementVNode)("th",{scope:"col",class:"px-6 py-3"}," Edit ")])],-1),T={scope:"row",class:"min-w-[8rem] px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},S=["src"],D=["src"],j={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},L={key:0,class:"ml-2 bg-red-800 text-white text-xs font-bold rounded-lg py-1 px-2"},M={key:1,class:"ml-2 bg-orange-600 text-white text-xs font-bold rounded-lg py-1 px-2"},A={scope:"row",class:"px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"},I={class:"px-6 py-4"},U={class:"px-6 py-4"},F=(0,l.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit",-1);const H={__name:"Index",props:{users:Object,filters:Object,can:Object,message:String},setup:function(e){var t=e,r=(0,c.q)(),n=(0,d.L)();r.currentPage="users",(0,l.onBeforeMount)((function(){n.scrollToTopCounter=0})),(0,l.onMounted)((function(){r.makeVideoTopRight(),0===n.scrollToTopCounter&&(document.getElementById("topDiv").scrollIntoView(),n.scrollToTopCounter++)}));var H=(0,l.ref)(t.filters.search);return(0,l.watch)(H,s()((function(e){a.Inertia.get("/users",{search:e},{preserveState:!0,replace:!0})}),300)),function(r,a){var n=(0,l.resolveComponent)("Head"),s=(0,l.resolveComponent)("Link");return(0,l.openBlock)(),(0,l.createElementBlock)(l.Fragment,null,[(0,l.createVNode)(n,{title:"Users"}),i,(0,l.createElementVNode)("div",u,[(0,l.createElementVNode)("div",p,[(0,l.unref)(t).message?((0,l.openBlock)(),(0,l.createElementBlock)("div",m,[(0,l.createElementVNode)("span",g,(0,l.toDisplayString)((0,l.unref)(t).message),1)])):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("div",x,[f,(0,l.createVNode)(s,{href:"/dashboard"},{default:(0,l.withCtx)((function(){return[h]})),_:1})]),(0,l.createElementVNode)("div",b,[(0,l.createVNode)(s,{href:"/users/create"},{default:(0,l.withCtx)((function(){return[k]})),_:1}),(0,l.withDirectives)((0,l.createElementVNode)("input",{"onUpdate:modelValue":a[0]||(a[0]=function(e){return(0,l.isRef)(H)?H.value=e:H=e}),type:"search",placeholder:"Search...",class:"text-black border px-2 rounded-lg"},null,512),[[l.vModelText,(0,l.unref)(H)]])]),(0,l.createElementVNode)("div",y,[(0,l.createElementVNode)("div",N,[(0,l.createElementVNode)("div",V,[(0,l.createElementVNode)("div",w,[(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).users.teams)+" ",1),(0,l.createElementVNode)("div",v,[(0,l.createElementVNode)("div",E,[(0,l.createElementVNode)("div",B,[(0,l.createVNode)((0,l.unref)(o.Z),{links:e.users.links,class:"mb-6"},null,8,["links"]),(0,l.createElementVNode)("table",C,[_,(0,l.createElementVNode)("tbody",null,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.users.data,(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("tr",{key:e.id,class:"bg-white border-b dark:bg-gray-800 dark:border-gray-700"},[(0,l.createElementVNode)("th",T,[(0,l.createVNode)(s,{href:"/users/".concat(e.id),class:""},{default:(0,l.withCtx)((function(){return[e.profile_photo_path?(0,l.createCommentVNode)("",!0):((0,l.openBlock)(),(0,l.createElementBlock)("img",{key:0,src:e.userSelected.profile_photo_url,class:"rounded-full h-20 w-20 object-cover"},null,8,S)),e.profile_photo_path?((0,l.openBlock)(),(0,l.createElementBlock)("img",{key:1,src:"/storage/".concat(e.profile_photo_path),class:"rounded-full h-20 w-20 object-cover"},null,8,D)):(0,l.createCommentVNode)("",!0)]})),_:2},1032,["href"])]),(0,l.createElementVNode)("th",j,[(0,l.createVNode)(s,{href:"/users/".concat(e.id),class:"text-blue-800 dark:text-blue-200 hover:text-blue-600 dark:hover:text-blue-400"},{default:(0,l.withCtx)((function(){return[(0,l.createTextVNode)((0,l.toDisplayString)(e.name),1)]})),_:2},1032,["href"]),e.isAdmin?((0,l.openBlock)(),(0,l.createElementBlock)("span",L,"Admin")):(0,l.createCommentVNode)("",!0),e.isNewsPerson?((0,l.openBlock)(),(0,l.createElementBlock)("span",M,"News Team")):(0,l.createCommentVNode)("",!0)]),(0,l.createElementVNode)("th",A,(0,l.toDisplayString)(e.email),1),(0,l.createElementVNode)("td",I,[(0,l.createElementVNode)("span",null,(0,l.toDisplayString)(e.role),1)]),(0,l.createElementVNode)("td",U,[(0,l.createVNode)(s,{href:"/users/".concat(e.id,"/edit")},{default:(0,l.withCtx)((function(){return[F]})),_:2},1032,["href"])])])})),128))])]),(0,l.createVNode)((0,l.unref)(o.Z),{links:e.users.links,class:"mt-6"},null,8,["links"])])])])])])])])])])],64)}}}}}]);
//# sourceMappingURL=2412.js.map