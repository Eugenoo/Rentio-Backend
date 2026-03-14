<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Your Payment</title>
</head>

<body style="margin:0; padding:0; background-color:#f5f7fb; font-family:Arial, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; padding:40px; box-shadow:0 4px 15px rgba(0,0,0,0.05);">

                <tr>
                    <td align="center">
                        <h2 style="margin-top:0; color:#2c3e50;">Complete Your Payment</h2>
                    </td>
                </tr>

                <tr>
                    <td style="color:#555; font-size:16px;">

                        <p>Hello <strong>{{$mailData['user_name']}}</strong>,</p>

                        <p>
                            You have successfully reserved the vehicle <strong>{{$mailData['car_name']}}</strong>.
                            To confirm your reservation, please complete the payment.
                        </p>

                    </td>
                </tr>

                <tr>
                    <td>

                        <table width="100%" cellpadding="10" cellspacing="0" style="background:#f8fafc; border-radius:8px; margin:20px 0; font-size:15px;">
                            <tr>
                                <td><strong>Vehicle:</strong></td>
                                <td>{{$mailData['car_name']}}</td>
                            </tr>

                            <tr>
                                <td><strong>Rental start date:</strong></td>
                                <td>{{$mailData['start_date']}}</td>
                            </tr>

                            <tr>
                                <td><strong>Rental end date:</strong></td>
                                <td>{{$mailData['end_date']}}</td>
                            </tr>

                            <tr>
                                <td><strong>Total price:</strong></td>
                                <td style="color:#2ecc71; font-weight:bold;">
                                    {{$mailData['total_price']}}
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>

                <tr>
                    <td style="color:#666; font-size:14px; padding-top:10px;">

                        <p>
                            Our assistant will contact you shortly with the remaining details.
                        </p>

                        <p>
                            Best regards,<br>
                            <strong>{{ config('app.name') }}</strong>
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
