(()=>{"use strict";const e=window.React,o=window.wp.blocks,t=window.wp.i18n,n=window.wp.blockEditor,a=window.wp.components;let l=0;const r=JSON.parse('{"UU":"boosted/boosted-registration"}');(0,o.registerBlockType)(r.UU,{edit:function({attributes:o,setAttributes:r}){const{usernameLabel:s,usernamePlaceholder:d,emailLabel:i,emailPlaceholder:m,passwordLabel:c,passwordPlaceholder:b,registerButtonLabel:u,confirmPassword:p,confirmPasswordPlaceholder:f,uniqueId:h}=o;return h||r({uniqueId:"boosted-registration-"+ ++l}),(0,e.createElement)(e.Fragment,null,(0,e.createElement)(n.InspectorControls,null,(0,e.createElement)(a.PanelBody,{title:(0,t.__)("Login Form Settings","boosted-front-end-login")},(0,e.createElement)(a.TextControl,{label:(0,t.__)("Username Label","boosted-front-end-login"),value:s,onChange:e=>r({usernameLabel:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Username Placeholder","boosted-front-end-login"),value:d,onChange:e=>r({usernamePlaceholder:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Email Label","boosted-front-end-login"),value:i,onChange:e=>r({emailLabel:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Email Placeholder","boosted-front-end-login"),value:m,onChange:e=>r({emailPlaceholder:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Password Label","boosted-front-end-login"),value:c,onChange:e=>r({passwordLabel:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Password Placeholder","boosted-front-end-login"),value:b,onChange:e=>r({passwordPlaceholder:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Confirm Password Label","boosted-front-end-login"),value:p,onChange:e=>r({confirmPassword:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Confirm Password Placeholder","boosted-front-end-login"),value:f,onChange:e=>r({confirmPasswordPlaceholder:e})}),(0,e.createElement)(a.TextControl,{label:(0,t.__)("Register Button","boosted-front-end-login"),value:u,onChange:e=>r({registerButtonLabel:e})}))),(0,e.createElement)("div",{...(0,n.useBlockProps)()},(0,e.createElement)("form",{className:"boosted-front-end boosted-front-end-login",method:"post",action:"#"},(0,e.createElement)("p",{className:"boosted-front-end-username"},(0,e.createElement)(n.RichText,{tagName:"label",htmlFor:"username",value:s,onChange:e=>r({usernameLabel:e}),placeholder:(0,t.__)("Username","boosted-front-end-login")}),(0,e.createElement)("input",{className:"boosted-front-end-username-field",type:"text",id:"username",name:"username",required:!0,placeholder:d})),(0,e.createElement)("p",{className:"boosted-front-end-email"},(0,e.createElement)(n.RichText,{tagName:"label",htmlFor:"user_email",value:i,onChange:e=>r({emailLabel:e}),placeholder:(0,t.__)("Email","boosted-front-end-login")}),(0,e.createElement)("input",{className:"boosted-front-end-password-field",type:"email",id:"user_email",name:"user_email",required:!0,placeholder:m})),(0,e.createElement)("p",{className:"boosted-front-end-password"},(0,e.createElement)(n.RichText,{tagName:"label",htmlFor:"user_pass",value:c,onChange:e=>r({passwordLabel:e}),placeholder:(0,t.__)("Password","boosted-front-end-login")}),(0,e.createElement)("input",{className:"boosted-front-end-password-confirm-field",type:"email",id:"user_pass",name:"user_pass",required:!0,placeholder:b})),(0,e.createElement)("p",{className:"boosted-front-end-password-confirm"},(0,e.createElement)(n.RichText,{tagName:"label",htmlFor:"user_pas_confirm",value:p,onChange:e=>r({confirmPassword:e}),placeholder:(0,t.__)("Confirm Password","boosted-front-end-login")}),(0,e.createElement)("input",{className:"boosted-front-end-password-confirm-field",type:"email",id:"user_pas_confirm",name:"user_pas_confirm",required:!0,placeholder:f})),(0,e.createElement)("p",{className:"boosted-front-end-submit"},(0,e.createElement)("input",{type:"submit",className:"boosted-front-end-submit-btn",value:u,"aria-label":(0,t.__)("Register a new account","boosted-front-end-login"),disabled:!0})))))},icon:()=>(0,e.createElement)("svg",{width:"800px",height:"800px",viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,e.createElement)("path",{d:"M20 18L17 18M17 18L14 18M17 18V15M17 18V21M11 21H4C4 17.134 7.13401 14 11 14C11.695 14 12.3663 14.1013 13 14.2899M15 7C15 9.20914 13.2091 11 11 11C8.79086 11 7 9.20914 7 7C7 4.79086 8.79086 3 11 3C13.2091 3 15 4.79086 15 7Z",stroke:"#000000",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"}))})})();