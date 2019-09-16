@extends('layouts.mail')

@section('content')
<tbody>
    <tr>
        <td>
            <h3> Dear {{ $user->name }},</h3><br/>
        </td>
    </tr>
    <tr>
        <td>
            <p> We have received a request to reset your password</p><br/>
        </td>
    </tr>
    <tr>
        <td>
            <p>Please enter the following password reset code : {{ $user->otp }}</p><br>
        </td>
    </tr>
</tbody>
@endsection
