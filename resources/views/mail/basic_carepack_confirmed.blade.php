@extends('layouts.mail')

@section('content')
<tbody>
    <tr>
        <td>
            <h3> Dear {{ $objDemo->receiver }},</h3><br/>
        </td>
    </tr>
    <tr>
        <td>
            <p style="color: #555;line-height: 25px;margin: 0;"> Congratulations! Your request for caregiver service has been accepted, Now you have to click below button and upload your required documents.</p><br/><br/>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <h3> <a href="{{ env('APP_URL').$objDemo->token }}" style="padding: 10px;border: 1px solid;background-color: #64b1e7;color: #fff;border-radius: 5px;text-decoration: none;" title="click here">Click Here</a></h3><br/><br/>
        </td>
    </tr>
    <tr>
        <td style="">
            <span style="color: #555;margin: 0;font-size: 10px;">if you are unable to click on button, please copy given link and past direct into your browser.</span>
            <span style="color: #555;margin: 0;font-size: 10px;">{{ env('APP_URL').$objDemo->token }}</span>
        </td>
    </tr>
</tbody>    
@endsection
