<?php

namespace Proxies\__CG__\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class PhysicalInterface extends \Entities\PhysicalInterface implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'status', 'speed', 'duplex', 'monitorindex', 'notes', 'id', 'SwitchPort', 'VirtualInterface', 'FanoutPhysicalInterface', 'PeeringPhysicalInterface'];
        }

        return ['__isInitialized__', 'status', 'speed', 'duplex', 'monitorindex', 'notes', 'id', 'SwitchPort', 'VirtualInterface', 'FanoutPhysicalInterface', 'PeeringPhysicalInterface'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (PhysicalInterface $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', [$status]);

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setSpeed($speed)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSpeed', [$speed]);

        return parent::setSpeed($speed);
    }

    /**
     * {@inheritDoc}
     */
    public function getSpeed()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSpeed', []);

        return parent::getSpeed();
    }

    /**
     * {@inheritDoc}
     */
    public function setDuplex($duplex)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDuplex', [$duplex]);

        return parent::setDuplex($duplex);
    }

    /**
     * {@inheritDoc}
     */
    public function getDuplex()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDuplex', []);

        return parent::getDuplex();
    }

    /**
     * {@inheritDoc}
     */
    public function setMonitorindex($monitorindex)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMonitorindex', [$monitorindex]);

        return parent::setMonitorindex($monitorindex);
    }

    /**
     * {@inheritDoc}
     */
    public function getMonitorindex()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMonitorindex', []);

        return parent::getMonitorindex();
    }

    /**
     * {@inheritDoc}
     */
    public function setNotes($notes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNotes', [$notes]);

        return parent::setNotes($notes);
    }

    /**
     * {@inheritDoc}
     */
    public function getNotes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNotes', []);

        return parent::getNotes();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setSwitchPort(\Entities\SwitchPort $switchPort = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSwitchPort', [$switchPort]);

        return parent::setSwitchPort($switchPort);
    }

    /**
     * {@inheritDoc}
     */
    public function getSwitchPort()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSwitchPort', []);

        return parent::getSwitchPort();
    }

    /**
     * {@inheritDoc}
     */
    public function setVirtualInterface(\Entities\VirtualInterface $virtualInterface = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVirtualInterface', [$virtualInterface]);

        return parent::setVirtualInterface($virtualInterface);
    }

    /**
     * {@inheritDoc}
     */
    public function getVirtualInterface()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVirtualInterface', []);

        return parent::getVirtualInterface();
    }

    /**
     * {@inheritDoc}
     */
    public function setFanoutPhysicalInterface(\Entities\PhysicalInterface $fanoutPhysicalInterface = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFanoutPhysicalInterface', [$fanoutPhysicalInterface]);

        return parent::setFanoutPhysicalInterface($fanoutPhysicalInterface);
    }

    /**
     * {@inheritDoc}
     */
    public function getFanoutPhysicalInterface()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFanoutPhysicalInterface', []);

        return parent::getFanoutPhysicalInterface();
    }

    /**
     * {@inheritDoc}
     */
    public function setPeeringPhysicalInterface(\Entities\PhysicalInterface $peeringPhysicalInterface = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPeeringPhysicalInterface', [$peeringPhysicalInterface]);

        return parent::setPeeringPhysicalInterface($peeringPhysicalInterface);
    }

    /**
     * {@inheritDoc}
     */
    public function getPeeringPhysicalInterface()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPeeringPhysicalInterface', []);

        return parent::getPeeringPhysicalInterface();
    }

    /**
     * {@inheritDoc}
     */
    public function getRelatedInterface()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRelatedInterface', []);

        return parent::getRelatedInterface();
    }

    /**
     * {@inheritDoc}
     */
    public function statusIsConnected()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'statusIsConnected', []);

        return parent::statusIsConnected();
    }

    /**
     * {@inheritDoc}
     */
    public function statusIsDisabled()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'statusIsDisabled', []);

        return parent::statusIsDisabled();
    }

    /**
     * {@inheritDoc}
     */
    public function statusIsNotConnected()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'statusIsNotConnected', []);

        return parent::statusIsNotConnected();
    }

    /**
     * {@inheritDoc}
     */
    public function statusIsAwaitingXConnect()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'statusIsAwaitingXConnect', []);

        return parent::statusIsAwaitingXConnect();
    }

    /**
     * {@inheritDoc}
     */
    public function statusIsQuarantine()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'statusIsQuarantine', []);

        return parent::statusIsQuarantine();
    }

}