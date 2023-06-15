$(document).ready(function(){
    //autocomplete(product_code_autocomplete_path,$("[data-field='product-code']"));
    autocomplete(customer_code_autocomplete_path,$("#customer-code"));
    autocomplete(phone_autocomplete_path,$("#phone"));

    $(window).bind('keydown', function(event) {
        if (event.altKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    //alert('ctrl-s');
                    $('#remarks').focus();
                    if ($('#btn-save-invoice').prop('disabled')=='')
                        //console.log($('#7-discount').val());
                        saveInvoice();
                    break;
                
                case 'a':
                    event.preventDefault();
                    //alert('ctrl-a');
                    if (confirmLeaveInovice())
                        window.location.href = url + 'order';
                    break;
                case 'f':
                    event.preventDefault();
                    //alert('ctrl-q');
                    window.open(url + 'searchInvoice');
                    break;
                case 'd':
                    event.preventDefault();
                    //save before print
                    $('#remarks').focus();
                    if ($('#btn-save-invoice').prop('disabled')==''){
                        //console.log($('#7-discount').val());
                        saveInvoice();
                    }
                    printInvoice();
                    break;
                case 'q':
                    event.preventDefault();
                    prevInvoice('all');
                    break;
                case 'w':
                    event.preventDefault();
                    prevInvoice('customer');
                    break;
                case 'e':
                    event.preventDefault();
                    nextInvoice('customer');
                    break;
                case 'r':
                    event.preventDefault();
                    nextInvoice('all');
                    break;
            }
        }
        
    });

    //Prevent default event of key 'Enter'
    $('form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });
    //Shift to next input when press 'Enter'
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
    if ($('#customer-code').val()=="")
        $('#customer-code').focus();
});

function clearInvoice(){
    $('input').each(function(){
        $(this).val('');
    });
    $('textarea').each(function(){
        $(this).val('');
    });
    $('#invoice-total').val('$0');
}

function enableSaveInvoice(){
    $('#btn-save-invoice').prop('disabled', '');
}
function getCustomer(code_or_phone){
    var customerCode = $('#customer-code').val(),
        invoice_id = $('#invoice-id').val(),
        phone = $('#phone').val();
    //if (phone == '' && customerCode == '')
    //    return;
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getcust',
        data:{customerCode:customerCode,invoice_id:invoice_id,phone:phone,code_or_phone:code_or_phone},
        success:function(result){
            if (customer = result.customer[0]){
                $('#phone').val(customer.phone);
                $('#address').val(customer.address);
                $('#remarks').val(customer.remarks);
                $('#fax').val(customer.fax);
                $('#contact-person').val(customer.contact_person);
                $('#customer-code').val(customer.customer_code);
                $('#customer-name').val(customer.customer_name);
                $('#district-name').val(customer.district_name);
                $('#district-code').val(customer.district_code);
                $('#payment-method').val(customer.payment_method);
                $('#payment-method-display').val(customer.value_name);
                enableSaveInvoice();
                getDeliveryDate();
                if(result.duplicate_invoice>0)
                    alert('此客戶已存在發票。');
            }
            else{
                $('#phone').val('');
                $('#address').val('');
                $('#remarks').val('');
                $('#fax').val('');
                $('#contact-person').val('');
                $('#customer-code').val('');
                $('#customer-name').val('');
                $('#district-name').val('');
                $('#district-code').val('');
                $('#payment-method').val('');
                $('#payment-method-display').val('');
                $('#delivery-date').val('');
            }
        },
        error:function(){
            alert("Error");
        }
    });

}

