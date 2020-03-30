<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 21/03/2020
 * Time: 10:48
 */

namespace App\Services;


use App\Entity\AlarmSettings;
use App\Form\AlarmSettingsType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class AlarmSettingsService
{
    private $container;

    private $em;

    private $musicDirectory;

    private const DAYS = [
        "monday",
        "tuesday",
        "wednesday",
        "thursday",
        "friday",
        "saturday",
        "sunday"
    ];

    const DAY_NAMES = [
        "monday" => "Lundi",
        "tuesday" => "Mardi",
        "wednesday" => "Mercredi",
        "thursday" => "Jeudi",
        "friday" => "Vendredi",
        "saturday" => "Samedi",
        "sunday" => "Dimanche"
    ];

    /**
     * AlarmSettingsService constructor.
     * @param $musicDirectory
     * @param ContainerInterface $container
     * @param EntityManager $em
     */
    public function __construct($musicDirectory, ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
        $this->musicDirectory = $musicDirectory;
    }

    public function getAlarmForms()
    {
        $alarmSettingForms = [];

        try {
            $availableMusics = $this->getAvailableMusics();
        } catch (\Exception $e) {
            echo $e->getMessage();die;
        }

        foreach (self::DAYS as $day) {
            $alarmSetting = $this->em->getRepository(AlarmSettings::class)->findOneBy(["day" => $day]);

            if (!$alarmSetting) {
                $alarmSetting = new AlarmSettings();
                $alarmSetting->setDay($day);
            }

            $dayName = self::DAY_NAMES[$day];

            $alarmSettingForms[$dayName] = $this->container
                                            ->get('form.factory')
                                            ->create(AlarmSettingsType::class, $alarmSetting, ['available_musics' => $availableMusics]);
        }

        return $alarmSettingForms;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getAvailableMusics()
    {
        $musicDirectory = $this->musicDirectory;

        if (empty($musicDirectory)) {
            throw new \Exception("MUSIC_DIRECTORY_PATH is empty in .env file.");
        }

        $musics = [];

        if ($musicFileNames = scandir($musicDirectory)) {
            foreach ($musicFileNames as $musicFileName) {
                if (!in_array($musicFileName, ['.', '..', 'desktop.ini', 'vlc-help.txt'])) {
                    $musicName = $this->getMusicName($musicFileName);
                    $musics[$musicName] = $musicFileName;
                }
            }
        } else {
            return ["Pas de musique disponible" => 0];
        }

        return $musics;
    }

    private function getMusicName($musicFileName)
    {
        return ucfirst(str_replace("-", " ", substr($musicFileName, 0, strpos($musicFileName, "."))));
    }
}