$(document).ready( function () {
    $('#delivery-date').datepicker( {
        format: 'yyyy-mm-dd',
        beforeShowDay: function(date) {
            var day = date.getDay();
            return [(day != 0), ''];
        } 
    });
} );

function changeStatus(element){
    invoice_code = $(element).prop('id');
    status = '';
    if ($(element).find('.cross').css('display')!='none'){
        status = 'NONE';  
    }
    else if ($(element).find('.circle').css('display')!='none'){
        status = 'PEND';
    }
    else{
        status = 'PAID';
    }
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    $.ajax({
        method:'POST',
        url:url+'checklistChangeStatus',
        data:{status:status,invoice_code:invoice_code},
        success:function(result){
            switch(status) {
                case 'PAID':
                    $(element).find('.circle').show();
                    break;
                case 'PEND':
                    $(element).find('.cross').show();
                    $(element).find('.circle').hide();
                    break;
                default:
                    $(element).find('.cross').hide();
              }
        },
        error:function(){
            alert('發生錯誤');
            //location.reload();
        }
    });

}