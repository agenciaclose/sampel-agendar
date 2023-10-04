var copy = function (link) {
    navigator.clipboard.writeText(link);
    setTimeout(() => {
        $('[data-bs-toggle="tooltip"], .tooltip').tooltip("hide");
    }, "2000");
}

$('#share_link').click(function(){
	var link = $(this).attr('data-link');
  	navigator.clipboard.writeText(link);
    setTimeout(() => {
        $('[data-bs-toggle="tooltip"], .tooltip').tooltip("hide");
    }, "2000");
});

var copy = function (link) {
    navigator.clipboard.writeText(link);
    setTimeout(() => {
        $('[data-bs-toggle="tooltip"], .tooltip').tooltip("hide");
    }, "2000");
}