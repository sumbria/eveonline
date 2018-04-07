<?php

namespace Sumbria\Esi;

use Sumbria\Esi\Esi;

class Character extends Esi {

    public function getCharacter($character_id) {
        return $this->call('characters/' . $character_id);
    }

}
