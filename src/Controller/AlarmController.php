<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 30/03/2020
 * Time: 11:45
 */

namespace App\Controller;


use App\Entity\AlarmSettings;
use App\Services\MusicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlarmController extends AbstractController
{
    /**
     * @Route("/check_alarm", name="check_alarm")
     * @param MusicService $musicService
     * @return Response
     */
    public function checkAlarm(MusicService $musicService){
        $response = "checked";

        $day = strtolower(date("l"));
        $time = date("H:i");

        $entityManager = $this->getDoctrine()->getManager();
        $alarmSetting = $entityManager->getRepository(AlarmSettings::class)->findOneBy(["day" => $day]);

        if (!empty($alarmSetting) && $alarmSetting->getTime()->format("H:i") == $time) {
            $response = $this->activeAlarm($alarmSetting->getMusic(), $musicService);
        }
        return new Response($response);
    }

    /**
     * @param $musicFileName
     * @param MusicService $musicService
     * @return string
     */
    private function activeAlarm($musicFileName, $musicService)
    {
        return $musicService->play($musicFileName);
    }
}