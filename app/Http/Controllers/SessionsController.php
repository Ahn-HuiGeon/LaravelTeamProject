<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\SessionsRequest;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'destroy']);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(SessionsRequest $request)
    {
        if(!auth()->attempt($request->only('email', 'password'), $request->has('remember'))) {
            return $this->respondError('이메일 또는 비밀번호가 맞지 않습니다.');
        }

        if(!auth()->user()->activated) {
            auth()->logout();
            flash('죄송합니다. 아직 검토가 끝나지 않은 계정입니다.ㅠㅠ');
        }

        flash(auth()->user()->name.'님, 환영합니다.');

        return redirect()->intended();
    }

    protected function respondError($message)
    {
        flash()->error($message);

        return back()->withInput();
    }

    public function destroy()
    {
        auth()->logout();
        flash('또 방문해 주세요.');

        return redirect('/');
    }
}
