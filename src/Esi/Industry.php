<?php

namespace Sumbria\Esi;

use Sumbria\Esi\Esi;

class Industry extends Esi {

    public function getCharacterMining($character_id) {
        return $this->call('characters/' . $character_id . '/mining');
    }

    public function getCorporationObservers($corporation_id, $page = 1) {
        return $this->call('corporation/' . $corporation_id . '/mining/observers', 'get', [], true, $page);
    }

    public function getObserver($corporation_id, $observer_id, $page = 1) {
        return $this->call('corporation/' . $corporation_id . '/mining/observers/' . $observer_id, 'get', [], true, $page);
    }

}
