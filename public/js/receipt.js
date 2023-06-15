$(document).ready(function(){
    autocomplete(product_code_autocomplete_path,$("[data-field='product-code']"));
    autocomplete(supplier_code_autocomplete_path,$("#supplier-code"));

    $(window).bind('keydown', function(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
            case 's':
                event.preventDefault();
                //alert('ctrl-s');
                if ($('#btn-save-receipt').prop('disabled')=='')
                    saveReceipt();
                break;
            }
        }
        
    });

    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

    $('body').on('keydown', 'input, textarea', function(e) {
        if (e.key === "Enter") {
            var self = $(this), form = $('form'), focusable, next;
            //focusable = form.find('input,a,select,button,textarea').filter(':not([tabindex="-1"]):not([type="hidden"]):not([class="notab"])');
            editable = form.find('input,textarea').filter(':not([type="hidden"]):not([readonly])');
            iteration = 1;
            next = editable[editable.index(this)+iteration];
            //console.log(editable.index(this));

            while ($(next).prop('tabindex')== "-1"){
                iteration++;
                next = editable[editable.index(this)+iteration];
            }
            //console.log(focusable.index(this));
            
            if (next.id.length) {
                next.focus();
            } 
            return false;
        }
    });
});

function clearReceipt(){
    $('input').each(function(){
        $(this).val('');
    });
    $('textarea').each(function(){
        $(this).val('');
    });
    $('#receipt-total').val('$0');
}

function enableSaveInovice(){
    $('#btn-save-receipt').prop('disabled', '');
}
function getSupplier(){
    var supplierCode = $('#supplier-code').val();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getSupplier',
        data:{supplierCode:supplierCode},
        success:function(result){
            if (supplier = result.supplier[0]){
                $('#phone').val(supplier.phone);
                $('#phone_2').val(supplier.phone_2);
                $('#address').val(supplier.address);
                $('#fax').val(supplier.fax);
                $('#email').val(supplier.email);
                $('#contact-person').val(supplier.contact_person);
                $('#supplier-name').val(supplier.supplier_name);
                enableSaveInovice();
                if(result.duplicate_receipt>0)
                    alert('此入貨單已存在入貨單。');
            }
            else{
                $('#phone').val('');
                $('#phone_2').val('');
                $('#email').val('');
                $('#address').val('');
                $('#fax').val('');
                $('#contact-person').val('');
                $('#supplier-name').val('');
                
            }
        },
        error:function(){
            alert("Error");
        }
    });

}

function getProduct(element_id){
    var id = element_id.substr(0,element_id.indexOf('-'));
    var productCode = $('#'+element_id).val();
    var duplicate = false;
    $("[data-field='product-code']").each(function(){
        if ($(this).val().toUpperCase() == productCode.toUpperCase() && element_id!=$(this).prop('id')){
            alert('此貨品已加入入貨單。');
            $('#'+element_id).val('');
            $(this).focus();
            duplicate = true;
            return false;
        }
    });
    if (!duplicate){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({
              method:'POST',
              url:url+'getprod',
              data:{productCode:productCode},
              success:function(result){
                  if (product = result.product[0]){
                      $('#'+id+'-category').val(product.value_name);
                      $('#'+id+'-product-name').val(product.product_name);
                      $('#'+id+'-unit').val(product.unit);
                      $('#'+id+'-packing').val(product.packing);
                      $('#'+id+'-amount').val('1');
                      $('#'+id+'-amount').select();
                      countProductTotal(element_id);
                  }
                  else{
                      $('#'+id+'-category').val('');
                      $('#'+id+'-product-name').val('');
                      $('#'+id+'-unit').val('');
                      $('#'+id+'-unit-cost').val('');
                      $('#'+id+'-packing').val('');
                      $('#'+id+'-total-price').val('');
                      $('#'+id+'-amount').val('');
                  }
                  enableSaveInovice();
              },
              error:function(){
                  alert("Error");
              }
          });
        }
}

function countProductTotal(element_id){
    var id = element_id.substr(0,element_id.indexOf('-')),
        //discount = $('#'+id+'-discount').val(),
        unit_cost = $('#'+id+'-unit-cost').val(),
        amount = $('#'+id+'-amount').val();
    //$('#'+id+'-total-price').val((amount*unit_price)-discount);
    $('#'+id+'-total-cost').val(amount*unit_cost);
    countReceiptTotal();
}

function countReceiptTotal(){
    var total = 0;
    $('[data-field="total-cost"]').each(function() {
        total = Number(total) + Number($(this).val());
    });
    $('#receipt-total').val('$'+total.toLocaleString());
}

function saveReceipt(){
    if (confirm('確定儲存入貨單?')){
        var data = $("form").serializeArray();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            method:'POST',
            url:url+'saveReceipt',
            data:{data:data},
            success:function(result){
                console.log(result);
                if (result){
                    alert('已儲存');
                    window.location.href=url + 'receipt/' + result.receipt_code;
                }
            },
            error:function(xhr){
                $.each(xhr.responseJSON.errors, function(key,value) {
                    alert(value);
                });
            }
        });
    }
}

function newReceipt(){
    if (confirmLeaveInovice())
        window.location.href= url + "receipt";
}

