const pieChart = (selector, context) => {
	context = context || document;
	let elements = context.querySelectorAll(selector);
	return Array.prototype.slice.call(elements);
}

pieChart('.pie').forEach(function(pie) {
	let p = pie.textContent;
	pie.style.animationDelay = '-' + parseFloat(p) + 's';
});

