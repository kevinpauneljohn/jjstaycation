let popUp = $('#owner-modal');
let ownerId;
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

//add owner//
$(document).on('click','.add-owner-btn', function(){
    $('.text-danger').remove();
    $('.form-submit').trigger('reset');
    popUp.find('.form-submit').removeAttr('id').attr('id','add-owner-form');
    popUp.find('.modal-title').text('Add New Owner');
    popUp.find('.username').html('<label for="username">Username</label><span class="required">*</span><input type="text" name="username" class="form-control" id="username">');
    popUp.find('.password').html('<label for="password">Password</label><span class="required">*</span><input type="password" name="password" class="form-control" id="password">');
    popUp.find('.password_confirmation').html('<label for="password_confirmation">Confirm Password</label><span class="required">*</span><input type="password" name="password_confirmation" class="form-control" id="password_confirmation">');
});
$(document).on('submit','#add-owner-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/owners',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            popUp.find('.form-submit input').attr('disabled',true);
        },success: function(response){
            if(response.success === true)
            {
                let table = $('#owners-list').DataTable();
                table.ajax.reload(null, false);

                $('.form-submit').trigger('reset');
                Toast.fire({
                    type: 'success',
                    title: response.message
                })
            }
            popUp.find('.form-submit input').attr('disabled',false);
        },error: function (xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            popUp.find('.form-submit input').attr('disabled',false);
        }
    });
    clear_errors('firstname','lastname','email','username','password')
});
//end of add owner//

//edit owner//
$(document).on('click','.edit-owner-btn',function(){
    ownerId = this.id;
    console.log(ownerId);

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    popUp.find('.form-submit').removeAttr('id').attr('id','edit-owner-form');
    $('#firstname').val(data[0]);
    $('#middlename').val(data[1]);
    $('#lastname').val(data[2]);
    $('#email').val(data[3]);
    $('.text-danger').remove();
    popUp.modal('toggle');
    popUp.find('.modal-title').text('Edit Owner');
    popUp.find('.username, .password, .password_confirmation').html('');
});

$(document).on('submit','.form-submit',function(f){
    f.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/owners/'+ownerId,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            popUp.find('.form-submit input').attr('disabled',true);
        },success: function(response){
            console.log(response);
            if(response.success === true)
            {
                let table = $('#owners-list').DataTable();
                table.ajax.reload(null, false);
                Toast.fire({
                    type: 'success',
                    title: response.message
                })

            }else if(response.success === false){
                Toast.fire({
                    type: 'info',
                    title: response.message
                })
            }
            popUp.find('.form-submit input').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            popUp.find('.form-submit input').attr('disabled',false);
        }
    });
    clear_errors('firstname','lastname','email');
});
//end edit owner//

// delete permission //
$(document).on('click','.delete-owner-btn',function(){
    ownerId = this.id;
    // console.log(ownerId);

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove Owner?',
        text: data[0]+' '+data[1]+' '+data[2],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                'url' : '/owners/'+ownerId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response,status){
                    console.log(response);
                    if(response.success === true)
                    {
                        let table = $('#owners-list').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        })
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
// end delete permission //

//restore owner//
$(document).on('click','.restore-all-owner-btn',function(){
    Swal.fire({
        title: 'Restore All Owner?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Restore All'
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                'url' : '/restore-all-trashed-owners',
                'type' : 'POST',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response,status){

                    if(response.success === true)
                    {
                        let table = $('#owners-list').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        })
                        $('.container-fluid .card-header').load(window.location.href+' .card-header button');
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
//end restore owner//

//restore specific owner//
$(document).on('click','.restore-owner-btn', function(){
    ownerId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Restore Owner?',
        text: data[0]+' '+data[1]+' '+data[2],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, restore it!'
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                'url' : '/restore-trashed-owners/'+ownerId,
                'type' : 'POST',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){
                    if(response.success === true)
                    {
                        let table = $('#owners-list').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        })
                        $('.container-fluid .card-header').load(window.location.href+' .card-header button');
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
})
//end restore specific owner//

//delete permanent owner //
$(document).on('click','.delete-permanent-owner-btn',function(){
    Swal.fire({
        title: 'Permanently Delete All Owner?',
        text: '',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Permanently Delete it!'
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                'url' : '/permanent-trashed-owners',
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){
                    if(response.success === true)
                    {
                        let table = $('#owners-list').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        })
                        $('.container-fluid .card-header').load(window.location.href+' .card-header button');
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
//end delete permanent owner //

//delete permanent specific owner //
$(document).on('click','.delete-owner-specific-btn',function(){
    ownerId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Permanently Delete Owner?',
        text: data[0]+' '+data[1]+' '+data[2],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Permanently Delete it!'
    }).then((result) => {
        if (result.value === true) {
            $.ajax({
                'url' : '/permanent-trashed-owners/'+ownerId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){
                    if(response.success === true)
                    {
                        let table = $('#owners-list').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        })
                        $('.container-fluid .card-header').load(window.location.href+' .card-header button');
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
//end delete permanent specific owner //
