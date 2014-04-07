/***
A Good School Is aggregates interviews with parents, teachers, community members and students
about what makes a good school. We store interviews in SoundCloud and share them on Tumblr,
as well as on the main site.

This is the code that pulls in recent interviews and renders them in the site header.

Dependencies:

 - jQuery (WP)
 - SoundCloud JS SDK (http://connect.soundcloud.com/sdk.js)
 - SoundCloud Widget JS (https://w.soundcloud.com/player/api.js)
***/

(function(SC, $) {

    // bail early if credentials aren't present
    if (!window.SOUNDCLOUD_CLIENT_ID || !window.GOOD_SCHOOLS_PLAYLIST_ID) { return; }
    
    // initialize soundcloud
    SC.initialize({ client_id: SOUNDCLOUD_CLIENT_ID });

    SC.get('/playlists/' + GOOD_SCHOOLS_PLAYLIST_ID, { limit: 4 }, function(playlist) {
        window.playlist = playlist;

        // set a target
        var target = $('#agoodschoolis');

        // create iframes, then render widgets
        playlist.tracks.forEach(function(track, i) {
        	// <iframe width="100%" height="450" scrolling="no" frameborder="no" 
        	// src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/142369876&amp;auto_play=false&amp;hide_related=false&amp;visual=true"></iframe>
        	var iframe = document.createElement('iframe');

        	$(iframe)
        		.attr('frameborder', 0)
        		.attr('scrolling', 'no')
        		.attr('id', 'agoodschoolis-' + i)
        		.addClass('sc-widget agoodschoolis')
        		.addClass('col-md-3 col-sm-3 col-xs-6')
        		.height(200)
        		.appendTo(target);

        	set_iframe_src(iframe, track);
        });
    });

    function set_iframe_src(iframe, track) {
    	// set src based on track and options
    	var options = {
    		buying: false,
    		download: false,
    		show_comments: false,
    		show_user: false,
    		show_artwork: true,
    		visual: true,
    		url: track.uri
    	}

    	var src = 'https://w.soundcloud.com/player/?';

    	iframe.setAttribute('src', src + $.param(options));
    }

    /***
	Running this with empty iframes collapses all widgets into one.
	Save for later if we want to swap out or otherwise control widgets.
    ***/
    function render_widget(iframe, track) {
    	// create the widget in an iframe
    	var widget = SC.Widget(iframe);

    	// when it's ready, add sound
    	widget.bind(SC.Widget.Events.READY, function() {
    		widget.load(track.uri, {
    			buying: false,
    			download: false,
    			show_comments: false,
    			show_user: false
    		});
    	})
    }

})(window.SC, window.jQuery);