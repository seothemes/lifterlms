!function(){"use strict";var e={n:function(t){var l=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(l,{a:l}),l},d:function(t,l){for(var o in l)e.o(l,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:l[o]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,l=window.wp.blocks,o=window.wp.components,n=window.wp.blockEditor,s=window.wp.i18n,r=window.wp.serverSideRender,i=e.n(r),a=window.wp.data,u=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/course-outline","title":"Course Outline","icon":"info-outline","category":"lifterlms","description":"Outputs the course outline as displayed by the widget of the same name. Can show full course outline or just the current section outline. Setting the Outline Type to Current Sections refers to the section that contains the next uncompleted lesson for current student. If the student is not enrolled then the first section in the course will be displayed.","textdomain":"lifterlms","attributes":{"collapse":{"type":"boolean","default":false},"course_id":{"type":"integer"},"outline_type":{"type":"string","default":"full"},"toggles":{"type":"boolean","default":false},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}');(0,l.registerBlockType)(u,{edit:e=>{const{attributes:l,setAttributes:r}=e,c=(0,n.useBlockProps)(),{courses:p,postType:d}=(0,a.useSelect)((e=>({courses:e("core")?.getEntityRecords("postType","course"),postType:e("core/editor")?.getCurrentPostType()})),[]),m=p?.map((e=>({label:e.title.rendered,value:e.id})))||[{label:(0,s.__)("No courses found","lifterlms"),value:null}];!l.course_id&&m.length>=1&&(l.course_id=m[0].value);const f=["course","lesson","llms_quiz"].includes(d);return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(n.InspectorControls,null,(0,t.createElement)(o.PanelBody,{title:(0,s.__)("Course Outline Settings","lifterlms")},(0,t.createElement)(o.PanelRow,null,(0,t.createElement)(o.ToggleControl,{label:(0,s.__)("Collapse","lifterlms"),help:(0,s.__)("If true, will make the outline sections collapsible via click events.","lifterlms"),checked:l.collapse,onChange:e=>r({collapse:e})})),l.collapse&&(0,t.createElement)(o.PanelRow,null,(0,t.createElement)(o.ToggleControl,{label:(0,s.__)("Toggles","lifterlms"),help:(0,s.__)("If true, will display “Collapse All” and “Expand All” toggles at the bottom of the outline. Only functions if “collapse” is true.","lifterlms"),checked:l.toggles,onChange:e=>r({toggles:e})})),!f&&(0,t.createElement)(o.PanelRow,null,(0,t.createElement)(o.SelectControl,{label:(0,s.__)("Course","lifterlms"),help:(0,s.__)("Select a course to display the course information for.","lifterlms"),value:l.course_id,options:m,onChange:e=>r({course_id:e})})),(0,t.createElement)(o.PanelRow,null,(0,t.createElement)(o.SelectControl,{label:(0,s.__)("Outline Type","lifterlms"),help:(0,s.__)("Select the type of outline to display.","lifterlms"),value:l.outline_type,options:[{label:(0,s.__)("Full","lifterlms"),value:"full",isDefault:!0},{label:(0,s.__)("Current Section","lifterlms"),value:"current_section"}],onChange:e=>r({outline_type:e})})))),(0,t.createElement)("div",c,(0,t.createElement)(o.Disabled,null,(0,t.createElement)(i(),{block:u.name,attributes:l,LoadingResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,s.__)("Loading...","lifterlms")),ErrorResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,s.__)("Error loading content. Please check block settings are valid.","lifterlms")),EmptyResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,s.__)("No outline information available for this course.","lifterlms"))}))))}})}();