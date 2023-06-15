$(document).ready(function() {
    $("input").focus(function() { $(this).select(); } );
});

function clearForm(){
     $('#form').trigger("reset");
}

function clearForm2(){
    $('#form2').trigger("reset");
}

function select(element){
    element.select();
}

function checkAll(){
    var checked = $('#check-all').is(':checked');
    $('input[type=checkbox]').prop('checked', checked);
}

function autocomplete(url,element){
    element.typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name:'autocomplete',
            limit: 100,
            source:  function (query, process) {
                $.ajax({
                    method:'GET',
                    url:url,
                    data:{query:query},
                    async:false,
                    success:function(result){
                        list = result;
                        //console.log(result);
                    },
                    error:function(){
                        alert("Error");
                    }
                });
                return process(list);
            },
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    'Nothing found.'
                ],

                suggestion: function(data) {
                return '<div class="list-group-item">' + data.name +" || <span style='font-size:smaller'>"+ data.descr + '</span></div>'
                }
            }
        }

    );
}
