<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 07/03/2020
 * Time: 17:42
 */

namespace App\Controller;

use App\Entity\AlarmSettings;
use App\Services\AlarmSettingsService;
use App\Services\MusicService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param AlarmSettingsService $alarmSettingsService
     * @param MusicService $musicService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(AlarmSettingsService $alarmSettingsService, MusicService $musicService){
        // permet de cacher la souris
        $musicService->exec('unclutter -idle 0 -root');
        $alarmSettingForms = $alarmSettingsService->getAlarmForms();
        return $this->render('home.html.twig', ["alarmSettingForms" => $alarmSettingForms]);
    }


    /**
     * @Route("/save", name="save", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function save(Request $request) {
        $day = $request->request->get('day');
        $timeData = $request->request->get('time');
        $timeData = explode(":", $timeData);
        $time = new \DateTime();
        $time->setTime($timeData[0], $timeData[1]);
        $music = $request->request->get('music');

        if (!empty($day) && !empty($time) && !empty($music)) {

            $entityManager = $this->getDoctrine()->getManager();
            $alarmSetting = $entityManager->getRepository(AlarmSettings::class)->findOneBy(["day" => $day]);

            if (!$alarmSetting) {
                $alarmSetting = new AlarmSettings();
                $alarmSetting->setDay($day);
            }

            $alarmSetting->setTime($time);
            $alarmSetting->setMusic($music);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($alarmSetting);
            $entityManager->flush();
            return new Response("success");
        }

        return new Response("error");
    }


}