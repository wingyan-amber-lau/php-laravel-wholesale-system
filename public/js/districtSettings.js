$(document).ready( function () {
    new Sortable(customerorder, {
        animation: 150,
        ghostClass: 'blue-background-class'
    });

    $('#district-table').DataTable( {
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
});

function add(){
    clearForm();
    $('#addEditModal').modal('show');
    $('#district-id').val('');
    $('#district-code').prop('readonly','');
    $('#customerorder').html('');
}

function edit(district_id){
    $('#addEditModal').modal('show');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getDistrictByID',
        data:{district_id:district_id},
        success:function(result){
            if (district = result.district[0]){
                $('#district-name').val(district.district_name);
                $('#district-code').val(district.district_code);
                $('#district-id').val(district.id);
                $('#mon').prop('checked',district.mon);
                $('#car-no-mon').val((district.car_no_mon)?district.car_no_mon:'');
                $('#order-in-car-mon').val((district.order_in_car_mon)?district.order_in_car_mon:'');
                $('#tue').prop('checked',district.tue);
                $('#car-no-tue').val((district.car_no_tue)?district.car_no_tue:'');
                $('#order-in-car-tue').val((district.order_in_car_tue)?district.order_in_car_tue:'');
                $('#wed').prop('checked',district.wed);
                $('#car-no-wed').val((district.car_no_wed)?district.car_no_wed:'');
                $('#order-in-car-wed').val((district.order_in_car_wed)?district.order_in_car_wed:'');
                $('#thu').prop('checked',district.thu);
                $('#car-no-thu').val((district.car_no_thu)?district.car_no_thu:'');
                $('#order-in-car-thu').val((district.order_in_car_thu)?district.order_in_car_thu:'');
                $('#fri').prop('checked',district.fri);
                $('#car-no-fri').val((district.car_no_fri)?district.car_no_fri:'');
                $('#order-in-car-fri').val((district.order_in_car_fri)?district.order_in_car_fri:'');
                $('#sat').prop('checked',district.sat);
                $('#car-no-sat').val((district.car_no_sat)?district.car_no_sat:'');
                $('#order-in-car-sat').val((district.order_in_car_sat)?district.order_in_car_sat:'');
                if (district.mon){
                    $('#car-no-mon').prop('required','required');
                    $('#order-in-car-mon').prop('required','required');
                }
                else {
                    $('#car-no-mon').prop('required','');
                    $('#order-in-car-mon').prop('required','');
                }
                if (district.tue){
                    $('#car-no-tue').prop('required','required');
                    $('#order-in-car-tue').prop('required','required');
                }
                else {
                    $('#car-no-tue').prop('required','');
                    $('#order-in-car-tue').prop('required','');
                }
                if (district.wed){
                    $('#car-no-wed').prop('required','required');
                    $('#order-in-car-wed').prop('required','required');
                }
                else {
                    $('#car-no-wed').prop('required','');
                    $('#order-in-car-wed').prop('required','');
                }
                if (district.thu){
                    $('#car-no-thu').prop('required','required');
                    $('#order-in-car-thu').prop('required','required');
                }
                else {
                    $('#car-no-thu').prop('required','');
                    $('#order-in-car-thu').prop('required','');
                }
                if (district.fri){
                    $('#car-no-fri').prop('required','required');
                    $('#order-in-car-fri').prop('required','required');
                }
                else {
                    $('#car-no-fri').prop('required','');
                    $('#order-in-car-fri').prop('required','');
                }
                if (district.sat){
                    $('#car-no-sat').prop('required','required');
                    $('#order-in-car-sat').prop('required','required');
                }
                else {
                    $('#car-no-sat').prop('required','');
                    $('#order-in-car-sat').prop('required','');
                }
                $('#district-code').prop('readonly','readonly');
            }
            else{
                $('#district-name').val('');
                $('#district-code').val('');
                $('#district-id').val('');
                $('#mon').prop('checked',0);
                $('#tue').prop('checked',0);
                $('#wed').prop('checked',0);
                $('#thu').prop('checked',0);
                $('#fri').prop('checked',0);
                $('#sat').prop('checked',0);
                $('#car-no-mon').val('');
                $('#order-in-car-mon').val('');
                $('#car-no-tue').val('');
                $('#order-in-car-tue').val('');
                $('#car-no-wed').val('');
                $('#order-in-car-wed').val('');
                $('#car-no-thu').val('');
                $('#order-in-car-thu').val('');
                $('#car-no-fri').val('');
                $('#order-in-car-fri').val('');
                $('#car-no-sat').val('');
                $('#order-in-car-sat').val('');
                $('#district-code').prop('readonly','');
            }
        },
        error:function(){
            alert("Error");
        }
    });
    $.ajax({
        method:'POST',
        url:url+'getCustomerDeliveryOrderByDistrictID',
        data:{district_id:district_id},
        success:function(result){
            if (customers = result.customers){
                customer_str = '';
                customers.forEach(function(customer,index){
                    customer_str = customer_str + '<div class="list-group-item" id="'+customer.customer_code+'">'+customer.customer_code+' '+customer.customer_name+'</div>';
                });
                $('#customerorder').html(customer_str);
                
            }
            else{
                $('#customerorder').html('');
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
        url:url+'saveDistrict',
        data:{data:data},
        success:function(result){
            var customer_order = [];
            $(".list-group-item").each(function(index){
                customer_order[index] = $(this).prop('id');
            });
            $.ajax({
                method:'POST',
                url:url+'saveCustomerDeliveryOrder',
                data:{customer_order:customer_order},
                success:function(result){
                    alert('已儲存');
                    window.location.href=url + 'districtSettings';
                },
                error:function(){
                    alert("Error");
                }
            });
        },
        error:function(){
            alert("Error");
        }
    });
    
}

function setRequired(id){
    if($('#'+id).prop('checked')){
        $('#car-no-'+id).prop('required','required');
        $('#order-in-car-'+id).prop('required','required');
    }
    else {
        $('#car-no-'+id).prop('required','');
        $('#order-in-car-'+id).prop('required','');
        $('#car-no-'+id).val('');
        $('#order-in-car-'+id).val('');
    }
}