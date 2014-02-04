var mbjwplayer;
(function($) {
	mbjwplayer = function(inside, id, config) {
		var ctx = $(inside).getParent(".media_container");
		if(!ctx.getParent("#mbImage")) return;
		id += $uid({});
		ctx.set("id", id);
		jwplayer(id).setup(config);
	};
})(document.id);