/*
  backbone-pageable
  http://github.com/wyuenho/backbone-pageable

  Copyright (c) 2012 Jimmy Yuen Ho Wong
  Licensed under the MIT @license.
*/
(function(e){if("object"==typeof exports)module.exports=e(require("underscore"),require("backbone"));else if("function"==typeof define&&define.amd)define(["underscore","backbone"],e);else if("undefined"!=typeof _&&"undefined"!=typeof Backbone){var t=Backbone.PageableCollection,r=Backbone.PageableCollection=e(_,Backbone);Backbone.PageableCollection.noConflict=function(){return Backbone.PageableCollection=t,r}}})(function(e,t){"use strict";function r(t,r){if(t*=1,!e.isNumber(t)||e.isNaN(t)||!e.isFinite(t)||~~t!==t)throw new TypeError("`"+r+"` must be a finite integer");return t}function i(){var t=arguments[0],r=e.toArray(arguments).slice(1),i=t.comparator;t.comparator=null;try{t.reset.apply(t,r)}finally{t.comparator=i,i&&t.sort()}return t}var a=/[\s'"]/g,s=/[<>\s'"]/g,o=t.Collection.extend({state:{firstPage:1,lastPage:null,currentPage:null,pageSize:25,totalPages:null,totalRecords:null,sortKey:null,order:-1},mode:"server",queryParams:{currentPage:"page",pageSize:"per_page",totalPages:"total_pages",totalRecords:"total_entries",sortKey:"sort_by",order:"order",directions:{"-1":"asc",1:"desc"}},initialize:function(t,r){r=r||{};var i=this.mode=r.mode||this.mode||n.mode,a=e.extend({},n.queryParams,this.queryParams,r.queryParams||{});a.directions=e.extend({},n.queryParams.directions,this.queryParams.directions,a.directions||{}),this.queryParams=a;var s=this.state=e.extend({},n.state,this.state,r.state||{});if(s.currentPage=null==s.currentPage?s.firstPage:s.currentPage,this.switchMode(i,e.extend({fetch:!1,resetState:!1,models:t},r)),"client"==i){null!=s.totalRecords||e.isEmpty(t)||(s.totalRecords=t.length),this.state=this._checkState(s);var o=r.comparator;if(s.sortKey&&!o&&(o=this.makeComparator(s.sortKey,s.order,r)),o)if(r.full){var l=this.fullCollection;l.comparator=o,l.sort()}else this.comparator=o;t&&!e.isEmpty(t)&&(this.getPage(s.currentPage),t.splice.apply(t,[0,t.length].concat(this.models)))}else this.state=this._checkState(s);this._initState=e.clone(this.state)},_makeFullCollection:function(r,i){var a,s,o,n=["url","model","sync","comparator"],l=this.constructor.prototype,c={};for(a=0,s=n.length;s>a;a++)o=n[a],e.isUndefined(l[o])||(c[o]=l[o]);var h=new(t.Collection.extend(c))(r,i),u=this;for(a=0,s=n.length;s>a;a++)o=n[a],u[o]!==l[o]&&(h[o]=o);return h._onFullCollectionEvent=this._onFullCollectionEvent,h.on("all",h._onFullCollectionEvent,h),h.pageableCollection=this,h},_onPageableCollectionEvent:function(t,r,a,s){var o=this.fullCollection;this.off("all",this._onPageableCollectionEvent,this),o.off("all",o._onFullCollectionEvent,o);var n=e.clone(this.state),l=n.firstPage,c=0===l?n.currentPage:n.currentPage-1,h=n.pageSize,u=c*h,f=u+h;if("add"==t){var g=u+this.indexOf(r);o.add(r,e.extend({},s||{},{at:g})),this.pop(),n.totalRecords++}if("remove"==t){var d=o.at(f);if(d&&this.push(d),o.remove(r),--n.totalRecords){var P=n.totalPages=Math.ceil(n.totalRecords/h);n.lastPage=0===l?P-1:P,n.currentPage>P&&(n.currentPage=n.lastPage)}else n.totalRecords=null,n.totalPages=null}if("reset"==t){var m=o.models.slice(0,u),p=o.models.slice(u+this.models.length);i(o,m.concat(this.models).concat(p)),i(this,o.models.slice(u,f),{silent:!0}),n.totalRecords=o.models.length}this.state=this._checkState(n),o.on("all",o._onFullCollectionEvent,o),this.on("all",this._onPageableCollectionEvent,this)},_onFullCollectionEvent:function(t,r,a,s){var o=this.pageableCollection;this.off("all",this._onFullCollectionEvent,this),o.off("all",o._onPageableCollectionEvent,o);var n=e.clone(o.state),l=n.firstPage,c=0===l?n.currentPage:n.currentPage-1,h=n.pageSize,u=c*h,f=u+h;if("add"==t){var g=this.indexOf(r);if(g>=u&&f>g){var d=g-u;o.pop(),o.add(r,e.extend({},s||{},{at:d}))}n.totalRecords++}if("remove"==t){if(s.index>=u&&f>s.index){o.remove(r);var P=this.at(c*(h+s.index));P&&o.push(P)}if(--n.totalRecords){var m=n.totalPages=Math.ceil(n.totalRecords/h);n.lastPage=0===l?m-1:m,n.currentPage>m&&(n.currentPage=n.lastPage)}else n.totalRecords=null,n.totalPages=null}("reset"==t||"sort"==t)&&(s=a,i(o,this.models.slice(u,f),s),n.totalRecords=this.models.length),o.state=o._checkState(n),o.on("all",o._onPageableCollectionEvent,o),this.on("all",this._onFullCollectionEvent,this)},_checkState:function(e){var t=e.totalRecords,i=e.pageSize,a=e.currentPage,s=e.firstPage,o=e.totalPages;if(null!=t&&null!=i&&null!=a&&null!=s){if(t=r(t,"totalRecords"),i=r(i,"pageSize"),a=r(a,"currentPage"),s=r(s,"firstPage"),1>i)throw new RangeError("`pageSize` must be >= 1");if(o=e.totalPages=Math.ceil(t/i),0===s){if(s>a||a>=o)throw new RangeError("`currentPage` must be firstPage <= currentPage < totalPages if 0-based.")}else{if(1!==s)throw new RangeError("`firstPage must be 0 or 1`");if(s>a||a>o)throw new RangeError("`currentPage` must be firstPage <= currentPage <= totalPages if 1-based.")}e.lastPage=0===s?o-1:o}return e},setPageSize:function(t,i){return t=r(t,"pageSize"),i=i||{},this.state=this._checkState(e.extend({},this.state,{pageSize:t,totalPages:"client"==this.mode?Math.ceil(this.fullCollection.size()/t):Math.ceil(this.state.totalRecords/t)})),this.getPage(this.state.currentPage,i)},switchMode:function(t,r){if(!e.contains(["server","client","infinite"],t))throw new TypeError('`mode` must be one of "server", "client" or "infinite"');r=r||{fetch:!0,resetState:!0};var i;return this.state=r.resetState?e.clone(this._initState):this._checkState(e.extend({},this.state)),"client"==t?(this.links&&delete this.links,i=this.fullCollection=this._makeFullCollection(r.models||[]),i.comparator=this._fullComparator,this.on("all",this._onPageableCollectionEvent,this)):this.fullCollection&&(i=this.fullCollection,i.off("all",i._onFullCollectionEvent,i),this.off("all",this._onPageableCollectionEvent,this),this._fullComparator=i.comparator,delete this.fullCollection,"server"==t&&this.links&&delete this.links),"infinite"==t&&(this.links={first:this.url,next:this.url}),r.fetch?this.fetch(e.omit(r,["fetch","resetState"])):this},getFirstPage:function(e){return this.getPage("first",e)},getPreviousPage:function(e){return this.getPage("prev",e)},getNextPage:function(e){return this.getPage("next",e)},getLastPage:function(e){return this.getPage("last",e)},getPage:function(t,i){i=i||{fetch:!1};var a=this.state,s=a.firstPage,o=a.currentPage,n=a.lastPage,l=a.pageSize,c=t;switch(t){case"first":c=s;break;case"prev":c=o-1;break;case"next":c=o+1;break;case"last":c=n;break;default:c=r(t,"index")}if(this.state=this._checkState(e.extend({},a,{currentPage:c})),"client"==this.mode&&!i.fetch){var h=(0===s?c:c-1)*l,u=h+l;return this.reset(this.fullCollection.models.slice(h,u),e.omit(i,"fetch"))}if("infinite"==this.mode){var f=this.links;if(!f[t])throw new TypeError("No link found for '"+t+"'");var g=this;return this.fetch(e.extend({url:this.links[t]},i)).done(function(e,t,r){g.links=g.parseLinks(e,r)})}return this.fetch(i,"fetch")},parseLinks:function(t,r){var i=r.getResponseHeader("Link"),o=["first","prev","previous","next","last"],n={};return e.each(i.split(","),function(t){var r=t.split(";"),i=r[0].replace(s,""),l=r.slice(1);e.each(l,function(t){var r=t.split("="),s=r[0].replace(a,""),l=r[1].replace(a,"");"rel"==s&&e.contains(o,l)&&("previous"==l?n.prev=i:n[l]=i)})}),n},parse:function(t){if(!e.isArray(t))return new TypeError("The server response must be an array");if(2===t.length&&e.isObject(t[0])&&e.isArray(t[1])){var r=this.queryParams,i=e.clone(this.state),a=t[0];return e.each(e.pairs(e.omit(r,"directions")),function(e){var t=e[0],r=e[1];i[t]=a[r]}),a.order&&(i.order=1*e.invert(r.directions)[a.order]),this.state=this._checkState(i),t[1]}return t},fetch:function(r){r=r||{};var i=r.data=r.data||{};if("infinite"==this.mode&&!r.url)return this.getNextPage();var a,s,o,l,c=this._checkState(this.state),h="client"==this.mode?e.pick(this.queryParams,"sortKey","order"):e.omit(e.pick(this.queryParams,e.keys(n.queryParams)),"directions"),u=e.pairs(h),f=e.clone(this);for(a=0;u.length>a;a++)s=u[a],o=s[0],l=s[1],l=e.isFunction(l)?l.call(f):l,null!=c[o]&&null!=l&&(i[l]=c[o]);c.sortKey&&c.order?i[h.order]=this.queryParams.directions[c.order+""]:c.sortKey||delete i[h.order];var g=e.pairs(e.omit(this.queryParams,e.keys(n.queryParams)));for(a=0;g.length>a;a++)s=g[a],l=s[1],l=e.isFunction(l)?l.call(f):l,i[s[0]]=l;var d=t.Collection.prototype;if("client"==this.mode){var P=this,m=r.success;return r.success=function(e,t,i){i=i||{},i.silent=r.silent,P.fullCollection.reset(e.models,i),m&&m(e,t,i)},d.fetch.call(this,e.extend({},r,{silent:!0}))}return d.fetch.call(this,r)},makeComparator:function(e,t){if(e=null==e?this.state.sortKey:e,t=null==t?this.state.order:t,e&&t){var r=function(r,i){var a,s=r.get(e),o=i.get(e);return 1===t&&(a=s,s=o,o=a),s===o?0:o>s?-1:1};return r}}}),n=o.prototype;return o});