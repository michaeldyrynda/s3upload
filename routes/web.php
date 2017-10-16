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
        'policy' => $policy = createPolicy(),
        'signature' => signPolicy($policy),
    ]);
});

Route::get('/s3-upload', function () {
    return request()->all();
});

function createPolicy() {
    return base64_encode(json_encode([
        'expiration' => now()->addHour()->format('Y-m-d\TG:i:s\Z'),
        'conditions' => [
            ['bucket' => config('filesystems.disks.s3.bucket')],
            ['acl' => 'private'],
            ['starts-with', '$key', ''],
            ['eq', '$success_action_redirect', url('/s3-upload')],
        ],
    ]));
}

function signPolicy($policy)
{
    return with($policy, function ($policy) {
        return base64_encode(hash_hmac('sha1', $policy, config('filesystems.disks.s3.secret'), true));
    });
}
