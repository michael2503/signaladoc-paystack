<x-auth-layout>

    <!-- ==== registration section start ==== -->
    <section class="registration clear__top">
        <div class="container">
            <div class="registration__area">
                <h4 class="neutral-top">Log in</h4>
                <p>Don't have an account? <a href="{{ route('register') }}">Register here.</a></p>
                <form action="{{ route('login') }}" method="post" class="form__login">
                    @csrf
                    <div class="input input--secondary">
                        <label for="loginMail">Email*</label>
                        <input type="email" name="email" id="loginMail" placeholder="Enter your email" value="{{ old('email') }}" />
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('email')}}</b></small></small></p></div>
                    </div>
                    <div class="input input--secondary">
                        <label for="loginPass">Password*</label>
                        <input type="password" name="password" id="loginPass" placeholder="Password"/>
                        <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('password')}}</b></small></small></p></div>
                    </div>
                    <div class="checkbox login__checkbox">
                        <label>
                            <input type="checkbox" id="remeberPass" name="remeber__pass" value="remember" />
                            <span class="checkmark"></span>
                            Remember Me
                        </label>
                        <a href="{{ route('password.request') }}">Forget Password</a>
                    </div>
                    <div class="input__button">
                        <button type="submit" class="button button--effect">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- ==== #registration section end ==== -->

</x-auth-layout>

<x-error-message />
<x-success-message />
