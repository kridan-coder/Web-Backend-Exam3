<?php

class Post
{
    private  $Text;

    public function __construct( $Text) {
        $this->$Text = $Text;
    }
}