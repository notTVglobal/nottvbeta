"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[7809],{55398:(e,t,o)=>{o.d(t,{m:()=>r});var r=(0,o(69876).Q_)("newsStore",{state:function(){return{newsArticleIdTiptop:"",newsArticleTitleTiptop:"",newsArticleContentTiptop:[]}},actions:{}})},57083:(e,t,o)=>{o.d(t,{Z:()=>i});var r=o(70821),n=["disabled"],l=["disabled"];const i={__name:"TiptapButtons",props:{editor:Object},setup:function(e){return function(t,o){return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[(0,r.createElementVNode)("button",{onClick:o[0]||(o[0]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleBold().run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-bold",{"bg-gray-600 text-white":e.editor.isActive("bold")}])}," B ",2),(0,r.createElementVNode)("button",{onClick:o[1]||(o[1]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleItalic().run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("italic")}])}," i ",2),(0,r.createElementVNode)("button",{onClick:o[2]||(o[2]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleHeading({level:1}).run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("heading",{level:1})}])}," h1 ",2),(0,r.createElementVNode)("button",{onClick:o[3]||(o[3]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleHeading({level:2}).run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("heading",{level:2})}])}," h2 ",2),(0,r.createElementVNode)("button",{onClick:o[4]||(o[4]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleHeading({level:3}).run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("heading",{level:3})}])}," h3 ",2),(0,r.createElementVNode)("button",{onClick:o[5]||(o[5]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleBulletList().run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("bulletList")}])}," bullet list ",2),(0,r.createElementVNode)("button",{onClick:o[6]||(o[6]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleOrderedList().run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("orderedList")}])}," ordered list ",2),(0,r.createElementVNode)("button",{onClick:o[7]||(o[7]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleCodeBlock().run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("codeBlock")}])}," code block ",2),(0,r.createElementVNode)("button",{onClick:o[8]||(o[8]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().toggleBlockquote().run()}),["prevent"])),class:(0,r.normalizeClass)(["py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic",{"bg-gray-600 text-white":e.editor.isActive("blockquote")}])}," blockquote ",2),(0,r.createElementVNode)("button",{onClick:o[9]||(o[9]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().setHorizontalRule().run()}),["prevent"])),class:"py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 rounded-lg font-italic"}," horizontal rule "),(0,r.createElementVNode)("button",{onClick:o[10]||(o[10]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().undo().run()}),["prevent"])),class:"py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 disabled:hover:bg-gray-200 disabled:cursor-not-allowed disabled:text-black rounded-lg font-italic",disabled:!e.editor.can().chain().focus().undo().run()}," undo ",8,n),(0,r.createElementVNode)("button",{onClick:o[11]||(o[11]=(0,r.withModifiers)((function(t){return e.editor.chain().focus().redo().run()}),["prevent"])),class:"py-1 px-3 ml-2 mb-2 text-black hover:text-white bg-gray-200 hover:bg-gray-600 disabled:hover:bg-gray-200 disabled:cursor-not-allowed disabled:text-black rounded-lg font-italic",disabled:!e.editor.can().chain().focus().redo().run()}," redo ",8,l)])}}}},36414:(e,t,o)=>{o.d(t,{Z:()=>l});var r=o(70821),n=["onKeydown","textContent"];const l={__name:"TabbableTextarea",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var o=t.emit;function l(e){var t=e.target,o=t.value,r=t.selectionStart,n=t.selectionEnd;t.value=o.substring(0,r)+"\t"+o.substring(n),t.selectionStart=t.selectionEnd=r+1}return function(t,i){return(0,r.openBlock)(),(0,r.createElementBlock)("textarea",{onKeydown:(0,r.withKeys)((0,r.withModifiers)(l,["prevent"]),["tab"]),onKeyup:i[0]||(i[0]=function(e){return(0,r.unref)(o)("update:modelValue",e.target.value)}),textContent:(0,r.toDisplayString)(e.modelValue)},null,40,n)}}}},30131:(e,t,o)=>{o.d(t,{Z:()=>c});var r=o(70821),n=o(39038),l={key:0},i=(0,r.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),a={class:"mt-3 list-disc list-inside text-sm text-red-600"};const c={__name:"ValidationErrors",setup:function(e){var t=(0,r.computed)((function(){return(0,n.qt)().props.value.errors})),o=(0,r.computed)((function(){return Object.keys(t.value).length>0}));return function(e,n){return o.value?((0,r.openBlock)(),(0,r.createElementBlock)("div",l,[i,(0,r.createElementVNode)("ul",a,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(t.value,(function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("li",{key:t},(0,r.toDisplayString)(e),1)})),128))])])):(0,r.createCommentVNode)("",!0)}}}},87809:(e,t,o)=>{o.r(t),o.d(t,{default:()=>S});var r=o(70821),n=o(39038),l=o(40191),i=o(90771),a=o(55398),c=o(30131),s=o(95404),d=o(90173),u=(o(20336),o(26022)),g=(o(52701),o(35525),o(34565),o(9680)),b=(o(23493),o(9669),o(36414),o(57083)),m={class:"h-auto overflow-y-auto min-h-[13rem] max-h-[96rem] mb-2 pb-2 bg-gray-50 border border-1 border-gray-300 focus:outline-none text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500"};const p={__name:"TiptapNewsPostEdit",setup:function(e){var t=(0,a.m)(),o=new s.ML({content:t.newsArticleContentTiptop,extensions:[d.Z,u.Z.configure({HTMLAttributes:{class:"news-paragraph"}})],onUpdate:function(e){var o=e.editor;t.newsArticleContentTiptop=o.getHTML()},autofocus:!0,editorProps:{attributes:{class:"h-auto min-h-[12rem] max-h-[96rem] overflow-y-auto block w-full p-2.5 focus:outline-none"}}});return function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(b.Z,{editor:(0,r.unref)(o)},null,8,["editor"]),(0,r.createElementVNode)("div",m,[(0,r.createVNode)((0,r.unref)(s.kg),{editor:(0,r.unref)(o),class:""},null,8,["editor"])])],64)}}};var f=o(664),h={class:"place-self-center flex flex-col gap-y-3"},v={id:"topDiv",class:"bg-white text-black dark:bg-gray-800 dark:text-gray-50 p-5 mb-10"},y={class:"flex flex-row justify-between mt-6"},w=(0,r.createElementVNode)("h2",{class:"text-xl font-semibold leading-tight"}," Edit News Post ",-1),x={class:"flex justify-end space-x-2"},k={class:"p-6 border-b border-gray-200"},E=["onSubmit"],V={class:"mb-6"},C=(0,r.createElementVNode)("label",{for:"Title",class:"block mb-2 text-sm font-bold text-gray-900 dark:text-gray-300"},"Title",-1),N={key:0,class:"text-sm text-red-600"},B={class:"mb-6"},T=(0,r.createElementVNode)("label",{for:"slug",class:"block mb-2 text-sm font-bold text-gray-900 dark:text-gray-300"},"Content",-1),A={key:1,class:"text-sm text-red-600"},M={class:"flex justify-start"},z=["disabled"];const S={__name:"Edit",props:{news:Object,can:Object},setup:function(e){var t=(0,l.q)(),o=(0,i.L)(),s=(0,a.m)(),d=e,u=(0,n.cI)({id:d.news.id,title:d.news.title,body:s.newsArticleContentTiptop}),b=function(){u.body=s.newsArticleContentTiptop,u.put(route("news.update",d.news.id))};function m(){s.newsArticleContentTiptop="",g.Inertia.visit(o.prevUrl)}return t.currentPage="newsEdit",o.showFlashMessage=!0,s.newsArticleIdTiptop=d.news.id,s.newsArticleTitleTiptop=d.news.title,s.newsArticleContentTiptop=d.news.content,u.body=s.newsArticleContentTiptop,(0,r.onMounted)((function(){t.makeVideoTopRight(),o.isMobile&&(t.ottClass="ottClose",t.ott=0),document.getElementById("topDiv").scrollIntoView()})),function(e,n){var l=(0,r.resolveComponent)("Head");return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,null,[(0,r.createVNode)(l,{title:"Edit News Post"}),(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("div",v,[(0,r.unref)(o).showFlashMessage?((0,r.openBlock)(),(0,r.createBlock)((0,r.unref)(f.Z),{key:0,flash:e.$page.props.flash},null,8,["flash"])):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("div",y,[w,(0,r.createElementVNode)("div",x,[(0,r.createElementVNode)("div",null,[d.can.viewNewsroom?((0,r.openBlock)(),(0,r.createElementBlock)("button",{key:0,onClick:n[0]||(n[0]=function(e){return(0,r.unref)(o).btnRedirect("/newsroom")}),class:"px-4 py-2 text-white bg-yellow-600 hover:bg-yellow-500 rounded-lg disabled:bg-gray-400"},"Newsroom")):(0,r.createCommentVNode)("",!0)]),(0,r.createElementVNode)("div",null,[(0,r.createElementVNode)("button",{onClick:m,class:"px-4 py-2 text-white bg-orange-600 hover:bg-orange-500 rounded-lg"},"Cancel ")])])]),(0,r.createElementVNode)("div",k,[(0,r.createElementVNode)("form",{onSubmit:(0,r.withModifiers)(b,["prevent"])},[(0,r.createElementVNode)("div",V,[C,(0,r.withDirectives)((0,r.createElementVNode)("input",{type:"text","onUpdate:modelValue":n[1]||(n[1]=function(e){return(0,r.unref)(u).title=e}),name:"title",class:"bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5",placeholder:""},null,512),[[r.vModelText,(0,r.unref)(u).title]]),(0,r.unref)(u).errors.title?((0,r.openBlock)(),(0,r.createElementBlock)("div",N,(0,r.toDisplayString)((0,r.unref)(u).errors.title),1)):(0,r.createCommentVNode)("",!0)]),(0,r.createElementVNode)("div",B,[T,"newsEdit"===(0,r.unref)(t).currentPage?((0,r.openBlock)(),(0,r.createBlock)(p,{key:0})):(0,r.createCommentVNode)("",!0),(0,r.unref)(u).errors.body?((0,r.openBlock)(),(0,r.createElementBlock)("div",A,(0,r.toDisplayString)((0,r.unref)(u).errors.body),1)):(0,r.createCommentVNode)("",!0)]),(0,r.createElementVNode)("div",M,[(0,r.createElementVNode)("button",{type:"submit",class:(0,r.normalizeClass)(["text-white bg-blue-700 hover:bg-blue-500 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5",{"opacity-25":(0,r.unref)(u).processing}]),disabled:(0,r.unref)(u).processing}," Save ",10,z),(0,r.createVNode)(c.Z,{class:"ml-4"})])],40,E)])])])],64)}}}}}]);
//# sourceMappingURL=7809.js.map