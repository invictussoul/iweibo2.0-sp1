$(function() {
	var names = '';
	$("a[data-group=1]").each(function(index) {
		var field = $(this);
		names += field.attr('data-name') + ',';
	});
	names = names.substring(0, names.length - 1);
	$.post(iwbRoot + "/friend/checkf/type/1", {'name': names}, function(row) {
		if (row.ret == 0) {
			$.each(row.data, function(key, value) {
				var field = $("a[data-group=1][data-name=" + key + "]");
				if (value) {
					field.filter('[data-styleid=1]').addClass('unfollow');
					field.filter('[data-styleid=0]').addClass('unfollowbtn');
					field.attr('data-type', '0');
					field.attr('title', '取消收听');
				} else {
					field.filter('[data-styleid=1]').removeClass('unfollow'); 
					field.filter('[data-styleid=0]').removeClass('unfollowbtn');
					field.attr('data-type', '1');
					field.attr('title', '收听');
				}
			});
		}
	}, "json");
});