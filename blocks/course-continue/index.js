!function(){"use strict";var e={n:function(t){var l=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(l,{a:l}),l},d:function(t,l){for(var r in l)e.o(l,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:l[r]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,l=window.wp.blocks,r=window.wp.components,n=window.wp.blockEditor,o=window.wp.i18n,s=window.wp.serverSideRender,i=e.n(s),u=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/course-continue","title":"Course Progress with Continue Button","icon":"chart-bar","category":"lifterlms","description":"Display a progress bar with continue button for a specific course. Renders only for enrolled students.","textdomain":"lifterlms","attributes":{"course_id":{"type":"integer"},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}'),c=window.wp.data;const a=["course","lesson","llms_quiz"],d=()=>{var e;const t=(0,c.useSelect)((e=>{var t;return null===(t=e("core"))||void 0===t?void 0:t.getEntityRecords("postType","course")}),[]),l=(0,c.useSelect)((e=>{var t;return null===(t=e("core/editor"))||void 0===t?void 0:t.getCurrentPostType()}),[]),r=null!==(e=null==t?void 0:t.map((e=>({label:e.title.rendered,value:e.id}))))&&void 0!==e?e:[];return a.includes(l)&&r.unshift({label:(0,o.__)("Inherit from current ","lifterlms")+(null==l?void 0:l.replace("llms_","")),value:0}),null!=r&&r.length||r.push({label:(0,o.__)("No courses found","lifterlms"),value:0}),r},p=e=>{var l,n;let{attributes:s,setAttributes:i}=e;const u=d();return(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.SelectControl,{label:(0,o.__)("Course","lifterlms"),help:(0,o.__)("The course to display the author for.","lifterlms"),value:null!==(l=s.course_id)&&void 0!==l?l:null==u||null===(n=u[0])||void 0===n?void 0:n.value,options:u,onChange:e=>i({course_id:e})}))};(0,l.registerBlockType)(u,{edit:e=>{const{attributes:l,setAttributes:s}=e,m=(0,n.useBlockProps)(),v=(()=>{const e=(0,c.useSelect)((e=>{var t;return null===(t=e("core/editor"))||void 0===t?void 0:t.getCurrentPostType()}),[]);return a.includes(e)})(),b=d();var f;return l.course_id||v||s({course_id:null==b||null===(f=b[0])||void 0===f?void 0:f.value}),(0,t.createElement)(t.Fragment,null,(0,t.createElement)(n.InspectorControls,null,(0,t.createElement)(r.PanelBody,{title:(0,o.__)("Course Continue Settings","lifterlms")},(0,t.createElement)(p,e))),(0,t.createElement)("div",m,(0,t.createElement)(r.Disabled,null,(0,t.createElement)(i(),{block:u.name,attributes:l,LoadingResponsePlaceholder:()=>(0,t.createElement)(r.Spinner,null),ErrorResponsePlaceholder:()=>(0,t.createElement)("p",{className:"llms-block-error"},(0,o.__)("Error loading content. Please check block settings are valid. This block will not be displayed.","lifterlms")),EmptyResponsePlaceholder:()=>(0,t.createElement)("p",{className:"llms-block-empty"},(0,o.__)("No progress data found for this course. This block will not be displayed.","lifterlms"))}))))}})}();