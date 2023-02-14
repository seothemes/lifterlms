!function(){"use strict";var e,t={n:function(e){var l=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(l,{a:l}),l},d:function(e,l){for(var n in l)t.o(l,n)&&!t.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:l[n]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},l=window.wp.element,n=window.wp.blocks,r=window.wp.components,o=window.wp.blockEditor,i=window.wp.i18n,s=window.wp.serverSideRender,c=t.n(s),a=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/checkout","title":"Checkout","icon":"cart","category":"lifterlms","description":"Outputs the checkout page for purchasing courses and memberships in LifterLMS.","textdomain":"lifterlms","attributes":{"cols":{"type":"integer","default":1},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}');const u=null!==(e=r.BaseControl.VisualLabel)&&void 0!==e?e:(0,l.createElement)(l.Fragment,null);(0,n.registerBlockType)(a,{edit:e=>{const{attributes:t,setAttributes:n}=e,s=(0,o.useBlockProps)(),m={1:(0,i.__)("One","lifterlms"),2:(0,i.__)("Two","lifterlms")};return(0,l.createElement)(l.Fragment,null,(0,l.createElement)(o.InspectorControls,null,(0,l.createElement)(r.PanelBody,{title:(0,i.__)("Checkout Settings","lifterlms")},(0,l.createElement)(r.PanelRow,null,(0,l.createElement)(r.BaseControl,{help:(0,i.__)("Determines the number of columns on the checkout screen. 1 or 2 are the only acceptable values.","lifterlms")},(0,l.createElement)(r.Flex,{direction:"column"},(0,l.createElement)(u,null,(0,i.__)("Number of Columns","lifterlms")),(0,l.createElement)(r.ButtonGroup,null,Object.keys(m).map((e=>(0,l.createElement)(r.Button,{key:e,isPrimary:e===t.cols,onClick:()=>n({cols:e})},m[e]))))))))),(0,l.createElement)("div",s,(0,l.createElement)(r.Disabled,null,(0,l.createElement)(c(),{block:a.name,attributes:t,LoadingResponsePlaceholder:()=>(0,l.createElement)("p",null,(0,i.__)("Loading…","lifterlms")),ErrorResponsePlaceholder:()=>(0,l.createElement)("p",null,(0,i.__)("Error loading content. Please check block settings are valid.","lifterlms"))}))))}})}();