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
    private $namespace;

    /**
     * @var string
     */
    private $version;

    /**
     * @var integer
     */
    private $status;

    public function __construct($name, $namespace, $version)
    {
        $this->name = $name;
        $this->namespace = $namespace;
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
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param integer $newVersion
     */
    public function setUpgradedTo($newVersion)
    {
        $this->version = $newVersion;
    }

    /**
     * @return boolean
     */
    public function isNeedInstallation()
    {
        return $this->status == self::STATUS_NEED_INSTALLATION;
    }

    public function setNeedInstallation()
    {
        $this->status = self::STATUS_NEED_INSTALLATION;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function setActive()
    {
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isNeedUpgrade()
    {
        return $this->status == self::STATUS_NEED_UPGRADE;
    }

    public function setNeedUpgrade()
    {
        $this->status = self::STATUS_NEED_UPGRADE;
    }

    /**
     * @return bool
     */
    public function isAbsent()
    {
        return $this->status == self::STATUS_ABSENT;
    }

    public function setAbsent()
    {
        $this->status = self::STATUS_ABSENT;
    }

    public function getStatusName()
    {
        $statuses = [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_NEED_INSTALLATION => 'need install',
            self::STATUS_NEED_UPGRADE => 'need upgrade',
            self::STATUS_ABSENT => 'absent',
        ];
        return isset($statuses[$this->status]) ? $statuses[$this->status] : 'unknown';
    }

} 