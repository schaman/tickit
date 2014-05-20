// Backbone.BabySitter
// -------------------
// v0.1.0
//
// Copyright (c)2014 Derick Bailey, Muted Solutions, LLC.
// Distributed under MIT license
//
// http://github.com/marionettejs/backbone.babysitter

(function(i,e){if("object"==typeof exports){var t=require("underscore"),n=require("backbone");module.exports=e(t,n)}else"function"==typeof define&&define.amd&&define(["underscore","backbone"],e)})(this,function(i,e){"option strict";return e.ChildViewContainer=function(i,e){var t=function(i){this._views={},this._indexByModel={},this._indexByCustom={},this._updateLength(),e.each(i,this.add,this)};e.extend(t.prototype,{add:function(i,e){var t=i.cid;return this._views[t]=i,i.model&&(this._indexByModel[i.model.cid]=t),e&&(this._indexByCustom[e]=t),this._updateLength(),this},findByModel:function(i){return this.findByModelCid(i.cid)},findByModelCid:function(i){var e=this._indexByModel[i];return this.findByCid(e)},findByCustom:function(i){var e=this._indexByCustom[i];return this.findByCid(e)},findByIndex:function(i){return e.values(this._views)[i]},findByCid:function(i){return this._views[i]},remove:function(i){var t=i.cid;return i.model&&delete this._indexByModel[i.model.cid],e.any(this._indexByCustom,function(i,e){return i===t?(delete this._indexByCustom[e],!0):void 0},this),delete this._views[t],this._updateLength(),this},call:function(i){this.apply(i,e.tail(arguments))},apply:function(i,t){e.each(this._views,function(n){e.isFunction(n[i])&&n[i].apply(n,t||[])})},_updateLength:function(){this.length=e.size(this._views)}});var n=["forEach","each","map","find","detect","filter","select","reject","every","all","some","any","include","contains","invoke","toArray","first","initial","rest","last","without","isEmpty","pluck"];return e.each(n,function(i){t.prototype[i]=function(){var t=e.values(this._views),n=[t].concat(e.toArray(arguments));return e[i].apply(e,n)}}),t}(e,i),e.ChildViewContainer});