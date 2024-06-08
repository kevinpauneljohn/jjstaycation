let userModal = $('#user-modal');

let userId;
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
//add user//
$(document).on('click','.add-user-btn',function(){
    $('.text-danger').remove();
    userModal.find('.form-submit').removeAttr('id').attr('id','add-user-form');
    userModal.find('.modal-title').text('Add Customer');
    userModal.find('#username').attr('disabled',false).attr('name','username');
    userModal.find('#email').attr('disabled',false).attr('name','email');
    $('#add-user-form').trigger('reset');
    $('#add-user-form').find('#roles').val('').change();
    $('.password-section').html('<div class="col-lg-6">\n' +
        '                                    <div class="form-group password">\n' +
        '                                        <label for="password">Password</label><span class="required">*</span>\n' +
        '                                        <input type="password" name="password" class="form-control" id="password">\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <div class="col-lg-6">\n' +
        '                                    <div class="form-group password_confirmation">\n' +
        '                                        <label for="password_confirmation">Confirm Password</label><span class="required">*</span>\n' +
        '                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">\n' +
        '                                    </div>\n' +
        '                                </div>');
    userModal.modal('toggle');
});

let addForm = $('#add-user-form');
$(document).on('submit','#add-user-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/users',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('#add-user-form').find('input[type=submit]').attr('disabled',true).val('Saving...');
        },success: function(response){
            console.log(response);

            if(response.success === true)
            {
                let table = $('#user-list').DataTable();
                table.ajax.reload(null, false);
                $('#add-user-form').trigger('reset');
                $('#add-user-form').find('#roles').val('').change();
                Toast.fire({
                    type: 'success',
                    title: response.message
                })
            }
            $('#add-user-form').find('input[type=submit]').attr('disabled',false).val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            addForm.find('input, select, textarea').attr('disabled',false);
            $('#add-user-form').find('input[type=submit]').attr('disabled',false).val('Save');
        }
    });
    clear_errors('firstname','lastname','date_of_birth','username','email','password','password_confirmation','roles');
});
//end add user//

//edit user //
let editForm = $('#edit-user-form');
$(document).on('click','.edit-user-btn',function(){
    userId = this.id;
    $('.text-danger').remove();
    $('.password-section').html('');
    userModal.find('.form-submit').removeAttr('id').attr('id','edit-user-form');
    userModal.find('.modal-title').text('Edit Customer');
    userModal.find('#username, #email').attr('disabled',true).removeAttr('name');

    $.ajax({
        'url' : '/users/'+userId+'/edit',
        'type' : 'GET',
        beforeSend: function(){

        },success: function(response){
            console.log('kevin')
            $.each(response,function(key, value){
                $('#'+key).val(value).change();
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });

    userModal.modal('toggle');
});

$(document).on('submit','#edit-user-form',function(f){
    f.preventDefault()
    let data = $(this).serializeArray();
    $.ajax({
        'url' : '/users/'+userId,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('#edit-user-form').find('input[type=submit]').attr('disabled',true).val('Saving...');
        },success: function(response){
            console.log(response);
            if(response.success === true)
            {
                let table = $('#user-list').DataTable();
                table.ajax.reload(null, false);
                Toast.fire({
                    type: 'success',
                    title: response.message
                })
            }else if(response.success === false){
                Toast.fire({
                    type: 'warning',
                    title: response.message
                })
            }
            $('#edit-user-form').find('input[type=submit]').attr('disabled',false).val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            $('#edit-user-form').find('input[type=submit]').attr('disabled',false).val('Save');
        }
    });
    clear_errors('firstname','lastname','date_of_birth','roles')
});
//end edit user //

//delete user//
$(document).on('click','.delete-user-btn',function(){
    userId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove Customer?',
        text: data[0],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {

            $.ajax({
                'url' : '/users/'+userId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){

                    if(response.success === true)
                    {
                        let table = $('#user-list').DataTable();
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
//end delete user//
