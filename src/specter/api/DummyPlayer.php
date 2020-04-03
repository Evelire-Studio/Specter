<?php

namespace specter\api;

use pocketmine\Server;
use specter\network\SpecterPlayer;
use specter\Specter;

class DummyPlayer{
    /** @var string */
    protected $name;
    /** @var Server */
    private $server;

    public function __construct(string $name, $address = "SPECTER", $port = 19133, Server $server = null){
        $this->name = $name;
        $this->server = $server === null ? Server::getInstance() : $server;

        //TODO: rewrite this exception shit
        if(!$this->getSpecter()->getInterface()->openSession($name, $address, $port)){
            throw new \Exception("Failed to open session.");
        }
    }

    /**
     * @return null|SpecterPlayer
     */
    public function getPlayer(){
        $p = $this->server->getPlayer($this->name);
        if($p instanceof SpecterPlayer){
            return $p;
        }else{
            return null;
        }
    }

    public function close(){
        $p = $this->getPlayer();
        if($p !== null){
            $p->close("", "client disconnect.");
        }
    }

    /**
     * @return null|Specter
     * @throws \Exception
     */
    protected function getSpecter(){
        $plugin = $this->server->getPluginManager()->getPlugin("Specter");
        if($plugin instanceof Specter && $plugin->isEnabled()){
            return $plugin;
        }else{
            throw new \Exception("Specter is not available.");
        }
    }
}
