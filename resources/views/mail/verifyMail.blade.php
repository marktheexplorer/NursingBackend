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
            <p>Your One Time Password to verify email: {{ $user->otp }}</p><br>
        </td>
    </tr>
</tbody>
@endsection
