<?php

namespace Sumbria\Esi;

use Sumbria\Esi\Esi;

class Industry extends Esi {

    public function getCharacterMining($character_id) {
        return $this->call('characters/' . $character_id.'/mining');
    }

}
