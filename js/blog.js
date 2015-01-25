(function () {
	var newId = null;
	function fixForm($form, map) {
		//get ID
		var oldId = $form.children('.wd-form-id')[0].name;
		if (newId === null) {
			newId = 10000;
			while ($('.wd-form[name="' + newId + '"]').length !== 0) {
				newId += 10000;
			}
		}
		map[oldId] = newId;

		var $children = $form.children("[name='" + oldId + ":*']");
		//inputs
		$.each($children, function (index, el) {
			var parts = el.name.split(":");
			el.name = map[oldId] + ":" + parts[1];
		});
		//form relations
		var $relations = $form.children(".wd-form-relation");
		$.each($relations, function (index, el) {
			var parts = el.name.split(":");
			var newName = '';
			if (parts[0] in map) {
				newName += map[parts[0]];
			} else {
				newName += parts[0];
			}
			newName += ":with:";
			if (parts[2] in map) {
				newName += map[parts[2]];
			} else {
				newName += parts[2];
			}
			el.name = newName;
		});
		//subforms
		var $subforms = $form.childen(".wd-subform");
		$.each($subforms, function (index, el) {
			fixForm($(el), map);
		});
	}

	$(document).on('.wd-multiple', 'click', function (e) {
		var $target = $(e.target);
		var $form = $target.parent();
		fixForm($form, {});
	});
})();