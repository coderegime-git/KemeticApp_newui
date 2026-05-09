@extends('web.default.layouts.email')

@section('body')
<td valign="top" style="padding: 30px; font-family: Arial, sans-serif; background-color: #000000;">
    
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:auto; background:#111111; border-radius:12px; overflow:hidden; border:1px solid #d4af37;">
        
        <!-- Header with Logo -->
        <tr>
            <td style="background: #4CAF50; padding: 20px; text-align: center; color: #fff; font-size: 20px; font-weight: bold;">
                {{ $generalSettings['site_name'] ?? 'Your Website' }} </td>
        </tr>

        <!-- Body -->
        <tr>
            <td style="padding: 40px 30px; text-align: center; color:#ffffff;">
                
                <h2 style="margin-bottom: 10px; color:#d4af37;">
                    Verify Your Email
                </h2>
                
                <p style="color: #cccccc; font-size: 14px;">
                    Use the OTP below to complete your registration.
                    <br>This OTP is valid for 10 minutes.
                </p>

                <!-- OTP BOX -->
                <div style="margin: 35px 0;">
                    <span style="
                        display: inline-block;
                        padding: 18px 35px;
                        font-size: 30px;
                        letter-spacing: 8px;
                        font-weight: bold;
                        color: #000;
                        background: linear-gradient(45deg, #d4af37, #f7e98e);
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(212,175,55,0.5);
                    ">
                        {{ $otp }}
                    </span>
                </div>

                <p style="font-size: 13px; color:#aaaaaa;">
                    If you didn’t request this, you can safely ignore this email.
                </p>

            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background:#000; padding:15px; text-align:center; font-size:12px; color:#888; border-top:1px solid #d4af37;">
                © {{ date('Y') }} {{ $generalSettings['site_name'] ?? '' }}. All rights reserved.
            </td>
        </tr>

    </table>

</td>
@endsection