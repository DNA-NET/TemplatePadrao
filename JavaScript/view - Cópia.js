(function() {

var i=true,j=null,k=false,p,q=this;function r(){}
function t(a){var b=typeof a;if(b=="object")if(a){if(a instanceof Array||!(a instanceof Object)&&Object.prototype.toString.call(a)=="[object Array]"||typeof a.length=="number"&&typeof a.splice!="undefined"&&typeof a.propertyIsEnumerable!="undefined"&&!a.propertyIsEnumerable("splice"))return"array";if(!(a instanceof Object)&&(Object.prototype.toString.call(a)=="[object Function]"||typeof a.call!="undefined"&&typeof a.propertyIsEnumerable!="undefined"&&!a.propertyIsEnumerable("call")))return"function"}else return"null";
else if(b=="function"&&typeof a.call=="undefined")return"object";return b}function v(a){return typeof a=="string"}function w(a){return a[aa]||(a[aa]=++ba)}var aa="closure_uid_"+Math.floor(Math.random()*2147483648).toString(36),ba=0;function ca(a,b){var c=b||q;if(arguments.length>2){var d=Array.prototype.slice.call(arguments,2);return function(){var e=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(e,d);return a.apply(c,e)}}else return function(){return a.apply(c,arguments)}}
function x(a,b){function c(){}c.prototype=b.prototype;a.s=b.prototype;a.prototype=new c};function da(a){this.stack=Error().stack||"";if(a)this.message=String(a)}x(da,Error);function ea(a){for(var b=1;b<arguments.length;b++){var c=String(arguments[b]).replace(/\$/g,"$$$$");a=a.replace(/\%s/,c)}return a}
function fa(a,b){for(var c=0,d=String(a).replace(/^[\s\xa0]+|[\s\xa0]+$/g,"").split("."),e=String(b).replace(/^[\s\xa0]+|[\s\xa0]+$/g,"").split("."),f=Math.max(d.length,e.length),g=0;c==0&&g<f;g++){var h=d[g]||"",l=e[g]||"",m=RegExp("(\\d*)(\\D*)","g"),u=RegExp("(\\d*)(\\D*)","g");do{var o=m.exec(h)||["","",""],n=u.exec(l)||["","",""];if(o[0].length==0&&n[0].length==0)break;c=ga(o[1].length==0?0:parseInt(o[1],10),n[1].length==0?0:parseInt(n[1],10))||ga(o[2].length==0,n[2].length==0)||ga(o[2],n[2])}while(c==
0)}return c}function ga(a,b){if(a<b)return-1;else if(a>b)return 1;return 0};function ha(a,b){b.unshift(a);da.call(this,ea.apply(j,b));b.shift();this.U=a}x(ha,da);function y(a,b){if(!a){var c=Array.prototype.slice.call(arguments,2),d="Assertion failed";if(b){d+=": "+b;var e=c}throw new ha(""+d,e||[]);}return a};var z=Array.prototype,A=z.indexOf?function(a,b,c){y(a.length!=j);return z.indexOf.call(a,b,c)}:function(a,b,c){c=c==j?0:c<0?Math.max(0,a.length+c):c;if(v(a)){if(!v(b)||b.length!=1)return-1;return a.indexOf(b,c)}for(c=c;c<a.length;c++)if(c in a&&a[c]===b)return c;return-1},B=z.forEach?function(a,b,c){y(a.length!=j);z.forEach.call(a,b,c)}:function(a,b,c){for(var d=a.length,e=v(a)?a.split(""):a,f=0;f<d;f++)f in e&&b.call(c,e[f],f,a)};
function ia(a){y(a.length!=j);return z.splice.apply(a,ja(arguments,1))}function ja(a,b,c){y(a.length!=j);return arguments.length<=2?z.slice.call(a,b):z.slice.call(a,b,c)};var C,ka,D,la;function ma(){return q.navigator?q.navigator.userAgent:j}la=D=ka=C=k;var E;if(E=ma()){var na=q.navigator;C=E.indexOf("Opera")==0;ka=!C&&E.indexOf("MSIE")!=-1;D=!C&&E.indexOf("WebKit")!=-1;la=!C&&!D&&na.product=="Gecko"}var F=ka,oa=la,pa=D,qa=q.navigator,ra=(qa&&qa.platform||"").indexOf("Mac")!=-1,sa;
a:{var G="",H;if(C&&q.opera){var ta=q.opera.version;G=typeof ta=="function"?ta():ta}else{if(oa)H=/rv\:([^\);]+)(\)|;)/;else if(F)H=/MSIE\s+([^\);]+)(\)|;)/;else if(pa)H=/WebKit\/(\S+)/;if(H){var ua=H.exec(ma());G=ua?ua[1]:""}}if(F){var va,wa=q.document;va=wa?wa.documentMode:undefined;if(va>parseFloat(G)){sa=String(va);break a}}sa=G}var xa={};function I(a){return xa[a]||(xa[a]=fa(sa,a)>=0)};!F||I("9");F&&I("9");function J(a){return(a=a.className)&&typeof a.split=="function"?a.split(/\s+/):[]}function K(a){var b=J(a),c;c=ja(arguments,1);for(var d=0,e=0;e<c.length;e++)if(!(A(b,c[e])>=0)){b.push(c[e]);d++}c=d==c.length;a.className=b.join(" ");return c}function L(a){var b=J(a),c;c=ja(arguments,1);for(var d=0,e=0;e<b.length;e++)if(A(c,b[e])>=0){ia(b,e--,1);d++}c=d==c.length;a.className=b.join(" ");return c};function ya(a,b,c){for(var d in a)b.call(c,a[d],d,a)};function M(a){return v(a)?document.getElementById(a):a}
function za(a,b,c,d){a=d||a;b=b&&b!="*"?b.toUpperCase():"";if(a.querySelectorAll&&a.querySelector&&(!pa||document.compatMode=="CSS1Compat"||I("528"))&&(b||c))return a.querySelectorAll(b+(c?"."+c:""));if(c&&a.getElementsByClassName){a=a.getElementsByClassName(c);if(b){d={};for(var e=0,f=0,g;g=a[f];f++)if(b==g.nodeName)d[e++]=g;d.length=e;return d}else return a}a=a.getElementsByTagName(b||"*");if(c){d={};for(f=e=0;g=a[f];f++){b=g.className;if(typeof b.split=="function"&&A(b.split(/\s+/),c)>=0)d[e++]=
g}d.length=e;return d}else return a};var Aa;!F||I("9");var Ba=F&&!I("8");function N(){}N.prototype.B=k;N.prototype.h=function(){if(!this.B){this.B=i;this.d()}};N.prototype.d=function(){};function O(a,b){this.type=a;this.currentTarget=this.target=b}x(O,N);O.prototype.d=function(){delete this.type;delete this.target;delete this.currentTarget};O.prototype.p=k;O.prototype.L=i;O.prototype.preventDefault=function(){this.L=k};function P(a,b){a&&this.i(a,b)}x(P,O);p=P.prototype;p.target=j;p.relatedTarget=j;p.offsetX=0;p.offsetY=0;p.clientX=0;p.clientY=0;p.screenX=0;p.screenY=0;p.button=0;p.keyCode=0;p.charCode=0;p.ctrlKey=k;p.altKey=k;p.shiftKey=k;p.metaKey=k;p.R=k;p.m=j;
p.i=function(a,b){var c=this.type=a.type;this.target=a.target||a.srcElement;this.currentTarget=b;var d=a.relatedTarget;if(d){if(oa)try{d=d.nodeName&&d}catch(e){d=j}}else if(c=="mouseover")d=a.fromElement;else if(c=="mouseout")d=a.toElement;this.relatedTarget=d;this.offsetX=a.offsetX!==undefined?a.offsetX:a.layerX;this.offsetY=a.offsetY!==undefined?a.offsetY:a.layerY;this.clientX=a.clientX!==undefined?a.clientX:a.pageX;this.clientY=a.clientY!==undefined?a.clientY:a.pageY;this.screenX=a.screenX||0;
this.screenY=a.screenY||0;this.button=a.button;this.keyCode=a.keyCode||0;this.charCode=a.charCode||(c=="keypress"?a.keyCode:0);this.ctrlKey=a.ctrlKey;this.altKey=a.altKey;this.shiftKey=a.shiftKey;this.metaKey=a.metaKey;this.R=ra?a.metaKey:a.ctrlKey;this.m=a;delete this.L;delete this.p};p.preventDefault=function(){P.s.preventDefault.call(this);var a=this.m;if(a.preventDefault)a.preventDefault();else{a.returnValue=k;if(Ba)try{if(a.ctrlKey||a.keyCode>=112&&a.keyCode<=123)a.keyCode=-1}catch(b){}}};
p.d=function(){P.s.d.call(this);this.relatedTarget=this.currentTarget=this.target=this.m=j};function Ca(){}var Da=0;p=Ca.prototype;p.key=0;p.f=k;p.v=k;p.i=function(a,b,c,d,e,f){if(t(a)=="function")this.F=i;else if(a&&a.handleEvent&&t(a.handleEvent)=="function")this.F=k;else throw Error("Invalid listener argument");this.j=a;this.K=b;this.src=c;this.type=d;this.capture=!!e;this.D=f;this.v=k;this.key=++Da;this.f=k};p.handleEvent=function(a){if(this.F)return this.j.call(this.D||this.src,a);return this.j.handleEvent.call(this.j,a)};function Q(a,b){this.G=b;this.c=[];if(a>this.G)throw Error("[goog.structs.SimplePool] Initial cannot be greater than max");for(var c=0;c<a;c++)this.c.push(this.a?this.a():{})}x(Q,N);Q.prototype.a=j;Q.prototype.A=j;function R(a){if(a.c.length)return a.c.pop();return a.a?a.a():{}}function S(a,b){a.c.length<a.G?a.c.push(b):Ea(a,b)}function Ea(a,b){if(a.A)a.A(b);else{var c=t(b);if(c=="object"||c=="array"||c=="function")if(t(b.h)=="function")b.h();else for(var d in b)delete b[d]}}
Q.prototype.d=function(){Q.s.d.call(this);for(var a=this.c;a.length;)Ea(this,a.pop());delete this.c};var Fa;var Ga=(Fa="ScriptEngine"in q&&q.ScriptEngine()=="JScript")?q.ScriptEngineMajorVersion()+"."+q.ScriptEngineMinorVersion()+"."+q.ScriptEngineBuildVersion():"0";var T,U,V,W,Ha,Ia,Ja,Ka,La,Ma,Na;
(function(){function a(){return{b:0,e:0}}function b(){return[]}function c(){function n(s){return g.call(n.src,n.key,s)}return n}function d(){return new Ca}function e(){return new P}var f=Fa&&!(fa(Ga,"5.7")>=0),g;Ia=function(n){g=n};if(f){T=function(){return R(h)};U=function(n){S(h,n)};V=function(){return R(l)};W=function(n){S(l,n)};Ha=function(){return R(m)};Ja=function(){S(m,c())};Ka=function(){return R(u)};La=function(n){S(u,n)};Ma=function(){return R(o)};Na=function(n){S(o,n)};var h=new Q(0,600);
h.a=a;var l=new Q(0,600);l.a=b;var m=new Q(0,600);m.a=c;var u=new Q(0,600);u.a=d;var o=new Q(0,600);o.a=e}else{T=a;U=r;V=b;W=r;Ha=c;Ja=r;Ka=d;La=r;Ma=e;Na=r}})();var X={},Y={},Z={},Oa={};
function $(a,b,c,d,e){if(b)if(t(b)=="array"){for(var f=0;f<b.length;f++)$(a,b[f],c,d,e);return j}else{d=!!d;var g=Y;b in g||(g[b]=T());g=g[b];if(!(d in g)){g[d]=T();g.b++}g=g[d];var h=w(a),l;g.e++;if(g[h]){l=g[h];for(f=0;f<l.length;f++){g=l[f];if(g.j==c&&g.D==e){if(g.f)break;return l[f].key}}}else{l=g[h]=V();g.b++}f=Ha();f.src=a;g=Ka();g.i(c,f,a,b,d,e);c=g.key;f.key=c;l.push(g);X[c]=g;Z[h]||(Z[h]=V());Z[h].push(g);if(a.addEventListener){if(a==q||!a.O)a.addEventListener(b,f,d)}else a.attachEvent(Pa(b),
f);return c}else throw Error("Invalid event type");}function Qa(a){if(!X[a])return k;var b=X[a];if(b.f)return k;var c=b.src,d=b.type,e=b.K,f=b.capture;if(c.removeEventListener){if(c==q||!c.O)c.removeEventListener(d,e,f)}else c.detachEvent&&c.detachEvent(Pa(d),e);c=w(c);e=Y[d][f][c];if(Z[c]){var g=Z[c],h=A(g,b);if(h>=0){y(g.length!=j);z.splice.call(g,h,1)}g.length==0&&delete Z[c]}b.f=i;e.I=i;Ra(d,f,c,e);delete X[a];return i}
function Ra(a,b,c,d){if(!d.k)if(d.I){for(var e=0,f=0;e<d.length;e++)if(d[e].f){var g=d[e].K;g.src=j;Ja(g);La(d[e])}else{if(e!=f)d[f]=d[e];f++}d.length=f;d.I=k;if(f==0){W(d);delete Y[a][b][c];Y[a][b].b--;if(Y[a][b].b==0){U(Y[a][b]);delete Y[a][b];Y[a].b--}if(Y[a].b==0){U(Y[a]);delete Y[a]}}}}
function Sa(a,b,c){var d=0,e=a==j,f=b==j,g=c==j;c=!!c;if(e)ya(Z,function(l){for(var m=l.length-1;m>=0;m--){var u=l[m];if((f||b==u.type)&&(g||c==u.capture)){Qa(u.key);d++}}});else{a=w(a);if(Z[a]){a=Z[a];for(e=a.length-1;e>=0;e--){var h=a[e];if((f||b==h.type)&&(g||c==h.capture)){Qa(h.key);d++}}}}return d}function Pa(a){if(a in Oa)return Oa[a];return Oa[a]="on"+a}
function Ta(a,b,c,d,e){var f=1;b=w(b);if(a[b]){a.e--;a=a[b];if(a.k)a.k++;else a.k=1;try{for(var g=a.length,h=0;h<g;h++){var l=a[h];if(l&&!l.f)f&=Ua(l,e)!==k}}finally{a.k--;Ra(c,d,b,a)}}return Boolean(f)}function Ua(a,b){var c=a.handleEvent(b);a.v&&Qa(a.key);return c}
Ia(function(a,b){if(!X[a])return i;var c=X[a],d=c.type,e=Y;if(!(d in e))return i;e=e[d];var f,g;if(Aa===undefined)Aa=F&&!q.addEventListener;if(Aa){var h;if(!(h=b))a:{h="window.event".split(".");for(var l=q;f=h.shift();)if(l[f])l=l[f];else{h=j;break a}h=l}f=h;h=i in e;l=k in e;if(h){if(f.keyCode<0||f.returnValue!=undefined)return i;a:{var m=k;if(f.keyCode==0)try{f.keyCode=-1;break a}catch(u){m=i}if(m||f.returnValue==undefined)f.returnValue=i}}m=Ma();m.i(f,this);f=i;try{if(h){for(var o=V(),n=m.currentTarget;n;n=
n.parentNode)o.push(n);g=e[i];g.e=g.b;for(var s=o.length-1;!m.p&&s>=0&&g.e;s--){m.currentTarget=o[s];f&=Ta(g,o[s],d,i,m)}if(l){g=e[k];g.e=g.b;for(s=0;!m.p&&s<o.length&&g.e;s++){m.currentTarget=o[s];f&=Ta(g,o[s],d,k,m)}}}else f=Ua(c,m)}finally{if(o){o.length=0;W(o)}m.h();Na(m)}return f}d=new P(b,this);try{f=Ua(c,d)}finally{d.h()}return f});function Va(a){this.o=j;this.H=200;if(a){this.o=M(a);this.n()}}Va.prototype.show=function(a){if(!(A(J(a.parentNode),"wide")>=0)){var b=a.parentNode.offsetWidth;a.style.width=b>this.H?b+"px":this.H+"px"}a.style.display="block"};
Va.prototype.n=function(){B(this.o.getElementsByTagName("li"),function(a){$(a,"mouseover",function(){K(a,"gweb-navmenu-hover")});$(a,"mouseout",function(){L(a,"gweb-navmenu-hover")})},this);this.S=this.o.getElementsByTagName("ul");B(this.S,ca(function(a){K(a.parentNode.getElementsByTagName("a")[0],"gweb-navmenu-drop");$(a.parentNode,"mouseover",function(){this.show(a)},k,this);$(a.parentNode,"mouseout",function(){a.style.display="none"},k,this)},this))};
function Wa(a){this.g=a;this.u=M(this.g.windowId);this.J=M(this.g.openId);this.w=M(this.g.closeId);this.Q=M(this.g.domainInputId);if(this.u&&this.J&&this.w){$(this.J,"click",function(b){b.preventDefault();this.open()},k,this);$(this.w,"click",function(b){b.preventDefault();this.close()},k,this)}}Wa.prototype.open=function(){this.u.style.display="block";this.Q.focus()};Wa.prototype.close=function(){this.u.style.display="none"};
function Xa(a){this.T=[];this.V=a;this.l=a.getElementsByTagName("li");this.T=a.getElementsByTagName("a");this.r=this.q=j;Ya(this);a=location.hash.substring(1).split("&")[0];this.M=!a.match("=")?a:j;Za(this)}function Za(a){if(a.l.length>=1)a.M?B(a.l,function(b){this.M==b.getElementsByTagName("a")[0].href.replace(/^.*?#/,"")&&$a(this,b)},a):$a(a,a.l[0])}
function Ya(a){B(a.l,function(b){var c=b.getElementsByTagName("a")[0].href.replace(/^.*?#/,"");M(c).id=c+"_window";ab(this,b);$(b,"click",function(d){d.preventDefault();$a(this,b)},k,this)},a)}function bb(a,b){var c=b.getElementsByTagName("a")[0].href.replace(/^.*?#/,"");return M(c+"_window")}function ab(a,b){var c=bb(a,b);if(c)c.style.display="none"}function $a(a,b){if(a.r)a.r.style.display="none";a.q&&L(a.q,"highlight");var c=bb(a,b);if(c)c.style.display="block";K(b,"highlight");a.r=c;a.q=b}
function cb(a,b){var c=[],d=[];B(document.getElementsByTagName(a),function(e){if(A(J(e),b)>=0){d.push(e);c.push(new Xa(e))}});return c}function db(a,b){this.N=a;this.t=this.N.getElementsByTagName("tbody")[0];this.rows=this.t.getElementsByTagName("tr");this.P=b||0;this.z=this.rows[this.P];this.n()}
db.prototype.C=function(a){if(a.type=="mouseover"){a=a.target.parentNode;if(a.nodeName!="TR")a=eb(a);fb(this,a)}else if(a.type=="mouseout"&&a.relatedTarget.nodeName!="TH"&&a.relatedTarget.nodeName!="TD"&&a.relatedTarget.nodeName!="IMG"||eb(a.relatedTarget).parentNode.nodeName=="THEAD")fb(this,this.z)};function eb(a){for(;a.nodeName!="TR";)a=a.parentNode;return a}function fb(a,b){B(a.rows,function(c){L(c,"highlight")});K(b,"highlight")}
db.prototype.n=function(){$(this.t,"mouseover",this.C,k,this);$(this.t,"mouseout",this.C,k,this);$(document.body,"unload",function(){Sa()},k,this);fb(this,this.z)};Va.prototype.show=function(a){a.style.display="block"};cb("ul","nav");cb("ul","nav-feature");new Wa({windowId:"sign-in",domainInputId:"f-domain",openId:"sign-in-open",closeId:"sign-in-close"});(function(a){var b=[];a=za(document,j,a,void 0);B(a,function(c){b.push(new db(c))});return b})("feature");
(function(){if(M("nav")){new Va("nav");var a=za(document,j,"noscript",void 0);B(a,function(b){L(b,"noscript")})}})();

})();