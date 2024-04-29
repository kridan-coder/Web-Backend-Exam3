<?php


class Topic
{
    public $id;
    public $name;
    public $parentId;

    public $childs;

    public function __construct( $id,  $name,  $parentId) {
        $this->id = $id;
        $this->name = $name;
        $this->parentId = $parentId;
    }
}
?>