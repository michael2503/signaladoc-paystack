<x-auth-layout>

    <!-- ==== registration section start ==== -->
    <section class="registration clear__top">
        <div class="container">
            <div class="registration__area">
                <h4 class="neutral-top">Set New Password</h4>

                <form action="{{ route('password.store') }}" method="post" class="form__login">
                    @csrf

                    <div class="input input--secondary">
                        <label for="loginPass">New Password*</label>
                        <input type="password" name="password" id="loginPass" placeholder="New Password"/>
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('password')}}</b></small></small></p></div>
                    </div>

                    <input type="hidden" name="token" value="{{ $request }}" class="mb-0">
                    <input type="hidden" name="email" value="{{ $email }}" class="mb-0">

                    <div class="input input--secondary">
                        <label for="loginPass">Retype New Password*</label>
                        <input type="password" name="password_confirmation" id="loginPass" placeholder="Retype New Password"/>
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('password_confirmation')}}</b></small></small></p></div>
                    </div>

                    <div class="input__button">
                        <button type="submit" class="button button--effect">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- ==== #registration section end ==== -->

</x-auth-layout>

<x-error-message />
<x-success-message />
