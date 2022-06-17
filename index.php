<?php
/**
 * Created by generate-k8s-ymal.
 * User: xiong
 * Date: 2022/5/22
 * Time: 07:26
 */

if (PHP_SAPI !== 'cli')
{
    exit("not cli");
}


$svcName = $argv[1];

require __DIR__."/generate.php";
generateYaml($svcName);