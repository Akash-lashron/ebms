/** AnimTree.js by Garrett Smith
*
*  URL:http://dhtmlkitchen.com/
*  email:admin@dhtmlkitchen.com
*
*  Usage: see http://dhtmlkitchen.com/
*
* [11/29/02]
* replaced TreeGlobals.browser with Browser in utils.js
*/
TreeGlobals={ini:false
};function toggleMenu(el){if(Browser.id.OP5||Browser.id.NS4)return;var l=TF.gL(el);if(l.m.bk)return;if(l.m.c.mto!=null)return;if(l.isDp){if(TreeParams.OPEN_MULTIPLE_MENUS||l.m.c.am==l.m){TF.cM(l.m);l.m.c.am=null;}}
else{if(l.i!=null)l.i.src=TreeParams.OPEN_MENU_ICON;l.m.c.mto=l.m;if(TreeParams.OPEN_MULTIPLE_MENUS||l.m.c.am==null){TF.oM(l.m);}
else{TF.cM(l.m.c.am);if(TreeParams.OPEN_WHILE_CLOSING)TF.oM(l.m);else
l.m.c.am.mic=l.m;}l.m.c.am=l.m;}}
function activateMenu(id){if(!window.toggleMenu)return;var b=document.getElementById(id);if(!b)return;var p=TUt.fAWC(b,"menu");if(p!=null){var bId=p.id.substring(0,p.id.indexOf("Menu"));activateMenu(bId);}
var l=TF.gL(TUt.gEWC(b,"*","buttonlabel")[0]);if(!l.isDp)toggleMenu(l.el);}
function buttonOver(el){window.status=el.parentNode.id;l=TF.gL(el);if(l.el.className.hasToken("labelHover"))return;l.el.className+=" labelHover";}
function buttonOff(l){window.status=window.defaultStatus;TUt.rc(l,"labelHover");}
if(typeof document.getElementsByTagName=="undefined"||Browser.id.OP5)buttonOver=buttonOff=function(){};Button=function(el,cat){this.el=el;this.cat=cat;this.m=new Menu(document.getElementById(cat+"Menu"),this);var ics=el.getElementsByTagName("img");this.i=(ics.length>0)?ics[0]:null;this.isI=false;if(el.tagName=="IMG"){this.isI=true;this.i=el;}
this.isDp=false;};Menu=function(el,l){this.ob=l;this.id=l.cat;this.el=el;this.its=TUt.gCNWC(this.el,"div","menuNode");this.ai=TUt.gEWC(this.el,"div","menuNode");this.cur=0;this.bk=false;this._r=null;this.c=this.getC();this.mto=null;this.am=null;this.mic=null;};Menu.prototype={op:function(){this.ito[this.cur].style.display="block";if(++this.cur==this.ito.length)this.pae("block");},cl:function(){this.itc[this.cur].style.display="none";if(++this.cur==this.itc.length)this.pae("none");},pae:function(sDisplay){this.el.style.display=sDisplay;this.pat=clearInterval(this.pat);if(sDisplay=='block')this.c.mto=null;else{TF.sdl(this.ob);if(!TreeGlobals.OPEN_WHILE_CLOSING&&this.mic!=null)TF.oM(this.mic);this.mic=null;}
setTimeout("TUt.rpt(TreeList[\""+this._r.id+"\"])",50);this.bk=false;},rt:function(){if(this._r==null){var rt=TUt.fAWC(this.el,"AnimTree");if(rt==null)rt=document.body;if(!rt.id)rt.id="AnimTree_"+Math.round(Math.random()* 100);if(TreeList[rt.id]!=null){this._r=TreeList[rt.id];this._r.ms[this.id]=this;}
else
this._r=new Tree(rt,this);}return this._r;},getC:function(){var rt=this.rt();var p=TUt.fAWC(this.el,"menu");if(p!=null)return rt.ms[p.id.replace(/Menu$/,"")];return rt;}
};Tree=function(el,m){this.el=el;this.am=null;this.ms[m.id]=m;this.mto=null;this.id=el.id;TreeList[this.id]=this;};Tree.prototype.ms=new Object();TreeList={};TF={gL:function(el){var id=TUt.fAWC(el,"button").id;var b;for(var t in TreeList)if(TreeList[t].ms[id]!=null)return TreeList[t].ms[id].ob;return new Button(el,id);},initMenu:function(){if(document.getElementById&&!Browser.id.OP5&&!TreeGlobals.ini){document.writeln("<style type='text/css'>\n","/*<![CDATA[ */\n",".menu,.menuNode{display:none;}\n","/* ]]>*/\n","<"+"/style>");TreeGlobals.ini=true;}
},oM:function(m){m.bk=true;m.cur=0;m.ito=new Array();m.el.style.display="block";if(m.itc&&m.itc.length>m.its.length)m.ito=m.itc.reverse();else
m.ito=m.its;if(!m.ob.isI)m.ob.el.className+=" labelDown";if(m.ito.length==0)m.ito[0]=m.el;m.pat=setInterval("TreeList."+m.rt().id+".ms."+m.id+".op()",TreeParams.TIME_DELAY);m.ob.isDp=true;},cM:function(m){m.bk=true;m.cur=0;m.itc=new Array();for(var i=m.ai.length-1,j=0;i>0;i--)if(m.ai[i].style.display=="block")m.itc[j++]=m.ai[i];m.itc[m.itc.length]=m.el;m.pat=setInterval("TreeList."+m.rt().id+".ms."+m.id+".cl()",TreeParams.TIME_DELAY);m.ob.isDp=false;},sdl:function(b){if(b.isI)return void(b.i.src=TreeParams.CLOSED_MENU_ICON);TUt.rc(b.el,"labelHover");TUt.rc(b.el,"labelDown");if(b.i!=null)b.i.src=TreeParams.CLOSED_MENU_ICON;}
};TF.initMenu();TUt={gCNWC:function(p,t,k){var rc=[];var c=p.childNodes;for(var i=0,j=0;i<c.length;i++){if(!c[i].className||c[i].tagName.toUpperCase()!=t.toUpperCase())continue;if(c[i].className.hasToken(k))rc[j++]=c[i];}
return rc;},gEWC:function(p,t,k){var c;var rc=[];if(p.all&&t=="*")c=p.all;else c=p.getElementsByTagName(t);for(var i=0,j=0;i<c.length;i++){if(c[i].className!=null&&c[i].className.hasToken(k))rc[j++]=c[i];}
return rc;},fAWC:function(el,k){for(var p=el.parentNode;p!=null;){if(p.className!=null&&p.className.hasToken(k))return p;p=p.parentNode;}
return null;},rc:function(el,k){var nc="";var l=el.className.split(" ");for(var i=0;i<l.length;i++)if(l[i]!=k)nc+=l[i]+" ";el.className=nc.normalize();},rpt:function(t){if(!t.am)return;t.am.el.style.visibility="hidden";t.am.el.style.visibility="visible";}
};String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"");};String.prototype.normalize=function(){return this.trim().replace(/\s\s+/g," ");};String.prototype.hasToken=function(s){return new RegExp(" "+s+" |^"+s+" |^"+s+"$| "+s+"$", "\g").test(this);};