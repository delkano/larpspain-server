<?php
namespace Controller;

class Auth {
    public function login($f3) {
        if( $f3->exists("POST.grant_type") && $f3->exists("POST.refresh_token")
            && $f3->get("POST.grant_type") === "refresh_token") {
            return $this->refresh_token($f3);
        } else if( $f3->exists("POST.username") && $f3->exists("POST.password")) {
            $email = $f3->get("POST.username");
            $password = $f3->get("POST.password");
            $user = new \Model\User;
            $user->load(["email=?", $email]);
            if($user->validate($password)) {
                //Logged in
                return $this->issue_token($f3, $user);
            }  else
                    $f3->error(401, "Wrong credentials.");
            }
        } else {
            $f3->error(400, "Bad request");
        }

    }

    public function refresh_token($f3) {
        $token = $f3->get("POST.refresh_token");
        $user = new \Model\User;
        $user->load(["refresh_key=?", $token]);
        if(!$user->dry()) {
            return $this->issue_token($f3, $user);
        } else {
            $f3->error(400, "Bad refresh token");
        }

    }

    public function authorize($f3) {
        // In the unlikely chance we call this method after we have already
        // authorized access in the same call, let's take a shortcut
        if($f3->exists("user")) return $f3->user->role;

        $token = $f3->get("HEADERS.Authorization")?:null;
        $token_parts = explode(" ", $token);
        if($token_parts[0] == "Bearer")
            $token = $token_parts[1];
        $user = new \Model\User;
        $user->load(["auth_key=? AND timeout>?", $token, time()]);
        if(!$user->dry()) {
            $f3->set("user", $user);

            return $user->role;
        } else {
            return null;
        }
    }

    public function logout($f3) {
        if($f3->exists("user")) {
            $f3->user->auth_key = "";
            $f3->user->save();
            $f3->unset("user");
        }
    }

    private function issue_token($f3, $user) {
        $auth_key = sha1($user->id."+".$user->email."-".time());
        $refresh_token = sha1($user->email."&%$%&/&".$user->id."sdkammsd".time());
        $user->auth_key = $auth_key;
        $user->refresh_key = $refresh_token;
        $user->timeout = time()+3600;
        $user->save();
        $f3->set('user', $user);

        echo json_encode(array(
            'access_token' => $auth_key,
            'token_type' => 'Bearer',
            'account_id' => $user->id,
            'account_role' => $user->role,
            'expires_in' => 3600,
            'refresh_token' => $refresh_token
        ));
        return;
    }
}
