<?php

namespace App\Controller;

use App\Api\TheMovieDataBase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'Now Playing',
            'movies' => $theMovieDataBase->getNowPlayingMovies(6)
        ]);
    }
}
