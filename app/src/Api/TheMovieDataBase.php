<?php

namespace App\Api;

use GuzzleHttp\Client;

class TheMovieDataBase
{
    protected const URL = 'https://api.themoviedb.org/3';
    protected const BEARER = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5MWZiMjI2MzFjYmRkOTZiMzZlMWFhZDBiYjI3YmFmMSIsInN1YiI6IjY0NTUwNTAyZDQ4Y2VlMDBmY2VlYTBjMyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.UB6TNHT7P4Wce6O5hzDoc5sV3bf0Ox3W0Y7h4G6nroA';
    protected const LANGUAGE = 'en-US';
    protected const STATUS_CODE_OK = 200;
    protected const REASON_PHRASE_OK = 'OK';

    protected string $images_base_url = '';
    protected string $images_secure_base_url = '';
    protected string $images_backdrop_size = '';
    protected string $images_logo_size = '';
    protected string $images_poster_size = '';

    protected string $original_poster_size = '';
    protected array $genres;

    public function __construct() {

        $response = $this->get('/configuration');
        $genreReponse = $this->get('/genre/movie/list');
        if(isset($genreReponse['genres'])) {
            $this->genres = $genreReponse['genres'];
        }
        $this->images_base_url = $response['images']['base_url'] ?? $response['images']['base_url'];
        $this->images_secure_base_url = $response['images']['secure_base_url'] ?? $response['images']['secure_base_url'];
        $this->images_backdrop_size = $response['images']['backdrop_sizes'][0] ?? $response['images']['backdrop_sizes'][0];
        $this->images_poster_size = $response['images']['poster_sizes'][0] ?? $response['images']['poster_sizes'][0];
        $this->original_poster_size = 'original';
    }

    public function getNowPlayingMovies(?int $limit = null)
    {
        return $this->getMovies('now_playing', $limit);
    }

    public function getPopularMovies(?int $limit = null)
    {
        return $this->getMovies('popular', $limit);
    }

    public function getTopRatedMovies(?int $limit = null)
    {
        return $this->discoverMovies('&vote_average.gte=8', $limit);
    }

    public function getRomanceMovies(?int $limit = null)
    {
        return $this->discoverMovies('&with_genres=10749&without_genres=10751%2C14%2C16&vote_average.gte=7', $limit);
    }

    public function getFamilyMovies(?int $limit = null)
    {
        return $this->discoverMovies('&with_genres=10751&vote_average.gte=7', $limit);
    }

    public function getMovie(int $id)
    {
        $movie = $this->get('/movie/' . $id);

        $movie['poster_path'] = $this->images_base_url . $this->original_poster_size . $movie['poster_path'];
        $movie['backdrop_path'] = $this->images_base_url . $this->original_poster_size . $movie['backdrop_path'];
        $movie['genres'] = $this->getGenres($movie['genres'], 5);
        $movie['externalIds'] = $this->getExternalIds($id);
        $movie['cast'] = $this->getCast($id, 5);
        $movie['videos'] = $this->getVideos($id, 3);
        $movie['keywords'] = $this->getKeywords($id, 5);
        $movie['price'] = $this->calculatePrice($movie);

        return $movie;
    }

    private function getExternalIds($movieId)
    {
        return $this->get('/movie/' . $movieId . '/external_ids');
    }

    private function getCast($movieId, $limit)
    {
        $credits = $this->get('/movie/' . $movieId . '/credits?language=en-US');
        return array_slice($credits['cast'], 0, $limit);
    }

    private function getVideos($movieId, $limit)
    {
        $selectedVideos = [];
        $videos = $this->get('/movie/' . $movieId . '/videos?language=en-US');
        if(isset($videos['results'])) {
            foreach($videos['results'] as $video) {
                if(in_array($video['type'], ['Teaser', 'Trailer'])) {
                    $selectedVideos[] = $video;
                }
            }
        }

        return array_slice($selectedVideos, 0, $limit);
    }

    private function getTrailer($movieId)
    {
        $videos = $this->get('/movie/' . $movieId . '/videos?language=en-US');

        if(isset($videos['results'])) {
            foreach($videos['results'] as $video) {
                if('Trailer' == $video['type']) {
                    return $video['key'];
                }
            }
        }

        return '';
    }

    private function getKeywords($movieId, $limit)
    {
        $results = $this->get('/movie/' . $movieId . '/keywords?language=en-US');
        return array_slice($results['keywords'], 0, $limit);
    }

    private function getMovies(string $queryString, ?int$limit = null): array
    {
        $response = $this->get('/movie/' . $queryString);
        $movies = $this->parseResponse($response, $limit);
        return $movies;
    }

    private function discoverMovies(string $queryString, ?int$limit = null): array
    {
        $response = $this->get('/discover/movie?language=en-US&sort_by=popularity.desc&year=' . date('Y') . $queryString);
        $movies = $this->parseResponse($response, $limit);
        return $movies;
    }

    private function calculatePrice($movie)
    {
        return number_format($movie['vote_average'] * 2, 2);
    }

    private function parseResponse($response, $limit = null)
    {
        $movies = [];

        if(isset($response['results'])) {
            foreach($response['results'] as $result) {
                if(isset($result['title']) && $result['backdrop_path'] && $result['overview']) {
                    $movies[] = [
                        'id' => $result['id'],
                        'title' => $result['title'],
                        'backdrop_path' => $this->images_base_url . $this->original_poster_size . $result['backdrop_path'],
                        'poster_path' => $this->images_base_url . $this->original_poster_size . $result['poster_path'],
                        'vote_average' => number_format($result['vote_average'],1),
                        'genres' => $this->getGenres($result['genre_ids'], 3),
                        'price' => number_format((rand(120, 570) / 100), 2),
                        'release_date' => $result['release_date'],
                        'trailer' => $this->getTrailer($result['id'])
                    ];
                }
            }

            if(!empty($limit)) {
                return array_slice($movies, 0, $limit);
            }
        }

        return $movies;
    }

    private function getGenres($genre_ids, $limit = null)
    {
        $genreList = [];
        foreach($this->genres as $genre) {
            if(in_array($genre['id'], $genre_ids)) {
                $genreList[] = $genre;
            }
        }

        if(!empty($limit)) {
            $genreList = array_slice($genreList, 0, $limit);
        }

        return $genreList;
    }

    private function get($queryString)
    {
        $client = new Client();

        $response = $client->request('GET', self::URL  . $queryString, [
            'headers' => [
                'Authorization' => 'Bearer ' . self::BEARER,
                'accept' => 'application/json',
            ],
        ]);

        if($this->isValid($response)) {
            $body = $response->getBody()->getContents();
            $body = json_decode($body, true);

            return $body;
        } else {
            throw new InvalidResponseException('Error: Response is not valid');
        }
    }


    private function isValid($response)
    {
        if(
            $response->getStatusCode() == self::STATUS_CODE_OK &&
            $response->getReasonPhrase() == self::REASON_PHRASE_OK
        ) {
            return true;
        } else {
            return false;
        }
    }
}