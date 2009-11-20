/*
 * date:	2003-01-23
 * info:	http://inspire.server101.com/js/xc/
 */

var xcNode = [];

function xcSet(m, c) {
if (document.getElementById && document.createElement) {
	m = document.getElementById(m).getElementsByTagName('ul');
	var d, p, x, h, i, j;
	for (i = 0; i < m.length; i++) {
		if (d = m[i].getAttribute('id')) {
			xcCtrl(d, c, 'x', '[+]', 'Show', m[i].getAttribute('title')+' (expand menu)');
			x = xcCtrl(d, c, 'c', '[-]', 'Hide', m[i].getAttribute('title')+' (collapse menu)');

			p = m[i].parentNode;
			if (h = !p.className) {
				j = 2;
				while ((h = !(d == arguments[j])) && (j++ < arguments.length));
				if (h) {
					m[i].style.display = 'none';
					x = xcNode[d+'x'];
				}
			}

			p.className = c;
			p.insertBefore(x, p.firstChild);
		}
	}
}}


function xcShow(m) {
	xcXC(m, 'block', m+'c', m+'x');
}


function xcHide(m) {
	xcXC(m, 'none', m+'x', m+'c');
}


function xcXC(e, d, s, h) {
	e = document.getElementById(e);
	e.style.display = d;
	e.parentNode.replaceChild(xcNode[s], xcNode[h]);
	xcNode[s].firstChild.focus();
}


function xcCtrl(m, c, s, v, f, t) {
	var a = document.createElement('a');
	a.setAttribute('href', 'javascript:xc'+f+'(\''+m+'\');');
	a.setAttribute('title', t);
	a.appendChild(document.createTextNode(v));

	var d = document.createElement('div');
	d.className = c+s;
	d.appendChild(a);

	return xcNode[m+s] = d;
}
