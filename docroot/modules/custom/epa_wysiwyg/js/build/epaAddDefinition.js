!function(e,t){"object"==typeof exports&&"object"==typeof module?module.exports=t():"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?exports.CKEditor5=t():(e.CKEditor5=e.CKEditor5||{},e.CKEditor5.epaAddDefinition=t())}(self,(function(){return function(){var e={"./node_modules/css-loader/dist/cjs.js!./js/ckeditor5_plugins/epaAddDefinition/src/dialog.css":function(e,t,n){"use strict";var i=n("./node_modules/css-loader/dist/runtime/noSourceMaps.js"),s=n.n(i),r=n("./node_modules/css-loader/dist/runtime/api.js"),o=n.n(r)()(s());o.push([e.id,".epa-add-def {\n  max-width: 90vw;\n\n  form {\n    max-width: 90vw;\n\n    .epa-add-def__match {\n      max-width: 90vw;\n\n      select {\n        display: block;\n        max-width: 80vw;\n\n        option {\n          max-width: 75vw;\n        }\n      }\n    }\n  }\n}\n\n@media screen and (min-width: 800px) {\n  .epa-add-def {\n    max-width: 800px;\n\n    form {\n      max-width: 750px;\n\n      .epa-add-def__match {\n        max-width: 700px;\n\n        select {\n          display: block;\n          max-width: 700px;\n\n          option {\n            max-width: 600px;\n          }\n        }\n      }\n    }\n  }\n}\n",""]),t.Z=o},"./node_modules/css-loader/dist/runtime/api.js":function(e){"use strict";e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var n="",i=void 0!==t[5];return t[4]&&(n+="@supports (".concat(t[4],") {")),t[2]&&(n+="@media ".concat(t[2]," {")),i&&(n+="@layer".concat(t[5].length>0?" ".concat(t[5]):""," {")),n+=e(t),i&&(n+="}"),t[2]&&(n+="}"),t[4]&&(n+="}"),n})).join("")},t.i=function(e,n,i,s,r){"string"==typeof e&&(e=[[null,e,void 0]]);var o={};if(i)for(var a=0;a<this.length;a++){var c=this[a][0];null!=c&&(o[c]=!0)}for(var d=0;d<e.length;d++){var l=[].concat(e[d]);i&&o[l[0]]||(void 0!==r&&(void 0===l[5]||(l[1]="@layer".concat(l[5].length>0?" ".concat(l[5]):""," {").concat(l[1],"}")),l[5]=r),n&&(l[2]?(l[1]="@media ".concat(l[2]," {").concat(l[1],"}"),l[2]=n):l[2]=n),s&&(l[4]?(l[1]="@supports (".concat(l[4],") {").concat(l[1],"}"),l[4]=s):l[4]="".concat(s)),t.push(l))}},t}},"./node_modules/css-loader/dist/runtime/noSourceMaps.js":function(e){"use strict";e.exports=function(e){return e[1]}},"./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":function(e){"use strict";var t=[];function n(e){for(var n=-1,i=0;i<t.length;i++)if(t[i].identifier===e){n=i;break}return n}function i(e,i){for(var r={},o=[],a=0;a<e.length;a++){var c=e[a],d=i.base?c[0]+i.base:c[0],l=r[d]||0,u="".concat(d," ").concat(l);r[d]=l+1;var f=n(u),h={css:c[1],media:c[2],sourceMap:c[3],supports:c[4],layer:c[5]};if(-1!==f)t[f].references++,t[f].updater(h);else{var m=s(h,i);i.byIndex=a,t.splice(a,0,{identifier:u,updater:m,references:1})}o.push(u)}return o}function s(e,t){var n=t.domAPI(t);n.update(e);return function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap&&t.supports===e.supports&&t.layer===e.layer)return;n.update(e=t)}else n.remove()}}e.exports=function(e,s){var r=i(e=e||[],s=s||{});return function(e){e=e||[];for(var o=0;o<r.length;o++){var a=n(r[o]);t[a].references--}for(var c=i(e,s),d=0;d<r.length;d++){var l=n(r[d]);0===t[l].references&&(t[l].updater(),t.splice(l,1))}r=c}}},"./node_modules/style-loader/dist/runtime/insertBySelector.js":function(e){"use strict";var t={};e.exports=function(e,n){var i=function(e){if(void 0===t[e]){var n=document.querySelector(e);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(e){n=null}t[e]=n}return t[e]}(e);if(!i)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");i.appendChild(n)}},"./node_modules/style-loader/dist/runtime/insertStyleElement.js":function(e){"use strict";e.exports=function(e){var t=document.createElement("style");return e.setAttributes(t,e.attributes),e.insert(t,e.options),t}},"./node_modules/style-loader/dist/runtime/setAttributesWithAttributesAndNonce.js":function(e){"use strict";e.exports=function(e,t){Object.keys(t).forEach((function(n){e.setAttribute(n,t[n])}))}},"./node_modules/style-loader/dist/runtime/singletonStyleDomAPI.js":function(e){"use strict";var t,n=(t=[],function(e,n){return t[e]=n,t.filter(Boolean).join("\n")});function i(e,t,i,s){var r;if(i)r="";else{r="",s.supports&&(r+="@supports (".concat(s.supports,") {")),s.media&&(r+="@media ".concat(s.media," {"));var o=void 0!==s.layer;o&&(r+="@layer".concat(s.layer.length>0?" ".concat(s.layer):""," {")),r+=s.css,o&&(r+="}"),s.media&&(r+="}"),s.supports&&(r+="}")}if(e.styleSheet)e.styleSheet.cssText=n(t,r);else{var a=document.createTextNode(r),c=e.childNodes;c[t]&&e.removeChild(c[t]),c.length?e.insertBefore(a,c[t]):e.appendChild(a)}}var s={singleton:null,singletonCounter:0};e.exports=function(e){if("undefined"==typeof document)return{update:function(){},remove:function(){}};var t=s.singletonCounter++,n=s.singleton||(s.singleton=e.insertStyleElement(e));return{update:function(e){i(n,t,!1,e)},remove:function(e){i(n,t,!0,e)}}}},"ckeditor5/src/core.js":function(e,t,n){e.exports=n("dll-reference CKEditor5.dll")("./src/core.js")},"ckeditor5/src/ui.js":function(e,t,n){e.exports=n("dll-reference CKEditor5.dll")("./src/ui.js")},"ckeditor5/src/widget.js":function(e,t,n){e.exports=n("dll-reference CKEditor5.dll")("./src/widget.js")},"dll-reference CKEditor5.dll":function(e){"use strict";e.exports=CKEditor5.dll}},t={};function n(i){var s=t[i];if(void 0!==s)return s.exports;var r=t[i]={id:i,exports:{}};return e[i](r,r.exports,n),r.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var i in t)n.o(t,i)&&!n.o(e,i)&&Object.defineProperty(e,i,{enumerable:!0,get:t[i]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)};var i={};return function(){"use strict";n.d(i,{default:function(){return K}});var e=n("ckeditor5/src/core.js"),t=n("ckeditor5/src/widget.js");const s="definition js-definition",r="definition__trigger js-definition__trigger",o="definition__tooltip js-definition__tooltip",a="definition__term",c="epaDefinition",d="term",l="definition";class u extends e.Plugin{static get requires(){return[t.Widget]}init(){this._defineSchema(),this._defineConverters()}_defineSchema(){this.editor.model.schema.register(c,{inheritAllFrom:"$inlineObject",allowAttributes:[d,l]})}_defineConverters(){const e=this.editor.conversion;e.for("editingDowncast").elementToElement({model:c,view:(e,n)=>{const{writer:i}=n,s=e.getAttribute(d)||"",o=e.getAttribute(l)||"",a=i.createContainerElement("dfn",{title:o,class:r},[i.createText(s)]);return(0,t.toWidget)(a,i,{label:"term definition display"})}}),e.for("dataDowncast").elementToStructure({model:c,view:(e,{writer:t})=>{const n=e.getAttribute(d)||"",i=e.getAttribute(l)||"",c=t.createContainerElement.bind(t);return c("span",{class:s},[c("button",{class:r},[t.createText(n)]),c("span",{class:o,role:"tooltip"},[c("dfn",{class:a},[t.createText(n)]),t.createText(i)])])}}),e.for("upcast").add((e=>{e.on("element:span",((e,t,n)=>{const{viewItem:i}=t,{consumable:u,writer:f,safeInsert:h,updateConversionResult:m}=n,p={name:!0,classes:s.split(" ")};if(!u.test(i,p))return;if(2!==i.childCount)return;const g=i.getChild(0),w={name:!0,classes:r.split(" ")};if(!(g.is("element","button")&&u.test(g,w)))return;const v=i.getChild(1),y={name:!0,classes:o.split(" "),attributes:["role"]};if(!(v.is("element","span")&&u.test(v,y)))return;if(2!==v.childCount)return;const x=v.getChild(0),b={name:!0,classes:a.split(" ")};if(!(x.is("element","dfn")&&u.test(x,b)))return;if(1!==x.childCount)return;const _=x.getChild(0);if(!_.is("$text"))return;const j=v.getChild(1);if(!j.is("$text"))return;const C=f.createElement(c,{[d]:_.data,[l]:j.data});h(C,t.modelCursor)&&(u.consume(i,p),u.consume(g,w),u.consume(v,y),u.consume(x,b),m(C,t))}))}))}}var f=n("ckeditor5/src/ui.js"),h=n("./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js"),m=n.n(h),p=n("./node_modules/style-loader/dist/runtime/singletonStyleDomAPI.js"),g=n.n(p),w=n("./node_modules/style-loader/dist/runtime/insertBySelector.js"),v=n.n(w),y=n("./node_modules/style-loader/dist/runtime/setAttributesWithAttributesAndNonce.js"),x=n.n(y),b=n("./node_modules/style-loader/dist/runtime/insertStyleElement.js"),_=n.n(b),j=n("./node_modules/css-loader/dist/cjs.js!./js/ckeditor5_plugins/epaAddDefinition/src/dialog.css"),C={attributes:{"data-cke":!0}};C.setAttributes=x(),C.insert=v().bind(null,"head"),C.domAPI=g(),C.insertStyleElement=_();m()(j.Z,C),j.Z&&j.Z.locals&&j.Z.locals;let E=0;var A=function(){return"epa-add-def-"+E++};function T(e,t,...n){const i=document.createElement(e);for(const[e,n]of Object.entries(t))i.setAttribute(e,n);for(const e of n){const t="string"==typeof e?document.createTextNode(e):e;i.appendChild(t)}return i}class k extends f.View{constructor(e){super(e),this.set("term",""),this.set("definitions",[]),this.set("selected","");const t=this.bindTemplate;this.selectId=A(),this.select=T("select",{id:this.selectId}),this.setTemplate({tag:"div",attributes:{class:["epa-add-def__match"]},children:[{tag:"label",attributes:{for:this.selectId},children:[{text:"Term: "},{text:t.to("term")}]},this.select],on:{[`change@select#${this.selectId}`]:t.to((()=>{const e=this.select.selectedIndex,t=0===e?"":this.definitions[e-1].definition;this.selected=t}))}}),this.on("change:definitions",((e,t,n)=>{const i=this.select.options;i.length=0,i.add(T("option",{value:""},""));for(const e of n){const t=125,n=`<${e.dictionary}> ${e.definition}`,s=n.substring(0,t),r=n.length>=t?s+"[...]":n;i.add(T("option",{value:n},r))}}))}}var S=function(e,t,n,i){const s=e.length,r=t.length,o=Math.min(s,r);for(let n=0;n<o;n++)i(t.get(n),e[n]);if(s>r){const s=e.slice(o).map((e=>{const t=n();return i(t,e),t}));t.addMany(s)}else if(s<r){const e=t.filter(((e,t)=>t>=o)).reverse();for(const n of e)t.remove(n)}};class O extends f.View{constructor(e){super(e),this.set("matches",[]),this.views=this.createCollection(),this.setTemplate({tag:"div",children:this.views}),this.on("change:matches",((e,t,n)=>{S(n,this.views,(()=>new k(this.locale)),((e,t)=>{e.term=t.term,e.definitions=t.definitions}))}))}}class D extends f.View{constructor(t){super(t),this.set("data",[]),this.listView=new O(t),this.listView.bind("matches").to(this,"data"),this.submitButton=this._createButton("Save",e.icons.check,"ck-button-save"),this.submitButton.type="submit",this.cancelButton=this._createButton("Cancel",e.icons.cancel,"ck-button-cancel"),this.cancelButton.delegate("execute").to(this,"cancel");const n=this.bindTemplate;this.setTemplate({tag:"dialog",on:{close:n.to("cancel")},attributes:{class:"epa-add-def"},children:[{tag:"form",children:[this.listView,this.submitButton,this.cancelButton]}]})}render(){super.render(),(0,f.submitHandler)({view:this})}show(){this.element.showModal()}hide(){this.element.close()}_createButton(e,t,n){const i=new f.ButtonView(this.locale);return i.set({label:e,icon:t,tooltip:!0,class:n}),i}}class I extends Error{constructor(...e){super(...e),this._userError="Cannot add definitions across paragraphs"}}class M extends Error{constructor(...e){super(...e),this._userError="No term definitions were found that exactly match your selected word or phrase"}}class B extends Error{constructor(...e){super(...e),this._userError="Network error while looking up definitions"}}var P=async function(e){const t=new URLSearchParams;t.set("text",e);const n=await fetch("https://termlookup.epa.gov/termlookup/v1/terms",{method:"POST",body:t,headers:{"Content-Type":"application/x-www-form-urlencoded"}});if(!n.ok){const e=new B(`Failed to look up terms: ${n.status} ${n.statusText}`);try{const t=await n.text();Object.assign(e,{responseText:t})}catch(e){}throw e}return n.json()};const V="epaAddDefinitions";class $ extends e.Plugin{static get requires(){return[f.Notification]}static get pluginName(){return"EpaAddDefinitionUI"}init(){const e=this.editor;this.modalView=new D(this.editor.locale),e.ui.componentFactory.add("epaAddDefinition",(t=>{const n=new f.ButtonView(t);return n.set({label:e.t("Add Definition"),icon:'<svg viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="m96 0c-53 0-96 43-96 96v320c0 53 43 96 96 96h288 32c17.7 0 32-14.3 32-32s-14.3-32-32-32v-64c17.7 0 32-14.3 32-32v-320c0-17.7-14.3-32-32-32h-32zm0 384h256v64h-256c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16h192c8.8 0 16 7.2 16 16s-7.2 16-16 16h-192c-8.8 0-16-7.2-16-16zm16 48h192c8.8 0 16 7.2 16 16s-7.2 16-16 16h-192c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>',tooltip:!0}),n.bind("isEnabled").to(e,"isReadOnly",(e=>!e)),this.listenTo(n,"execute",(()=>{this._execute().catch((e=>{const t=this.editor.plugins.get(f.Notification),n=e._userError||"An unexpected error occurred";t.showWarning(n,{namespace:"epa:addDefinition"}),console.error(e)}))})),n}))}async _execute(){const e=this.modalView;e&&!e.isRendered&&(e.render(),document.body.appendChild(e.element));const t=this.editor.model,n=t.document.selection,i=n.getFirstRange();if(!i||i&&i.isCollapsed)return;const s=Array.from(i.getItems()),r=n.getFirstPosition().findAncestor("paragraph"),o=Array.from(r.getChildren());let a="",c="",d=[];function l(e){if(e.data)c+=e.data;else if("epaDefinition"===e.name)c+=" ";else if("paragraph"===e.name)throw new I}function u(e){if(e.data){let t=a.length;a+=e.data,d.push({node:e,startOffset:t,endOffset:a.length})}else if("epaDefinition"===e.name){let t=a.length;a+=" ",d.push({node:e,startOffset:t,endOffset:t+a.length+1})}else if("paragraph"===e.name)throw new I}for(const e of o)u(e);for(const e of s)l(e);if(c&&""===c.trim())return;let f=null,h=null;try{if(!e)throw new Error("Modal not initialized");if(this.editor.enableReadOnlyMode(V),h=await P(c),null===h)return;const t=h?h.matches:null;if(!t||0===t.length)throw new M(`Could not find a term that matches '${c}'`);e.data=t,e.show(),f=await new Promise((t=>{let n;function i(e){return()=>{n||(t(e),n=!0)}}e.on("submit",i(!0)),e.on("cancel",i(!1))}))}finally{if(!e)throw new Error("Modal not initialized");this.editor.disableReadOnlyMode(V),e.hide()}if(!f)return;const m=e.listView.views._items,p=[];t.change((t=>{e.data.forEach((e=>{const n=e.term,i=a.toLocaleLowerCase().indexOf(n);if(-1!==i){const e=i+n.length,s=a.substring(i,e),r=d.find((e=>e.startOffset<=i&&e.endOffset>i)),o=d.find((t=>t.startOffset<e&&t.endOffset>=e));if(r&&o){const a=t.createPositionAt(r.node.parent,i),c=t.createPositionAt(o.node.parent,e),d=t.createRange(a,c),l=`Term: ${n} - ${Date.now()}`,u=t.addMarker(l,{range:d,usingOperation:!0,affectsData:!0});p.push({term:n,originalTerm:s,range:d,marker:u})}}}))})),m&&p.length>0&&t.change((e=>{for(const t in m)if(m[t].selected){const n=p.find((e=>e.term===m[t].term)),i=n.range;e.remove(i),e.insertElement("epaDefinition",{term:n.originalTerm,definition:m[t].selected},i.start)}})),t.change((t=>{p.forEach((e=>{t.removeMarker(e.marker)})),e.listView.views.clear()}))}}class N extends e.Plugin{static get requires(){return[$,u]}}var K={EpaAddDefinition:N}}(),i=i.default}()}));