<?php
/**
 * Created by PhpStorm.
 * User: T2G-WEB
 * Date: 13/04/2020
 * Time: 12:18
 */

namespace App\Services;


use App\Entity\RaspbianHelper;
use Doctrine\ORM\EntityManagerInterface;

class MusicService
{
    private $musicDirectory;
    private $em;

    /**
     * MusicService constructor.
     * @param $musicDirectory
     * @param EntityManagerInterface $em
     * @throws \Exception
     */
    public function __construct($musicDirectory, EntityManagerInterface $em)
    {
        $this->musicDirectory = $musicDirectory;
        $this->em = $em;

        if (empty($musicDirectory)) {
            throw new \Exception("MUSIC_DIRECTORY_PATH is empty in .env file.");
        }
    }

    public function play($musicFileName)
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $cmd = "vlc --one-instance " . $this->musicDirectory . DIRECTORY_SEPARATOR . $musicFileName . " --qt-start-minimized &";
        } else {
            $cmd = "vlc ".$this->musicDirectory.$musicFileName." vlc://quit -I dummy";
        }
        $this->exec($cmd);

        return $this->getParsedMusicName($musicFileName);
    }

    public function quit()
    {
        if (substr(php_uname(), 0, 7) == "Windows") {
            $cmd = "vlc --one-instance vlc://quit";
        } else {
            $cmd = "";
            if ($pid = $this->getMusicPid()) {
                $cmd = "kill " . $pid;
            }
        }
        $this->exec($cmd);
    }

    private function getParsedMusicName($musicFileName)
    {
        $musicFileName = str_replace("-", " ", substr($musicFileName, 0, strpos($musicFileName, ".")));
        $musicFileName = str_replace("_", " ", $musicFileName);
        return ucfirst($musicFileName);
    }

    private function exec($cmd, $savePid = false) {
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r"));
        }
        else {
            $pid = shell_exec($cmd." > /dev/null 2>&1 & echo $!; ");
            if ($savePid)
                $this->savePid($pid);
        }
    }

    private function getMusicPid()
    {
        $raspbianHelper = $this->em->getRepository(RaspbianHelper::class)->findOneBy(["name" => self::MUSIC_PID]);
        if (!$raspbianHelper) {
            return false;
        }

        return $raspbianHelper->getValue();
    }

    private function savePid($pid)
    {

        $raspbianHelper = $this->em->getRepository(RaspbianHelper::class)->findOneBy(["name" => self::MUSIC_PID]);
        if (!$raspbianHelper) {
            $raspbianHelper = new RaspbianHelper();
            $raspbianHelper->setName(self::MUSIC_PID);
        }
        $raspbianHelper->setValue($pid);

        $this->em->persist($pid);
        $this->em->flush();
    }
}