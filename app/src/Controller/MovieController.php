<?php

namespace App\Controller;

use App\Api\TheMovieDataBase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{

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
        if(empty($this->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('seats/prices.html.twig', [
            'movie' => $theMovieDataBase->getMovie($id),
        ]);
    }
}
