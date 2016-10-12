$(document).ready(function() {
	var $citations = $('<div class="citations"><h2>References</h2><ol></ol></div>');
	var $citation_list = $citations.find('ol');

	$('a.citation').each(function(i, link) {
		$citation_list.append(
			'<li><cite>' + $(link).text() + '</cite>: ' + link.href + '</li>'
		);
		$('<sup class="ref"> [' + (i + 1) + ']</sup>').insertAfter(link);
	});

	if ($citation_list.children().length) {
		$('<footer></footer>').append($citations).appendTo('body');
	}
});
