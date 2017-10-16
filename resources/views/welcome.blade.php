<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
    </head>
    <body>
        <form method="post" action="https://{{ config('filesystems.disks.s3.bucket') }}.s3.amazonaws.com" enctype="multipart/form-data">
            <input type="hidden" name="AWSAccessKeyId" value="{{ config('filesystems.disks.s3.key') }}">
            <input type="hidden" name="acl" value="private">
            <input type="hidden" name="key" value="${filename}">
            <input type="hidden" name="policy" value="{{ $policy }}">
            <input type="hidden" name="success_action_redirect" value="{{ url('/s3-upload') }}">
            <input type="hidden" name="signature" value="{{ $signature }}">
            <input type="file" name="file">
            <button type="submit">Upload</button>
        </form>
    </body>
</html>
