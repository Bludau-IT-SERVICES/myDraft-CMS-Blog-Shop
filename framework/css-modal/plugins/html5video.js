/*
 * CSS Modal Plugin for HTML5 Video (play/pause)
 * Author: Anselm Hannemann
 * Date: 2014-02-04
 */

(function (global) {

	'use strict';

	var CSSModal = global.CSSModal;

	// If CSS Modal is still undefined, throw an error
	if (!CSSModal) {
		throw new Error('Error: CSSModal is not loaded.');
	}

	var videos;

	// Enables Auto-Play when calling modal
	CSSModal.on('cssmodal:show', document, function (event) {
		// Fetch all video elements in active modal
		videos = CSSModal.activeElement.querySelectorAll('video');

		if (videos.length > 0) {
			// Play first video in modal
			videos[0].play();
		}
	});

	// If modal is closed, pause all videos
	CSSModal.on('cssmodal:hide', document, function (event) {
		var i = 0;

		if (videos.length > 0) {
			// Pause all videos in active modal
			for (; i < videos.length; i++) {
				videos[i].pause();
			}
		}
	});

}(window));
