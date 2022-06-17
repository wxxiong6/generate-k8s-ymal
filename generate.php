<?php
/**
 * Created by generate-k8s-ymal.
 * User: wxiong6@gmail.com
 * Date: 2022/5/22
 * Time: 06:46
 */


const TEMPLATE_PATH = "k8s/template/base-ClusterIP.yaml";

function generateYaml($svcName) {
    $tplSvcPath = __DIR__ ."/k8s/template/base-svc.yaml";
    $tplDeployPath = __DIR__."/k8s/template/base-deploy.yaml";
    $cfgSvcPath = __DIR__."/k8s/svc/".$svcName."/".$svcName."-svc.yaml";
    $cfgDeployPath = __DIR__."/k8s/svc/".$svcName."/".$svcName."-deploy.yaml";
    if (!file_exists($tplSvcPath)) {
        exit("template ".$tplSvcPath." not exists.");
    }
    if (!file_exists($tplDeployPath)) {
        exit("template ".$tplDeployPath." not exists.");
    }
    if (!file_exists($cfgSvcPath)) {
        exit( "tplCnfPath ".$cfgSvcPath." not exists.");
    }
    if (!file_exists($cfgDeployPath)) {
        exit( "tplCnfPath ".$cfgDeployPath." not exists.");
    }
    $tplDeployContent = yaml_parse_file($tplDeployPath);
    $tplSvcContent = yaml_parse_file($tplSvcPath);

    $cfgDeployContent = yaml_parse_file($cfgDeployPath);
    $cfgSvcCnfContent = yaml_parse_file($cfgSvcPath);


    $deployContent = $tplDeployContent;
    $svcContent =  $tplSvcContent;


    merge($deployContent, $cfgDeployContent);
    merge($svcContent, $cfgSvcCnfContent);


    $deployYaml = yaml_emit($deployContent, YAML_UTF8_ENCODING);
    $deployYaml = str_replace(["---\n","...\n","emptyDir: []"], ["", "","emptyDir: {}"],
        $deployYaml);
    $svcYaml = yaml_emit($svcContent, YAML_UTF8_ENCODING);
    $svcYaml = str_replace(["...\n"], [""], $svcYaml);

    $toYamlDir = __DIR__."/yaml/".$svcName."/manifests";
    if (!file_exists($toYamlDir)) {
        mkdir($toYamlDir, 0777, true);
    }
    $toYamlPath = $toYamlDir."/".$svcName.".yaml";
    $mergeSvnAndDeployYaml = $deployYaml.$svcYaml;
    file_put_contents($toYamlPath, $mergeSvnAndDeployYaml);

}

function merge(&$array1, $array2) {
    foreach ($array2 as $k => $v) {
        if (is_array($v)) {
            merge($array1[$k], $v);
        } else {
            $array1[$k] = $v;
        }
    }
}


//['EmitDemo' => array('EmitDemo', 'yamlEmit')]

class EmitDemo {
    public $data;
    public function __construct($d) {
        $this->data = $d;
    }
    public static function yamlEmit(EmitDemo $obj) {
        var_dump($obj);exit;
        return array(
            'tag' => 'args',
            'data' => $obj->data,
        );
    }
}