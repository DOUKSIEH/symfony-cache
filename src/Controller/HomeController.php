<?php

namespace App\Controller;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Stopwatch $stopwatch, CacheInterface $cache )
    {
        // stopwatch : fournit un moyen cohérent de mesurer le temps d'exécution
        // de certaines parties du code afin que vous n'ayez pas 
        //à analyser constamment microtime par vous-même.
        // https://symfony.com/doc/current/components/stopwatch.html
        $stopwatch->start('calcul-long');

        // On imagine un calcul ou un traitement long
        //$resultatCalcul = $this->fonctionQuiPrendDuTemps();
        $resultatCalcul = $cache->get('my_cache_key', function (ItemInterface $item) {
           //$item : la boite qui contient les données
           // au bout de 10s la valeur sera périmée
           // https://symfony.com/doc/current/cache.html 
            $item->expiresAfter(10);      
            return $this->fonctionQuiPrendDuTemps();
        });

        $stopwatch->stop('calcul-long');

        

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    private function fonctionQuiPrendDuTemps(): int
    {
        sleep(2);

        return 10;
    }
}
