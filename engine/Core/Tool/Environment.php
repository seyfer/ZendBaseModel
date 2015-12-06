<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 9/23/15
 * Time: 11:08 PM
 */

namespace Core\Tool;


use Core\Validation\ValidationException;
use ZendMover\Command\MoveCommand;
use ZendMover\MoverInterface;

/**
 * Class Environment
 * @package Core\Tool
 */
class Environment
{
    const ENV_STABLE  = 'stable';
    const ENV_STAGING = 'staging';
    const ENV_DEV     = 'dev';

    private $availableEnvs = [
        self::ENV_STABLE, self::ENV_STAGING, self::ENV_DEV
    ];

    private $configPath;

    /**
     * @var string
     */
    private $env = '';

    /**
     * @var MoverInterface
     */
    private $mover;

    /**
     * @param string $env
     * @param MoverInterface $mover
     * @throws ValidationException
     */
    public function __construct($env, MoverInterface $mover)
    {
        $this->configPath = __DIR__ . '/../../../config/autoload/';

        $this->setEnv($env);
        $this->setMover($mover);
    }

    /**
     * @param null $env
     * @param null $dbu
     * @param null $dbp
     * @param null $dbn
     * @return bool
     * @throws ValidationException
     */
    public function installEnv($env = null, $dbu = null, $dbp = null, $dbn = null)
    {
        if ($env) {
            $this->setEnv($env);
        }

        //check config file
        $configFile = $this->configPath . 'env.' . $env . '.php';
        if (!file_exists($configFile)) {
            throw new \RuntimeException('Config file ' . $configFile . ' not exist');
        }
        $localConfigFile     = 'env.local.php';
        $localConfigFilePath = $this->configPath . $localConfigFile;

        $moveCommand = new MoveCommand($this->mover);
        $moveCommand->setFromDirectory($this->configPath)
                    ->setToDirectory($this->configPath)
                    ->addFileToMove(new \SplFileInfo($configFile))
                    ->setDestinationFileName($localConfigFile);

        $moveCommand->execute();

        if ($dbu && $dbp && $dbn) {
            $envConfigFileContent  = file_get_contents($localConfigFilePath);
            $envConfigFileContentR = preg_replace(
                ['/{dbu}/', '/{dbp}/', '/{dbn}/'],
                [$dbu, $dbp, $dbn],
                $envConfigFileContent
            );

            file_put_contents($localConfigFilePath, $envConfigFileContentR);

            return true;
        }

        if ($dbu && $dbp) {
            $envConfigFileContent  = file_get_contents($localConfigFilePath);
            $envConfigFileContentR = preg_replace(
                ['/{dbu}/', '/{dbp}/'],
                [$dbu, $dbp],
                $envConfigFileContent
            );

            file_put_contents($localConfigFilePath, $envConfigFileContentR);

            return true;
        }

        if ($dbp) {
            $envConfigFileContent  = file_get_contents($localConfigFilePath);
            $envConfigFileContentR = preg_replace(
                '/{dbp}/',
                $dbp,
                $envConfigFileContent
            );

            file_put_contents($localConfigFilePath, $envConfigFileContentR);

            return true;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @param string $env
     * @throws ValidationException
     */
    public function setEnv($env)
    {
        if (!in_array($env, $this->availableEnvs)) {
            throw new ValidationException('Wrong env value');
        }

        $this->env = $env;
    }

    /**
     * @return MoverInterface
     */
    public function getMover()
    {
        return $this->mover;
    }

    /**
     * @param MoverInterface $mover
     */
    public function setMover($mover)
    {
        $this->mover = $mover;
    }
}