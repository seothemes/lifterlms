!function(){"use strict";var e={n:function(t){var l=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(l,{a:l}),l},d:function(t,l){for(var r in l)e.o(l,r)&&!e.o(t,r)&&Object.defineProperty(t,r,{enumerable:!0,get:l[r]})},o:function(e,t){return Object.prototype.hasOwnProperty.call(e,t)}},t=window.wp.element,l=window.wp.blocks,r=window.wp.components,o=window.wp.blockEditor,n=window.wp.i18n,i=window.wp.serverSideRender,s=e.n(i),a=window.wp.data,u=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"llms/course-author","title":"Course Author","icon":"admin-users","category":"lifterlms","description":"Display the Course Author’s name, avatar, and (optionally) biography for a specific course.","textdomain":"lifterlms","attributes":{"avatar_size":{"type":"integer","default":48},"bio":{"type":"string","default":"yes"},"course_id":{"type":"integer"},"llms_visibility":{"type":"string"},"llms_visibility_in":{"type":"string"},"llms_visibility_posts":{"type":"string"}},"supports":{"align":["wide","full"]},"editorScript":"file:./index.js","render":"file:../../templates/blocks/shortcode.php"}');(0,l.registerBlockType)(u,{edit:e=>{const{attributes:l,setAttributes:i}=e,c=(0,o.useBlockProps)(),{courses:d,postType:p}=(0,a.useSelect)((e=>{var t,l;return{courses:null===(t=e("core"))||void 0===t?void 0:t.getEntityRecords("postType","course"),postType:null===(l=e("core/editor"))||void 0===l?void 0:l.getCurrentPostType()}}),[]),m=(null==d?void 0:d.map((e=>({label:e.title.rendered,value:e.id}))))||[{label:(0,n.__)("No courses found","lifterlms"),value:null}];!l.course_id&&m.length>=1&&(l.course_id=m[0].value);const _=["course","lesson","llms_quiz"].includes(p);return(0,t.createElement)(t.Fragment,null,(0,t.createElement)(o.InspectorControls,null,(0,t.createElement)(r.PanelBody,{title:(0,n.__)("Course Author Settings","lifterlms")},(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.RangeControl,{label:(0,n.__)("Avatar Size","lifterlms"),help:(0,n.__)("The size of the avatar in pixels.","lifterlms"),value:l.avatar_size,onChange:e=>i({avatar_size:e}),min:0,max:300,allowReset:!0})),(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.ToggleControl,{label:(0,n.__)("Display Bio","lifterlms"),help:l.bio?(0,n.__)("Bio is displayed.","lifterlms"):(0,n.__)("Bio is hidden.","lifterlms"),checked:l.bio,onChange:e=>i({bio:e})})),!_&&(0,t.createElement)(r.PanelRow,null,(0,t.createElement)(r.SelectControl,{label:(0,n.__)("Course","lifterlms"),help:(0,n.__)("The course to display the author for.","lifterlms"),value:l.course_id,options:m,onChange:e=>i({course_id:e})})))),(0,t.createElement)("div",c,(0,t.createElement)(r.Disabled,null,(0,t.createElement)(s(),{block:u.name,attributes:l,LoadingResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Loading…","lifterlms")),ErrorResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Error loading content. Please check block settings are valid.","lifterlms")),EmptyResponsePlaceholder:()=>(0,t.createElement)("p",null,(0,n.__)("Author not found.","lifterlms"))}))))}})}();