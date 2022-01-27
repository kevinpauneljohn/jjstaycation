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
                    <button type="button" class="btn btn-primary btn-flat edit-booking" data-toggle="modal" data-target="#booking-modal">Edit</button>
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
    @once
        <script>
            $(document).on('click','.edit-booking',function(){
                $(this).closest('.modal').modal('toggle');
                $('#booking-modal').find('.form-submit').attr('id','edit-booking-form');
                // $('#booking-modal').modal('toggle');

                // bookingId var located in add-booking.blade.php as a javascript global variable
                console.log(bookingId)
            });
        </script>
    @endonce
@endpush
