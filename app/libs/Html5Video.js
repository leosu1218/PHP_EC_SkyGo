/**
*   HTML5 video tools.
*
*/

define([], function(){ 

	var Module = function() {

		var self = this;
		self.state = "initail";
		self.eventHandle = function(){};

		/**
		*	Trigger video event handle
		*
		*/
		this.triggerVideoEvent = function(e) {			
			self.state = e.type;
			self.eventHandle(e.type, video, e);
		}

		/**
		*	Register event listener
		*/
		this.registerEvents = function() {	

			var mediaEvents = new Array();
			mediaEvents["loadstart"] = 0;
			mediaEvents["progress"] = 0;
			mediaEvents["suspend"] = 0;
			mediaEvents["abort"] = 0;
			mediaEvents["error"] = 0;
			mediaEvents["emptied"] = 0;
			mediaEvents["stalled"] = 0;
			mediaEvents["loadedmetadata"] = 0;
			mediaEvents["loadeddata"] = 0;
			mediaEvents["canplay"] = 0;
			mediaEvents["canplaythrough"] = 0;
			mediaEvents["playing"] = 0;
			mediaEvents["waiting"] = 0;
			mediaEvents["seeking"] = 0;
			mediaEvents["seeked"] = 0;
			mediaEvents["ended"] = 0;
			mediaEvents["durationchange"] = 0;
			mediaEvents["timeupdate"] = 0;
			mediaEvents["play"] = 0;
			mediaEvents["pause"] = 0;
			mediaEvents["ratechange"] = 0;
			mediaEvents["resize"] = 0;
			mediaEvents["volumechange"] = 0;

			for (var key in mediaEvents) {
				window.video.addEventListener(key, self.triggerVideoEvent, false);				
			}		
		}


		/**
		*	Get video object instance.
		*
		*	@param id string DOM id.
		*/
		this.getInstance = function(id) {
			if(window.video) {
				return window.video;
			}
			else {
				window.video = document.getElementsByTagName('video')[0];
				self.registerEvents();		
				return window.video;				
			}
		}

		/**
		*	Set handler for video evnets
		*
		*	@param handle function Handling function(type, video, event)
		*/
		this.onEvent = function(handle) {
			self.eventHandle = handle
		}

		this.play = function() {
			window.video.play();
		}
		        	
		this.pause = function() {
			window.video.pause();
		}       			        	
	}

	return new Module();
});


