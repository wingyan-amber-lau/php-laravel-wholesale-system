$(document).ready( function () {
    $('#balance-date').datepicker( {format: 'yyyy-mm-dd' });

    $('input').on('change',function (){
        if (this.id != "balance-date")
            update(this);
        //TODO:save in DB
    });
} );


function update(element){
    balance_date = $('#balance-date').val();
    data = $(element).data();
    value = $(element).val();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method:'POST',
        url:url+'updateDailySettlement',
        data:{data:data,value:value,balance_date:balance_date},
        success:function(result){
            
        },
        error:function(){
            alert("Error");
        }
    });
}