<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome', [
        'date' => $date = now(),
        'credential' => $credential = createCredential($date),
        'policy' => $policy = createPolicy($credential),
        'signature' => signPolicy($date, $policy),
    ]);
});

Route::get('/s3-upload', function () {
    return request()->all();
});

function createCredential($date) {
    return vsprintf('%s/%s/%s/s3/aws4_request', [
        config('filesystems.disks.s3.key'),
        $date->format('Ymd'),
        config('filesystems.disks.s3.region'),
    ]);
}

function createPolicy($credential) {
    return base64_encode(json_encode([
        'expiration' => now()->addHour()->format('Y-m-d\TH:i:s\Z'),
        'conditions' => [
            ['bucket' => config('filesystems.disks.s3.bucket')],
            ['acl' => 'private'],
            ['starts-with', '$key', ''],
            ['eq', '$success_action_redirect', url('/s3-upload')],
            ['x-amz-algorithm' => 'AWS4-HMAC-SHA256'],
            ['x-amz-credential' => $credential],
            ['x-amz-date' => now()->format('Ymd\THis\Z')],
        ],
    ]));
}

function signPolicy($date, $policy)
{
    $dateKey = hash_hmac('sha256', $date->format('Ymd'), 'AWS4'.config('filesystems.disks.s3.secret'), true);
    $dateRegionKey = hash_hmac('sha256', config('filesystems.disks.s3.region'), $dateKey, true);
    $dateRegionServiceKey = hash_hmac('sha256', 's3', $dateRegionKey, true);
    $signingKey = hash_hmac('sha256', 'aws4_request', $dateRegionServiceKey, true);

    return hash_hmac('sha256', $policy, $signingKey);
}
