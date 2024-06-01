<?php
class Book
{
    public $id;
    public $title;
    public $price;
    public $created_at;


    function getPrice()
    {
        return "$$this->price";
    }
}
