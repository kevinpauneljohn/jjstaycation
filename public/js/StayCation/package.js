let packageId;
let packageModal = $('#package-modal');
let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

//color picker with addon
$('.my-colorpicker2').colorpicker();
$('.my-colorpicker2').on('colorpickerChange', function(event) {
    $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
})

$(document).on('click','.add-staycation-package-btn',function(){
    packageModal.find('.form-submit').removeAttr('id').attr('id','add-package-form');
    packageModal.find('.modal-title').text('Add New Package');
    packageModal.find('.form-submit').trigger('reset');
    $('.form-submit').find('#color').val('#3788d8').change();
    packageModal.modal('toggle');
});
0

//add package form//
let addForm = $('#add-package-form');
$(document).on('submit','#add-package-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/packages',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            addForm.find('input, textarea').attr('disabled',true);
            addForm.find('.submit-package-btn').attr('disabled',true).val('Saving...');
        },success: function(response){

            if(response.success === true)
            {
                let table = $('#packages-list').DataTable();
                table.ajax.reload(null, false);
                addForm.trigger('reset');

                Toast.fire({
                    type: 'success',
                    title: response.message
                });
            }

            addForm.find('input, textarea').attr('disabled',false);
            addForm.find('.submit-package-btn').attr('disabled',false).val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            addForm.find('input, textarea').attr('disabled',false);
            addForm.find('.submit-package-btn').attr('disabled',false).val('Save');
        }
    });
    clear_errors('name','pax','amount','days','time_in','time_out','color');
});

//end add package form //

//edit package form //
$(document).on('click','.edit-staycation-package-btn',function(){
    packageId = this.id;
    packageModal.find('.modal-title').text('Edit Package');
    packageModal.find('.form-submit').removeAttr('id').attr('id','edit-package-form');

    $.ajax({
        'url' : '/packages/'+packageId+'/edit',
        'type' : 'GET',
        beforeSend: function(){

        },success: function(response){
            $('.form-submit').find('#name').val(response.name);
            $('.form-submit').find('#pax').val(response.pax);
            $('.form-submit').find('#amount').val(response.amount);
            $('.form-submit').find('#remarks').val(response.remarks);
            $('.form-submit').find('#days').val(response.days);
            $('.form-submit').find('#time_in').val(response.time_in);
            $('.form-submit').find('#time_out').val(response.time_out);
            $('.form-submit').find('#color').val(response.color).change();
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    packageModal.modal('toggle');
});


$(document).on('submit','#edit-package-form',function(f){
    f.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/packages/'+packageId,
        'type': 'PUT',
        'data' : data,
        beforeSend: function(){
            $('#edit-package-form').find('input, textarea').attr('disabled',true);
            $('#edit-package-form').find('.submit-package-btn').attr('disabled',true).val('Saving...');
        },success: function(response){
            if(response.success === true)
            {
                let table = $('#packages-list').DataTable();
                table.ajax.reload(null, false);
                Toast.fire({
                    type: 'success',
                    title: response.message
                });
            }else if(response.success === false){
                Toast.fire({
                    type: 'warning',
                    title: response.message
                });
            }
            $('#edit-package-form').find('input, textarea').attr('disabled',false);
            $('#edit-package-form').find('.submit-package-btn').attr('disabled',false).val('Save');
        },error: function(xhr, status, error){
            console.log(xhr);
            errorDisplay(xhr.responseJSON.errors);
            $('#edit-package-form').find('input, textarea').attr('disabled',false);
            $('#edit-package-form').find('.submit-package-btn').attr('disabled',false).val('Save');
        }
    });
    clear_errors('name','days','time_in','time_out','pax','amount','color')
});
//end edit package //

//delete package//
$(document).on('click','.delete-staycation-btn',function(){
    packageId = this.id;

    $tr = $(this).closest('tr');

    let data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    Swal.fire({
        title: 'Remove Package?',
        text: data[0],
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove it!'
    }).then((result) => {
        if (result.value === true) {

            $.ajax({
                'url' : '/packages/'+packageId,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                beforeSend: function(){

                },success: function(response){

                    if(response.success === true)
                    {
                        let table = $('#packages-list').DataTable();
                        table.ajax.reload(null, false);
                        Toast.fire({
                            type: 'success',
                            title: response.message
                        });
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });
        }
    })
});
//end delete package//
