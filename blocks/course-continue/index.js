!function(){"use strict";var e={n:function(t){var r=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(r,{a:r}),r},d:function(t,r){for(var o in r)e.o(r,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:r[o]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,r=window.wp.blocks,o=window.wp.components,l=window.wp.blockEditor,n=window.wp.i18n,s=window.wp.serverSideRender,i=e.n(s),c=window.wp.data,a=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/course-continue","title":"Course Progress with Continue Button","icon":"chart-bar","category":"lifterlms","description":"Display a progress bar with continue button for a specific course. Renders only for enrolled students.","textdomain":"lifterlms","attributes":{"course_id":{"type":"integer"},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}');(0,r.registerBlockType)(a,{edit:e=>{const{attributes:r,setAttributes:s}=e,u=(0,l.useBlockProps)(),{courses:p,postType:d}=(0,c.useSelect)((e=>({courses:e("core")?.getEntityRecords("postType","course"),postType:e("core/editor")?.getCurrentPostType()})),[]),m=p?.map((e=>({label:e.title.rendered,value:e.id})))||[{label:(0,n.__)("No courses found","lifterlms"),value:0}];!r.course_id&&m.length>=1&&(r.course_id=m[0].value);const f=["course","lesson","llms_quiz"].includes(d);return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(l.InspectorControls,null,(0,t.createElement)(o.PanelBody,{title:(0,n.__)("Course Continue Settings","lifterlms")},!f&&(0,t.createElement)(o.PanelRow,null,(0,t.createElement)(o.SelectControl,{label:(0,n.__)("Course","lifterlms"),help:(0,n.__)("Select a course to display the course information for.","lifterlms"),value:r.course_id,options:m,onChange:e=>s({course_id:e})})))),(0,t.createElement)("div",u,(0,t.createElement)(o.Disabled,null,(0,t.createElement)(i(),{block:a.name,attributes:r,LoadingResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Loading...","lifterlms")),ErrorResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Error loading content. Please check block settings are valid.","lifterlms")),EmptyResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("No progress data found for this course.","lifterlms"))}))))}})}();