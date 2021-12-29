jQuery(document).ready(function ($) {
    var feed = new Instafeed({
    	get: 'tagged',
    	tagName: 'scodb',
    	clientId: 'a8e1384f0eb842f6a8d2f1314f02a0ee',
    	limit: 34,
    	sortBy: 'most-recent',
    	template: '<a href="{{link}}" target="_blank"><img data-toggle="tooltip_instafeed" title="{{caption}}" src="{{image}}" /></a>',
    	after: function(){
    		$('[data-toggle=tooltip_instafeed]').tooltip();
    	}
    });
    feed.run();
});
