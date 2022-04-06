@extends('themes.email.master')

@section('content')
    <table border="0" align="center" cellpadding="0" cellspacing="0" role="presentation" class="row container-padding" width="520" style="width:520px;max-width:520px;">
        <tr>
            <td height="40" style="font-size:40px;line-height:40px;" Simpli>&nbsp;</td>
        </tr>
        <tr>
            <td class="center-text" Simpli align="center" style="font-family:'Catamaran',Arial,Helvetica,sans-serif;font-size:34px;line-height:54px;font-weight:700;font-style:normal;color:#333333;text-decoration:none;letter-spacing:0px;">
                <singleline>
                    <div mc:edit Simpli>
                        <!-- Title -->
                    </div>
                </singleline>
            </td>
        </tr>
        <tr>
            <td height="15" style="font-size:15px;line-height:15px;" Simpli>&nbsp;</td>
        </tr>
        <tr>
            <td class="center-text" Simpli align="center" style="font-family:'Catamaran',Arial,Helvetica,sans-serif;font-size:16px;line-height:26px;font-weight:300;font-style:normal;color:#333333;text-decoration:none;letter-spacing:0px;">
                <singleline>
                    <div mc:edit Simpli>
                        {!! $content !!}
                    </div>
                </singleline>
            </td>
        </tr>
        <tr>
            <td height="40" style="font-size:40px;line-height:40px;" Simpli>&nbsp;</td>
        </tr>
    </table>
@endsection