<x-auth-layout>

    <!-- ==== registration section start ==== -->
    <section class="registration clear__top">
        <div class="container">
            <div class="registration__area">
                <h4 class="neutral-top">Forgot Password</h4>

                <form action="{{ route('password.email') }}" method="post" class="form__login">
                    @csrf
                    <div class="input input--secondary">
                        <label for="loginMail">Email*</label>
                        <input type="email" name="email" id="loginMail" placeholder="Enter your email" value="{{ old('email') }}" />
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('email')}}</b></small></small></p></div>
                    </div>

                    <div class="input__button">
                        <button type="submit" class="button button--effect">Send Code</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- ==== #registration section end ==== -->

</x-auth-layout>

<x-error-message />
<x-success-message />
