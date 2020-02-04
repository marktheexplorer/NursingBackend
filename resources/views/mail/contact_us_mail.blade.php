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
            <p style="color: #555;line-height: 25px;margin: 0;"> {{ $objDemo->userName }} has contacted you.</p><br/><br/>
        </td>
    </tr> 
    <tr>
        <td>
            <p style="color: #555;line-height: 25px;margin: 0;"> Message: {{ $objDemo->message }}</p><br/><br/>
        </td>
    </tr>       
   <tr>
        <td><br/><br/>
            <p style="color: #555;line-height: 25px;margin: 0;">Regards,</p>
            <p style="color: #555;line-height: 25px;margin: 0;">24*7 Nursing Care Team,</p>
        </td>
    </tr>    
</tbody>    
@endsection
