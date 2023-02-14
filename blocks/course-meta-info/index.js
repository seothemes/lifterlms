!function(){"use strict";var e={n:function(t){var l=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(l,{a:l}),l},d:function(t,l){for(var o in l)e.o(l,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:l[o]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,l=window.wp.blocks,o=window.wp.components,r=window.wp.blockEditor,n=window.wp.i18n,i=window.wp.serverSideRender,s=e.n(i),a=window.wp.data,c=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/course-meta-info","title":"Course Meta Information","icon":"info","category":"lifterlms","description":"Display all meta information for a course.","textdomain":"lifterlms","attributes":{"course_id":{"type":"integer"},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}');(0,l.registerBlockType)(c,{edit:e=>{const{attributes:l,setAttributes:i}=e,u=(0,r.useBlockProps)(),{courses:d,postType:p}=(0,a.useSelect)((e=>{var t,l;return{courses:null===(t=e("core"))||void 0===t?void 0:t.getEntityRecords("postType","course"),postType:null===(l=e("core/editor"))||void 0===l?void 0:l.getCurrentPostType()}}),[]),m=(null==d?void 0:d.map((e=>({label:e.title.rendered,value:e.id}))))||[{label:(0,n.__)("No courses found","lifterlms"),value:null}];!l.course_id&&m.length>=1&&(l.course_id=m[0].value);const f=["course","lesson","llms_quiz"].includes(p);return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(r.InspectorControls,null,(0,t.createElement)(o.PanelBody,{title:(0,n.__)("Course Meta Info Settings","lifterlms")},!f&&(0,t.createElement)(o.PanelRow,null,(0,t.createElement)(o.SelectControl,{label:(0,n.__)("Course","lifterlms"),help:(0,n.__)("Select a course to display the course information for.","lifterlms"),value:l.course_id,options:m,onChange:e=>i({course_id:e})})))),(0,t.createElement)("div",u,(0,t.createElement)(o.Disabled,null,(0,t.createElement)(s(),{block:c.name,attributes:l,LoadingResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Loading…","lifterlms")),ErrorResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Error loading content. Please check block settings are valid.","lifterlms")),EmptyResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("No meta information available for this course.","lifterlms"))}))))}})}();