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
  