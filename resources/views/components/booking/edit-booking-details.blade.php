<div>
    <form id="edit-booking-form">
        @csrf
            <h4>Booking Details</h4>
            <div class="row">
                <idv class="col-lg-12">
                    <div class="form-group preferred_date">
                        <label>Preferred Date</label><span class="required">*</span>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input name="preferred_date" type="text" class="form-control float-right" id="preferred_date" readonly="readonly">
                        </div>
                        <!-- /.input group -->
                    </div>
                </idv>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group package">
                        <label for="package">Package</label><span class="required">*</span>
                        <select class="form-control" name="package" id="package" style="width: 100%;">
                            <option value=""></option>
                            <option value="custom">Custom</option>
                            @foreach($staycation->packages as $package)
                                <option value="{{$package->id}}">{{$package->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group pax">
                        <label for="pax">Number of persons</label><span class="required">*</span>
                        <input type="number" name="pax" class="form-control" id="pax" min="0">
                    </div>
                </div>
            </div>
            <div class="package-details"></div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group total_amount">
                        <label for="total_amount">Total Amount</label><span class="required">*</span>
                        <input type="text" name="total_amount" class="form-control" id="total_amount" inputmode="numeric">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group status">
                        <label for="status">Status</label><span class="required">*</span>
                        <select class="form-control" name="status" id="status" style="width: 100%;">
                            <option value=""></option>
                            @foreach($status as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group occasion">
                        <label for="occasion">Occasion</label> <i class="text-muted">(optional)</i>
                        <select class="form-control" name="occasion" id="occasion" style="width: 100%;">
                            <option value=""></option>
                            @foreach($occasion as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 remarks">
                    <label>Remarks</label> <i class="text-muted">(optional)</i>
                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                </div>
            </div>
        <button type="submit" class="btn btn-primary edit-booking-btn mt-4">Save</button>
        <button type="button" class="btn btn-default float-right mt-4" data-dismiss="modal">Close</button>
    </form>
</div>

@push('edit-booking-form')
    @php
        // bookingId var located in add-booking.blade.php as a javascript global variable
       // bookingInformation var located in add-booking.blade.php as a javascript global variable
    @endphp
    <script>
        $(document).on('submit','#edit-booking-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray().concat({'name' : 'booking_id','value' : parseInt(bookingId)});;

            $.ajax({
                url : '/bookings/'+bookingId,
                type: 'PUT',
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                beforeSend: function(){

                },success: function(response){
                    console.log(response);
                },error: function(xhr, status, error){
                    console.log(xhr);
                    errorDisplayWithParent('edit-booking-form',xhr.responseJSON.errors);
                }
            });
            clear_booking_errors('preferred_date','package','total_amount','status','pax');
        });

        function clear_booking_errors()
        {
            let i;
            for (i = 0; i < arguments.length; i++) {

                if($('#edit-booking-form #'+arguments[i]).val().length > 0){
                    $('#edit-booking-form .'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
                }
            }
        }

    </script>
@endpush
