<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\User;
use App\Models\PasswordResetToken;
use Carbon\Carbon;


class AuthController extends BaseController
{
    protected $helpers = ['url', 'form', 'CIMail', 'CIFunctions '];
    protected $userModel;

    public function loginForm()
    {
        $data = [
            'pageTitle' => 'Login',
            'validation' => null
        ];
        return view('backend/pages/auth/login', $data);
    }
    public function registerForm()
    {
        $data = [
            'pageTitle' => 'register',
            'validation' => null
        ];
        return view('backend/pages/auth/register', $data);
    }

    //login 
    public function loginHandler()
    {
        $loginId = $this->request->getVar('login_id');
        $password = $this->request->getVar('password');

        // Determine si l utilisateur se connecte avec l'email ou username
        $fieldType = filter_var($loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Validations des entrées
        if ($fieldType === 'email') {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|valid_email|is_not_unique[users.email]',
                    'errors' => [
                        'required' => 'Email est requit',
                        'valid_email' => 'veillez saisir une adresse email valide ',
                        'is_not_unique' => 'cette adresse mail n existe pas dans notre systeme'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'mot de passe obligatoire',
                        'min_length' => 'le mot de passe doit contenir 5 caractere minimum',
                        'max_length' => 'le mot de passe ne doit pas excéder 45 caractere'
                    ]
                ]
            ]);
        } else {
            $isValid = $this->validate([
                'login_id' => [
                    'rules' => 'required|is_not_unique[users.username]',
                    'errors' => [
                        'required' => 'nom d utilisateur obligatoire',
                        'is_not_unique' => 'ce nom d utilisateur n existe pas dans notre systeme'
                    ]
                ],
                'password' => [
                    'rules' => 'required|min_length[5]|max_length[45]',
                    'errors' => [
                        'required' => 'mot de passe obligatoire',
                        'min_length' => 'le mot de passe doit contenir 5 caractere minimum',
                        'max_length' => 'le mot de passe ne doit pas excéder 45 caractere'
                    ]
                ]
            ]);
        }

        if (!$isValid) {
            return view('backend/pages/auth/login', [
                'pageTitle' => 'Login',
                'validation' => $this->validator
            ]);
        }


        $userModel = new User();
        $user = $userModel->where($fieldType, $loginId)->first();

        if (!$user) {
            return redirect()->route('admin.login.form')->with('fail', 'User not found')->withInput();
        }



        if (!$this->passwordIsHashed($user['password'])) {

            $hashedPassword = hash::make($password);

            $userModel->update($user['id'], ['password' => $hashedPassword]);
        }

        if (hash::check($password, $user['password'])) {
            CIAuth::setCIAuth($user);
            return redirect()->route('admin.home', $user);
        } else {
            // si le mot de passe est incorrect 
            return redirect()->route('admin.login.form')->with('fail', 'Wrong password')->withInput();
        }
    }

    private function passwordIsHashed($password)
    {
        return password_verify($password, $password);
    }

    public function forgotForm()
    {
        $data = [
            'pageTitle' => 'forgot password',
            'validation' => null
        ];
        return view('backend/pages/auth/forgot', $data);
    }

    public function sendPasswordResetLink()
    {
        $isvalid = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[users.email]',
                'errors' => [
                    'required' => 'adresse Email obligatoire',
                    'valid_email' => 'veuillez vérifier le champ email ,il semble pas valide ',
                    'is_not_unique' => 'cette adresse n existe pas dans notre systeme '
                ],
            ],

        ]);
        if (!$isvalid) {
            return view('backend/pages/auth/forgot', [
                'pageTitle' => 'forgot password',
                'validation' => $this->validator

            ]);
        } else {
            $user = new User();
            $user_info = $user->asObject()->where('email', $this->request->getVar('email'))->first();

            $token = bin2hex(openssl_random_pseudo_bytes(65));
            $password_reset_token = new PasswordResetToken();
            $isOldTokenExists = $password_reset_token->asObject()->where('email', $user_info->email)->first();

            if ($isOldTokenExists) {

                $password_reset_token->where('email', $user_info->email)
                    ->set(['token' => $token, 'created_at' => Carbon::now()])
                    ->update();
            } else {
                $password_reset_token->insert([
                    'email' => $user_info->email,
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);
            }
            //create action link
            $actionLink = route_to('admin.reset-password', $token);

            $mail_data =  array(
                'actionLink' => $actionLink,
                'user' => $user_info,
            );

            $view = \config\Services::renderer();
            $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');
            $mailConfig = array(
                'mail_from_email' => env('MAIL_FROM_ADDRESS'),
                'mail_from_name' => env('MAIL_FROM_NAME'),
                'mail_recipient_email' => $user_info->name,
                'mail_subject' => 'Reset password',
                'mail_body' => $mail_body,
            );

            if (sendEmail($mailConfig)) {
                return redirect()->route('admin.forgot.form')->with('success', 'we have e-mailed you password reset link');
            } else {
                return redirect()->route('admin.forgot.form')->with('fail', 'somethink went wrong');
            }
        }
    }

    //regsiter function
    public function registerHandler()
    {

        $name = $this->request->getPost('name');
        $firstName = $this->request->getPost('first_name');
        $dateOfBirth = $this->request->getPost('datenais');
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');


        $validationRules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'first_name' => 'required|min_length[3]|max_length[255]',
            'datenais' => 'required|valid_date_of_birth',
            'username' => 'required|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]|max_length[255]'
        ];

        $validationMessages = [
            'name' => [
                'required' => 'Le champ Nom est obligatoire.',
                'min_length' => 'Le Nom doit contenir au moins 3 caractères.',
                'max_length' => 'Le Nom ne peut pas dépasser 255 caractères.'
            ],
            'first_name' => [
                'required' => 'Le champ Prénom est obligatoire.',
                'min_length' => 'Le Prénom doit contenir au moins 3 caractères.',
                'max_length' => 'Le Prénom ne peut pas dépasser 255 caractères.'
            ],
            'datenais' => [
                'required' => 'Le champ Date de Naissance est obligatoire.',
                'valid_date_of_birth' => 'La date de naissance doit être valide et ne peut pas être dans le futur.'
            ],
            'username' => [
                'required' => 'Le champ Username est obligatoire.',
                'is_unique' => 'Le Username est déjà utilisé.'
            ],
            'email' => [
                'required' => 'Le champ Email est obligatoire.',
                'valid_email' => 'L\'adresse email fournie n\'est pas valide.',
                'is_unique' => 'L\'adresse email est déjà utilisée.'
            ],
            'password' => [
                'required' => 'Le champ Mot de passe est obligatoire.',
                'min_length' => 'Le Mot de passe doit contenir au moins 6 caractères.',
                'max_length' => 'Le Mot de passe ne peut pas dépasser 255 caractères.'
            ]
        ];

        if (!$this->validate($validationRules, $validationMessages)) {
            return view('backend/pages/auth/register', [
                'pageTitle' => 'Register',
                'validation' => $this->validator
            ]);
        }

        // Préparer les données pour l'enregistrement
        $userData = [
            'name' => $name,
            'first_name' => $firstName,
            'datenais' => $dateOfBirth,
            'username' => $username,
            'email' => $email,
            'password' => hash::make($password)
        ];

        $userModel = new User();

        // Enregistrer l'utilisateur dans la base de données
        if ($userModel->insert($userData)) {
            return redirect()->route('admin.login.form')->with('success', 'User registered successfully.');
        } else {
            $errors = $userModel->errors();
            return redirect()->back()->with('fail', 'Failed to register user. ' . implode(', ', $errors))->withInput();
        }
    }

    public function getUserInfo()
    {
        $user = CIAuth::user();

        if (!$user) {
            return redirect()->route('admin.login.form')->with('fail', 'Vous devez être connecté pour accéder à cette page.');
        }

        $data = [
            'pageTitle' => 'infos user',
            'user' => $user
        ];

        return view('backend/layout/inc/header', $data);
        // return $this->response->setJSON($user);
    }
}
