<?php

namespace App\Http\Controllers;

use App\User;
use App\Dashboard;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;

class DashboardController extends Controller
{
    public function faceRecognition()
    {
        
        $client = new Client();
        
        $usersFound = [];
        if (request()->hasFile('file')) {
            $file = request()->file('file');

            $response = $client->post(url('http://face-recognition:8888/upload'), [
                'multipart' => [
                    [
                        'name'     => 'filearg',
                        'filename' => $file->getClientOriginalName(),
                        'Mime-Type'=> $file->getClientMimeType(),
                        'contents' => fopen($file->path(), "r"),
                    ],
                ]
            ]);
            $file->move("uploads/", $file->getClientOriginalName());
            $usersFound = json_decode($response->getBody());
            if (!empty($usersFound)) {
                usort($usersFound, function ($a, $b) {
                        return $a->distance > $b->distance;
                    }
                );
            }   
        }

        return $usersFound;
    }


    public function dashboard()
    {
        $usersFound = $this->faceRecognition();

        if (!empty($usersFound)) {
            $dashboard = Dashboard::where('owner_id', $usersFound[0]->id)->first();
            return view('dashboard', ['content' => $dashboard->content]);
        }

        if (request()->userId != null) {
            $dashboard = Dashboard::where('owner_id', request()->userId)->first();
            return view('dashboard', ['content' => $dashboard->content]); 
        }
           
        return view('welcome');
        
    }


}
