<x-auth-layout>


    <!-- ==== registration section start ==== -->
    <section class="registration clear__top">
        <div class="container">
            <div class="registration__area">
                <h4 class="neutral-top">Registration</h4>
                <p>Already Registered? <a href="{{ route('login') }}">Login</a></p>
                <form action="{{ route('register') }}" method="post" name="registration__form">
                    @csrf
                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <div class="input input--secondary">
                                <label for="firstName">First Name*</label>
                                <input type="text" name="first_name" id="firstName" placeholder="First Name" value="{{ old('first_name') }}" />
                                <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('first_name')}}</b></small></small></p></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input input--secondary">
                                <label for="lastName">Last Name*</label>
                                <input type="text" name="last_name" id="lastName" placeholder="Last Name" value="{{ old('last_name') }}"/>
                                <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('last_name')}}</b></small></small></p></div>
                            </div>
                        </div>
                    </div>
                    <div class="input input--secondary">
                        <label for="username">Username*</label>
                        <input type="text" name="username" id="username" placeholder="Enter your username" value="{{ old('username') }}" />
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('username')}}</b></small></small></p></div>
                    </div>
                    <div class="input input--secondary">
                        <label for="registrationMail">Email*</label>
                        <input type="email" name="email" id="registrationMail" placeholder="Enter your email" value="{{ old('email') }}" />
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('email')}}</b></small></small></p></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input input--secondary">
                                <label for="regiPass">Password*</label>
                                <input type="password" name="password" id="regiPass" placeholder="Password" />
                                <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('password')}}</b></small></small></p></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input input--secondary">
                                <label for="passCon">Password Confirmation*</label>
                                <input type="password" name="password_confirmation" id="passCon" placeholder="Password Confirm" />
                                <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('password_confirmation')}}</b></small></small></p></div>
                            </div>
                        </div>
                    </div>

                    <div class="input input--secondary">
                        <label for="referral">Referral*</label>
                        @if(Session::has('theRefUsername'))
                        <input type="text" readonly name="referral" id="referral" value="{{ Session::get('theRefUsername') }}" />
                        @else
                        <input type="text" name="referral" id="referral" placeholder="Enter your referral username" value="{{ old('email') }}" />
                        @endif
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('email')}}</b></small></small></p></div>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="condtion" name="agree" />
                            <span class="checkmark"></span>
                            I have read and I agree to the <a href="{{ route('termsCondition') }}">
                                Terms & Condition</a>
                        </label>
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('agree')}}</b></small></small></p></div>
                    </div>
                    <div class="input__button">
                        <button type="submit" class="button button--effect">Create My Account</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- ==== #registration section end ==== -->


</x-auth-layout>
<x-error-message />
<x-success-message />
