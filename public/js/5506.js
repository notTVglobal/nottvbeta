"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[5506],{22704:(e,t,n)=>{n.d(t,{Z:()=>d});var l=n(70821),o=n(54784),r={class:"mb-2"},a=(0,l.createElementVNode)("button",{class:"bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded"}," See all products ",-1),c={class:"bg-blue-600 hover:bg-blue-700 text-white ml-2 px-2 py-1 rounded"},s=(0,l.createElementVNode)("span",null,"Checkout",-1),u=["textContent"];const d={__name:"ShopHeader",setup:function(e){var t=(0,o.M)();return function(e,n){var o=(0,l.resolveComponent)("Link");return(0,l.openBlock)(),(0,l.createElementBlock)("div",r,[(0,l.createVNode)(o,{href:e.route("products")},{default:(0,l.withCtx)((function(){return[a]})),_:1},8,["href"]),(0,l.createVNode)(o,{href:e.route("checkout")},{default:(0,l.withCtx)((function(){return[(0,l.createElementVNode)("button",c,[s,(0,l.createElementVNode)("span",{class:"inline-block ml-1",textContent:(0,l.toDisplayString)("("+(0,l.unref)(t).cart.length+" items)")},null,8,u)])]})),_:1},8,["href"])])}}}},55506:(e,t,n)=>{n.r(t),n.d(t,{default:()=>v});var l=n(70821),o=n(40191),r=n(90771),a=n(54784),c=n(664),s=n(22704),u={class:"place-self-center flex flex-col gap-y-3"},d={id:"topDiv",class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},i={class:"flex justify-between mb-3"},p={id:"topDiv"},m={class:"text-3xl font-semibold pb-3"},x={class:"lg:w-5/6 2xl:w-3/5 mx-auto flex flex-wrap"},f=(0,l.createElementVNode)("img",{alt:"ecommerce placeholder",class:"lg:w-1/2 2xl:w-1/2 w-full lg:h-auto 2xl:h-auto h-64 object-cover object-center rounded",src:"https://dummyimage.com/640x640"},null,-1),g={class:"lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0"},b=["textContent"],h=["textContent"],k=["textContent"],C={class:"flex mt-6 pt-4 border-t-2 border-gray-200"},V=["textContent"],y=(0,l.createElementVNode)("div",{class:"px-2"},null,-1);const v={__name:"Index",props:{product:Object,can:Object},setup:function(e){var t=e,n=(0,o.q)(),v=(0,r.L)(),E=(0,a.M)();return v.currentPage="products",v.showFlashMessage=!0,(0,l.onMounted)((function(){n.makeVideoTopRight(),v.isMobile&&(n.ottClass="ottClose",n.ott=0),document.getElementById("topDiv").scrollIntoView(),E.getProducts()})),function(n,o){var r,a=(0,l.resolveComponent)("Head");return(0,l.openBlock)(),(0,l.createElementBlock)(l.Fragment,null,[(0,l.createVNode)(a,{title:"".concat((0,l.unref)(t).product.name)},null,8,["title"]),(0,l.createElementVNode)("div",u,[(0,l.createElementVNode)("div",d,[(0,l.unref)(v).showFlashMessage?((0,l.openBlock)(),(0,l.createBlock)((0,l.unref)(c.Z),{key:0,flash:n.$page.props.flash},null,8,["flash"])):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("header",i,[(0,l.createElementVNode)("div",p,[(0,l.createElementVNode)("h1",m,(0,l.toDisplayString)((0,l.unref)(t).product.name),1)])]),(0,l.createVNode)((0,l.unref)(s.Z)),(0,l.createElementVNode)("div",x,[f,(0,l.createElementVNode)("div",g,[((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(e.product.categories.slice(0,2),(function(e){return(0,l.openBlock)(),(0,l.createElementBlock)("h2",{class:"text-sm title-font text-gray-500 tracking-widest uppercase inline-block mr-4",textContent:(0,l.toDisplayString)(e.name)},null,8,b)})),256)),(0,l.createElementVNode)("h1",{class:"text-blue-800 dark:text-blue-200 text-3xl title-font font-medium mb-2",textContent:(0,l.toDisplayString)(e.product.name)},null,8,h),(0,l.createElementVNode)("p",{class:"leading-relaxed",textContent:(0,l.toDisplayString)(e.product.description)},null,8,k),(0,l.createElementVNode)("div",C,[(0,l.createElementVNode)("span",{class:"title-font font-medium text-2xl text-gray-900 dark:text-gray-100",textContent:(0,l.toDisplayString)((r=e.product.price,(r/=100).toLocaleString("en-CA",{style:"currency",currency:"CAD"})))},null,8,V)]),(0,l.createElementVNode)("button",{class:"flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded",onClick:o[0]||(o[0]=function(t){return(0,l.unref)(E).addToCart(e.product)})},"Add To Cart")])]),y])])],64)}}}}}]);
//# sourceMappingURL=5506.js.map