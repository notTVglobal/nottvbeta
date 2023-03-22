"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[4257],{4257:(e,t,r)=>{r.r(t),r.d(t,{default:()=>O});var l=r(821),o=r(191),a=r(771),n=(0,l.createElementVNode)("div",{id:"topDiv"},null,-1),s={class:"flex flex-col gap-y-3"},c={class:"light:bg-white dark:bg-gray-800 light:text-black dark:text-gray-50 p-5 mb-10"},d={class:"flex justify-end mb-3 gap-2"},i=(0,l.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"All Users",-1),u=(0,l.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Edit User",-1),m=(0,l.createElementVNode)("button",{class:"px-4 py-2 text-white bg-blue-600 hover:bg-blue-500 rounded-lg"},"Dashboard",-1),p={key:0,class:"p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800",role:"alert"},g={class:"font-medium"},b={class:"mb-6"},f=["src"],N={class:"text-2xl pb-2"},V={class:"p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200 space-y-1"},x={key:0},E=[(0,l.createElementVNode)("span",{class:"font-bold text-red-600"},"Administrator",-1)],h=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"User Type: ",-1),y={key:0,class:"text-sm font-semibold capitalize"},k=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Subscription Status: ",-1),v={class:"p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200"},S={class:""},C=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Email: ",-1),T={class:"mb-6"},B=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Phone: ",-1),D=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Address 1: ",-1),w={class:""},z=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Address 2: ",-1),_={class:""},A=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"City: ",-1),j={class:""},P=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Province: ",-1),I={class:""},L=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Country: ",-1),U={class:""},$=(0,l.createElementVNode)("span",{class:"text-sm font-semibold capitalize"},"Postal Code: ",-1),F={class:"p-6 light:bg-white dark:bg-gray-800 border-b border-gray-200"},M=(0,l.createElementVNode)("div",{class:"text-2xl pb-2"}," Teams this user belongs to: ",-1);const O={__name:"Index",props:{userSelected:Object,role:String,teams:Object,message:String},setup:function(e){var t=e,r=(0,o.q)(),O=(0,a.L)();return r.currentPage="users",(0,l.onBeforeMount)((function(){O.scrollToTopCounter=0})),(0,l.onMounted)((function(){r.makeVideoTopRight(),0===O.scrollToTopCounter&&(document.getElementById("topDiv").scrollIntoView(),O.scrollToTopCounter++)})),function(e,r){var o=(0,l.resolveComponent)("Head"),a=(0,l.resolveComponent)("Link");return(0,l.openBlock)(),(0,l.createElementBlock)(l.Fragment,null,[(0,l.createVNode)(o,{title:(0,l.unref)(t).userSelected.name},null,8,["title"]),n,(0,l.createElementVNode)("div",s,[(0,l.createElementVNode)("div",c,[(0,l.createElementVNode)("div",d,[1===e.$page.props.user.isAdmin?((0,l.openBlock)(),(0,l.createBlock)(a,{key:0,href:"/users"},{default:(0,l.withCtx)((function(){return[i]})),_:1})):(0,l.createCommentVNode)("",!0),1===e.$page.props.user.isAdmin?((0,l.openBlock)(),(0,l.createBlock)(a,{key:1,href:"/users/".concat((0,l.unref)(t).userSelected.id,"/edit")},{default:(0,l.withCtx)((function(){return[u]})),_:1},8,["href"])):(0,l.createCommentVNode)("",!0),(0,l.createVNode)(a,{href:"/dashboard"},{default:(0,l.withCtx)((function(){return[m]})),_:1})]),(0,l.unref)(t).message?((0,l.openBlock)(),(0,l.createElementBlock)("div",p,[(0,l.createElementVNode)("span",g,(0,l.toDisplayString)((0,l.unref)(t).message),1)])):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("p",b,[(0,l.createElementVNode)("img",{src:(0,l.unref)(t).userSelected.profile_photo_url,class:"rounded-full h-20 w-20 object-cover"},null,8,f)]),(0,l.createElementVNode)("h2",N,(0,l.toDisplayString)((0,l.unref)(t).userSelected.name),1),(0,l.createElementVNode)("div",V,[1===(0,l.unref)(t).userSelected.isAdmin?((0,l.openBlock)(),(0,l.createElementBlock)("div",x,E)):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("div",null,[h,(0,l.createTextVNode)(" "+(0,l.toDisplayString)((0,l.unref)(t).role),1)]),(0,l.createElementVNode)("div",null,[4===e.$page.props.userSelected.role_id?((0,l.openBlock)(),(0,l.createElementBlock)("span",y,"Creator Number: ")):(0,l.createCommentVNode)("",!0),(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.creatorNumber),1)]),(0,l.createElementVNode)("div",null,[k,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.subscriptionStatus),1)])]),(0,l.createElementVNode)("div",v,[(0,l.createElementVNode)("div",S,[C,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.email),1)]),(0,l.createElementVNode)("div",T,[B,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.phone),1)]),(0,l.createElementVNode)("div",null,[D,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.address1),1)]),(0,l.createElementVNode)("div",w,[z,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.address2),1)]),(0,l.createElementVNode)("div",_,[A,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.city),1)]),(0,l.createElementVNode)("div",j,[P,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.province),1)]),(0,l.createElementVNode)("div",I,[L,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.country),1)]),(0,l.createElementVNode)("div",U,[$,(0,l.createTextVNode)((0,l.toDisplayString)((0,l.unref)(t).userSelected.postalCode),1)])]),(0,l.createElementVNode)("div",F,[M,((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)((0,l.unref)(t).teams,(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("div",{key:e.id},[(0,l.createVNode)(a,{href:"/teams/".concat(e.slug),class:"light:text-blue-800 light:hover:text-blue-600 dark:text-blue-200 dark:hover:text-blue-400"},{default:(0,l.withCtx)((function(){return[(0,l.createTextVNode)((0,l.toDisplayString)(e.name),1)]})),_:2},1032,["href"])])})),128))])])])],64)}}}}}]);
//# sourceMappingURL=4257.js.map