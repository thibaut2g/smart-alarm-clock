<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 07/03/2020
 * Time: 20:13
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MusicController extends AbstractController
{

    /**
     * @Route("/play_music", name="play_music")
     */
    public function play(){
        $musicFileName = "owl_city_when_can_i_see_you_again.mp3";

        $musicDirectoryPath = $this->getParameter("music_directory_path");

        if (empty($musicDirectoryPath)) {
            throw new \Exception("MUSIC_DIRECTORY_PATH is empty in .env file.");
        }

        $this->exec("vlc ".$musicDirectoryPath.$musicFileName." -I dummy");
        return new Response($this->getParsedMusicName($musicFileName));
    }

    /**
     * @Route("/kill_music", name="kill_music")
     */
    public function quit(){
        $this->exec("kill 3624");
        return new Response("Music");
    }

    private function getParsedMusicName($musicFileName)
    {
        return ucfirst(str_replace("-", " ", substr($musicFileName, 0, strpos($musicFileName, "."))));
    }

    private function exec($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r"));
        }
        else {
            $output = array();
            exec($cmd." > /dev/null 2>&1 & echo $!; ", $output);
            var_dump($output);
            die;
        }
    }
}
