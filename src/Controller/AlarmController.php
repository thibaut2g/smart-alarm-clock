<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 30/03/2020
 * Time: 11:45
 */

namespace App\Controller;


use App\Entity\AlarmSettings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlarmController extends AbstractController
{
    /**
     * @Route("/check_alarm", name="check_alarm")
     */
    public function checkAlarm(){
        $day = strtolower(date("l"));
        $time = date("H:i");

        $entityManager = $this->getDoctrine()->getManager();
        $alarmSetting = $entityManager->getRepository(AlarmSettings::class)->findOneBy(["day" => $day]);

        if (!empty($alarmSetting) && $alarmSetting->getTime()->format("H:i") == $time) {
            $this->activeAlarm($alarmSetting->getMusic());
        }
        return new Response("checked");
    }

    private function exec($cmd) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r"));
        }
        else {
            exec($cmd . " > /dev/null &");
        }
    }

    private function activeAlarm($musicFileName)
    {
        $musicDirectoryPath = $this->getParameter("music_directory_path");

        $this->exec("vlc --one-instance ".$musicDirectoryPath.DIRECTORY_SEPARATOR.$musicFileName." --qt-start-minimized &");

    }
}