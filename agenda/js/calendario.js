$(document).ready(function(){	
      //CARREGA CALENDÁRIO E EVENTOS DO BANCO
      var DataHoje = new Date().toISOString().slice(0,10);
      
      $('#calendario').fullCalendar({
          header: {
              left: 'prev,next today',
              center: 'title',
              right: 'month,agendaWeek,agendaDay'
          },
          defaultDate: DataHoje,
          editable: true,
          eventLimit: true, 
          events: 'agenda.php',           
          eventColor: '#dd6777',
          eventClick:  function(event, jsEvent, view) {  
              $.ajax({
                url:'../detalhar.php?target='+event.target, 
                success: function(result){
                  $('#ModalView #ModalViewContent').html(result);
                  $('#ModalView').modal('show', {backdrop: 'statitc'});
                }
              });
          }
      }); 
});	