<?php

interface Restfull
{
    public function _list();
    public function _getById($id);
    public function _create($body);
    public function _modify($body);
    public function _delete($id);

}