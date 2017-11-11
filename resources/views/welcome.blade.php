<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.2.0/min/dropzone.min.css" rel="stylesheet">
    </head>
    <body>
        <form method="post" action="https://{{ config('filesystems.disks.s3.bucket') }}.s3.amazonaws.com" class="dropzone" enctype="multipart/form-data">
            <input type="hidden" name="policy" value="{{ $policy }}">
            <input type="hidden" name="x-amz-algorithm" value="AWS4-HMAC-SHA256">
            <input type="hidden" name="x-amz-credential" value="{{ $credential }}">
            <input type="hidden" name="x-amz-date" value="{{ $date->format('Ymd\THis\Z') }}">
            <input type="hidden" name="x-amz-signature" value="{{ $signature }}">

            <input type="hidden" name="acl" value="private">
            <input type="hidden" name="key" value="${filename}">
            <div class="fallback">
                <input type="file" name="file">
                <button type="submit">Upload</button>
            </div>
        </form>
    </body>
</html>
