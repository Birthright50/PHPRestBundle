<?php
/**
 * Created by PhpStorm.
 * User: birthright
 * Date: 13.01.18
 * Time: 1:20
 */

namespace Birthright\SuperRestBundle\Service;


interface RestService
{

    function findAll();

    function find(mixed $id);

    function deleteAll();

    function delete(mixed $id);

    function update(mixed $object);

    function save(mixed $object);
}