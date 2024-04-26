<?php

namespace App\Controller;

use App\Api\TheMovieDataBase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('index.html.twig', [
            'title' => 'Now Playing',
            'movies' => $theMovieDataBase->getNowPlayingMovies(12)
        ]);
    }

    #[Route('/movies', name: 'app_movies')]
    public function movies(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movies.html.twig', [
            'movies' => $theMovieDataBase->getNowPlayingMovies(12)
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('contact.html.twig', [
        ]);
    }
}
