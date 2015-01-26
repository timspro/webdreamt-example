(function () {
	var newId = null;
	function fixForm($form, map) {
		//get ID
		var formId = $form.children('.wd-form-id')[0];
		var oldId = formId.name;
		if (newId === null) {
			newId = 10000;
			while ($('.wd-form > [name="' + newId + '"]').length !== 0) {
				newId += 10000;
			}
		}
		formId.name = newId;
		map[oldId] = newId;

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
		//inputs
		var $children = $form.children().children().filter("[name^='" + oldId + ":']");
		$.each($children, function (index, el) {
			var parts = el.name.split(":");
			el.name = map[oldId] + ":" + parts[1];
		});
		//for
		var $children = $form.children().children().filter("[for^='" + oldId + ":']");
		$.each($children, function (index, el) {
			var parts = el.getAttribute('for').split(":");
			el.setAttribute('for', map[oldId] + ":" + parts[1]);
		});
		//subforms
		var $subforms = $form.children(".wd-subform");
		$.each($subforms, function (index, el) {
			newId++;
			fixForm($(el), map);
		});
	}

	$(document).on('click', '.wd-multiple', function (e) {
		var $target = $(e.target);
		var $form = $target.parent();
		var $newForm = $form.clone(true);
		$newForm.insertAfter($form);
		fixForm($newForm, {});
		$target.remove();
	});

	$(document).on('click', '.wd-form-submit', function (e) {
		$(e.target).parents('form')[0].submit();
	});
})();