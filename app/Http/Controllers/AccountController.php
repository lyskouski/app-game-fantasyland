<?php
// Copyright 2026 The terCAD team. All rights reserved.
// Use of this source code is governed by a CC BY-NC-ND 4.0 license that can be found in the LICENSE file.

namespace App\Http\Controllers;

final class AccountController extends Controller
{
    public function changePasswordForm()
    {
        return view('account_password');
    }

    public function changePassword()
    {
        $this->post('cgi/pl_change_pass.php');
        return redirect('/cgi/show_info.php');
    }

    public function changeEmailForm()
    {
        return view('account_email');
    }

    public function changeEmail()
    {
        $this->post('cgi/pl_change_email.php');
        return redirect('/cgi/show_info.php');
    }

    public function deleteAccountForm()
    {
        return view('account_delete');
    }

    public function deleteAccount()
    {
        $this->post('cgi/pl_delete.php');
        return redirect('/');
    }
}
