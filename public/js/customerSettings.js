 $(document).ready(function () {
    $('#customer-table').DataTable( {
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
    $('#customer-id').val('');
    $('#delivery-order').val('0');
    $('#customer-code').prop('readonly','');
}

function edit(customer_id){
    $('#addEditModal').modal('show');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getCustomerByID',
        data:{customer_id:customer_id},
        success:function(result){
            if (customer = result.customer[0]){
                $('#phone').val(customer.phone);
                $('#address').val(customer.address);
                $('#remarks').val(customer.remarks);
                $('#fax').val(customer.fax);
                $('#contact-person').val(customer.contact_person);
                $('#customer-name').val(customer.customer_name);
                $('#district').val(customer.district_id);
                $('#customer-code').val(customer.customer_code);
                $('#customer-id').val(customer.id);
                $('#payment-method').val(customer.payment_method);
                $('#delivery-order').val(customer.delivery_order);
                $('#untrade').prop('checked',customer.untrade);
                $('#customer-code').prop('readonly','readonly');
            }
            else{
                $('#phone').val('');
                $('#address').val('');
                $('#remarks').val('');
                $('#fax').val('');
                $('#contact-person').val('');
                $('#customer-name').val('');
                $('#district').val('');
                $('#customer-code').val('');
                $('#customer-id').val('');
                $('#payment-method').val('');
                $('#delivery-order').val('0');
                $('#untrade').prop('checked',0);
                $('#customer-code').prop('readonly','');
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
        url:url+'saveCustomer',
        data:{data:data},
        success:function(result){
            alert('已儲存');
            window.location.href=url + 'customerSettings';
        },
        error:function(){
            alert("Error");
        }
    });
}
