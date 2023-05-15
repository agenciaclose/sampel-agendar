var minPrice = function (){
	var min = $('#price_area').attr('min');
	return min;
}

var maxPrice = function (){
	var max = $('#price_area').attr('max');
	return max;
}

var defaultPrice = function (){
	var defaultPrice = $('#price_area').attr('value');
	if(defaultPrice == ''){
		defaultPrice = $('#price_area').attr('max');
	}
	return defaultPrice;
}

$('#price_area').ionRangeSlider({
    min: minPrice(),
    max: maxPrice(),
    from: defaultPrice(),
    prefix: "R$ ",
});