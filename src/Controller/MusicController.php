<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 07/03/2020
 * Time: 20:13
 */

namespace App\Controller;

use App\Services\MusicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends AbstractController
{
    private $musicService;

    public function __construct(MusicService $musicService)
    {
        $this->musicService = $musicService;
    }

    /**
     * @Route("/play_music", name="play_music")
     * @return Response
     */
    public function play(){
        $musicFileName = "owl_city_when_can_i_see_you_again.mp3";

        $musicName = $this->musicService->play($musicFileName);

        return new Response($musicName);
    }

    /**
     * @Route("/play_music/{musicFileName}", name="play_by_music_name")
     * @param $musicFileName
     * @return Response
     */
    public function playByMusicName($musicFileName){

        $musicName = $this->musicService->play($musicFileName);

        return new Response($musicName);
    }

    /**
     * @Route("/kill_music", name="kill_music")
     */
    public function quit(){

        $this->musicService->quit();

        return new Response("Music");
    }


}
