<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerTmrvdrk\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerTmrvdrk/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerTmrvdrk.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerTmrvdrk\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerTmrvdrk\App_KernelDevDebugContainer([
    'container.build_hash' => 'Tmrvdrk',
    'container.build_id' => 'cfb1165b',
    'container.build_time' => 1684591766,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerTmrvdrk');
