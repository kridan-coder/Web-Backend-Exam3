<?php

class User
{
    private $Name;
    private $Surname;
    private $Username;
    private $Password;
    private $Birthday;
    private $Avatar;

    public function __construct( $name,  $surname,  $username,  $password,  $birthday = NULL,  $avatar = NULL) {
        $this->Name = $name;
        $this->Surname = $surname;
        $this->Username = $username;
        $this->Password = $password;
        if ($birthday != null) {
            $this->Birthday = $birthday;
        }
        if ($avatar != null) {
            $this->Avatar = $avatar;
        }
    }

    public static function validateName($name): string {
        if (is_string($name) && strlen($name) <= 63) {
            return $name;
        }

        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Name format is invalid. It must be a string and contain exactly or less than to 63 characters.'
        ]);
        die();
    }         //
    public static function validateSurname($surname): string {
        if (is_string($surname) && strlen($surname) <= 63) {
            return $surname;
        }

        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Surname format is invalid. It must be a string and contain exactly or less than to 63 characters.'
        ]);
        exit();
    }   //
    public static function validatePassword($password): string {
        if (is_string($password) && strlen($password) <= 63) {
            return $password;
        }

        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Password format is invalid. It must be a string and contain exactly or less than to 63 characters.'
        ]);
        exit();
    } // can be used as setter for constructor
    public static function validateUsername($username): string {
        if (is_string($username) && strlen($username) <= 63) {
            return $username;
        }

        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Username format is invalid. It must be a string and contain exactly or less than to 63 characters.'
        ]);
        exit();
    } //
    public function setAvatar($avatar) {
        if (is_string($avatar) && strlen($avatar) <= 1000) {
            $this->Avatar = $avatar;
            return;
        }

        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Username format is invalid. It must be a string and contain mo greater than 1000 characters.'
        ]);
        exit();
    } //
    public static function validateBirthday($birthday): string {
        if (strlen($birthday) == 10) {
            preg_match('/(([1][9][0-9][0-9]|[2][0][0-1][0-5])\-([0][1-9]|[1][0-2])\-([3][0-1]|[1-2][0-9]|[0][0-9]))/', $birthday, $matches);
            if (isset($matches[0])) {
                return $birthday;
            }
        }

        http_response_code(400);
        echo json_encode([
            'status' => false,
            'message' => 'Birthday format is invalid. You can set date in range [1900-01-01 - 2015-12-31]'
        ]);
        exit();
    } //

}
