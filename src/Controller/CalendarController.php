<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 27/03/2020
 * Time: 11:33
 */

namespace App\Controller;

use App\Entity\GoogleConfig;
use DateInterval;
use DateTime;
use Exception;
use Google_Client;
use Google_Service_Calendar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarController extends AbstractController
{
    const TYPE_TOKEN = "token";

    const CALENDAR_IDS = [
        "primary",
        "villeneuvedascq@scouts-unitaires.org",
        "scouts-unitaires.org_8bkb6j09tmf6nks11uldhqqjmo@group.calendar.google.com"
    ];

    const DATE_DELIMITER = "T";

    const TIME_DELIMITER = "+";

    /**
     * @Route("/get-calendar-events", name="get-calendar-events")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Google_Exception
     */
    public function getEventList() {

        $client = $this->getClient();

        $service = new Google_Service_Calendar($client);

        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
            'timeMax' => $this->getMaxDate()
        );

        $response = [];

        foreach (self::CALENDAR_IDS as $calendarId) {

            $results = $service->events->listEvents($calendarId, $optParams);
            $events = $results->getItems();

            foreach ($events as $event) {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $dayAndTime = $this->getDayAndTime($start);
                $response[$dayAndTime["day"]][] = ["time" => $dayAndTime["time"], "summary" => $event->getSummary()];
            }
        }

        return new Response(json_encode($response));
    }

    /**
     * @Route("/redirect-uri", name="redirect-uri")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws Exception
     */
    public function redirectUri(Request $request) {
        $code = $request->query->get('code');

        $client = new Google_Client();
        $client->setApplicationName("Google Calendar API");
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig( $this->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'config/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        if (!empty($code)) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }

        } else {
            throw new Exception("Code de vérification non récupéré.");

        }

        $entityManager = $this->getDoctrine()->getManager();
        $googleConfig = $entityManager->getRepository(GoogleConfig::class)->findOneBy(["type" => self::TYPE_TOKEN]);

        if (!$googleConfig) {
            $googleConfig = new GoogleConfig();
            $googleConfig->setType(self::TYPE_TOKEN);
        }
        $googleConfig->setValue(json_encode($accessToken));
        $entityManager->persist($googleConfig);
        $entityManager->flush();

        return $this->redirectToRoute('get-calendar-events');
    }

    /**
     * @param Google_Client $client
     */
    private function refreshToken($client)
    {
        if ($client->getRefreshToken()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            if (!empty($newToken)) {
                $entityManager = $this->getDoctrine()->getManager();
                $googleConfig = $entityManager->getRepository(GoogleConfig::class)->findOneBy(["type" => self::TYPE_TOKEN]);
                $googleConfig->setValue(json_encode($newToken));
                $entityManager->persist($googleConfig);
                $entityManager->flush();
            }
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            var_dump($authUrl);die;

            $this->redirect($authUrl);
        }
    }

    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName("Google Calendar API");

        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig( $this->getParameter('kernel.project_dir').DIRECTORY_SEPARATOR.'config/credentials.json');
        $client->setRedirectUri($this->generateUrl("redirect-uri", [], UrlGeneratorInterface::ABSOLUTE_URL));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $entityManager = $this->getDoctrine()->getManager();
        $googleConfigToken = $entityManager->getRepository(GoogleConfig::class)->findOneBy(["type" => self::TYPE_TOKEN]);


        if (!empty($googleConfigToken)) {
            $client->setAccessToken($googleConfigToken->getValue());
        }

        if ($client->isAccessTokenExpired()) {
            $this->refreshToken($client);
        }

        return $client;
    }

    private function getMaxDate()
    {
        $dateTime = new DateTime('NOW');
        $dateTime->add(new DateInterval('P3D'));
        return $dateTime->format('c');
    }

    private function getDayAndTime($start)
    {
        $dateTime = new DateTime('NOW');

        $explodedDate = explode(self::DATE_DELIMITER, $start);
        $day = $explodedDate[0];

        if (!empty($explodedDate[1])) {
            $explodedTime = explode(self::TIME_DELIMITER, $explodedDate[1]);
            $time = substr($explodedTime[0], 0, 5);
        } else {
            $time = "all day";
        }

        $today = $dateTime->format('Y-m-d');

        $dateTime->add(new DateInterval('P1D'));
        $tomorrow = $dateTime->format('Y-m-d');

        $dateTime->add(new DateInterval('P1D'));
        $afterTomorrow = $dateTime->format('Y-m-d');

        if ($day == $today) {
            return ["day" => "Aujourd'hui", "time" => $time];
        } elseif ($day == $tomorrow) {
            return ["day" => "Demain", "time" => $time];
        } elseif ($day == $afterTomorrow) {
            return ["day" => "Après-demain", "time" => $time];
        } else {
            return "Autre";
        }
    }
}