<?php

namespace Sumbria\EveOnline;

use Sumbria\EveOnline\EveOnline;

class Character extends EveOnline {

    public function getCharacter() {
        return $this->call('verify');
    }

}
