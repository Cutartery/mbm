<?php
namespace controllers;

class UploadController
{
    public function upload()
    {
        $file = $_FILES['image'];
        $name = time();
    }
}