function getProduct(element_id){
    var id = element_id.substr(0,element_id.indexOf('-'));
    var productCode = $('#'+element_id).val(),
        customer_code = $('#customer-code').val(),
        invoice_id = $('#invoice-id').val(),
        delivery_date = $('#delivery-date').val();
    var duplicate = false;

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (productCode!=""){
        //check duplicate product in same invoice
        $("[data-field='product-code']").each(function(){
            if ($(this).val().toUpperCase() == productCode.toUpperCase() && element_id!=$(this).prop('id')){
                alert('此貨品已加入發票。');
                $('#'+element_id).focus();
                //$('#'+element_id).val('');
                //console.log($('#'+element_id).next().html());
                //$('#'+element_id).next().html('');
                //$(this).focus();
                duplicate = true;
                return false;
            }
        });
        //check duplicate product in separated invoice
        $.ajax({
            method:'POST',
            url:url+'checkDuplicateProductOnSameDeliveryDate',
           // async:false,
            data:{productCode:productCode,customer_code:customer_code,invoice_id:invoice_id,delivery_date:delivery_date},
            success:function(result){
                if (result > 0){
                    alert('此貨品已加入先前發票。');
                    $('#'+element_id).focus();
                    //$('#'+element_id).next().next().html('');
                    duplicate = true;
                }
            },
            error:function(){
                alert("Error");
            }
        });
    }
    //if (!duplicate){
          $.ajax({
              method:'POST',
              url:url+'getprod',
              data:{productCode:productCode,customerCode:customer_code},
              success:function(result){
                  if (product = result.product[0]){
                      $('#'+id+'-category').val(product.value_name);
                      $('#'+id+'-product-name').val(product.product_name);
                      $('#'+id+'-unit').val(product.unit);
                      $('#'+id+'-unit-price').val(product.unit_price);
                      $('#'+id+'-unit-cost').val(product.unit_cost);
                      $('#'+id+'-packing').val(product.packing);
                      $('#'+id+'-amount').val('1');
                      $('#'+id+'-amount').focus();
                      countProductTotal(element_id);
                  }
                  else{
                      $('#'+id+'-category').val('');
                      $('#'+id+'-product-name').val('');
                      $('#'+id+'-unit').val('');
                      $('#'+id+'-unit-price').val('');
                      $('#'+id+'-unit-cost').val('');
                      $('#'+id+'-packing').val('');
                      $('#'+id+'-discount').val('');
                      $('#'+id+'-total-price').val('');
                      $('#'+id+'-amount').val('');
                  }
                  enableSaveInvoice();
              },
              error:function(){
                  alert("Error");
              }
          });
        //}
        //else $('#'+element_id).val('');
}

function countProductTotal(element_id){
    var id = element_id.substr(0,element_id.indexOf('-')),
        discount = $('#'+id+'-discount').val(),
        unit_price = $('#'+id+'-unit-price').val(),
        amount = $('#'+id+'-amount').val();
        arr = amount.split(/[*|個]/);
        arr = arr.map(Number);
        if (arr.length>1)
            amount = arr[0]*arr[1];
    $('#'+id+'-total-price').val(amount*(unit_price-discount));
    countInvoiceTotal();
}

function countInvoiceTotal(){
    var total = 0;
    $('[data-field="total-price"]').each(function() {
        total = Number(total) + Number($(this).val());
    });
    $('#invoice-total').val('$'+total.toLocaleString());
}

