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

    function find($id);

    function deleteAll();

    function delete($id);

    function update($id, $object);

    function save($object);
}