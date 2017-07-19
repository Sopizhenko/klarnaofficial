<?php
/**
* File: /upgrade/Upgrade-1.9.3.php
*/
function upgrade_module_1_9_3($module)
{
    $result = true;
    foreach (Tools::scandir($module->getLocalPath().'override', 'php', '', true) as $file) {
        if ($file == "controllers/front/OrderConfirmationController.php") {
            $class = basename($file, '.php');
            if (PrestaShopAutoload::getInstance()->getClassPath($class.'Core') || Module::getModuleIdByName($class)) {
                $result &= $module->addOverride($class);
            }
        }
    }
    return $result;
}
