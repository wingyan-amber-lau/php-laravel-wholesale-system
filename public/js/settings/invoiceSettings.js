function edit(category_id){
    $('#addEditModal').modal('show');

}

function save(){
    var data = $("form").serializeArray();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'saveInvoiceSetting',
        data:{data:data},
        success:function(result){
          alert('已儲存');
          window.location.href=url + 'invoiceSettings';
        },
        error:function(){
            alert("Error");
        }
    });
}
