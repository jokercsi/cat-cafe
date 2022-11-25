<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// form request
use App\Http\Requests\ContactRequest;

// mail form
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactAdminMail;

class ContactController extends Controller
{
    public function index() 
    {
        return view('contact.index');
    }

    function sendMail(ContactRequest $request) {
        $validated = $request->validated();
    
        // これ以降の行は入力エラーがなかった場合のみ実行されます
        // 登録処理(実際はメール送信などを行う)
        Mail::to('admin@example.com')->send(new ContactAdminMail($validated));
        //Log::debug($validated['name']. 'さんよりお問い合わせがありました');
        return to_route('contact.complete');
    }

    public function complete()
    {
        return view('contact.complete');
    }
}
