<?php
namespace App\Controllers;

use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\User;

class LoginController extends BaseController {

    public function loginForm()
    {
        $this->initializeSession();
        return $this->render('login-form');
    }

    public function login()
    {
        $this->initializeSession();
        $data = $_POST;
       
        $userObj = new User();
       
        // Verify if the user exists and credentials are correct
        if ($userObj->verifyAccess($data['email'], $data['password'])) {
            // Fetch user details after verifying access
            $user = $userObj->getUserByEmail($data['email']);
           
            // Set session data for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
           
            // Redirect or render the next page
            return $this->render('pre-exam', $user);
        } else {
            // Handle login failure
            $data['error'] = "Invalid email or password.";
            return $this->render('login-form', $data);
        }
    }
}
