<?php
class UserWithPosts
{
    public  $id;
    public  $Surname;
    public  $Username;
    public  $Password;
    public  $Birthday;
    public  $Avatar;

    public $posts;

    public function __construct( $name,  $surname,  $username,  $password, $birthday,  $avatar) {
        $this->id = $name;
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

}