<div>
    <div class="modal fade" id="booking-details-modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Booking Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="btn-group" style="width: 100%">
                    <button type="button" class="btn btn-primary btn-flat edit-booking" data-toggle="modal" data-target="#edit-booking-modal">Edit Booking</button>
                    <button type="button" class="btn btn-info btn-flat edit-customer">Edit Customer</button>
                    <button type="button" class="btn btn-danger btn-flat cancel-booking">Cancel Booking</button>
                    <button type="button" class="btn btn-warning btn-flat" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <form id="edit-customer-form">
        @csrf
        <div class="modal fade" id="customer-modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Edit Customer Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <section class="mb-4">
                            <h4>Customer Info</h4>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group firstname">
                                        <label for="firstname">First Name</label><span class="required">*</span>
                                        <input type="text" name="firstname" class="form-control" id="firstname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group middlename">
                                        <label for="middlename">Middle Name</label> <i class="text-muted">(optional)</i>
                                        <input type="text" name="middlename" class="form-control" id="middlename">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group lastname">
                                        <label for="lastname">Last Name</label><span class="required">*</span>
                                        <input type="text" name="lastname" class="form-control" id="lastname">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group email">
                                        <label for="email">Email</label> <i class="text-muted">(optional)</i>
                                        <input type="text" name="email" class="form-control" id="email">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mobile_number">
                                        <label for="mobile_number">Mobile Number</label><span class="required">*</span>
                                        <input type="text" name="mobile_number" class="form-control" id="mobile_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group facebook_url">
                                        <label for="facebook_url">Facebook URL</label> <i class="text-muted">(optional)</i>
                                        <input type="text" name="facebook_url" class="form-control" id="facebook_url">
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="btn-group" style="width: 100%">
                        <button type="submit" class="btn btn-primary btn-flat" data-toggle="modal">Save</button>
                        <button type="button" class="btn btn-warning btn-flat" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>


</div>
@push('booking-details')
    @php
        // bookingId var located in add-booking.blade.php as a javascript global variable
       // bookingInformation var located in add-booking.blade.php as a javascript global variable
    @endphp
    @once
        <script>
            $(document).on('click','.edit-booking',function(){
                $(this).closest('.modal').modal('toggle'); @php //close the booking details modal @endphp
                $('input[name=preferred_date]').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 30,
                    startDate: moment(bookingInformation.start).format('MM-DD-YYYY hh:mm a'),
                    endDate: moment(bookingInformation.end).format('MM-DD-YYYY hh:mm a'),
                    minDate: new Date(),
                    locale: {
                        format: 'MM/DD/YYYY hh:mm A'
                    },isInvalidDate: function (date){
                        return blocked_dates.reduce(function(bool, range) {
                            return bool || (date >= range.start && date <= range.end);
                        }, false);
                    }
                });

                // console.log(moment(bookingInformation.end).format('MM-DD-YYYY hh:mm a'))

                let editBookingForm = $('#edit-booking-form');
                let packageVal = $("#package option:contains("+bookingInformation.title+")").val()

                editBookingForm.find('table').remove();

                editBookingForm.find('#package').val(packageVal).change();
                editBookingForm.find('#pax').val(bookingInformation.pax);
                editBookingForm.find('#total_amount').val(bookingInformation.total_amount);
                editBookingForm.find('#status').val(bookingInformation.status);
                editBookingForm.find('#remarks').val(bookingInformation.remarks);
                editBookingForm.find('h4').after('<table class="table table-bordered"><tr>' +
                    '<td>Guest Name</td><td>'+bookingInformation.customer.firstname+' '+bookingInformation.customer.lastname+'</td></tr>' +
                    '<tr><td>Email</td><td><a href="mailto:'+bookingInformation.customer.email+'">'+bookingInformation.customer.email+'</a> </td></tr>' +
                    '<tr><td>Contact Number</td><td><a href="tel:'+bookingInformation.customer.mobile_number+'">'+bookingInformation.customer.mobile_number+'</a></td></tr></table>');
            });

            $(document).on('click','.cancel-booking',function(){
                removeBooking();
            });

            @if(auth()->user()->can('edit customer'))
                let customerModal = $('#customer-modal');
                $(document).on('click','.edit-customer',function(){
                    bookingDetailsModal.modal('toggle');
                    customerModal.modal('toggle');

                    // console.log(bookingInformation)
                    $.ajax({
                        url: '/customers/'+bookingInformation.customer_id+'/edit',
                        type: 'get',
                        beforeSend: function(){
                            customerModal.find('.modal-content').prepend('<div class="overlay">\n' +
                                '                <i class="fas fa-2x fa-sync fa-spin"></i>\n' +
                                '            </div>');
                        }
                    }).done(function(response){
                        console.log(response)
                        $.each(response, function(key, value){
                            customerModal.find('#'+key).val(value)
                        })
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                    }).always(function(){
                        customerModal.find('.overlay').remove();
                    })
                });

                $(document).on('submit','#edit-customer-form',function(form){
                    form.preventDefault();
                    let data = $(this).serializeArray();

                    $.ajax({
                        url: '/customers/'+bookingInformation.customer_id,
                        type: 'put',
                        data: data,
                        beforeSend: function(){
                            customerModal.find('.is-invalid').removeClass('is-invalid');
                            customerModal.find('.errors').remove();
                            customerModal.find('.modal-content').prepend('<div class="overlay">\n' +
                                '                <i class="fas fa-2x fa-sync fa-spin"></i>\n' +
                                '            </div>');
                        }
                    }).done(function(response){
                        console.log(response)
                        if(response.success === true)
                        {
                            Toast.fire({
                                type: 'success',
                                title: response.message
                            });
                            displayCalendar(events);
                        }else{
                            Toast.fire({
                                type: 'warning',
                                title: response.message
                            });
                        }
                    }).fail(function(xhr, status, error){
                        console.log(xhr)
                        $.each(xhr.responseJSON.errors, function(key, value){
                            customerModal.find('#'+key).addClass('is-invalid').closest('.'+key).append('<p class="text-danger errors">'+value+'</p>');
                        });
                    }).always(function(){
                        customerModal.find('.overlay').remove();
                    });
                });
            @endif

        </script>

    @endonce
@endpush
