!function(){"use strict";var e={n:function(t){var l=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(l,{a:l}),l},d:function(t,l){for(var r in l)e.o(l,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:l[r]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,l=window.wp.blocks,r=window.wp.components,o=window.wp.blockEditor,n=window.wp.i18n,i=window.wp.serverSideRender,s=e.n(i),u=window.wp.data;const a=["course","lesson","llms_quiz"],c=()=>{var e;const t=(0,u.useSelect)((e=>{var t;return null===(t=e("core"))||void 0===t?void 0:t.getEntityRecords("postType","course")}),[]),l=(0,u.useSelect)((e=>{var t;return null===(t=e("core/editor"))||void 0===t?void 0:t.getCurrentPostType()}),[]),r=null!==(e=null==t?void 0:t.map((e=>({label:e.title.rendered,value:e.id}))))&&void 0!==e?e:[];return a.includes(l)&&r.unshift({label:(0,n.__)("Inherit from current ","lifterlms")+(null==l?void 0:l.replace("llms_","")),value:0}),null!=r&&r.length||r.push({label:(0,n.__)("No courses found","lifterlms"),value:0}),r},d=e=>{var l,o;let{attributes:i,setAttributes:s}=e;const u=c();return(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.SelectControl,{label:(0,n.__)("Course","lifterlms"),help:(0,n.__)("The course to display the author for.","lifterlms"),value:null!==(l=i.course_id)&&void 0!==l?l:null==u||null===(o=u[0])||void 0===o?void 0:o.value,options:u,onChange:e=>s({course_id:e})}))};var p=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/course-reviews","title":"Course Reviews","icon":"star-half","category":"lifterlms","description":"Display reviews and review form for a LifterLMS Course.","textdomain":"lifterlms","attributes":{"course_id":{"type":"integer"},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}');(0,l.registerBlockType)(p,{edit:e=>{const{attributes:l,setAttributes:i}=e,m=(0,o.useBlockProps)(),v=(()=>{const e=(0,u.useSelect)((e=>{var t;return null===(t=e("core/editor"))||void 0===t?void 0:t.getCurrentPostType()}),[]);return a.includes(e)})(),w=c();var f;return l.course_id||v||i({course_id:null==w||null===(f=w[0])||void 0===f?void 0:f.value}),(0,t.createElement)(t.Fragment,null,(0,t.createElement)(o.InspectorControls,null,(0,t.createElement)(r.PanelBody,{title:(0,n.__)("Course Reviews Settings","lifterlms")},(0,t.createElement)(d,e))),(0,t.createElement)("div",m,(0,t.createElement)(r.Disabled,null,(0,t.createElement)(s(),{block:p.name,attributes:l,LoadingResponsePlaceholder:()=>(0,t.createElement)(r.Spinner,null),ErrorResponsePlaceholder:()=>(0,t.createElement)("p",{className:"llms-block-error"},(0,n.__)("Error loading content. Please check block settings are valid. This block will not be displayed.","lifterlms")),EmptyResponsePlaceholder:()=>(0,t.createElement)("p",{className:"llms-block-empty"},(0,n.__)("No reviews found for this course. This block will not be displayed.","lifterlms"))}))))}})}();