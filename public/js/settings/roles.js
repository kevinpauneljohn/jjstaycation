//global variable
let popUp = $('#add-role-modal');

//add role form//
$(document).on('click','.add-role-btn',function(){

    popUp.find('.text-danger').remove();
    popUp.find('.form-submit').trigger('reset');
    popUp.find('.modal-title').text('Add New Role');
    popUp.find('.form-submit').removeAttr('id').attr('id','add-role-form');
});

$(document).on('submit','#add-role-form',function(f){
    f.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/roles',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            popUp.find('.form-submit input').attr('disabled',true);
        },success: function(response){
            console.log(response);

            if(response.success === true)
            {
                let table = $('#roles-list').DataTable();
                let form = $('#'+f.target.id);
                form.trigger('reset');
                // console.log(f.target.id)
                table.ajax.reload(null, false);

                Swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            errorDisplay(response);
            popUp.find('.form-submit input, .form-submit select').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
            popUp.find('.form-submit input').attr('disabled',false);
        }
    });
    clear_errors('role')
});

// end add role form //

//edit role //
let roleId;
$(document).on('click','.edit-role-btn',function(){
    roleId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    popUp.find('.text-danger').remove();
    popUp.find('.modal-title').text('Edit Role');
    popUp.find('.form-submit').removeAttr('id').attr('id','edit-role-form');
    popUp.find('.form-submit #role').val(data[0]);
    popUp.modal('toggle');
});

$(document).on('submit','#edit-role-form',function(f){
   f.preventDefault();

   let data = $(this).serializeArray();
   $.ajax({
       'url' : '/roles/'+roleId,
       'type' : 'PUT',
       'data' : data,
       beforeSend: function(){
           popUp.find('.form-submit input').attr('disabled',true);
       },success: function(response){
           // console.log(response);
           if(response.success === true)
           {
               let table = $('#roles-list').DataTable();
               table.ajax.reload(null, false);

               Swal.fire({
                   position: 'top-end',
                   type: 'success',
                   title: response.message,
                   showConfirmButton: false,
                   timer: 1500
               })
           }else if (response.success === false){
               Swal.fire({
                   position: 'top-end',
                   type: 'warning',
                   title: response.message,
                   showConfirmButton: false,
                   timer: 1500
               })
           }

           errorDisplay(response);
           popUp.find('.form-submit input').attr('disabled',false);
       },error: function(xhr, status, error){
           console.log(xhr);
           popUp.find('.form-submit input').attr('disabled',false);
       }
   });
   clear_errors('role');
});
// end edit role

// delete permission //
$(document).on('click','.delete-role-btn',function(){
    roleId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove Role?',
        text: data[0],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {

            $.ajax({
                'url' : '/roles/'+roleId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){

                    if(response.success === true)
                    {
                        let table = $('#roles-list').DataTable();
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
