//global variable
let popUp = $('#add-permission-modal');
//add permission form //
$(document).on('click','.add-permission-btn',function(){

    popUp.find('.form-submit').trigger('reset');
    popUp.find('.select2').val('').trigger('change');
    popUp.find('.modal-title').text('Add New Permission');
    popUp.find('.form-submit').removeAttr('id').attr('id','add-permission-form');
});
$(document).on('submit','#add-permission-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();
    // let form = $(this);

    $.ajax({
        'url' : '/permissions',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            popUp.find('.form-submit input, .form-submit select').attr('disabled',true);
        },success: function(response){
            // console.log(response);

            if(response.success === true)
            {
                let table = $('#permission-list').DataTable();
                let form = $('#'+f.target.id);
                form.trigger('reset');
                form.find('.select2').val('').trigger('change');
                // console.log(f.target.id)
                table.ajax.reload(null, false);

                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: 'Permission has been saved',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            errorDisplay(response);
            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        },error: function (xhr, status, error){
            console.log(xhr);
            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        }
    });
    clear_errors('permission','roles')
});
// end add permission form //

// edit permission //
let permissionId;
$(document).on('click','.edit-permission-btn', function(){
    permissionId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    popUp.find('.select2').val('').trigger('change');

    popUp.find('.text-danger').remove();
    popUp.find('.modal-title').text('Edit Permission');
    popUp.find('.form-submit').removeAttr('id').attr('id','edit-permission-form');
    popUp.find('.form-submit #permission').val(data[0]);
    popUp.modal('toggle');

    $.ajax({
        'url' : '/permissions/'+permissionId,
        'type' : 'GET',
        beforeSend: function(){
            popUp.find('.form-submit input, .form-submit select').attr('disabled',true);
        },success: function(response){
            // console.log(response);

            popUp.find('.select2').val(response).trigger('change');

            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        }
    });
});

$(document).on('submit','#edit-permission-form',function(f){
    f.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/permissions/'+permissionId,
        'type': 'PUT',
        'data' : data,
        beforeSend: function(){
            popUp.find('.form-submit input, .form-submit select').attr('disabled',true);
        },success: function (response){

            if(response.success === true)
            {
                let table = $('#permission-list').DataTable();
                table.ajax.reload(null, false);

                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: 'Permission has been updated!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }else{
                Swal.fire({
                    position: 'top-end',
                    type: 'warning',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            errorDisplay(response);

            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        },error: function (xhr, status, error){
            console.log(xhr);
            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        }
    });
    clear_errors('permission')
})
// end edit permission //

// delete permission //
$(document).on('click','.delete-permission-btn',function(){
    permissionId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove permission?',
        text: data[0],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {

            $.ajax({
                'url' : '/permissions/'+permissionId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){

                    if(response.success === true)
                    {
                        let table = $('#permission-list').DataTable();
                        table.ajax.reload(null, false);
                        Swal.fire(
                            'Removed!',
                            response.message,
                            'success'
                        )
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
// end delete permission //


