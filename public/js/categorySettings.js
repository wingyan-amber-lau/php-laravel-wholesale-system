$(document).ready( function () {
    new Sortable(categoryorder, {
        animation: 150,
        ghostClass: 'blue-background-class'
    });

    $('#category-table').DataTable( {
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
    $('#category-id').val('');
    $('#category-code').prop('readonly','');
    $('#categoryorder').html('');
    $('#hidden-row').hide();
}

function edit(category_id){
    $('#addEditModal').modal('show');
    $('#hidden-row').show();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getCategoryByID',
        data:{category_id:category_id},
        success:function(result){
            if (category = result.category[0]){
                $('#category-name').val(category.category_name);
                $('#category-code').val(category.category_code);
                $('#category-id').val(category.id);
                $('#category-id-2').val(category.id);
                $('#for-product').prop('checked',category.for_product);
                $('#category-code').prop('readonly','readonly');
            }
            else{
                $('#category-name').val('');
                $('#category-code').val('');
                $('#category-id').val('');
                $('#category-id-2').val('');
                $('#category-code').prop('readonly','');
                $('#for-product').prop('checked',0);
            }
        },
        error:function(){
            alert("Error");
        }
    });
    $.ajax({
        method:'POST',
        url:url+'getCategoryValueByCategoryID',
        data:{category_id:category_id},
        success:function(result){
            if (category_values = result.category_values){
                category_value_str = '';
                category_values.forEach(function(category_value,index){
                    category_value_str = category_value_str + '<div class="list-group-item" data-name="'+category_value.id+'">'+'<button type="button" id="'+category_value.id+'" name="'+category_value.id+'" class="btn custom-btn" onclick="editCategoryValue(this.id)"><img class="icon" src="'+url+'img/edit.png"/></button>' +category_value.value_code+' '+category_value.value_name+'</div>';
                });
                $('#categoryorder').html(category_value_str);
                
            }
            else{
                $('#categoryorder').html('');
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
        url:url+'saveCategory',
        data:{data:data},
        success:function(result){
            var category_order = [];
            $(".list-group-item").each(function(index){
                category_order[index] = $(this).data('name');
            });
            $.ajax({
                method:'POST',
                url:url+'saveCategoryOrder',
                data:{category_order:category_order},
                success:function(result){
                    alert('已儲存');
                    window.location.href=url + 'categorySettings';
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

function addCategoryValue(){
    clearForm2();
    $('#addEditCategoryValueModal').modal('show');
    $('#value-id').val('');
    $('#order-in-category').val('');
    $('#value-code').prop('readonly','');
}

function editCategoryValue(category_value_id){
    $('#addEditCategoryValueModal').modal('show');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getCategoryValueByID',
        data:{category_value_id:category_value_id},
        success:function(result){
            if (category_value = result.category_value[0]){
                $('#value-name').val(category_value.value_name);
                $('#value-code').val(category_value.value_code);
                $('#value-id').val(category_value.id);
                $('#category-id-2').val(category_value.category_id);
                $('#order-in-category').val(category_value.order_in_category);
                $('#value-code').prop('readonly','readonly');
            }
            else{
                $('#value-name').val('');
                $('#value-code').val('');
                $('#value-id').val('');
                $('#category-id-2').val('');
                $('#order-in-category').val('');
                $('#value-code').prop('readonly','');
            }
        },
        error:function(){
            alert("Error");
        }
    });
    
}

function saveCategoryValue(){
    var data = $("form").serializeArray();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'saveCategoryValue',
        data:{data:data},
        success:function(result){
           alert('已儲存');
           $('#addEditCategoryValueModal').modal('hide');
           $.ajax({
                method:'POST',
                url:url+'getCategoryValueByCategoryID',
                data:{category_id:$('#category-id').val()},
                success:function(result){
                    if (category_values = result.category_values){
                        category_value_str = '';
                        category_values.forEach(function(category_value,index){
                            category_value_str = category_value_str + '<div class="list-group-item" data-name="'+category_value.id+'">'+'<button type="button" id="'+category_value.id+'" name="'+category_value.id+'" class="btn custom-btn" onclick="editCategoryValue(this.id)"><img class="icon" src="'+url+'img/edit.png"/></button>' +category_value.value_code+' '+category_value.value_name+'</div>';
                        });
                        $('#categoryorder').html(category_value_str);
                        
                    }
                    else{
                        $('#categoryorder').html('');
                    }
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
