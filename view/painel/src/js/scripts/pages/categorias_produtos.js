$('.cb-select-all').click(function(){
	$("input[name='delete_cat[]']").not(this).prop('checked', this.checked);
	if($(this).attr('data-location') == 'header'){
		$(".cb-select-all.footer").not(this).prop('checked', this.checked);
	}else{
		$(".cb-select-all.header").not(this).prop('checked', this.checked);
	}
});
$("input[name='delete_cat[]']").click(function(){
	$(".cb-select-all").prop('checked', false);
});
$(".action_select").change(function(){
	if($(this).val() == '-1'){
		$('#action_list').attr('type', 'button');
	}else{
		$('#action_list').attr('type', 'submit');
	}
});
$("#categorias").filterTable({
    placeholder: "Pesquisar...",
    containerClass: "form-group pull-right filter-table",
    callback: function(a, t) {
        t.find("tr").removeClass("striped").filter(":visible:even").addClass("striped")
    }
});
var slug = function(str) {
	str = str.replace(/^\s+|\s+$/g, '');
	str = str.toLowerCase();
	var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆĞÍÌÎÏİŇÑÓÖÒÔÕØŘŔŠŞŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇğíìîïıňñóöòôõøðřŕšşťúůüùûýÿžþÞĐđßÆa·/_,:;";
	var to   = "AAAAAACCCDEEEEEEEEGIIIIINNOOOOOORRSSTUUUUUYYZaaaaaacccdeeeeeeeegiiiiinnooooooorrsstuuuuuyyzbBDdBAa------";
	for (var i=0, l=from.length ; i<l ; i++) {str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));}
	str = str.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
	$('.cat_slug').val(str);
};
