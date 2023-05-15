$(document).ready(function () {

	//DATA TABLES
	$('#table-users').DataTable({
		initComplete: function() {
			this.api().columns('.dt-filter').every(function() {
				var column = this;
				var coluna = column.header();
				var select = $('<select class="form-select form-select-sm"><option value="">Selecione</option></select>')
				.appendTo($(column.header()).empty())
				.on('change', function() {
					var val = $.fn.dataTable.util.escapeRegex($(this).val());
					column.search(val ? '^' + val + '$' : '', true, false).draw();
				});

				column.data().unique().sort().each(function(d, j) {
					d = d.replace(/<(\/)?([a-zA-Z]*)(\s[a-zA-Z]*=[^>]*)?(\s)*(\/)?>/g, '');
					select.append('<option value="' + d + '">' + d + '</option>')
				});
			});
		},
		"order": [[ 0, "desc" ]],
		"ordering": false,
		"lengthMenu": [[-1], ['Todos']],
		"language": {
			"sEmptyTable": "Nenhum registro encontrado",
			"sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
			"sInfoFiltered": "(Filtrados de _MAX_ registros)",
			"sInfoPostFix": "",
			"sInfoThousands": ".",
			"searchPlaceholder": "Pesquisar..",
			"sLengthMenu": "_MENU_",
			"sLoadingRecords": "Carregando...",
			"sProcessing": "Processando...",
			"sZeroRecords": "Nenhum registro encontrado",
			"sSearch": "",
			"oPaginate": {
				"sNext": "Próximo",
				"sPrevious": "Anterior",
				"sFirst": "Primeiro",
				"sLast": "Último"
			},
			"oAria": {
				"sSortAscending": ": Ordenar colunas de forma ascendente",
				"sSortDescending": ": Ordenar colunas de forma descendente"
			}
		}
	});

});

function userStatus(status, user){
	$.post(domain + "/painel/admin/usuarios/status",{status:status, user:user},
	function(){
		location.reload();
	});
}
