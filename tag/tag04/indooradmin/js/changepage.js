function changePage()
{
	var sep = (arguments[0].indexOf('?') != -1) ? '&' : '?';
	location.href = arguments[0] + sep + 'page=' + arguments[1];
}