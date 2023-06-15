$(document).ready( function () {
    autocomplete(customer_code_autocomplete_path,$("#customer-code"));
    
    $('#delivery-month').datepicker( {
        format: 'yyyy-mm',
        startView: "months", 
        minViewMode: "months"
     });

} );
