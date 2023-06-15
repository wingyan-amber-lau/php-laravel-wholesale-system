$(document).ready(function () {
    $('#product-table').DataTable( {
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
    $('#product-id').val('');
    $('#product-code').prop('readonly','');
    $('#category-iteration').val('0');
    $('#category-table').html('');
}

function edit(product_id){
    $('#addEditModal').modal('show');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getProductByID',
        data:{product_id:product_id},
        success:function(result){
            if (product = result.product[0]){
                $('#category-table').html('');
                $('#category-iteration').val('0');
                $('#product-name').val(product.product_name);
                $('#product-code').val(product.product_code);
                $('#category').val(product.category_value_id);
                $('#unit').val(product.unit);
                $('#unit-price').val(product.unit_price);
                $('#unit-cost').val(product.unit_cost);
                $('#packing').val(product.packing);
                $('#product-id').val(product.id);
                $('#remarks').val(product.remarks);
                $('#count-inventory').prop('checked',product.count_inventory);
                $('#product-code').prop('readonly','readonly');
                if (result.category_values){
                    cat_vals = result.category_values;
                    if (cat_vals.length !=0){
                        cat_vals.forEach(function(cat_val,index){
                            appendCategory();
                            $('#'+$('#category-iteration').val()+'-category-id').val(cat_val.category_id);
                            changeValueMenu('#'+$('#category-iteration').val()+'-category-id');
                            $('#'+$('#category-iteration').val()+'-category-value-id').val(cat_val.category_value_id);
                        });
                    }
                    else{
                        $('#category-table').html('');
                        $('#category-iteration').val('0');
                    }
                }
                
            }
            else{
                $('#product-name').val('');
                $('#product-code').val('');
                $('#product-id').val('');
                $('#category').val('');
                $('#unit').val('');
                $('#unit-price').val('');
                $('#unit-cost').val('');
                $('#packing').val('');
                $('#remarks').val('');
                $('#count-inventory').prop('checked',0);
                $('#product-code').prop('readonly','');
                $('#category-table').html('');
                $('#category-iteration').val('0');
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
        url:url+'saveProduct',
        data:{data:data},
        success:function(result){
            alert('已儲存');
            window.location.href=url + 'productSettings';
        },
        error:function(){
            alert("Error");
        }
    });
}

function appendCategory(){
    iteration = parseInt($('#category-iteration').val()) + 1;
    category_select_str = '<tr><th><i class="fas fa-minus-square btn-fa red" onclick="removeCategory(this)"></i> 種類：</th><td><select id="'+iteration+'-category-id" name="'+iteration+'-category-id" class="form-control" onchange="changeValueMenu(this)"><option></option>'+other_categories+'</select></td><th>類別：</th><td><select id="'+iteration+'-category-value-id" name="'+iteration+'-category-value-id" class="form-control"><option></option></select></td></tr>';
    $('#category-table').append(category_select_str);
    $('#category-iteration').val(iteration);
}

function removeCategory(element){
    $(element).closest("tr").remove();
}

function changeValueMenu(element){
    category_id = $(element).val();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        method:'POST',
        url:url+'getCategoryValueByCategoryID',
        data:{category_id:category_id},
        async:false,
        success:function(result){
        if (category_values = result.category_values){
            category_value_str = '<option></option>';
            category_values.forEach(function(category_value,index){
                category_value_str = category_value_str + '<option value="'+category_value.id+'" data-code="'+category_value.value_code+'">'+category_value.value_name+'</option>';
            });
            $(element).parent().next().next().find('select').html(category_value_str);
        }
        else{
            $(element).parent().next().next().find('select').html('');
        }
        },
        error:function(){
            alert("Error");
        }
    });
}
