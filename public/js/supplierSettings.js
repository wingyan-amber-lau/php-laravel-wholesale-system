 $(document).ready(function () {
    $('#supplier-table').DataTable( {
        "pageLength": 50,
        "language":{
            "url": url+'js/datatables/dataTables.chinese.lang'
        },
        "dom": '<"top"f>rt<"bottom"lp><"clear">',
        "buttons": [
            'selectAll',
            'selectNone'
        ],"columnDefs": [
        { "orderable": false, "targets": 0 }
        ]
    });
// $('.table').DataTable();
// $('.dataTables_length').addClass('bs-select');
 });


function add(){
    clearForm();
    $('#addEditModal').modal('show');
    $('#supplier-id').val('');
    $('#supplier-code').prop('readonly','');
}

function edit(supplier_id){
    $('#addEditModal').modal('show');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getSupplierByID',
        data:{supplier_id:supplier_id},
        success:function(result){
            if (supplier = result.supplier[0]){
                $('#phone').val(supplier.phone);
                $('#phone_2').val(supplier.phone_2);
                $('#address').val(supplier.address);
                $('#fax').val(supplier.fax);
                $('#contact-person').val(supplier.contact_person);
                $('#supplier-name').val(supplier.supplier_name);
                $('#supplier-code').val(supplier.supplier_code);
                $('#supplier-id').val(supplier.id);
                $('#email').val(supplier.email);
                $('#supplier-code').prop('readonly','readonly');
            }
            else{
                $('#phone').val('');
                $('#phone_2').val('');
                $('#address').val('');
                $('#email').val('');
                $('#fax').val('');
                $('#contact-person').val('');
                $('#supplier-name').val('');
                $('#district').val('');
                $('#supplier-code').val('');
                $('#supplier-id').val('');
                $('#supplier-code').prop('readonly','');
            }
        },
        error:function(){
            alert("Error");
        }
    });
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
        url:url+'saveSupplier',
        data:{data:data},
        success:function(result){
            alert('已儲存');
            window.location.href=url + 'supplierSettings';
        },
        error:function(){
            alert("Error");
        }
    });
}
