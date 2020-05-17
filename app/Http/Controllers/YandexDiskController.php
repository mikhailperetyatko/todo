<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Arhitector\Yandex\Disk;
use Arhitector\Yandex\Client\OAuth;
use App\Storage;
use App\User;
use App\Services\FileYandexDisk;
use App\Jobs\RefreshToken;

class YandexDiskController extends Controller
{   
    public function test()
    {
    }
    
    public function access(Request $request, Storage $storage)
    {
        return redirect('https://oauth.yandex.ru/authorize?response_type=code&client_id=' . config('app.yandexDiskAccount') . '&state=' . $storage->id);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function attach(Request $request)
    {
        //if (! $request->all()) return view('home.disks.yandex.access');
        
        $user = auth()->user();
        $json = FileYandexDisk::getToken($request->code);

        if ($json->access_token) {
            $storage = Storage::findOrFail((int) $request->input('storage_id'));
            if ($storage->owner->id != $user->id) abort(403);
            
            $storage->token = $json->access_token;
            $storage->refresh_token = $json->refresh_token;
            $storage->token_expires_at = Carbon::now()->addSeconds($json->expires_in);
            $storage->save();
            
            $client = new OAuth($storage->token);
            $disk = new Disk($client);
            $resource = $disk->getResource(config('app.yandexDiskResource'));
    
            if (is_null($resource->getProperty('storage_id')) || $user->storages()->where('service', FileYandexDisk::class)->count() < 2) {
                $resource->set([
                  'storage_id' => $storage->id,
                  'user_id' => $user->id,
                ]);
                flash('success');
            } else {
                $storage->delete();
                $user_id = $resource->getProperty('user_id');
                $userStorage = User::where('id', $user_id)->exists() ? User::find($user_id)->name : 'Неизвестный пользователь';
                flash('danger', 'Такое хранилище уже ранее было добавлено в систему. Пользователь, добавивший хранилище - ' . $userStorage);
            }
        } else {
            flash('warning', 'Произошла ошибка при получении токена: ' . $json->error_description);
        }
        
        return redirect('/home/storages/');
/*
        $client = new OAuth($token);
        $disk = new Disk($client);
        $resource = $disk->getResource('disk:/Приложения/db3b7916112b4e29855ad679cea97f92/1');
        dump($resource->upload(__DIR__ . '/TestController.php', true));
*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\YandexDisk  $yandexDisk
     * @return \Illuminate\Http\Response
     */
    public function show(YandexDisk $yandexDisk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\YandexDisk  $yandexDisk
     * @return \Illuminate\Http\Response
     */
    public function edit(YandexDisk $yandexDisk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\YandexDisk  $yandexDisk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YandexDisk $yandexDisk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\YandexDisk  $yandexDisk
     * @return \Illuminate\Http\Response
     */
    public function destroy(YandexDisk $yandexDisk)
    {
        //
    }
}
