(()=>{var e,t={7381:(e,t,o)=>{"use strict";o.r(t);var r=o(9196);const i=window.wp.blocks;var n=o(5736);const l=window.wp.blockEditor;var a=o(897);const s=window.wc.wcSettings;var c,p,u,d,g,m,w,v,b,k;const f=(0,s.getSetting)("wcBlocksConfig",{buildPhase:1,pluginUrl:"",productCount:0,defaultAvatar:"",restApiRoutes:{},wordCountType:"words"}),S=(f.pluginUrl,f.pluginUrl,f.buildPhase,null===(c=s.STORE_PAGES.shop)||void 0===c||c.permalink,null===(p=s.STORE_PAGES.checkout)||void 0===p?void 0:p.id),O=(null===(u=s.STORE_PAGES.checkout)||void 0===u||u.permalink,null===(d=s.STORE_PAGES.privacy)||void 0===d||d.permalink,null===(g=s.STORE_PAGES.privacy)||void 0===g||g.title,null===(m=s.STORE_PAGES.terms)||void 0===m||m.permalink,null===(w=s.STORE_PAGES.terms)||void 0===w||w.title,null===(v=s.STORE_PAGES.cart)||void 0===v?void 0:v.id),E=(null===(b=s.STORE_PAGES.cart)||void 0===b||b.permalink,null!==(k=s.STORE_PAGES.myaccount)&&void 0!==k&&k.permalink?s.STORE_PAGES.myaccount.permalink:(0,s.getSetting)("wpLoginUrl","/wp-login.php"),(0,s.getSetting)("localPickupEnabled",!1),(0,s.getSetting)("countries",{})),y=(0,s.getSetting)("countryData",{});Object.fromEntries(Object.keys(y).filter((e=>!0===y[e].allowBilling)).map((e=>[e,E[e]||""]))),Object.fromEntries(Object.keys(y).filter((e=>!0===y[e].allowBilling)).map((e=>[e,y[e].states||[]]))),Object.fromEntries(Object.keys(y).filter((e=>!0===y[e].allowShipping)).map((e=>[e,E[e]||""]))),Object.fromEntries(Object.keys(y).filter((e=>!0===y[e].allowShipping)).map((e=>[e,y[e].states||[]]))),Object.fromEntries(Object.keys(y).map((e=>[e,y[e].locale||[]])));var h=o(9307);const P=JSON.parse('{"name":"woocommerce/page-content-wrapper","version":"1.0.0","title":"WooCommerce Page","description":"Displays WooCommerce page content.","category":"woocommerce","keywords":["WooCommerce"],"textdomain":"woocommerce","supports":{"html":false,"multiple":false,"inserter":false},"attributes":{"page":{"type":"string","default":""}},"providesContext":{"postId":"postId","postType":"postType"},"apiVersion":2,"$schema":"https://schemas.wp.org/trunk/block.json"}');o(1786),(0,i.registerBlockType)(P,{icon:{src:a.Z},edit:({attributes:e,setAttributes:t})=>{const o=(0,l.useBlockProps)({className:"wp-block-woocommerce-page-content-wrapper"});return(0,h.useEffect)((()=>{if(!e.postId&&e.page){let o=0;"checkout"===e.page&&(o=S),"cart"===e.page&&(o=O),o&&t({postId:o,postType:"page"})}}),[e,t]),(0,r.createElement)("div",{...o},(0,r.createElement)(l.InnerBlocks,{template:[["core/post-title",{align:"wide"}],["core/post-content",{align:"wide"}]]}))},save:()=>(0,r.createElement)(l.InnerBlocks.Content,null),variations:[{name:"checkout-page",title:(0,n.__)("Checkout Page","woocommerce"),attributes:{page:"checkout"},isActive:(e,t)=>e.page===t.page},{name:"cart-page",title:(0,n.__)("Cart Page","woocommerce"),attributes:{page:"cart"},isActive:(e,t)=>e.page===t.page}]})},1786:()=>{},9196:e=>{"use strict";e.exports=window.React},9307:e=>{"use strict";e.exports=window.wp.element},5736:e=>{"use strict";e.exports=window.wp.i18n},444:e=>{"use strict";e.exports=window.wp.primitives}},o={};function r(e){var i=o[e];if(void 0!==i)return i.exports;var n=o[e]={exports:{}};return t[e].call(n.exports,n,n.exports,r),n.exports}r.m=t,e=[],r.O=(t,o,i,n)=>{if(!o){var l=1/0;for(p=0;p<e.length;p++){for(var[o,i,n]=e[p],a=!0,s=0;s<o.length;s++)(!1&n||l>=n)&&Object.keys(r.O).every((e=>r.O[e](o[s])))?o.splice(s--,1):(a=!1,n<l&&(l=n));if(a){e.splice(p--,1);var c=i();void 0!==c&&(t=c)}}return t}n=n||0;for(var p=e.length;p>0&&e[p-1][2]>n;p--)e[p]=e[p-1];e[p]=[o,i,n]},r.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return r.d(t,{a:t}),t},r.d=(e,t)=>{for(var o in t)r.o(t,o)&&!r.o(e,o)&&Object.defineProperty(e,o,{enumerable:!0,get:t[o]})},r.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.j=6413,(()=>{var e={6413:0};r.O.j=t=>0===e[t];var t=(t,o)=>{var i,n,[l,a,s]=o,c=0;if(l.some((t=>0!==e[t]))){for(i in a)r.o(a,i)&&(r.m[i]=a[i]);if(s)var p=s(r)}for(t&&t(o);c<l.length;c++)n=l[c],r.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return r.O(p)},o=self.webpackChunkwebpackWcBlocksJsonp=self.webpackChunkwebpackWcBlocksJsonp||[];o.forEach(t.bind(null,0)),o.push=t.bind(null,o.push.bind(o))})();var i=r.O(void 0,[2869],(()=>r(7381)));i=r.O(i),((this.wc=this.wc||{}).blocks=this.wc.blocks||{})["page-content-wrapper"]=i})();