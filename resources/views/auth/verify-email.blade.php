<x-auth-layout>

    <style>
        #changeEmailForm{
            display: none
        }
    </style>

    <!-- ==== registration section start ==== -->
    <section class="registration clear__top">
        <div class="container">
            <div class="registration__area">


                <h4 class="neutral-top">Please Verify Your Email</h4>
                <p>
                    An OTP has been sent to your email
                    <a href="javascript:()" onclick="switchForm()">
                       <span class="text-success ml-3" id="getContent">CHANGE</span>
                    </a>
                </p>
                <div id="submitOtpForm">
                    <form action="{{ route('submitMailVerify') }}" method="post" class="form__login">
                        @csrf
                        <div class="input input--secondary">
                            <label for="loginMail">Enter Code*</label>
                            <input type="text" name="code" id="loginMail" placeholder="Enter code here" value="{{ old('code') }}" />
                            <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('code')}}</b></small></small></p></div>
                        </div>

                        <div class="input__button">
                            <button type="submit" class="button button--effect">Verify Email</button>
                        </div>
                    </form>
                </div>

                <div id="changeEmailForm">
                    <form action="{{ route('changeEmail') }}" method="post" class="form__login">
                        @csrf
                        <div class="input input--secondary">
                            <label for="loginMail">New Email*</label>
                            <input type="email" name="email" id="loginMail" placeholder="Enter your email" value="{{ old('email') }}" />
                            <div><p class="text-danger mt-0"><small><small><b>{{$errors->first('email')}}</b></small></small></p></div>
                        </div>

                        <div class="input__button">
                            <button type="submit" class="button button--effect">Change Email</button>
                        </div>
                    </form>
                </div>

                <div class="checkbox login__checkbox mt-4">
                    Don't receive email? <a href="{{ route('resendOTP') }}">Resend</a>
                </div>
            </div>
        </div>
    </section>
    <!-- ==== #registration section end ==== -->

    <script>
        function switchForm(){
            const val = document.getElementById("getContent").innerHTML;
            if(val == 'CHANGE'){
                document.getElementById("changeEmailForm").style.display = 'block';
                document.getElementById("submitOtpForm").style.display = 'none';
                document.getElementById("getContent").innerHTML = 'CANCEL';
            } else {
                document.getElementById("changeEmailForm").style.display = 'none';
                document.getElementById("submitOtpForm").style.display = 'block';
                document.getElementById("getContent").innerHTML = 'CHANGE';
            }

        }
    </script>
</x-auth-layout>

<x-error-message />
<x-success-message />
