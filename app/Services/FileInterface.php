<?php
    
namespace App\Services;

interface FileInterface
{
    public function link();
    public function upload();
    public function remove();
}
