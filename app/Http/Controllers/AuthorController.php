<?php

namespace App\Http\Controllers;

use App\Repositories\AuthorRepository;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public $authorRepository;

    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }

    public function getAuthor(){
        return $this->authorRepository->getAuthor();
    }
}
