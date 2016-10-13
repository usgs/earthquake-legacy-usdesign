designval_parse = function(_str) {
	_str = _str.trim();
	var idx = _str.indexOf('g') - 1;
	_str = _str.substr(0, idx);
	return _str;
};

designval_asc = function(_x, _y) {
	_x = designval_parse(_x);
	_y = designval_parse(_y);
	return default_asc(_x, _y);
};

designval_desc = function(_x, _y) {
	_x = designval_parse(_x);
	_y = designval_parse(_y);
	return default_desc(_x, _y);
};

default_asc = function(_x, _y) {
	return ((_x < _y) ? -1 : ((_x > _y) ? 1 : 0 ));
};

default_desc = function(_x, _y) {
	return ((_x < _y) ? 1 : ((_x > _y) ? -1 : 0 ));
};

init_page = function(_event) {
	$('#regiondata tbody td').click(function(_event) {
		window.location = $('.location', $(this)).attr('href');
	});

	$('#regiondata').addClass('activetable');
};

$(document).ready(init_page);