function confirmLeaveInovice(){
    if ($('#btn-save-receipt').prop('disabled')=='' ){
        return confirm('你的入貨單尚未儲存，確定離開頁面？');
    }
    else return true;
}

function nextReceipt(all_or_supplier){
    if (confirmLeaveInovice()){
        var receipt_id = $('#receipt-id').val(),
        supplier_code = $('#supplier-code').val();

        if (receipt_id){
            
            //alert(receipt_code);
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                method:'POST',
                url:url+'nextReceipt',
                data:{receipt_id:receipt_id,all_or_supplier:all_or_supplier,supplier_code:supplier_code},
                success:function(result){
                    if (result!=0){
                        window.location.href= url + 'receipt/' + result;
                    }
                    else alert('這是最後入貨單。');
                    //else window.location.href= url + 'order';
                },
                error:function(xhr){
                    $.each(xhr.responseJSON.errors, function(key,value) {
                        alert(value);
                    });
                }
            });
        }
        //else alert("已是最後入貨單！");
    }
}

function prevReceipt(all_or_supplier){
    if (confirmLeaveInovice()){
        var receipt_id = $('#receipt-id').val(),
        supplier_code = $('#supplier-code').val();
        
            //alert(receipt_code);
        $.ajaxSetup({
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            method:'POST',
            url:url+'prevReceipt',
            data:{receipt_id:receipt_id,all_or_supplier:all_or_supplier,supplier_code:supplier_code},
            success:function(result){
                if (result!=0){
                    window.location.href= url + 'receipt/' + result;
                }
                else alert('這是最前入貨單。');
                //else window.location.href= url + 'order';
            },
            error:function(xhr){
                $.each(xhr.responseJSON.errors, function(key,value) {
                    alert(value);
                });
            }
        });
    }
        //else alert("已是最後入貨單！");
}

function voidReceipt(){
    if (confirm('你確定要刪除入貨單？')){
        var receipt_code = $('#receipt-code').val();
        
        $.ajaxSetup({
            headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
       });
       $.ajax({
           method:'POST',
           url:url+'voidReceipt',
           data:{receipt_code:receipt_code},
           success:function(result){
               alert('已刪除入貨單。');
               location.reload();
           },
           error:function(xhr){
               $.each(xhr.responseJSON.errors, function(key,value) {
                   alert('Error');
               });
           }
       });
    }
}

function checkProductLastOrderDate(element_id){
    var id = element_id.substr(0,element_id.indexOf('-'));
    var product_code = $('#'+id+'-product-code').val(),
        supplier_code = $('#supplier-code').val();
    if (product_code){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            method:'POST',
            url:url+'getprodlastorderdate',
            data:{product_code:product_code,supplier_code:supplier_code},
            success:function(result){
                alert(result);
            },
            error:function(){
                alert("Error");
            }
        });
    }
}

function appendNewRow(element){
    
        max_row = $('#max-row').val();
        new_row_str = '';
        if ($(element).prop('id') == max_row+"-product-code" ){
            new_max_row = Number(max_row) + 1;
            new_row_str = new_row_str + '<tr>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-order-item-no" name="'+new_max_row+'-order-item-no" data-field="order-item-no" type="text" value="'+new_max_row+'" readonly tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-category" name="'+new_max_row+'-category" data-field="category" value="" type="text" readonly tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-product-code" name="'+new_max_row+'-product-code" data-field="product-code" value="" type="text" class="text-uppercase" onchange="getProduct(this.id)" onfocus="appendNewRow(this)"/></td><td><button id="'+new_max_row+'-btn-check-product" class="btn btn-sm" name="'+new_max_row+'-btn-check-product" type="button" tabindex="-1" onclick="checkProductLastOrderDate(this.id)">&#x2605;</button></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-product-name" name="'+new_max_row+'-product-name" data-field="product-name" value="" type="text" readonly tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-amount" name="'+new_max_row+'-amount" type="text" data-field="amount" value="" onchange="countProductTotal(this.id)" onfocus="select(this)"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-unit" name="'+new_max_row+'-unit" type="text" data-field="unit" value="" readonly tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td>';
            //new_row_str = new_row_str + '    <input id="'+new_max_row+'-unit-price" name="'+new_max_row+'-unit-price" data-field="unit-price" value="" readonly type="number" step=".01" tabindex="-1"/>';
            new_row_str = new_row_str + '    <input id="'+new_max_row+'-unit-cost" name="'+new_max_row+'-unit-cost" data-field="unit-cost" value="" type="number" step="0.01" onchange="countProductTotal(this.id)" />';
            new_row_str = new_row_str + '</td>';
            //new_row_str = new_row_str + '<td><input id="'+new_max_row+'-discount" name="'+new_max_row+'-discount" data-field="discount" type="number" value="" step=".01" tabindex="-1" onchange="countProductTotal(this.id)"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-total-cost" name="'+new_max_row+'-total-cost" data-field="total-cost" value="" readonly type="number" step=".01" tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-packing" name="'+new_max_row+'-packing" data-field="packing" value="" type="text" tabindex="-1"/></td>';
            new_row_str = new_row_str + '</tr>';
            $('#receipt-item-list').append(new_row_str);
            $('#max-row').val(new_max_row);
        }

}
