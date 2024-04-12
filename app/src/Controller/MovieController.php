<?php

namespace App\Controller;

use App\Api\TheMovieDataBase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/', name: 'movie_index')]
    public function index(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Now Playing',
            'movies' => $theMovieDataBase->getNowPlayingMovies(3)
        ]);
    }

    #[Route('/now-playing', name: 'app_now_playing')]
    public function nowPlaying(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Now Playing',
            'movies' => $theMovieDataBase->getNowPlayingMovies()
        ]);
    }

    #[Route('/family', name: 'app_family')]
    public function family(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Family movies',
            'movies' => $theMovieDataBase->getFamilyMovies(9)
        ]);
    }

    #[Route('/romance', name: 'app_romance')]
    public function romance(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Romance night',
            'movies' => $theMovieDataBase->getRomanceMovies(9)
        ]);
    }

    #[Route('/popular', name: 'app_popular')]
    public function popular(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Popular movies',
            'movies' => $theMovieDataBase->getPopularMovies(9)
        ]);
    }

    #[Route('/top-rated', name: 'app_top_rated')]
    public function topRated(TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/list.html.twig', [
            'title' => 'Top rated movies',
            'movies' => $theMovieDataBase->getTopRatedMovies(9)
        ]);
    }

    #[Route('/movie/{id}', name: 'app_movie')]
    public function movie(int $id, TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $theMovieDataBase->getMovie($id),
        ]);
    }

    #[Route('/seats/{id}', name: 'app_seats')]
    public function seats(int $id, TheMovieDataBase $theMovieDataBase): Response
    {
        return $this->render('seats/index.html.twig', [
            'movie' => $theMovieDataBase->getMovie($id),
        ]);
    }
}
