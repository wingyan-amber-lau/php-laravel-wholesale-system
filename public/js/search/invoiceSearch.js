$(document).ready( function () {
    autocomplete(customer_code_autocomplete_path,$("#customer-code"));

    $('#result').DataTable( {
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
    if ($('#delivery-date').val())
        $('#car-no').prop('readonly','');
    else
    $('#car-no').prop('readonly','readonly');

        //$('#district-code').multiselect();
        $('#delivery-date').datepicker( {format: 'yyyy-mm-dd' });
} );

function printInvoice(){
    var invoice_list = $('#invoice-selected').val() || null;
    var check_all = $('#check-all').is(':checked'),
        invoice_code = $('#invoice-code-search').val() || null,
        invoice_date = $('#invoice-date-search').val() || null,
        delivery_date = $('#delivery-date-search').val() || null,
        customer_code = $('#customer-code-search').val() ||null,
        customer_name = $('#customer-name-search').val() || null,
        district_code = $('#district-code-search').val() || null,
        car_no = $('#car-no-search').val() || null
        ;
    if (invoice_list== null && !check_all){
        alert("請選擇列印的發票");
        return;
    }

    var win = window.open(url+'printInvoice/'+check_all+'/'+invoice_list+'/'+invoice_code+'/'+invoice_date+'/'+delivery_date+'/'+customer_code+'/'+customer_name+'/'+district_code+'/'+car_no,'列印發票',"resizable,scrollbars,status");
    if (win) {
        //Browser has allowed it to be opened
        win.focus();
    } else {
        //Browser has blocked it
        alert('Please allow popups for this website');
    }


}
/* Update selected invoice list*/
function updateInvoiceSelected(invoice_code){
    //clear invoice list if check all button clicked
    if (invoice_code == 'all'){
        $('#invoice-selected').val('');
        return;
    }
    var invoice_list = $('#invoice-selected').val();
    //var checked = $('#'+invoice_code).is(':checked');
    var invoice_arr = [];
    if (invoice_list!="")
        invoice_arr = invoice_list.split(',');
    var index = invoice_arr.indexOf(invoice_code);
    var new_invoice_list = "";
    //if (index>-1 && !checked){
    if (index>-1){
        invoice_arr.splice(index,1);
        new_invoice_list = invoice_arr.join();
        $('#invoice-selected').val(new_invoice_list);
    }
    //else if (checked && invoice_list.indexOf(invoice_code)==-1){
    else if (invoice_list.indexOf(invoice_code)==-1){
        invoice_arr.push(invoice_code);
        new_invoice_list = invoice_arr.join();
        $('#invoice-selected').val(new_invoice_list);
    }
}

/*check all checkbox for datatable*/
function checkAll2(){

    var checked = $('#check-all').is(':checked');
    $('input[type=checkbox]', $('#result').dataTable().fnGetNodes()).prop('checked', checked);
}

function toggleCarNo(element){
    if (element.value){
        $('#car-no').prop('readonly','');
    }
    else {
        $('#car-no').prop('readonly','readonly');
        $('#car-no').val('');
        $('#car-no-search').val('');
    }
}
