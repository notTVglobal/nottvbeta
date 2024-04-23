"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[2406],{71631:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(94015),r=n.n(o),a=n(23645),s=n.n(a)()(r());s.push([e.id,".modal-mask[data-v-6a218682]{background:#0009;display:grid;inset:0;place-items:center;position:fixed;z-index:100}.modal-mask-default[data-v-6a218682]{background:linear-gradient(135deg,#2ebbec,#1c93d199 70%,#1c93d100)}.modal-mask-local[data-v-6a218682]{background:linear-gradient(135deg,red,#8b000099 70%,#8b000000)}.modal-content[data-v-6a218682]{border-radius:8px;box-shadow:0 4px 6px #0000001a;color:#000;max-width:600px;padding:20px;width:90%}@media (min-width:768px){.modal-content[data-v-6a218682]{width:50%}}@media (min-width:1024px){.modal-content[data-v-6a218682]{width:40%}}.modal-footer[data-v-6a218682]{border-top:1px solid #ddd;font-size:.8rem;margin-top:1rem;padding-top:.5rem}.div1[data-v-6a218682],.div2[data-v-6a218682]{left:50%;position:absolute;top:50%;transform:translate(-50%,-50%)}.div1[data-v-6a218682]{z-index:2}.div2[data-v-6a218682]{z-index:1}","",{version:3,sources:["webpack://./resources/js/Components/Pages/Welcome/Login.vue"],names:[],mappings:"AA8KA,6BAGE,gBAA8B,CAC9B,YAAa,CAFb,OAAQ,CAGR,kBAAmB,CAJnB,cAAe,CAKf,WACF,CAEA,qCACE,kEACF,CAEA,mCACE,8DACF,CAEA,gCAGE,iBAAkB,CAGlB,8BAAwC,CALxC,UAAY,CAGZ,eAAgB,CAFhB,YAAa,CAGb,SAGF,CAEA,yBACE,gCACE,SACF,CACF,CAEA,0BACE,gCACE,SACF,CACF,CAEA,+BACE,yBAA0B,CAG1B,eAAgB,CAFhB,eAAgB,CAChB,iBAEF,CAEA,8CAGE,QAAS,CAFT,iBAAkB,CAClB,OAAQ,CAER,8BAEF,CAEA,uBACE,SACF,CAEA,uBACE,SACF",sourcesContent:['<template>\n  <Transition\n      enter-from-class="opacity-0 scale-125"\n      enter-to-class="opacity-100 scale-100"\n      enter-active-class="transition duration-300"\n      leave-active-class="transition duration-200"\n      leave-from-class="opacity-100 scale-100"\n      leave-to-class="opacity-0 scale-125"\n  >\n    \x3c!--    <div class="modal-mask overflow-auto py-32 hide-scrollbar">--\x3e\n    \x3c!--      <div class="bg-white py-4 px-4 rounded-lg">--\x3e\n\n    <div v-if="show" :class="[\'modal-mask\', \'overflow-auto\', \'py-32\', \'hide-scrollbar\', \'bg-base-100\', modalClass]">\n      <div class="relative w-full h-full">\n        <div v-if="videoPlayerStore.mistServerUri.includes(\'localhost\')" class="w-full text-center text-white text-2xl font-semibold tracking-wide">LOCAL VERSION</div>\n        <div class="div1 modal-content bg-base-200 py-4 px-4 rounded-lg">\n          <header class="flex justify-center uppercase text-sm font-semibold pt-6 mb-2 text-center">\n            <JetAuthenticationCardLogo class="max-w-[30%]"/>\n\n            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">\n              {{ status }}\n            </div>\n          </header>\n          <JetValidationErrors class="px-6 my-4"/>\n          <div class="text-center mt-4 py-3 text-gray-600">\n            Please log in to watch notTV and chat.\n          </div>\n          <div class="py-3 px-6">\n            <form @submit.prevent="submit">\n              <div class="w-full">\n                <label for="email" class="input input-bordered input-info flex items-center gap-2">\n                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"\n                       class="w-4 h-4 opacity-70">\n                    <path\n                        d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"/>\n                    <path\n                        d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"/>\n                  </svg>\n                  <input id="email"\n                         v-model="form.email" type="email" class="grow border-none w-full" placeholder="Email" required\n                         autofocus/>\n                </label>\n              </div>\n\n              <div class="w-full mt-4">\n                <label for="password" class="input input-bordered input-info flex items-center gap-2">\n                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"\n                       class="w-4 h-4 opacity-70">\n                    <path fill-rule="evenodd"\n                          d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"\n                          clip-rule="evenodd"/>\n                  </svg>\n                  <input id="password"\n                         v-model="form.password" type="password" class="grow border-none w-full" value="" required/>\n                </label>\n              </div>\n\n              <div class="block mt-4">\n                <label class="flex items-center">\n\x3c!--                  <JetCheckbox v-model:checked="form.remember" name="remember"/>--\x3e\n                  <input type="checkbox" v-model="form.remember" class="checkbox checkbox-info" />\n                  <span class="ml-2 text-sm text-gray-600">Remember me</span>\n                </label>\n              </div>\n\n              <div class="flex flex-wrap-reverse items-center justify-end mt-4">\n                <Link :href="route(\'password.request\')" class="underline text-sm text-gray-600 hover:text-gray-900">\n                  Forgot your password?\n                </Link>\n\n                <JetButton class="ml-4 bg-info hover:bg-info/80" :class="{ \'opacity-25\': form.processing }" :disabled="form.processing">\n                  Log in\n                </JetButton>\n              </div>\n            </form>\n          </div>\n\n          <footer>\n            <div class="flex justify-between modal-footer">\n              <button\n                  @click="clearForm"\n                  class="bg-gray-300 p-2 rounded-md hover:bg-gray-400 hover:text-gray-800"\n              >Cancel\n              </button>\n              <div class="mt-2">\n                Need to\n                <button @click="showRegister" class="text-blue-800 hover:text-blue-600">register</button>\n                for an account?\n              </div>\n            </div>\n          </footer>\n        </div>\n\n        <div class="div2">\n          <img src="/storage/images/Ping.png">\n        </div>\n      </div>\n\n\n    </div>\n  </Transition>\n</template>\n\n<script setup>\nimport { useWelcomeStore } from \'@/Stores/WelcomeStore\'\nimport { useVideoPlayerStore } from \'@/Stores/VideoPlayerStore\'\nimport { useUserStore } from \'@/Stores/UserStore\'\nimport { useForm } from \'@inertiajs/inertia-vue3\'\nimport JetAuthenticationCard from \'@/Jetstream/AuthenticationCard\'\nimport JetAuthenticationCardLogo from \'@/Jetstream/AuthenticationCardLogo\'\nimport JetButton from \'@/Jetstream/Button\'\nimport JetInput from \'@/Jetstream/Input\'\nimport JetCheckbox from \'@/Jetstream/Checkbox\'\nimport JetLabel from \'@/Jetstream/Label\'\nimport JetValidationErrors from \'@/Jetstream/ValidationErrors\'\nimport { computed } from \'vue\'\n\nconst welcomeStore = useWelcomeStore()\nconst videoPlayerStore = useVideoPlayerStore()\nconst userStore = useUserStore()\n\ndefineProps({\n  canResetPassword: Boolean,\n  status: String,\n  show: Boolean,\n})\n\nconst form = useForm({\n  email: \'\',\n  password: \'\',\n  remember: false,\n})\n\nconst submit = () => {\n  form.transform(data => ({\n    ...data,\n    remember: form.remember ? \'on\' : \'\',\n  })).post(route(\'login\'), {\n    onFinish: () => {\n      form.reset(\'password\') // Reset the password field after submission\n      console.log(\'get user data on Login\')\n      userStore.fetchUserData() // Fetch user data after successful login\n          .then(() => {\n            // Handle any post-fetch logic here, e.g., redirecting the user or updating the UI\n            // Optionally, update timezone or perform other actions after login\n            // const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;\n            // userStore.updateUserTimezone(timezone);\n          })\n          .catch(error => {\n            console.error(\'Failed to fetch user data:\', error)\n            // Handle any errors in fetching user data here\n          })\n    },\n  })\n}\n\nfunction clearForm() {\n  form.reset()\n  welcomeStore.showLogin = false\n}\n\nfunction showRegister() {\n  form.reset()\n  welcomeStore.showLogin = false\n  welcomeStore.showRegister = true\n}\n\nconst modalClass = computed(() => {\n  return videoPlayerStore.mistServerUri.includes(\'localhost\') ? \'modal-mask-local\' : \'modal-mask-default\';\n});\n\n<\/script>\n\n<style scoped>\n.modal-mask {\n  position: fixed;\n  inset: 0;\n  background: rgba(0, 0, 0, 0.6);\n  display: grid;\n  place-items: center;\n  z-index: 100;\n}\n\n.modal-mask-default {\n  background: linear-gradient(135deg, rgba(46, 187, 236, 1) 0%, rgba(28, 147, 209, 0.6) 70%, rgba(28, 147, 209, 0) 100%);\n}\n\n.modal-mask-local {\n  background: linear-gradient(135deg, rgba(255, 0, 0, 1) 0%, rgba(139, 0, 0, 0.6) 70%, rgba(139, 0, 0, 0) 100%);\n}\n\n.modal-content {\n  color: black;\n  padding: 20px; /* Your padding */\n  border-radius: 8px; /* Your border-radius */\n  max-width: 600px; /* Maximum width */\n  width: 90%; /* Default width */\n  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: Adds a shadow for depth */\n\n}\n\n@media (min-width: 768px) {\n  .modal-content {\n    width: 50%; /* Adjust based on preference */\n  }\n}\n\n@media (min-width: 1024px) {\n  .modal-content {\n    width: 40%; /* Or fixed size like 600px */\n  }\n}\n\n.modal-footer {\n  border-top: 1px solid #ddd;\n  margin-top: 1rem;\n  padding-top: 0.5rem;\n  font-size: .8rem;\n}\n\n.div1, .div2 {\n  position: absolute;\n  top: 50%;\n  left: 50%;\n  transform: translate(-50%, -50%); /* Centers the divs */\n  /* Additional styles here (width, height, etc.) */\n}\n\n.div1 {\n  z-index: 2; /* Higher z-index, div1 will be in front */\n}\n\n.div2 {\n  z-index: 1; /* Lower z-index, div2 will be behind */\n}\n\n</style>\n'],sourceRoot:""}]);const l=s},72406:(e,t,n)=>{n.r(t),n.d(t,{default:()=>z});var o=n(70821),r=n(27569),a=n(40191),s=n(90771),l=n(39038),i=(n(32242),n(5556)),c=n(28277),d=(n(6710),n(43497),n(15957),n(30131));function u(e){return u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},u(e)}function m(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function p(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?m(Object(n),!0).forEach((function(t){f(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):m(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function f(e,t,n){var o;return o=function(e,t){if("object"!=u(e)||!e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var o=n.call(e,t||"default");if("object"!=u(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(t,"string"),(t="symbol"==u(o)?o:String(o))in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var v=function(e){return(0,o.pushScopeId)("data-v-6a218682"),e=e(),(0,o.popScopeId)(),e},g={class:"relative w-full h-full"},b={key:0,class:"w-full text-center text-white text-2xl font-semibold tracking-wide"},h={class:"div1 modal-content bg-base-200 py-4 px-4 rounded-lg"},w={class:"flex justify-center uppercase text-sm font-semibold pt-6 mb-2 text-center"},x={key:0,class:"mb-4 font-medium text-sm text-green-600"},y=v((function(){return(0,o.createElementVNode)("div",{class:"text-center mt-4 py-3 text-gray-600"}," Please log in to watch notTV and chat. ",-1)})),A={class:"py-3 px-6"},k={class:"w-full"},C={for:"email",class:"input input-bordered input-info flex items-center gap-2"},E=v((function(){return(0,o.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 16",fill:"currentColor",class:"w-4 h-4 opacity-70"},[(0,o.createElementVNode)("path",{d:"M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z"}),(0,o.createElementVNode)("path",{d:"M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z"})],-1)})),V={class:"w-full mt-4"},B={for:"password",class:"input input-bordered input-info flex items-center gap-2"},S=v((function(){return(0,o.createElementVNode)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 16",fill:"currentColor",class:"w-4 h-4 opacity-70"},[(0,o.createElementVNode)("path",{"fill-rule":"evenodd",d:"M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z","clip-rule":"evenodd"})],-1)})),N={class:"block mt-4"},L={class:"flex items-center"},F=v((function(){return(0,o.createElementVNode)("span",{class:"ml-2 text-sm text-gray-600"},"Remember me",-1)})),O={class:"flex flex-wrap-reverse items-center justify-end mt-4"},j=v((function(){return(0,o.createElementVNode)("div",{class:"div2"},[(0,o.createElementVNode)("img",{src:"/storage/images/Ping.png"})],-1)}));const P={__name:"Login",props:{canResetPassword:Boolean,status:String,show:Boolean},setup:function(e){var t=(0,r.useWelcomeStore)(),n=(0,a.useVideoPlayerStore)(),u=(0,s.useUserStore)(),m=(0,l.cI)({email:"",password:"",remember:!1}),f=function(){m.transform((function(e){return p(p({},e),{},{remember:m.remember?"on":""})})).post(route("login"),{onFinish:function(){m.reset("password"),console.log("get user data on Login"),u.fetchUserData().then((function(){})).catch((function(e){console.error("Failed to fetch user data:",e)}))}})};function v(){m.reset(),t.showLogin=!1}function P(){m.reset(),t.showLogin=!1,t.showRegister=!0}var Z=(0,o.computed)((function(){return n.mistServerUri.includes("localhost")?"modal-mask-local":"modal-mask-default"}));return function(t,r){var a=(0,o.resolveComponent)("Link");return(0,o.openBlock)(),(0,o.createBlock)(o.Transition,{"enter-from-class":"opacity-0 scale-125","enter-to-class":"opacity-100 scale-100","enter-active-class":"transition duration-300","leave-active-class":"transition duration-200","leave-from-class":"opacity-100 scale-100","leave-to-class":"opacity-0 scale-125"},{default:(0,o.withCtx)((function(){return[e.show?((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:0,class:(0,o.normalizeClass)(["modal-mask","overflow-auto","py-32","hide-scrollbar","bg-base-100",Z.value])},[(0,o.createElementVNode)("div",g,[(0,o.unref)(n).mistServerUri.includes("localhost")?((0,o.openBlock)(),(0,o.createElementBlock)("div",b,"LOCAL VERSION")):(0,o.createCommentVNode)("",!0),(0,o.createElementVNode)("div",h,[(0,o.createElementVNode)("header",w,[(0,o.createVNode)((0,o.unref)(i.Z),{class:"max-w-[30%]"}),e.status?((0,o.openBlock)(),(0,o.createElementBlock)("div",x,(0,o.toDisplayString)(e.status),1)):(0,o.createCommentVNode)("",!0)]),(0,o.createVNode)((0,o.unref)(d.Z),{class:"px-6 my-4"}),y,(0,o.createElementVNode)("div",A,[(0,o.createElementVNode)("form",{onSubmit:(0,o.withModifiers)(f,["prevent"])},[(0,o.createElementVNode)("div",k,[(0,o.createElementVNode)("label",C,[E,(0,o.withDirectives)((0,o.createElementVNode)("input",{id:"email","onUpdate:modelValue":r[0]||(r[0]=function(e){return(0,o.unref)(m).email=e}),type:"email",class:"grow border-none w-full",placeholder:"Email",required:"",autofocus:""},null,512),[[o.vModelText,(0,o.unref)(m).email]])])]),(0,o.createElementVNode)("div",V,[(0,o.createElementVNode)("label",B,[S,(0,o.withDirectives)((0,o.createElementVNode)("input",{id:"password","onUpdate:modelValue":r[1]||(r[1]=function(e){return(0,o.unref)(m).password=e}),type:"password",class:"grow border-none w-full",value:"",required:""},null,512),[[o.vModelText,(0,o.unref)(m).password]])])]),(0,o.createElementVNode)("div",N,[(0,o.createElementVNode)("label",L,[(0,o.withDirectives)((0,o.createElementVNode)("input",{type:"checkbox","onUpdate:modelValue":r[2]||(r[2]=function(e){return(0,o.unref)(m).remember=e}),class:"checkbox checkbox-info"},null,512),[[o.vModelCheckbox,(0,o.unref)(m).remember]]),F])]),(0,o.createElementVNode)("div",O,[(0,o.createVNode)(a,{href:t.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900"},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Forgot your password? ")]})),_:1},8,["href"]),(0,o.createVNode)((0,o.unref)(c.Z),{class:(0,o.normalizeClass)(["ml-4 bg-info hover:bg-info/80",{"opacity-25":(0,o.unref)(m).processing}]),disabled:(0,o.unref)(m).processing},{default:(0,o.withCtx)((function(){return[(0,o.createTextVNode)(" Log in ")]})),_:1},8,["class","disabled"])])],32)]),(0,o.createElementVNode)("footer",null,[(0,o.createElementVNode)("div",{class:"flex justify-between modal-footer"},[(0,o.createElementVNode)("button",{onClick:v,class:"bg-gray-300 p-2 rounded-md hover:bg-gray-400 hover:text-gray-800"},"Cancel "),(0,o.createElementVNode)("div",{class:"mt-2"},[(0,o.createTextVNode)(" Need to "),(0,o.createElementVNode)("button",{onClick:P,class:"text-blue-800 hover:text-blue-600"},"register"),(0,o.createTextVNode)(" for an account? ")])])])]),j])],2)):(0,o.createCommentVNode)("",!0)]})),_:1})}}};var Z=n(93379),_=n.n(Z),J=n(71631),U={insert:"head",singleton:!1};_()(J.Z,U);J.Z.locals;const z=(0,n(83744).Z)(P,[["__scopeId","data-v-6a218682"]])},32242:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r={class:"min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"},a={class:"w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"};const s={},l=(0,n(83744).Z)(s,[["render",function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("div",r,[(0,o.createElementVNode)("div",null,[(0,o.renderSlot)(e.$slots,"logo")]),(0,o.createElementVNode)("div",a,[(0,o.renderSlot)(e.$slots,"default")])])}]])},5556:(e,t,n)=>{n.d(t,{Z:()=>i});var o=n(70821),r=n(58358),a=n(39038),s=n(9680),l=["src"];const i={__name:"AuthenticationCardLogo",setup:function(e){var t=(0,r.useAppSettingStore)();t.pageReload=!1;var n=function(){t.noLayout?(t.pageReload=!0,s.Inertia.visit("/")):s.Inertia.visit("/")};return function(e,t){return(0,o.openBlock)(),(0,o.createBlock)((0,o.unref)(a.rU),{onClick:n},{default:(0,o.withCtx)((function(){return[(0,o.createElementVNode)("img",{src:"/storage/images/logo_black_311.png",alt:"image",class:"justify-center"},null,8,l)]})),_:1})}}}},43497:(e,t,n)=>{n.d(t,{Z:()=>a});var o=n(70821),r=["value"];const a={__name:"Checkbox",props:{checked:{type:[Array,Boolean],default:!1},value:{type:String,default:null}},emits:["update:checked"],setup:function(e,t){var n=t.emit,a=e,s=(0,o.computed)({get:function(){return a.checked},set:function(e){n("update:checked",e)}});return function(t,n){return(0,o.withDirectives)(((0,o.openBlock)(),(0,o.createElementBlock)("input",{"onUpdate:modelValue":n[0]||(n[0]=function(e){return s.value=e}),type:"checkbox",value:e.value,class:"rounded border-2 border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"},null,8,r)),[[o.vModelCheckbox,s.value]])}}}},6710:(e,t,n)=>{n.d(t,{Z:()=>a});var o=n(70821),r=["value"];const a={__name:"Input",props:{modelValue:String},emits:["update:modelValue"],setup:function(e,t){var n=t.expose,a=(0,o.ref)(null);return(0,o.onMounted)((function(){a.value.hasAttribute("autofocus")&&a.value.focus()})),n({focus:function(){return a.value.focus()}}),function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("input",{ref_key:"input",ref:a,class:"border-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm",value:e.modelValue,onInput:n[0]||(n[0]=function(e){return t.$emit("update:modelValue",e.target.value)})},null,40,r)}}}},15957:(e,t,n)=>{n.d(t,{Z:()=>l});var o=n(70821),r={class:"block font-medium text-sm text-gray-700"},a={key:0},s={key:1};const l={__name:"Label",props:{value:String},setup:function(e){return function(t,n){return(0,o.openBlock)(),(0,o.createElementBlock)("label",r,[e.value?((0,o.openBlock)(),(0,o.createElementBlock)("span",a,(0,o.toDisplayString)(e.value),1)):((0,o.openBlock)(),(0,o.createElementBlock)("span",s,[(0,o.renderSlot)(t.$slots,"default")]))])}}}},30131:(e,t,n)=>{n.d(t,{Z:()=>i});var o=n(70821),r=n(39038),a={key:0},s=(0,o.createElementVNode)("div",{class:"font-medium text-red-600"}," Whoops! Something went wrong. ",-1),l={class:"mt-3 list-disc list-inside text-sm text-red-600"};const i={__name:"ValidationErrors",setup:function(e){var t=(0,o.computed)((function(){return(0,r.qt)().props.value.errors})),n=(0,o.computed)((function(){return Object.keys(t.value).length>0}));return function(e,r){return n.value?((0,o.openBlock)(),(0,o.createElementBlock)("div",a,[s,(0,o.createElementVNode)("ul",l,[((0,o.openBlock)(!0),(0,o.createElementBlock)(o.Fragment,null,(0,o.renderList)(t.value,(function(e,t){return(0,o.openBlock)(),(0,o.createElementBlock)("li",{key:t},(0,o.toDisplayString)(e),1)})),128))])])):(0,o.createCommentVNode)("",!0)}}}}}]);
//# sourceMappingURL=2406.js.map