function saveInvoice(){
    if (confirm('確定儲存發票?')){
        var data = $("form").serializeArray();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            method:'POST',
            url:url+'saveinvoice',
            data:{data:data},
            success:function(result){
                console.log(result);
                if (result){
                    alert('已儲存');
                    window.location.href=url + 'order/' + result.invoice_code;
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

function newInvoice(){
    if (confirmLeaveInovice())
        window.location.href= url + "order";
}

function confirmLeaveInovice(){
    if ($('#btn-save-invoice').prop('disabled')=='' ){
        return confirm('你的發票尚未儲存，確定離開頁面？');
    }
    else return true;
}

function nextInvoice(all_or_customer){
    if (confirmLeaveInovice()){
        var invoice_code = $('#invoice-code').val(),
        customer_code = $('#customer-code').val();
        if (invoice_code){
            invoice_code = invoice_code.substring(3);
            //alert(invoice_code);
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
                method:'POST',
                url:url+'nextinvoice',
                data:{invoice_code:invoice_code,all_or_customer:all_or_customer,customer_code:customer_code},
                success:function(result){
                    if (result>0){
                        window.location.href= url + 'order/' + result;
                    }
                    else alert('這是最後發票。');
                    //else window.location.href= url + 'order';
                },
                error:function(xhr){
                    $.each(xhr.responseJSON.errors, function(key,value) {
                        alert(value);
                    });
                }
            });
        }
        //else alert("已是最後發票！");
    }
}

function prevInvoice(all_or_customer){
    if (confirmLeaveInovice()){
        var invoice_code = $('#invoice-code').val(),
        customer_code = $('#customer-code').val();
        if (invoice_code){
            invoice_code = invoice_code.substring(3);
        }
            //alert(invoice_code);
        $.ajaxSetup({
             headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            method:'POST',
            url:url+'previnvoice',
            data:{invoice_code:invoice_code,all_or_customer:all_or_customer,customer_code:customer_code},
            success:function(result){
                if (result>0){
                    window.location.href= url + 'order/' + result;
                }
                else alert('這是最前發票。');
                //else window.location.href= url + 'order';
            },
            error:function(xhr){
                $.each(xhr.responseJSON.errors, function(key,value) {
                    alert(value);
                });
            }
        });
    }
        //else alert("已是最後發票！");
}

function voidInvoice(){
    if (confirm('你確定要刪除發票？')){
        var invoice_code = $('#invoice-code').val();
        invoice_code = invoice_code.substring(3);
        $.ajaxSetup({
            headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
       });
       $.ajax({
           method:'POST',
           url:url+'voidInvoice',
           data:{invoice_code:invoice_code},
           success:function(result){
               alert('已刪除發票。');
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
        product_name = $('#'+id+'-product-name').val(),
        customer_code = $('#customer-code').val(),
        invoice_id = $('#invoice-id').val();
        if (invoice_id == "")
            invoice_id = 99999999999;
    if (product_code){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
            method:'POST',
            url:url+'getprodlastorderdate',
            data:{product_code:product_code,customer_code:customer_code,invoice_id:invoice_id},
            success:function(result){
                str = product_code + ' ' + product_name;
                if (result != '沒有紀錄。'){
                    obj = JSON.parse(result);
                    str = str + '<table class="table table-hover">';
                    str = str + '<tr><th>發票編號</th><th>送貨日期</th><th>數量</th></tr>';
                    obj.forEach( row => str = str + '<tr><td><a target="_BLANK" href="'+url+'order/'+row.invoice_code+'">INV'+row.invoice_code+'</a></td><td>'+row.delivery_date+'</td><td>'+row.amount+row.unit+'</td></tr>');
                    str = str + '';
                    str = str + '</table>';
                }
                else str = str + '<br>' + result;
                $('#alert-content').html(str);
                $('#alert-container').show();
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
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-amount" name="'+new_max_row+'-amount" type="text" data-field="amount" value="" onchange="countProductTotal(this.id)" onfocus="select(this)" pattern="[0-9]+[*|個]?[0-9]*"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-unit" name="'+new_max_row+'-unit" type="text" data-field="unit" value="" readonly tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td>';
            new_row_str = new_row_str + '    <input id="'+new_max_row+'-unit-price" name="'+new_max_row+'-unit-price" data-field="unit-price" value="" type="number" step=".01" tabindex="-1"/>';
            new_row_str = new_row_str + '    <input id="'+new_max_row+'-unit-cost" name="'+new_max_row+'-unit-cost" data-field="unit-cost" value="" type="hidden" tabindex="-1"/>';
            new_row_str = new_row_str + '</td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-discount" name="'+new_max_row+'-discount" data-field="discount" type="number" value="" step=".01" tabindex="-1" onchange="countProductTotal(this.id);enableSaveInvoice()"/></td>';
            new_row_str = new_row_str + '<td><input type="checkbox" id="'+new_max_row+'-discount-once" name="'+new_max_row+'-discount-once" tabindex="-1" ></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-total-price" name="'+new_max_row+'-total-price" data-field="total-price" value="" readonly type="number" step=".01" tabindex="-1"/></td>';
            new_row_str = new_row_str + '<td><input id="'+new_max_row+'-packing" name="'+new_max_row+'-packing" data-field="packing" value="" type="text" readonly tabindex="-1"/></td>';
            new_row_str = new_row_str + '</tr>';
            $('#invoice-item-list').append(new_row_str);
            $('#max-row').val(new_max_row);
        }

}

function printInvoice(){
    var invoice_list = null;
    var check_all = true,
        invoice_code = $('#invoice-code').val().substring(3),
        invoice_date = null,
        delivery_date = null,
        customer_code = null,
        customer_name = null,
        district_code = null,
        car_no = null
        ;


    /*if (invoice_list== null && !check_all){
        alert("請選擇列印的發票");
        return;
    }*/

    var win = window.open(url+'printInvoice/'+check_all+'/'+invoice_list+'/'+invoice_code+'/'+invoice_date+'/'+delivery_date+'/'+customer_code+'/'+customer_name+'/'+district_code+'/'+car_no,'列印發票',"resizable,scrollbars,status");
    if (win) {
        //Browser has allowed it to be opened
        win.focus();
    } else {
        //Browser has blocked it
        alert('Please allow popups for this website');
    }


}

function hideAlertContainer(){
    $('#alert-container').hide();
}

function getDeliveryDate(){
    var district_code = $('#district-code').val();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
        method:'POST',
        url:url+'getDeliveryDate',
        data:{district_code:district_code},
        success:function(result){
            $('#delivery-date').val(result);
            
        },
        error:function(){
            alert("Error");
        }
    });
}
