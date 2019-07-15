<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body style="margin:0px; padding:0px; background:#fff; font-family: 'Roboto', sans-serif; line-height:normal; font-size:14px; color:#000;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 590px;; width:100%; margin:0 auto;background: #fff; padding: 20px;">
        <tbody>
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <img src="{{ asset('mail/email-logo.png') }}" style="width: 590px;">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
            @yield('content')
        <tbody>
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <p style="text-align: center;padding:15px 0;background: #012e6d;color:#fff;">  ©2019 {{ env('APP_NAME') }}  </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>