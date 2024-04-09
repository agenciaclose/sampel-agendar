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

document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="resposta[]"]');
    let uniqueClasses = new Set();
  
    // Coleta todas as classes únicas dos checkboxes
    checkboxes.forEach(checkbox => {
      checkbox.classList.forEach(className => {
        uniqueClasses.add(className);
      });
    });
  
    uniqueClasses.forEach(uniqueClass => {
      const classCheckboxes = document.querySelectorAll(`input[type="checkbox"].${uniqueClass}[name="resposta[]"]`);
  
      classCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
          // Se um checkbox for selecionado, desmarque os outros da mesma classe
          if (checkbox.checked) {
            classCheckboxes.forEach(innerCheckbox => {
              if (innerCheckbox !== checkbox) {
                innerCheckbox.checked = false;
              }
            });
          }
        });
      });
    });
});

// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
  $('.select2-tag').select2({
    tags: true
  });
});


$(document).ready(function() {

  $("[name='resenha']").on('keyup', function() {
    // Verifica se ambos os campos estão preenchidos
    var senha = $("[name='senha']").val().trim();
    var resenha = $("[name='resenha']").val().trim();
    
    if (senha !== '' && resenha !== '') {
      // Se ambos os campos estiverem preenchidos, verifica se os valores são iguais
      if (senha === resenha) {
        $("button[type='submit']").prop('disabled', false);
        $('#senha_alert').hide();
      } else {
        $("button[type='submit']").prop('disabled', true);
        $('#senha_alert').show();
      }
    } else {
      $("button[type='submit']").prop('disabled', true);
      $('#senha_alert').hide();
    }
  });

  $("#user_definir_senha").submit(function (c) {
  
    $('.form-load').addClass('show');
    $('button[type="submit"]').prop("disabled", true);
  
    c.preventDefault();
    var DOMAIN = $('body').data('domain');
    var form = $(this);
  
    $.ajax({
        type: "POST", async: true, data: form.serialize(),
        url: DOMAIN + '/login/senha/senhaSave',
        success: function (data) {
          window.location.href = DOMAIN + '/login';
        }
    });

  });

});