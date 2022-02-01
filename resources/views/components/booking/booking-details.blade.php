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
                    <button type="button" class="btn btn-info btn-flat edit-booking" data-toggle="modal" data-target="#booking-modal">Edit Customer</button>
                    <button type="button" class="btn btn-danger btn-flat cancel-booking">Cancel Booking</button>
                    <button type="button" class="btn btn-warning btn-flat" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


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

                // console.log(bookingInformation)
                let editBookingForm = $('#edit-booking-form');
                let packageVal = $("#package option:contains("+bookingInformation.title+")").val()

                editBookingForm.find('#package').val(packageVal).change();
                editBookingForm.find('#pax').val(bookingInformation.pax);
                editBookingForm.find('#total_amount').val(bookingInformation.total_amount);
                editBookingForm.find('#status').val(bookingInformation.status);
                editBookingForm.find('#remarks').val(bookingInformation.remarks);
            });

            // $(document).on('submit','#edit-booking-form',function(form){
            //     form.preventDefault();
            //     let data = $(this).serializeArray().concat({'name' : 'booking_id','value' : parseInt(bookingId)});
            // });

            $(document).on('click','.cancel-booking',function(){
                removeBooking();
            });
        </script>

    @endonce
@endpush
