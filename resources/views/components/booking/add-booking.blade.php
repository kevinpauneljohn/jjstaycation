<div>
        <div class="bs-stepper">
            <form class="form-submit" id="add-booking-form">
                @csrf
                <input type="hidden" name="staycation_id" value="{{$staycation->id}}">
                <div class="bs-stepper-header" role="tablist">
                    <!-- steps here -->
                    <div class="step" data-target="#booking-details">
                        <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" onclick="stepper.to(1)">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">Booking Details</span>
                        </button>
                    </div>
                    <div class="line"></div>
                    <div class="step" data-target="#customer-info">
                        <button type="button" class="step-trigger" role="tab" aria-controls="logins-part" onclick="stepper.to(2)">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">Customer Information</span>
                        </button>
                    </div>


                </div>
                <div class="bs-stepper-content">
                    <!-- your steps content here -->
                    <div id="booking-details" class="content" role="tabpanel" aria-labelledby="information-part-trigger">
                        <section class="mb-4">
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
                                        <input type="number" name="pax" class="form-control" id="pax">
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
                        </section>

                        <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                    </div>
                    <div id="customer-info" class="content" role="tabpanel" aria-labelledby="logins-part-trigger">
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
                        <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                        <button type="submit" class="btn btn-primary add-booking-btn">Save</button>
                    </div>
                </div>
            </form>
        </div>

{{--        <div class="row">--}}
{{--            <div class="col-lg-12">--}}
{{--                <input type="submit" value="Save" class="btn btn-primary add-booking-btn" style="width: 100%;">--}}
{{--            </div>--}}
{{--        </div>--}}

</div>

{{--note: include booking.js is a must--}}
