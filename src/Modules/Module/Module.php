<?php

namespace Modules\Module;


class Module {

    const STATUS_ACTIVE = 1;
    const STATUS_NEED_INSTALLATION = 2;
    const STATUS_NEED_UPGRADE = 3;
    const STATUS_ABSENT = 4;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $version;

    /**
     * @var integer
     */
    private $status;

    public function __construct($name, $version)
    {
        $this->name = $name;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isNeedInstallation()
    {
        return $this->status == self::STATUS_NEED_INSTALLATION;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function setNeedInstallation()
    {
        $this->status = self::STATUS_NEED_INSTALLATION;
    }

} 