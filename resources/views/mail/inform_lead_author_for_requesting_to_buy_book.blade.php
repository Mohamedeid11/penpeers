<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8" />
</head>

<body style="margin: 0;">
    <style>
        @font-face {
            font-family: 'Open Sans';
            src: url("{{asset('fonts/OpenSans-Regular.ttf')}}") format("truetype");
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: Oswald;
            src: url("{{asset('fonts/Oswald-Regular.ttf')}}") format("truetype");
            font-weight: 400;
            font-display: swap;
        }
        .ExternalClass {
            width: 100%;
        }
    </style>
    <table cellpadding="0" cellspacing="0" border="0" style="
            max-width: 700px;
            box-sizing: border-box;
            margin: 0 auto;
            text-align: center;
            width: 100%;
            color: #262626;
            font-family: 'Open Sans', sans-serif;">
        <!-- Header -->
        <tr>
            <td style="padding: 25px 10px 40px; background: #1f1710">
                <a href="{{route('web.get_landing')}}" style="display: block" target="_blank">
                    <img src="{{asset('images/web/logo/logo2.png')}}" alt="Logo" width="150">
                </a>

                <p style="
                    color: #fff;
                    margin-bottom: 0;
                    font-size: 20px;
                    line-height: 30px;
                    text-align: center;
                    font-family: 'Open Sans', sans-serif;">
                    The first platform for authors and co-authors
                </p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 40px 10px">
                <table cellpadding="0" cellspacing="0" border="0" style="width: 100%">
                    <!-- Welcome -->
                    <tr>
                        <td style="padding-bottom: 40px">
                            <h1 style="
                            margin: 0;
                            line-height: 34px;
                            font-family: Oswald, sans-serif;
                            font-size: 28px;
                            text-align: center;">
                            Welcome to <a style="color: #ce7852; text-decoration: underline;"
                                href="{{ route('web.get_landing') }}"> PenPeers.com </a>
                            </h1>
                        </td>
                    </tr>

                    <!-- Name -->
                    <tr>
                        <td>
                            <p style="
                            margin: 0;
                            font-size: 20px;
                            line-height: 30px;
                            text-align: center;
                            font-family: 'Open Sans', sans-serif;">Dear <strong>{{$lead_author_name}}<strong>,</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding-bottom: 40px">
                            <p style="margin: 0;
                            font-size: 20px;
                            line-height: 30px;
                            text-align: center;
                            font-family: 'Open Sans', sans-serif;">

                                You have a new buying request for
                                <a style="color: #ce7852; text-decoration: underline;" href="{{ route('web.view_book', ['slug' => $slug]) }}"><strong>{{ $book_name }}</strong></a>
                                book,
                                you can check all buying requests from <a style="color: #ce7852; text-decoration: underline;" href=" {{ $button }}"> here </a> .<br>
                            </p>
                        </td>
                    </tr>

                    <!-- Thank you -->
                    <tr>
                        <td style="padding-bottom: 20px">
                            <p style="margin: 0;
                            font-size: 20px;
                            line-height: 30px;
                            text-align: left;
                            font-weight: 700;
                            font-family: 'Open Sans', sans-serif;">Thank you.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="
            max-width: 700px;
            padding: 8px 0;
            color: #000;
            margin: 0 auto;
            font-family: Futura;">
        <tr>
            <td>
                <hr>
            </td>
        </tr>
        <tr>
            <td style="font-size: 13px">
                If you have any questions please feel free to contact us at support@penpeers.com
            </td>
        </tr>
    </table>
</body>

</html>
