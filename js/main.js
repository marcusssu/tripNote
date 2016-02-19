
$(document).ready(function(){
    $("#edit").click(function(){
        $(this).closest('.input-group').find('.form-control').prop("disabled", false);
    });

    // $("#editform").click(function(){
    //     $('#input').prop("disabled", true);
    // });
});